<?php

require_once ($_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php');
include $_SERVER['DOCUMENT_ROOT'] . "/lms/editor/fckeditor.php";

class Job extends Util {

    function __construct() {
        parent::__construct();
    }

    function get_jobs_page() {
        $list = "";

        if ($this->session->justloggedin == 1) {
            $query = "select id, content from mdl_jobs where id=1";
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $content = $row['content'];
            }
            $list = $list . "<table class='table table-hover' border='0'>";
            $list = $list . "<tr>";
            $oFCKeditor = new FCKeditor('editor1');
            $oFCKeditor->BasePath = $this->editor_path;
            $oFCKeditor->Value = $content;
            $editor = $oFCKeditor->Create(false);
            $list = $list . "</td >&nbsp;&nbsp;$editor</td>";
            $list = $list . "</tr>";
            $list = $list . "</table>";

            $query2 = "select id, content from mdl_jobs where id=2";
            $result2 = $this->db->query($query2);
            while ($row = $result2->fetch(PDO::FETCH_ASSOC)) {
                $content2 = $row['content'];
            }
            $list = $list . "<table class='table table-hover' border='0'>";
            $list = $list . "<tr>";
            $oFCKeditor2 = new FCKeditor('editor2');
            $oFCKeditor2->BasePath = $this->editor_path;
            $oFCKeditor2->Value = $content2;
            $editor2 = $oFCKeditor2->Create(false);
            $list = $list . "</td >&nbsp;&nbsp;$editor2</td>";
            $list = $list . "</tr>";
            $list = $list . "<tr>";
            $list = $list . "<td align='left' style='padding-left:0px'><button type='button' id='save_jobs_instructor' class='btn btn-primary' style='spacing-left:0px;width:375px;'>Save Jobs Instructor</button></td>";
            $list = $list . "<td align='left' style='padding-left:0px'><button type='button' id='save_jobs_student' class='btn btn-primary' style='spacing-left:0px;width:375px;'>Save Jobs Student</button></td>";
            $list = $list . "</tr>";
            $list = $list . "</table>";
        } // end if
        else {
            $list.="<p>You are not authenticated. &nbsp; <a href='https://medical2.com/login'><button class='btn btn-primary' id='relogin'>Login</button></a></p>";
        }
        return $list;
    }

    function update_jobs_instructor($data) {
        $clean_data = addslashes($data);
        $query = "update mdl_jobs "
                . "set content='$clean_data' where id=1";
        $this->db->query($query);
        $list = "<p align='center'>Data successfully saved. </p>";
        return $list;
    }

    function update_jobs_student($data) {
        $clean_data = addslashes($data);
        $query = "update mdl_jobs "
                . "set content='$clean_data' where id=2";
        $this->db->query($query);
        $list = "<p align='center'>Data successfully saved. </p>";
        return $list;
    }

}
