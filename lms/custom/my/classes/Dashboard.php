<?php

/**
 * Description of Dashboard
 *
 * @author sirromas
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/Payment.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/Invoice.php';

class Dashboard extends Util {

    public $resolution_path;
    public $resolution_path2;

    function __construct() {
        parent::__construct();
        $this->resolution_path = 'https://' . $_SERVER['SERVER_NAME'] . '/lms/custom/my/get_screen_resolution.php';
        $this->resolution_path2 = $_SERVER['SERVER_NAME'];
    }

    function is_user_paid() {
        if ($this->user->id == 11772) {
            return 1;
        } // end if $this->user->id==11772
        $status = 0;
        $courseid = $this->course->id;
        $userid = $this->user->id;
        $invoice = new Invoice();
        $installment_status = $invoice->is_installment_user($userid, $courseid);
        if ($installment_status == 0) {
            // 1. Check among card payments
            $query = "select * from mdl_card_payments "
                    . "where userid=$userid and courseid=$courseid and refunded=0 ";
            $card_payments_num = $this->db->numrows($query);

            // 2. Check among invoice payments
            $query = "select * from mdl_invoice "
                    . "where userid=$userid and courseid=$courseid and i_status=1";
            $invoice_payments_num = $this->db->numrows($query);

            // 3. Check among partial payments
            $query = "select * from mdl_partial_payments "
                    . "where userid=$userid and courseid=$courseid";
            $partial_num = $this->db->numrows($query);

            if ($card_payments_num > 0 || $invoice_payments_num > 0 || $partial_num > 0) {
                $status = 1;
            } // end if $card_payments_num>0 || $invoice_payments_num>0
        } // end if $installment_status==0
        else {
            $interval = 604800; // 7 days in sec
            $query = "select * from mdl_installment_users "
                    . "where userid=$userid "
                    . "and courseid=$courseid";
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $subscription_id = $row['subscription_id'];
                $subscription_start = $row['subscription_start'];
            }
            if (is_numeric($subscription_id)) {
                $user_interval = time() - $subscription_start;
                if ($user_interval <= $interval) {
                    $status = 1;
                } // end if $user_interval<=$interval
                else {
                    $query = "select * from mdl_card_payments "
                            . "where userid=$userid and "
                            . "courseid=$courseid "
                            . "and refunded=0 "
                            . " and pdate>$subscription_start";
                    $status = $this->db->numrows($query);
                } // end else
            } // end if is_numeric($subscription_id)
            else {
                $status = 0;
            }
        } // end else when it is installment user
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

    function get_user_course_slot($courseid, $userid) {
        $query = "select * from mdl_slots "
                . "where courseid=$courseid and userid=$userid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $slotid = $row['slotid'];
        }
        return $slotid;
    }

    function get_renew_fee() {
        $query = "select * from mdl_renew_fee";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $fee = $row['fee_sum'];
        } // end while
        return $fee;
    }

    function get_user_warning_message() {
        $list = "";
        $userid = $this->user->id;
        $courseid = $this->course->id;
        $slotid = $this->get_user_course_slot($courseid, $userid);
        $list.="<div class='container-fluid'>";
        $list.="<span class='span12'>Your account is not active because we did not receive payment from you. Please <a href='https://" . $_SERVER['SERVER_NAME'] . "/index.php/payments/index/$userid/$courseid/$slotid/0' target='_blank'>click</a> here to pay by card. </span>";
        $list.="</div>";
        $list.="<div class='container-fluid'>";
        $list.="<span class='span12' >If you need help please contact support team.</span>";
        $list.="</div>";
        $list.="<div class='container-fluid'>";
        $list.="<span class='span12' id='logged_user_payment_card'></span>";
        $list.="</div>";
        return $list;
    }

    function get_course_categories($send = NULL) {
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
        $enrolid = $this->getEnrolId($courseid);

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

    function add_user_slots($courseid, $slotid, $userid) {
        $query = "insert into mdl_slots "
                . "(slotid,"
                . "courseid,"
                . "userid) "
                . "values($slotid,"
                . "$courseid,"
                . "$userid)";
        $this->db->query($query);
    }

    function enrol_user_to_course($courseid, $slotid, $userid) {
        $enrolled = $this->is_user_already_enrolled($courseid, $userid);
//echo "Enrolled status: ".$enrolled."<br>";
        if ($enrolled == 0) {
            $this->enroll_user($courseid, $userid);
        } // end if $enrolled==0
        if ($slotid > 0) {
            $this->add_user_slots($courseid, $slotid, $userid);
        } // end if $slotid>0
        $list = "You was successfully enrolled into selected course/schedule.The page will be reloaded ....";
        return $list;
    }

    function get_user_slots($userid) {
        $slotid = array();
        $query = "select * from mdl_scheduler_appointment "
                . "where studentid=$userid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $slotid[] = $row['slotid'];
            } // end while
        } // end if $num>0
        return $slotid;
    }

    function get_course_slots($courseid) {
        $slotids = array();
        $query = "select * from mdl_scheduler where course=$courseid";
//echo "Query: " . $query . "<br>";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $schedulerid = $row['id'];
        } // end while
//echo "SchedulerID: " . $schedulerid . "<br>";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $query = "select * from mdl_scheduler_slots "
                    . "where schedulerid=$schedulerid";
//echo "Query: " . $query . "<br>";
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $slotids[] = $row['id'];
            } // end while
        } // end if $num > 0
        return $slotids;
    }

    function get_user_card_payments($userid, $courseid) {
        $list = "";
        $query = "select * from mdl_card_payments "
                . "where courseid=$courseid and userid=$userid and refunded=0";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $list.="Paid by card $" . $row['psum'] . "&nbsp;(" . date('m-d-Y', $row['pdate']) . ")";
            } // end while
        } // end if $num>0
        else {
            $list.="Paid by card: N/A &nbsp;";
        }
        return $list;
    }

    function get_user_invoice_payments($userid, $courseid) {
        $list = "";
        $query = "select * from mdl_invoice "
                . "where courseid=$courseid and userid=$userid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $list.="Paid by cash $" . $row['i_sum'] . "&nbsp;(" . date('m-d-Y', $row['i_pdate']) . ")";
            } // end while
        } // end if $num>0
        else {
            $list.="Paid by cash: N/A &nbsp;";
        }
        return $list;
    }

    function get_user_partial_payments($userid, $courseid) {
        $list = "";
        $query = "select * from mdl_partial_payments  "
                . "where courseid=$courseid and userid=$userid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $list.="Paid by cash/cheque $" . $row['psum'] . "&nbsp;(" . date('m-d-Y', $row['pdate']) . ")";
            } // end while
        } // end if $num>0
        else {
            $list.="Paid by cheque: N/A &nbsp;";
        }
        return $list;
    }

    function get_user_payments($userid, $courseid) {
        $list = "";
        $card_payments = $this->get_user_card_payments($userid, $courseid);
//$invoice_payments = $this->get_user_invoice_payments($userid, $courseid);
        $partial_payments = $this->get_user_partial_payments($userid, $courseid);
        $list.=$card_payments . "&nbsp;" . $partial_payments;
//$list.=$invoice_payments;
        return $list;
    }

    function get_course_schedule_data($userid, $userslots, $courseid) {
        $list = "";
        date_default_timezone_set('Pacific/Wallis');
        $user_payments = $this->get_user_payments($userid, $courseid);
        if (count($userslots) > 0) {
            foreach ($userslots as $slotid) {
                $course_slots = $this->get_course_slots($courseid);
                foreach ($course_slots as $cslot) {
                    if ($cslot == $slotid) {
                        $query = "select * from mdl_scheduler_slots where id=$slotid";
                        $result = $this->db->query($query);
                        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                            $date = date('m-d-Y h:i:s', $row['starttime']);
                            $location_string = $row['appointmentlocation'];
                            $notes = $row['notes'];
                        } // end while
                        $location_arr = explode("/", $location_string);
                        $location = $location_arr[1] . "," . $location_arr[0];
                        $list.="<b>Date:</b> " . $date . "<br>";
                        $list.="<b>Location:</b> " . $location . "<br>$notes";
                        $list.="<b>Payment status:</b> $user_payments";
                    } // end if $cslot==$slotid
                    else {
//$list.="Program detailes: N/A";
                    }
                } // end foreach
            } // end foreach
        } // end if count($slotids)> 0 
        else {
            $list.="<b>Program detailes</b>: N/A<br>";
            $list.="<b>Payment status:</b> $user_payments";
        }
        return $list;
    }

    function get_address_block($userid) {
        $list = "";
        $user_detailes = $this->get_user_details($userid);
        $list.="$user_detailes->firstname $user_detailes->lastname<br>";
        $list.="Phone: $user_detailes->phone1<br>";
        $list.="Email: $user_detailes->email<br>";
        $list.="$user_detailes->address<br>";
        $list.="$user_detailes->city, $user_detailes->state, $user_detailes->zip";
        return $list;
    }

    function get_exam_courses() {
        $courses = array();
        $query = "SELECT * FROM mdl_course WHERE category =4"; // exams category
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $courses[] = $row['id'];
            } // end while
        } // end if $num > 0
        return $courses;
    }

    function get_course_questions_category($contextid) {
//mdl_question_categories
        $query = "select * from mdl_question_categories where contextid=$contextid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $id = $row['id'];
        }
        return $id;
    }

    function get_category_name($category) {
        $query = "select * from mdl_question_categories where id=$category";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $name = $row['name'];
        }
        return $name;
    }

    function is_category_has_items($category) {
        $query = "select * from mdl_question where category=$category";
        $num = $this->db->numrows($query);
        return $num;
    }

    function get_course_option_value($contextid) {
        $list = "";
        $query = "select * from mdl_question_categories where contextid=$contextid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $id = $row['id'];
            $num = $this->is_category_has_items($id);
            if ($num > 0) {
                $name = $this->get_category_name($id);
                $list.="<option value='" . $id . ",$contextid'>$name</option>";
            } // end of $num>0
        } // end while        
        return $list;
    }

    function get_courses_questions_banks() {
        $list = "";
        $list.="<select id='id_category' name='category'>";
        $courses = $this->get_exam_courses();
        $contexts = array();
        if (count($courses) > 0) {
            foreach ($courses as $courseid) {
                $contexts[] = $this->get_course_context($courseid);
            } // end foreach
        } // end if count($courses)>0

        if (count($contexts) > 0) {
            foreach ($contexts as $contextid) {
                $list.=$this->get_course_option_value($contextid);
            } // end foreach
        } // end if count($contexts)>0
        $list.="</select>";
        return $list;
    }

    function get_courses_questions_context() {
        $courses = $this->get_exam_courses();
        $contexts = array();
        if (count($courses) > 0) {
            foreach ($courses as $courseid) {
                $contexts[] = $this->get_course_context($courseid);
            } // end foreach
        } // end if count($courses)>0
        return $contexts;
    }

    function get_course_cost($courseid) {
        $query = "select * from mdl_course where id=$courseid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $cost = $row['cost'];
        }
        return $cost;
    }

    function get_course_category($courseid) {
        $query = "select * from mdl_course where id=$courseid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $category = $row['category'];
        }
        return $category;
    }

    function get_payments_history_block($courseid, $userid) {
        $list = "";
        $cc_list = "";
        $pp_list = "";
        $inv_list = "";
        $cc_payments = array();
        $cash_payments = array();
        $invoice_payments = array();
        $course_category = $this->get_course_category($courseid);

        date_default_timezone_set('Pacific/Wallis');


// 1. Get data from mdl_card_payments // payments made by card
        $query = "select * from mdl_card_payments "
                . "where refunded=0 and courseid=$courseid "
                . "and userid=$userid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $payment = new stdClass();
                foreach ($row as $key => $value) {
                    $payment->$key = $value;
                } // end foreach
                $cc_payments[] = $payment;
            } // end while
        } // end if $num > 0
//2. Get data from mdl_partial_payments  // cash payments
        $query = "select * from mdl_partial_payments "
                . "where courseid=$courseid "
                . "and userid=$userid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $payment = new stdClass();
                foreach ($row as $key => $value) {
                    $payment->$key = $value;
                } // end foreach
                $cash_payments[] = $payment;
            } // end while
        } // end if $num > 0
//3. Check invoice table
        $query = "select * from mdl_invoice "
                . "where userid=$userid "
                . "and courseid=$courseid "
                . "and i_status=1";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $payment = new stdClass();
                foreach ($row as $key => $value) {
                    $payment->$key = $value;
                } // end foreach
                $invoice_payments[] = $payment;
            } // end while
        } // end if $num>0


        $coursename = $this->get_course_name($courseid);
        $coursecost = $this->get_course_cost($courseid);
        $renew_fee = $this->get_renew_fee();
        $total_paid = 0;

        if (count($cc_payments) > 0) {
            $cc_list.="<table>";
            foreach ($cc_payments as $payment) {
                $date = date('m-d-Y', $payment->pdate);
                $cc_list.="<tr>";
                if ($payment->psum != $renew_fee) {
                    $cc_list.="<td style='padding:15px;'>Program/Workshop payment</td><td style='padding:15px;'>$$payment->psum</td><td style='padding:15px;'>$date</td>";
                } // end if $payment->psum!=$renew_fee
                else {
                    $cc_list.="<td style='padding:15px;'>Certificate renew payment</td><td style='padding:15px;'>$$payment->psum</td><td style='padding:15px;'>$date</td>";
                } // end else
                $cc_list.="</tr>";
                $total_paid = $total_paid + $payment->psum;
            } // end foreach
            $cc_list.="</table>";
        } // end if count($cc_payments)>0


        if (count($cash_payments) > 0) {
            $pp_list.="<table>";
            foreach ($cash_payments as $payment) {
                $date = date('m-d-Y', $payment->pdate);
                $pp_list.="<tr>";
                $pp_list.="<td style='padding:15px;'>Program/Workshop payment</td><td style='padding:15px;'>$$payment->psum</td><td style='padding:15px;'>$date</td>";
                $pp_list.="</tr>";
                $total_paid = $total_paid + $payment->psum;
            } // end foreach            
            $pp_list.="</table>";
        } // end if count($cash_payments) > 0

        if (count($invoice_payments) > 0) {
            $inv_list.="<table>";
            foreach ($invoice_payments as $payment) {
                $date = date('m-d-Y', $payment->i_pdate);
                $inv_list.="<tr>";
                $inv_list.="<td style='padding:15px;'>Program/Workshop payment</td><td style='padding:15px;'>$$payment->i_sum</td><td style='padding:15px;'>$date</td>";
                $inv_list.="</tr>";
                $total_paid = $total_paid + $payment->i_sum;
            } // end foreach
            $inv_list.="</table>";
        } // end if count($invoice_payments)>0        
//echo "Course cost: " . $coursecost . "<br>";
//echo "Total paid: " . $total_paid . "<br>";
        $balance = $coursecost - $total_paid;
        if ($balance >= 0) {
            $clear_balance = $balance;
        } // end if $balance>=0
        else {
            $clear_balance = 0;
        } // end else

        $list.="<table>";
        $list.="<tr>";
        $list.="<th>$coursename - payments history</th>";
        $list.="</tr>";
        $list.="<tr>";
        $list.="<th>Program/Workshop fee - $$coursecost</th>";
        $list.="</tr>";
        $list.="<tr>";
        $list.="<td>$cc_list</td>";
        $list.="</tr>";
        $list.="<tr>";
        $list.="<td>$pp_list</td>";
        $list.="</tr>";
        $list.="<tr>";
        $list.="<td>$inv_list</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td style='padding:15px;'>Your unpaid balance - $$clear_balance</td>";
        $list.="</tr>";

        $list.="</table><br>";

        if ($course_category == 5) {
            // It is college programs            
            $slotid = $this->get_user_course_slot($courseid, $userid);
            $list.="<input type='hidden' id='courseid' value='$courseid'>";
            $list.="<input type='hidden' id='userid' value='$userid'>";
            $list.="<input type='hidden' id='slotid' value='$slotid'>";
            $list.="<table border='0'>";
            $list.="<tr valign='middle'>";
            $list.="<td style='padding:15px;'><input  type='text' id='amount' name='amount' ></td><td style='padding:15px;'><button id='make_college_strudent_partial_payment'>Make Payment</button></td>";
            $list.="</tr>";
            $list.="<tr>";
            $list.="<td style='padding:15px;' colspan='2'><span id='partial_err'></span></td>";
            $list.="</tr>";
            $list.="</table>";
            //<a href = 'https://" . $_SERVER['SERVER_NAME'] . "/index.php/payments/index/$userid/$courseid/$slotid/0
        } // end if $course_category == 5



        return $list;
    }

}
