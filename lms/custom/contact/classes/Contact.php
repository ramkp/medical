<?php

/**
 * Description of Contact
 *
 * @author sirromas
 */
require_once ($_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php');
include $_SERVER['DOCUMENT_ROOT'] . "/lms/editor/fckeditor.php";

class Contact extends Util {

    function get_edit_page() {
        $list = "";

        if ($this->session->justloggedin == 1) {
            $query = "select * from mdl_contact_page order by item";
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $list.="<div class='row-fluid'>";
                $id = $row['id'];
                $valueid = "value_$id";
                $btnid = "btn_$id";
                $list.="<span class='span2'>" . $row['item'] . "</span>";
                $list.="<span class='span6'><input style='width:100%' type='text' id='$valueid' value='" . $row['value'] . "'></span>";
                $list.="<span class='span1'><button class='btn btn-primary' id='$btnid'>Update</button></span>";
                $list.="</div>";
                $list.="<div class='row-fluid'>";
                $list.="<span class='span2'>&nbsp;</span>";
                $list.="<span class='span6' id='update_result_$id'></span>";
                $list.="</div>";
                $list.="<div class='row-fluid'>";
                $list.="<span class='span9'><hr/></span>";
                $list.="</div>";
            } // end while
        } // end if
        else {
            $list.="<p>You are not authenticated. &nbsp; <a href='https://medical2.com/login'><button class='btn btn-primary' id='relogin'>Login</button></a></p>";
        } // end else

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
        $query = "update mdl_contact_page "
                . "set content='$clean_data' where id=1";
        $this->db->query($query);
        $list = "<p align='center'>Data successfully saved. </p>";
        return $list;
    }

    function update_item($item) {
        $id = $item->id;
        $value = addslashes($item->value);
        $query = "update mdl_contact_page set value='$value' where id=$id";
        $this->db->query($query);
        $list = "Data updated successfully";
        return $list;
    }

}
