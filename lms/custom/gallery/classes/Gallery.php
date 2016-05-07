<?php

/**
 * Description of Gallery
 *
 * @author sirromas
 */
require_once ($_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php');

class Gallery extends Util {

    public $upload_dir;
    public $db;

    function __construct() {
        $this->upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/gallery/files';
        $db = new pdo_db;
        $this->db = $db;
    }

    function get_states_list($upload = false) {
        $list = "";
        $query = "select * from mdl_states order by state";
        if ($upload == false) {
            $list.="<select id='state' style='width:145px;'>";
        } // end if $upload==false
        else {
            $list.="<select id='upload_state' style='width:145px;'>";
        } // end else 
        $list.="<option value='0' selected>All states</option>";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $list.="<option value='" . $row['id'] . "'>" . $row['state'] . "</option>";
        }
        $list.="</select>";
        return $list;
    }

    function get_month_list($upload = false) {
        $list = "";
        if ($upload == false) {
            $list.="<select id='month' style='width:70px;'>";
        } // end if $upload == false
        else {
            $list.="<select id='upload_month' style='width:70px;'>";
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

    function get_toolbar($upload = true) {
        $list = "";
        $states = $this->get_states_list();
        $month = $this->get_month_list();
        $year = $this->get_year_list();
        $list.="<span class='span2'><input type='file' name='files[]' id='files' multiple></span>";
        $list.= "<span class='span2'>$states</span>";
        $list.= "<span class='span1'>$month</span>";
        $list.= "<span class='span1'>$year</span>";
        $list.= "<span class='span1'><button type='button' class='btn btn-primary' id='filter'>Filter</button></span>";
        if ($upload == true) {
            $list = $list . "<span class='span1'>
                    <button type='button' class='btn btn-primary start' id='start_upload'>                    
                    <span name='start_upload'>Add</span></button></span>";
            $list = $list . "<span class='span2'>
                <button type='button' class='btn btn-danger delete' id='delete_img'>                    
                    <span id='delete_img' >Delete</span></button></span>";
        } // end if $upload==true        
        return $list;
    }

    function get_index_page() {
        $list = "";
        $thumbs_list = $this->get_galllery_thumbs(null, null, null);
        $toolbar = $this->get_toolbar();
        $states = $this->get_states_list();
        $month = $this->get_month_list();
        $year = $this->get_year_list();
        // Toolbar
        $list .= "<div class='container-fluid' style='padding-left:10px;padding-right:10px;'>";
        $list .=$toolbar;
        $list.= "</div>";
        // Comment box
        $list .= "<div class='container-fluid' style='padding-left:10px;padding-right:10px;text-align:center;'>";
        $list.="<span class='span10' style='text-align:center;'>Comment: &nbsp; <input type='text' id='comment' style='width:375px;'></span>";
        $list.= "</div>";
        // Error div
        $list .= "<div class='container-fluid' style='padding-left:10px;padding-right:10px;text-align:center;'>";
        $list.="<span class='span10' id='gallery_err' style='color:red;'></span>";
        $list.= "</div>";
        // Thumbnails div
        $list = $list . "<div class='container-fluid' style='padding-left:10px;padding-right:10px;'>";
        $list.="<span id='thumb_list' style='' class='span10'>$thumbs_list</span>";
        $list.= "</div>";
        return $list;
    }

    function upload_gallery_images($files, $post) {

        /*
         * 
          [state] => 5
          [month] => 4
          [year] => 2016
          [comment] => Some comment
         * 
         */

        $state = $post['state'];
        $month = $post['month'];
        $year = $post['year'];
        $comment = $post['comment'];

        foreach ($files as $file) {
            $tmp_name = $file['tmp_name'];
            $error = $file['error'];
            $size = $file['size'];
            $ext = 'jpg';
            if ($tmp_name != '' && $error == 0 && $size > 0) {
                $stamp = time();
                $rand = rand(12, 75);
                $new_file_name = $stamp . $rand . "." . $ext;
                $destination = $this->upload_dir . "/" . $new_file_name;
                if (move_uploaded_file($tmp_name, $destination)) {
                    $query = "insert into mdl_gallery "
                            . "(stateid,"
                            . "month,"
                            . "year,"
                            . "path,"
                            . "comment,"
                            . "date_added) "
                            . "values($state,"
                            . "$month,"
                            . "$year,"
                            . "'$new_file_name',"
                            . "'$comment',"
                            . "'" . time() . "')";
                    $this->db->query($query);
                    if ($comment != '') {
                        $this->apply_text_to_image($destination, $comment);
                    } // end if $comment!=''
                    $this->create_image_thumb($new_file_name);
                } //end if move_uploaded_file($tmp_name, $destination)
            } // end if $tmp_name != '' && $error == 0 && $size > 0
        } // end foreach
    }

    function create_image_thumb($filename) {

        $final_width_of_image = 300;
        $path_to_image_directory = $_SERVER['DOCUMENT_ROOT'] . "/lms/custom/gallery/files/";
        $path_to_thumbs_directory = $_SERVER['DOCUMENT_ROOT'] . "/lms/custom/gallery/files/thumbs/";

        if (preg_match('/[.](jpg)$/', $filename)) {
            $im = imagecreatefromjpeg($path_to_image_directory . $filename);
        } else if (preg_match('/[.](gif)$/', $filename)) {
            $im = imagecreatefromgif($path_to_image_directory . $filename);
        } else if (preg_match('/[.](png)$/', $filename)) {
            $im = imagecreatefrompng($path_to_image_directory . $filename);
        }

        $ox = imagesx($im);
        $oy = imagesy($im);

        $nx = $final_width_of_image;
        $ny = floor($oy * ($final_width_of_image / $ox));

        $nm = imagecreatetruecolor($nx, $ny);

        imagecopyresized($nm, $im, 0, 0, 0, 0, $nx, $ny, $ox, $oy);

        if (!file_exists($path_to_thumbs_directory)) {
            if (!mkdir($path_to_thumbs_directory)) {
                die("There was a problem. Please try again!");
            }
        }

        imagejpeg($nm, $path_to_thumbs_directory . $filename);
    }

    function apply_text_to_image($path, $text) {
        $jpg_image = imagecreatefromjpeg($path);
        $color = imagecolorallocate($jpg_image, 255, 255, 255);
        $font_path = $_SERVER['DOCUMENT_ROOT'] . '/assets/fonts/font-cert/KingCityFont.ttf';
        imagettftext($jpg_image, 12, 0, 15, 35, $color, $font_path, $text);
        imagejpeg($jpg_image, $path);
        imagedestroy($jpg_image);
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

    function get_galllery_thumbs($state = null, $month = null, $year = null) {

        /*
         *          
          echo "State: " . $state . "<br>";
          echo "Month: " . $month . "<br>";
          echo "Year: " . $year . "<br>";
         * 
         */

        $list = "";
        $list = $list . "<span class='thumbnails' style='margin-left: 90px;'>";
        $query=$this->get_image_sql_criteria($state, $month, $year);
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $files[] = $row['path'];
            }

            //echo "Files list ...<br>";
            //print_r($files);
            for ($i = 0; $i <= count($files) - 1; $i++) {
                if ($files[$i] != '.' && $files[$i] != '..') {
                    $img_http_path = 'http://' . $_SERVER['SERVER_NAME'] . "/lms/custom/gallery/files/thumbs/" . $files[$i];
                    if ($i % 2 == 0) {
                        $list = $list . "<ul class='thumbnails'>";
                        $list = $list . "<li class='span6'>
                    <div class='thumbnail' style='text-align:center'>                        
                            <img alt='Gallery file' src='$img_http_path' width='300px;' height='200px;'>&nbsp;<input type='checkbox' id='$files[$i]' value='$files[$i]'>                        
                    </div>
                </li> ";
                        $list = $list . "<ul class='thumbnails'>";
                    } // end if $i%2==0
                    else {
                        $list = $list . "<li class='span6'>
                    <div class='thumbnail' style='text-align:center'>                        
                            <img alt='Gallery file' src='$img_http_path' width='300px;' height='200px;'>&nbsp;<input type='checkbox' id='$files[$i]' value='$files[$i]'>                        
                    </div>
                </li> &nbsp;&nbsp;&nbsp;";
                    } // end else 
                } // end if $file!='.' && $file!='..'
            }
            $list = $list . "</ul>";
        } // end if $num>0
        else {
            $list .= "<div class='container-fluid' style='padding-left:10px;padding-right:10px;text-align:center;'>";
            $list.="<span class='span10' style='text-align:center;'>There are no images matched criteria</span>";
            $list.= "</div>";
        }
        return $list;
    }

    function refresh_gallery_thumbs() {
        $list = $this->get_galllery_thumbs();
        return $list;
    }

    function filter($state, $month, $year) {
        /*
         * 
          echo "State: ".$state."<br>";
          echo "Month: ".$month."<br>";
          echo "Year: ".$year."<br>";
         * 
         */
        $list = $this->get_galllery_thumbs($state, $month, $year);
        return $list;
    }

    function delete_gallery_thumbs($items) {
        foreach ($items as $item) {
            // Delete DB record
            $query = "delete from mdl_gallery where path='$item'";
            $this->db->query($query);

            // Delete original file
            $filepath = $this->upload_dir . "/" . $item;
            unlink($filepath);

            // Delete thummb
            $filepath = $this->upload_dir . "/thumbs/" . $item;
            unlink($filepath);
        }
    }

}
