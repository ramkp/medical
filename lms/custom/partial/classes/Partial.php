<?php

require_once ('/home/cnausa/public_html/lms/class.pdo.database.php');
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/Payment.php';

class Partial extends Util {

    public $payment;
    public $db;
    public $limit = 3;

    function __construct() {
        parent::__construct();
        $this->payment = new Payment();
        $this->db = new pdo_db();
    }

    function get_partial_payments_total() {

        $query = "select * from mdl_card_payments "
                . "where pdate>1464074847 order by pdate desc";

        $counter = 0;
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $course_cost_array = $this->payment->get_personal_course_cost($row['courseid']);
                $user_payment = $row['psum'];
                $course_cost = $course_cost_array['cost'];
                if ($user_payment < $course_cost) {
                    $counter++;
                } // end if $user_payment!=$course_cost
            } // end while
        } // end if $num>0
        //echo "Couner: ".$counter."<br>";
        return $counter;
    }

    function get_partial_payments_list() {
        $list = "";
        $partials = array();
        $query = "select * from mdl_card_payments "
                . "where pdate>1464074847 order by pdate desc limit 0, $this->limit";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $course_cost_array = $this->payment->get_personal_course_cost($row['courseid']);
                $user_payment = $row['psum'];
                $course_cost = $course_cost_array['cost'];
                if ($user_payment < $course_cost) {
                    $partial = new stdClass();
                    $partial->userid = $row['userid'];
                    $partial->courseid = $row['courseid'];
                    $partial->payment = $row['psum'];
                    $partial->cost = $course_cost;
                    $partial->pdate = $row['pdate'];
                    $partials[] = $partial;
                } // end if $user_payment!=$course_cost
            } // end while
        } // end if $num > 0        
        $list.=$this->create_partial_payments_list($partials);
        return $list;
    }

    function create_partial_payments_list($partials, $toolbar = true, $search = false) {

        date_default_timezone_set('Pacific/Wallis');
        $list = "";
        if ($toolbar == true) {
            $list.="<br><div class='container-fluid' style='text-align:center;'>";
            $list.="<span class='span10'><a href='#' onClick='return false;' id='add_partial'>Add partial payment</a></span>";
            $list.="</div>";
            $add_payment_block = $this->get_add_partial_payment_page();
            $list.="<br><div class='container-fluid' style='text-align:center;'>";
            $list.="<span class='span10' id='add_payment_container' style='display:none;'>$add_payment_block</span>";
            $list.="</div>";
        } // end if $toolbar==true

        if (count($partials) > 0) {
            $list.="<div class='container-fluid' style='text-align:center;' id='partial_container'>";
            foreach ($partials as $partial) {
                $user_data = $this->get_user_details($partial->userid);
                $coursename = $this->get_course_name($partial->courseid);
                $date_h = date('m-d-Y', $partial->pdate);
                $list.="<div class='container-fluid' style='text-align:left;'>";
                $list.="<span class='span2'>User</span>";
                $list.="<span class='span2'><a href='https://" . $_SERVER['SERVER_NAME'] . "/lms/user/profile.php?id=$partial->userid' target='_blank'>$user_data->firstname $user_data->lastname</a></span>";
                $list.="</div>";

                $list.="<div class='container-fluid' style='text-align:left;'>";
                $list.="<span class='span2'>Applied program</span>";
                $list.="<span class='span4'>$coursename</span>";
                $list.="</div>";

                $list.="<div class='container-fluid' style='text-align:left;'>";
                $list.="<span class='span2'>Program fee</span>";
                $list.="<span class='span2'>$$partial->cost</span>";
                $list.="</div>";

                $list.="<div class='container-fluid' style='text-align:left;'>";
                $list.="<span class='span2'>User paid</span>";
                $list.="<span class='span2'>$$partial->payment</span>";
                $list.="</div>";

                $list.="<div class='container-fluid' style='text-align:left;'>";
                $list.="<span class='span2'>Payment date</span>";
                $list.="<span class='span2'>$date_h</span>";
                $list.="</div>";

                $list.="<div class='container-fluid' style='text-align:left;'>";
                $list.="<span class='span8'><hr/></span>";
                $list.="</div>";
            } // end foreach
            $list.="</div>";
            if ($toolbar == true) {
                $list.="<div class='container-fluid'>";
                $list.="<span class='span9'  id='pagination'></span>";
                $list.="</div>";
            } // end if $toolbar==true
        } // end if count($partials) > 0
        else {
            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span6'>There are no partial payments in the system</span>";
            $list.="</div>";
        }
        return $list;
    }

    function get_add_partial_payment_page() {
        $list = "";
        $program_types = $this->get_course_categories();
        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span8'>$program_types</span>";
        $list.="</div>";
        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span8' id='category_courses'></span>";
        $list.="</div>";
        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span8' id='enrolled_users'></span>";
        $list.="</div>";
        $list.="<div class='container-fluid' style='text-align:left;display:none;' id='payment_options'>";
        $list.="<span class='span3'><input type='radio' name='payment_type' value='cc' checked>Card payment</span>";
        $list.="<span class='span3'><input type='radio' name='payment_type' value='cash' >Cash payment</span>";
        $list.="<span class='span3'><input type='radio' name='payment_type' value='cheque' >Cheque payment</span>";
        $list.="<span class='span3'><a href='#' id='get_partial_payment_section' onClick='return false;'>Proceed</a></span>";
        $list.="</div>";
        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span8' id='payment_section'></span>";
        $list.="</div>";
        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span8' id='partial_err' style='color:red;'></span>";
        $list.="</div>";
        //$list.="<div class='container-fluid' style='text-align:left;'>";
        //$list.="<span class='span2'></span><span class='span2'><button type='button' id='add_payment' class='btn btn-primary'>Add</button></span>";
        //$list.="</div>";
        return $list;
    }

    function get_partial_payment_item($page) {
        $partials = array();
        $rec_limit = $this->limit;
        if ($page == 1) {
            $offset = 0;
        } // end if $page==1
        else {
            $page = $page - 1;
            $offset = $rec_limit * $page;
        }
        $query = "select * from mdl_card_payments where pdate>1464074847 "
                . "order by pdate desc LIMIT $offset, $rec_limit";
        //echo "Query: ".$query."<br>";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $course_cost_array = $this->payment->get_personal_course_cost($row['courseid']);
            $user_payment = $row['psum'];
            $course_cost = $course_cost_array['cost'];
            if ($user_payment < $course_cost) {
                $partial = new stdClass();
                foreach ($row as $key => $value) {
                    $partial->$key = $value;
                } // end foreach
                $partials[] = $partial;
            } // end if $user_payment<$course_cost
        } // end while
        $list = $this->create_partial_payments_list($partials, false, false);
        return $list;
    }

    function add_payments_log($courseid, $userid, $sum) {
        $payment_type = 1;
        $modifierid = $this->user->id;
        $date = time();
        $query = "insert into mdl_payments_log "
                . "(userid,"
                . "courseid,"
                . "modifierid,"
                . "sum,"
                . "payment_type,"
                . "date_added) "
                . "values "
                . "($userid,"
                . "$courseid,"
                . "$modifierid,"
                . "'" . $sum . "',"
                . "$payment_type,"
                . "'" . $date . "')";
        //echo "Query: " . $query . "<br>";
        $this->db->query($query);
    }

    function add_partial_payment($courseid, $userid, $sum) {
        $date = time();
        $query3 = "insert into mdl_card_payments "
                . "(userid,"
                . "courseid,"
                . "psum,"
                . "trans_id,"
                . "auth_code,"
                . "pdate) "
                . "values($userid,"
                . "$courseid, "
                . "'$sum',"
                . "'partial',"
                . "'1'"
                . ",$date)";
        //echo "Query: " . $query3 . "<br>";
        $this->db->query($query3);
        $this->add_payments_log($courseid, $userid, $sum);
        $list = "Partial payment successfully added. Please reload the page";
        return $list;
    }

    function get_payment_section($ptype) {
        $list = "";
        $list.="Payment type: " . $ptype . "<br>";
        return $list;
    }

}
