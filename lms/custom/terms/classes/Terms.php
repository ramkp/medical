<?php

include $_SERVER['DOCUMENT_ROOT'] . "/lms/editor/fckeditor.php";
require_once ($_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php');

class Terms extends Util {

    function __construct() {
        parent::__construct();
    }

    function get_terms_page() {
        $list = "";

        if ($this->session->justloggedin == 1) {
            $query = "select id, content from mdl_terms where id=1";
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $content = $row['content'];
            }
            $list = $list . "<table class='table table-hover' border='0'>";
            $list = $list . "<tr>";
            $oFCKeditor = new FCKeditor('editor');
            $oFCKeditor->BasePath = $this->editor_path;
            $oFCKeditor->Value = $content;
            $editor = $oFCKeditor->Create(false);
            $list = $list . "</td >&nbsp;&nbsp;$editor</td>";
            $list = $list . "</tr>";
            $list = $list . "<tr>";
            $list = $list . "<td align='left' style='padding-left:0px'><button type='button' id='save_terms' class='btn btn-primary' style='spacing-left:0px;'>Save</button></td>";
            $list = $list . "</tr>";
            $list = $list . "</table>";
        } // end if
        else {
            $list.="<p>You are not authenticated. &nbsp; <a href='https://medical2.com/login'><button class='btn btn-primary' id='relogin'>Login</button></a></p>";
        }
        return $list;
    }

    function save_page_changes($data) {
        $clean_data = addslashes($data);
        $query = "update mdl_terms "
                . "set content='$clean_data' where id=1";
        $this->db->query($query);
        $list = "<p align='center'>Data successfully saved. </p>";
        return $list;
    }

}
