<?php

/**
 * Description of Gallery
 *
 * @author sirromas
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/class.pdo.database.php';

class Gallery {

    public $db;

    function __construct() {
        $this->db = new pdo_db();
    }

    function get_images_list() {
        $list = "";
        $dir_path = $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/gallery/files';
        $images = scandir($dir_path);
        $list.="<div class='form_div'><div class='fotorama'>";
        foreach ($images as $image) {
            if ($image != '.' && $image != '..') {
                $file_path = 'http://' . $_SERVER['SERVER_NAME'] . '/lms/custom/gallery/files/' . $image;
                $list.="<img src='$file_path' alt='$image' width='300px;' height='200px;'>";
            }
        }
        $list.="</div></div>";
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

    function get_gallery_pics($state = null, $month = null, $year = null) {
        $list = "";
        $query = $this->get_image_sql_criteria($state, $month, $year);
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $files[] = $row['path'];
            }
            $list.="<div class='fotorama' id='fotorama'>";
            foreach ($files as $file) {
                $file_path = 'http://' . $_SERVER['SERVER_NAME'] . '/lms/custom/gallery/files/' . $file;
                $list.="<img src='$file_path' alt='image' width='300px;' height='200px;'>";
            } // end foreach
            $list.= "</div>";
        } // end if $num > 0
        else {
            $list .= "<div class='container-fluid' style='padding-left:10px;padding-right:10px;text-align:center;'>";
            $list.="<span class='span10' style='text-align:center;'>There are no images matched criteria</span>";
            $list.= "</div>";
        } // end else
        return $list;
    }

    function show_image($image, $width) {
        $list = "";
        if ($width >= 768) {
            $list.="<div id='myModal' class='modal responsive' style='display: inline; width: 90%; height:80%;  margin-left:-45%;'>";
        } // end if $width >= 768 
        else {
            $list.="<div id='myModal' class='modal responsive' style='display: inline; width: 90%; height:80%;'>";
        }

        $list.="<div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>                
            </div>
            <div class='modal-body' style='text-align:center;'>                
                    <div class='col-lg-3 col-md-4 col-xs-6 thumb'>
                      <a class='thumbnail' href='#' onClick='return false;'>
                         <img class='img-responsive' src='$image' alt='' id='img_$files[$i]'>
                      </a>
                   </div>
            </div>
            <div class='modal-footer' style='text-align:center;>
                <span align='center'><button type='button' class='btn btn-primary' data-dismiss='modal' id='close'>Close</button></span>                
            </div>
         </div>
        </div>
        </div>";
        return $list;
    }

}
