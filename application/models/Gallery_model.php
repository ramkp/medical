<?php

/**
 * Description of Gallery_model
 *
 * @author sirromas
 */
//require_once $_SERVER['DOCUMENT_ROOT']. '/lms/custom/gallery/classes/Gallery.php';

class Gallery_model extends CI_Model {

    public $gl;

    function __construct() {
        parent::__construct();
        $this->load->database();
        //$this->gl=new Gallery();
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
            $query = "select * from mdl_gallery order by date_added";
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

    function get_galllery_thumbs($state = null, $month = null, $year = null, $full = false) {

        /*
         *          
          echo "State: " . $state . "<br>";
          echo "Month: " . $month . "<br>";
          echo "Year: " . $year . "<br>";
         * 
         */

        $list = "";
        $query = $this->get_image_sql_criteria($state, $month, $year);
        //echo "Query: ".$query."<br>";
        $result = $this->db->query($query);
        if ($result->num_rows() > 0) {
            foreach ($result->result() as $row) {
                $files[] = $row->path;
            } // end foreach
            $list.="<div class='row-fluid'>";
            //$list.="<ul class='thumbnails'>";
            for ($i = 0; $i <= count($files) - 1; $i++) {
                if ($files[$i] != '.' && $files[$i] != '..') {
                    if ($full == false) {
                        $img_http_path = 'http://' . $_SERVER['SERVER_NAME'] . "/lms/custom/gallery/files/thumbs/" . $files[$i];
                    } // end if $full==false
                    else {
                        $img_http_path = 'http://' . $_SERVER['SERVER_NAME'] . "/lms/custom/gallery/files/" . $files[$i];
                    } // end else                                       
                    if ($i % 2 == 0) {
                        $list = $list . "<ul class='thumbnails'>";
                        $list = $list . "<li class='span6'>
                    <div class='thumbnail' style='text-align:center'>                        
                            <img alt='Gallery file' src='$img_http_path' id='img_$files[$i]' alt='Gallery file' width='300px;' height='200px;' style='cursor:pointer;'>                        
                    </div>
                    </li> ";
                        $list = $list . "<ul class='thumbnails'>";
                    } // end if $i%2==0
                    else {
                        $list = $list . "<li class='span6'>
                    <div class='thumbnail' style='text-align:center'>                        
                            <img alt='Gallery file' src='$img_http_path' id='img_$files[$i]' alt='Gallery file' width='300px;' height='200px;' style='cursor:pointer;'>                        
                    </div>
                    </li> &nbsp;&nbsp;&nbsp;";
                    } // end else
                } // end if $files[$i] != '.' && $files[$i] != '..'
                //$list.="<li class='span5'><a href='#' class='thumbnail' onClick='return false;'><img src='$img_http_path' alt='' id='img_$files[$i]' width='300' height='200'></a></li>";
            } // end for
            $list.= "</ul></div>";
        } // end if $result->num_rows() > 0
        else {
            $list.= "<div class='container-fluid' style='padding-left:10px;padding-right:10px;text-align:center;'>";
            $list.="<span class='span10' style='text-align:center;'>There are no images matched criteria</span>";
            $list.= "</div>";
        } // end else
        return $list;
    }
    
    function get_galllery_thumbs2($state = null, $month = null, $year = null, $full = false) {
        $list = "";
        $list.="
                <link rel='stylesheet' href='//blueimp.github.io/Gallery/css/blueimp-gallery.min.css'>
                <link rel='stylesheet' href='http://mdeical2.com/css/bootstrap-image-gallery.min.css'>";
        
        $list.="<!-- The Bootstrap Image Gallery lightbox, should be a child element of the document body -->
        <div id='blueimp-gallery' class='blueimp-gallery'>
            <!-- The container for the modal slides -->
            <div class='slides'></div>
            <!-- Controls for the borderless lightbox -->
            <h3 class='title'></h3>
            <a class='prev'>‹</a>
            <a class='next'>›</a>
            <a class='close'>×</a>
            <a class='play-pause'></a>
            <ol class='indicator'></ol>
            <!-- The modal dialog, which will be used to wrap the lightbox content -->
            <div class='modal fade'>
                <div class='modal-dialog'>
                    <div class='modal-content'>
                        <div class='modal-header'>
                            <button type='button' class='close' aria-hidden='true'>&times;</button>
                            <h4 class='modal-title'></h4>
                        </div>
                        <div class='modal-body next'></div>
                        <div class='modal-footer'>
                            <button type='button' class='btn btn-default pull-left prev'>
                                <i class='glyphicon glyphicon-chevron-left'></i>
                                Previous
                            </button>
                            <button type='button' class='btn btn-primary next'>
                                Next
                                <i class='glyphicon glyphicon-chevron-right'></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <script src='//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js'></script>
        <script src='//blueimp.github.io/Gallery/js/jquery.blueimp-gallery.min.js'></script>
        <script src='http://mdeical2.com/js/bootstrap-image-gallery.min.js'></script>";
        
        $query = $this->get_image_sql_criteria($state, $month, $year);
        //echo "Query: ".$query."<br>";
        $result = $this->db->query($query);
        if ($result->num_rows() > 0) {
            foreach ($result->result() as $row) {
                $files[] = $row->path;
            } // end foreach
            $list.="<div id='links'>";
            //$list.="<ul class='thumbnails'>";
            for ($i = 0; $i <= count($files) - 1; $i++) {
                if ($files[$i] != '.' && $files[$i] != '..') {
                    if ($full == false) {
                        $img_http_path = 'http://' . $_SERVER['SERVER_NAME'] . "/lms/custom/gallery/files/thumbs/" . $files[$i];
                    } // end if $full==false
                    else {
                        $img_http_path = 'http://' . $_SERVER['SERVER_NAME'] . "/lms/custom/gallery/files/" . $files[$i];
                    } // end else                                       
                    $list.="<a href='http://" . $_SERVER['SERVER_NAME'] . "/lms/custom/gallery/files/" . $files[$i]."' title='Gallery images' data-gallery>
                            <img src='$img_http_path' alt='Galley images' ></a>";
                } // end if $files[$i] != '.' && $files[$i] != '..'
                //$list.="<li class='span5'><a href='#' class='thumbnail' onClick='return false;'><img src='$img_http_path' alt='' id='img_$files[$i]' width='300' height='200'></a></li>";
            } // end for
            $list.= "</div>";
        } // end if $result->num_rows() > 0
        else {
            $list.= "<div class='container-fluid' style='padding-left:10px;padding-right:10px;text-align:center;'>";
            $list.="<span class='span10' style='text-align:center;'>There are no images matched criteria</span>";
            $list.= "</div>";
        } // end else
        return $list;
    }

    function get_images_list($state = null, $month = null, $year = null) {
        $toolbar = $this->get_toolbar();
        $list = "";
        $list.="<div class='form_div' style='text-align:center;'>" . $toolbar . "<br>";
        //$list.="<div>" . $toolbar . "<br>";
        //$list.=$this->get_galllery_thumbs($state, $month, $year);
        $list.=$this->get_galllery_thumbs2($state, $month, $year);
        $list.="</div>";
        return $list;
    }

}
