<?php

/**
 * Description of Dashboard
 *
 * @author sirromas
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/Payment.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/Invoice.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/schedule/classes/Schedule.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/certificates/classes/Renew.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/register/classes/Register.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/certificates/classes/Certificates2.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/partial/classes/Partial.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/balance/classes/Balance.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/grades/classes/Grades.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/Mailer.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/pdf/mpdf/mpdf.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/pdf/dompdf/autoload.inc.php';

use Dompdf\Dompdf;

class Dashboard extends Util {

    public $resolution_path;
    public $resolution_path2;
    public $assignment_module;
    public $assesment_path;
    public $student_role = 5;
    public $workshop_category = 2;

    function __construct() {
        parent::__construct();
        $this->resolution_path = 'https://' . $_SERVER['SERVER_NAME'] . '/lms/custom/my/get_screen_resolution.php';
        $this->resolution_path2 = $_SERVER['SERVER_NAME'];
        $this->assignment_module = 1;
        $this->assesment_path = $_SERVER['DOCUMENT_ROOT'] . "/lms/custom/my";
        $this->create_programs_data();
    }

    function is_user_paid() {
        if ($this->user->id == 11772 || $this->user->id == 11773) {
            return 1;
        } // end if $this->user->id==11772
        $status = 0;
        $courseid = $this->course->id;
        $userid = $this->user->id;

        $contextid = $this->get_course_context($courseid);
        $roleid = $this->get_user_role($userid, $contextid);

        if ($roleid <= 3) {
            return 1;
        } // end if
        else {
            $categoryid = $this->get_course_category($courseid);
            if ($categoryid >= 5) {
                return 1;
            } // end if 
            $invoice = new Invoice();
            $installment_status = $invoice->is_installment_user($userid, $courseid);
            //echo "Installment status: ".$installment_status."<br>";
            if ($installment_status == 0) {
                // 1. Check among card payments
                $query = "select * from mdl_card_payments "
                        . "where userid=$userid and courseid=$courseid and refunded=0 ";
                $card_payments_num = $this->db->numrows($query);

                //echo "Card payments num: " . $card_payments_num . "<br>";
                // 2. Check among invoice payments
                $query = "select * from mdl_invoice "
                        . "where userid=$userid and courseid=$courseid and i_status=1";
                $invoice_payments_num = $this->db->numrows($query);
                //echo "Invoice payments num: " . $invoice_payments_num . "<br>";
                // 3. Check among partial payments
                $query = "select * from mdl_partial_payments "
                        . "where userid=$userid and courseid=$courseid";
                $partial_num = $this->db->numrows($query);
                //echo "Partial payments num: " . $partial_num . "<br>";
                //4. Check among free access 
                $query = "select * from mdl_free where courseid=$courseid and userid=$userid";
                $free_num = $this->db->numrows($query);
                //echo "Free payments num: " . $free_num . "<br>";
                //5. Check among any invoice payments
                $query = "select * from mdl_any_invoice_user where userid=$userid";
                $num = $this->db->numrows($query);
                if ($num > 0) {
                    $result = $this->db->query($query);
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        $invoiceid = $row['invoiceid'];
                    }

                    $query = "select * from mdl_invoice where id=$invoiceid";
                    $result = $this->db->query($query);
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        $db_courseid = $row['courseid'];
                    }
                    if ($db_courseid == $courseid) {
                        $any_invoice_num = 1;
                    } // end if $db_courseid==$courseid
                    else {
                        $any_invoice_num = 0;
                    }
                } // end if $num>0
                else {
                    $any_invoice_num = 0;
                }
                //echo "Any invoice num: " . $card_payments_num . "<br>";
                if ($card_payments_num > 0 || $invoice_payments_num > 0 || $partial_num > 0 || $any_invoice_num > 0 || $free_num > 0) {
                    $status = 1;
                } // end if $card_payments_num>0 || $invoice_payments_num>0
            } // end if $installment_status==0
            else {
                $query = "select * from mdl_installment_users "
                        . "where userid=$userid "
                        . "and courseid=$courseid and canceled=0";
                $result = $this->db->query($query);
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $status = $row['completed']; // could be 0/1
                }
                $status = 1; // temp workaround - allow installment users to access course
            } // end else when it is installment user
        } // end else 

        return $status;
    }

    function get_user_status() {

        $username = $this->user->username;
        if ($username != 'manager') {
            $contextid = $this->get_course_context($this->course->id);
            $roleid = $this->get_user_role($this->user->id, $contextid);
            //echo "Role ID: ".$roleid."<br>";
            if ($roleid == 5) {
                $status = $this->is_user_paid();
            } // end if $roleid == 5
            elseif ($roleid <= 3) {
                // It is teacher, manager or higher 
                $status = 1;
            } // end if 
        } // end if $username != 'manager'
        else {
            $status = 1;
        }

        return $status;
    }

    function get_user_course_slot($courseid, $userid) {
        $query = "select * from mdl_slots "
                . "where courseid=$courseid and userid=$userid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $slotid = $row['slotid'];
            }
        } //end if
        else {
            $slotid = 0;
        }
        return $slotid;
    }

    function get_renew_fee($courseid) {
        $renew = new Renew();
        $amount = $renew->get_renew_amount($courseid);
        return $amount;
    }

    function get_user_warning_message() {
        $list = "";
        $userid = $this->user->id;
        $courseid = $this->course->id;
        $slotid = $this->get_user_course_slot($courseid, $userid);
        //echo "User slot: " . $slotid . "<br>";
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
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $schedulerid = $row['id'];
        } // end while
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $query = "select * from mdl_scheduler_slots "
                    . "where schedulerid=$schedulerid";
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $slotids[] = $row['id'];
            } // end while
        } // end if $num > 0
        return $slotids;
    }

    function get_user_course_completion_status($userid, $courseid) {
        $query = "select * from mdl_course_completions "
                . "where userid=$userid and course=$courseid";
        $num = $this->db->numrows($query);
        return $num;
    }

    function get_user_card_payments($userid, $courseid) {
        $list = "";
        $b = new Balance();
        $already_paid = 0;
        $current_user = $this->user->id;
        $slotid = $this->get_user_course_slot($courseid, $userid);
        $cost = $b->get_item_cost($courseid, $userid, $slotid);
        $renew_amount = $this->get_renew_fee($courseid);
        $query = "select * from mdl_card_payments "
                . "where courseid=$courseid and userid=$userid and refunded=0";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $coursename = $this->get_course_name($courseid);
            $status = $this->get_user_course_completion_status($userid, $courseid);
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $already_paid = $already_paid + $row['psum'];
                $diff = $cost - $already_paid;
                $owe = ($diff < 0) ? '-$' . abs($diff) : '$' . $diff;
                $list.="<div class='container-fluid' style='padding-left:0px;'>";
                if ($row['psum'] != $renew_amount) {
                    $list.="<span class='span8'>Paid by card $" . round($row['psum']) . "&nbsp;(" . date('m-d-Y', $row['pdate']) . ") &nbsp; $coursename </span>";
                } // end if $row['psum']!=$renew_amount
                else {
                    $list.="<span class='span8'>Paid by card $" . round($row['psum']) . "&nbsp;(" . date('m-d-Y', $row['pdate']) . ") &nbsp; Certificate Renewal Fee ($coursename) </span>";
                } // end else
                if ($status == 0) {
                    $prohibit = $this->get_user_roles($userid);
                    if ($prohibit == 0 && ($current_user == 2 || $current_user == 234)) {
                        $list.="<span class='span2'><button class='profile_move_payment'  data-userid='$userid' data-courseid='$courseid' data-paymentid='c_" . $row['id'] . "'>Move</button></span>";
                        $list.="<span class='span2'><button class='profile_refund_payment'data-userid='$userid' data-courseid='$courseid' data-paymentid='c_" . $row['id'] . "'>Refund</button></span>";
                    }
                }
                $list.="</div>";
            } // end while
        } // end if $num>0

        return $list;
    }

    function get_user_invoice_payments($userid, $courseid) {
        $list = "";
        $current_user_id = $this->user->id;
        $query = "select * from mdl_invoice "
                . "where courseid=$courseid and userid=$userid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $coursename = $this->get_course_name($courseid);
            $status = $this->get_user_course_completion_status($userid, $courseid);
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $list.="<div class='container-fluid' style=''>";
                $list.="<span class='span8'>Paid by invoice $" . round($row['i_sum']) . "&nbsp;(" . date('m-d-Y', $row['i_date']) . ") &nbsp; $coursename </span>";
                if ($status == 0 && ($current_user_id == 2 || $current_user_id == 234)) {
                    $list.="<span class='span2'><button class='profile_move_payment'  data-userid='$userid' data-courseid='$courseid' data-paymentid='i_" . $row['id'] . "'>Move</button></span>";
                    $list.="<span class='span2'><button class='profile_refund_payment'data-userid='$userid' data-courseid='$courseid' data-paymentid='i_" . $row['id'] . "'>Refund</button></span>";
                }
                $list.="</div>";
            } // end while
        } // end if $num>0

        return $list;
    }

    function get_user_partial_payments($userid, $courseid) {
        $list = "";
        $already_paid = 0;
        $current_user_id = $this->user->id;
        $b = new Balance();
        $slotid = $this->get_user_course_slot($courseid, $userid);
        $cost = $b->get_item_cost($courseid, $userid, $slotid);
        $renew_amount = $this->get_renew_fee($courseid);
        $query = "select * from mdl_partial_payments  "
                . "where courseid=$courseid and userid=$userid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $coursename = $this->get_course_name($courseid);
            $status = $this->get_user_course_completion_status($userid, $courseid);
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $already_paid = $already_paid + $row['psum'];
                $diff = $cost - $already_paid;
                $owe = ($diff < 0) ? '-$' . abs($diff) : '$' . $diff;
                $list.="<div class='container-fluid' style='padding-left:0px;'>";
                if ($row['psum'] != $renew_amount) {
                    $list.="<span class='span8'>Paid by cash/cheque $" . round($row['psum']) . "&nbsp;(" . date('m-d-Y', $row['pdate']) . ") &nbsp; $coursename </span>";
                } // end if 
                else {
                    $list.="<span class='span8'>Paid by cash/cheque $" . round($row['psum']) . "&nbsp;(" . date('m-d-Y', $row['pdate']) . ") &nbsp; Certificate Renewal Fee ($coursename) </span>";
                } // end else
                if ($status == 0 && ($current_user_id == 2 || $current_user_id == 234)) {
                    $list.="<span class='span2'><button class='profile_move_payment'  data-userid='$userid' data-courseid='$courseid' data-paymentid='p_" . $row['id'] . "'>Move</button></span>";
                    $list.="<span class='span2'><button class='profile_refund_payment'data-userid='$userid' data-courseid='$courseid' data-paymentid='p_" . $row['id'] . "'>Refund</button></span>";
                }
                $list.="</div>";
            } // end while
        } // end if $num>0
        return $list;
    }

    function get_user_free_payments($userid, $courseid) {
        $list = "";
        $query = "select * from mdl_free  "
                . "where courseid=$courseid and userid=$userid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $coursename = $this->get_course_name($courseid);
            $status = $this->get_user_course_completion_status($userid, $courseid);
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $list.="<div class='container-fluid' style='padding-left:0px;'>";
                $list.="<span class='span8'><span style='color:red;'>Discount: $" . round($row['psum']) . "</span>&nbsp;(" . date('m-d-Y', $row['pdate']) . ") &nbsp; $coursename </span>";
                if ($status == 0) {
                    $list.="<span class='span2'><button class='profile_move_payment'  data-userid='$userid' data-courseid='$courseid' data-paymentid='p_" . $row['id'] . "'>Move</button></span>";
                    $list.="<span class='span2'><button class='profile_refund_payment'data-userid='$userid' data-courseid='$courseid' data-paymentid='p_" . $row['id'] . "'>Refund</button></span>";
                }
                $list.="</div>";
            } // end while
        } // end if $num>0
        return $list;
    }

    function get_refund_payments($userid, $courseid) {
        $list = "";

        // 1. Get full refunds
        $query = "select * from mdl_card_payments "
                . "where courseid=$courseid "
                . "and userid=$userid and refunded=1 and refund_date<>''";
        //echo "Query: " . $query . "<br>";
        $num = $this->db->numrows($query);
        //echo "Num: " . $num . "<br>";
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $coursename = $this->get_course_name($courseid);
                $date = date('m-d-Y', $row['refund_date']);
                $list.="<div class='row-fluid'>";
                $list.="<span span9>Refunded $" . $row['psum'] . " ($date) $coursename</span>";
                $list.="</div>";
            } // end while
        } // end if $num > 0
        // 2. Get partial refunds
        $query = "select * from mdl_partial_refund_payments "
                . "where courseid=$courseid and userid=$userid";
        //echo "Query: " . $query . "<br>";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $coursename = $this->get_course_name($courseid);
                $date = date('m-d-Y', $row['refund_date']);
                $list.="<div class='row-fluid'>";
                $list.="<span span9>Refunded $" . $row['psum'] . " ($date) $coursename</span>";
                $list.="</div>";
            } // end while
        } // end if $num > 0

        return $list;
    }

    function get_original_payment_info($courseid, $userid) {
        $query = "select * from mdl_card_payments "
                . "where courseid=$courseid and userid=$userid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $pdate = date('m-d-Y', $row['pdate']);
            $psum = $row['psum'];
        }
        $p = new stdClass();
        $p->pdate = $pdate;
        $p->psum = $psum;
        return $p;
    }

    function get_user_all_refund_payments($userid) {
        $list = "";
        $list.="<div class='row-fluid' style='color:red;font-weigh:bold;'>";
        $list.="<span class='span6'><hr/></span>";
        $list.="</div>";

        // 1. Get full refunds
        $query = "select * from mdl_card_payments "
                . "where
                userid=$userid and refunded=1 and refund_date<>''";
        //echo "Query: " . $query . "<br>";
        $num = $this->db->numrows($query);
        //echo "Num: " . $num . "<br>";
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $coursename = $this->get_course_name($row['courseid']);
                $pdate = date('m-d-Y', $row['pdate']);
                $date = date('m-d-Y', $row['refund_date']);

                // Original payment
                $list.="<div class='row-fluid' style=''>";
                $list.="<span class='span9'>Paid by card $" . $row['psum'] . " ($pdate) $coursename</span>";
                $list.="</div>";

                // Refund info
                $list.="<div class='row-fluid' style='color:red;font-weigh:bold;'>";
                $list.="<span class='span9'>Refunded $" . $row['psum'] . " ($date) $coursename</span>";
                $list.="</div>";
            } // end while
        } // end if $num > 0
        // 2. Get partial refunds
        $query = "select * from mdl_partial_refund_payments "
                . "where userid=$userid";
        //echo "Query: " . $query . "<br>";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $coursename = $this->get_course_name($row['courseid']);
                $original_payment = $this->get_original_payment_info($row['courseid'], $row['userid']);
                $pdate = $original_payment->pdate;
                $psum = $original_payment->psum;
                $fullpsum = $psum + $row['psum'];
                $date = date('m-d-Y', $row['refund_date']);

                $list.="<div class='row-fluid'>";
                $list.="<span class='span9'>Paid by card $" . $fullpsum . " ($pdate) $coursename</span>";
                $list.="</div>";

                $list.="<div class='row-fluid' style='color:red;font-weigh:bold;'>";
                $list.="<span class='span9'>Refunded $" . $row['psum'] . " ($date) $coursename</span>";
                $list.="</div>";
            } // end while
        } // end if $num > 0

        return $list;
    }

    function get_user_payments($userid, $courseid) {
        $list = "";
        $card_payments = $this->get_user_card_payments($userid, $courseid);
        //$refund_payments = $this->get_refund_payments($userid, $courseid);
        //$invoice_payments = $this->get_user_invoice_payments($userid, $courseid);
        $partial_payments = $this->get_user_partial_payments($userid, $courseid);
        $free_payments = $this->get_user_free_payments($userid, $courseid);
        $list.=$card_payments . $partial_payments . $free_payments;
        return $list;
    }

    function get_course_schedule_data($userid, $userslots, $courseid) {
        $list = "";

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

    function get_user_roles($userid) {
        $prohibit = 0;
        $query = "select * from mdl_role_assignments where userid=$userid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                if ($row['roleid'] < 5) {
                    $prohibit = 1;
                }
            } // end while
        } // end if $num > 0
        return $prohibit;
    }

    function get_address_block($userid) {
        $list = "";
        $currentuser = $this->user->id;
        if ($currentuser != 2 && $userid != $currentuser) {
            $prohibit = $this->get_user_roles($userid);
            if ($prohibit == 0) {
                $user_detailes = $this->get_user_details($userid);
                $list.="$user_detailes->firstname $user_detailes->lastname<br>";
                $list.="Phone: $user_detailes->phone1<br>";
                $list.="Email: $user_detailes->email<br>";
                $list.="$user_detailes->address<br>";
                $list.="$user_detailes->city, $user_detailes->state, $user_detailes->zip";
            } // end if $prohibit == 0
        } // end if $currentuser != 2 && $userid != $currentuser
        else {
            $user_detailes = $this->get_user_details($userid);
            $list.="$user_detailes->firstname $user_detailes->lastname<br>";
            $list.="Phone: $user_detailes->phone1<br>";
            $list.="Email: $user_detailes->email<br>";
            $list.="$user_detailes->address<br>";
            $list.="$user_detailes->city, $user_detailes->state, $user_detailes->zip";
        }
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

    function check_free_access($userid) {
        $query = "select * from mdl_free where userid=$userid";
        $free_num = $this->db->numrows($query);
        return $free_num;
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
        $renew_fee = $this->get_renew_fee($courseid);
        $total_paid = 0;

        if (count($cc_payments) > 0) {
            $cc_list.="<table>";
            foreach ($cc_payments as $payment) {
                $date = date('m-d-Y', $payment->pdate);
                $cc_list.="<tr>";
                if ($payment->psum != $renew_fee) {
                    $cc_list.="<td style='padding:15px;'>Program/Workshop payment</td><td style='padding:15px;'>$$payment->psum</td><td style='padding:15px;'>$date</td>";
                    $total_paid = $total_paid + $payment->psum;
                } // end if $payment->psum!=$renew_fee

                /*
                  else {
                  $cc_list.="<td style='padding:15px;'>Certificate renew payment</td><td style='padding:15px;'>$$payment->psum</td><td style='padding:15px;'>$date</td>";
                  } // end else
                 */

                $cc_list.="</tr>";
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

        $free_acces = $this->check_free_access($userid);
        if ($free_acces > 0) {
            $clear_balance = 0;
        }

        $list.="<table>";
        $list.="<tr>";
        $list.="<th>$coursename - payments history</th>";
        $list.="</tr>";

        /*
          $list.="<tr>";
          $list.="<th>Program/Workshop fee - $$coursecost</th>";
          $list.="</tr>";
         */

        $list.="<tr>";
        $list.="<td>$cc_list</td>";
        $list.="</tr>";
        $list.="<tr>";
        $list.="<td>$pp_list</td>";
        $list.="</tr>";
        $list.="<tr>";
        $list.="<td>$inv_list</td>";
        $list.="</tr>";

        /*
          $list.="<tr>";
          $list.="<td style='padding:15px;'>Your unpaid balance - $$clear_balance</td>";
          $list.="</tr>";
         */

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

    function is_assignment_page() {
        $querystring = $_SERVER['REQUEST_URI'];
        $needle = "mod/assign/view.php";
        $status = strpos($querystring, $needle);
        if ($status !== FALSE) {
            return 1;
        }
    }

    function get_module_id() {
        $querystring = $_SERVER['REQUEST_URI'];
        parse_str($querystring, $output);
        $id = $output['/lms/mod/assign/view_php?id'];
        return $id;
    }

    function create_assignment_pdf($moduleid) {
        $query = "select * from mdl_course_modules where id=$moduleid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $instanceid = $row['instance'];
        }

        $query = "select * from mdl_assign where id=$instanceid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $html = $row['intro'];
        }

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();
        $output = $dompdf->output();

        $file_path = $this->assesment_path . "/assesment_$instanceid.pdf";
        file_put_contents($file_path, $output);

        $path = "https://" . $_SERVER['SERVER_NAME'] . "/lms/custom/my/assesment_$instanceid.pdf";

        return $path;
    }

    function get_notes_status($userid) {
        $query = "select * from mdl_post where module='notes' and userid=$userid";
        $num = $this->db->numrows($query);
        return $num;
    }

    function get_grades_block($courseid, $userid) {
        $list = "";
        $gr = new Grades;
        $grades = $gr->get_student_grades($courseid, $userid);
        if (count($grades) > 0) {
            foreach ($grades as $gradeitem) {
                $list.="<div class='row-fluid'>";
                $list.="<span class='span4'>$gradeitem->name</span>";
                $list.="<span class='span1'>$gradeitem->grade %</span>";
                $list.="<span class='span1'>$gradeitem->date</span>";
                $list.="</div>";
            } // end foreach
        } // end if count($grades)>0
        else {
            $list.='N/A';
        } // end else
        return $list;
    }

    function get_user_grades($userid) {
        $list = "";
        $gr = new Grades;
        $courses = $gr->get_user_courses($userid);
        if (count($courses) > 0) {
            foreach ($courses as $courseid) {
                $coursename = $this->get_course_name($courseid);
                $grades = $this->get_grades_block($courseid, $userid);
                $list.="<div class='row-fluid' style='font-weight:bold;'>";
                $list.="<span class='span12'>$coursename</span>";
                $list.="</div>";
                $list.="<div class='row-fluid' style='font-weight:bold;'>";
                $list.="<span class='span6'><hr/></span>";
                $list.="</div>";
                $list.="<div class='row-fluid'>";
                $list.="<span class='span12'>$grades</span>";
                $list.="</div>";
            } // end foreach
        } // end if count($courses)>0
        else {
            $list.='N/A';
        } // end else
        return $list;
    }

    function get_students_status_box() {
        $list = "";
        $list.="<select id='students_status'>";
        $list.="<option value='A'>Absent</p>";
        $list.="<option value='P' selected>Present</option>";
        $list.="<option value='T'>Tardy</option>";
        $list.="</select>";
        return $list;
    }

    function get_presence_modal_dialog($at) {
        $list = "";
        $status = $this->get_students_status_box();

        $list.="<div id='myModal' class='modal fade'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h4 class='modal-title'>Student attendance</h4>
                </div>
                <div class='modal-body'>
                <input type='hidden' id='at_userid' value='$at->userid'>
                <input type='hidden' id='at_courseid' value='$at->courseid'>
                <input type='hidden' id='at_date' value='$at->date'>     
                   
                <div class='container-fluid'>
                <span class='span1'>Status</span>
                <span class='span3'>$status</span>
                </div>
                
                <div class='container-fluid'>
                <span class='span1'>Notes</span>
                <span class='span3'><textarea id='at_notes' style='width:365px;'></textarea></span>
                </div>
                
                <div class='modal-footer' style='text-align:center;'>
                    <span align='center'><button type='button' class='btn btn-primary' data-dismiss='modal' id='cancel'>Cancel</button></span>
                    <span align='center'><button type='button' class='btn btn-primary' id='update_student_attendance'>OK</button></span>
                </div>
            </div>
        </div>
    </div>";

        return $list;
    }

    function is_at_date_exists($at) {
        $udate = strtotime($at->date);
        $query = "select * from mdl_user_attendance "
                . "where courseid=$at->courseid "
                . "and userid=$at->userid "
                . "and adate='$udate'";
        $num = $this->db->numrows($query);
        return $num;
    }

    function delete_calendar_entry($at) {
        $udate = strtotime($at->date);
        $query = "delete from mdl_user_attendance "
                . "where courseid=$at->courseid and "
                . "userid=$at->userid and "
                . "adate='$udate'";
        $this->db->query($query);
    }

    function insert_calendar_entry($at) {
        $udate = strtotime($at->date);
        $query = "insert into mdl_user_attendance "
                . "(courseid, notes, "
                . "userid,"
                . "status,"
                . "adate) "
                . "values ($at->courseid, "
                . "'$at->notes',"
                . "$at->userid,"
                . "'$at->status',"
                . "'$udate')";
        $this->db->query($query);
    }

    function update_student_attendance($at) {
        $exists = $this->is_at_date_exists($at);
        if ($exists == 0) {
            $this->insert_calendar_entry($at);
        } // end if $exists==0
        else {
            $this->delete_calendar_entry($at);
        }
    }

    function get_student_calendar_dates($courseid, $userid) {
        $list = "";
        $query = "select * from mdl_user_attendance "
                . "where courseid=$courseid "
                . "and userid=$userid order by adate desc";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $status = $row['status'];
                $list.="<div class='row-fluid'>";
                $list.="<span class='span2'>" . date('m-d-Y', $row['adate']) . "</span>";
                $list.="<span class='span1'>$status</span>";
                $list.="<span class='span6'>" . $row['notes'] . "</span>";
                $list.="</div>";
            } // end while
        } // end if $num > 0
        else {
            $list.='N/A';
        }
        return $list;
    }

    function student_at_block($courseid, $userid) {
        $list = "";

        $current_userid = $this->user->id;
        if ($current_userid == 2 || $current_userid == 234) {
            $roleid = 3;
        } // end if
        else {
            $roleid = $this->get_user_role($userid);
        } // end else

        $dates = $this->get_student_calendar_dates($courseid, $userid);

        $list.="<div class='row-fluid'>";
        $list.="<span class='span8'>Please select calendar dates to add students attendance</span>";
        $list.="</div>";

        $list.="<div class='row-fluid'>";
        if ($roleid == 3) {
            $list.="<span class='span3'><div class='at_calendar' data-userid='$userid' data-courseid='$courseid'></div></span>";
        } // end if $roleid==3
        $list.="<span class='span9' style='padding-left:35px;'>$dates</span>";
        $list.="</div>";

        $list.="<div class='row-fluid'>";
        $list.="<span class='span8'><hr/></span>";
        $list.="</div>";

        return $list;
    }

    function get_student_attendance($userid) {
        $list = "";
        $gr = new Grades;
        $courses = $gr->get_user_courses($userid);

        if (count($courses) > 0) {
            foreach ($courses as $courseid) {
                $categoryid = $this->get_course_category($courseid);
                if ($categoryid == 5) {
                    // Career College programs
                    $coursename = $this->get_course_name($courseid);
                    $at = $this->student_at_block($courseid, $userid);
                    $list.="<br><div class='row-fluid' style='font-weight:bold;'>";
                    $list.="<span class='span9'>$coursename</span>";
                    $list.="</div>";

                    $list.="<div class='row-fluid'>";
                    $list.="<span class='span4'><hr/></span>";
                    $list.="</div>";

                    $list.="<div class='row-fluid'>";
                    $list.="<span class='span9'>$at</span>";
                    $list.="</div>";
                } // end if $categoryid==5
            } // end foreach
        } // end if count($courses)>0
        return $list;
    }

    function get_att_report($userid) {
        $list = "";
        $gr = new Grades;
        $courses = $gr->get_user_courses($userid);

        if (count($courses) > 0) {
            foreach ($courses as $courseid) {
                $categoryid = $this->get_course_category($courseid);
                if ($categoryid == 5) {
                    // Career College programs
                    $coursename = $this->get_course_name($courseid);
                    $user = $this->get_user_details($userid);

                    $list.="<br><table align='center'>";

                    $list.="<tr>";
                    $list.="<td colspan='3' style='padding:15px;text-align:center;font-weight:bold;'>$user->firstname $user->lastname</td>";
                    $list.="</tr>";

                    $list.="<tr>";
                    $list.="<td colspan='3' style='padding:15px;text-align:center;font-weight:bold;'>$coursename</td>";
                    $list.="</tr>";

                    $list.="<tr style='text-align:center;'>";
                    $list.="<th style='padding:15px'>Date</th>";
                    $list.="<th style='padding:15px'>Status</th>";
                    $list.="<th style='padding:15px'>Notes</th>";
                    $list.="</tr>";

                    $query = "select * from mdl_user_attendance "
                            . "where courseid=$courseid "
                            . "and userid=$userid order by adate desc";
                    $num = $this->db->numrows($query);
                    if ($num > 0) {
                        $result = $this->db->query($query);
                        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                            $date = date('m-d-Y', $row['adate']);
                            $status = $row['status'];
                            $notes = $row['notes'];
                            $list.="<tr style='text-align:center;'>";
                            $list.="<td style='padding:15px'>$date</td>";
                            $list.="<td style='padding:15px'>$status</td>";
                            $list.="<td style='padding:15px'>$notes</td>";
                            $list.="</tr>";
                        } // end while
                    } // end if $num > 0
                    $list.="</table>";
                } // end if $categoryid==5
            } // end foreach
        } // end if count($courses)>0
        return $list;
    }

    function create_attendance_report($userid) {
        $list = $this->get_att_report($userid);
        $pdf = new mPDF('utf-8', 'A4-L');
        $pdf->WriteHTML($list);
        $filename = "attendance_$userid.pdf";
        $path = $_SERVER['DOCUMENT_ROOT'] . "/lms/custom/my/$filename";
        $pdf->Output($path, 'F');
        return $filename;
    }

    function get_payment_report($userid) {
        $list = "";
        $user = $this->get_user_details($userid);
        $already_paid = 0;
        $b = new Balance();
        $list.="<table align='center'>";
        $list.="<tr>";
        $list.="<th colspan='4' style='padding:15px;'>$user->firstname $user->lastname</th>";
        $list.="</tr>";
        $courses = $this->get_user_courses($userid);
        if (count($courses) > 0) {
            foreach ($courses as $courseid) {
                $slotid = $this->get_user_course_slot($courseid, $userid);
                $cost = $b->get_item_cost($courseid, $userid, $slotid);
                $balance = $this->get_user_course_balance($userid, $courseid, true);
                // 1. Card payments
                $query = "select * from mdl_card_payments "
                        . "where userid=$userid "
                        . "and courseid=$courseid "
                        . "and refunded=0";
                $num1 = $this->db->numrows($query);
                if ($num1 > 0) {
                    $result = $this->db->query($query);
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        $already_paid = $already_paid + $row['psum'];
                        $diff = $cost - $already_paid;
                        $owe = ($diff < 0) ? '-$' . abs($diff) : '$' . $diff;
                        $courseid = $row['courseid'];
                        $coursename = $this->get_course_name($courseid);
                        $date = date('m-d-Y', $row['pdate']);
                        $sum = $row['psum'];
                        $list.="<tr>";
                        $list.="<td style='padding:15px;'>Paid by Card</td>";
                        $list.="<td style='padding:15px;'>$$sum</td>";
                        $list.="<td style='padding:15px;'>$date</td>";
                        $list.="<td style='padding:15px;'>$coursename</td>";
                        //$list.="<td style='padding:15px;'>Balance: $owe</td>";
                        $list.="</tr>";
                    } // end while
                } // end if $num >0
                $already_paid2 = 0;
                // 2. Partial payments
                $query = "select * from mdl_partial_payments "
                        . "where userid=$userid and courseid=$courseid";
                $num2 = $this->db->numrows($query);
                if ($num2 > 0) {
                    $result = $this->db->query($query);
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        $courseid = $row['courseid'];
                        $coursename = $this->get_course_name($courseid);
                        $date = date('m-d-Y', $row['pdate']);
                        $sum = $row['psum'];
                        $slotid = $this->get_user_course_slot($courseid, $userid);
                        $cost = $b->get_item_cost($courseid, $userid, $slotid);
                        $already_paid2 = $already_paid2 + $sum;
                        $diff = $cost - $already_paid2;
                        $owe = ($diff < 0) ? '-$' . abs($diff) : '$' . $diff;
                        $list.="<tr>";
                        $list.="<td style='padding:15px;'>Paid by Cash/Cheque</td>";
                        $list.="<td style='padding:15px;'>$$sum</td>";
                        $list.="<td style='padding:15px;'>$date</td>";
                        $list.="<td style='padding:15px;'>$coursename</td>";
                        $list.="</tr>";
                    } // end while
                } // end if $num2 >0§
                // 3. Free adjustments
                $query = "select * from mdl_free "
                        . "where courseid=$courseid "
                        . "and userid=$userid";
                $num3 = $this->db->numrows($query);
                if ($num3 > 0) {
                    $result = $this->db->query($query);
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        $date = date('m-d-Y', $row['pdate']);
                        $sum = $row['psum'];
                        $list.="<tr>";
                        $list.="<td style='padding:15px;'>Discount: </td>";
                        $list.="<td style='padding:15px;'>$$sum</td>";
                        $list.="<td style='padding:15px;'>$date</td>";
                        $list.="<td style='padding:15px;'>$coursename</td>";
                        $list.="</tr>";
                    }
                } // end if $num3>0
                if ($num1 > 0 || $num2 > 0 || $num3) {
                    $list.="<tr>";
                    $list.="<td style='padding:15px;' colspan='4'>$balance</td>";
                    $list.="</tr>";
                }
            } // end foreach
        } // end if count($courses)>0
        else {
            $list.="N/A";
        } // end else


        /*
          $query = "select * from mdl_card_payments "
          . "where userid=$userid and refunded=0";
          $num = $this->db->numrows($query);
          if ($num > 0) {
          $result = $this->db->query($query);
          while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
          $courseid = $row['courseid'];
          $coursename = $this->get_course_name($courseid);
          $date = date('m-d-Y', $row['pdate']);
          $sum = $row['psum'];
          $list.="<tr>";
          $list.="<td style='padding:15px;'>Paid by Card</td>";
          $list.="<td style='padding:15px;'>$$sum</td>";
          $list.="<td style='padding:15px;'>$date</td>";
          $list.="<td style='padding:15px;'>$coursename</td>";
          $list.="</tr>";
          } // end while
          } // end if $num >0

          $query = "select * from mdl_partial_payments "
          . "where userid=$userid ";
          $num = $this->db->numrows($query);
          if ($num > 0) {
          $result = $this->db->query($query);
          while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
          $courseid = $row['courseid'];
          $coursename = $this->get_course_name($courseid);
          $date = date('m-d-Y', $row['pdate']);
          $sum = $row['psum'];
          $list.="<tr>";
          $list.="<td style='padding:15px;'>Paid by Cash/Cheque</td>";
          $list.="<td style='padding:15px;'>$$sum</td>";
          $list.="<td style='padding:15px;'>$date</td>";
          $list.="<td style='padding:15px;'>$coursename</td>";
          $list.="</tr>";
          } // end while
          } // end if $num >0
         */

        $list.="</table>";

        $pdf = new mPDF('utf-8', 'A4-L');
        $pdf->WriteHTML($list);
        $filename = "payment_$userid.pdf";
        $path = $_SERVER['DOCUMENT_ROOT'] . "/lms/custom/my/$filename";
        $pdf->Output($path, 'F');
        return $filename;
    }

    // ************* Code related to custom profile section  ************** /

    function get_user_profile_custom_sections($id) {
        $list = "";
        $current_user_id = $this->user->id;
        $payments = $this->get_user_payments_block($id);
        $workshops = $this->get_user_workshops($id);
        $certficates = $this->get_user_certificates($id);
        $grades = $this->get_user_grades($id);
        $attend = $this->get_student_attendance($id);
        $other = $this->get_other_tab($id);

        if ($current_user_id == 2 || $current_user_id == 234) {
            $list.="<ul class='nav nav-tabs'>
                <li class='active'><a data-toggle='tab' href='#home'>Payments</a></li>";
            $list.="<li><a data-toggle='tab' href='#menu1'>Courses</a></li>
              <li><a data-toggle='tab' href='#menu2'>Certificates</a></li>
              <li><a data-toggle='tab' href='#grades'>Grades</a></li>
              <li><a data-toggle='tab' href='#attend'>Attendance</a></li>
              <li><a data-toggle='tab' href='#menu3'>Other</a></li>";

            $list.="<input type='hidden' id='userid' value='$id'>  
            </ul>";

            $list.="<div class='tab-content'>
              <div id='home' class='tab-pane fade in active'>
                <h3>Payments &nbsp;&nbsp;<button id='print_payment'>Print</button></h3>
                <p>$payments</p>
              </div>
              <div id='menu1' class='tab-pane fade'>
                <h3>Courses</h3>
                <p>$workshops</p>
              </div>
              <div id='menu2' class='tab-pane fade'>
                <h3>Certificates</h3>
                <p>$certficates</p>
              </div>
              <div id='grades' class='tab-pane fade'>
                <h3>Grades &nbsp;&nbsp;<button id='print_grades'>Print</button></h3>
                <p>$grades</p>
              </div>
             <div id='attend' class='tab-pane fade'>
                <h3>Attendance &nbsp;&nbsp;<button id='print_att'>Print</button></h3>
                <p>$attend</p>
              </div>
              <div id='menu3' class='tab-pane fade'>
                <h3>Other</h3>
                <p>$other</p>
              </div> 
            </div>";
        } // end if
        else {
            //$status = $this->is_student_only_career_college($id);
            //if ($status == 1) {
            $list.="<ul class='nav nav-tabs'>
                <li class='active'><a data-toggle='tab' href='#home'>Payments</a></li>";
            $list.="<input type='hidden' id='userid' value='$id'>  
            </ul>";

            $list.="<div class='tab-content'>
              <div id='home' class='tab-pane fade in active'>
                <h3>Payments &nbsp;&nbsp;<button id='print_payment'>Print</button></h3>
                <p>$payments</p>
              </div>
             </div>";
            //} // end if $status == 1
        } // end else

        return $list;
    }

    function is_student_only_career_college($userid) {
        $status = 1;
        $courses = $this->get_user_courses($userid);
        if (count($courses) > 0) {
            foreach ($courses as $courseid) {
                $categoryid = $this->get_course_category($courseid);
                if ($categoryid != 5) {
                    $status = 0;
                    break;
                }
            } // end foreach
        } // end if count($courses)>0
        else {
            $status = 0;
        } // end else 
        return $status;
    }

    function is_user_suspended($userid) {
        $query = "select * from mdl_user where id=$userid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $suspended = $row['suspended'];
        }
        return $suspended;
    }

    function get_other_tab($id) {
        $list = "";
        $userid = $this->user->id;
        $suspended = $this->is_user_suspended($id);
        $title = ($suspended == 0) ? 'Suspend' : 'Activate';
        $button = "<button class='profile_user_suspend' data-userid='$id' data-status='$suspended'>$title</button>";

        $list.="<div class='container-fluid'>";
        if ($userid == 2) {
            $list.="<span class='span2'><button data-userid='$id' class='delete_profile_user' style='width:175px;'>Delete User</button></span>";
            $list.= "<span class='span2'>$button</span>";
        } // end if $this->user->id == 2
        else {
            $list.= "<span class='span2'>$button</span>";
        }
        $list.="</div>";

        return $list;
    }

    function suspend_user($userid, $state) {
        $status = ($state == 0) ? 1 : 0;
        $query = "update mdl_user set suspended=$status where id=$userid";
        $this->db->query($query);
    }

    function get_courseid_by_name($coursename, $wsname = '') {
        $query = "select * from mdl_course where fullname='$coursename'";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $courseid = $row['id'];
        }

        if ($wsname != '') {
            $sch = new Schedule();
            $slotid = $sch->get_slotid_by_name($wsname);
        } // end if 
        else {
            $slotid = 0;
        } // end else
        $program = array('courseid' => $courseid, 'slotid' => $slotid);
        return json_encode($program);
    }

    function create_programs_data() {
        $query = "select * from mdl_course where cost>0 and visible=1";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $fullname[] = mb_convert_encoding($row['fullname'], 'UTF-8');
        }
        file_put_contents('/home/cnausa/public_html/lms/custom/utils/programs.json', json_encode($fullname));
    }

    function get_courseid_by_contextid($id) {
        $query = "select * from mdl_context where id=$id";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $courseid = $row['instanceid'];
        }
        return $courseid;
    }

    function get_user_courses($id) {
        $courses = array();
        $query = "select * from mdl_role_assignments "
                . "where roleid=5 and userid=$id";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $courses[] = $this->get_courseid_by_contextid($row['contextid']);
            } // end while
        } // end if $num > 0
        return $courses;
    }

    function get_course_id_by_slot_id($slotid) {
        $query = "select * from mdl_scheduler_slots where id=$slotid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $schedulerid = $row['schedulerid'];
        }
        $query = "select * from mdl_scheduler where id=$schedulerid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $courseid = $row['course'];
        }
        return $courseid;
    }

    function get_profile_course_slotid($userid, $courseid) {
        $query = "SELECT * FROM `mdl_scheduler_appointment` WHERE studentid=$userid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $course_by_slot = $this->get_course_id_by_slot_id($row['slotid']);
                if ($course_by_slot == $courseid) {
                    $slotid = $row['slotid'];
                    break;
                } // end if $course_by_slot==$courseid
            } // end while 
        } // end if $num > 0
        else {
            $slotid = null;
        }
        return $slotid;
    }

    function get_user_course_balance($userid, $courseid, $report = false) {
        $list = "";
        $b = new Balance();
        $slotid = $this->get_profile_course_slotid($userid, $courseid);
        $cost = $b->get_item_cost($courseid, $userid, $slotid);
        $total = $b->get_student_payments($courseid, $userid);
        $diff = $cost - $total;
        $owe = ($diff < 0) ? '-$' . abs($diff) : '$' . $diff;
        if ($report) {
            $list.="<table>";
            $list.="<tr style='font-weight:bold;' style=''>";
            $list.="<td style='padding:0px'>Program cost:</td><td style='padding:15px'>$$cost</td>";
            $list.="<td style='padding:0px'>Paid:</td><td style='padding:15px'>$$total</td>";
            $list.="<td style='padding:0px'>Balance:</td><td style='padding:15px'>$owe</td>";
            $list.="</tr>";
            //$list.="<tr style='font-weight:bold;'>";
            //$list.="<td style='padding:0px' colspan='9'><hr/></td>";
            //$list.="</tr>";
            $list.="</table>";
        } // end if $report
        else {
            $list.="<div class='row-fluid' style='font-weight:bold;color:red;'>";
            $list.="<span class='span1'>Cost:</span><span class='span1'>$$cost</span>";
            $list.="<span class='span1'>Paid:</span><span class='span1'>$$total</span>";
            $list.="<span class='span1'>Balance:</span><span class='span1'><span style='font-weight:bold;color:red;'>$owe</span></span>";
            $list.="</div>";
        } // end else

        return $list;
    }

    function get_user_payments_block($id) {
        $list = "";
        $current_userid = $this->user->id;
        $courses = $this->get_user_courses($id);
        if ($current_userid == 2 || $current_userid == 234) {
            $list.="<div class='container-fluid' style=''>";
            $list.="<span class='span3'><button class='profile_add_payment' style='width:175px;' data-userid='$id'>Add payment</button></span>";
            $list.="</div><br><br>";
        }
        $refund_payments = $this->get_user_all_refund_payments($id);
        if (count($courses) > 0) {
            foreach ($courses as $courseid) {
                $payments = $this->get_user_payments($id, $courseid);
                $balance = $this->get_user_course_balance($id, $courseid);

                if ($payments != '') {
                    $list.="<div class='container-fluid' style=''>";
                    $list.="<span class='span12'>$payments</span>";
                    $list.="</div>";
                    $list.="<div class='container-fluid' style=''>";
                    $list.="<span class='span6'><hr/></span>";
                    $list.="</div>";
                    $list.="<div class='container-fluid' style=''>";
                    $list.="<span class='span12'>$balance</span>";
                    $list.="</div>";
                    $list.="<div class='container-fluid' style=''>";
                    $list.="<span class='span6'><br></span>";
                    $list.="</div>";
                } // end if
            } // end foreach
            $list.="<div class='container-fluid' style=''>";
            $list.="<span class='span12'>$refund_payments</span>";
            $list.="</div>";
        } // end if count($courses)>0
        else {
            $list.="N/A";
        } // end else

        return $list;
    }

    function get_worshop_course($slotid) {
        $query = "select * from mdl_scheduler_slots where id=$slotid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $schedulerid = $row['schedulerid'];
        }

        $query = "select * from mdl_scheduler where id=$schedulerid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $courseid = $row['course'];
        }
        return $courseid;
    }

    function is_workshop_slot_exists($slotid) {
        $query = "select * from mdl_scheduler_slots where id=$slotid";
        $num = $this->db->numrows($query);
        return $num;
    }

    function get_user_workshops($id) {
        $list = "";
        $list.="<div class='container-fluid' style=''>";
        $list.="<span class='span6'><button class='profile_add_to_workshop' style='width:205px;' data-userid='$id'>Add course</button></span>";
        $list.="</div><br><br>";

        $query = "select * from mdl_scheduler_appointment where studentid=$id";

        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $status = $this->is_workshop_slot_exists($row['slotid']);
                if ($status > 0) {
                    $ws = new stdClass();
                    foreach ($row as $key => $value) {
                        $ws->$key = $value;
                    }
                    $courseid = $this->get_worshop_course($row['slotid']);
                    $coursename = $this->get_course_name($courseid);
                    $ws->courseid = $courseid;
                    $ws->coursename = $coursename;
                    /*
                      echo "<pre>";
                      print_r($ws);
                      echo "<br></pre>";
                     */

                    $app[] = $ws;
                } // end if $status > 0
            } // end while

            foreach ($app as $ws) {
                $query = "select * from mdl_scheduler_slots where id=$ws->slotid";
                $result = $this->db->query($query);
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $list.="<div class='container-fluid' style=''>";
                    $date = date('m-d-Y', $row['starttime']);
                    $location = $row['appointmentlocation'];
                    $notes = $row['notes'];
                    $list.="<span class='span2'>$date</span>";
                    $list.="<span class='span3'>$ws->coursename<br>$location</span>";
                    $list.="<span class='span3'>$notes</span>";
                    $list.="<span class='span2'><button class='profile_move_to_workshop' data-userid='$id' data-slotid='$ws->slotid' data-appid='$ws->id' data-courseid='$ws->courseid'>Move</button></span>";
                    $list.="<span class='span2'><button class='profile_cancel_workshop' data-userid='$id' data-slotid='$ws->slotid' data-appid='$ws->id' data-courseid='$ws->courseid'>Remove</button></span>";
                    $list.="</div>";
                    $list.="<div class='container-fluid' style=''>";
                    $list.="<span class='12'><hr></span>";
                    $list.="</div>";
                } // end while
            } // end foreach 
        } // end if $num > 0
        else {
            $courses = $this->get_user_courses($id);
            if (count($courses) > 0) {
                foreach ($courses as $courseid) {
                    $coursename = $this->get_course_name($courseid);
                    $list.="<div class='container-fluid' style=''>";
                    $list.="<span class='span9'>$coursename</span>";
                    $list.="</div>";
                } // end foreach
            } // end if count($courses)>0
        }

        return $list;
    }

    function is_course_expired($id) {
        $query = "select * from mdl_course where id=$id";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $expire = $row['expired'];
        }
        return $expire;
    }

    function get_user_certificates($userid) {
        $list = "";
        $list.="<div class='container-fluid' style=''>";
        if ($this->user->id == 2) {
            $list.="<span class='span4'><button class='profile_create_cert' style='width:175px;' data-userid='$userid'>Create certificate</button></span>";
        }
        $list.="</div><br><br>";

        $query = "select * from mdl_certificates where userid=$userid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $list.="<div class='container-fluid' style=''>";
                $start = date('m-d-Y', $row['issue_date']);
                if ($row['expiration_date'] != '') {
                    $exp = date('m-d-Y', $row['expiration_date']);
                } // end if
                else {
                    $exp = 'N/A';
                } // end else
                $courseid = $row['courseid'];
                $id = $row['id'];
                $coursename = $this->get_course_name($courseid);
                $list.="<span class='span4'><a href='https://" . $_SERVER['SERVER_NAME'] . "/lms/custom/certificates/$userid/$courseid/certificate.pdf' target='_blank'>$coursename</a></span>";
                $list.="<span class='span2'>$start</span>";
                $expired = $this->is_course_expired($courseid);
                if ($expired > 0) {
                    $list.="<span class='span2'>$exp</span>";
                } // end if
                else {
                    $list.="<span class='span2'>N/A</span>";
                }
                $list.="<span class='span2'><button class='profile_send_cert' data-userid='$userid' data-courseid='$courseid' data-id='$id'>Send</button></span></span>";
                $list.="<span class='span2'><button class='profile_renew_cert' data-userid='$userid' data-courseid='$courseid' data-id='$id'>Renew</button></span></span>";
                $list.="</div>";
            } // end while
        } // end if $num > 0
        else {
            $list.="N/A";
        } // end else

        return $list;
    }

    function get_add_payment_dialog($userid) {
        $list = "";
        $cuser = $this->user->id;
        $list.="<div id='myModal' class='modal fade'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h4 class='modal-title'>Add Payment</h4>
                </div>
                <div class='modal-body'>
                <input type='hidden' id='userid' value='$userid'>";
        if ($cuser == 2) {
            $list.="<div class='container-fluid' style='text-align:center;'>
                  <span class='span1'><input type='radio' name='ptype' value='card' checked>Card</span>
                  <span class='span1'><input type='radio' name='ptype' value='cash'>Cash</span>
                  <span class='span1'><input type='radio' name='ptype' value='cheque'>Cheque</span>
                  <span class='span1'><input type='radio' name='ptype' value='free'>Free</span>
                </div>";
        } // end if $userid==2
        else {
            $list.="<div class='container-fluid' style='text-align:center;'>
                  <span class='span1'><input type='radio' name='ptype' value='card' checked>Card</span>
                  <span class='span1'><input type='radio' name='ptype' value='cash'>Cash</span>
                  <span class='span1'><input type='radio' name='ptype' value='cheque'>Cheque</span>
                </div>";
        } // end else

        $list.="<div class='container-fluid'>
                <span class='span1'>Amount</span>
                <span class='span3'><input type='text' id='amount' style='width:275px;'></span>
                </div>
                   
                <div class='container-fluid'>
                <span class='span1'>Program</span>
                <span class='span3'><input type='text' id='coursename' style='width:275px;'></span>    
                </div>
                
                <div class='container-fluid'>
                <span class='span1'>Venue</span>
                <span class='span3'><input type='text' id='wsname' style='width:275px;'></span>    
                </div>
                
                <div class='container-fluid' style=''>
                <span class='span6' style='color:red;' id='payment_err'></span>
                </div>
             
                <div class='modal-footer' style='text-align:center;'>
                    <span align='center'><button type='button' class='btn btn-primary' data-dismiss='modal' id='cancel'>Cancel</button></span>
                    <span align='center'><button type='button' class='btn btn-primary' id='add_profile_payment'>OK</button></span>
                </div>
            </div>
        </div>
    </div>";

        return $list;
    }

    function get_payment_move_dialog($courseid, $userid, $paymentid) {
        $list = "";

        $list.="<div id='myModal' class='modal fade'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h4 class='modal-title'>Move Payment</h4>
                </div>
                <div class='modal-body'>
                <input type='hidden' id='userid' value='$userid'>
                <input type='hidden' id='oldcourseid' value='$courseid'>
                <input type='hidden' id='d_paymentid' value='$paymentid'>     
                   
                <div class='container-fluid'>
                <span class='span1'>Program</span>
                <span class='span3'><input type='text' id='coursename' style='width:275px;'></span>
                <br><br>
                </div>
                
                <div class='container-fluid' style=''>
                <span class='span6' style='color:red;' id='payment_err'></span>
                </div>
             
                <div class='modal-footer' style='text-align:center;'>
                    <span align='center'><button type='button' class='btn btn-primary' data-dismiss='modal' id='cancel'>Cancel</button></span>
                    <span align='center'><button type='button' class='btn btn-primary' id='move_profile_payment'>OK</button></span>
                </div>
            </div>
        </div>
    </div>";

        return $list;
    }

    function get_delete_user_dialog($userid) {
        $list = "";

        $query = "select * from mdl_refund_pwd where id=1";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $dbpwd = $row['pwd'];
        }

        $list.="<div id='myModal' class='modal fade'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h4 class='modal-title'>Delete User</h4>
                </div>
                <div class='modal-body'>
                
                <input type='hidden' id='userid' value='$userid'>
                <input type='hidden' id='dbpwd' value='$dbpwd'>
                    
                <div class='container-fluid'>
                <span class='span1'>Password</span>
                <span class='span3'><input type='text' id='pwd' style='width:275px;'></span>
                <br><br>
                </div>
                
                <div class='container-fluid' style=''>
                <span class='span6' style='color:red;' id='user_err'></span>
                </div>
             
                <div class='modal-footer' style='text-align:center;'>
                    <span align='center'><button type='button' class='btn btn-primary' data-dismiss='modal' id='cancel'>Cancel</button></span>
                    <span align='center'><button type='button' class='btn btn-primary' id='delete_user_profile'>OK</button></span>
                </div>
            </div>
        </div>
    </div>";

        return $list;
    }

    function delete_profile_user($userid) {
        // 1. Delete user payments
        // 2. // Delete user itself
        // a) Delete credit card payment
        // b) Delete cash payments
        // c) Delete free payments 
        // d) Delete invoice payments

        $query = "delete from mdl_card_payments where userid=$userid";
        $this->db->query($query);

        $query = "delete from mdl_partial_payments where userid=$userid";
        $this->db->query($query);

        $query = "delete from mdl_free where userid=$userid";
        $this->db->query($query);

        $query = "delete from mdl_invoice where userid=$userid";
        $this->db->query($query);

        $query = "update mdl_user set deleted=1 where id=$userid";
        $this->db->query($query);
    }

    function move_payment($payment) {
        $payments_data = explode('_', $payment->id);

        switch ($payments_data[0]) {
            case 'c':
                // credit cards
                $query = "update mdl_card_payments "
                        . "set courseid=$payment->courseid "
                        . "where id=$payments_data[1]";

                break;
            case 'i':
                // invoices
                $query = "update mdl_invoice "
                        . "set courseid=$payment->courseid "
                        . "where id=$payments_data[1]";

                break;
            case 'p':
                // partial payments (cash/cheque)
                $query = "update mdl_partial_payments "
                        . "set courseid=$payment->courseid "
                        . "where id=$payments_data[1]";

                break;
        }

        $this->assign_roles($payment->userid, $payment->courseid);
        $this->db->query($query);
        $list = "ok";
        return $list;
    }

    function getCourseContext($courseid) {
        $query = "select id from mdl_context
                     where contextlevel=50
                     and instanceid='" . $courseid . "' ";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $contextid = $row['id'];
        }
        return $contextid;
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

    function assign_roles($userid, $courseid) {
        $roleid = $this->student_role;
        $enrolid = $this->getEnrolId($courseid);
        $contextid = $this->getCourseContext($courseid, $roleid);

        // Check if user already enrolled?
        $query = "select * from mdl_role_assignments "
                . "where userid=$userid "
                . "and contextid='$contextid'";
        //echo "Query: " . $query . "<br>";
        $num = $this->db->numrows($query);
        //echo "Num: " . $num . "<br>";
        if ($num == 0) {

            // 1. Insert into mdl_user_enrolments table
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
            //echo "Query: " . $query . "<br/>";
            $this->db->query($query);

            // 2. Insert into mdl_role_assignments table
            $query = "insert into mdl_role_assignments
                  (roleid,
                   contextid,
                   userid,
                   timemodified,
                   modifierid)                   
                   values ('" . $roleid . "',
                           '" . $contextid . "',
                           '" . $userid . "',
                           '" . time() . "',
                            '2'         )";
            //echo "Query: " . $query . "<br/>";
            $this->db->query($query);
        }
    }

    function add_other_payment($payment) {
        $date = time();
        // Enroll user into course
        $this->assign_roles($payment->userid, $payment->courseid);
        if ($payment->ptype != 'free') {
            $type = ($payment->ptype == 'cash') ? 1 : 2;
            $slotid = $payment->slotid;
            $query = "insert into mdl_partial_payments "
                    . "(courseid,"
                    . "userid,"
                    . "slotid,"
                    . "ptype,"
                    . "psum,"
                    . "pdate) "
                    . "values($payment->courseid, "
                    . "$payment->userid, "
                    . "$slotid, "
                    . "'$type', "
                    . "'$payment->amount', "
                    . "'$date')";
        } // end if $payment->ptype!='free'
        else {
            $query = "insert into mdl_free (courseid, userid, psum, pdate) "
                    . "values ($payment->courseid,"
                    . "$payment->userid, "
                    . "'$payment->amount', "
                    . "'$date')";
        } // end else
        $this->db->query($query);

        if ($slotid > 0) {
            $query = "insert into mdl_scheduler_appointment "
                    . "(slotid,studentid,attended) "
                    . "values($slotid,$payment->userid,0)";
            $this->db->query($query);
        }

        // Do not create notice for free adjustment
        if ($payment->ptype != 'free') {
            $userObj = $this->get_user_details($payment->userid);
            $userObj->courseid = $payment->courseid;
            $userObj->userid = $payment->userid;
            $userObj->slotid = $payment->slotid;
            $userObj->payment_amount = $payment->amount;
            $userObj->period = 0;
            $mailer = new Mailer();
            //$mailer->send_partial_payment_confirmation($userObj);
            $mailer->send_partial_payment_confirmation2($userObj);
        }
    }

    function refund_payment($payment) {

        /*
         * 
          echo "<pre>";
          print_r($payment);
          echo "</pre>";
          die();
         * 
         */
        $now = time();
        $payments_data = explode('_', $payment->id);
        switch ($payments_data[0]) {
            case 'c':
                // credit cards
                $query = "select * from mdl_card_payments "
                        . "where id=$payments_data[1]";
                $result = $this->db->query($query);
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $amount = $row['psum'];
                    $card_last_four = $row['card_last_four'];
                    $exp_date = $row['exp_date'];
                    $trans_id = $row['trans_id'];
                }
                $pr = new ProcessPayment();
                $status = $pr->makeRefund($amount, $card_last_four, $exp_date, $trans_id);
                if ($status) {
                    $query = "update mdl_card_payments "
                            . "set refunded=1, refund_date='$now' "
                            . "where id=$payments_data[1]";
                    $this->db->query($query);
                }
                break;
            case 'i':
                // invoices
                $query = "delete from mdl_invoice "
                        . "where id=$payments_data[1]";
                break;
            case 'p':
                // partial payments (cash/cheque)
                $query = "delete from mdl_partial_payments "
                        . "where id=$payments_data[1]";
                break;
        }
        $this->db->query($query);
    }

    function get_add_to_workshop_dialog($userid) {
        $list = "";
        $r = new Register();
        $form = $r->get_program_register_form();
        $list.="<div id='myModal' class='modal fade' style='width:875px;left:40%;'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h4 class='modal-title'>Add course</h4>
                </div>
                <div class='modal-body'>
                <input type='hidden' id='userid' value='$userid'>
                    
                <div class='row-fluid' style=''><span class='span12'>$form</span></div>
                
                <div class='container-fluid' style=''>
                <span class='span6' style='color:red;' id='ws_err'></span>
                </div><br><br>
             
                <div class='container-fluid' style='text-align:left;'>
                    <span class='span1' style='margin-left:0px;'><button type='button' class='btn btn-primary' data-dismiss='modal' id='cancel'>Cancel</button></span>
                    <span class='span1' style='padding-left:25px;'><button type='button' class='btn btn-primary' id='add_to_ws'>OK</button></span>
                </div>

                
            </div>
        </div>
    </div>";

        return $list;
    }

    function add_user_to_workshop($ws) {

        /*
          echo "<pre>";
          print_r($ws);
          echo "</pre>";

          [courseid] => 45
          [userid] => 11773
          [slotid] => 1302
         */

        $enroll = new Enroll();
        $enroll->assign_roles($ws->userid, $ws->courseid);

        if ($ws->slotid > 0) {
            $query = "insert into mdl_scheduler_appointment "
                    . "(slotid,studentid,attended) "
                    . "values($ws->slotid,$ws->userid,0)";
            $this->db->query($query);
        }
    }

    function get_move_to_workshop_dialog($courseid, $slotid, $appid) {
        $list = "";

        $list.="<div id='myModal' class='modal fade'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h4 class='modal-title'>Move student to other workshop</h4>
                </div>
                <div class='modal-body'>
                <input type='hidden' id='appid' value='$appid'>
                <input type='hidden' id='courseid' value='$courseid'>
                <input type='hidden' id='slotid' value='$slotid'>    
                   
                <div class='container-fluid'>
                <span class='span1'>Workshop</span>
                <span class='span3'><input type='text' id='wsname' style='width:275px;'></span>
                <br><br>
                </div>
                
                <div class='container-fluid' style=''>
                <span class='span6' style='color:red;' id='ws_err'></span>
                </div>
             
                <div class='modal-footer' style='text-align:center;'>
                    <span align='center'><button type='button' class='btn btn-primary' data-dismiss='modal' id='cancel'>Cancel</button></span>
                    <span align='center'><button type='button' class='btn btn-primary' id='move_to_ws'>OK</button></span>
                </div>
            </div>
        </div>
    </div>";

        return $list;
    }

    function move_user_to_workshop($ws) {

        //$schedulerid = $this->get_course_scheduler($ws->courseid);
        $sch = new Schedule();
        $slotid = $sch->get_slotid_by_name($ws->wsname);
        if ($slotid > 0) {
            $query = "update mdl_scheduler_appointment set slotid=$slotid "
                    . "where id=$ws->appid";
            $this->db->query($query);
        }
    }

    function get_add_create_cert_dialog($userid) {
        $list = "";

        $list.="<div id='myModal' class='modal fade'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h4 class='modal-title'>Create user certificate</h4>
                </div>
                <div class='modal-body'>
                <input type='hidden' id='userid' value='$userid'>
                   
                <div class='container-fluid'>
                <span class='span1'>Program</span>
                <span class='span3'><input type='text' id='coursename' style='width:275px;'></span>
                <br><br>
                </div>
                
                <div class='container-fluid'>
                <span class='span1'>Issue</span>
                <span class='span3'><input type='text' id='date1' style='width:275px;'></span>
                </div>
                
                <div class='container-fluid'>
                <span class='span1'>Expire</span>
                <span class='span3'><input type='text' id='date2' style='width:275px;'></span>
                </div>
                
                <div class='container-fluid' style=''>
                <span class='span6' style='color:red;' id='program_err'></span>
                </div>
             
                <div class='modal-footer' style='text-align:center;'>
                    <span align='center'><button type='button' class='btn btn-primary' data-dismiss='modal' id='cancel'>Cancel</button></span>
                    <span align='center'><button type='button' class='btn btn-primary' id='create_user_cert'>OK</button></span>
                </div>
            </div>
        </div>
    </div>";

        return $list;
    }

    function get_send_cert_dialog($cert) {
        $list = "";

        $user = $this->get_user_details($cert->userid);
        $email = $user->email;
        $list.="<div id='myModal' class='modal fade'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h4 class='modal-title'>Send certificate</h4>
                </div>
                <div class='modal-body'>
                <input type='hidden' id='userid' value='$cert->userid'>
                <input type='hidden' id='courseid' value='$cert->courseid'>
                   
                <div class='container-fluid'>
                <span class='span3'><input type='checkbox' id='default_email' checked>Send certificate to student</span>
                <span class='span3'><input type='text' id='user_email' style='width:275px;' disabled value='$email'></span>
                <br><br>
                </div>
                
                <div class='container-fluid' style=''>
                <span class='span6' style='color:red;' id='cert_err'></span>
                </div>
             
                <div class='modal-footer' style='text-align:center;'>
                    <span align='center'><button type='button' class='btn btn-primary' data-dismiss='modal' id='cancel'>Cancel</button></span>
                    <span align='center'><button type='button' class='btn btn-primary' id='send_user_cert'>OK</button></span>
                </div>
            </div>
        </div>
    </div>";

        return $list;
    }

    function send_user_cetificate($cert) {

        $user = $this->get_user_details($cert->userid);
        $path = $_SERVER['DOCUMENT_ROOT'] . "/lms/custom/certificates/$cert->userid/$cert->courseid/certificate.pdf";
        $subject = "Medical2 - Certificate";
        $list = "";
        $list.="<html><body>";
        $list.="<br/><p>Dear $user->firstname $user->lastname!</p>";

        $list.="<p>Please find out certificate attached.</p>";
        $list.="<p>If you need help, please contact us via email $this->mail_smtp_user</p>";
        $list.="<p>Best regards,</p>";
        $list.="<p>Support team.</p>";
        $list.="</body></html>";

        $m = new Mailer();
        $m->send_email($subject, $list, $cert->email, $path, 1);
    }

    function pass_user_at_the_course($user) {
        $date = time();
        $query = "insert into mdl_course_completions "
                . "(userid,"
                . "course,"
                . "timeenrolled,"
                . "timecompleted) "
                . "values ($user->userid,"
                . "$user->courseid,"
                . "'$date'"
                . ",$date)";
        $this->db->query($query);
    }

    function create_user_certificate($certificate) {
        $start_arr = explode('/', $certificate->date1);
        $end_arr = explode('/', $certificate->date2);
        $start_date = $start_arr[2] . "-" . $start_arr[0] . "-" . $start_arr[1];
        $end_date = $end_arr[2] . "-" . $end_arr[0] . "-" . $end_arr[1];
        $this->pass_user_at_the_course($certificate);
        $cert = new Certificates();
        $cert->create_certificate($certificate->courseid, $certificate->userid, $start_date, $end_date);
    }

    function get_renew_cert_dialog($cert) {
        $list = "";

        $userid = $this->user->id;

        $query = "select * from mdl_certificates where id=$cert->id";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $certificate = new stdClass();
            foreach ($row as $key => $value) {
                $certificate->$key = $value;
            } // end foreach
        } // end while

        if ($userid == 2) {

            $start = date('m/d/Y', $certificate->issue_date);
            $end = date('m/d/Y', $certificate->expiration_date);

            $list.="<div id='myModal' class='modal fade'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h4 class='modal-title'>Renew user certificate</h4>
                </div>
                <div class='modal-body'>
                <input type='hidden' id='id' value='$certificate->id'>
                
                <div class='container-fluid'>
                <span class='span1'>Issue</span>
                <span class='span3'><input type='text' id='date1' style='width:275px;' value='$start'></span>
                </div>
                
                <div class='container-fluid'>
                <span class='span1'>Expire</span>
                <span class='span3'><input type='text' id='date2' style='width:275px;' value='$end'></span>
                </div>
                
                <div class='container-fluid' style=''>
                <span class='span6' style='color:red;' id='program_err'></span>
                </div>
             
                <div class='modal-footer' style='text-align:center;'>
                    <span align='center'><button type='button' class='btn btn-primary' data-dismiss='modal' id='cancel'>Cancel</button></span>
                    <span align='center'><button type='button' class='btn btn-primary' id='renew_user_cert'>OK</button></span>
                </div>
            </div>
        </div>
    </div>";
        } // end if $userid==2
        else {
            $list.="<div id='myModal' class='modal fade'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h4 class='modal-title'>Renew user certificate</h4>
                </div>
                <div class='modal-body' style='text-align:center;'>
                
                <input type='hidden' id='id' value='$certificate->id'>
                
                <div class='container-fluid' style='text-align:left;'>
                
                 <span class='span1'>
                 <input type='radio' name='renew_payment_type' class='ptype' value='0' checked>Card
                 </span>
                 
                  <span class='span1'>
                  <input type='radio' name='renew_payment_type' class='ptype' value='1'>Cash
                  </span>
                 
                  <span class='span2'>
                  <input type='radio' name='renew_payment_type' class='ptype' value='2'>Cheque
                  </span>
              
                </div>
                
                 <div class='container-fluid' style='text-align:left;'>
                 
                 <span class='span1'>
                 <input type='radio' name='period' class='period' value='1' checked>1 Year
                 </span>
                 
                  <span class='span1'>
                  <input type='radio' name='period' class='period' value='2'>2 Year
                  </span>
                 
                  <span class='span2'>
                  <input type='radio' name='period' class='period' value='3'>3 Year
                  </span>
                
                  </div>

                <div class='container-fluid' style=''>
                <span class='span6' style='color:red;' id='program_err'></span>
                </div>
             
                <div class='modal-footer' style='text-align:center;'>
                    <span align='center'><button type='button' class='btn btn-primary' data-dismiss='modal' id='cancel'>Cancel</button></span>
                    <span align='center'><button type='button' class='btn btn-primary' id='renew_user_cert_manager'>OK</button></span>
                </div>
            </div>
        </div>
    </div>";
        } // end else


        return $list;
    }

    function get_add_participants_dialog($join_url) {
        $list = "";

        $list.="<div id='myModal' class='modal fade'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h4 class='modal-title'>Add participants</h4>
                </div>
                <div class='modal-body' style='text-align:center;'>
                
                <input type='hidden' id='join_url' value='$join_url'>
                
                <div class='container-fluid' style='text-align:left;'>
                <input type='text' id='participants' style='width:535px;' placeholder='Participant emails separated by comma'>
                </div><br>
                
                <div class='container-fluid' style='text-align:left;'>
                <textarea rows='5' style='width:535px;' id='invitation_text' placeholder='Invitation text'></textarea>
                </div>

                <div class='container-fluid' style=''>
                <span class='span6' style='color:red;' id='inv_err'></span>
                </div>
             
                <div class='modal-footer' style='text-align:center;'>
                    <span align='center'><button type='button' class='btn btn-primary' data-dismiss='modal' id='cancel'>Cancel</button></span>
                    <span align='center'><button type='button' class='btn btn-primary' id='add_meeting_participants'>OK</button></span>
                </div>
            </div>
        </div>
    </div>";

        return $list;
    }

    function send_meeting_invitation($inv) {
        $m = new Mailer();
        $recipients = explode(',', $inv->parts);
        $message = $inv->text;
        $message.="<p style='font-weight:bold;'>Meeting join url: $inv->join_url</p>";
        if (count($recipients) > 0) {
            foreach ($recipients as $recipient) {
                echo "Currenr recipient: " . $recipient . "<br>";
                $m->send_meeting_invitation($message, $recipient);
            } // end foreach
        } // end if count($recipients)>0
        $list = "Invitation(s) are sent";
        return $list;
    }

    function renew_user_certificate($certificate) {
        $certstr = $certificate->id . ",";
        $cert = new Certificates();
        $cert->recertificate($certstr, $certificate->date1, $certificate->date2);
    }

    function renew_user_certificate_manager($certificate) {
        $query = "select * from mdl_certificates where id=$certificate->id";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $userid = $row['userid'];
            $courseid = $row['courseid'];
            $expire = $row['expiration_date'];
        }

        $renew = new Renew();
        $renew_amount = $renew->get_renew_amount($courseid);
        $late_fee = $renew->get_renew_late_fee($courseid, $expire);

        $one_year_payment = $renew_amount + $late_fee;
        $two_year_payment = $renew_amount * 2 + $late_fee;
        $three_year_payment = $renew_amount * 3 + $late_fee;

        switch ($certificate->period) {
            case 1:
                $amount = $one_year_payment;
                break;
            case 2:
                $amount = $two_year_payment;
                break;
            case 3:
                $amount = $three_year_payment;
                break;
        }

        $outcert = array(
            'userid' => $userid,
            'courseid' => $courseid,
            'slot' => 0,
            'amount' => $amount,
            'period' => $certificate->period
        );

        return json_encode($outcert);
    }

    function renew_user_certificate_manager_cash($cert) {
        $c = json_decode($this->renew_user_certificate_manager($cert));
        $cert2 = new Certificates2();
        $date = time();
        $query = "insert into mdl_partial_payments "
                . "(userid,"
                . "courseid,"
                . "slotid,"
                . "ptype,"
                . "psum,"
                . "pdate) "
                . "values ($c->userid,"
                . "$c->courseid,"
                . "$c->slot,"
                . "$cert->ptype,"
                . "'$c->amount',"
                . "'$date')";
        $this->db->query($query);
        $cert2->renew_certificate($c->courseid, $c->userid, $c->period);
        $userObj = $this->get_user_details($c->userid);
        $userObj->courseid = $c->courseid;
        $userObj->userid = $c->userid;
        $userObj->slotid = $c->slot;
        $userObj->payment_amount = $c->amount;
        $userObj->period = $c->period;
        $mailer = new Mailer();
        $mailer->send_partial_payment_confirmation2($userObj);
    }

    // ******************* Code related to students survey ********************

    function is_course_completed($courseid, $userid) {
        $query = "select * from mdl_course_completions "
                . "where course=$courseid "
                . "and userid=$userid";
        $num = $this->db->numrows($query);
        return $num;
    }

    function is_ws_survey_was_completed($courseid, $userid) {
        $query = "select * from mdl_ws_survey "
                . "where courseid=$courseid "
                . "and userid=$userid "
                . "and viewed=1";
        $num = $this->db->numrows($query);
        return $num;
    }

    function get_attend_box() {
        $list = "";
        $list.="<select id='s_attend' style='width:220px;'>";
        $list.="<option value='--' selected>Please select</option>";
        $list.="<option value='0' >No</option>";
        $list.="<option value='1' >Yes</option>";
        $list.="</select>";

        return $list;
    }

    function get_brochure_box() {
        $list = "";
        $list.="<select id='s_brochure' style='width:220px;'>";
        $list.="<option value='--' selected>Please select</option>";
        $list.="<option value='0' >No</option>";
        $list.="<option value='1' >Yes</option>";
        $list.="</select>";

        return $list;
    }

    function get_register_box() {
        $list = "";
        $list.="<select id='s_register_type' style='width:220px;'>";
        $list.="<option value='--' selected>Please select</option>";
        $list.="<option value='0' >Online</option>";
        $list.="<option value='1' >By call</option>";
        $list.="</select>";

        return $list;
    }

    function get_recommend_box() {
        $list = "";
        $list.="<select id='s_recommend' style='width:220px;'>";
        $list.="<option value='--' selected>Please select</option>";
        $list.="<option value='0' >No</option>";
        $list.="<option value='1' >Yes</option>";
        $list.="</select>";

        return $list;
    }

    function get_rate_box($input_id) {
        $list = "";
        $list.="<select id='s_$input_id' style='width:220px;'>";
        $list.="<option value='--' selected>Please select</option>";
        if ($input_id != 'draw_blood') {
            $list.="<option value='1'>Excellent</option>";
            $list.="<option value='2'>Good</option>";
            $list.="<option value='3'>Needs improvement</option>";
        } // end if
        else {
            $list.="<option value='1'>1</option>";
            $list.="<option value='2'>2</option>";
            $list.="<option value='3'>3</option>";
            $list.="<option value='4'>More than 3</option>";
        } // end else
        $list.="</select>";

        return $list;
    }

    function get_workshop_survey($courseid, $userid) {
        $list = "";

        $attend = $this->get_attend_box();
        $register = $this->get_register_box();

        $list.="<input type='hidden' id='courseid' value='$courseid'>";

        $list.="<input type='hidden' id='userid' value='$userid'>";

        $list.="<div class='container-fluid' style='font-weight:bold;font-size:16px;'>";
        $list.="<span class='span9'>Please complete one time survey:</span>";
        $list.="</div>";

        $list.="<div class='container-fluid'>";
        $list.="<span class='span9'>Rate the following. Select your answers 1-Excellent, 2-Good, 3-Needs improvement, 4-Very disappointing</span>";
        $list.="</div>";

        $list.="<div class='container-fluid'>";
        $list.="<span class='span6'>What city was your workshop in?*</span>";
        $list.="<span class='span3'><input type='text' id='s_city'></span>";
        $list.="</div>";

        $rate_box = $this->get_rate_box('in_prof');
        $list.="<div class='container-fluid'>";
        $list.="<span class='span6'>Instructor’s professionalism and knowledge*</span>";
        $list.="<span class='span3'>$rate_box</span>";
        $list.="</div>";

        $rate_box = $this->get_rate_box('qu_answer');
        $list.="<div class='container-fluid'>";
        $list.="<span class='span6'>Questions were answered*</span>";
        $list.="<span class='span3'>$rate_box</span>";
        $list.="</div>";

        $rate_box = $this->get_rate_box('in_clear');
        $list.="<div class='container-fluid'>";
        $list.="<span class='span6'>Instructor was clear and understandable*</span>";
        $list.="<span class='span3'>$rate_box</span>";
        $list.="</div>";

        $rate_box = $this->get_rate_box('training_met');
        $list.="<div class='container-fluid'>";
        $list.="<span class='span6'>Training met my expectations*</span>";
        $list.="<span class='span3'>$rate_box</span>";
        $list.="</div>";

        $rate_box = $this->get_rate_box('draw_blood');
        $list.="<div class='container-fluid'>";
        $list.="<span class='span6'>How many times did you attempt to draw blood on another student?*</span>";
        $list.="<span class='span3'>$rate_box</span>";
        $list.="</div>";

        $rate_box = $this->get_brochure_box('brochure');
        $list.="<div class='container-fluid'>";
        $list.="<span class='span6'>Were you given a 4 page handout with questions to answer?*</span>";
        $list.="<span class='span3'>$rate_box</span>";
        $list.="</div>";

        $rate_box = $this->get_recommend_box();
        $list.="<div class='container-fluid'>";
        $list.="<span class='span6'>Would you recommend Medical 2 to friends, family, etc?*</span>";
        $list.="<span class='span3'>$rate_box</span>";
        $list.="</div>";

        $list.="<div class='container-fluid'>";
        $list.="<span class='span6'>What would have made the workshop more interesting? </span>";
        $list.="<span class='span3'><textarea id='s_more_interesting' style='width:206px;'></textarea></span>";
        $list.="</div>";

        $list.="<div class='container-fluid'>";
        $list.="<span class='span6'>How would you suggest Medical 2 improve the workshop? </span>";
        $list.="<span class='span3'><textarea id='s_improve' style='width:206px;'></textarea></span>";
        $list.="</div>";

        $list.="<div class='container-fluid'>";
        $list.="<span class='span6'>Any comments</span>";
        $list.="<span class='span3'><textarea id='s_comments' style='width:206px;'></textarea></span>";
        $list.="</div>";

        $list.="<div class='container-fluid'>";
        $list.="<span class='span9' id='survey_err' style='color:red;'></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span9' style='text-align:center;display:none;' id='ajax_loader'><img src='https://$this->host/assets/img/ajax.gif' /></span>";
        $list.="</div>";

        $list.="<div class='container-fluid'>";
        $list.="<span class='span6'><button id='submit_survey'>Submit</button></span>";
        $list.="<span class='span3'></span>";
        $list.="</div><br/><br/><br/>";

        return $list;
    }

    function get_workshop_courses() {
        $query = "select * from mdl_course "
                . "where category=$this->workshop_category";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $ids[] = $row['id'];
        }
        $list = implode(',', $ids);
        return $list;
    }

    function is_survey_exists($courseid, $userid) {
        $query = "select * from mdl_ws_survey "
                . "where courseid=$courseid and userid=$userid";
        $num = $this->db->numrows($query);
        return $num;
    }

    function get_workshop_completed_users() {
        $list = $this->get_workshop_courses();
        $query = "select * from mdl_course_completions where course in ($list)";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $user = new stdClass();
            foreach ($row as $key => $value) {
                $user->$key = $value;
            } // end foreach
            $users[] = $user;
        } // end while
        return $users;
    }

    function create_users_survey() {
        $i = 0;
        $users = $this->get_workshop_completed_users();
        foreach ($users as $user) {
            $status = $this->is_survey_exists($user->course, $user->userid);
            if ($status == 0) {
                $query = "insert into mdl_ws_survey "
                        . "(courseid,userid) "
                        . "values($user->course,$user->userid)";
                echo "Query: " . $query . "<br>";
                $this->db->query($query);
            } // end if
        } // end foreach
    }

    function get_ws_item_score($score) {
        switch ($score) {
            case "1":
                $item = "Excellent";
                break;

            case "2":
                $item = "Good";
                break;

            case "3":
                $item = "Needs improvement";
                break;
        }

        return $item;
    }

    function get_survey_message($survey) {
        $list = "";

        $user = $this->get_user_details($survey->userid);
        $coursename = $this->get_course_name($survey->courseid);

        $list.="<html>";
        $list.="<body>";
        $list.="<table>";

        $list.="<tr>";
        $list.="<td align='center' colspan='2' style='padding:15px;font-weight:bold;'>Workshop survey results</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td style='padding:15px;'>Student</td>";
        $list.="<td style='padding:15px;'>$user->firstname $user->lastname</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td style='padding:15px;'>Program</td>";
        $list.="<td style='padding:15px;'>$coursename</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td style='padding:15px;'>What city was your workshop?</td>";
        $list.="<td style='padding:15px;'>$survey->city</td>";
        $list.="</tr>";

        $item = $this->get_ws_item_score($survey->in_prof);
        $list.="<tr>";
        $list.="<td style='padding:15px;'>Instructor professionalism and knowledge</td>";
        $list.="<td style='padding:15px;'>$item</td>";
        $list.="</tr>";

        $item = $this->get_ws_item_score($survey->qu_answer);
        $list.="<tr>";
        $list.="<td style='padding:15px;'>Questions were answered</td>";
        $list.="<td style='padding:15px;'>$item</td>";
        $list.="</tr>";

        $item = $this->get_ws_item_score($survey->in_clear);
        $list.="<tr>";
        $list.="<td style='padding:15px;'>Instructor was clear and understandable</td>";
        $list.="<td style='padding:15px;'>$item</td>";
        $list.="</tr>";

        $item = $this->get_ws_item_score($survey->training_met);
        $list.="<tr>";
        $list.="<td style='padding:15px;'>Training met my expectations</td>";
        $list.="<td style='padding:15px;'>$item</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td style='padding:15px;'>How many times did you attempt to draw blood on another student?</td>";
        $list.="<td style='padding:15px;'>$survey->draw_blood</td>";
        $list.="</tr>";

        $item = ($survey->brochure == 1) ? 'Yes' : 'No';
        $list.="<tr>";
        $list.="<td style='padding:15px;'>Were you given a 4 page handout with questions to answer?</td>";
        $list.="<td style='padding:15px;'>$item</td>";
        $list.="</tr>";

        $item = ($survey->recommend == 1) ? 'Yes' : 'No';
        $list.="<tr>";
        $list.="<td style='padding:15px;'>Would you recommend Medical 2 to friends, family, etc?</td>";
        $list.="<td style='padding:15px;'>$item</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td style='padding:15px;'>What would have made the workshop more interesting?</td>";
        $list.="<td style='padding:15px;'>$survey->ws_more</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td style='padding:15px;'>How would you suggest Medical 2 improve the workshop?</td>";
        $list.="<td style='padding:15px;'>$survey->improve</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td style='padding:15px;'>Any comments</td>";
        $list.="<td style='padding:15px;'>$survey->comments</td>";
        $list.="</tr>";

        $list.="</table>";
        $list.="</body>";
        $list.="</html>";


        return $list;
    }

    function send_survey_results($survey) {

        //echo "<pre>";
        //print_r($survey);
        //echo "</pre><br>-----------------------------------------<br>";
        //die();

        $list = "";
        $now = time();
        $query = "update mdl_ws_survey "
                . "set  attend='$survey->attend', "
                . "city='$survey->city', "
                . "reg_exp='$survey->reg_exp', "
                . "reg_online='$survey->reg_online', "
                . "in_prof='$survey->in_prof', "
                . "in_know='$survey->in_know', "
                . "ws_content='$survey->ws_content', "
                . "ws_thro='$survey->ws_thro', "
                . "ws_pace='$survey->ws_pace', "
                . "hands_exp='$survey->hands_exp', "
                . "ws_use='$survey->ws_use', "
                . "qu_answer='$survey->qu_answer', "
                . "co_org='$survey->co_org', "
                . "in_org='$survey->in_org', "
                . "in_clear='$survey->in_clear', "
                . "training_met='$survey->training_met', "
                . "draw_blood='$survey->draw_blood', "
                . "brochure='$survey->brochure', "
                . "recommend='$survey->recommend', "
                . "ws_more='$survey->ws_more',"
                . "improve='$survey->improve', "
                . "comments='$survey->comments', "
                . "viewed=1, viewed_date='$now' "
                . " where courseid=$survey->courseid "
                . "and userid=$survey->userid";
        //echo "Query: " . $query . "<br>";
        //die();
        $this->db->query($query);

        $m = new Mailer();
        $message = $this->get_survey_message($survey);
        $m->send_survey_data($message);
        $list.="Thank you!";
        return $list;
    }

    function remove_from_ws($id) {
        $query = "delete from mdl_scheduler_appointment where id=$id ";
        $this->db->query($query);
    }

    function get_program_slots($coursename) {

        // Create clear set first to refresh input
        $none[0] = "";
        file_put_contents('/home/cnausa/public_html/lms/custom/utils/none.json', json_encode($none));

        $query = "select * from mdl_course where fullname='$coursename'";
        $coursenum = $this->db->numrows($query);
        if ($coursenum > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $courseid = $row['id'];
            }

            $query = "select * from mdl_scheduler where course=$courseid";
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $schedulerid = $row['id'];
            }

            $query = "select * from mdl_scheduler_slots "
                    . "where schedulerid=$schedulerid";
            $num = $this->db->numrows($query);
            if ($num > 0) {
                $result = $this->db->query($query);
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $location = mb_convert_encoding($row['appointmentlocation'], 'UTF-8');
                    $date = mb_convert_encoding(date('m-d-Y', trim($row['starttime'])), 'UTF-8');
                    $ws[] = $date . "--" . $location;
                }
                unlink('/home/cnausa/public_html/lms/custom/utils/slots.json');
                file_put_contents('/home/cnausa/public_html/lms/custom/utils/slots.json', json_encode($ws));
            } // end if $num > 0
            else {
                unlink('/home/cnausa/public_html/lms/custom/utils/slots.json');
            }
        } // end if $coursenum
        return $coursenum;
    }

    function is_user_has_survey_applicagtion($userid) {
        $query = "select * from mdl_ws_survey where userid=$userid";
        $num = $this->db->numrows($query);
        return $num;
    }

    function has_completion_status($courseid, $userid) {
        $query = "select * from mdl_course_completions "
                . "where course=$courseid and userid=$userid";
        $num = $this->db->numrows($query);
        return $num;
    }

    function make_user_passed($courseid, $userid) {
        $date = time();
        $query = "insert into mdl_course_completions "
                . "(userid,"
                . "course,"
                . "timeenrolled,"
                . "timecompleted) "
                . "values($userid,"
                . "$courseid,"
                . "'$date',"
                . "'$date')";
        $this->db->query($query);
    }

    function complete_users() {
        $cert_users = array();
        $query = "select * from mdl_certificates";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $item = new stdClass();
            foreach ($row as $key => $value) {
                $item->$key = $value;
            }
            $cert_users[] = $item;
        }

        $i = 0;
        foreach ($cert_users as $item) {
            $user = $this->get_user_details($item->userid);
            $st = $this->has_completion_status($item->courseid, $item->userid);
            if ($st > 0) {
                $status = 'Completed';
            } // end if $st
            else {
                $status = "Not completed";
                $this->make_user_passed($item->courseid, $item->userid);
                $i++;
            } // end else
            echo "User: $user->firstname $user->lastname has $status  ";
            echo "<br>----------------------------------------------------<br>";
        } // end foreach
        echo "Total non-completed users: $i";
    }

    // ***************** Skype Meeting Integration ************************

    function get_meeting_block($courseid, $roleid) {
        $list = "";
        $items = array();
        $now = time();
        $query = "select * from mdl_meeting where courseid=$courseid "
                . "and mdate>$now order by mdate";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $item = new stdClass();
                foreach ($row as $key => $value) {
                    $item->$key = $value;
                } // end foreach
                $items[] = $item;
            } // end while
        } // end if $num>0
        $list.=$this->create_meetings_block($items);
        return $list;
    }

    function createRandomString($length = 25) {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
    }

    function get_start_meeting_button($courseid) {
        $list = "";
        $roomid = $this->createRandomString();
        $userid = $this->user->id;
        $list.="<form id='start_meeting_$courseid' method='post' target='_blank' action='https://medical2.com/lms/custom/hangouts/meeting.php'>";
        $list.="<input type='hidden' name='courseid' value='$courseid'>";
        $list.="<input type='hidden' name='roomid' id='roomid' value='$roomid'>";
        $list.="<input type='hidden' name='userid' value='$userid'>";
        $list.="<button style='width:175px;' id='meeting_start' data-courseid='$courseid'>Start Meeting</button>";
        $list.="</form>";
        return $list;
    }

    function get_meeting_toolbar() {
        $list = "";
        $courseid = $this->course->id;
        $meeting_btn = $this->get_start_meeting_button($courseid);
        $list.="<div class='row-fluid' style='padding-left:12px;'>";
        $list.="<span class='span4'><button style='width:175px;' id='meeting_add' data-courseid='$courseid'>Add Webinar</button></span>";
        $list.="<span class='span4'>$meeting_btn</span>";
        $list.="</div>";
        return $list;
    }

    function get_join_button($roomid, $courseid) {
        $list = "";
        $userid = $this->user->id;
        $list.="<form id='join_webinar_$roomid' action='https://medical2.com/lms/custom/hangouts/meeting.php?roomid=$roomid' method='post' target='_blank'>";
        $list.="<input type='hidden' name='roomid' id='roomid' value='$roomid'>";
        $list.="<input type='hidden' name='userid' value='$userid'>";
        $list.="<input type='hidden' name='courseid' value='$courseid'>";
        $list.="<a href='#' onClick='return false;' id='wjoin_$roomid'>Join</a>";
        $list.="</form>";
        return $list;
    }

    function get_start_button($roomid, $courseid) {
        $list = "";
        $userid = $this->user->id;
        $list.="<form id='start_webinar_$id' action='https://medical2.com/lms/custom/hangouts/meeting.php' method='post' target='_blank'>";
        $list.="<input type='hidden' name='roomid' id='roomid' value='$roomid'>";
        $list.="<input type='hidden' name='userid' value='$userid'>";
        $list.="<input type='hidden' name='courseid' value='$courseid'>";
        $list.="<a href='#' onClick='return false;' id='wstart_$id'>Start</a>";
        $list.="</form>";
        return $list;
    }

    function create_meetings_block($items) {
        $list = "";
        $userid = $this->user->id;
        $courseid = $this->course->id;
        $contextid = $this->get_course_context($courseid);
        $roleid = $this->get_user_role($userid, $contextid);
        if ($roleid == '' || $roleid < 5) {
            $list.=$this->get_meeting_toolbar();
        } // end if

        if (count($items) > 0) {
            $list.="<div class='row-fluid' style='padding-left:12px;font-weight:bold;'>";
            $list.="<span class='span4'>Title</span>";
            $list.="<span class='span3'>Date</span>";
            $list.="<span class='span2' style='text-align:left;'>Ops</span>";
            $list.="</div>";
            foreach ($items as $item) {
                $date = date('m-d-Y', $item->mdate) . " " . $item->mh . ":" . $item->mm;
                $joinbtn = $this->get_join_button($item->id, $courseid);
                $list.="<div class='row-fluid' style='padding-left:12px;'>";
                $list.="<span class='span4'>$item->title</span>";
                $list.="<span class='span3'>$date</span>";
                if ($roleid == '' || $roleid < 5) {
                    $start_btn = $this->get_start_button($item->id, $courseid);
                    $list.="<span class='span1'><a href='#' onClick='return false;' id='wedit_$item->id'>Edit</a></span>";
                    $list.="<span class='span1'>$start_btn</span>";
                }
                $list.="<span class='span1'>$joinbtn</span>";
                $list.="</div>";
                $list.="<div class='row-fluid' style='padding-left:12px;'>";
                if ($roleid == '' || $roleid < 5) {
                    $list.="<span class='span9'><hr/></span>";
                } // end if
                else {
                    $list.="<span class='span8'><hr/></span>";
                }
                $list.="</div>";
            } // end foreach
        } // end if count($items)>0 
        else {
            $list.="<div clas='row-fluid' style='padding-left:12px;'>";
            $list.="<span class='span8'>There are no any meetings/webinars scheduled</span>";
            $list.="</div><br><br>";
        } // end else

        return $list;
    }

    function get_webinar_hours_block($selected) {
        $list = "";
        $list.="<select id='wh'>";
        for ($i = 0; $i <= 23; $i++) {
            $index = ($i < 10) ? '0' . $i : $i;
            if ($index == $selected) {
                $list.="<option value='$index' selected>$index</option>";
            } // end if
            else {
                $list.="<option value='$index'>$index</option>";
            }
        }
        $list.="</select>";
        return $list;
    }

    function get_webinar_minutes_block($selected) {
        $list = "";
        $list.="<select id='wm'>";
        for ($i = 0; $i <= 55; $i+=5) {
            $index = ($i < 10) ? '0' . $i : $i;
            if ($index == $selected) {
                $list.="<option value='$index' selected>$index</option>";
            } // end if
            else {
                $list.="<option value='$index'>$index</option>";
            }
        }
        $list.="</select>";
        return $list;
    }

    function get_add_webinar($courseid) {
        $list = "";
        $wh = $this->get_webinar_hours_block();
        $wm = $this->get_webinar_minutes_block();
        $list.="<div id='myModal' class='modal fade'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h4 class='modal-title'>Add webinar</h4>
                </div>
                <div class='modal-body' style='text-align:center;'>
                <input type='hidden' id='meeting_courseid' value='$courseid'>
                    
                <div class='container-fluid' style='text-align:left;'>
                <span class='span1'>Title:</span>
                <span class='span2'><input type='text' id='webinar_title'></span>
                </div>
                
                 <div class='container-fluid' style='text-align:left;'>
                 <span class='span1'>Date</span>
                 <span class='span2'><input type='text' id='webinar_date'></span>
                 </div>
                 
                 <div class='container-fluid' style='text-align:left;'>
                 <span class='span1'>Time</span>
                 <span class='span2'>$wh &nbsp; $wm</span>
                 </div>

                <div class='container-fluid' style=''>
                <span class='span3' style='color:red;' id='meeting_err'></span>
                </div>
             
                <div class='modal-footer' style='text-align:center;'>
                    <span align='center'><button type='button' class='btn btn-primary' data-dismiss='modal' id='cancel'>Cancel</button></span>
                    <span align='center'><button type='button' class='btn btn-primary' id='add_new_webinar_btn'>OK</button></span>
                </div>
            </div>
        </div>
    </div>";

        return $list;
    }

    function get_edit_webinar_dialog($id) {
        $list = "";
        $query = "select * from mdl_meeting where id=$id";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $item = new stdClass();
            foreach ($row as $key => $value) {
                $item->$key = $value;
            } // end foreach
        } // end while
        $wh = $this->get_webinar_hours_block($item->mh);
        $wm = $this->get_webinar_minutes_block($item->mm);
        $list.="<div id='myModal' class='modal fade'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h4 class='modal-title'>Add webinar</h4>
                </div>
                <div class='modal-body' style='text-align:center;'>
                <input type='hidden' id='meeting_courseid' value='$item->courseid'>
                <input type='hidden' id='meeting_id' value='$id'>    
                    
                <div class='container-fluid' style='text-align:left;'>
                <span class='span1'>Title:</span>
                <span class='span2'><input type='text' id='webinar_title' value='$item->title'></span>
                </div>
                
                 <div class='container-fluid' style='text-align:left;'>
                 <span class='span1'>Date</span>
                 <span class='span2'><input type='text' id='webinar_date' value='" . date('m/d/y', $item->mdate) . "'></span>
                 </div>
                 
                 <div class='container-fluid' style='text-align:left;'>
                 <span class='span1'>Time</span>
                 <span class='span2'>$wh &nbsp; $wm</span>
                 </div>

                <div class='container-fluid' style=''>
                <span class='span3' style='color:red;' id='meeting_err'></span>
                </div>
             
                <div class='modal-footer' style='text-align:center;'>
                    <span align='center'><button type='button' class='btn btn-primary' data-dismiss='modal' id='cancel'>Cancel</button></span>
                    <span align='center'><button type='button' class='btn btn-primary' id='update_webinar_btn'>OK</button></span>
                </div>
            </div>
        </div>
    </div>";

        return $list;
    }

    function add_new_webinar($w) {
        $date = strtotime($w->date);
        $query = "insert into mdl_meeting "
                . "(courseid,"
                . "title,"
                . "mdate,"
                . "mh,"
                . "mm) "
                . "values ($w->courseid,"
                . "'$w->title',"
                . "'$date',"
                . "'$w->hour',"
                . "'$w->min')";
        $this->db->query($query);
    }

    function update_webinar($w) {
        $date = strtotime($w->date);
        $query = "update mdl_meeting "
                . "set courseid=$w->courseid, "
                . "title='$w->title', "
                . "mdate='$date', "
                . "mh='$w->hour', "
                . "mm='$w->min' where id='$w->id'";
        echo "Query: " . $query . "<br>";
        $this->db->query($query);
    }

}
