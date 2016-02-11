<?php

/**
 * Description of Upload
 *
 * @author sirromas
 */

require_once './Enroll.php';

class Upload {

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
        $list.="<div class='panel panel-default' id='program_section' style='margin-bottom:0px;'>";
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

    function upload_users_file($files) {
        $file = $files[0];
        //print_r($file);
        if ($file['error'] == 0 && $file['size'] > 0) {
            $status = $this->check_file_structure($file);
            echo "Status: ".$status."<br/>";
            if ($status===true) {
                // File structure is ok we can start enroll process
                
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
        $handle = fopen($file['tmp_name'], "r");
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                $items = explode(",", $line);
                if ($items[0] != '' && $items[1] != '' && $this->check_email($items[2]) === true && $items[2] != '') {
                    $status = true;
                } // end if $items[0]!='' && $items[1]!=''
                else {
                    $status = 'Incorrect file structure';
                } // end else
            } // end while
        } // end if $handle
        else {
            $status = 'Error reading file';
        }
        return $status;
    }

}
