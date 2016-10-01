<?php

/**
 * Description of Mailer
 *
 * @author sirromas
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/class.pdo.database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/pdf/dompdf/autoload.inc.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/mailer/vendor/PHPMailerAutoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/Payment.php';

use Dompdf\Dompdf;

class Mailer {

    public $mail_smtp_host = 'mail.medical2.com';
    public $mail_smtp_port = 25;
    public $mail_smtp_user = 'info@medical2.com';
    public $mail_smtp_pwd = 'aK6SKymc';
    public $invoice_path;
    public $registration_path;
    public $db;

    function __construct() {
        $this->db = new pdo_db();
        $this->invoice_path = $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/invoices';
        $this->registration_path = $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/invoices/registrations';
    }

    function get_course_name($user) {
        $query = "select * from mdl_course where id=$user->courseid";
        //echo "Query: " . $query . "<br>";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $name = $row['fullname'];
        }
        return $name;
    }

    function get_classs_info($user) {
        $list = "";
        date_default_timezone_set('Pacific/Wallis');
        if ($user->slotid > 0) {
            $query = "select * from mdl_scheduler_slots where id=$user->slotid";
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $notes = $row['notes'];
                $start = date('m-d-Y', $row['starttime']);
                $location_arr = explode("/", $row['appointmentlocation']);
                $location = $location_arr[1] . "," . $location_arr[0];
            } // end while
            $list.="<p align='left'>Date: $start<br>Location: $location<br>Venue: $notes</p>";
        } // end if $user->slotid>0
        else {
            $list.="n/a";
        }
        return $list;
    }

    function is_group_user($user) {
        $query = "select * from mdl_groups_members where userid=$user->userid";
        $num = $this->db->numrows($query);
        return $num;
    }

    function get_group_user_num($user) {
        $query = "select * from mdl_groups_members where userid=$user->userid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $groupid = $row['groupid'];
        } // end while 
        $query = "select count(id) as total from mdl_groups_members "
                . "where groupid=$groupid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $total = $row['total'];
        } // end while
        return $total;
    }

    function get_course_cost($user) {
        $payment = new Payment();
        $late = new Late();
        $group_status = $this->is_group_user($user);
        if ($group_status == 0) {
            $course_cost_array = $payment->get_personal_course_cost($user->courseid);
            $course_cost = $course_cost_array['cost'];
        } // end if $group_status==0
        else {
            $participants = $this->get_group_user_num($user);
            $course_cost_array = $payment->get_course_group_discount($user->courseid, $participants);
            $course_cost = $course_cost_array['cost'];
        }
        $tax_status = $payment->is_course_taxable($user->courseid);
        if ($tax_status == 1) {
            $tax = $payment->get_state_taxes($user->state);
        } // end if $tax_status == 1
        else {
            $tax = 0;
        } // end else
        if ($user->slotid > 0) {
            $apply_delay_fee = $late->is_apply_delay_fee($user->courseid, $user->slotid);
            $late_fee = $late->get_delay_fee($user->courseid);
        }
        $grand_total = $course_cost;
        if ($tax_status == 1) {
            $grand_total = $grand_total + round(($course_cost * $tax) / 100);
        }
        if ($apply_delay_fee) {
            $grand_total = $grand_total + $late_fee;
        }
        return $grand_total;
    }

    function get_account_confirmation_message($user, $printed_data = null) {
        $list = "";
        $course_name = $this->get_course_name($user);
        $class_info = $this->get_classs_info($user);
        $course_cost = $this->get_course_cost($user);
        $list.= "<!DOCTYPE HTML><html><head><title>Account Confirmation</title>";
        $list.="</head>";
        $list.="<body><br/><br/><br/><br/>";
        $list.="<div class='datagrid'>            
        <table style='table-layout: fixed;' width='360'>
        <thead>";

        /*
          if ($printed_data == NULL) {
          $list.="<tr>";
          $list.="<th colspan='2' align='left'><img src='http://medical2.com/assets/logo/5.png' width='360' height='90'></th>";
          4  $list.="</tr>";
          } // end if $printed_data == NULL
         */

        $list.="</thead>
        <tbody>
        <tr style='background-color:#F5F5F5;'>
        <td>First name</td><td>$user->first_name</td>
        </tr>
        <tr>
        <td>Last name</td><td>$user->last_name</td>
        </tr>
        <tr style='background-color:#F5F5F5;'>
        <td>Email</td><td>$user->email</td>
        </tr>
        <tr>
        <td>Phone</td><td>$user->phone</td>
        </tr>
        <tr style='background-color:#F5F5F5;'>
        <td>Username</td><td>$user->email</td>
        </tr>
        <tr >
        <td>Password</td><td>$user->pwd</td>
        </tr>
        <tr style='background-color:#F5F5F5;'>
        <td>Address</td><td>$user->addr</td>
        </tr>
        <tr >
        <td>City</td><td>$user->city</td>
        </tr>        
        <tr style='background-color:#F5F5F5;'>
        <td>State</td><td>$user->state</td>
        </tr>
        <tr >
        <td>Zip</td><td>$user->zip</td>
        </tr>
        <tr style='background-color:#F5F5F5;'>
        <td>Applied Progarm</td><td>$course_name</td>
        </tr>
        <tr >
        <td>Program fee</td><td>$$course_cost</td>
        </tr>";

        if (property_exists($user, 'payment_amount')) {
            date_default_timezone_set("America/New_York");
            $date = date('m-d-Y h:i:s', time());

            $list.="<tr style='background-color:#F5F5F5;'>
            <td>Payment status: </td><td>Paid by card: $$user->payment_amount</td>
            </tr>";

            $list.="<tr>";
            $list.="<td>Card Holder:</td><td>$user->card_holder</td>";
            $list.="</tr>";

            $list.="<tr style='background-color:#F5F5F5;'>";
            $list.="<td>Credit Card:</td><td>XXXX XXXX XXXX XXXX</td>";
            $list.="</tr>";

            $list.="<tr>";
            $list.="<td>Expiry date: </td><td>$user->card_month$user->card_year</td>";
            $list.="</tr>";

            $list.="<tr style='background-color:#F5F5F5;'>";
            $list.="<td>Order Date:</td><td>$date</td>";
            $list.="</tr>";
        } // end if $payment_amount != null

        $list.="<tr style='background-color:#F5F5F5;'>
        <td>Class info</td><td>$class_info</td>
        </tr>
        </tbody>
        </table>
        </div>";
        $list.="<p>If you need assistance please contact us by email <a href='mailto:help@medical2.com'>help@medical2.com</a> or call us 877-741-1996</p>";
        $list.="</body></html>";
        return $list;
    }

    function create_registration_data_details($user) {
        $dompdf = new Dompdf();
        $message = $this->get_account_confirmation_message($user, true);
        $dompdf->loadHtml($message);

        $dompdf->setPaper('A4', 'portrait');

        $dompdf->render();
        $output = $dompdf->output();

        $file_path = $this->registration_path . "/$user->email.pdf";
        file_put_contents($file_path, $output);
    }

    function send_account_confirmation_message($user) {
        $subject = "Medical2 - registration confirmation";
        $message = $this->get_account_confirmation_message($user);
        $recipient = $user->email;
        $payment_amount = (property_exists($user, 'payment_amount') == true) ? $user->payment_amount : null;
        $this->send_signup_confirmation_email($subject, $message, $recipient, $payment_amount);
        $this->create_registration_data_details($user);
    }

    function send_signup_confirmation_email($subject, $message, $recipient, $payment_amount) {

        /* We send confirmation email only if payment is received */

        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->Host = $this->mail_smtp_host;
        $mail->SMTPAuth = true;
        $mail->Username = $this->mail_smtp_user;
        $mail->Password = $this->mail_smtp_pwd;
        $mail->SMTPSecure = 'tls';
        $mail->Port = $this->mail_smtp_port;

        $mail->setFrom($this->mail_smtp_user, 'Medical2');
        if ($payment_amount != null) {
            $mail->addAddress($recipient);
            $mail->AddCC('info@medical2.com');
            $mail->AddCC('sirromas@gmail.com'); 
            $mail->AddBCC('help@medical2.com');
            $mail->addReplyTo($this->mail_smtp_user, 'Medical2');
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $message;
            if (!$mail->send()) {
                echo 'Message could not be sent.';
                echo 'Mailer Error: ' . $mail->ErrorInfo;
            } // end if !$mail->send()        
            else {
                //echo 'Message has been sent to ' . $recipient;
            }
        } // end if $payment_amount != null
    }

    function get_payment_confirmation_message($payment, $group = null, $free = null) {
        $list = "";
        $list.="<html><body>";
        $list.="<br/><p>Dear $payment->card_holder!</p>";
        if ($group == null) {
            if ($free == null) {
                $list.="<p>Payment of $$payment->sum has been received. Thank you. Your account is active now.</p>";
            } // end if $free == null
            else {
                $list.="<p>You got free access to the system. Your account is active now.</p>";
            }
        } // end if $group==null
        else {
            if ($free == null) {
                $list.="<p>Payment of $$payment->sum has been received. Thank you. All your group accounts are active now.</p>";
            } // end if $free == null
            else {
                $list.="<p>Your group members got free access to the system. All your group accounts are active now.</p>";
            } // end else
        } // end else
        $list.="<p>If you need help, please contact us via email $this->mail_smtp_user</p>";
        $list.="<p>Best regards,</p>";
        $list.="<p>Support team.</p>";
        $list.="</body></html>";
        return $list;
    }

    function get_renew_fee() {
        $query = "select * from mdl_renew_fee";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $fee = $row['fee_sum'];
        }
        return $fee;
    }

    function get_renew_certificate_message($user) {
        $list = "";
        $course_name = $this->get_course_name($user);
        $renew_fee = $this->get_renew_fee();
        $list.= "<!DOCTYPE HTML><html><head><title>Certificate Renew Confirmation</title>";
        $list.="</head>";
        $list.="<body><br/><br/><br/><br/>";
        $list.="<div class='datagrid'>            
        <table style='table-layout: fixed;' width='360'>
        <thead>";

        //$list.="<tr>";
        //$list.="<th colspan='2' align='left'><img src='http://medical2.com/assets/logo/5.png' width='360' height='90'></th>";
        //$list.="</tr>";

        $list.="</thead>
        <tbody>
        
        <tr style='background-color:#F5F5F5;'>
        <td>First name</td><td>$user->first_name</td>
        </tr>
        
        <tr>
        <td>Last name</td><td>$user->last_name</td>
        </tr>
        
        <tr style='background-color:#F5F5F5;'>
        <td>Email</td><td>$user->email</td>
        </tr>
        
        <tr>
        <td>Phone</td><td>$user->phone</td>
        </tr>
        
        <tr style='background-color:#F5F5F5;'>
        <td>Applied Progarm</td><td>$course_name</td>
        </tr> 
        
        <tr>
        <td>Amount paid</td><td>$$renew_fee</td>
        </tr> 
        
        <tr>
        <td colspan='2' align='left'>&nbsp;</td>
        </tr> 
        
        <tr>
        <td colspan='2' align='left'>This certification has been renewed</td>
        </tr> 
        
        </table>";
        return $list;
    }

    function send_payment_confirmation_message($payment, $group = null, $free = null) {
        $renew_fee = $this->get_renew_fee();
        if ($payment->sum != $renew_fee) {
            $this->send_account_confirmation_message($payment); // send user info to info@medical2.com        
            $subject = "Medical2 - payment confirmation";
            $message = $this->get_payment_confirmation_message($payment, $group, $free);
            $recipient = $payment->bill_email;
            $this->send_email($subject, $message, $recipient);
        } // end if $payment->sum!=$renew_fee
        else {
            $subject = "Medical2 - Certificate Renew Payment";
            $message = $this->get_renew_certificate_message($payment);
            $recipient = $payment->bill_email;
            $this->send_email($subject, $message, $recipient);

            // Send copy of message to info@medical2.com and sirromas@gmail.com
            $recipient = 'info@medical2.com';
            $this->send_email($subject, $message, $recipient);

            $recipient = 'sirromas@gmail.com';
            $this->send_email($subject, $message, $recipient);

            $recipient = 'help@medical2.com';
            $this->send_email($subject, $message, $recipient);
        } // end else
    }

    function get_payment_group_confirmation_message($user) {
        $list = "";
        $list.="<html><body>";
        $list.="<br/><p>Dear $user->firstname $user->lastname!</p>";
        $list.="<p>Payment for your group membership has been received. Thank you. Your account is active now.</p>";
        $list.="<p>If you need help, please contact us via email $this->mail_smtp_user</p>";
        $list.="<p>Best regards,</p>";
        $list.="<p>Support team.</p>";
        $list.="</body></html>";
        return $list;
    }

    function send_group_payment_confirmation_message($user) {
        $subject = "Medical2 - payment confirmation";
        $message = $this->get_payment_group_confirmation_message($user);
        $recipient = $user->username;
        $this->send_email($subject, $message, $recipient);
    }

    function send_group_reply_message($reply, $recipient) {
        $subject = "Medical2 - Private Group Request";
        $this->send_email($subject, $reply, $recipient);
    }

    function get_invoice_message($user, $gowner = null) {
        $list = "";
        $list.="<html><body>";
        $list.="<br/><p>Dear $user->first_name $user->last_name!</p>";
        $list.="<p>Thank you for signup.</p>";
        $list.="<p>Please find out invoice attached to make a payment.</p>";
        $list.="<p>If you need help, please contact us via email $this->mail_smtp_user</p>";
        $list.="<p>Best regards,</p>";
        $list.="<p>Support team.</p>";
        $list.="</body></html>";
        return $list;
    }

    function send_invoice($user, $gowner = null) {
        //print_r($user);
        $subject = "Medical2 - invoice";
        $message = $this->get_invoice_message($user, $gowner);
        $recipient = $user->email;
        $this->send_email($subject, $message, $recipient, $user->invoice);
    }

    function send_certificate($user) {
        /*
          echo "<pre>";
          print_r($user);
          echo "<pre>";
         */
        $subject = "Medical2 - Certificate";
        $list = "";
        $list.="<html><body>";
        $list.="<br/><p>Dear $user->firstname $user->lastname!</p>";
        $list.="<p>Congratulations! You successfully passed selected program!</p>";
        $list.="<p>Please find out certificate attached.</p>";
        $list.="<p>If you need help, please contact us via email $this->mail_smtp_user</p>";
        $list.="<p>Best regards,</p>";
        $list.="<p>Support team.</p>";
        $list.="</body></html>";
        $this->send_email($subject, $list, $user->email, $user->path, 1);
    }

    function send_email($subject, $message, $recipient, $attachment = null, $certificate = null) {

        $mail = new PHPMailer;
        //$recipient = 'sirromas@gmail.com'; // temp workaround
        //$mail->SMTPDebug = 3;                                

        $mail->isSMTP();
        $mail->Host = $this->mail_smtp_host;
        $mail->SMTPAuth = true;
        $mail->Username = $this->mail_smtp_user;
        $mail->Password = $this->mail_smtp_pwd;
        $mail->SMTPSecure = 'tls';
        $mail->Port = $this->mail_smtp_port;

        $mail->setFrom($this->mail_smtp_user, 'Medical2');
        $mail->addAddress($recipient);
        $mail->addReplyTo($this->mail_smtp_user, 'Medical2');

        if ($attachment != null) {
            $invoice = $this->invoice_path . "/$attachment.pdf";
            $mail->addAttachment($invoice, "invoice.pdf");
        } // end if $attachment != null

        if ($attachment != null && $certificate != null) {
            $mail->addAttachment($attachment, "certificate.pdf");
        } // end if $attachment != null && $certificate!=null
        $mail->isHTML(true);

        $mail->Subject = $subject;
        $mail->Body = $message;

        if (!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } // end if !$mail->send()        
        else {
            //echo 'Message has been sent to ' . $recipient;
        }
    }

    function send_password_recovery_email($user, $supportuser, $subject, $message) {

        $mail = new PHPMailer;
        //$user->email = 'sirromas@gmail.com'; // temp workaround
        //$mail->SMTPDebug = 3;                                

        $mail->isSMTP();
        $mail->Host = $this->mail_smtp_host;
        $mail->SMTPAuth = true;
        $mail->Username = $this->mail_smtp_user;
        $mail->Password = $this->mail_smtp_pwd;
        $mail->SMTPSecure = 'tls';
        $mail->Port = $this->mail_smtp_port;

        $mail->setFrom($this->mail_smtp_user, 'Medical2');
        $mail->addAddress($user->email);
        $mail->addReplyTo($this->mail_smtp_user, 'Medical2');

        $mail->isHTML(true);

        $mail->Subject = $subject;
        $mail->Body = $message;

        if (!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } // end if !$mail->send()        
        else {
            //echo 'Message has been sent to ' . $user->email;
        }
    }

    function send_any_invoice($client, $email, $invoice_file_name) {
        $mail = new PHPMailer;
        $recipient = $email;
        //$recipient = 'sirromas@gmail.com'; // temp workaround

        $message = "";
        $message.="<html>";
        $message.="<body>";
        $message.="<p align='center'>Dear $client!</p>";
        $message.="<p align='center'>Invoice is in attachment.</p>";
        $message.="<p>If you need assistance please contact us by email <a href='mailto:help@medical2.com'>help@medical2.com</a> or by phone 877-741-1996</p>";
        $message.="<p>Best regards,</p>";
        $message.="<p>Medical2 team</p>";
        $message.="</body></html>";

        $mail->isSMTP();
        $mail->Host = $this->mail_smtp_host;
        $mail->SMTPAuth = true;
        $mail->Username = $this->mail_smtp_user;
        $mail->Password = $this->mail_smtp_pwd;
        $mail->SMTPSecure = 'tls';
        $mail->Port = $this->mail_smtp_port;

        $mail->setFrom($this->mail_smtp_user, 'Medical2');
        $mail->addAddress($recipient);
        $mail->addReplyTo($this->mail_smtp_user, 'Medical2');

        $mail->addAttachment($invoice_file_name, "invoice.pdf");

        $mail->isHTML(true);

        $mail->Subject = 'Medical2 - Invoice';
        $mail->Body = $message;

        if (!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } // end if !$mail->send()
        else {
            //echo 'Message has been sent to ' . $recipient;
        }
    }

    function send_contact_request($message) {

        $mail = new PHPMailer;
        $addressA = 'info@medical2.com';
        $addressB = 'help@medical2.com';
        $addressC = 'sirromas@gmail.com';

        $mail->isSMTP();
        $mail->Host = $this->mail_smtp_host;
        $mail->SMTPAuth = true;
        $mail->Username = $this->mail_smtp_user;
        $mail->Password = $this->mail_smtp_pwd;
        $mail->SMTPSecure = 'tls';
        $mail->Port = $this->mail_smtp_port;

        $mail->setFrom($this->mail_smtp_user, 'Medical2');
        $mail->addAddress($addressA);
        $mail->addAddress($addressB);
        $mail->addAddress($addressC);
        $mail->addReplyTo($this->mail_smtp_user, 'Medical2');

        $mail->isHTML(true);
        $mail->Subject = 'Medical2 - Contact Page Request';
        $mail->Body = $message;

        if (!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } // end if !$mail->send()
        else {
            //echo 'Message has been sent to ' . $recipient;
        }
    }

}
