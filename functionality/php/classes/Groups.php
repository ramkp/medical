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

    function get_courses_list() {
        $drop_down = "";
        $drop_down.="<div class='dropdown'>
        <a href='#' id='courses' data-toggle='dropdown' 
        class='dropdown-toggle'>Program 
        <b class='caret'></b></a>
        <ul class='dropdown-menu'>";
        $query = "select id, fullname from mdl_course where id>1";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $drop_down.="<li><a href='#' id='course_" . $row['id'] . "'>" . $row['fullname'] . "</a></li>";
        }
        $drop_down.="</ul></div>";
        return $drop_down;
    }

    function get_private_group_form() {
        $list = "";
        $courses = $this->get_courses_list();
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

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>Phone*</span>";
        $list.="<span class='span2'><input type='text' id='group_phone' name='group_phone'></span>";
        $list.="<span class='span2' >Estimate budget*</span>";
        $list.="<span class='span2'><input type='text' id='group_budget' name='group_budget'></span>";
        $list.="</div>"; // end of container-fluid

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>Company*</span>";
        $list.="<span class='span2'><input type='text' id='group_company' name='group_company'></span>";
        $list.="<span class='span2' >State*</span>";
        $list.="<span class='span2'><input type='text' id='group_state' name='group_state'></span>";
        $list.="</div>"; // end of container-fluid

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>Program*</span>";
        $list.="<span class='span2'>$courses</span>";
        $list.="<span class='span2' >Request*</span>";
        $list.="<span class='span2'><textarea rows='5' name='text' id='group_request' name='group_request'></textarea></span>";
        $list.="</div>"; // end of container-fluid

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'><button class='btn btn-primary' id='submit_private_group'>Submit</button></span>";
        $list.="<span class='span4' id='private_err' style='color:red;'></span>";
        $list.="</div>"; // end of container-fluid

        $list.="</div>"; // end of panel-body
        $list.="</div>"; // end of panel panel-default

        return $list;
    }

    function submit_private_group_request($request) {
        echo "<pre>";
        print_r($request);
        echo "</pre>";
    }

}
