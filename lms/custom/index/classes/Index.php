<?php

require_once ($_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php');

class Index extends Util {

    public $slides_path;
    public $host;

    function __construct() {
        parent::__construct();
        $this->host = $_SERVER['SERVER_NAME'];
        $this->slides_path = $_SERVER['DOCUMENT_ROOT'] . '/assets/slides';
    }

    function get_toolbar() {
        $list = "";

        $list.="<div class='container-fluid'>";
        $list.="<span class='span2'>Slide title</span><span class='span4'><input type='text' id='title' style='width:145px;'></span><span class='span2'><input type='file' id='files' name='files'></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:center;display:none;' id='ajax_loader'>";
        $list.="<span class='span8'><img src='https://$this->host/assets/img/ajax.gif' /></span>";
        $list.="</div>";

        $list.="<div class='container-fluid'>";
        $list.="<span class='span2'>Slogan #1*</span><span class='span2'><input type='text' id='slogan1' style='width:145px;'></span>";
        $list.="</div>";

        $list.="<div class='container-fluid'>";
        $list.="<span class='span2'>Slogan #2*</span><span class='span2'><input type='text' id='slogan2' style='width:145px;'></span>";
        $list.="</div>";

        $list.="<div class='container-fluid'>";
        $list.="<span class='span2'>Slogan #3*</span><span class='span2'><input type='text' id='slogan3' style='width:145px;'></span>";
        $list.="</div>";

        $list.="<div class='container-fluid'>";
        $list.="<span class='span12' id='slide_err'></id>";
        $list.="</div>";

        $list.="<div class='container-fluid'>";
        $list.="<span class='span2'><button class='btn btn-primary' id='upload_slide'>Upload</button></span>";
        $list.="</div>";

        $list.="<div class='container-fluid'>";
        $list.="<span class='span12'><hr/></id>";
        $list.="</div>";

        return $list;
    }

    function get_index_page() {
        $slides = array();
        $query = "select * from mdl_slides ";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $slide = new stdClass();
                foreach ($row as $key => $value) {
                    $slide->$key = $value;
                } // end foreach
                $slides[] = $slide;
            } // end while
        } // end if $num>0
        $list = $this->create_index_page($slides);
        return $list;
    }

    function get_banner($slide) {
        $list = "";

        $list.="<div class='container-fluid'>";
        $list.="<span class='span2'>Title</span>";
        $list.="<span class='span8'>$slide->title</span>";
        $list.="</div>";

        $list.="<div class='container-fluid'>";
        $list.="<span class='span2'>Slogan1</span>";
        $list.="<span class='span8'>$slide->slogan1</span>";
        $list.="</div>";


        $list.="<div class='container-fluid'>";
        $list.="<span class='span2'>Slogan2</span>";
        $list.="<span class='span8'>$slide->slogan2</span>";
        $list.="</div>";


        $list.="<div class='container-fluid'>";
        $list.="<span class='span2'>Slogan3</span>";
        $list.="<span class='span8'>$slide->slogan3</span>";
        $list.="</div>";

        return $list;
    }

    function create_index_page($slides) {
        $list = "";
        $toolbar = $this->get_toolbar();
        $list.="<div class='container-fluid'>";
        $list.="<span class='span8'>$toolbar</span>";
        $list.="</div>";
        if (count($slides) > 0) {
            foreach ($slides as $slide) {
                $path = $_SERVER['DOCUMENT_ROOT'] . '/assets/slides/';
                $file = trim(str_replace($path, '', $slide->path));
                $img_path = 'https://' . $_SERVER['SERVER_NAME'] . "/assets/slides/thumbs/$file";
                $banner = $this->get_banner($slide);
                $list.="<div class='container-fluid'>";
                $list.="<span class='span5'><img src='$img_path'></span><span class='span7'>$banner</span>";
                $list.="<span class='span2'><button class='btn btn-primary' id='del_slide_$slide->id'>Delete</button>";
                $list.="</div>";
                $list.="<div class='container-fluid'>";
                $list.="<span class='span8'><hr/></id>";
                $list.="</div>";
            } // end foreach
        } // end if count($slides)>0
        else {
            $list.="<div class='container-fluid' style='text-align:center;'>";
            $list.="<span class='span8'>There are no slides on index page</span>";
            $list.="</div>";
        }
        return $list;
    }

    function upload_slides($files, $post) {
        $title = $post['title'];
        $slogan1 = $post['slogan1'];
        $slogan2 = $post['slogan2'];
        $slogan3 = $post['slogan3'];

        foreach ($files as $file) {
            $tmp_name = $file['tmp_name'];
            $error = $file['error'];
            $size = $file['size'];
            $ext = 'jpg';
            if ($tmp_name != '' && $error == 0 && $size > 0) {
                $stamp = time();
                $rand = rand(12, 75);
                $new_file_name = $stamp . $rand . "." . $ext;
                $destination = $this->slides_path . "/" . $new_file_name;
                if (move_uploaded_file($tmp_name, $destination)) {
                    $query = "insert into mdl_slides "
                            . "(title,"
                            . "slogan1,"
                            . "slogan2,"
                            . "slogan3,"
                            . "path) "
                            . "values('$title',"
                            . "'$slogan1',"
                            . "'$slogan2',"
                            . "'$slogan3',"
                            . "'$destination')";
                    //echo "Query: ".$query."<br>";
                    $this->db->query($query);
                    $this->create_image_thumb($new_file_name);
                } //end if move_uploaded_file($tmp_name, $destination)
            } // end if $tmp_name != '' && $error == 0 && $size > 0
        } // end foreach
    }

    function create_image_thumb($filename) {

        $final_width_of_image = 300;
        $path_to_image_directory = $_SERVER['DOCUMENT_ROOT'] . "/assets/slides/";
        $path_to_thumbs_directory = $_SERVER['DOCUMENT_ROOT'] . "/assets/slides/thumbs/";

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

    function delete_slide($id) {
        $query = "delete from mdl_slides where id=$id";
        $this->db->query($query);
    }

}
