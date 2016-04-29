<?php

/**
 * Description of Dashboard
 *
 * @author sirromas
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/Payment.php';

class Dashboard extends Util {

    public $resolution_path;
    public $resolution_path2;

    function __construct() {
        parent::__construct();
        $this->resolution_path = 'http://' . $_SERVER['SERVER_NAME'] . '/lms/custom/my/get_screen_resolution.php';
        $this->resolution_path2 = $_SERVER['SERVER_NAME'];
    }

    function is_user_paid() {
        $status = 0;
        $courseid = $this->course->id;
        $userid = $this->user->id;

        //echo "Course ID: " . $courseid . "<br>";
        //echo "User ID: " . $userid . "<br>";
        // 1. Check among card payments
        $query = "select * from mdl_card_payments "
                . "where userid=$userid and courseid=$courseid";
        $card_payments_num = $this->db->numrows($query);

        // 2. Check among invoice payments
        $query = "select * from mdl_invoice "
                . "where userid=$userid and courseid=$courseid and i_status=1";
        $invoice_payments_num = $this->db->numrows($query);
        if ($card_payments_num > 0 || $invoice_payments_num > 0) {
            $status = 1;
        } // end if $card_payments_num>0 || $invoice_payments_num>0
        return $status;
    }

    function get_user_status() {
        //print_r($this->user);
        //echo "Username: " . $this->user->username . "<br>";
        $username = $this->user->username;
        //echo "Username: " . $username . "<br>";
        if ($username != 'manager') {
            $roleid = $this->get_user_role($this->user->id);
            if ($roleid == 5) {
                $status = $this->is_user_paid();
            } // end if $roleid == 5
            else {
                // It is Manager 
                $status = 1;
            }
        } // end if $username != 'manager'
        else {
            $status = 1;
        }

        return $status;
    }

    function get_user_warning_message() {
        $list = "";
        $userid = $this->user->id;
        $courseid=$this->course->id;
        $list.="<div class='container-fluid'>";
        $list.="<span class='span12'>Your account is not active because we did not receive payment from you. Please <a href='http://" . $_SERVER['SERVER_NAME'] . "/index.php/payments/index/$userid/$courseid' target='_blank'>click</a> here to pay by card. </span>";
        $list.="</div>";
        $list.="<div class='container-fluid'>";
        $list.="<span class='span12' >If you need help please contact support team.</span>";
        $list.="</div>";
        $list.="<div class='container-fluid'>";
        $list.="<span class='span12' id='logged_user_payment_card'></span>";
        $list.="</div>";
        return $list;
    }

    function get_course_categories() {
        $drop_down = "";
        $drop_down.="<select id='categories' style='width:120px;'>";
        $drop_down.="<option value='0' selected='selected'>Program type</option>";
        $query = "select id,name from mdl_course_categories";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $drop_down.="<option value=" . $row['id'] . ">" . $row['name'] . "</option>";
        }
        $drop_down.="</select>";
        return $drop_down;
    }

    public function get_courses_by_category($cat_id = null) {
        $drop_down = "";
        if ($cat_id != null) {
            $query = "select id, fullname from mdl_course where category=$cat_id";
            $num = $this->db->numrows($query);
            if ($num > 0) {
                $drop_down.="<select id='register_courses' style='width:120px;'><option value='0'>Program</option></select>";
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

    public function get_register_course_states_list($courseid = null) {
        $drop_down = "";
        $drop_down.="<select id='register_state' style='width:120px;'>";
        $drop_down.="<option value='0' selected>All States</option>";
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

    public function get_register_course_cities_list($courseid = null) {
        $drop_down = "";
        $drop_down.="<select id='register_cities' style='width:120px;'>";
        //$drop_down.="<select id='register_cities'>";
        $drop_down.="<option value='0' selected>All Cities</option>";
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

    function get_programs_panel() {
        $userid = $this->user->id;
        $cats = $this->get_course_categories();
        $courses = $this->get_courses_by_category();
        $register_state = $this->get_register_course_states_list();
        $cities = $this->get_register_course_cities_list();
        $screen_width = trim(file_get_contents($this->resolution_path));
        $list = "";
        $list.="<div class='panel panel-default' id='program_section' style='margin-bottom:0px;'>";
        $list.="<div class='panel-heading' style='text-align:left;'><h5 class='panel-title'>Available courses</h5></div>";
        $list.="<div class='panel-body' style='text-align:center;'>";
        $list.="<input type='hidden' value='$userid' id='userid'>";
        //echo "Width: " . htmlentities($screen_width) . "<br>";
        //echo "Screen type: " . gettype($screen_width) . "<br>";
        if ($screen_width > 1024) {
            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span3'>$cats</span>";
            $list.="<span class='span3' id='cat_course'>$courses</span>";
            $list.="<span class='span3' id='register_states_container'>$register_state</span>";
            $list.="<span class='span3' id='register_cities_container'>$cities</span>";
            $list.="</div>";

            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span2'><button type='button' class='btn btn-primary' id='internal_apply'>Apply</button></span>";
            $list.="<span class='span4' id='program_err' style='color:red;'></span>";
            $list.="</div>";
        } // end if $screen_width > 1024
        else {
            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span3'>$cats</span>";
            $list.="</div>";

            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span3' id='cat_course'>$courses</span>";
            $list.="</div>";

            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span3' id='register_states_container'>$register_state</span>";
            $list.="</div>";

            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span3' id='register_cities_container'>$cities</span>";
            $list.="</div>";

            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span3'><button type='button' class='btn btn-primary' id='internal_apply'>Apply</button></span>";
            $list.="<span class='span9' id='program_err' style='color:red;'></span>";
            $list.="</div>";
        } // end else

        $list.="</div>"; // end of panel-body
        $list.="</div><br>"; // end of panel panel-default

        return $list;
    }

    function get_state_name($stateid) {
        $query = "select * from mdl_states where id=$stateid";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $state = $row->state;
        }
        return $state;
    }

    function is_course_has_schedule($courseid, $stateid = null) {
        $num = 0;
        //echo "Function course id: $courseid";
        $query = "select id from mdl_scheduler where course=$courseid";
        $num = $this->db->numrows($query);
        //echo "Scheduler num: $num";
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $schedulerid = $row['id'];
            } // end foreach            
            // 2. Get slots list
            if ($state == null) {
                $query = "select * from mdl_scheduler_slots "
                        . "where schedulerid=$schedulerid order by starttime";
            } // end if $state==null
            else {
                $statename = $this->get_state_name($stateid);
                $query = "select * from mdl_scheduler_slots "
                        . "where schedulerid=$schedulerid "
                        . "and appointmentlocation like '%$statename%' "
                        . "order by starttime";
            } // end else                         
            $num = $this->db->numrows($query);
        } // end if $num > 0
        return $num;
    }

    function is_user_already_enrolled($courseid, $userid) {
        $contextid = $this->get_course_context($courseid);
        //echo "Context id: ".$contextid."<br>";
        $query = "select * from mdl_role_assignments "
                . "where roleid=5 "
                . "and contextid=$contextid "
                . "and userid=$userid";
        //echo "Query: ".$query."<br>";
        $num = $this->db->numrows($query);
        return $num;
    }
    
    function getEnrolId($courseid) {
        $query = "select id from mdl_enrol
                     where courseid=" . $courseid . " and enrol='manual'";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $enrolid = $row['id'];
        }
        return $enrolid;
    }

    function enroll_user($courseid, $userid) {
        $contextid = $this->get_course_context($courseid);
        $enrolid=$this->getEnrolId($courseid);
        
        $query = "insert into mdl_user_enrolments
             (enrolid,
              userid,
              timestart,
              modifierid,
              timecreated,
              timemodified)
               values ('" . $enrolid . "',
                       '" . $userid . "',
                        '" . time() . "',   
                        '2',
                         '" . time() . "',
                         '" . time() . "')";
        //echo "Query: ".$query."<br/>";
        $this->db->query($query);
        
        $query = "insert into mdl_role_assignments"
                . " (roleid,"
                . "contextid,"
                . "userid,"
                . "timemodified,"
                . "modifierid) "
                . "values('5',"
                . "'$contextid',"
                . "'$userid',"
                . "'" . time() . "','2')";
        $this->db->query($query);
    }

    function add_user_to_slot($userid, $slotid) {
        $query = "insert into mdl_scheduler_appointment "
                . "(slotid,"
                . "studentid,"
                . "attended) "
                . "values($slotid,"
                . "$userid,"
                . "0)";
        $this->db->query($query);
    }

    function enrol_user_to_course($courseid, $slotid, $userid) {
        $enrolled = $this->is_user_already_enrolled($courseid, $userid);
        //echo "Enrolled status: ".$enrolled."<br>";
        if ($enrolled == 0) {
            $this->enroll_user($courseid, $userid);
        } // end if $enrolled==0
        if ($slotid > 0) {
            $this->add_user_to_slot($userid, $slotid);
        } // end if $slotid>0
        $list = "You was successfully enrolled into selected course/schedule.The page will be reloaded ....";
        return $list;
    }

}
