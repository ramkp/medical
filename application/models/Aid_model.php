<?php

class Aid_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_financial_aid_page() {
        $list = "";
        $query = "select * from mdl_financial_aid where id=1";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $page = $row->content;
        }
        $list.="<div class='row-fluid'>";
        $list.="<div class='form_div'><br><span style='text-align:justify'>$page</span></div>";
        $list.="</div>";
        return $list;
    }

    public function get_financial_aid_workshop_page() {
        $list = "";
        $query = "select * from mdl_financial_aid where id=1";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $page = $row->content;
        }
        $list.="<div class='row-fluid'>";
        $list.="<div class='form_div'><br><span style='text-align:justify'>$page</span></div>";
        $list.="</div>";
        return $list;
    }

    public function get_financial_aid_college_page() {
        $list = "";
        $query = "select * from mdl_financial_aid where id=2";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $page = $row->content;
        }
        $list.="<div class='row-fluid'>";
        $list.="<div class='form_div'><br><span style='text-align:justify'>$page</span></div>";
        $list.="</div>";
        return $list;
    }

}
