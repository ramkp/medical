<?php

/**
 * Description of Register
 *
 * @author sirromas
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/class.pdo.database.php';

class Register {

    public $db;

    function __construct() {
        $this->db = new pdo_db();
    }

}
