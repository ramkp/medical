<?php

require_once ('/home/cnausa/public_html/lms/class.pdo.database.php');
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/my/classes/Dashboard.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/Payment.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/Mailer.php';
require_once ($_SERVER['DOCUMENT_ROOT'] . '/lms/custom/invoices/classes/Invoice.php');

class Partial extends Util {

    public $invoice;
    public $payment;
    public $db;
    public $limit = 3;

    function __construct() {
        parent::__construct();
        $this->payment = new Payment();
        $this->invoice = new Invoices();
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

    function get_renew_fee() {
        $query = "select * from mdl_renew_fee";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $fee = $row['fee_sum'];
        } // end while
        return $fee;
    }

    function get_partial_cc_payments() {
        $partials = array();
        $query = "select * from mdl_card_payments "
                . "where pdate>1464074847 order by pdate desc";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $renew_fee = $this->get_renew_fee();
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $course_cost_array = $this->payment->get_personal_course_cost($row['courseid']);
                $user_payment = $row['psum'];
                $course_cost = $course_cost_array['cost'];
                if ($user_payment < $course_cost && $user_payment != $renew_fee) {
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
        return $partials;
    }

    function get_partial_offline_payments() {
        $partials = array();
        $query = "select * from mdl_partial_payments order by pdate desc";
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
        return $partials;
    }

    function get_workshop_date($slotid) {
        $query = "select * from mdl_scheduler_slots where id=$slotid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $date = $row['starttime'];
        } // end while
        return $date;
    }

