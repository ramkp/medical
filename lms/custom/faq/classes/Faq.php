<?php

/**
 * Description of Faq
 *
 * @author sirromas
 */
require_once ($_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php');
include $_SERVER['DOCUMENT_ROOT'] . "/lms/editor/fckeditor.php";

class Faq extends Util {

    function __construct() {
        parent::__construct();
    }

    function get_edit_page() {
        $list = "";
        $query = "select id, content from mdl_faq where id=1";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $content = $row['content'];
        }
        $oFCKeditor = new FCKeditor('editor');
        $oFCKeditor->BasePath = $this->editor_path;
        $oFCKeditor->Value = $content;
        $editor = $oFCKeditor->Create(false);
        $list = $list . "<table class='table table-hover' border='0'>";
        $list = $list . "<tr>";
        $list = $list . "</td >&nbsp;&nbsp;$editor</td>";
        $list = $list . "</tr>";
        $list = $list . "<tr>";
        $list = $list . "<td align='left' style='padding-left:0px'><button type='button' id='save_faq' class='btn btn-primary' style='spacing-left:0px;'>Save</button></td>";
        $list = $list . "</tr>";
        $list = $list . "</table>";
        return $list;
    }

    function get_editor_instance($content) {
        $clean_content = $this->prepare_editor_data($content);
        $list = "";
        $list = $list . "<textarea name='editor1'></textarea>
                         <script>
                             CKEDITOR.replace( 'editor1' ); 
                             CKEDITOR.instances.editor1.setData('$clean_content');
                          </script>";
        return $list;
    }

    function save_page_changes($data) {
        $clean_data = addslashes($data);
        $query = "update mdl_faq "
                . "set content='$clean_data' where id=1";
        $result = $this->db->query($query);
        $list = "<p align='center'>Data successfully saved. </p>";
        return $list;
    }

}
