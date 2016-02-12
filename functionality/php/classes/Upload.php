<?php

/**
 * Description of Upload
 *
 * @author sirromas
 */
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/Enroll.php';

class Upload {

    public $dir_path;

    function __construct() {
        $this->dir_path = $_SERVER['DOCUMENT_ROOT'] . '/upload';
    }

    function get_upload_block() {
        $list = "";
        $list.="<div class='row fileupload-buttonbar' style='margin-left:0px;'>
            <div class='col-lg-7'>
                <!-- The fileinput-button span is used to style the file input field as button -->
                <span class='btn btn-success fileinput-button' style='float: left;padding:2px;margin-left:2px;'>              
                    <input type='file' name='files[]' id='files' multiple>
                </span>
                <button type='button' class='btn btn-primary start' id='start_upload'>
                    <i class='icon-upload icon-white'></i>
                    <span name='start_upload'>Start upload</span>
                </button>                               
            </div>";
        return $list;
    }

    function get_users_upload_form() {
        $list = "";
        $upload_block = $this->get_upload_block();
        $list.="<div class='panel panel-default' id='upload_section' style='margin-bottom:0px;'>";
        $list.="<div class='panel-heading' style='text-align:left;'><h5 class='panel-title'>Upload Users File</h5></div>";
        $list.="<div class='panel-body'>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span8'>You can upload CSV file with users data. File content should follow next rule:  firstname, lastname, email, phone.  </span>";
        $list.="</div>"; // end of container-fluid

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span8'>$upload_block</span>";
        $list.="<span class='span5' id='upload_err' style='color:red;>'></span>";
        $list.="</div>"; // end of container-fluid

        $list.="</div>"; // end of panel-body
        $list.="</div>"; // end of panel panel-default
        return $list;
    }

    function group_signup($group_common, $file) {
        $users = $this->prepare_users_data($group_common, $file);
        $enroll = new Enroll();
        $enroll->group_signup($users);
    }

    function upload_users_file($files) {
        $file = $files[0];
        if ($file['error'] == 0 && $file['size'] > 0) {
            $status = $this->check_file_structure($file);
            if ($status > 0) {
                // File structure is ok we can move it to safe place
                $filename = time() . rand(10, 175);
                $full_file_path = $this->dir_path . '/' . $filename . '.csv';                
                move_uploaded_file($file['tmp_name'], $full_file_path);
                $_SESSION["file_path"] = $full_file_path;
            } // end if $status
        } // end if $file['error'] == 0 && $file['size'] > 0        
        else {
            $status = 'Error loading file';
        }
        return $status;
    }

    function check_email($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    function check_file_structure($file) {
        $status = NULL;
        $counter = 0;
        $handle = fopen($file['tmp_name'], "r");
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                $items = explode(",", $line);
                if ($items[0] != '' && $items[1] != '' && $this->check_email($items[2]) == true && $items[2] != '') {
                    $counter++;
                } // end if $items[0]!='' && $items[1]!=''                
            } // end while            
            $status = ($counter > 0) ? $counter : "Incorrect file structure";
        } // end if $handle
        else {
            $status = 'Error reading file';
        }
        return $status;
    }

}
