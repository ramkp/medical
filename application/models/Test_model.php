<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Test_model
 *
 * @author sirromas
 */
class Test_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    function get_testimonial_page() {
        $list = "";
        $query = "select * from mdl_testimonial";
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
