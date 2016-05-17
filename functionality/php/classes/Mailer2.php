<?php

/**
 * Description of Mailer2
 *
 * @author sirromas
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/class.pdo.database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/pdf/dompdf/autoload.inc.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/mailer/vendor/PHPMailerAutoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/Payment.php';

class Mailer2 {

    public $mail_smtp_host = 'mail.medical2.com';
    public $mail_smtp_port = 25;
    public $mail_smtp_user = 'info@medical2.com';
    public $mail_smtp_pwd = 'aK6SKymc';
    public $invoice_path;
    public $db;

    function __construct() {
        $this->db = new pdo_db();
        $this->invoice_path = $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/invoices';
    }

    function get_course_name($user) {
        $query = "select * from mdl_course where id=$user->courseid";
        echo "Query: ".$query."<br>";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $name = $row['fullname'];
        }
        return $name;
    }

    function get_classs_info($user) {
        $list = "";
        if ($user->slotid > 0) {
            $query = "select * from mdl_scheduler_slots where id=$user->slotid";
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $notes = $row['notes'];
                $start=date('d-m-Y',$row['starttime']);
                $location_arr=explode("/", $row['appointmentlocation']);
                $location=$location_arr[1].",".$location_arr[0];
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
            $course_cost=$course_cost_array['cost'];
        } // end if $group_status==0
        else {
            $participants = $this->get_group_user_num($user);
            $course_cost_array = $payment->get_course_group_discount($user->courseid, $participants);
            $course_cost=$course_cost_array['cost'];
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

    function get_account_confirmation_message($user) {
        $list = "";
        $course_name = $this->get_course_name($user);
        $class_info = $this->get_classs_info($user);
        $course_cost = $this->get_course_cost($user);        
        $list.= "<!DOCTYPE HTML><html><head><title>Account Confirmation</title>";        
        $list.="</head>";
        $list.="<body><br/><br/><br/><br/>";
        $list.="<div class='datagrid'>            
        <table style='table-layout: fixed;' width='360'>
        <thead><tr>
        <th colspan='2' align='left'><img src='http://medical2.com/assets/logo/5.png' width='360' height='90'></th>
        </tr>
        </thead>
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
        <td>Applied Progarm</td><td>$course_name</td>
        </tr>
        <tr style='background-color:#F5F5F5;'>
        <td>Program fee</td><td>$$course_cost</td>
        </tr>
        <tr style='background-color:#F5F5F5;'>
        <td>Class info</td><td>$class_info</td>
        </tr>
        </tbody>
        </table>
        </div>";
        $list.="<p>If you need assistance please contact us by email <a href='mailto:help@medical2.com'>help@medical2.com</a> or call us 877-741-1996</p>";
        $list.="</body></html>";
        return $list;
    }

    function send_account_confirmation_message($user) {        
        $subject = "Medical2 Career College - registration confirmation";
        $message = $this->get_account_confirmation_message($user);
        $recipient = $user->email;
        $this->send_signup_confirmation_email($subject, $message, $recipient);
    }

    function send_signup_confirmation_email($subject, $message, $recipient) {
        $mail = new PHPMailer;
        $recipient = 'sirromas@gmail.com'; // temp workaround
        //$mail->SMTPDebug = 3;                                

        $mail->isSMTP();
        $mail->Host = $this->mail_smtp_host;
        $mail->SMTPAuth = true;
        $mail->Username = $this->mail_smtp_user;
        $mail->Password = $this->mail_smtp_pwd;
        $mail->SMTPSecure = 'tls';
        $mail->Port = $this->mail_smtp_port;

        $mail->setFrom($this->mail_smtp_user, 'Medical2 Career College');
        $mail->addAddress($recipient);
        //$mail->AddCC('info@medical2.com');
        //$mail->AddBCC('help@medical2.com');
        $mail->addReplyTo($this->mail_smtp_user, 'Medical2 Career College');

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

}