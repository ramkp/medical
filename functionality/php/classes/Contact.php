<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/class.pdo.database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/Mailer.php';

class Contact {

    public $db;

    function __construct() {
        $this->db = new pdo_db();
    }

    function post_contact_form($firstname, $lastname, $email, $phone, $user_message) {

        /*
          $query="insert into mdl_contact (fname,lname,email,phone,message,added)
          values('".$firstname."',
          '".$lastname."',
          '".$email."',
          '".$phone."',
          '".$message."', '".time()."')";
          $this->db->query($query);
         */

        $message = "";

        $message.="<html>";
        $message.="<body>";
        $message.="<table align='center'>";

        $message.="<tr>";
        $message.="<td style='padding:15px;'>Firstname</td><td style='padding:15px;'>$firstname</td>";
        $message.="</tr>";

        $message.="<tr>";
        $message.="<td style='padding:15px;'>Lastname</td><td style='padding:15px;'>$lastname</td>";
        $message.="</tr>";

        $message.="<tr>";
        $message.="<td style='padding:15px;'>Email</td><td style='padding:15px;'>$email</td>";
        $message.="</tr>";

        $message.="<tr>";
        $message.="<td style='padding:15px;'>Phone</td><td style='padding:15px;'>$phone</td>";
        $message.="</tr>";

        $message.="<tr>";
        $message.="<td style='padding:15px;' colspan='2'>$user_message</td>";
        $message.="</tr>";


        $message.="</table>";
        $message.="</html>";
        $message.="</body>";

        $mailer = new Mailer();
        $mailer->send_contact_request($message);

        $list = "Your message is sent. Thank you";
        return $list;
    }

}
