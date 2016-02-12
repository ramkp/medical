<?php

/**
 * Description of Enroll
 *
 * @author sirromas
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/class.pdo.database.php';

class Enroll {

    public $db;
    public $student_role = 5;
    public $signup_url;

    function __construct() {
        $this->db = new pdo_db();
        $this->signup_url = 'http://' . $_SERVER['SERVER_NAME'] . '/lms/login/my_signup.php';
    }

    function get_password($length = 8) {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
    }

    function single_signup($user) {
        $encoded_user = base64_encode(json_encode($user));
        $data = array('user' => $encoded_user);
        $options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data),
            ),
        );
        $context = stream_context_create($options);
        $response = file_get_contents($this->signup_url, false, $context);
        /*
         * 
          echo "<br/><pre>";
          print_r($response);
          echo "</pre><br/>";
         * 
         */
        $this->enroll_user_to_course($user);
    }

    function enroll_user_to_course($user) {
        
    }

    function group_signup($users) {
        foreach ($users as $user) {
            $this->single_signup($user);
        }
    }

    function prepare_users_data() {
        $file = $_SESSION['file_path'];
        $users = array();
        $handle = fopen($file, "r");
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                $user = new stdClass();
                $items = explode(",", $line);
                $user->firstname = $items[0];
                $user->lastname = $items[1];
                $user->email = $items[2];
                $user->phone = $items[3];
            } // end while
        } // end if $handle
    }

}
