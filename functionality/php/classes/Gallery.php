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
        $img_block = "";
        $query = "select * from mdl_gallery order by date_added desc";
        $result = $this->db->query($query);
        $img_block.="<div id='links'>";
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $img_block.="<a href='http://medical2.com/lms/custom/gallery/files/" . $row['path'] . "' title='Gallery image' data-gallery>
                         <img src='http://medical2.com/lms/custom/gallery/files/thumbs/" . $row['path'] . "' alt='Galley image'></a>";
        } // end while
        $img_block.="</div>";

        $list.="
        <link rel='stylesheet' href='//netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css'>
        <link rel='stylesheet' href='//blueimp.github.io/Gallery/css/blueimp-gallery.min.css'>
        <link rel='stylesheet' href='//assets/gallery/css/bootstrap-image-gallery.min.css'>

         <!-- The Bootstrap Image Gallery lightbox, should be a child element of the document body -->
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
        $img_block 
        <script src='//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js'></script>
        <script src='//blueimp.github.io/Gallery/js/jquery.blueimp-gallery.min.js'></script>
        <script src='//assets/galley/js/bootstrap-image-gallery.min.js'></script>";
        return $list;
    }

}
