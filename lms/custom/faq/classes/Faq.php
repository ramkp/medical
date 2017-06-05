<?php

/**
 * Description of Faq
 *
 * @author sirromas
 */
require_once ($_SERVER ['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php');
include $_SERVER ['DOCUMENT_ROOT'] . "/lms/editor/fckeditor.php";

class Faq extends Util {

    function __construct() {
        parent::__construct();
    }

    function get_edit_page() {
        $list = "";
        $query = "select id, content from mdl_faq where id=1";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $content = $row ['content'];
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
        $query = "update mdl_faq " . "set content='$clean_data' where id=1";
        $result = $this->db->query($query);
        $list = "<p align='center'>Data successfully saved. </p>";
        return $list;
    }

    function get_categories_list($edit = false, $catid = null) {
        $list = "";
        if ($edit == false) {
            $list .= "<select id='faq_categories' style='width:275px;'>";
        } // end if
        else {
            $list .= "<select id='faq_categories2' style='width:275px;'>";
        }
        if ($catid == null) {
            $list .= "<option value='0' selected>FAQ Category</option>";
        }
        $query = "select * from mdl_faq_category order by name";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            if ($catid == null) {
                $list .= "<option value='" . $row ['id'] . "'>" . $row ['name'] . "</option>";
            } // end if $catid == null)
            else {
                if ($row['id'] == $catid) {
                    $list .= "<option value='" . $row ['id'] . "' selected>" . $row ['name'] . "</option>";
                } // end if $row['catid'] == $catid
                else {
                    $list .= "<option value='" . $row ['id'] . "'>" . $row ['name'] . "</option>";
                } // end else 
            } // end else
        } // end while
        $list .= "</select>";
        return $list;
    }

    function get_faq_page() {
        $list = "";
        if ($this->session->justloggedin == 1) {
            $categories = $this->get_categories_list();
            $list .= "<div class='container-fluid' style='text-align:center;'>";
            $list .= "<span class='span6'>$categories</span>";
            $list.="<span class='span1'><a href='#' onClick='return false;'><img id='faq_add' title='Add question' src='https://medical2.com/lms/theme/image.php/lambda/core/1468523658/t/add'></a></span>";
            $list.="<span class='span1'><a href='#' onCLick='return false;'><img id='faq_add_cat' src='https://medical2.com/lms/theme/image.php/lambda/core/1479536192/i/withsubcat' title='Add category'></a></span>";
            $list.="<span class='span1'><a href='#' onCLick='return false;'><img id='edit_cat' src='https://medical2.com/lms/theme/image.php/lambda/core/1468523658/t/edit' title='Edit category'></a></span>";
            $list.="<span class='span1'><a href='#' onClick='return false'><img id='del_cat' src='https://medical2.com/lms/theme/image.php/lambda/core/1468523658/t/delete' title='Delete category'></a></span>";
            $list .= "</div>";
            $list .= "<div class='container-fluid' style='text-align:left;'>";
            $list .= "<span class='span9' id='faq_container'></span>";
            $list .= "</div>";
        } // end if
        else {
            $list.="<p>You are not authenticated. &nbsp; <a href='https://medical2.com/login'><button class='btn btn-primary' id='relogin'>Login</button></a></p>";
        }
        return $list;
    }

    function get_questions_by_category($id) {
        $list = "";
        $query = "select * from mdl_faq_old where catid=$id";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $id = $row['id'];
                $list .= "<div class='container-fluid' style='text-align:left;'>";
                $list .= "<span class='span1'>Q</span>";
                $list .= "<span class='span8'>" . $row ['q'] . "</span>";
                $list.="<span class='span1'><a href='#' onClick='return false;' ><img id='faq_edit_$id' title='Edit question' src='https://medical2.com/lms/theme/image.php/lambda/core/1468523658/t/edit'></a></span>";
                $list.="<span class='span1'><a href='#' onClick='return false;' ><img id='faq_del_$id'  title='Delete question' src='https://medical2.com/lms/theme/image.php/lambda/core/1468523658/t/delete'></a></span>";

                $list .= "</div>";

                $list .= "<div class='container-fluid' style='text-align:left;'>";
                $list .= "<span class='span1'>A</span>";
                $list .= "<span class='span8'>" . $row ['a'] . "</span>";
                $list .= "</div>";

                $list .= "<div class='container-fluid' style='text-align:left;'>";
                $list .="<span class='span9'><hr/></span>";
                $list .= "</div>";
            }
        } // end if $num>0
        else {
            $list .= "<div class='container-fluid' style='text-align:center;'>";
            $list .= "<span class='span9'>N/A</span>";
            $list .= "</div>";
        }
        return $list;
    }

    function faq_add() {
        $list = "";

        $list.="<div id='myModal' class='modal fade'>
		<div class='modal-dialog modal-lg'>
		<div class='modal-content'>
		<div class='modal-header'>
		<h4 class='modal-title'>Add Q & A</h4>
		</div>
		<div class='modal-body'>
		
		<div class='container-fluid' style='text-align:left;'>
		<table align='center'>
		
		<tr>
		<td style='padding:15px;'>Question*</span><td style='padding:15px;'><input type='text' id='q' style='width:375px;' ></td>
		</tr>
		
		<tr>
		<td style='padding:15px;' colspan='2'><textarea id='a' style='width:468px;height:182px;'></textarea></td>
		</tr>
		
		<tr>
		<td colspan='2' style='padding:15px;'><span style='text-align:center' id='faq_err'></span></td>
		</tr>
		
		</table>
		
		</div>
		
		<div class='modal-footer'>
		<span align='center'><button type='button' class='btn btn-primary' data-dismiss='modal' id='cancel_faq_edit'>Cancel</button></span>
		<span align='center'><button type='button' class='btn btn-primary'  id='add_faq'>Ок</button></span>
		</div>
		</div>
		</div>
		</div>";

        return $list;
    }

    function get_add_cat_dialog() {
        $list = "";

        $list.="<div id='myModal' class='modal fade'>
		<div class='modal-dialog modal-lg'>
		<div class='modal-content'>
		<div class='modal-header'>
		<h4 class='modal-title'>Add FAQ category</h4>
		</div>
		<div class='modal-body'>
		
		<div class='container-fluid' style='text-align:left;'>
		<table align='center'>
		
		<tr>
		<td style='padding:15px;'>Name:*</span><td style='padding:15px;'><input type='text' id='cat_name' style='width:375px;' ></td>
		</tr>
		
		<tr>
		<td colspan='2' style='padding:15px;'><span style='text-align:center' id='faq_err'></span></td>
		</tr>
		
		</table>
		
		</div>
		
		<div class='modal-footer'>
		<span align='center'><button type='button' class='btn btn-primary' data-dismiss='modal' id='cancel_faq_edit'>Cancel</button></span>
		<span align='center'><button type='button' class='btn btn-primary'  id='add_cat'>Ок</button></span>
		</div>
		</div>
		</div>
		</div>";

        return $list;
    }

    function get_edit_cat_dialog() {
        $list = "";

        $cats = $this->get_categories_list(TRUE);

        $list.="<div id='myModal' class='modal fade'>
		<div class='modal-dialog modal-lg'>
		<div class='modal-content'>
		<div class='modal-header'>
		<h4 class='modal-title'>Delete FAQ category</h4>
		</div>
		<div class='modal-body'>
		
		<div class='container-fluid' style='text-align:left;'>
		<table align='center'>
		
		<tr>
		<td style='padding:15px;'><span class='span1'>Category:*</span><span class='span3'>$cats</span></td>
		</tr>
                
                <tr>
		<td style='padding:15px;'><span class='span1'>Name:*</span><span class='span3'><input type='text' id='new_cat_name' style='width:265px;'></span></td>
		</tr>
		
		<tr>
		<td colspan='2' style='padding:15px;'><span style='text-align:center' id='faq_err'></span></td>
		</tr>
		
		</table>
		
		</div>
		
		<div class='modal-footer'>
		<span align='center'><button type='button' class='btn btn-primary' data-dismiss='modal' id='cancel_faq_edit'>Cancel</button></span>
		<span align='center'><button type='button' class='btn btn-primary'  id='update_cat_button'>Ок</button></span>
		</div>
		</div>
		</div>
		</div>";

        return $list;
    }

    function get_del_cat_dialog() {
        $list = "";

        $cats = $this->get_categories_list(TRUE);

        $list.="<div id='myModal' class='modal fade'>
		<div class='modal-dialog modal-lg'>
		<div class='modal-content'>
		<div class='modal-header'>
		<h4 class='modal-title'>Delete FAQ category</h4>
		</div>
		<div class='modal-body'>
		
		<div class='container-fluid' style='text-align:left;'>
		<table align='center'>
		
		<tr>
		<td style='padding:15px;'><span class='span1'>Category:*</span><span class='span3'>$cats</span></td>
		</tr>
		
		<tr>
		<td colspan='2' style='padding:15px;'><span style='text-align:center' id='faq_err'></span></td>
		</tr>
		
		</table>
		
		</div>
		
		<div class='modal-footer'>
		<span align='center'><button type='button' class='btn btn-primary' data-dismiss='modal' id='cancel_faq_edit'>Cancel</button></span>
		<span align='center'><button type='button' class='btn btn-primary'  id='del_cat_button'>Ок</button></span>
		</div>
		</div>
		</div>
		</div>";

        return $list;
    }

    function is_category_exists($name) {
        $query = "select * from mdl_faq_category where name='$name'";
        $num = $this->db->numrows($query);
        return $num;
    }

    function is_cat_has_items($id) {
        $query = "select * from mdl_faq_old where catid=$id";
        $num = $this->db->numrows($query);
        return $num;
    }

    function delete_cat($id) {
        $query = "delete from mdl_faq_category where id=$id";
        $this->db->query($query);
    }

    function add_category($name) {
        $query = "insert into mdl_faq_category (name) value ('$name')";
        $this->db->query($query);
    }

    function get_faq_eit_page($id) {
        $list = "";

        $query = "select *  from mdl_faq_old where id=$id";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $q = $row['q'];
            $a = $row['a'];
            $catid = $row['catid'];
        }

        $cat = $this->get_categories_list(true, $catid);

        $list.="<div id='myModal' class='modal fade'>
        <div class='modal-dialog modal-lg'>
        <div class='modal-content'>
            <div class='modal-header'>                
                <h4 class='modal-title'>Edit Q & A</h4>
                </div>                
                <div class='modal-body'>                                
                
                <div class='container-fluid' style='text-align:left;'>
                <input type='hidden' id='id' value='$id'>          
                
                <table align='center'>
                
                <tr>
                <td style='padding:15px;'>Question*</span><td style='padding:15px;'><input type='text' id='q' style='width:375px;' value='$q'></td>
                </tr>
                
                <tr>
                <td style='padding:15px;'>Category*</span><td style='padding:15px;'>$cat</td>
                </tr>
                
                <tr>
                <td style='padding:15px;' colspan='2'><textarea id='a' style='width:468px;height:120px;'>$a</textarea></td>
                </tr>
                
                <tr>
                <td colspan='2' style='padding:15px;'><span style='text-align:center' id='faq_err'></span></td>
                </tr>
                
                </table>               
                                
                </div>
                
                <div class='modal-footer'>
                <span align='center'><button type='button' class='btn btn-primary' data-dismiss='modal' id='cancel_faq_edit'>Cancel</button></span>
                <span align='center'><button type='button' class='btn btn-primary' data-dismiss='modal' id='update_faq'>Ок</button></span>
                </div>
        </div>
        </div>
        </div>";

        return $list;
    }

    function update_qa($id, $q, $a, $catid) {
        $query = "update mdl_faq_old set q='$q', a='$a', catid=$catid where id=$id";
        $this->db->query($query);
        $list = 'ok';
        return $list;
    }

    function add_faq($catid, $q, $a) {
        $query = "insert into mdl_faq_old (q,a,catid) values ('$q','$a',$catid)";
        echo "Query: " . $query . "<br>";
        $this->db->query($query);
        $list = 'ok';
        return $list;
    }

    function delete_faq($id) {
        $query = "delete from mdl_faq_old where id=$id";
        $this->db->query($query);
        $list = 'ok';
        return $list;
    }

    function update_category_name($cat) {
        $id = $cat->id;
        $name = $cat->name;
        $query = "update mdl_faq_category set name='$name' where id=$id";
        $this->db->query($query);
    }

}
