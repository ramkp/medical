<?php

/**
 * Description of testInsert
 *
 * @author sirromas
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/class.pdo.database.php';

class testInsert {

    public $db;

    function __construct() {
        $this->db = new pdo_db();
    }

    function get_password($length = 8) {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
    }

    function test() {
        $string = $this->get_password();
        $query = "insert into mdl_test_table (link) values ('$string')";
        $result = $this->db->query($query);
        echo "<pre>";
        print_r($result);
        echo "</pre>";
    }

}

$t=new testInsert();
$t->test();
