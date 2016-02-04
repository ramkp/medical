<?php

/**
 * Description of Signup
 *
 * @author sirromas
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/class.pdo.database.php';

class Signup {

    public $db;

    function __construct() {
        $this->db = new pdo_db();
    }
    
    function enroll_user ($user) {
        $list="Successfull";
        return $list;
        
    }

}
