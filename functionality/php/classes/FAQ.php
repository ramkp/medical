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

    function get_faq_by_category_id($id) {
        $list = "";
        $query = "select * from mdl_faq_old where catid=$id";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $list.="<div class='container-fluid'>";
            $list.="<span class='span1' style='font-weight:bold;'>Q:</span><span class='span9'>" . $row['q'] . "</span>";
            $list.="</div>";

            $list.="<div class='container-fluid'>";
            $list.="<span class='span1' style='font-weight:bold;'>A:</span><span class='span9'>" . $row['a'] . "</span>";
            $list.="</div>";

            $list.="<div class='container-fluid'>";
            $list.="<span class='span9'><hr/></span>";
            $list.="</div>";
        } // end foreach

        return $list;
    }

}
