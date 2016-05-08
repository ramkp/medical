<?php

/**
 * Description of Gallery_model
 *
 * @author sirromas
 */
class Gallery_model extends CI_Model {

    public $gl;

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    function get_states_list($upload = false) {
        $list = "";
        $query = "select * from mdl_states order by state";
        if ($upload == false) {
            $list.="<select id='state' style=''>";
        } // end if $upload==false
        else {
            $list.="<select id='upload_state' style='width:145px;'>";
        } // end else 
        $list.="<option value='0' selected>All states</option>";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $list.="<option value='" . $row->id . "'>" . $row->state . "</option>";
        }
        $list.="</select>";
        return $list;
    }

    function get_month_list($upload = false) {
        $list = "";
        if ($upload == false) {
            $list.="<select id='month' style=''>";
        } // end if $upload == false
        else {
            $list.="<select id='upload_month' style=''>";
        } // end else 
        $list.="<option value='0' selected>Month</option>";
        for ($i = 1; $i <= 12; $i++) {
            $list.="<option value='$i'>$i</option>";
        }
        $list.="</select>";
        return $list;
    }

    function get_year_list($upload = false) {
        $list = "";
        if ($upload == false) {
            $list.="<select id='year'>";
        } // end if $upload == false
        else {
            $list.="<select id='upload_year'>";
        } // end else 
        $list.="<option value='0' selected>Year</option>";
        for ($i = 2014; $i <= 2027; $i++) {
            $list.="<option value='$i'>$i</option>";
        }
        $list.="</select>";
        return $list;
    }

    function get_toolbar() {
        $list = "";
        $states = $this->get_states_list();
        $month = $this->get_month_list();
        $year = $this->get_year_list();
        $list.="<div class='row'>";
        $list.="<table align='center'><tr><td>";
        $list.= "<span class='span2' style=''>$states</span>";
        $list.= "<span class='span1' style=''>$month</span>";
        $list.= "<span class='span1' style=''>$year</span>";
        $list.= "<span class='span1' style=''><button type='button' class='btn btn-primary' id='filter'>Show</button></span>";
        $list.="</tr></td></table></div>";
        return $list;
    }
    
    function get_image_sql_criteria($state = null, $month = null, $year = null) {
        if ($state == null && $month == null && $year == null) {
            $query = "select * from mdl_gallery";
        }

        if ($state != null && $month == null && $year == null) {
            $query = "select * from mdl_gallery "
                    . "where stateid=$state";
        }

        if ($state != null && $month != null && $year == null) {
            $query = "select * from mdl_gallery "
                    . "where stateid=$state and month=$month";
        }

        if ($state != null && $month != null && $year != null) {
            $query = "select * from mdl_gallery "
                    . "where stateid=$state "
                    . "and month=$month "
                    . "and year=$year";
        }

        if ($state == null && $month != null && $year != null) {
            $query = "select * from mdl_gallery "
                    . "where  month=$month "
                    . "and year=$year";
        }

        if ($state != null && $month == null && $year != null) {
            $query = "select * from mdl_gallery "
                    . "where  stateid=$state "
                    . "and year=$year";
        }

        if ($state == null && $month == null && $year != null) {
            $query = "select * from mdl_gallery "
                    . "where year=$year";
        }

        if ($state == null && $month != null && $year == null) {
            $query = "select * from mdl_gallery "
                    . "where month=$month";
        }
        //echo "Query: " . $query . "<br>";
        return $query;
    }

    function get_images_list($state = null, $month = null, $year = null) {
        $toolbar = $this->get_toolbar();
        $list = "";
        $list.="<div class='form_div'>" . $toolbar . "<br>";
        //$query = "select * from mdl_gallery";
        $query=$this->get_image_sql_criteria($state, $month, $year);
        $result = $this->db->query($query);
        if ($result->num_rows() > 0) {
            $list.="<div class='fotorama' id='fotorama'>";
            foreach ($result->result() as $row) {
                $file_path = 'http://' . $_SERVER['SERVER_NAME'] . '/lms/custom/gallery/files/' . $row->path;
                $list.="<img src='$file_path' alt='image' width='300px;' height='200px;'>";
            } // end foreach
            $list.="</div></div>";
        } // end if $result->num_rows() > 0
        else {
            $list.="<div class='container-fluid' style=''>";
            $list.="<span class='span9'>There are no images</span>";
            $list.="</div></div>";
        }
        return $list;
    }

}
