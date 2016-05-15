<?php

/**
 * Description of Mailer
 *
 * @author sirromas
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/mailer/vendor/PHPMailerAutoload.php';

class Mailer {
    
    public $mail_smtp_host = 'mail.medical2.com';
    public $mail_smtp_port = 25;
    public $mail_smtp_user = 'info@medical2.com';
    public $mail_smtp_pwd = 'aK6SKymc';
    public $invoice_path;
    

    function __construct() {
        $this->invoice_path = $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/invoices';
    }

    function get_account_confirmation_message($user) {
        $list = "";
        $list.="<html><body>";
        $list.="<br/><p>Dear $user->first_name $user->last_name!</p>";
        $list.="<p>Thank you for signup!</p>";
        $list.="<p>Your username: $user->email <br/>";
        $list.="Your password: $user->pwd</p>";
        $list.="<p>Please be aware your account is not active untill payment will be received.</p>";
        $list.="<p>If you need help, please contact us via email $this->mail_smtp_user</p>";
        $list.="<p>Best regards,</p>";
        $list.="<p>Support team.</p>";
        $list.="</body></html>";
        return $list;
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
                $list.="<p>Your group membes got free access to the system. All your group accounts are active now.</p>";
            } // end else
        } // end else
        $list.="<p>If you need help, please contact us via email $this->mail_smtp_user</p>";
        $list.="<p>Best regards,</p>";
        $list.="<p>Support team.</p>";
        $list.="</body></html>";
        return $list;
    }

    function send_account_confirmation_message($user) {
        $subject = "Medical2 Career College - registration confirmation";
        $message = $this->get_account_confirmation_message($user);
        $recipient = $user->email;
        $this->send_email($subject, $message, $recipient);
    }

    function send_payment_confirmation_message($payment, $group = null, $free = null) {
        $subject = "Medical2 Career College - payment confirmation";
        $message = $this->get_payment_confirmation_message($payment, $group, $free);
        $recipient = $payment->bill_email;
        $this->send_email($subject, $message, $recipient);
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
        $subject = "Medical2 Career College - payment confirmation";
        $message = $this->get_payment_group_confirmation_message($user);
        $recipient = $user->username;
        $this->send_email($subject, $message, $recipient);
    }

    function send_group_reply_message($reply, $recipient) {
        $subject = "Medical2 Career College - Private Group Request";
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
    	$subject = "Medical2 Career College - invoice";
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
        $subject = "Medical2 Career College - Certificate";
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
        $mail->addReplyTo($this->mail_smtp_user, 'Medical2 Career College');

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
    
    function send_password_recovery_email ($user, $supportuser, $subject, $message) {
        
        $mail = new PHPMailer;
        $user->email = 'sirromas@gmail.com'; // temp workaround
        //$mail->SMTPDebug = 3;                                

        $mail->isSMTP();
        $mail->Host = $this->mail_smtp_host;
        $mail->SMTPAuth = true;
        $mail->Username = $this->mail_smtp_user;
        $mail->Password = $this->mail_smtp_pwd;
        $mail->SMTPSecure = 'tls';
        $mail->Port = $this->mail_smtp_port;

        $mail->setFrom($this->mail_smtp_user, 'Medical2 Career College');
        $mail->addAddress($user->email);
        $mail->addReplyTo($this->mail_smtp_user, 'Medical2 Career College');
        
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

}
