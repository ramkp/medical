<?php

/**
 * Description of Groups_model
 *
 * @author sirromas
 */
class Groups_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    function get_courses_list() {
        $drop_down = "";
        //$drop_down.="<div class='dropdown'>
        //<a href='#' id='courses' data-toggle='dropdown' 
        //class='dropdown-toggle'>Program 
        //<b class='caret'></b></a>
        //<ul class='dropdown-menu'>";
        $query = "select id, fullname from mdl_course where id>1";
        $result = $this->db->query($query);
        $drop_down.="<select id='courses' style='width:160px;'>";
        $drop_down.="<option value='0' selected>Program</option>";
        foreach ($result->result() as $row) {
            $drop_down.="<option value='$row->id'>$row->fullname </option>";
        }
        $drop_down.="</select>";
        return $drop_down;
    }

    function get_states_list() {
        $drop_down = "";
        $query = "select * from mdl_states order by state";
        $result = $this->db->query($query);
        $drop_down.="<select id='group_state' style='width:160px;'>";
        $drop_down.="<option value='0' selected>State</option>";
        foreach ($result->result() as $row) {
            $drop_down.="<option value='$row->id'>$row->state </option>";
        }
        $drop_down.="</select>";
        return $drop_down;
    }

    function get_people_num_dropbox() {
        $drop_down = "";
        $drop_down.="<select id='people_num' style='width:160px;'>";
        $drop_down.="<option value='0' selected>People Num</option>";
        for ($i = 1; $i <= 300; $i++) {
            $drop_down.="<option value='$i'>$i</option>";
        }
        $drop_down.="</select>";
        return $drop_down;
    }

    function get_private_group_form() {
        $list = "";
        $courses = $this->get_courses_list();
        $states = $this->get_states_list();
        $people_num = $this->get_people_num_dropbox();
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
        $list.="<span class='span2' >Estimated budget</span>";
        $list.="<span class='span2'><input type='text' id='group_budget' name='group_budget'></span>";
        $list.="</div>"; // end of container-fluid

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>Number of people*</span>";
        $list.="<span class='span2'>$people_num</span>";
        $list.="<span class='span2' >State*</span>";
        $list.="<span class='span2'>$states</span>";
        $list.="</div>"; // end of container-fluid

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>Company*</span>";
        $list.="<span class='span2'><input type='text' id='group_company' name='group_company'></span>";
        $list.="<span class='span2' >Contact email*</span>";
        $list.="<span class='span2'><input type='text' id='group_email' name='group_email'></span>";
        $list.="</div>"; // end of container-fluid

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>Program*</span>";
        $list.="<span class='span2'>$courses</span>";
        $list.="<span class='span2' >Special Request</span>";
        $list.="<span class='span2'><textarea rows='5' name='text' id='group_request' name='group_request'></textarea></span>";
        $list.="</div>"; // end of container-fluid

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'><button class='btn btn-primary' id='submit_private_group'>Submit</button></span>";
        $list.="<span class='span4' id='private_err' style='color:red;'></span>";
        $list.="</div></div>"; // end of container-fluid

        $list.="</div>"; // end of panel-body
        $list.="</div>"; // end of panel panel-default

        return $list;
    }

    function get_course_id($coursename) {
        $query = "select id, fullname from mdl_course "
                . "where fullname='$coursename'";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $id = $row->id;
        }
        return $id;
    }

    function submit_private_group_request($request) {
        $list = "";
        $courseid = $this->get_course_id($request->courses);
        $query = "insert into mdl_private_groups "
                . "(group_fio,"
                . "group_city,"
                . "group_phone,"
                . "group_email,"
                . "group_budget,"
                . "group_company,"
                . "courseid,"
                . "group_request,"
                . "request_date,"
                . "status,"
                . "group_reply) "
                . "values ('$request->group_fio' ,"
                . "'$request->group_city', "
                . "'$request->group_phone', "
                . "'$request->group_email', "
                . "'$request->group_budget', "
                . "'$request->group_company', "
                . "'$courseid', "
                . "'$request->group_request', "
                . "'" . time() . "', "
                . "'0',"
                . "'')";
        $this->db->query($query);

        $list.="<br/><div  class='form_div'>";
        $list.="<div class='panel panel-default' id='program_section' style='margin-bottom:0px;'>";
        $list.="<div class='panel-heading' style='text-align:left;'><h5 class='panel-title'>Private Groups</h5></div>";
        $list.="<div class='panel-body'>";

        $list.="<div class='container-fluid' style='text-align:center;'>";
        $list.="<span class='span6'>Request successfully submitted. We get back to you within 24h.</span>";
        $list.="</div>"; // end of container-fluid

        $list.="</div>"; // end of panel-body
        $list.="</div></div><br/>"; // end of panel panel-default

        return $list;
    }

}
