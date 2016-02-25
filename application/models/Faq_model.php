<?php

/**
 * Description of Faq_model
 *
 * @author sirromas
 */
class Faq_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_faq_page() {
        $list = "";
        $query = "select * from mdl_faq";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $content = $row->content;
        }
        $list.="<br/><div class='container-fluid'>";
        $list.="<span class='span9'>$content</span>";
        $list.="</div>";
        return $list;
    }

}
