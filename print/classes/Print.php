<?php

require_once ('/home/cnausa/public_html/class.pdo.database.php');

class Printer {

    public $db;

    function __construct() {
        $db = new pdo_db();
        $this->db = $db;
    }

    function get_print_job() {
        $query = "select * from mdl_print_job";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $students = $row['students'];
        }
        return $students;
    }

}
