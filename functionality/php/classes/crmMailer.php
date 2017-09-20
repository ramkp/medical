<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/mailer/vendor/PHPMailerAutoload.php';

class crmMailer {

    public $mail_smtp_host = 'mail.medical2.com';
    public $mail_smtp_port = 25;
    public $mail_smtp_user = 'crm@medical2.com';
    public $mail_smtp_pwd = 'aK6SKymc*';

    function send_moodle_account_info($user) {
        $list = "";
        $subject = "E2success.com - Moodle account have been created";

        $list.="<html>";
        $list.="<body>";

        $list.="<table>";
        /*
          $list.="<tr>";
          $list.="<td colspan='2' align='center'><img src='http://academy.e2success.com/assets/logo.png' width='227px;' height='78px;'></td>";
          $list.="</tr>";
         */
        $list.="<tr>";
        $list.="<td style='padding:15px;' colspan='2' align='center'>Dear $user->first_name $user->last_name!</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td style='padding:15px;' colspan='2' align='center'>Your Moodle account have been created.</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td style='padding:15px;'>Your username</td>";
        $list.="<td style='padding:15px;'>$user->email</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td style='padding:15px;'>Your password</td>";
        $list.="<td style='padding:15px;'>$user->pwd</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td style='padding:15px;' colspan='2'>Best regards, <br> Support team</td>";
        $list.="</tr>";

        $list.="</table>";

        $mail = new PHPMailer;
        $addressC = 'sirromas@gmail.com';

        $mail->isSMTP();
        $mail->Host = $this->mail_smtp_host;
        $mail->SMTPAuth = true;
        $mail->Username = $this->mail_smtp_user;
        $mail->Password = $this->mail_smtp_pwd;
        $mail->SMTPSecure = 'tls';
        $mail->Port = $this->mail_smtp_port;

        $mail->setFrom($this->mail_smtp_user, 'E2success.com');
        $mail->addAddress($user->email);
        $mail->addCC($addressC);

        $mail->addReplyTo($this->mail_smtp_user, 'E2success.com');

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $list;

        if (!$mail->send()) {
            return false;
        } // end if !$mail->send()
        else {
            return true;
        }
    }

}