    function get_user_slot($courseid, $userid) {
        $slotid = 0;
        $query = "select * from mdl_slots "
                . "where courseid=$courseid and userid=$userid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $slotid = $row['slotid'];
            } // end while
        } // end if $num > 0
        return $slotid;
    }

    function get_partial_payments_list() {
        $list = "";

        if ($this->session->justloggedin == 1) {
            $partials = array();
            $cc_partials = $this->get_partial_cc_payments();
            $of_partials = $this->get_partial_offline_payments();
            $partials = array_merge($cc_partials, $of_partials);

            /*
              foreach ($partials_arr as $p) {
              $slotid=$this->get_user_slot($p->courseid, $p->userid);
              $wsdate=$this->get_workshop_date($slotid);
              $partials[$wsdate]=$p;
              }
              ksort($partials);
             */
            $list.=$this->create_partial_payments_list($partials);
        } // end if 
        else {
            $list.="<p>You are not authenticated. &nbsp; <a href='https://medical2.com/login'><button class='btn btn-primary' id='relogin'>Login</button></a></p>";
        } // end else

        return $list;
    }

    function is_past_schedule($slotid) {
        $now = time();
        $diff = 86400; // one day in sec
        $query = "select * from mdl_scheduler_slots where id=$slotid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $starttime = $row['starttime'];
            } // end while 
        } // end if $num > 0
        if (($starttime - $now) >= $diff) {
            return true;
        }  // end if ($starttime - $now) >= $diff        
        else {
            return false;
        }
    }

    function create_partial_payments_list($partials, $toolbar = true) {

        $list = "";
        if ($toolbar == true) {
            $add_payment_block = $this->get_add_partial_payment_page();

            $list.="<div class='container-fluid' style='text-align:center;'>";
            $list.="<span class='span2'>Search</span>";
            $list.="<span class='span2'><input type='text' id='search_partial' class='typehead' style='width:125px;' /></span>";
            $list.="<span class='span3'><button class='btn btn-primary' id='search_partial_button'>Search</button></span>";
            $list.="<span class='span2'><button class='btn btn-primary' id='clear_partial_button'>Clear filter</button></span>";
            $list.="</div>";

            $list.="<div class='container-fluid' style='display:none;text-align:center;' id='ajax_loader'>";
            $list.="<span class='span10'><img src='https://$this->host/assets/img/ajax.gif' /></span>";
            $list.="</div>";

            $list.="<br><div class='container-fluid' style='text-align:center;'>";
            $list.="<span class='span12' id='add_payment_container'>$add_payment_block</span>";
            $list.="</div>";

            $list.="<div class='container-fluid' style='text-align:center;'>";
            $list.="<span class='span12' id='partial_err'></span>";
            $list.="</div>";

            $list.="<div class='container-fluid' style='text-align:center;'>";
            $list.="<span class='span12'><hr></span>";
            $list.="</div>";
        } // end if $toolbar==true

        if (count($partials) > 0) {
            $list.="<div class='container-fluid' style='text-align:center;' id='partial_container'>";
            $list.="<div class='container-fluid' style='text-align:center;'>";
            $list.="<span class='span12' style='font-weight:bold;'>Total items: " . count($partials) . "</span>";
            $list.="</div>";

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

    function get_renew_period_dropbox() {
        $list = "";
        $list.="<select id='renew_period'>";
        $list.="<option value='0' selected>Renew period</option>";
        $list.="<option value='1'>One Year</option>";
        $list.="<option value='2'>Two Years</option>";
        $list.="<option value='3'>Three Years</option>";
        $list.="</select>";
        return $list;
    }

    function get_add_partial_payment_page() {
        $list = "";
        $ds = new Dashboard();
        $cats = $ds->get_course_categories();
        $courses = $ds->get_courses_by_category();
        $register_state = $ds->get_register_course_states_list();
        $cities = $ds->get_register_course_cities_list();

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span3'>$cats</span>";
        $list.="<span class='span3' id='cat_course'>$courses</span>";
        $list.="<span class='span3' id='register_states_container'>$register_state</span>";
        $list.="<span class='span3' id='register_cities_container'>$cities</span>";
        $list.="</div>";
        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span3'></span>";
        $list.="</div>";
        $list.="<div class='container-fluid' style='text-align:center;'>";
        $list.="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class='span8' id='enrolled_users'></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;display:block;' id='payment_options'>";

        $list.="<span class='span3'><input type='radio' name='payment_type' class='ptype' value='cc' checked>Card payment</span>";
        $period = $this->get_renew_period_dropbox();
        $enabled = $this->check_module_permission('cash');

        if ($this->user->id == 2) {
            // It is admin 
            $list.="<span class='span3'><input type='radio' name='payment_type' class='ptype' value='cash' >Cash payment</span>";
            $list.="<span class='span3'><input type='radio' name='payment_type' class='ptype' value='cheque' >Cheque payment</span>";
        }  // end if $this->user->id == 2
        else {
            if ($enabled == 1) {
                $list.="<span class='span3'><input type='radio' name='payment_type' class='ptype' value='cash' >Cash payment</span>";
                $list.="<span class='span3'><input type='radio' name='payment_type' class='ptype' value='cheque' >Cheque payment</span>";
            } // end if $enabled == 1
        } // end else

        $list.="<span class='span3'><input type='text' id='sum' style='width:120px;' /></span>";
        $list.="<span class='span6'><input type='checkbox' id='renew'> &nbsp; This is certificate renew payment</span>";
        $list.="<span class='span3'>$period</span>";
        $list.="<span class='span3'><button class='btn btn-primary' id='get_partial_payment_section'>Add</button></span>";

        $list.="</div>";

        /*
          $list.="<div class='container-fluid' style='text-align:left;'>";
          $list.="<span class='span6'><input type='checkbox' id='renew'> &nbsp; This is certificate renew payment</span>";
          $list.="<span class='span3'>$period</span>";
          $list.="<span class='span3'><button class='btn btn-primary' id='get_partial_payment_section'>Add</button></span>";
          $list.="</div>";
         */

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span12' id='payment_section'></span>";
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

    function add_payments_log($courseid, $userid, $sum, $payment_type) {
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

    function confirm_user($userid) {
        $query = "update mdl_user set confirmed=1 where id=$userid";
        $this->db->query($query);
    }

    function add_user_to_course_schedule($slotid, $userid) {
        if ($slotid > 0) {
            $query = "select * from mdl_scheduler_appointment "
                    . "where slotid=$slotid "
                    . "and studentid=$userid";
            $num = $this->db->numrows($query);
            if ($num == 0) {
                $query = "insert into mdl_scheduler_appointment "
                        . "(slotid,"
                        . "studentid,"
                        . "attended) values ($slotid,$userid,0)";
                //echo "Query: ".$query."<br>";
                $this->db->query($query);
            } // end if $num == 0
        } // end if $user->slotid>0
    }

    function update_slots_table($courseid, $userid, $slotid) {
        $query = "insert into mdl_slots "
                . "(slotid,"
                . "courseid,"
                . "userid) "
                . "values($slotid,"
                . "$courseid,"
                . "$userid)";
        $this->db->query($query);
    }

    function add_partial_payment($courseid, $userid, $sum, $source, $slotid, $period) {
        $date = time();
        $payment_type = 0; // cc                
        if ($source == 'cheque' || $source == 'cash') {
            //1 - cash
            //2 - cheque 
            $payment_type = ($source == 'cash') ? 1 : 2;
            $query = "insert into mdl_partial_payments "
                    . "(userid,"
                    . "courseid, slotid,"
                    . "ptype,"
                    . "psum,"
                    . "pdate) "
                    . "values($userid, "
                    . "$courseid, $slotid,"
                    . "$payment_type,"
                    . "'$sum',"
                    . "'$date')";
            //echo "Query: ".$query."<br>";
            $this->db->query($query);
            $this->confirm_user($userid);
            $this->update_slots_table($courseid, $userid, $slotid);
            $this->add_user_to_course_schedule($slotid, $userid);
        } // end if $source == 'add_cash' || $source == 'add_cheque'

        $userObj = $this->get_user_details($userid);
        $userObj->courseid = $courseid;
        $userObj->userid = $userid;
        $userObj->slotid = $slotid;
        $userObj->payment_amount = $sum;
        $userObj->period = $period;
        $mailer = new Mailer();
        $mailer->send_partial_payment_confirmation($userObj);

        $list = "Partial payment successfully added. Please reload the page";
        return $list;
    }

    function get_payment_section($courseid, $userid, $sum, $ptype, $slotid) {
        $list = "";

        if ($ptype == 'cash') {
            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span2'>Paid sum</span>";
            $list.="<span class='span2'><input type='text' id='sum' style='width:45px;' /></span>";
            $list.="<span class='span2'><button class='btn btn-primary' id='add_cash'>Add</span>";
            $list.="</div>";
        } // end if $ptype=='cash'

        if ($ptype == 'cheque') {
            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span2'>Paid sum</span>";
            $list.="<span class='span2'><input type='text' id='sum' style='width:45px;' /></span>";
            $list.="<span class='span2'><button class='btn btn-primary' id='add_cheque'>Add</button></span>";
            $list.="</div>";
        } // end if $ptype=='cheque'

        if ($ptype == 'cc') {
            $group_data = '';
            $participants = 1;
            $user_data = $this->get_user_details($userid);
            $user = new stdClass();
            $user->id = $userid;
            $user->courseid = $courseid;
            $user->sloid = $slotid;
            $user->state = $user_data->state;
            $list.=$this->payment->get_payment_section($group_data, $user, $participants, null, true);
        } // end if $ptype=='cc'

        return $list;
    }

    function get_user_classes($item) {
        $schedulers = array();
        $courses = array();
        $query = "select * from mdl_scheduler_slots where notes like '%$item%'";
        //echo "Query: ".$query."<br>";
        $num = $this->db->numrows($query);
        //echo "Slots num: ".$num."<br>";
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $schedulers[] = $row['schedulerid'];
            } // end while
            //echo "<br>----------Schedulers--------------------<br>";
            //print_r($schedulers);
            //echo "<br>";
            foreach ($schedulers as $scheduler) {
                $query = "select * from mdl_scheduler where id=$scheduler";
                //echo "Scheduler Query: ".$query."<br>";
                $result = $this->db->query($query);
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $courses[] = $row['course'];
                } // end while
            } // end foreach
        } // end if $num > 0
        return $courses;
    }

    function search_item($item) {
        $list = "";
        $partials = array();
        $users_array = array_unique($this->invoice->search_invoice_users($item));
        $courses_array = array_unique($this->invoice->search_invoice_courses($item));
        $users_list = implode(",", $users_array);
        $courses_list = implode(",", $courses_array);

        // 1. Card payments
        if ($users_list != '') {
            $query = "select * from mdl_card_payments "
                    . "where  userid in ($users_list) and pdate>1464074847 order by pdate desc ";
        } // end if $users_list != ''
        if ($courses_list != '') {
            $query = "select * from mdl_card_payments "
                    . "where courseid in ($courses_list) and pdate>1464074847 order by pdate desc ";
        } // end if $courses_list != ''
        if ($users_list != '' && $courses_list != '') {
            $query = "select * from mdl_card_payments "
                    . "where (courseid in ($courses_list) "
                    . "or userid in ($users_list)) and pdate>1464074847 order by pdate desc ";
        } // end if $users_list != '' && $courses_list != '' 
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $renew_fee = $this->get_renew_fee();
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $course_cost_array = $this->payment->get_personal_course_cost($row['courseid']);
                $course_cost = $course_cost_array['cost'];
                if ($row['psum'] < $course_cost and $row['psum'] != $renew_fee) {
                    $partial = new stdClass();
                    $partial->userid = $row['userid'];
                    $partial->courseid = $row['courseid'];
                    $partial->payment = $row['psum'];
                    $partial->cost = $course_cost;
                    $partial->pdate = $row['pdate'];
                    $partials[] = $partial;
                } // end if $row['psum']<$course_cost and $row['psum']!=$renew_fee
            } // end while            
        } // end if $num > 0
        // 2. Cash and cheque payments
        if ($users_list != '') {
            $query = "select * from mdl_partial_payments "
                    . "where  userid in ($users_list) and pdate>1464074847 order by pdate desc ";
        } // end if $users_list != ''
        if ($courses_list != '') {
            $query = "select * from mdl_partial_payments "
                    . "where courseid in ($courses_list) and pdate>1464074847 order by pdate desc ";
        } // end if $courses_list != ''
        if ($users_list != '' && $courses_list != '') {
            $query = "select * from mdl_partial_payments "
                    . "where (courseid in ($courses_list) "
                    . "or userid in ($users_list)) and pdate>1464074847 order by pdate desc ";
        } // end if $users_list != '' && $courses_list != ''        

        $num = $this->db->numrows($query);
        if ($num > 0) {
            $renew_fee = $this->get_renew_fee();
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $course_cost_array = $this->payment->get_personal_course_cost($row['courseid']);
                $course_cost = $course_cost_array['cost'];
                if ($row['psum'] < $course_cost and $row['psum'] != $renew_fee) {
                    $partial = new stdClass();
                    $partial->userid = $row['userid'];
                    $partial->courseid = $row['courseid'];
                    $partial->payment = $row['psum'];
                    $partial->cost = $course_cost;
                    $partial->pdate = $row['pdate'];
                    $partials[] = $partial;
                } // end if $row['psum']<$course_cost and $row['psum']!=$renew_fee
            } // end while            
        } // end if $num > 0

        if (count($partials) > 0) {
            $list.=$this->create_partial_payments_list($partials, FALSE);
        } // end if count($partials)>0        
        else {
            $list.="<div class='container-fluid' style='text-align:center;'>";
            $list.="<span class='span6'>No Partial payments found</span>";
            $list.="</div>";
        } // end else 
        return $list;
    }

}
