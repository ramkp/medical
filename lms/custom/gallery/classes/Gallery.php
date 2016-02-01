<?php

/**
 * Description of Gallery
 *
 * @author sirromas
 */
require_once ($_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php');

class Gallery extends Util {

    public $upload_dir;

    function __construct() {
        $this->upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/gallery/files';
    }

    function get_index_page() {
        $list = "";
        $thumbs_list = $this->get_galllery_thumbs();
        $list = $list . "
        <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
       <div class='row fileupload-buttonbar' style='margin-left:0px;'>
            <div class='col-lg-7'>
                <!-- The fileinput-button span is used to style the file input field as button -->
                <span class='btn btn-success fileinput-button' style='float: left;width: 452px;padding:2px;margin-left:2px;'>              
                    <input type='file' name='files[]' id='files' multiple>
                </span>
                <button type='button' class='btn btn-primary start' id='start_upload'>
                    <i class='icon-upload icon-white'></i>
                    <span name='start_upload'>Start upload</span>
                </button>                
                <button type='button' class='btn btn-danger delete' id='delete_img'>
                    <i class='icon-trash icon-white'></i>
                    <span id='delete_img' >Delete</span>
                </button>                
            </div>            
          </div>
          <div id='gallery_err' style='color:red;margin-left: 300px;'></div>
          <div class='container' style='margin-left: 300px;margin-top:10px;display:none;' id='loader'>     
	<button class='btn btn-lg btn-warning'><span class='glyphicon glyphicon-refresh glyphicon-refresh-animate'></span> Loading...</button>
        </div>
        </div>
        <span id='thumb_list' style='margin-left:200px;'>$thumbs_list</span>";
        return $list;
    }

    function upload_gallery_images($files) {
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
                    echo "Ok <br/>";
                }
            }
        }
    }

    function get_galllery_thumbs() {
        $list = "";
        $list = $list . "<span class='thumbnails' style='margin-left: 90px;'>";
        $files = scandir($this->upload_dir);
        for ($i = 0; $i <= count($files) - 2; $i++) {
            if ($files[$i] != '.' && $files[$i] != '..') {
                $img_http_path = 'http://' . $_SERVER['SERVER_NAME'] . "/lms/custom/gallery/files/" . $files[$i];
                if ($i % 2 == 0) {
                    $list = $list . "<ul class='thumbnails'>";
                    $list = $list . "<li class='span5'>
                    <div class='thumbnail' style='text-align:center'>                        
                            <img alt='Gallery file' src='$img_http_path' width='300px;' height='200px;'>&nbsp;<input type='checkbox' id='$files[$i]' value='$files[$i]'>                        
                    </div>
                </li> ";
                    $list = $list . "<ul class='thumbnails'>";
                } // end if $i%2==0
                else {
                    $list = $list . "<li class='span5'>
                    <div class='thumbnail' style='text-align:center'>                        
                            <img alt='Gallery file' src='$img_http_path' width='300px;' height='200px;'>&nbsp;<input type='checkbox' id='$files[$i]' value='$files[$i]'>                        
                    </div>
                </li> &nbsp;&nbsp;&nbsp;";
                } // end else 
            } // end if $file!='.' && $file!='..'
        }
        $list = $list . "</ul>";
        return $list;
    }

    function refresh_gallery_thumbs() {
        $list = $this->get_galllery_thumbs();
        return $list;
    }

    function delete_gallery_thumbs($items) {
        foreach ($items as $item) {
            $filepath = $this->upload_dir . "/" . $item;
            unlink($filepath);
        }
    }

}
