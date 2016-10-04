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
                <option value='03'>02</option>
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
        date_default_timezone_set('Pacific/Wallis');
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
            $list.="<span class='span2'>Card Holder Name*</span>";
            $list.="<span class='span2'><input type='text' required id='billing_name' name='billing_name' ></span>";
            $list.="<span class='span2'>CVV*</span>";
            $list.="<span class='span2'><input type='text' id='cvv' name='cvv'  ></span>";
            $list.="</div>";

            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span2'>Card number*</span>";
            $list.="<span class='span2'><input type='text' id='card_no' name='card_no'  ></span>";
            $list.="<span class='span2'>Expiration Date*</span>";
            $list.="<span class='span2'>" . $card_month . "&nbsp;&nbsp;&nbsp;" . $card_year . "</span>";
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

            /* ********************** Payment section ********************* */
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
            $list.="<span class='span2'><input type='text' id='cvv' name='cvv'  ></span>";
            $list.="</div>";

            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span2'>Card number*</span>";
            $list.="<span class='span2'><input type='text' id='card_no' name='card_no'  ></span>";
            $list.="<span class='span2'>Expiration Date*</span>";
            $list.="<span class='span2'>" . $card_month . "&nbsp;&nbsp;&nbsp;" . $card_year . "</span>";
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

}
