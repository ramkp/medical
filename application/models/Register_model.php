<?php

/**
 * Description of register_model
 *
 * @author sirromas
 */
class register_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_participants_dropbox() {
        $drop_down = "";
        $drop_down.="<div class='dropdown'>
        <a href='#' id='participants' data-toggle='dropdown' 
        class='dropdown-toggle'>Participants 
        <b class='caret'></b></a>
        <ul class='dropdown-menu'>";
        for ($i = 1; $i <= 50; $i++) {
            $drop_down.="<li><a href='#' id='tot_" . $i . "'>" . $i . "</a></li>";
        }
        $drop_down.="</ul></div>";
        return $drop_down;
    }

    public function get_coure_name_by_id($courseid) {
        $query = "select id, fullname "
                . "from mdl_course where id=$courseid";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $name = $row->fullname;
        }
        return $name;
    }

    public function get_selected_program($courseid) {
        $list = "";
        $name = $this->get_coure_name_by_id($courseid);
        $list.="<a id='courses' class='dropdown-toggle' 
                onclick='return false;' data-toggle='dropdown' 
            href='#'>$name</a>";
        return $list;
    }

    public function get_course_categories() {
        $drop_down = "";
        $drop_down.="<div class='dropdown'>
        <a href='#' id='categories' data-toggle='dropdown' class='dropdown-toggle' onClick='return false;'>Program type<b class='caret'></b></a>
        <ul class='dropdown-menu'>";
        $query = "select id,name from mdl_course_categories";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $drop_down.="<li><a href='#' id='cat_" . $row->id . "'>" . $row->name . "</a></li>";
        }
        $drop_down.="</ul></div>";
        return $drop_down;
    }

    public function get_courses_by_category($cat_id = null) {
        $drop_down = "";
        if ($cat_id != null) {
            $drop_down.="<div class='dropdown'>
            <a href='#' id='courses' data-toggle='dropdown' class='dropdown-toggle' onClick='return false;'>Program <b class='caret'></b></a>
            <ul class='dropdown-menu'>";

            $query = "select id, fullname from mdl_course where category=$cat_id";
            $result = $this->db->query($query);
            $num = $result->num_rows();
            if ($num > 0) {
                foreach ($result->result() as $row) {
                    $drop_down.="<li><a href='#' id='course_" . $row->id . "'>" . $row->fullname . "</a></li>";
                } // end while
            } // end if $num > 0
            $drop_down.="</ul></div>";
        } // end if $cat_id != null
        else {
            $drop_down.="<a href='#' data-toggle='dropdown' class='dropdown-toggle' onClick='return false;'>Program </a>";
        }
        return $drop_down;
    }

    public function get_group_registration_form($tot_participants) {
        $list = "";
        $list.="<div class='panel panel-default' id='group_common_section'>";
        $list.="<div class='panel-heading'style='text-align:left;'><h5 class='panel-title'>Group Registration </h5></div>";
        $list.="<div class='panel-body'>";


        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>Address*</span>";
        $list.="<span class='span2'><input type='text' id='group_addr' name='group_addr' ></span>";
        $list.="<span class='span2'>Business Or Institution*</span>";
        $list.="<span class='span2'><input type='text' id='group_inst' name='group_inst' ></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>ZIP Code*</span>";
        $list.="<span class='span2'><input type='text' id='group_zip' name='group_zip' ></span>";
        $list.="<span class='span2'>City*</span>";
        $list.="<span class='span2'><input type='text' id='group_city' name='group_city' ></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>State*</span>";
        $list.="<span class='span2'><input type='text' id='group_state' name='group_state' ></span>";
        $list.="<span class='span2'>Group name*</span>";
        $list.="<span class='span2'><input type='text' id='group_name' name='group_name' ></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'><a href='#' id='manual_group_registration' onClick='return false;'>Proceed to participants</a></span>";
        $list.="<span class='span4'>Have a lot of group participants? <a href='#' id='upload_group_file'>Upload users file</a></span><span class='span2' style='color:red;' id='group_common_errors'></span>";
        $list.="</div>";

        $list.="</div>";
        $list.="</div>";
        return $list;
    }

    public function get_group_manual_registration_form($tot_participants) {
        $list = "";
        $list.="<div class='panel panel-default' id='participants_details'>";
        $list.="<div class='panel-heading'style='text-align:left;'><h5 class='panel-title'>Participants Detailes</h5></div>";
        $list.="<div class='panel-body'>";
        for ($i = 1; $i <= $tot_participants; $i++) {

            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span2'>First name*</span>";
            $list.="<span class='span2'><input type='text' id='first_name_$i' name='first_name_$i' ></span>";
            $list.="<span class='span2'>Last name*</span>";
            $list.="<span class='span2'><input type='text' id='last_name_$i' name='last_name_$i'  ></span>";
            $list.="</div>";

            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span2'>Email*</span>";
            $list.="<span class='span2'><input type='text' id='email_$i' name='email_$i' ></span>";
            $list.="<span class='span2'>Phone*</span>";
            $list.="<span class='span2'><input type='text' id='phone_$i' name='phone_$i'  ></span>";
            $list.="</div>";
            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span8'><hr/></span>";
            $list.="</div>";
        } // end for

        $list.= "<div class='container-fluid' style='text-align:left;'";
        $list.= "<span class='span2'><a href='#' id='proceed_to_group_payment' onClick='return false;'>Proceed to payment</a></span>";
        $list.= "&nbsp <span style='color:red;' id='group_manual_form_err'></span>";
        $list.= "</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span8' style='text-align:center;display:none;' id='ajax_loading_group'><img src='http://cnausa.com/assets/img/ajax.gif' /></span>";
        $list.="</div>";

        $list.="</div>";
        $list.="</div>";
        return $list;
    }

    public function is_email_exists($email) {
        $query = "select username, deleted from mdl_user "
                . "where username='$email' and deleted=0";
        $result = $this->db->query($query);
        $num = $result->num_rows();
        return $num;
    }

    public function get_course_id($course_name) {
        $query = "select id, fullname from mdl_course "
                . "where fullname='$course_name'";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $id = $row->id;
        }
        return $id;
    }

    public function come_from() {
        $drop_down = "";
        $drop_down.="<div class='dropdown'>
        <a href='#' id='come_from' data-toggle='dropdown' class='dropdown-toggle' onClick='return false;'>Select <b class='caret'></b></a>
        <ul class='dropdown-menu'>";
        $drop_down.="<li><a href='#' id='Newspaper' onClick='return false;'>Newspaper</a></li>";
        $drop_down.="<li><a href='#' id='Magazine' onClick='return false;'>Magazine</a></li>";
        $drop_down.="<li><a href='#' id='Radio' onClick='return false;'>Radio</a></li>";
        $drop_down.="<li><a href='#' id='TV' onClick='return false;'>TV</a></li>";
        $drop_down.="<li><a href='#' id='Google' onClick='return false;'>Google</a></li>";
        $drop_down.="<li><a href='#' id='Microsoft' onClick='return false;'>Microsoft</a></li>";
        $drop_down.="<li><a href='#' id='Yahoo' onClick='return false;'>Yahoo</a></li>";
        $drop_down.="<li><a href='#' id='Twitter' onClick='return false;'>Twitter</a></li>";
        $drop_down.="<li><a href='#' id='Instagram' onClick='return false;'>Instagram</a></li>";
        $drop_down.="<li><a href='#' id='Other' onClick='return false;'>Other</a></li>";
        $drop_down.="</ul></div>";
        return $drop_down;
    }

    public function get_states_list() {
        $drop_down = "";
        $drop_down.="<div class='dropdown'>
        <a href='#' id='state' data-toggle='dropdown' class='dropdown-toggle' onClick='return false;'>State <b class='caret'></b></a>
        <ul class='dropdown-menu'>";
        $query = "select * from mdl_states";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $drop_down.="<li><a href='#' id='state_" . $row->id . "' onClick='return false;'>" . $row->state . "</a></li>";
        } // end while
        $drop_down.="</ul></div>";
        return $drop_down;
    }

    public function get_countries_list() {
        $drop_down = "";
        $drop_down.="<div class='dropdown'>
        <a href='#' id='country' data-toggle='dropdown' class='dropdown-toggle' onClick='return false;'>United States <b class='caret'></b></a>
        <ul class='dropdown-menu'>";
        $query = "select * from mdl_countries";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $drop_down.="<li><a href='#' id='country_" . $row->id . "' onClick='return false;'>" . $row->name . "</a></li>";
        }
        $drop_down.="</ul></div>";
        return $drop_down;
    }

    public function get_register_form($courseid = null) {
        $list = "";
        $cats = $this->get_course_categories();
        $courses = $this->get_courses_by_category();
        $participants = $this->get_participants_dropbox();
        $come_from = $this->come_from();
        $states = $this->get_states_list();
        $countries = $this->get_countries_list();

        // ****************** Program information **************************

        if ($courseid == null) {
            $list.="<br/><div  class='form_div'>";
            $list.="<div class='panel panel-default' id='program_section' style='margin-bottom:0px;'>";
            $list.="<div class='panel-heading' style='text-align:left;'><h5 class='panel-title'>Program information</h5></div>";
            $list.="<div class='panel-body'>";

            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span2'>Program type*</span>";
            $list.="<span class='span2'>$cats</span>";
            $list.="<span class='span2' id='cat_course'>$courses</span>";
            $list.="<span class='span2' id='program_err' style='color:red;'></span>";
            $list.="</div>"; // end of container-fluid

            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span2'>How did you hear about us*</span><span class='span2'>$come_from</span>";
            $list.="</div>"; // end of container-fluid

            $list.="</div>"; // end of panel-body
            $list.="</div>"; // end of panel panel-default
        } // end if $courseid==null
        else {
            $selected_program = $this->get_selected_program($courseid);
            $list.="<br/><div  class='form_div'>";
            $list.="<div class='panel panel-default' id='program_section' style='margin-bottom:0px;'>";
            $list.="<div class='panel-heading' style='text-align:left;'><h5 class='panel-title'>Program information</h5></div>";
            $list.="<div class='panel-body'>";

            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span2'>Selected program:</span>";
            $list.="<span class='span2'>$selected_program</span>";
            $list.="</div>"; // end of container-fluid

            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span2'>How did you hear about us*</span><span class='span2'>$come_from</span><span style='color:red;' id='program_err' class='span2'></span>";
            $list.="</div>"; // end of container-fluid

            $list.="</div>"; // end of panel-body
            $list.="</div>"; // end of panel panel-default
        }

        // ********************  Registration type **************************        
        $list.="<div class='panel panel-default' id='type_section'>";
        $list.="<div class='panel-heading'style='text-align:left;'><h5 class='panel-title'>Registration type</h5></div>";
        $list.="<div class='panel-body'>";
        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'><input type='radio' name='type' id='me' value='me' checked>Register Myself</span>";
        $list.="<span class='span2'><input type='radio' name='type' id='group' value='group' >Register Group</span>";
        $list.="<span class='span2' id='gr_num'>$participants</span>";
        $list.="<span class='span2' id='type_err' style='color:red;'></span>";
        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="</div>"; // end of container-fluid
        $list.="</div>"; // end of panel-body
        $list.="</div>"; // end of panel panel-default
        //
        // ********************  Individual registration form **************************        
        $list.="<div class='panel panel-default' id='personal_section'>";
        $list.="<div class='panel-heading'style='text-align:left;'><h5 class='panel-title'>User details </h5></div>";
        $list.="<div class='panel-body'>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2' >First name*</span>";
        $list.="<span class='span2' ><input type='text' id='first_name' name='first_name' ></span>";
        $list.="<span class='span2' >Last name*</span>";
        $list.="<span class='span2' ><input type='text' id='last_name' name='last_name'  ></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>Email*</span>";
        $list.="<span class='span2'><input type='text' id='email' name='email' ></span>";
        $list.="<span class='span2'>Phone*</span>";
        $list.="<span class='span2'><input type='text' id='phone' name='phone'  ></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>Address*</span>";
        $list.="<span class='span2'><input type='text' id='addr' name='addr' ></span>";
        $list.="<span class='span2'>Business Or Institution</span>";
        $list.="<span class='span2'><input type='text' id='inst' name='inst' ></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>ZIP Code*</span>";
        $list.="<span class='span2'><input type='text' id='zip' name='zip' ></span>";
        $list.="<span class='span2'>City*</span>";
        $list.="<span class='span2'><input type='text' id='city' name='city' ></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>State*</span>";
        $list.="<span class='span2'>$states</span>";
        $list.="<span class='span2'>Country*</span>";
        $list.="<span class='span2'>$countries</span>";
        $list.="</div>";

        /*
        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'><a href='#' id='proceed_to_personal_payment' onClick='return false;'>Proceed to payment</a></span>&nbsp;<span style='color:red;' id='personal_err'></span>";
        $list.="</div>";
        */
        
        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'><a href='#' id='p_options_p' onClick='return false;'>Payment options</a></span>&nbsp;<span style='color:red;' id='personal_err'></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span8' style='text-align:center;display:none;' id='ajax_loading_personal'><img src='http://cnausa.com/assets/img/ajax.gif' /></span";
        $list.="</div>";

        $list.="</div>";
        $list.="</div></div>";

        $list.= "</div></div>"; // end of form div

        return $list;
    }

}
