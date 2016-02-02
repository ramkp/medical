<?php

/**
 * Description of Testimonial
 *
 * @author sirromas
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/class.pdo.database.php';

class Testimonial {

    public $db;

    function __construct() {
        $this->db = new pdo_db();
    }

    function get_testimonial_page() {
        $list = "";
        $query = "select * from mdl_testimonial";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $content = $row['content'];
        }
        $list.="<div class='container-fluid'>";
        $list.="<span class='span9'>$content</span>";
        $list.="</div>";
        return $list;
    }

}
