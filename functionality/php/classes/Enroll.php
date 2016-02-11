<?php

/**
 * Description of Enroll
 *
 * @author sirromas
 */
//require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/class.pdo.database.php';
require('../../../lms/config.php');
require_once($CFG->dirroot . '/user/editlib.php');
require_once($CFG->dirroot . '/user/class.pdo.database.php');

class Enroll {

    public $db;
    public $student_role = 5;
    public $signup_url;

    function __construct() {
        $this->db = new pdo_db();
        $this->signup_url='http://'.$_SERVER['SERVER_NAME'].'/lms/login/signup.php';
    }
    
    

}
