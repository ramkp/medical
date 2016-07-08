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

    public function get_faq_categories() {
        $list = "";
        $list.="<select id='faq_cat' style='width:375px;'>";
        $query = "select * from mdl_faq_category order by id";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $list.="<option value='$row->id'>$row->name</option>";
        }
        $list.="</select>";
        return $list;
    }

    public function get_initial_faq() {
        $list = "";
        $query = "select * from mdl_faq_old where catid=1";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span1' style='font-weight:bold;'>Q:</span><span class='span8'>".strip_tags($row->q)."</span>";
            $list.="</div>";

            $list.="<div class='container-fluid'>";
            $list.="<span class='span1' style='font-weight:bold;'>A:</span><span class='span8'>($row->a)</span>";
            $list.="</div>";

            $list.="<div class='container-fluid'>";
            $list.="<span class='span8'><hr/></span>";
            $list.="</div>";
        } // end foreach

        return $list;
    }

    public function get_faq_by_category_id($id) {
        $list = "";
        $query = "select * from mdl_faq_old where catid=$id";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $list.="<div class='container-fluid' >";
            $list.="<span class='span1' style='font-weight:bold;'>Q:</span><span class='span8'>$row->q</span>";
            $list.="</div>";

            $list.="<div class='container-fluid'>";
            $list.="<span class='span1' style='font-weight:bold;'>A:</span><span class='span8'>$row->a</span>";
            $list.="</div>";

            $list.="<div class='container-fluid'>";
            $list.="<span class='span9'><hr/></span>";
            $list.="</div>";
        } // end foreach

        return $list;
    }

    public function get_faq_page() {
        $cat = $this->get_faq_categories();
        $q = $this->get_initial_faq();
        $list = "";
        $list.="<br/><div class='container-fluid' style='text-align:center;'>";
        $list.="<span class='span9'>$cat</span>";
        $list.="</div>";
        
        $list.="<div class='container-fluid' style='text-align:center;display:none;' id='ajax_loader'>";
        $list.="<span class='span12'><img src='/assets/img/ajax.gif'></span>";
        $list.="</div>";

        $list.="<br/><div class='container-fluid' style='text-align:left;' id='q_container'>";
        $list.="<span class='span10'>$q</span>";
        $list.="</div>";

        return $list;
    }

}
