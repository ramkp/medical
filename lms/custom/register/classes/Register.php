<?php

require_once ('/home/cnausa/public_html/lms/custom/utils/classes/Util.php');
require_once ('/home/cnausa/public_html/lms/custom/partial/classes/Partial.php');
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/Enroll.php';

class Register extends Util {

    function __construct() {
        parent::__construct();
    }

    public function get_course_categories($send = null) {
        $drop_down = "";
        $drop_down.="<select id='categories' style='width:120px;'>";
        $drop_down.="<option value='0' selected='selected'>Program type</option>";
        $query = "select id,name from mdl_course_categories";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $drop_down.="<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
        }
        $drop_down.="</select>";
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
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $drop_down.="<li><a href='#' id='course_" . $row['id'] . "' onClick='return false;'>" . $row['fullname'] . "</a></li>";
                } // end while
            } // end if $num > 0
            $drop_down.="</ul></div>";
        } // end if $cat_id != null
        else {
            $drop_down.="<select id='register_courses' style='width:120px;'><option value='0'>Program</option></select>";
        }
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
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                //$drop_down.="<option value='$row->id'>$row->state</option>";
            } // end while
        } // end if $courseid != null        

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
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                //$drop_down.="<option value='$row->id'>$row->state</option>";
            } // end while
        } // end if $courseid != null        

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
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $drop_down.="<option value='" . $row['id'] . "'>" . $row['state'] . "</option>";
        } // end while
        $drop_down.="</select>";
        return $drop_down;
    }

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
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            if ($row['name'] == 'United States') {
                $drop_down.="<option value='" . $row['id'] . "' selected>" . $row['name'] . "</option>";
            } // end if $row->name=='United States'
            else {
                $drop_down.="<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
            }
        }
        $drop_down.="</select>";
        return $drop_down;
    }

    function get_program_register_form() {
        $list = "";

        $cats = $this->get_course_categories();
        $courses = $this->get_courses_by_category();
        $states = $this->get_states_list();
        $countries = $this->get_countries_list();
        $register_state = $this->get_register_course_states_list();
        $cities = $this->get_register_course_cities_list();

        $list.="<div class='container-fluid' style='text-align:center;'>";
        $list.="<span class='span2'>$cats</span>";
        $list.="<span class='span2' id='cat_course'>$courses</span>";
        $list.="<span class='span2' id='register_states_container'>$register_state</span>";
        $list.="<span class='span2' id='register_cities_container'>$cities</span>";
        $list.="<span class='span2' id='program_err' style='color:red;'></span>";
        $list.="</div>"; // end of container-fluid

        return $list;
    }

    function get_card_month() {
        $list = "";
        $list.="<select id='card_m'>";
        $list.="<option value='0' selected>Month</option>";
        for ($i = 1; $i <= 12; $i++) {
            $name = ($i < 10) ? '0' . $i : $i;
            $list.="<option value='$name'>$name</option>";
        }
        $list.="</select>";
        return $list;
    }

    function get_card_year() {
        $list = "";
        $list.="<select id='card_y'>";
        $list.="<option value='0' selected>Year</option>";
        for ($i = 2017; $i <= 2024; $i++) {
            $list.="<option value='$i'>$i</option>";
        }
        $list.="</select>";
        return $list;
    }

    function get_register_form() {

        $list = "";
        $card_month2 = $this->get_card_month();
        $card_year2 = $this->get_card_year();
        $states2 = $this->get_states_list(true);
        $countries2 = $this->get_countries_list(true);

        $cats = $this->get_course_categories();
        $courses = $this->get_courses_by_category();
        $states = $this->get_states_list();
        $countries = $this->get_countries_list();
        $register_state = $this->get_register_course_states_list();
        $cities = $this->get_register_course_cities_list();

        $list.="<div class='panel panel-default' id='program_section' style='margin-bottom:0px;'>";
        $list.="<div class='panel-heading' style='text-align:left;'><h5 class='panel-title'>User registration</h5></div>";
        $list.="<div class='panel-body'>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>$cats</span>";
        $list.="<span class='span2' id='cat_course'>$courses</span>";
        $list.="<span class='span2' id='register_states_container'>$register_state</span>";
        $list.="<span class='span2' id='register_cities_container'>$cities</span>";
        $list.="<span class='span2' id='program_err' style='color:red;'></span>";
        $list.="</div>"; // end of container-fluid

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2' >First name*</span>";
        $list.="<span class='span2' ><input type='text' id='first_name' name='first_name' ></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2' >Last name*</span>";
        $list.="<span class='span2' ><input type='text' id='last_name' name='last_name'  ></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>Mailing Address*</span>";
        $list.="<span class='span2'><input type='text' id='r_addr' name='r_addr' ></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>City*</span>";
        $list.="<span class='span2'><input type='text' id='city' name='city' ></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>State*</span>";
        $list.="<span class='span2'>$states</span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>Country*</span>";
        $list.="<span class='span2'>$countries</span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>ZIP Code*</span>";
        $list.="<span class='span2'><input type='text' id='zip' name='zip' ></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>Phone*</span>";
        $list.="<span class='span2'><input type='text' id='phone' name='phone'  ></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>Email*</span>";
        $list.="<span class='span2'><input type='text' id='email' name='email' ></span>";
        $list.="</div>";
        
        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>Amount*</span>";
        $list.="<span class='span2'><input type='text' id='amount' name='amount' ></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span12' style='color:red;' id='register_cash_error'></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span3'><input type='radio' name='r_payments' id='r_card' value='r_card'  checked>Pay by card</span>";
        $list.="<span class='span3'><input type='radio' name='r_payments' id='r_cash' value='r_cash'>Pay by cash</span>";
        $list.="<span class='span3'><input type='radio' name='r_payments' id='r_cheque' value='r_cheque'>Pay by cheque</span>";
        $list.="<span class='span3'><button class='btn btn-primary' id='register_payment_proceed'>Proceed</button></span>";
        $list.="<span class='span3'></span>";
        $list.="</div>";

        // ********************  Cash payment section ********************
        $list.="<div id='register_cash_payments' style='display:none;'>";
        $list.="<div class='row-fluid'>";
        $list.="<span class='span1'>Amount*</span>";
        $list.="<span class='span3'><input type='text' id='register_cash_payments_amount'></span>";
        $list.="<span class='span1'><button class='btn btn-primary' id='add_register_cash_payment'>Submit</button></span>";
        $list.="</div>";
        $list.="</div>";

        // ********************  Card payment section ********************
        $list.="<div id='register_card_payments' style='display:none;'>";
        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span6'><hr/></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>Amount*</span>";
        $list.="<span class='span2'><input type='text' required id='sum' name='sum' required></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>Holder First name*</span>";
        $list.="<span class='span2'><input type='text' required id='b_fname' name='b_fname' placeholder='Firstname' required></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>Holder Last name*</span>";
        $list.="<span class='span2'><input type='text' required id='b_lname' name='b_lname' placeholder='Lastname' required></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>CVV*</span>";
        $list.="<span class='span2'><input type='text' id='cvv2' name='cvv2'  ></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>Card number*</span>";
        $list.="<span class='span2'><input type='text' id='card_no2' name='card_no2'  ></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>Expiration Date*</span>";
        $list.="<span class='span3'>" . $card_month2 . "&nbsp;&nbsp;&nbsp;" . $card_year2 . "</span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:center;'>";
        $list.="<span class='span8'><input type='checkbox' id='da'> &nbsp; If billing address is different</span>";
        $list.="</div>";

        $list.="<div id='diff_address' style='display:none;'>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>Billing Address*</span>";
        $list.="<span class='span2'><input type='text' id='addr2' name='addr2'  ></span>";
        $list.="</div>";


        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>City*</span>";
        $list.="<span class='span2'><input type='text' id='city2' name='city2'  ></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>State*</span>";
        $list.="<span class='span2'>$states2</span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>Country*</span>";
        $list.="<span class='span2' id='register_cities_container'>$countries2</span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>Zip code*</span>";
        $list.="<span class='span2'><input type='text' id='zip2' name='zip2'  ></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>Receipt email*</span>";
        $list.="<span class='span2'><input type='text' id='email2' name='email2'></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>Phone*</span>";
        $list.="<span class='span2'><input type='text' id='phone2' name='phone2'></span>";
        $list.="</div>";

        $list.="</div>";

        $list.="<div class='container-fluid' id='ajax_loading_payment' style='text-align:center;display:none;'>";
        $list.="<span class='span4'><img src='https://medical2.com/assets/img/ajax.gif'></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span9' id='personal_err' style='color:red;'></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>&nbsp;</span>";
        $list.="<span class='span2'><button class='btn btn-primary' id='internal_register_submit'>Submit</button></span>";
        $list.="</div>";

        $list.="</div>";

        $list.="</div>"; // end of panel-body
        $list.="</div>"; // end of panel panel-default


        return $list;
    }

    function get_user_id_by_email($email) {
        $query = "select id, username from mdl_user where username='$email'";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $id = $row['id'];
        }
        return $id;
    }

    function add_register_cash_payment($user) {
        $list = "";
        $en = new Enroll();
        $pa = new Partial();
        $user->inst = 'n/a';
        $response = $en->single_signup($user);
        if ($response !== false) {
            $userid = $this->get_user_id_by_email($user->email);
            $pa->add_partial_payment($user->courseid, $userid, $user->sum, $user->type, $user->slotid, 0);
            $list.="Registration is successfull";
        } // end if
        else {
            $list.="Signup error happened";
        } // end else
        return $list;
    }

}
