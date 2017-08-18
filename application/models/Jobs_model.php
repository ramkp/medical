<?php

class Jobs_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_jobs_instructor_page() {
        $list = "";
        $query = "select * from mdl_jobs where id=1";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $content = $row->content;
        }
        $list.="<div class='container-fluid'>";
        $list.="<div class='form_div'><br><span style='text-align:justify;'>$content</span></div>";
        $list.="</div>";
        return $list;
    }

    public function get_jobs_student_page() {
        $list = "";
        $query = "select * from mdl_jobs where id=2";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $content = $row->content;
        }
        $list.="<div class='container-fluid'>";
        $list.="<div class='form_div'><br>$content</div>";
        $list.="</div>";
        return $list;
    }

}
