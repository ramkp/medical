<?php

/**
 * Description of Certificates
 *
 * @author sirromas
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/class.pdo.database.php';

class Certificates {

    public $db;

    function __construct() {
        $this->db = new pdo_db();
    }
    
    function get_certificate_verification_form () {
        $list='';
        
        
    }

}
