<?php

/**
 * Description of Enroll
 *
 * @author sirromas
 */

session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/class.pdo.database.php';

class Enroll {

    public $db;
    public $student_role = 5;
    public $signup_url;

    function __construct() {
        $this->db = new pdo_db();
        $this->signup_url = 'http://' . $_SERVER['SERVER_NAME'] . '/lms/login/signup.php';
    }

    function single_signup($user) {
        $request_url = $this->signup_url . "?user=$user";
        $response = file_get_contents($request_url);
        echo "<br/><pre>";
        print_r($response);
        echo "</pre><br/>";
    }

    function group_signup($users) {
        foreach ($users as $user) {
            $this->single_signup($user);
        }
    }
    
        function prepare_users_data() {
        $file=$_SESSION['file_path'];
        $users = array();
        $handle = fopen($file, "r");
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                $user = new stdClass();
                $items = explode(",", $line);
                $user->firstname=$items[0];
                $user->lastname=$items[1];
                $user->email=$items[2];
                $user->phone=$items[3];
            } // end while
        } // end if $handle
    }


}
