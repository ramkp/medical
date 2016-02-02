<?php

/**
 * Description of FAQ
 *
 * @author sirromas
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/class.pdo.database.php';

class FAQ {

    public $db;

    function __construct() {
        $this->db = new pdo_db();
    }

    function get_faq_page() {
        $list = "";
        $query = "select * from mdl_faq";
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
