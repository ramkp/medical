<?php

/**
 * Description of Groups
 *
 * @author sirromas
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/class.pdo.database.php';

class Groups {

    public $db;

    function __construct() {
        $this->db = new pdo_db();
    }

    function get_private_group_form() {
        $list = "";

        $list.="<br/><div  class='form_div'>";
        $list.="<div class='panel panel-default' id='program_section' style='margin-bottom:0px;'>";
        $list.="<div class='panel-heading' style='text-align:left;'><h5 class='panel-title'>Private Groups</h5></div>";
        $list.="<div class='panel-body'>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>Contact person*</span>";
        $list.="<span class='span2'><input type='text' id='group_fio' name='group_fio'></span>";
        $list.="<span class='span2' >City*</span>";
        $list.="<span class='span2'><input type='text' id='group_city' name='group_city'></span>";
        $list.="</div>"; // end of container-fluid
        $list.="</div>"; // end of panel-body
        $list.="</div>"; // end of panel panel-default

        return $list;
    }

}
