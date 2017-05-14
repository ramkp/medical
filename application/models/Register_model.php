<?php

/**
 * Description of register_model
 *
 * @author sirromas
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/Payment.php';

class register_model extends CI_Model {

    public $host;

    public function __construct() {
        parent::__construct();
        $this->load->database();
        //$this->load->library('session');
        $this->host = $_SERVER['SERVER_NAME'];
    }

    function get_card_types_dropbox() {
        $drop_down = "";
        $drop_down.="<select id='card_type'>";
        $drop_down.="<option value='American Express'>American Express</option>";
        $drop_down.="<option value='Discover' >Discover</option>";
        $drop_down.="<option value='Master' >Master</option>";
        $drop_down.="<option value='Visa' >Visa</option>";
        $drop_down.="</select>";
        return $drop_down;
    }

    function get_year_drop_box($alter = false) {
        $drop_down = "";
        if ($alter === false) {
            $drop_down.= "<select id='card_year' style='width: 75px;'>";
        } // end if
        else {
            $drop_down.= "<select id='card_year2' style='width: 75px;'>";
        } // end else
        $drop_down.="<option value='0' selected>Year</option>";
        $drop_down.="<option value='2016'>2016</option>";
        $drop_down.="<option value='2017'>2017</option>";
        $drop_down.="<option value='2018'>2018</option>";
        $drop_down.="<option value='2019'>2019</option>";
        $drop_down.="<option value='2020'>2020</option>";
        $drop_down.="<option value='2021'>2021</option>";
        $drop_down.="<option value='2022'>2022</option>";
        $drop_down.="<option value='2023'>2023</option>";
        $drop_down.="<option value='2024'>2024</option>";
        $drop_down.="<option value='2025'>2025</option>";
        $drop_down.="<option value='2026'>2026</option>";
        $drop_down.="<option value='2027'>2027</option>";
        $drop_down.="<option value='2028'>2028</option>";
        $drop_down.="<option value='2029'>2029</option>";
        $drop_down.="<option value='2030'>2030</option>";
        $drop_down.="</select>";
        return $drop_down;
    }

    function get_month_drop_box($alter = false) {
        $drop_down = "";
        $items = "<option value='01'>01</option>
                <option value='0' selected>Month</option>
                <option value='02'>02</option>
                <option value='03'>03</option>
                <option value='04'>04</option>
                <option value='05'>05</option>
                <option value='06'>06</option>
                <option value='07'>07</option>
                <option value='08'>08</option>
                <option value='09'>09</option>
                <option value='10'>10</option>
                <option value='11'>11</option>
                <option value='12'>12</option>";
        if ($alter === false) {
            $drop_down.= "<select id='card_month' style='width: 65px;'>";
        } // end if
        else {
            $drop_down.= "<select id='card_month2' style='width: 65px;'>";
        } // end else
        $drop_down.=$items;
        $drop_down.="</select>";
        return $drop_down;
    }

    public function get_participants_dropbox() {
        $drop_down = "";
        //$drop_down.="<div class='dropdown'>
        //<a href='#' id='participants' data-toggle='dropdown' 
        //class='dropdown-toggle'>Participants 
        //<b class='caret'></b></a>
        //<ul class='dropdown-menu'>";
        $drop_down.="<select id='participants' style='width:120px;'>";
        $drop_down.="<option value='0' selected>Participants</option>";
        for ($i = 1; $i <= 50; $i++) {
            $drop_down.="<option value='$i'>$i</option>";
        }
        $drop_down.="</select>";
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
        $list.=$name;
        return $list;
    }

    public function get_course_categories() {
        $drop_down = "";

        /*
         * 
          $drop_down.="<div class='dropdown'>
          <a href='#' id='categories' data-toggle='dropdown' class='dropdown-toggle' onClick='return false;'>Program type<b class='caret'></b></a>
          <ul class='dropdown-menu'>";
          $query = "select id,name from mdl_course_categories";
          $result = $this->db->query($query);
          foreach ($result->result() as $row) {
          $drop_down.="<li><a href='#' id='cat_" . $row->id . "' onClick='return false;'>" . $row->name . "</a></li>";
          }
          $drop_down.="</ul></div>";
         * 
         */

        $drop_down.="<select id='categories' style='width:120px;'>";
        $drop_down.="<option value='0' selected='selected'>Program type</option>";
        $query = "select id,name from mdl_course_categories";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $drop_down.="<option value='$row->id'>$row->name</option>";
        }
        $drop_down.="</select>";
        //$drop_down.="<select id='categories'>";

        return $drop_down;
    }

    public function get_courses_by_category($cat_id = null) {
        $drop_down = "";
        if ($cat_id != null) {
            $drop_down.="<div class='dropdown'>
            <a href='#' id='register_courses' data-toggle='dropdown' class='dropdown-toggle' onClick='return false;'>Program <b class='caret'></b></a>
            <ul class='dropdown-menu'>";

            $query = "select id, fullname from mdl_course where category=$cat_id";
            $result = $this->db->query($query);
            $num = $result->num_rows();
            if ($num > 0) {
                foreach ($result->result() as $row) {
                    $drop_down.="<li><a href='#' id='course_" . $row->id . "' onClick='return false;'>" . $row->fullname . "</a></li>";
                } // end while
            } // end if $num > 0
            $drop_down.="</ul></div>";
        } // end if $cat_id != null
        else {
            $drop_down.="<select id='register_courses' style='width:120px;'><option value='0'>Program</option></select>";
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
        $come_from = $this->come_from();
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
        $list.="<span class='span2'>How did you hear about us?*</span>";
        $list.="<span class='span2'>$come_from</span>";
        $list.="<span class='span4'><input type='checkbox' id='gr_policy'> I have read and agree to Terms and Conditions</span>";
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
        $list.="<span class='span8' style='text-align:center;display:none;' id='ajax_loading_group'><img src='https://$this->host/assets/img/ajax.gif' /></span>";
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
        $drop_down = "<select id='come_from' style='width:120px;'>";
        $drop_down.="<option value='0' selected>Select</option>";
        $drop_down.="<option value='Newspaper'>Newspaper</option>";
        $drop_down.="<option value='Magazine' >Magazine</option>";
        $drop_down.="<option value='Radio' >Radio</option>";
        $drop_down.="<option value='TV'>TV</option>";
        $drop_down.="<option value='Google' >Google</option>";
        $drop_down.="<option value='Microsoft' >Microsoft</option>";
        $drop_down.="<option value='Yahoo' >Yahoo</option>";
        $drop_down.="<option value='Twitter' >Twitter</option>";
        $drop_down.="<option value='Instagram' >Instagram</option>";
        $drop_down.="<option value='Other'>Other</option>";
        $drop_down.="</select>";
        return $drop_down;
    }

    public function get_states_list($bill = FALSE) {
        $drop_down = "";
        //$drop_down.="<div class='dropdown'>
        //<a href='#' id='state' data-toggle='dropdown' class='dropdown-toggle' onClick='return false;'>State <b class='caret'></b></a>
        //<ul class='dropdown-menu'>";
        if ($bill == FALSE) {
            $drop_down.="<select id='state' style='width:120px;'>";
        } // end if $bill == FALSE 
        else {
            $drop_down.="<select id='state2' style='width:120px;'>";
        } // end else
        $drop_down.="<option value='0' selected>State</option>";
        $query = "select * from mdl_states";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $drop_down.="<option value='$row->id'>$row->state</option>";
        } // end while
        $drop_down.="</select>";
        return $drop_down;
    }

    public function get_register_course_states_list($courseid = null) {
        $drop_down = "";
        $drop_down.="<select id='register_state' style='width:120px;'>";
        $drop_down.="<option value='0' selected>State</option>";

        if ($courseid != null) {
            $query = "";
            $result = $this->db->query($query);
            foreach ($result->result() as $row) {
                //$drop_down.="<option value='$row->id'>$row->state</option>";
            } // end while
        } // end if $courseid != null        

        $drop_down.="</select>";
        return $drop_down;
    }

    public function get_register_course_cities_list($courseid = null) {
        $drop_down = "";
        $drop_down.="<select id='register_cities' style='width:120px;'>";
        //$drop_down.="<select id='register_cities'>";
        $drop_down.="<option value='0' selected>City</option>";
        if ($courseid != null) {
            $query = "";
            $result = $this->db->query($query);
            foreach ($result->result() as $row) {
                //$drop_down.="<option value='$row->id'>$row->state</option>";
            } // end while
        } // end if $courseid != null        

        $drop_down.="</select>";
        return $drop_down;
    }

    /*
     * 
      function get_states_list() {
      $drop_down = "";
      $drop_down.="<select id='bill_state' style='width:140px;' name='bill_state'>";
      $drop_down.="<option value='0' selected>State</option>";
      $query = "select * from mdl_states";
      $result = $this->db->query($query);
      while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
      $drop_down.="<option value='" . $row['id'] . "'>" . $row['state'] . "</option>";
      } // end while
      $drop_down.="</select>";
      return $drop_down;
      }
     * 
     */

    public function get_countries_list($alter = false) {
        $drop_down = "";

        if ($alter === false) {
            $drop_down.="<select id='country' style='width:120px;'>";
        } // end if
        else {
            $drop_down.="<select id='country2' style='width:120px;'>";
        } // end else
        $query = "select * from mdl_countries";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            if ($row->name == 'United States') {
                $drop_down.="<option value='$row->id' selected>$row->name</option>";
            } // end if $row->name=='United States'
            else {
                $drop_down.="<option value='$row->id'>$row->name</option>";
            }
        }
        $drop_down.="</select>";
        return $drop_down;
    }

    public function get_program_schedule($slotid) {

        $list = "";
        $query = "select * from mdl_scheduler_slots where id=$slotid";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $locations = explode("/", $row->appointmentlocation);
            if (count($locations) == 0) {
                $locations = explode(",", $row->appointmentlocation);
            }
            $state = $locations[0];
            $city = $locations[1];
            $location = $city . " , " . $state;

            $hdate = date('m-d-Y', $row->starttime);
            $list.=$hdate . "<br>" . $location . "<br/>" . $row->notes;
        }
        return $list;
    }

    public function get_register_form($courseid = null, $slotid = null) {
        $list = "";
        $cats = $this->get_course_categories();
        $courses = $this->get_courses_by_category();
        $participants = $this->get_participants_dropbox();
        $come_from = $this->come_from();
        $states = $this->get_states_list();
        $register_state = $this->get_register_course_states_list();
        $countries = $this->get_countries_list();
        $cities = $this->get_register_course_cities_list();

        // ****************** Program information **************************

        if ($courseid == null) {
            $list.="<br/><div  class='form_div'>";

            //$list.="<div class='container-fluid' tyle='text-align:center;'>";
            //$list.="<span class='span8'>$policy_dialog</span>";
            //$list.="</div>";

            $list.="<div class='panel panel-default' id='program_section' style='margin-bottom:0px;'>";
            $list.="<div class='panel-heading' style='text-align:left;'><h5 class='panel-title'>Program information</h5></div>";
            $list.="<div class='panel-body'>";

            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span2'>$cats</span>";
            $list.="<span class='span2' id='cat_course'>$courses</span>";
            $list.="<span class='span2' id='register_states_container'>$register_state</span>";
            $list.="<span class='span2' id='register_cities_container'>$cities</span>";
            $list.="<span class='span2' id='program_err' style='color:red;'></span>";
            $list.="</div>"; // end of container-fluid

            $list.="<div class='container-fluid' style='text-align:center;'>";
            $list.="<span class='span9'>Already have an account? Please <a href='/index.php/login' target='_blank'>sign-in</a> and apply for course you need.</span>";
            $list.="</div>"; // end of container-fluid
            //$list.="<div class='container-fluid' style='text-align:center;font-weight:bold;'>";
            //$list.="<span class='span9'>Registration is not available for now, please try again later 877 741 1996. </span>";
            //$list.="</div>"; // end of container-fluid

            $list.="</div>"; // end of panel-body
            $list.="</div>"; // end of panel panel-default
        } // end if $courseid==null
        else {
            $selected_program = $this->get_selected_program($courseid);
            $program_schedule = $this->get_program_schedule($slotid);
            $list.="<br/><div  class='form_div'>";
            $list.="<div class='panel panel-default' id='program_section' style='margin-bottom:0px;'>";
            $list.="<div class='panel-heading' style='text-align:left;'><h5 class='panel-title'>Program information</h5></div>";
            $list.="<div class='panel-body'>";

            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span2'>Selected program:</span>";
            $list.="<span class='span4'>$selected_program</span>";
            $list.="<span class='span1'><input type='hidden' value='$slotid' id='register_cities'></span>";
            $list.="<span class='span1'><input type='hidden' value='$courseid' id='register_courses'></span>";
            $list.="<span class='span3'>$program_schedule</span>";
            $list.="</div>"; // end of container-fluid
            //$list.="<div class='container-fluid' style='text-align:center;font-weight:bold;'>";
            //$list.="<span class='span9'>Registration is not available for now, please try again later or call 877 741 1996. </span>";
            //$list.="</div>"; // end of container-fluid

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
        $list.="<span class='span2'>St Address*</span>";
        $list.="<span class='span2'><input type='text' id='addr' name='addr' ></span>";
        $list.="<span class='span2'>City*</span>";
        $list.="<span class='span2'><input type='text' id='city' name='city' ></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>State*</span>";
        $list.="<span class='span2'>$states</span>";
        $list.="<span class='span2'>Country*</span>";
        $list.="<span class='span2' id='register_cities_container'>$countries</span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>ZIP Code*</span>";
        $list.="<span class='span2'><input type='text' id='zip' name='zip' ></span>";
        $list.="<span class='span2'>Business Or Institution</span>";
        $list.="<span class='span2'><input type='text' id='inst' name='inst' ></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>Phone*</span>";
        $list.="<span class='span2'><input type='text' id='phone' name='phone'  ></span>";
        $list.="<span class='span2'>Email*</span>";
        $list.="<span class='span2'><input type='text' id='email' name='email' ></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>How did you hear about us?*</span>";
        $list.="<span class='span2'>$come_from</span>";
        //$list.="<span class='span4'><input type='checkbox' id='policy'> I have read and agree to Terms and Conditions</span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span4' id='personal_err' style='color:red;'></span>";
        $list.="</div>";

        // Payment options link
        $list.="<div class='container-fluid' style='text-align:center;'>";
        $list.="<span class='span8'><a href='#' id='p_options_p' onClick='return false;'>Next</a></span>&nbsp;<span style='color:red;' id=''></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span8' style='text-align:center;display:none;' id='ajax_loading_personal'><img src='https://$this->host/assets/img/ajax.gif' /></span";
        $list.="</div>";

        $list.="</div>";
        $list.="</div></div>";

        $list.= "</div></div>"; // end of form div

        return $list;
    }

    public function get_register_form2($courseid = null, $slotid = null) {
        $list = "";
        $cats = $this->get_course_categories();
        $courses = $this->get_courses_by_category();
        $participants = $this->get_participants_dropbox();
        $come_from = $this->come_from();
        $states = $this->get_states_list();
        $states2 = $this->get_states_list(true);
        $register_state = $this->get_register_course_states_list();
        $countries = $this->get_countries_list();
        $countries2 = $this->get_countries_list(true);
        $cities = $this->get_register_course_cities_list();
        $card_year = $this->get_year_drop_box();
        $card_month = $this->get_month_drop_box();
        $card_year2 = $this->get_year_drop_box(true);
        $card_month2 = $this->get_month_drop_box(true);

        // ****************** Program information **************************

        if ($courseid == null) {
            $list.="<br/><div  class='form_div'>";

            //$list.="<div class='container-fluid' tyle='text-align:center;'>";
            //$list.="<span class='span8'>$policy_dialog</span>";
            //$list.="</div>";

            $list.="<div class='panel panel-default' id='program_section' style='margin-bottom:0px;'>";
            $list.="<div class='panel-heading' style='text-align:left;'><h5 class='panel-title'>Program information</h5></div>";
            $list.="<div class='panel-body'>";

            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span2'>$cats</span>";
            $list.="<span class='span2' id='cat_course'>$courses</span>";
            $list.="<span class='span2' id='register_states_container'>$register_state</span>";
            $list.="<span class='span2' id='register_cities_container'>$cities</span>";
            $list.="<span class='span2' id='program_err' style='color:red;'></span>";
            $list.="</div>"; // end of container-fluid

            $list.="<div class='container-fluid' style='text-align:center;'>";
            $list.="<span class='span9'>Already have an account? Please <a href='/index.php/login' target='_blank'>sign-in</a> and apply for course you need.</span>";
            $list.="</div>"; // end of container-fluid
            $list.="</div>"; // end of panel-body
            $list.="</div>"; // end of panel panel-default 
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
            // ********************  Individual registration form **************************        
            $list.="<div class='panel panel-default' id='personal_section'>";
            $list.="<div class='panel-heading'style='text-align:left;'><h5 class='panel-title'>User details </h5></div>";
            $list.="<div class='panel-body'>";

            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span2' >First name*</span>";
            $list.="<span class='span2' ><input type='text' required id='first_name' name='first_name' ></span>";
            $list.="<span class='span2' >Last name*</span>";
            $list.="<span class='span2' ><input type='text' required id='last_name' name='last_name'  ></span>";
            $list.="</div>";

            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span2'>Mailing Address*</span>";
            $list.="<span class='span2'><input type='text' required id='addr' name='addr' ></span>";
            $list.="<span class='span2'>City*</span>";
            $list.="<span class='span2'><input type='text' required id='city' name='city' ></span>";
            $list.="</div>";

            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span2'>State*</span>";
            $list.="<span class='span2'>$states</span>";
            $list.="<span class='span2'>Country*</span>";
            $list.="<span class='span2' id='register_cities_container'>$countries</span>";
            $list.="</div>";

            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span2'>ZIP Code*</span>";
            $list.="<span class='span2'><input type='text' required id='zip' name='zip' ></span>";
            $list.="<span class='span2'>Email*</span>";
            $list.="<span class='span2'><input type='text' required id='email' name='email' ></span>";
            $list.="</div>";

            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span2'>Phone*</span>";
            $list.="<span class='span2'><input type='text' required id='phone' name='phone'></span>";
            $list.="<span class='span2'>How did you hear about us?</span>";
            $list.="<span class='span2'>$come_from</span>";
            $list.="</div>";

            $list.="<div class='container-fluid' style='text-align:center;'>";
            $list.="<span class='span8'><hr></span>";
            $list.="</div>";

            // ********************  Payment section ********************
            $list.="<div class='container-fluid' style='text-align:left;font-weight:bold;display:none;' id='course_fee'>";
            $list.="<span class='span2'>Selected program</span>";
            $list.="<span class='span2' id='dyn_course_name'></span>";
            $list.="<span class='span2'>Fee</span>";
            $list.="<span class='span2' id='dyn_course_fee'></span>";
            $list.="</div>";

            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span2'>Card Holder First name*</span>";
            $list.="<span class='span2'><input type='text' required id='b_fname' name='b_fname' placeholder='Firstname' required></span>";
            $list.="<span class='span2'>Card Holder Last name*</span>";
            $list.="<span class='span2'><input type='text' required id='b_lname' name='b_lname' placeholder='Lastname' required></span>";
            $list.="</div>";

            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span2'>Card number*</span>";
            $list.="<span class='span2'><input type='text' id='card_no2' name='card_no2'  ></span>";
            $list.="<span class='span2'>CVV*</span>";
            $list.="<span class='span2'><input type='text' id='cvv2' name='cvv2'  ></span>";
            $list.="</div>";

            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span2'>&nbsp;</span>";
            $list.="<span class='span2'>&nbsp;</span>";
            $list.="<span class='span2'>Expiration Date*</span>";
            $list.="<span class='span2'>" . $card_month2 . "&nbsp;&nbsp;&nbsp;" . $card_year2 . "</span>";
            $list.="</div>";

            $list.="<div class='container-fluid' style='text-align:center;'>";
            $list.="<span class='span8'><input type='checkbox' id='da'> &nbsp; If billing address is different</span>";
            $list.="</div>";

            $list.="<div id='diff_address' style='display:none;'>";

            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span2'>Billing Address*</span>";
            $list.="<span class='span2'><input type='text' id='addr2' name='addr2'  ></span>";
            $list.="<span class='span2'>City*</span>";
            $list.="<span class='span2'><input type='text' id='city2' name='city2'  ></span>";
            $list.="</div>";

            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span2'>State*</span>";
            $list.="<span class='span2'>$states2</span>";
            $list.="<span class='span2'>Country*</span>";
            $list.="<span class='span2' id='register_cities_container'>$countries2</span>";

            $list.="</div>";

            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span2'>Zip code*</span>";
            $list.="<span class='span2'><input type='text' id='zip2' name='zip2'  ></span>";
            $list.="<span class='span2'>Receipt email*</span>";
            $list.="<span class='span2'><input type='text' id='email2' name='email2'></span>";
            $list.="</div>";

            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span2'>Phone*</span>";
            $list.="<span class='span2'><input type='text' id='phone2' name='phone2'></span>";
            $list.="</div>";

            $list.="</div>";

            $list.="<div class='container-fluid' style='text-align:center;'>";
            $list.="<span class='span8' id='personal_err' style='color:red;'></span>";
            $list.="</div>";

            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span8' style='text-align:center;display:none;' id='ajax_loading_payment'><img src='https://$this->host/assets/img/ajax.gif' /></span>";
            $list.="</div>";

            $list.="<div class='container-fluid' style='text-align:center;'>";
            $list.="<span class='span8'>By clicking the 'I Agree, Submit' button, you confirm you have reviewed and agree to the Pay by Computer Terms & Conditions (<a href='#' onClick='return false;' id='policy'>click to view).</a></span>";
            $list.="</div>";

            $list.="<div class='container-fluid' style='text-align:center;'>";
            $list.="<span class='span8'><button class='btn btn-primary' id='make_payment_personal2'>I Agree, Submit</button></span>";
            $list.="</div>";

            $list.="</div></div></div></div>";
        } // end if $courseid==null
        else {
            $selected_program = $this->get_selected_program($courseid);
            $program_schedule = $this->get_program_schedule($slotid);
            $p = new Payment();
            $courseObj = json_decode($p->get_course_data($courseid, $slotid));
            $list.="<br/><div  class='form_div'>";
            $list.="<div class='panel panel-default' id='program_section' style='margin-bottom:0px;'>";
            $list.="<div class='panel-heading' style='text-align:left;'><h5 class='panel-title'>Program information</h5></div>";
            $list.="<div class='panel-body'>";

            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span2'>Selected program:</span>";
            $list.="<span class='span4'>$selected_program</span>";
            $list.="<span class='span1'><input type='hidden' value='$slotid' id='selected_slot'></span>";
            $list.="<span class='span1'><input type='hidden' value='$courseid' id='selected_course'></span>";
            $list.="<span class='span1'><input type='hidden' value='$courseObj->raw_cost' id='payment_sum'></span>";
            $list.="<span class='span3'>$program_schedule</span>";
            $list.="</div>"; // end of container-fluid

            $list.="</div>"; // end of panel-body
            $list.="</div>"; // end of panel panel-default
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
            $list.="<span class='span2'>Mailing Address*</span>";
            $list.="<span class='span2'><input type='text' id='addr' name='addr' ></span>";
            $list.="<span class='span2'>City*</span>";
            $list.="<span class='span2'><input type='text' id='city' name='city' ></span>";
            $list.="</div>";

            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span2'>State*</span>";
            $list.="<span class='span2'>$states</span>";
            $list.="<span class='span2'>Country*</span>";
            $list.="<span class='span2' id='register_cities_container'>$countries</span>";
            $list.="</div>";

            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span2'>ZIP Code*</span>";
            $list.="<span class='span2'><input type='text' id='zip' name='zip' ></span>";
            $list.="<span class='span2'>Email*</span>";
            $list.="<span class='span2'><input type='text' id='email' name='email' ></span>";
            $list.="</div>";

            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span2'>Phone*</span>";
            $list.="<span class='span2'><input type='text' id='phone' name='phone'  ></span>";
            $list.="<span class='span2'>How did you hear about us?</span>";
            $list.="<span class='span2'>$come_from</span>";
            $list.="</div>";

            $list.="<div class='container-fluid' style='text-align:center;'>";
            $list.="<span class='span8'><hr></span>";
            $list.="</div>";

            /*             * ********************* Payment section ********************* */
            $list.="<div class='container-fluid' style='text-align:left;font-weight:bold;' id='course_fee'>";
            $list.="<span class='span2'>Selected program</span>";
            $list.="<span class='span2' id='dyn_course_name'>$selected_program</span>";
            $list.="<span class='span2'>Fee</span>";
            $list.="<span class='span2' id='dyn_course_fee'>$courseObj->cost</span>";
            $list.="</div>";

            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span2'>Card Holder Name*</span>";
            $list.="<span class='span2'><input type='text' required id='billing_name' name='billing_name' ></span>";
            $list.="<span class='span2'>CVV*</span>";
            $list.="<span class='span2'><input type='text' id='cvv' name='cvv2'  ></span>";
            $list.="</div>";

            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span2'>Card number*</span>";
            $list.="<span class='span2'><input type='text' id='card_no2' name='card_no2'  ></span>";
            $list.="<span class='span2'>Expiration Date*</span>";
            $list.="<span class='span2'>" . $card_month2 . "&nbsp;&nbsp;&nbsp;" . $card_year2 . "</span>";
            $list.="</div>";

            $list.="<div class='container-fluid' style='text-align:center;'>";
            $list.="<span class='span8'><input type='checkbox' id='da'> &nbsp; The card address is differrent</span>";
            $list.="</div>";

            $list.="<div id='diff_address' style='display:none;'>";

            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span2'>Mailing Address*</span>";
            $list.="<span class='span2'><input type='text' id='addr2' name='addr2'  ></span>";
            $list.="<span class='span2'>City*</span>";
            $list.="<span class='span2'><input type='text' id='city2' name='city2'  ></span>";
            $list.="</div>";

            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span2'>State*</span>";
            $list.="<span class='span2'>$states2</span>";
            $list.="<span class='span2'>Country*</span>";
            $list.="<span class='span2' id='register_cities_container'>$countries2</span>";

            $list.="</div>";

            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span2'>Zip code*</span>";
            $list.="<span class='span2'><input type='text' id='zip2' name='zip2'  ></span>";
            $list.="<span class='span2'>Receipt email*</span>";
            $list.="<span class='span2'><input type='text' id='email2' name='email2'></span>";
            $list.="</div>";

            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span2'>Phone*</span>";
            $list.="<span class='span2'><input type='text' id='phone2' name='phone2'></span>";
            $list.="</div>";

            $list.="</div>";

            $list.="<div class='container-fluid' style='text-align:center;'>";
            $list.="<span class='span8' id='personal_err' style='color:red;'></span>";
            $list.="</div>";

            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span8' style='text-align:center;display:none;' id='ajax_loading_payment'><img src='https://$this->host/assets/img/ajax.gif' /></span>";
            $list.="</div>";

            $list.="<div class='container-fluid' style='text-align:center;'>";
            $list.="<span class='span8'>By clicking the 'I Agree, Submit' button, you confirm you have reviewed and agree to the Pay by Computer Terms & Conditions (<a href='#' onClick='return false;' id='policy'>click to view).</a></span>";
            $list.="</div>";

            $list.="<div class='container-fluid' style='text-align:center;'>";
            $list.="<span class='span8'><button class='btn btn-primary' id='make_payment_personal2'>I Agree, Submit</button></span>";
            $list.="</div>";

            $list.="</div>";
            $list.="</div></div>";

            return $list;
        }

        return $list;
    }

    function get_register_paypal_button($coursid, $slotid, $userid) {
        $list = "";


        $list.="<form action='https://www.sandbox.paypal.com/cgi-bin/webscr' method='post' id='paypal_group_renew_submit'>"; // sandbox
        $list.="<input type='hidden' name='cmd' value='_xclick'>";
        $list.="<INPUT TYPE='hidden' name='charset' value='utf-8'>";
        //$list.="<input type='hidden' name='business' value='contato@iprovida.org.br'>"; // production
        $list.="<input type='hidden' name='business' value='sirromas-facilitator@outlook.com'>"; // sandbox
        $list.="<input type='hidden' name='item_name' value='Group certificate renewal'>
        <input type='hidden' name='amount' value='$cost'>
        <input type='hidden' name='custom' value='$courseid/$userid'>    
        <INPUT TYPE='hidden' NAME='currency_code' value='USD'>    
        <INPUT TYPE='hidden' NAME='return' value='https://medical2.com/payments/paypal_receive/'>
        <input type='image' id='paypal_btn' src='https://www.sandbox.paypal.com/en_US/i/btn/btn_buynow_LG.gif' border='0' name='submit' alt='PayPal - The safer, easier way to pay online!'>
        <img alt='' border='0' src='https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif' width='1' height='1'>
        </form>";

        return $list;
    }

    function get_brain_register_form($courseid = null, $slotid = null) {
        $list = "";
        $cats = $this->get_course_categories();
        $courses = $this->get_courses_by_category();
        //$participants = $this->get_participants_dropbox();
        $come_from = $this->come_from();
        $states = $this->get_states_list();
        $register_state = $this->get_register_course_states_list();
        $countries = $this->get_countries_list();
        $cities = $this->get_register_course_cities_list();

        // ****************** Program information **************************

        if ($courseid == null) {
            $list.="<br/><div  class='form_div'>";

            $list.="<div class='panel panel-default' id='program_section' style='margin-bottom:0px;'>";
            $list.="<div class='panel-heading' style='text-align:left;'><h5 class='panel-title'>Program information</h5></div>";
            $list.="<div class='panel-body'>";

            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span2'>$cats</span>";
            $list.="<span class='span2' id='cat_course'>$courses</span>";
            $list.="<span class='span2' id='register_states_container'>$register_state</span>";
            $list.="<span class='span2' id='register_cities_container'>$cities</span>";
            $list.="<span class='span2' id='program_err' style='color:red;'></span>";
            $list.="</div>"; // end of container-fluid

            $list.="<div class='container-fluid' style='text-align:center;'>";
            $list.="<span class='span9'>Already have an account? Please <a href='/index.php/login' target='_blank'>sign-in</a> and apply for course you need.</span>";
            $list.="</div>"; // end of container-fluid
            $list.="</div>"; // end of panel-body
            $list.="</div>"; // end of panel panel-default 
            // ********************  Individual registration form **************************        
            $list.="<div class='panel panel-default' id='personal_section'>";
            $list.="<div class='panel-heading'style='text-align:left;'><h5 class='panel-title'>User details </h5></div>";
            $list.="<div class='panel-body'>";

            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span2' >First name<span style='color:red;'>*</span></span>";
            $list.="<span class='span2' ><input type='text' required id='first_name' name='first_name' ></span>";
            $list.="<span class='span2' >Last name<span style='color:red;'>*</span></span>";
            $list.="<span class='span2' ><input type='text' required id='last_name' name='last_name'  ></span>";
            $list.="</div>";

            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span2'>Mailing Address<span style='color:red;'>*</span></span>";
            $list.="<span class='span2'><input type='text' required id='addr' name='addr' ></span>";
            $list.="<span class='span2'>City<span style='color:red;'>*</span></span>";
            $list.="<span class='span2'><input type='text' required id='city' name='city' ></span>";
            $list.="</div>";

            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span2'>State<span style='color:red;'>*</span></span>";
            $list.="<span class='span2'>$states</span>";
            $list.="<span class='span2'>Country<span style='color:red;'>*</span></span>";
            $list.="<span class='span2' id='register_cities_container'>$countries</span>";
            $list.="</div>";

            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span2'>ZIP Code<span style='color:red;'>*</span></span>";
            $list.="<span class='span2'><input type='text' required id='zip' name='zip' ></span>";
            $list.="<span class='span2'>Email<span style='color:red;'>*</span></span>";
            $list.="<span class='span2'><input type='text' required id='email' name='email' ></span>";
            $list.="</div>";

            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span2'>Phone<span style='color:red;'>*</span></span>";
            $list.="<span class='span2'><input type='text' required id='phone' name='phone'></span>";
            $list.="<span class='span2'>How did you hear about us?</span>";
            $list.="<span class='span2'>$come_from</span>";
            $list.="</div>";

            $list.="<div class='container-fluid' style='text-align:left;font-weight:bold;display:none;' id='course_fee'>";
            $list.="<span class='span2'>Selected program</span>";
            $list.="<span class='span2' id='dyn_course_name'></span>";
            $list.="<span class='span2'>Fee</span>";
            $list.="<span class='span2' id='dyn_course_fee'></span>";
            $list.="</div>";

            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span2'><img
                        src='https://www.merchantequip.com/image/?logos=v|m|a|d&height=16' alt='Merchant Equipment Store Credit Card Logos'/></span><span class='span2'><input type='radio' name='ptype' id='ptype' value='card' checked><span style='font-size:14px;font-weight:bold;color:blue;'>Pay with Credit or debit card</span></span>";
            $list.="<span class='span2'>&nbsp;</span><span class='span2'><input type='radio' name='ptype' id='ptype' value='paypal'><img
                        src='https://www.merchantequip.com/image/?logos=p&height=16' alt='Merchant Equipment Store Credit Card Logos'/><span style='font-size:14px;font-weight:bold;color:blue;'> Pay with Paypal</span></span>";
            $list.="</div>";

            $list.="<div class='container-fluid' style='text-align:center;'>";
            $list.="<span class='span8' id='personal_err' style='color:red;'></span>";
            $list.="</div>";

            $list.="<div class='container-fluid' style='text-align:center;'>";
            $list.="<span class='span8'><button class='btn btn-primary' id='next_register_payment'>Next</button></span>";
            $list.="</div>";

            $list.="<div class='container-fluid' style='text-align:center;'>";
            $list.="<span class='span8'><hr></span>";
            $list.="</div>";

            $list.="<div class='container-fluid' style='text-align:center;'>";
            $list.="<span class='span8'><img
                        src='https://www.merchantequip.com/image/?logos=v|m|a|d|p&height=64' alt='Merchant Equipment Store Credit Card Logos'/></span>";
            $list.="</div>";

            $list.="</div></div></div>";
        } // end if $courseid==null
        else {
            $selected_program = $this->get_selected_program($courseid);
            $program_schedule = $this->get_program_schedule($slotid);
            $p = new Payment();
            $courseObj = json_decode($p->get_course_data($courseid, $slotid));
            $list.="<br/><div  class='form_div'>";
            $list.="<div class='panel panel-default' id='program_section' style='margin-bottom:0px;'>";
            $list.="<div class='panel-heading' style='text-align:left;'><h5 class='panel-title'>Program information</h5></div>";
            $list.="<div class='panel-body'>";

            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span2'>Selected program:</span>";
            $list.="<span class='span4'>$selected_program</span>";
            $list.="<span class='span1'><input type='hidden' value='$slotid' id='selected_slot'></span>";
            $list.="<span class='span1'><input type='hidden' value='$courseid' id='selected_course'></span>";
            $list.="<span class='span1'><input type='hidden' value='$courseObj->raw_cost' id='payment_sum'></span>";
            $list.="<span class='span3'>$program_schedule</span>";
            $list.="</div>"; // end of container-fluid

            $list.="</div>"; // end of panel-body
            $list.="</div>"; // end of panel panel-default
            // ********************  Individual registration form **************************        
            $list.="<div class='panel panel-default' id='personal_section'>";
            $list.="<div class='panel-heading'style='text-align:left;'><h5 class='panel-title'>User details </h5></div>";
            $list.="<div class='panel-body'>";

            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span2' >First name<span style='color:red;'>*</span></span>";
            $list.="<span class='span2' ><input type='text' id='first_name' name='first_name' ></span>";
            $list.="<span class='span2' >Last name<span style='color:red;'>*</span></span>";
            $list.="<span class='span2' ><input type='text' id='last_name' name='last_name'  ></span>";
            $list.="</div>";

            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span2'>Mailing Address<span style='color:red;'>*</span></span>";
            $list.="<span class='span2'><input type='text' id='addr' name='addr' ></span>";
            $list.="<span class='span2'>City<span style='color:red;'>*</span></span>";
            $list.="<span class='span2'><input type='text' id='city' name='city' ></span>";
            $list.="</div>";

            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span2'>State<span style='color:red;'>*</span></span>";
            $list.="<span class='span2'>$states</span>";
            $list.="<span class='span2'>Country<span style='color:red;'>*</span></span>";
            $list.="<span class='span2' id='register_cities_container'>$countries</span>";
            $list.="</div>";

            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span2'>ZIP Code<span style='color:red;'>*</span></span>";
            $list.="<span class='span2'><input type='text' id='zip' name='zip' ></span>";
            $list.="<span class='span2'>Email<span style='color:red;'>*</span></span>";
            $list.="<span class='span2'><input type='text' id='email' name='email' ></span>";
            $list.="</div>";

            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span2'>Phone<span style='color:red;'>*</span></span>";
            $list.="<span class='span2'><input type='text' id='phone' name='phone'  ></span>";
            $list.="<span class='span2'>How did you hear about us?</span>";
            $list.="<span class='span2'>$come_from</span>";
            $list.="</div>";

            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span2'><img
                        src='https://www.merchantequip.com/image/?logos=v|m|a|d&height=16' alt='Merchant Equipment Store Credit Card Logos'/></span><span class='span2'><input type='radio' name='ptype' id='ptype' value='card' checked><span style='font-size:14px;font-weight:bold;color:blue;'>Pay with Credit or debit card</span></span>";
            $list.="<span class='span2'>&nbsp;</span><span class='span2'><input type='radio' name='ptype' id='ptype' value='paypal'><img
                        src='https://www.merchantequip.com/image/?logos=p&height=16' alt='Merchant Equipment Store Credit Card Logos'/><span style='font-size:14px;font-weight:bold;color:blue;'> Pay with Paypal</span></span>";
            $list.="</div>";

            $list.="<div class='container-fluid' style='text-align:center;'>";
            $list.="<span class='span8' id='personal_err' style='color:red;'></span>";
            $list.="</div>";

            $list.="<br><div class='container-fluid' style='text-align:center;'>";
            $list.="<span class='span8'><button class='btn btn-primary' id='next_register_payment'>Next</button></span>";
            $list.="</div>";

            $list.="<div class='container-fluid' style='text-align:center;'>";
            $list.="<span class='span8'><hr></span>";
            $list.="</div>";

            $list.="<div class='container-fluid' style='text-align:center;'>";
            $list.="<span class='span8'><img
                        src='https://www.merchantequip.com/image/?logos=v|m|a|d|p&height=64' alt='Merchant Equipment Store Credit Card Logos'/></span>";
            $list.="</div>";

            $list.="</div>";
            $list.="</div></div>";

            return $list;
        }
        return $list;
    }

    function get_brain_card_form($user) {
        $list = "";
        $userObj = json_decode(base64_decode($user));
        $program = $this->get_coure_name_by_id($userObj->courseid);
        $cost = $userObj->amount;
        $userObj->program = $program;
        $userdata = json_encode($userObj);

        $list.="<br/><div  class='form_div'>";
        $list.="<div class='panel panel-default' id='program_section' style='margin-bottom:0px;'>";
        $list.="<div class='panel-heading' style='text-align:left;'><h5 class='panel-title'>Program checkout</h5></div>";
        $list.="<div class='panel-body'>";

        $list.="<div class='container-fluid' style='text-align:center;'>";
        $list.="<span class='span2'>Selected program:</span>";
        $list.="<span class='span2'>$program</span>";
        $list.="<span class='span2'>Program fee</span>";
        $list.="<span class='span2'>$$cost</span>";
        $list.="</div>";

        $list.="<input type='hidden' id='user' value='$userdata'>";
        $list.="<input type='hidden' id='amount' value='$cost'>";
        $list.="<input type='hidden' id='email' value='$userObj->email'>";
        $list.="<div class='container-fluid' style='text-align:center;'>";
        $list.="<span class='span8'><hr/></span>";
        $list.="</div>";

        $list.="<form id='checkout-form' action='/' method='post'>";
        $list.="<div class='container-fluid' style='text-align:center;'>";
        $list.="<span class='span8' id='error-message'></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:center;'>";
        $list.="<span class='span2'><label for='card-number'>Card Number<span style='color:red;'>*</span></label></span>";
        $list.="<span class='span2'><div class='hosted-field' id='card-number'></div></span>";
        $list.="<span class='span2'><label for='cvv'>CVV<span style='color:red;'>*</span></label></span>";
        $list.="<span class='span2'><div class='hosted-field' id='cvv'></div></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:center;'>";
        $list.="<span class='span2'><label for='cardholder'>Cardholder Name<span style='color:red;'>*</span></label></span>";
        $list.="<span class='span2'><input class='hosted-field' id='cardholder' placeholder=''></span>";
        $list.="<span class='span2'><label for='expiration-date'>Expiration Date<span style='color:red;'>*</span></label></span>";
        $list.="<span class='span2'><div class='hosted-field' id='expiration-date'></div></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:center;'>";
        $list.="<span class='span8' id='err' style='color:red;'></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span8' style='text-align:center;display:none;' id='ajax_loading_payment'><img src='https://$this->host/assets/img/ajax.gif' /></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:center;'>";
        $list.="<span class='span8'>By clicking the 'I Agree, Submit' button, you confirm you have reviewed and agree to the Pay by Computer Terms & Conditions (<a href='#' onClick='return false;' id='policy'>click to view).</a></span>";
        $list.="</div>";

        $list.="<input type='hidden' name='payment_method_nonce'>";

        $list.="<div class='container-fluid' style='text-align:center;'>";
        $list.="<span class='span8'><input type='submit' id='make_register_braintree_pyament' style='background-color: #e2a500;' value='I Agree, Submit' disabled></span>";
        $list.="</div>";

        $list.="</div></div></div>";

        return $list;
    }

    function get_user_detailes($userid) {
        $query = "select * from mdl_user where id=$userid";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $data = $row;
        }
        return $data;
    }

    function get_any_pay_payment_form($user) {
        $list = "";
        if ($user->period == 0) {
            $program = $this->get_coure_name_by_id($user->courseid);
        } // end if $user->period==0
        else {
            $program = 'Certificate renewal';
        } // end else
        $cost = $user->amount;
        $user->program = $program;
        $userdetails = $this->get_user_detailes($user->userid);
        $userdata = json_encode($userdetails);

        $list.="<br/><div  class='form_div'>";
        $list.="<div class='panel panel-default' id='program_section' style='margin-bottom:0px;'>";
        $list.="<div class='panel-heading' style='text-align:left;'><h5 class='panel-title'>Program checkout</h5></div>";
        $list.="<div class='panel-body'>";

        $list.="<div class='container-fluid' style='text-align:center;'>";
        $list.="<span class='span2'>Selected program:</span>";
        $list.="<span class='span2'>$program</span>";
        $list.="<span class='span2'>Program fee</span>";
        $list.="<span class='span2'>$$cost</span>";
        $list.="</div>";

        $list.="<input type='hidden' id='courseid' value='$user->courseid'>";
        $list.="<input type='hidden' id='slotid' value='$user->slotid'>";
        $list.="<input type='hidden' id='period' value='$user->period'>";
        $list.="<input type='hidden' id='amount' value='$cost'>";
        $list.="<input type='hidden' id='user' value='$userdata'>";
        $list.="<input type='hidden' id='email' value='$userdetails->email'>";
        $list.="<div class='container-fluid' style='text-align:center;'>";
        $list.="<span class='span8'><hr/></span>";
        $list.="</div>";

        $list.="<form id='checkout-form' action='/' method='post'>";
        $list.="<div class='container-fluid' style='text-align:center;'>";
        $list.="<span class='span8' id='error-message'></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:center;'>";
        $list.="<span class='span2'><label for='card-number'>Card Number<span style='color:red;'>*</span></label></span>";
        $list.="<span class='span2'><div class='hosted-field' id='card-number'></div></span>";
        $list.="<span class='span2'><label for='cvv'>CVV<span style='color:red;'>*</span></label></span>";
        $list.="<span class='span2'><div class='hosted-field' id='cvv'></div></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:center;'>";
        $list.="<span class='span2'><label for='cardholder'>Cardholder Name<span style='color:red;'>*</span></label></span>";
        $list.="<span class='span2'><input class='hosted-field' id='cardholder' placeholder=''></span>";
        $list.="<span class='span2'><label for='expiration-date'>Expiration Date<span style='color:red;'>*</span></label></span>";
        $list.="<span class='span2'><div class='hosted-field' id='expiration-date'></div></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:center;'>";
        $list.="<span class='span8' id='err' style='color:red;'></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span8' style='text-align:center;display:none;' id='ajax_loading_payment'><img src='https://$this->host/assets/img/ajax.gif' /></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:center;'>";
        $list.="<span class='span8'>By clicking the 'I Agree, Submit' button, you confirm you have reviewed and agree to the Pay by Computer Terms & Conditions (<a href='#' onClick='return false;' id='policy'>click to view).</a></span>";
        $list.="</div>";

        $list.="<input type='hidden' name='payment_method_nonce'>";

        $list.="<div class='container-fluid' style='text-align:center;'>";
        $list.="<span class='span8'><input type='submit' id='make_any_pay_payment' style='background-color: #e2a500;' value='I Agree, Submit' disabled></span>";
        $list.="</div>";

        $list.="</div></div></div>";

        return $list;
    }

    function get_brain_paypal_form($user) {
        $userObj = json_decode(base64_decode($user));
        $cost = $userObj->amount;
        $email = $userObj->email;
        $_SESSION[$email] = $user;

        $paypal_btn = $this->get_paypal_register_button($userObj);
        $program = $this->get_coure_name_by_id($userObj->courseid);

        $list = "";

        $list.="<br/><div  class='form_div'>";
        $list.="<div class='panel panel-default' id='program_section' style='margin-bottom:0px;'>";
        $list.="<div class='panel-heading' style='text-align:left;'><h5 class='panel-title'>Program checkout</h5></div>";
        $list.="<div class='panel-body'>";

        $list.="<div class='container-fluid' style='text-align:center;'>";
        $list.="<span class='span2'>Selected program:</span>";
        $list.="<span class='span2'>$program</span>";
        $list.="<span class='span2'>Program fee</span>";
        $list.="<span class='span2'>$$cost</span>";
        $list.="</div>";

        $list.="<br><div class='container-fluid' style='text-align:center;'>";
        $list.="<span class='span8'>$paypal_btn</span>";
        $list.="</div>";

        $list.="</div></div></div>";

        return $list;
    }

    function get_paypal_register_button($userObj) {
        $list = "";

        $cost = $userObj->amount;
        $email = $userObj->email;
        $program = $this->get_coure_name_by_id($userObj->courseid);

        $list.="<form action='https://www.paypal.com/cgi-bin/webscr' method='post'>"; // production
        //$list.="<form action='https://www.sandbox.paypal.com/cgi-bin/webscr' method='post'>"; // sandbox
        $list.="<input type='hidden' name='cmd' value='_xclick'>";
        $list.="<INPUT TYPE='hidden' name='charset' value='utf-8'>";
        $list.="<input type='hidden' name='business' value='info@medical2.com'>"; // production
        //$list.="<input type='hidden' name='business' value='sirromas-facilitator@outlook.com'>"; // sandbox
        $list.="<input type='hidden' name='item_name' value='$program'>
        <input type='hidden' name='amount' value='$cost'>
        <input type='hidden' name='custom' value='$email'>    
        <INPUT TYPE='hidden' NAME='currency_code' value='USD'>    
        <INPUT TYPE='hidden' NAME='return' value='https://medical2.com/register2/receive_paypal_register_payment/'>
        <input type='image' id='paypal_btn' src='https://www.sandbox.paypal.com/en_US/i/btn/btn_buynow_LG.gif' border='0' name='submit' alt='PayPal - The safer, easier way to pay online!'>
        <img alt='' border='0' src='https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif' width='1' height='1'>
        </form>";

        return $list;
    }

    function process_paypal_payment($payment) {
        $list = "";

        $status = $payment['st'];
        if ($status == 'Completed') {
            $email = $payment['cm'];
            $transactionid = $payment['tx'];
            $user = $_SESSION[$email];
            $userObj = json_decode(base64_decode($user));
            $userObj->transactionid = $transactionid;
            $p = new Payment();
            $signup_status = $p->enroll->single_signup($userObj);
            if ($signup_status === true) {
                $p->confirm_user($userObj->email);
                $userid = $p->get_user_id_by_email($userObj->email);
                $p->enroll->add_user_to_course_schedule($userid, $userObj);
                $userObj->renew = 0;
                $this->add_paypal_payment($userObj);
                $list.="<br/><div  class='form_div'>";
                $list.="<div class='panel panel-default' id='program_section' style='margin-bottom:0px;'>";
                $list.="<div class='panel-heading' style='text-align:left;'><h5 class='panel-title'>Payment status</h5></div>";
                $list.="<div class='panel-body'>";

                $list.="<div class='container-fluid' style='text-align:center;'>";
                $list.="<span class='span8'>Payment is successful, thank you! Confirmation email is sent to $email</span>";
                $list.="</div>";

                $list.="</div></div></div>";
            } // end if $signup_status === true
            else {
                $list.="<br/><div  class='form_div'>";
                $list.="<div class='panel panel-default' id='program_section' style='margin-bottom:0px;'>";
                $list.="<div class='panel-heading' style='text-align:left;'><h5 class='panel-title'>Signup status</h5></div>";
                $list.="<div class='panel-body'>";

                $list.="<div class='container-fluid' style='text-align:center;'>";
                $list.="<span class='span8'>Payment is successful, thank you, but signup was not ok, please cotact us and provide your email address</span>";
                $list.="</div>";

                $list.="</div></div></div>";
            } // end else when signup was not successfull ....
        } // end if $status=='Completed'
        else {
            $list.="<br/><div  class='form_div'>";
            $list.="<div class='panel panel-default' id='program_section' style='margin-bottom:0px;'>";
            $list.="<div class='panel-heading' style='text-align:left;'><h5 class='panel-title'>Payment status</h5></div>";
            $list.="<div class='panel-body'>";

            $list.="<div class='container-fluid' style='text-align:center;'>";
            $list.="<span class='span8'>Payment is failed</span>";
            $list.="</div>";

            $list.="</div></div></div>";
        } // end else

        return $list;
    }

    function add_paypal_payment($user) {
        $date = time();
        $p = new Payment();
        $m = new Mailer();
        $query = "insert into mdl_paypal_payments "
                . "(courseid,"
                . "userid,"
                . "psum,"
                . "trans_id,"
                . "renew,"
                . "pdate) "
                . "values ($user->courseid,"
                . "$user->userid,"
                . "'$user->amount', "
                . "'$user->transactionid',"
                . "'$user->renew', '$date')";
        $this->db->query($query);
        $p->confirm_user($user->email);
        // Make user object compatible with email receipt
        $user_detailes = $p->get_user_detailes($user->userid);
        $user->sum = $user->amount;
        $user->pwd = $user_detailes->purepwd;
        $user->payment_amount = $user->amount;
        $user->card_holder = $user->first_time . " " . $user->last_name;
        $user->signup_first = $user->first_name;
        $user->signup_last = $user->last_name;
        $m->send_payment_confirmation_message($user, null, null, true);
    }

    function get_policy_dialog() {
        $list = "";
        $list.="<div id='myModal' class='modal fade'>
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
                <h4 class='modal-title'>Terms and Conditions</h4>
            </div>
            <div class='modal-body'>
                <p style='font-weight:bold;'>Medical2 Inc.  ADVANCE REGISTRATION / REFUND / PREREQUISITE POLICY:</p>
                
                <p align='justify'>Participants who wish to cancel/transfer their registration must provide official written notification. Medical2 does not accept cancellations/transfers by phone. Cancellations must be received by Medical2 no later than 2 weeks (14Days) prior to your original workshop date. Cancellations/transfers must include the participant's name, and workshop name and date. A 100% refund can be issued if the deadline has been met. Failure to notify will result in the forfeit of any obligation by Medical2. Failure to attend your Workshop results in the forfeit of the entire Workshop Fee. Late Registrations are not entitled to any refund. Transfers forfeit the right to a refund if they voluntarily cancel their enrollment after transferring. Transfers must attend a Workshop within 6 months of their original Workshop date. After 6 months, your transfer is null and void, and you will be required to re-register and pay in full again.
                All books are non-refundable and non-returnable. Medical2 Medical Certification Agency reserves the right to cancel or limit the time of the workshops without any prior notice, if minimum registration requirements are not met. Medical2 Medical Certification Agency is not responsible for any travel related expenses incurred, including but not limited to: Airfare, Hotel, Taxi, Parking, Rental Car, etc…by registering for and/or attending our workshops. The OB Tech Program is entirely non-refundable. All Online Exam fees are non-refundable. Your certification is non-refundable, non-transferable. Medical2 Medical Certification Agency is not a Job Placement Agency. We neither provide jobs, nor guarantee jobs. Medical2 simply provides a training, certification and continuing education service. It is your sole responsibility to check with your state and/or employer for acceptance of our certification.
                Should the workshop for which you are registered be cancelled or rescheduled by Medical2 Medical Certification Agency, you are subject to a full refund or transfer. All refund requests and transfers must be made in writing. If you transfer, you forfeit your right to any refund, and any future cancellation refunds, no exceptions.<br> 
                <span style='color:red;'>
                Notice:There will be $25 late registration fee within 7 days prior to the workshop.
                Medical2 Medical Certification Agencys Phlebotomy Workshop & Online Exam shall not be used to obtain a California Phlebotomy State License.</span><br>
                
                By submitting this form I confirm that I have read and meet all of the medical background pre-requisites, am at least 18 years of age, and completely understand and agree with the Medical2 Medical Certification Agency policies stated on this form. Noncompliance of any these polices will forfeit any obligation by Medical2 Medical Certification Agency.<br>
                <span style='color:red;'>Refund / Pre-Requisite Policy for On-line Exams</span> (online phlebotomy certification exams are not part of phlebotomy workshop)
                In order to be eligible for a Madical2 On-line Exam, you are required to send Medical2 proof of eligibility as outlined in the eligibility requirements for the exam for which you are registering.
                The exam fee is non-refundable, non-transferable. Medical2,Inc will not be held responsible for any registrant who fails to read and/or comply with these policies. It is your sole responsibility to check with your state and/or employer for acceptance of our certification.
                Your test will be activated only after we receive proof of eligibility from you.
                You have 90 days from the activation date to finish the exam. You acknowledge that in the event that you fail the exam on your first attempt you are only eligible to attempt this exam 4 times in a 12 month period. You must register and pay the fee each time you attempt the exam.You must use a computer with a stable, high-speed Internet connection. Medical2 is not responsible for any technical difficulties you may have due to your computer and/or poor Internet connection. Once you have started your exam, you cannot log out, as it is timed. Medical2 is not responsible if you log out of your timed exam, and lose time. In the event of a technical problem you can retry 2 times in a 1 hour period from the same computer at the discretion of Medical2 technical support staff. It is your responsibility to disable call waiting, answering machines, and any other devise that may cause interference of your on-line exam. This exam must be passed with an 75% or better in order to become certified. You may request a hand score of the exam for an additional fee of $25.
                Medical2,Inc reserves the right to update our Terms and Conditions at any time, without prior notice. Updates are effective immediately.
                By submitting this form: I confirm that I meet all of the medical background pre-requisites for the program for which I am registering, I am at least 18 years of age, and I fully understand and agree with the Medical2,Inc policies stated on this form. I understand noncompliance of any of these policies will forfeit any obligation by Medical2,Inc.
                Refund / Pre-Requisite Policy for: Online Exams.
                In order to be eligible for the 'Medical2 Online Exams', you will need to send us your proof of eligibility as outlined in the eligibility requirements for this exam (Please see FAQs).
                The exam fee is non-refundable, non-transferable. Medical2 will not be held responsible for any registrant who fails to read and/or comply with these policies. It is your sole responsibility to check with your state and/or employer for acceptance our certification. Your test will be activated only after we receive proof of eligibility from you.
                You have 90 days from the registration date to finish the exam. You acknowledge that in the event that you fail the exam on your first attempt you are only eligible to attempt this exam 4 times in a 12 month period. The fee is the same each time. In the event of a technical problem you can retry 2 times in a 1 hour period from the same computer at the discretion of Medical2 Medical Certification Agency technical support staff. It is your responsibility to disable call waiting, answering machines, and any other devise that may cause interference of your online exam. This exam must be passed with an 75% or better in order to become certified. You may request a hand score of the exam for an additional fee of $25.'
                </p>
            </div>
            <div class='modal-footer'>
                <span align='center'><button type='button' class='btn btn-primary' data-dismiss='modal'>I Agree with Terms and Conditions</button></span>
            </div>
        </div>
    </div>
</div>";
        return $list;
    }

    // ******************* School Application Form ************************//

    function get_college_programs() {
        $list = "";
        $list.="<select id='programs' style='width:155px;' required>";
        $list.="<option value='0' selected>Program</option>";
        $query = "select * from mdl_course where category=5 and cost>0 and visible=1";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $list.="<option value='$row->id'>$row->fullname</option>";
        }
        $list.="</select>";

        return $list;
    }

    function get_gender_box() {
        $list = "";
        $list.="<select id='gender' style='width:155px;' required>";
        $list.="<option value='0' selected>Gender</option>";
        $list.="<option value='m'>Male</option>";
        $list.="<option value='f'>Female</option>";
        $list.="</select>";
        return $list;
    }

    function get_education_box() {
        $list = "";
        $list.="<select id='education' style='width:155px;' required>";
        $list.="<option value='0' selected>Education</option>";
        $list.="<option value='1'>High School</option>";
        $list.="<option value='2'>GED</option>";
        $list.="<option value='3'>College</option>";
        $list.="<option value='4'>Study</option>";
        $list.="</select>";
        return $list;
    }

    function get_state_list2() {
        $list = "";
        $list.="<select id='state' style='width:155px;' required>";
        $list.="<option value='0' selected>State</option>";
        $query = "select * from mdl_states ";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $list.="<option value='$row->id'>$row->state</option>";
        }
        $list.="</select>";

        return $list;
    }

    function get_pc_box() {
        $list = "";
        $list.="<select id='pc_knoweldge' style='width:155px;' required>";
        $list.="<option value='0' selected>Please select</option>";
        $list.="<option value='1'>Yes</option>";
        $list.="<option value='2'>No</option>";
        $list.="</select>";
        return $list;
    }

    function get_certified_box() {
        $list = "";
        $list.="<select id='cert_status' style='width:155px;' required>";
        $list.="<option value='0' selected>Please select</option>";
        $list.="<option value='1'>Yes</option>";
        $list.="<option value='2'>No</option>";
        $list.="</select>";
        return $list;
    }

    function get_program_classes($courseid = NULL) {
        $list = "";
        $list.="<select id='slotid' style='width:355px;' required>";

        if ($courseid != NULL) {
            $now = time();
            $query = "select * from mdl_scheduler where course=$courseid";
            $result = $this->db->query($query);
            foreach ($result->result() as $row) {
                $schedulerid = $row->id;
            }

            $query = "select * from mdl_scheduler_slots "
                    . "where schedulerid=$schedulerid "
                    . "and starttime>=$now";
            $result = $this->db->query($query);
            if ($result->num_rows() > 0) {
                foreach ($query->result() as $row) {
                    $slot_item = date('m-d-Y', $row->starttime) . "-" . $row->appointmentlocation;
                    $list.="<option value='$row->id'>$slot_item</option>";
                }
            } // end if
            else {
                $list.="<option value='0'>N/A</option>";
            } // end else
        } // end if $courseid != NULL
        else {
            $list.="<option value='0' selected>Please select</option>";
        }

        $list.="</select>";

        return $list;
    }

    function get_school_app_policy() {
        $list = "";
        $query = "select * from mdl_terms_school where id=1";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $list.=$row->content;
        }
        return $list;
    }

    function get_scholl_app_form() {
        $list = "";
        $policy = $this->get_school_app_policy();
        $programs = $this->get_college_programs();
        $states = $this->get_state_list2();
        $pc = $this->get_pc_box();
        $cert = $this->get_certified_box();
        $slots = $this->get_program_classes();

        $list.="<br/><div  class='form_div2' >";
        $list.="<div class='panel panel-default' id='program_section' style='margin-bottom:0px;'>";
        $list.="<div class='panel-heading' style='text-align:left;'><h5 class='panel-title'>School application</h5></div>";
        $list.="<div class='panel-body' style='text-align:center;'>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>I am registering for*:</span>";
        $list.="<span class='span2'>$programs</span>";
        $list.="<span class='span2'>Choose a Class</span>";
        $list.="<span class='span4' id='program_schedule'>$slots</span>";
        $list.="</div>"; // end of container-fluid

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>Name*</span>";
        $list.="<span class='span2'><input type='text' id='last' placeholder='(Last)'     required ></span>";
        $list.="<span class='span2'><input type='text' id='first' placeholder='(First)'   required ></span>";
        $list.="<span class='span2'><input type='text' id='middle' placeholder='(Middle)' required ></span>";
        $list.="<span class='span2'><input type='text' id='maiden' placeholder='(Maiden)' required ></span>";
        $list.="</div>"; // end of container-fluid

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>Address*</span>";
        $list.="<span class='span2'><input type='text' id='street' placeholder='(Street)' required  ></span>";
        $list.="<span class='span2'><input type='text' id='city' placeholder='(City)'     required  ></span>";
        $list.="<span class='span2'>$states</span>";
        $list.="<span class='span2'><input type='text' id='zip' placeholder='(Zip)'  required></span>";
        $list.="</div>"; // end of container-fluid

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>Provide 2 contact phones numbers, email and birth of date *:</span>";
        $list.="<span class='span2'><input type='text' id='phone1' placeholder='(___) ___-____'   required></span>";
        $list.="<span class='span2'><input type='text' id='phone2' placeholder='(___) ___-____'   required></span>";
        $list.="<span class='span2'><input type='email' id='email' placeholder='Email' required  style='width:140px;'></span>";
        $list.="<span class='span2'><input type='text' id='birth'  placeholder='yyyy/mm/dd' required></span>";
        $list.="</div>";

        $list.="<br><div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>Level of Education:</span>";
        $list.="<span class='span7'>(give name of school, year graduated) Understand you must supply Medical2 with a copy of HS diploma, GED certificate, or HS transcript upon beginning program of study.</span>";
        $list.="</div>"; // end of container-fluid

        $edu = $this->get_education_box();
        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>Education*:</span>";
        $list.="<span class='span2'>$edu</span>";
        $list.="<span class='span4'><input type='text' id='edu_name' style='width:340px;'></span>";
        $list.="<span class='span2'><input type='text' id='graduate_date' placeholder='YYYY' required></span>";
        $list.="</div>"; // end of container-fluid

        $list.="<br><div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>Work experience*:</span>";
        $list.="<span class='span7'>Beginning with present or last employer (name, address, dates employed, type of work)*</span>";
        $list.="</div>"; // end of container-fluid

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>&nbsp;</span>";
        $list.="<span class='span8'><textarea id='work' style='width:740px;' rows='5' required></textarea></span>";
        $list.="</div>"; // end of container-fluid

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>Do you have a working knowledge of computers?*:</span>";
        $list.="<span class='span2'>$pc</span>";
        $list.="<span class='span2'>Have you ever been certified or licensed in a medical field before and what field?</span>";
        $list.="<span class='span2'>$cert</span>";
        $list.="<span class='span2'><input type='text' id='cert_area'></span>";
        $list.="</div>"; // end of container-fluid

        $list.="<br><div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>Why do you want to take this program?*</span>";
        $list.="<span class='span8'><textarea id='reason' style='width:740px;' rows='5' required></textarea></span>";
        $list.="</div>"; // end of container-fluid

        /*
          $list.="<div class='container-fluid' style='text-align:left;'>";
          $list.="<span class='span2'>&nbsp;</span>";
          $list.="<span class='span8'>Medical 2 Career College and the State of Mississippi requires that all students validate no history of the following according to Mississippi Code of 1972, Section 43-11-13.</span>";
          $list.="</div>"; // end of container-fluid

          $list.="<br><div class='container-fluid' style='text-align:left;'>";
          $list.="<span class='span2'>&nbsp;</span>";
          $list.="<span class='span8'>By signing below, I attest I have not been convicted of or pleaded guilty or nolo contendere to a felony of possession or sale of drugs, murder, manslaughter, armed robbery, rape, sexual battery, any gratification of lust, aggravated assault, or felonious abuse and/or battery of a vulnerable adult. I have not been convicted of or pleaded guilty or nolo contendere to other crimes which his/her employer has and/or would determine to be disqualifying for employment. By signing below, I give Medical 2 Career College permission to conduct a background check in accordance with the Mississippi State Law with the Department of Health Nurse Registry to provide a clean medical abuse record with the State of Mississippi and permission to conduct a background with the Mississippi Department of Public Safety. </span>";
          $list.="</div>"; // end of container-fluid

          $list.="<br><div class='container-fluid' style='text-align:left;'>";
          $list.="<span class='span2'>&nbsp;</span>";
          $list.="<span class='span8'>I am applying for admittance as a student at Medical 2 Career College in a healthcare program. Falsification of information on any application is reason for dismissal and loss of all payments made.  </span>";
          $list.="</div>"; // end of container-fluid
         * 
         */
        $list.=$policy;

        $list.="<div class='container-fluid' style='text-align:center;'>";
        $list.="<span class='span10' style='text-align:center;display:none;' id='ajax_loading'><img src='https://$this->host/assets/img/ajax.gif' /></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>&nbsp;</span>";
        $list.="<span class='span8' id='app_err' style='color:red;'></span>";
        $list.="</div>"; // end of container-fluid

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>&nbsp;</span>";
        $list.="<span class='span8' style='text-align:center;'><button class='btn btn-primary' id='shcool_apply'>Submit</button></span>";
        $list.="</div>"; // end of container-fluid

        $list.="</div>"; // end of panel-body
        $list.="</div>"; // end of panel panel-default

        $list.="</div><br/>";

        return $list;
    }

    function get_campus_locations() {
        $items = array();
        $query = "select * from mdl_campus";
        $result = $this->db->query($query);
        $num = $result->num_rows();
        if ($num > 0) {
            $result = $this->db->query($query);
            foreach ($result->result() as $row) {
                $items[] = $row;
            }
        } // end if
        return json_encode($items);
    }

    function get_location_dropdown() {
        $list = "";

        $list.="<select id='end' style='width:300px;'>";
        $list.="<option value='0' selected>Please select</option>";
        $query = "select * from mdl_campus";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $list.="<option value='$row->campus_desc'>$row->campus_desc</option>";
        }

        $list.="</select>";

        return $list;
    }

    function get_map_data() {
        $list = "";
        $locations = $this->get_location_dropdown();
        $list.="<div class='container-fluid' style='text-align:center;'>";
        $list.="<span class='span4'><img src='https://medical2.com/assets/img/m2.jpg' class='img-rounded' width='100%' height='100%'></span>";
        $list.="</div><br>";


        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span4'>Phone: 877-741-1996</span>";
        $list.="</div><br>";

        $query = "select * from mdl_campus";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span4'>$row->campus_desc</span>";
            $list.="</div><br>";
        }

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span4' style='padding-left:6px;'><input type='text'id='start'  placeholder='Your Location ...' style='width:282px;'></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span4' style='padding-left:6px;'>$locations</span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span4' style='padding-left:4px;color:red;' id='map_err'></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span4'><button class='btn btn-primary' id='get_driver_directions' style='width:300px;'>Get Driving Directions</button></span>";
        $list.="</div>";

        return $list;
    }

    function get_campus_page() {
        $list = "";
        $map = $this->get_map_data();
        $list.="<br/><div  class='form_div2' >";
        $list.="<div class='panel panel-default' id='program_section' style='margin-bottom:0px;'>";
        $list.="<div class='panel-heading' style='text-align:left;'><h5 class='panel-title'>Campus Locations</h5></div>";
        $list.="<div class='panel-body' style='text-align:center;'>";

        $list.="<div class='container-fluid' style='1px solid #ccc;'>";
        $list.="<div class='span4' style=''>$map</div>";
        $list.="<div class='span6' id='map' style='border: 1px solid #ccc;height:475px'></div>";
        $list.="</div>";

        $list.="</div>"; // end of panel-body
        $list.="</div>"; // end of panel panel-default

        $list.="</div><br/>";


        return $list;
    }

}
