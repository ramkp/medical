<?php

/**
 * Description of Mailer
 *
 * @author sirromas
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/mailer/vendor/PHPMailerAutoload.php';

class Mailer {

    public $mail_smtp_host = 'mail.cnausa.com';
    public $mail_smtp_port = 25;
    public $mail_smtp_user = 'support@cnausa.com';
    public $mail_smtp_pwd = 'aK6SKymc*';

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

    function get_payment_confirmation_message($payment) {
        $list = "";
        $list.="<html><body>";
        $list.="<br/><p>Dear $payment->card_holder!</p>";
        $list.="<p>Payment of $$payment->sum has been received. Thank you. Your account now active.</p>";        
        $list.="<p>If you need help, please contact us via email $this->mail_smtp_user</p>";
        $list.="<p>Best regards,</p>";
        $list.="<p>Support team.</p>";
        $list.="</body></html>";
        return $list;
    }

    function send_account_confirmation_message($user) {
        $subject = "Medical2 Institute - registration confirmation";
        $message = $this->get_account_confirmation_message($user);
        $recipient = $user->email;
        $this->send_email($subject, $message, $recipient);
    }

    function send_payment_confirmation_message($payment) {
        $subject = "Medical2 Institute - payment confirmation";
        $message = $this->get_payment_confirmation_message($payment);
        $recipient = $user->email;
        $this->send_email($subject, $message, $recipient);
    }

    function send_email($subject, $message, $recipient) {

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

        $mail->setFrom($this->mail_smtp_user, 'Support team');
        $mail->addAddress($recipient);
        $mail->addReplyTo($this->mail_smtp_user, 'Support team');

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
