<?php

/**
 * Description of Report
 *
 * @author sirromas
 */
ini_set('memory_limit', '1024M'); // or you could use 1G
require_once ($_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php');

class Report extends Util {

    public $card_sum = 0;
    public $cash_sum = 0;
    public $cheque_sum = 0;
    public $refund_sum = 0;
    public $invoice_sum = 0;
    public $program_sum = 0;
    public $cert_path;
    public $courseid;
    public $from;
    public $to;
    public $files_path;
    public $card_report_csv_file;
    public $cash_report_scv_file;
    public $cheque_report_csv_file;
    public $refund_report_csv_file;
    public $invoice_report_csv_file;
    public $full_refund_sum;
    public $partial_refund_sum;

    function __construct() {
        parent::__construct();
        $this->cert_path = $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/certificates';
        $this->files_path = $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/reports/files';
        $this->card_report_csv_file = 'card_payments.csv';
        $this->cash_report_scv_file = 'cash_payments_csv';
        $this->cheque_report_csv_file = 'cheque_payment.csv';
        $this->refund_report_csv_file = 'refund_payments.csv';
        $this->invoice_report_csv_file = 'invoice_payments.csv';
    }

    /*     * *********************************** Service functions ******************************** */

    function get_courses_list() {
        $list = "";
        $items = array();
        $query = "select id, fullname from mdl_course where cost>0";
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
            $list.="<select id='courses' style='width:175px;'>";
            $list.="<option value='0' selected>Program</option>";
            foreach ($items as $item) {
                $list.="<option value='$item->id'>$item->fullname</option>";
            } // end foreach
            $list.="</select>";
        } // end if $num>0
        return $list;
    }

    function get_states_list() {
        $list = "";
        $states = array();
        $query = "select * from mdl_states order by state";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $state = new stdClass();
            foreach ($row as $key => $value) {
                $state->$key = $value;
            } // end foreach
            $states[] = $state;
        } // end while
        $list.="<select id='states' style='width:100px;'>";
        $list.="<option value='0' selected>All states</option>";
        foreach ($states as $state) {
            $list.="<option value='$state->id'>$state->state</option>";
        } // end foreach
        $list.="</select>";
        return $list;
    }

    function get_workshops_list() {
        $list = "";
        $workshops = array();
        $query = "select * from mdl_course where category=2 and cost>0";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $workshop = new stdClass();
                foreach ($row as $key => $value) {
                    $workshop->$key = $value;
                } // end foreach
                $workshops[] = $workshop;
            } // end while
            $list.="<select id='workshops' style='width:175px;'>";
            $list.="<option value='0' selected>Workshop</option>";
            foreach ($workshops as $workshop) {
                $list.="<option value='$workshop->id'>$workshop->fullname</option>";
            }
            $list.="</select>";
        } // end if $num > 0
        return $list;
    }

    function get_course_category($courseid) {
        $query = "select category from mdl_course where id=$courseid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $cat = $row['category'];
        }
        return $cat;
    }

    function get_workshop_by_state($id) {
        $list = "";
        //echo "State id: ".$id."<br>";
        $state_courses = array();
        $state_workshops = array();
        if ($id > 0) {
            $query = "select * from mdl_course_to_state where stateid=$id";
        } // end if $id>0
        else {
            $query = "select * from mdl_course_to_state";
        }
        $num = $this->db->numrows($query);
        // echo "State num: ".$num."<br>";
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $state_courses[] = $row['courseid'];
            } // end while
            foreach ($state_courses as $courseid) {
                $cat = $this->get_course_category($courseid);
                if ($cat == 2) {
                    $state_workshops[] = $courseid;
                } // end if $cat==2
            } // end foreach
            // echo "Total workshops: ".count($state_workshops)."<br>";
            if (count($state_workshops) > 0) {
                $list.="<select id='workshops' style='width:175px;'>";
                $list.="<option value='0' selected>Workshop</option>";
                foreach ($state_workshops as $workshop) {
                    $workshop_name = $this->get_course_name($workshop);
                    $list.="<option value='$workshop'>$workshop_name</option>";
                } // end foreach
                $list.="</select>";
            } // end if $state_workshops)>0
            else {
                $list.="n/a";
            }
        } // end if $num > 0
        else {
            $list.="n/a";
        }
        return $list;
    }

    function get_user_certification_data($courseid, $userid) {
        $query = "select * from mdl_certificates where courseid=$courseid and userid=$userid";
        $num = $this->db->numrows($query);
        $cert = new stdClass();
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $cert->certified = 1;
                $cert->no = str_replace($this->cert_path, "", $row['path']);
                $cert->issue_date = date('d/m/Y', $row['issue_date']);
                $cert->exp_date = date('d/m/Y', $row['expiration_date']);
                $cert->payment_status = $this->get_user_payment_status($courseid, $userid);
            } // end while
        } // end if $num > 0
        else {
            $cert->certified = 0;
            $cert->no = 'n/a';
            $cert->issue_date = 'n/a';
            $cert->exp_date = 'n/a';
            $cert->payment_status = $this->get_user_payment_status($courseid, $userid);
        } // end else
        return $cert;
    }

    function get_user_balance($courseid, $userid) {
        $list = "";
        $cert = $this->get_user_certification_data($courseid, $userid);
        $cert_status = ($cert->certified == 0) ? 'User is not certified' : 'User is certified';
        $query = "select * from mdl_user_balance where courseid=$courseid and userid=$userid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                if ($cert->certified == 1) {
                    $expiration_status = ($cert->exp_date == null) ? "n/a" : $cert->exp_date;
                } // end if $cert->certified==1
                else {
                    $expiration_status = "n/a";
                } // end else
                $balance_status = ($row['balance_sum'] == null) ? "n/a" : $row['balance_sum'];
            } // end while
            $query = "update mdl_user_balance
			set is_certified=$cert->certified 
			where courseid=$courseid and userid=$userid";
            $this->db->query($query);
        } // end if $num > 0
        else {
            // Set User balance
            $expiration_status = "n/a";
            $balance_status = "n/a";
            $query = "insert into mdl_user_balance
				(courseid,
				 userid,
				 is_certified,
				 cert_no,
				 cert_exp,
				 balance_sum) values
				  ('" . $courseid . "',
				  '" . $userid . "',
				  '" . $cert->certified . "',
				  '" . $cert->no . "',
				  '" . $cert->exp_date . "',
				   'n/a')";
            //echo "Query: ".$query."<br>";
            $this->db->query($query);
        }
        if ($cert->certified == 1) {
            $list.="$cert_status<br>Expiration date $expiration_status<br>Balance: $$balance_status";
        } else {
            $list.="$cert_status<br>Balance: $$balance_status";
        }
        return $list;
    }

    /*     * *********************************** Revenue report  ********************************** */

    function get_revenue_report() {
        $list = "";
        if ($this->session->justloggedin == 1) {
            $courses = $this->get_courses_list();
            $list.="<div class='container-fluid'>";
            $list.="<span class='span6' id='revenue_report_err'></span>";
            $list.="</div>";
            $list.="<div class='container-fluid'>";
            $list.="<span class='span3'>$courses</span><span class='span1'>From</span><span class='span2'><input type='text' id='datepicker1' style='width:75px;'></span><span class='span1'>To</span><span class='span2'><input type='text' id='datepicker2' style='width:75px;'></span><span class='span1'><button type='button' id='rev_go' class='btn btn-primary'>Go</button></span>";
            $list.="</div>";
            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span8' style='text-align:center;display:none;' id='ajax_loading'><img src='http://$this->host/assets/img/ajax.gif' /></span>";
            $list.="</div>";
            $list.="<div id='revenue_report_container'>";
            $list.="</div>";
        } // end if 
        else {
            $list.="<p>You are not authenticated. &nbsp; <a href='https://medical2.com/login'><button class='btn btn-primary' id='relogin'>Login</button></a></p>";
        }
        return $list;
    }

    function create_csv_file($filename, $payments) {
        // Write CSV data
        $path = $this->files_path . '/' . $filename;

        $output = fopen($path, 'w');
        fputcsv($output, array('User', 'Program applied', 'Payment', 'Date'));
        foreach ($payments as $payment) {
            $date = date('m-d-Y', $payment->pdate);
            $coursename = $this->get_course_name($payment->courseid);
            $userdata = $this->get_user_details($payment->userid);
            fputcsv($output, array("$userdata->firstname $userdata->lastname", $coursename, $payment->psum, $date));
        }
        fclose($output);
    }

    function get_revenue_report_data($courseid, $from, $to, $status = true, $output = true) {


        $this->courseid = $courseid;
        $this->from = $from;
        $this->to = $to;
        $list = "";

        if ($from == $to) {
            $timestamp = time();
            $unix_from = strtotime("midnight", $timestamp);
            $unix_to = strtotime("tomorrow", $unix_from) - 1;
            //$unix_to = $unix_from + 86400;
        } // end if $from==$to
        else {
            $unix_from = strtotime($from);
            $unix_to = strtotime($to);
        } // end else

        $coursename = $this->get_course_name($courseid);
        if ($status == true) {
            $list.="<div class='container-fluid' style='font-weight:bold'>";
            $list.="<span class='span9'>$coursename - $from - $to</span>";
            $list.="</div>";
        }

        // 2.1 Get refund payments
        if ($courseid > 0) {
            $query = "select * from mdl_card_payments "
                    . "where courseid=$courseid and refunded=1 "
                    . "and pdate between $unix_from and $unix_to "
                    . "order by pdate desc ";
        } // end if $courseid>0
        else {
            $query = "select * from mdl_card_payments "
                    . "where pdate between $unix_from and $unix_to "
                    . "and refunded=1  order by pdate desc ";
        } // end else
        //echo "Query: ".$query."<br>";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $user_status = $this->is_user_deleted($row['userid']);
                if ($user_status == 0) {
                    $this->full_refund_sum = $this->full_refund_sum + $row['psum'];
                } // end if $user_status==0
            } // end while
        } // end if $num > 0

        $payments = $this->get_period_payments($courseid, $unix_from, $unix_to);
        $card_payments_detailes = $this->get_card_payments_detailes($courseid, $from, $to);
        $cash_payments_detailes = $this->get_other_payment_report_data($courseid, $from, $to, 1);
        $cheque_payments_detailes = $this->get_other_payment_report_data($courseid, $from, $to, 2);
        $refund_payment_detailes = $this->get_refund_payments_detailes($courseid, $from, $to);
        $invoice_data_details = $this->get_invoice_payments_detailes($courseid, $from, $to);
        $grand_total = $payments->card + $payments->cash + $payments->cheque + $payments->invoice;
        $list.="<div class='container-fluid'>";
        $list.="<div class='span12'>

                    <ul class='nav nav-tabs '>

                        <li class='active'><a href='#option1' data-toggle='tab'>Card payments</a></li>

                        <li><a href='#option2' data-toggle='tab'>Cash payments</a></li>

                        <li><a href='#option3' data-toggle='tab'>Cheque payments</a></li>
                        
                        <li><a href='#option5' data-toggle='tab'>Invoice payments</a></li>
                        
                        <li><a href='#option4' data-toggle='tab'>Refund payments</a></li>
                        
                        <li><a href='#option6' data-toggle='tab'>Statistics</a></li>

                    </ul>                    

                    <div class='tab-content'>

                        <div class='tab-pane active' id='option1'>

                            <h3>Card payments - $$payments->card - <a href='http://" . $_SERVER['SERVER_NAME'] . "/lms/custom/reports/files/" . $this->card_report_csv_file . "' target='_blank'>Export to CSV</a></h3>
							<h5>Grand total (card payments, cash and cheque payments) - $$grand_total</h5>
                            <p>$card_payments_detailes</p>

                        </div>                    

                        <div class='tab-pane' id='option2'>

                            <h3>Cash payments - $$payments->cash - <a href='http://" . $_SERVER['SERVER_NAME'] . "/lms/custom/reports/files/" . $this->cash_report_scv_file . "' target='_blank'>Export to CSV</a></h3>
                            
                            <p>$cash_payments_detailes</p>

                        </div>              

                        <div class='tab-pane' id='option3'>

                            <h3>Cheque payments - $$payments->cheque - <a href='http://" . $_SERVER['SERVER_NAME'] . "/lms/custom/reports/files/" . $this->cheque_report_csv_file . "' target='_blank'>Export to CSV</a></h3>
                            <p>$cheque_payments_detailes</p> 

                        </div>
                        
			<div class='tab-pane' id='option5'>

                            <h3>Invoice payments - $$payments->invoice - <a href='http://" . $_SERVER['SERVER_NAME'] . "/lms/custom/reports/files/" . $this->invoice_report_csv_file . "' target='_blank'>Export to CSV</a></h3>
                            <p>$invoice_data_details</p> 

                        </div>
                        
                        
			  <div class='tab-pane' id='option4'>

                            <h3>Refund payments - $$this->full_refund_sum - <a href='http://" . $_SERVER['SERVER_NAME'] . "/lms/custom/reports/files/" . $this->refund_report_csv_file . "' target='_blank'>Export to CSV</a></h3>
                            <p>$refund_payment_detailes</p> 

                        </div>
                        
                        <div class='tab-pane' id='option6'>

                            <h3>Statistics</h3>
                           
                            <div class='row' style='padding-left:45px;'>
                            
                            <span class='span2'><input type='radio' name='interval' id='byyear'>By year</span>
                            <span class='span2'><input type='radio' name='interval' id='bymonth'>By month</span>
                            <span class='span2'><input type='radio' name='interval' id='byweek'>By week</span>
                            
                            <span class='span2'><input type='text' id='stat_start' style='width:95px;'></span>
                            
                            <span class='span2'><input type='text' id='stat_end' style='width:95px;'></span>

                            <span class='span1'><button type='button' id='get_stat' class='btn btn-primary'>Go</button></span>

                            </div>
                            
                            <div class='row' id='stat_data' style='margin-left:35px;'>
                            
                            </div>
                            
                            <div class='row' id='stat_err' style='margin-left:35px;color:red;'>
                                
                            </div>
                            
                            
                            <div class='row' id='stat_ajax_loader' style='margin-left:35px;text-align:center;display:none;'>
                                <img src='https://$this->host/assets/img/ajax.gif' />
                            </div>
                            
                </div>
                </div>
            </div>";

        $list.="</table>";
        if ($output == true) {
            return $list;
        }
    }

    function get_revenue_payments_stats() {
        $payments = array();
        $card_payments = new stdClass();
        $card_payments->src = 'Card payments';
        $card_payments->counter = $this->card_sum;
        $payments[] = $card_payments;
        $cash_payments = new stdClass();
        $cash_payments->src = 'Cash payments';
        $cash_payments->counter = $this->cash_sum;
        $payments[] = $cash_payments;
        $cheque_payments = new stdClass();
        $cheque_payments->src = 'Cheque payments';
        $cheque_payments->counter = $this->cheque_sum;
        $payments[] = $cheque_payments;
        return $payments;
    }

    /*     * *********************************** Program report  ********************************** */

    function get_program_report() {
        $list = "";
        $courses = $this->get_courses_list();
        $list.="<div class='container-fluid'>";
        $list.="<span class='span6' id='program_report_err'></span>";
        $list.="</div>";
        $list.="<div class='container-fluid'>";
        $list.="<span class='span3'>$courses</span><span class='span1'>From</span><span class='span2'><input type='text' id='datepicker1' style='width:75px;'></span><span class='span1'>To</span><span class='span2'><input type='text' id='datepicker2' style='width:75px;'></span><span class='span1'><button type='button' id='program_go' class='btn btn-primary'>Go</button></span>";
        $list.="</div>";
        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span8' style='text-align:center;display:none;' id='ajax_loading'><img src='https://$this->host/assets/img/ajax.gif' /></span>";
        $list.="</div>";
        $list.="<div id='program_report_container'>";
        $list.="</div>";
        return $list;
    }

    function payment_types($typeid) {
        $query = "select * from mdl_payments_type where id=$typeid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $type = $row['type'];
        }
        return $type;
    }

    function get_user_payment_status($courseid, $userid) {
        $status = "User has free access";
        //1. Check card payments
        $query = "select * from mdl_card_payments
			where userid=$userid and courseid=$courseid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $date = date('d/m/Y', $row['pdate']);
                $status = "Payment using card $" . $row['psum'] . " <br>from $date";
                //$this->program_sum = $this->program_sum + $row['psum'];
            } // end while
        } // end if $num > 0
        else {
            //2. Check invoice payments
            $query = "select * from mdl_invoice
			where courseid=$courseid 
			and userid=$userid 
			and i_status=1";
            $num = $this->db->numrows($query);
            if ($num > 0) {
                $result = $this->db->query($query);
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $ptype = $this->payment_types($row['i_ptype']);
                    $date = date('d/m/Y', $row['i_pdate']);
                    $status = "Payment using $ptype $" . $row['i_sum'] . "<br> from $date";
                    //$this->program_sum = $this->program_sum + $row['i_sum'];
                } // end while
            } // end if $num > 0
        } // end else
        return $status;
    }

    function get_program_user_data($courseid, $userid) {
        $query = "select confirmed,firstname,lastname,email,phone1,timecreated "
                . "from mdl_user where id=$userid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $user = new stdClass();
            foreach ($row as $key => $value) {
                $user->$key = $value;
            } // end foreach
            if ($row['confirmed'] == 1) {
                $payment_status = $this->get_user_payment_status($courseid, $userid);
                $user->payment_status = $payment_status;

                $balance = $this->get_user_balance($courseid, $userid);
                $user->balance = $balance;
            } // end if $row['confirmed']==1
            else {
                $user->payment_status = 'User does not have access';
                $user->balance = 'n/a';
            }
        } // end while
        return $user;
    }

    function get_user_signup_date($userid) {
        //echo "User id: ".$userid."<br>";
        $query = "select timecreated from mdl_user where id=$userid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $signup_date = $row['timecreated'];
        }
        return $signup_date;
    }

    function get_program_payments($courseid, $from, $to) {
        $this->get_revenue_report_data($courseid, $from, $to, false, false);
        $this->program_sum = $this->card_sum + $this->cash_sum + $this->cheque_sum;
    }

    function is_user_has_card_payments($from, $to, $userid) {

        $query = "select * from mdl_card_payments "
                . "where userid=$userid "
                . "and pdate>=$from and pdate<=$to";
        $num = $this->db->numrows($query);
        return $num;
    }

    function is_user_has_invoice_payments($from, $to, $userid) {

        $query = "select * from mdl_invoice where "
                . "userid=$userid "
                . "and i_status=1 "
                . "and i_pdate>=$from "
                . "and i_pdate<=$to";
        $num = $this->db->numrows($query);
        return $num;
    }

    function get_program_report_data($courseid, $from, $to, $status = true, $output = true) {
        $this->courseid = $courseid;
        $this->from = $from;
        $this->to = $to;
        $program_users = array();
        $coursename = $this->get_course_name($courseid);
        if ($status == true) {
            $list.="<div class='container-fluid' style='font-weight:bold'>";
            $list.="<span class='span9'>$coursename - $from - $to</span>";
            $list.="</div>";
        }
        $users = $this->get_course_users($courseid, false);
        if (count($users) > 0) {
            $this->get_program_payments($courseid, $from, $to);
            foreach ($users as $user) {
                $user_invoice_status = $this->is_user_has_invoice_payments(strtotime($from), strtotime($to), $user->userid);
                $user_card_status = $this->is_user_has_card_payments(strtotime($from), strtotime($to), $user->userid);
                if ($user_invoice_status > 0 || $user_card_status > 0) {
                    $user_data = $this->get_program_user_data($courseid, $user->userid);
                    $program_users[] = $user_data;
                } // end if $signup_date>=strtotime($from)
            } // end foreach
        } // end if count(users)>0
        if ($output == true) {
            $list = $this->create_program_users_block($program_users);
            return $list;
        } else {
            return $program_users;
        }
    }

    function create_program_users_block($users) {
        $list = "";
        $sum = 0;
        if (count($users) > 0) {
            // Create report file
            $path = $this->files_path . '/program_report_data.csv';
            $output = fopen($path, 'w');
            fputcsv($output, array('User credentials', 'Payment status', 'Signup date'));
            // GUI block
            $list.="<div class='container-fluid' style='font-weight:bold;'>";
            $list.="<span class='span4'>User credentials</span><span class='span4'>Payment status</span><span class='2'>Signup date</p>";
            $list.="</div>";
            $list.="<div class='container-fluid'>";
            $list.="<span class='span10'><hr/></span>";
            $list.="</div>";
            foreach ($users as $user) {
                $date = date('m/d/Y', $user->timecreated);
                if ($user->firstname != '' && $user->lastname != '') {
                    $list.="<div class='container-fluid'>";
                    $list.="<span class='span4'>$user->firstname $user->lastname $user->email</span><span class='span4'>$user->payment_status</span><span class='2'>$date</span>";
                    $list.="</div>";
                    $list.="<div class='container-fluid'>";
                    $list.="<span class='span10'><hr/></span>";
                    $list.="</div>";
                    $date = date('d/m/Y', $user->timecreated);
                    // Put CSV data into file
                    fputcsv($output, array("$user->firstname $user->lasname $user->email", "$user->payment_status", "$date"));
                } // end if $user->firstname!='' && $user->lastname!=''
            } // end foreach
            fclose($output);
            $list.="<div class='container-fluid' style='font-weight:bold;'>";
            $list.="<span class='span4'>Total users  " . count($users) . "</span><span class='span4'>Total program sum $$this->program_sum</span><span class='span2' style='font-weight:normal;'><a href='/lms/custom/reports/files/program_report_data.csv' target='_blank'>Download CSV</a></span>";
            $list.="</div>";
        } // end if count($users)>0
        else {
            $list.="<div class='container-fluid' style=''>";
            $list.="<span class='span4'>There are no users at selected program</span>";
            $list.="</div>";
        }
        return $list;
    }

    /*     * ******************************* Workshops report ******************************** */

    function get_workshops_report() {
        $list = "";
        $states_list = $this->get_states_list();
        $workshops_list = $this->get_workshops_list();
        $list.="<div class='container-fluid'>";
        $list.="<span class='span6' id='workshop_report_err'></span>";
        $list.="</div>";
        $list.="<div class='container-fluid'>";
        $list.="<span class='span2'>$states_list</span><span class='span3' id='workshops_dropdown'>$workshops_list</span><span class='span1'>From</span><span class='span2'><input type='text' id='datepicker1' style='width:75px;'></span><span class='span1'>To</span><span class='span2'><input type='text' id='datepicker2' style='width:75px;'></span><span class='span1'><button type='button' id='workshops_go' class='btn btn-primary'>Go</button></span>";
        $list.="</div>";
        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span8' style='text-align:center;display:none;' id='ajax_loading'><img src='http://$this->host/assets/img/ajax.gif' /></span>";
        $list.="</div>";
        $list.="<div id='workshops_report_container'>";
        $list.="</div>";
        return $list;
    }

    function get_workshop_report_data($courseid, $from, $to, $status = false) {
        $this->courseid = $courseid;
        $this->from = $from;
        $this->to = $to;
        $list = "";
        $workshop_users = array();
        $coursename = $this->get_course_name($courseid);
        if ($status == true) {
            $list.="<div class='container-fluid' style='font-weight:bold'>";
            $list.="<span class='span9'>$coursename - $from - $to</span>";
            $list.="</div>";
        }
        $users = $this->get_course_users($courseid, false);

        if (count($users) > 0) {
            foreach ($users as $user) {
                $user_invoice_status = $this->is_user_has_invoice_payments(strtotime($from), strtotime($to), $user->userid);
                $user_card_status = $this->is_user_has_card_payments(strtotime($from), strtotime($to), $user->userid);
                if ($user_invoice_status > 0 || $user_card_status > 0) {
                    $workshop_users[] = $user;
                } // end if $signup_date >= strtotime($from) && $signup_date <= strtotime($to)
            } // end foreach
            //print_r($workshop_users);
            if (count($workshop_users) > 0) {
                // Write CSV data
                $path = $this->files_path . '/workshop_report_data.csv';
                $output = fopen($path, 'w');
                fputcsv($output, array('User credentials', 'Payment status', 'Signup date', 'Balance'));

                $this->get_program_payments($courseid, $from, $to);
                $list.="<div class='container-fluid' style='font-weight:bold;'>";
                $list.="<span class='span4'>User credentials</span><span class='span4'>Payment status</span><span class='span2'>Signup date</span><span class='span2'>Balance</span>";
                $list.="</div>";
                $list.="<div class='container-fluid'>";
                $list.="<span class='span12'><hr/></span>";
                $list.="</div>";
                foreach ($workshop_users as $user) {
                    $user_data = $this->get_program_user_data($courseid, $user->userid);
                    $date = date('m/d/Y', $user_data->timecreated);
                    if ($user_data->firstname != '' && $user_data->lastname != '') {
                        $list.="<div class='container-fluid'>";
                        $list.="<span class='span4'>$user_data->firstname $user_data->lastname $user_data->email</span><span class='span4'>$user_data->payment_status</span><span class='span2'>$date</span><span class='span2'>$user_data->balance</span>";
                        $list.="</div>";
                        $list.="<div class='container-fluid'>";
                        $list.="<span class='span12'><hr/></span>";
                        $list.="</div>";
                        fputcsv($output, array("$user_data->firstname $user_data->lasname $user_data->email", $user_data->payment_status, $date, $user_data->balance));
                    } // end if $user->firstname!='' && $user->lastname!=''
                } // end foreach
                fclose($output);
                $list.="<div class='container-fluid' style='font-weight:bold;'>";
                $list.="<span class='span4'>Total users  " . count($workshop_users) . "</span><span class='span4'>Total program sum $$this->program_sum</span><span class='span2' style='font-weight:normal;'><a href='/lms/custom/reports/files/workshop_report_data.csv' target='_blank'>Download CSV</a></span>";
                $list.="</div>";
            } // end if count($workshop_users)>0
            else {
                $list.="<div class='container-fluid' style=''>";
                $list.="<span class='span4'>There are no users at selected program</span>";
                $list.="</div>";
            }
        } // end if count($users)>0
        else {
            $list.="<div class='container-fluid' style=''>";
            $list.="<span class='span4'>There are no users at selected program</span>";
            $list.="</div>";
        }
        return $list;
    }

    function get_card_payments_detailes($courseid, $from, $to) {

        $payments = array();
        $this->courseid = $courseid;
        $this->from = $from;
        $this->to = $to;
        $list = "";

        if ($from == $to) {
            $timestamp = time();
            $unix_from = strtotime("midnight", $timestamp);
            $unix_to = strtotime("tomorrow", $unix_from) - 1;
        } // end if $from==$to
        else {
            $unix_from = strtotime($from);
            $unix_to = strtotime($to) + 86400;
        } // end else
        //1. Get partial payments
        if ($courseid > 0) {
            $query = "select * from mdl_card_payments "
                    . "where courseid=$courseid and refunded=0 "
                    . "and pdate between $unix_from and $unix_to "
                    . "order by pdate desc ";
        } // end if $courseid>0
        else {
            $query = "select * from mdl_card_payments "
                    . "where pdate between $unix_from and $unix_to "
                    . "and refunded=0 "
                    . "order by pdate desc ";
        } // end else
        //echo "<br/>Query: $query<br/>";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $user_status = $this->is_user_deleted($row['userid']);
                if ($user_status == 0) {
                    $payment = new stdClass();
                    foreach ($row as $key => $value) {
                        $payment->$key = $value;
                    }
                    $payments[] = $payment;
                } // end if $user_status==0
            } // end while

            $csv_file = $this->create_csv_file($this->card_report_csv_file, $payments);
            $list.="<div class='container-fluid' style='text-align:left;font-weight:bold;'>";
            $list.="<span class='span3'>User</span>";
            $list.="<span class='span3'>Program applied</span>";
            $list.="<span class='span3'>Payment</span>";
            $list.="<span class='span3'>Date</span>";
            $list.="</div>";

            foreach ($payments as $payment) {
                //echo "Inside payments ...<br>";
                $date = date('m-d-Y', $payment->pdate);
                $coursename = $this->get_course_name($payment->courseid);
                $userdata = $this->get_user_details($payment->userid);
                $list.="<div class='container-fluid' style='text-align:left;'>";
                $list.="<span class='span3'><a href='https://medical2.com/lms/user/profile.php?id=$payment->userid' target='_blank'>$userdata->firstname $userdata->lastname</a></span>";
                $list.="<span class='span3'>$coursename</span>";
                $list.="<span class='span3'>$$payment->psum</span>";
                $list.="<span class='span3'>$date</span>";
                $list.="</div>";
            } // end for
        } // end if $num > 0
        else {
            $list.="<div class='container-fluid' style='text-align:center;'>";
            $list.="<span class='span12'>No data found</span>";
            $list.="</div>";
        }
        return $list;
    }

    function get_refund_payments_detailes($courseid, $from, $to) {

        $payments = array();
        $partial_payments = array();
        $this->courseid = $courseid;
        $this->from = $from;
        $this->to = $to;
        $list = "";

        if ($from == $to) {
            $timestamp = time();
            $unix_from = strtotime("midnight", $timestamp);
            $unix_to = strtotime("tomorrow", $unix_from) - 1;
        } // end if $from==$to
        else {
            $unix_from = strtotime($from);
            $unix_to = strtotime($to) + 86400 * 2;
        } // end else
        //1. Full refunded payments
        if ($courseid > 0) {
            $query = "select * from mdl_card_payments "
                    . "where courseid=$courseid and refunded=1 "
                    . "and pdate between $unix_from and $unix_to "
                    . "order by pdate desc ";
        } // end if $courseid>0
        else {
            $query = "select * from mdl_card_payments "
                    . "where pdate between $unix_from and $unix_to "
                    . "and refunded=1 order by pdate desc ";
        } // end else    
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $user_status = $this->is_user_deleted($row['userid']);
                if ($user_status == 0) {
                    $payment = new stdClass();
                    foreach ($row as $key => $value) {
                        $payment->$key = $value;
                    }
                    $payments[] = $payment;
                } // end if $user_status==0
            } // end while
        } // end if $num > 0
        //2. Partial refunded payments
        if ($courseid > 0) {
            $query = "select * from mdl_partial_refund_payments "
                    . "where courseid=$courseid "
                    . "and pdate between $unix_from and $unix_to";
        } // end if $courseid > 0
        else {
            $query = "select * from mdl_partial_refund_payments "
                    . "where pdate between $unix_from and $unix_to";
        } // end else 
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $payment = new stdClass();
                foreach ($row as $key => $value) {
                    $payment->$key = $value;
                } // end foreach
                $partial_payments[] = $payment;
            } // end while
        } // end if $num > 0

        $all_refunds = array_merge($payments, $partial_payments);

        if (count($all_refunds) > 0) {

            $this->create_csv_file($this->refund_report_csv_file, $all_refunds);
            $list.="<div class='container-fluid' style='text-align:left;font-weight:bold;'>";
            $list.="<span class='span3'>User</span>";
            $list.="<span class='span3'>Program applied</span>";
            $list.="<span class='span3'>Payment</span>";
            $list.="<span class='span3'>Date</span>";
            $list.="</div>";

            foreach ($all_refunds as $payment) {
                //echo "Inside payments ...<br>";
                $date = date('m-d-Y', $payment->pdate);
                $coursename = $this->get_course_name($payment->courseid);
                $userdata = $this->get_user_details($payment->userid);
                $list.="<div class='container-fluid' style='text-align:left;'>";
                $list.="<span class='span3'><a href='https://medical2.com/lms/user/profile.php?id=$payment->userid' target='_blank'>$userdata->firstname $userdata->lastname</a></span>";
                $list.="<span class='span3'>$coursename</span>";
                $list.="<span class='span3'>$$payment->psum</span>";
                $list.="<span class='span3'>$date</span>";
                $list.="</div>";
            } // end for
        } // end if $num > 0
        else {
            $list.="<div class='container-fluid' style='text-align:center;'>";
            $list.="<span class='span12'>No data found</span>";
            $list.="</div>";
        }
        return $list;
    }

    function get_other_payments_report($type) {

        // 1- cash
        // 2- cheque

        $list = "";
        $courses = $this->get_courses_list();

        $list.="<div class='container-fluid'>";
        $list.="<span class='span6' id='revenue_report_err'></span>";
        $list.="</div>";

        $list.="<div class='container-fluid'>";
        $list.="<input type='hidden' id='type' value='$type'>";
        $list.="<span class='span3'>$courses</span><span class='span1'>From</span><span class='span2'><input type='text' id='datepicker1' style='width:75px;'></span><span class='span1'>To</span><span class='span2'><input type='text' id='datepicker2' style='width:75px;'></span><span class='span1'><button type='button' id='other_go' class='btn btn-primary'>Go</button></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span8' style='text-align:center;display:none;' id='ajax_loading'><img src='http://$this->host/assets/img/ajax.gif' /></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:center;'>";
        $list.="<span class='span12' id='other_report_container'></span>";
        $list.="</div>";
        return $list;
    }

    function get_invoice_payments_detailes($courseid, $from, $to) {

        $payments = array();
        $this->courseid = $courseid;
        $this->from = $from;
        $this->to = $to;
        $list = "";

        if ($from == $to) {
            $timestamp = time();
            $unix_from = strtotime("midnight", $timestamp);
            $unix_to = strtotime("tomorrow", $unix_from) - 1;
        } // end if $from==$to
        else {
            $unix_from = strtotime($from);
            $unix_to = strtotime($to) + 86400;
        } // end else
        //1. Get partial payments
        if ($courseid > 0) {
            $query = "select * from mdl_invoice "
                    . "where courseid=$courseid and i_status=1 "
                    . "and i_date between $unix_from and $unix_to "
                    . "order by i_date desc ";
        } // end if $courseid>0
        else {
            $query = "select * from mdl_invoice "
                    . "where i_date between $unix_from and $unix_to "
                    . "and i_status=1 "
                    . "order by i_date desc ";
        } // end else
        //echo "<br/>Query: $query<br/>";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $user_status = $this->is_user_deleted($row['userid']);
                if ($user_status == 0) {
                    $payment = new stdClass();
                    foreach ($row as $key => $value) {
                        $payment->$key = $value;
                    }
                    $payments[] = $payment;
                } // end if $user_status==0
            } // end while

            $filename = $this->invoice_report_csv_file;
            $this->create_csv_file($filename, $payments);


            $list.="<div class='container-fluid' style='text-align:left;font-weight:bold;'>";
            $list.="<span class='span3'>User</span>";
            $list.="<span class='span3'>Program applied</span>";
            $list.="<span class='span3'>Invoice No</span>";
            $list.="<span class='span3'>Payment</span>";
            //$list.="<span class='span3'>Date</span>";
            $list.="</div>";

            foreach ($payments as $payment) {
                $date = date('m-d-Y', $payment->i_date);
                $coursename = $this->get_course_name($payment->courseid);
                $userdata = $this->get_user_details($payment->userid);
                $list.="<div class='container-fluid' style='text-align:left;'>";
                if ($payment->userid > 0) {
                    $list.="<span class='span3'><a href='https://medical2.com/lms/user/profile.php?id=$payment->userid' target='_blank'>$userdata->firstname $userdata->lastname</a></span>";
                } // end if $payment->userid
                else {
                    $list.="<span class='span3'>$payment->client</span>";
                }
                $list.="<span class='span3'>$coursename</span>";
                $list.="<span class='span3'>$payment->i_num</span>";
                $list.="<span class='span3'>$$payment->i_sum</span>";
                $list.="<span class='span3'>$date</span>";
                $list.="</div>";
            } // end for
        } // end if $num > 0
        else {
            $list.="<div class='container-fluid' style='text-align:center;'>";
            $list.="<span class='span12'>No data found</span>";
            $list.="</div>";
        }
        return $list;
    }

    function get_other_payment_report_data($courseid, $from, $to, $type) {

        $payments = array();
        $this->courseid = $courseid;
        $this->from = $from;
        $this->to = $to;
        $list = "";

        if ($from == $to) {
            $timestamp = time();
            $unix_from = strtotime("midnight", $timestamp);
            $unix_to = strtotime("tomorrow", $unix_from) - 1;
        } // end if $from==$to
        else {
            $unix_from = strtotime($from);
            $unix_to = strtotime($to) + 86400;
        } // end else
        //1. Get partial payments
        if ($courseid > 0) {
            $query = "select * from mdl_partial_payments "
                    . "where courseid=$courseid and ptype=$type "
                    . "and pdate between $unix_from and $unix_to "
                    . "order by pdate desc ";
        } // end if $courseid>0
        else {
            $query = "select * from mdl_partial_payments "
                    . "where pdate between $unix_from and $unix_to "
                    . "and ptype=$type "
                    . "order by pdate desc ";
        } // end else
        //echo "<br/>Query: $query<br/>";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $user_status = $this->is_user_deleted($row['userid']);
                if ($user_status == 0) {
                    $payment = new stdClass();
                    foreach ($row as $key => $value) {
                        $payment->$key = $value;
                    }
                    $payments[] = $payment;
                } // end if $user_status==0
            } // end while         

            $filename = ($type == 1) ? $this->cash_report_scv_file : $this->cheque_report_csv_file;
            $this->create_csv_file($filename, $payments);


            $list.="<div class='container-fluid' style='text-align:left;font-weight:bold;'>";
            $list.="<span class='span3'>User</span>";
            $list.="<span class='span3'>Program applied</span>";
            $list.="<span class='span3'>Payment</span>";
            $list.="<span class='span3'>Date</span>";
            $list.="</div>";

            foreach ($payments as $payment) {
                //echo "Inside payments ...<br>";
                $date = date('m-d-Y', $payment->pdate);
                $coursename = $this->get_course_name($payment->courseid);
                $userdata = $this->get_user_details($payment->userid);
                $list.="<div class='container-fluid' style='text-align:left;'>";
                $list.="<span class='span3'><a href='https://medical2.com/lms/user/profile.php?id=$payment->userid' target='_blank'>$userdata->firstname $userdata->lastname</a></span>";
                $list.="<span class='span3'>$coursename</span>";
                $list.="<span class='span3'>$$payment->psum</span>";
                $list.="<span class='span3'>$date</span>";
                $list.="</div>";
            } // end for
        } // end if $num > 0
        else {
            $list.="<div class='container-fluid' style='text-align:center;'>";
            $list.="<span class='span12'>No data found</span>";
            $list.="</div>";
        }
        return $list;
    }

    function get_pemissions_page() {
        $list = "";

        if ($this->session->justloggedin == 1) {
            $permissions = array();
            $query = "select * from mdl_permissions order by module_name";
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $permission = new stdClass();
                foreach ($row as $key => $value) {
                    $permission->$key = $value;
                }
                $permissions[] = $permission;
            }

            $list.="<div class='container-fluid' style='text-align:center;'>";
            $list.="<span class='span12' style='font-weight:bold;'>Permissions based list of modules</span>";
            $list.="</div>";

            $list.="<div class='container-fluid' style='text-align:left;font-weight:bold;'>";
            $list.="<span class='span3'>Module name</span>";
            $list.="<span class='span3'>Permission status</span>";
            $list.="</div>";

            foreach ($permissions as $permission) {
                switch ($permission->module_name) {
                    case 'invoice':
                        $moduleName = 'Invoices';
                        break;
                    case 'cash':
                        $moduleName = 'Cash/Cheque payments';
                        break;
                } // end switch

                if ($permission->enabled == 0) {
                    $status = "Enable &nbsp; <input type='checkbox' id='permission_$permission->id'>";
                } // end if $permission->enabled==0
                else {
                    $status = "Enable &nbsp; <input type='checkbox' id='permission_$permission->id' checked>";
                } // end else 

                $list.="<div class='container-fluid' style='text-align:left;'>";
                $list.="<span class='span3'>$moduleName</span>";
                $list.="<span class='span3'>$status</span>";
                $list.="</div>";
            } // end foreach
            $list.="<div class='container-fluid' style='text-align:center;'>";
            $list.="<span class='span6' id='status'></span>";
            $list.="</div>";
        } // end if 
        else {
            $list.="<p>You are not authenticated. &nbsp; <a href='https://medical2.com/login'><button class='btn btn-primary' id='relogin'>Login</button></a></p>";
        }


        return $list;
    }

    function update_permission($moduleid, $status) {
        $query = "update mdl_permissions "
                . "set enabled=$status where id=$moduleid";
        $this->db->query($query);
        $list = "Module permissions updated";
        return $list;
    }

    function get_renew_fee() {
        $query = "select * from mdl_renew_fee";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $fee = $row['fee_sum'];
        } // end while
        return $fee;
    }

    function get_period_payments($courseid, $unix_start, $unix_end) {
        $payments = new stdClass();
        $renew_payment = $this->get_renew_fee();

        // Credit card payments
        if ($courseid == 0) {
            $query = "select sum(p.psum) as total, p.userid, u.id, u.deleted "
                    . "from mdl_card_payments p, mdl_user u "
                    . "where p.userid=u.id and u.deleted=0 "
                    . "and pdate between $unix_start and $unix_end "
                    . "and  refunded=0";
        } // end if 
        else {
            $query = "select sum(p.psum) as total, p.userid, u.id, u.deleted "
                    . "from mdl_card_payments p, mdl_user u "
                    . "where p.userid=u.id and u.deleted=0 "
                    . "and courseid=$courseid "
                    . "and pdate between $unix_start and $unix_end "
                    . "and refunded=0";
        } // end else
        //echo "Query: ".$query."<br>";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $card_amout = $row['total'];
        }
        $payments->card = round($card_amout);

        // Cash payments
        if ($courseid == 0) {
            $query = "select sum(p.psum) as total, p.userid, u.id, u.deleted "
                    . "from mdl_partial_payments p, mdl_user u "
                    . "where p.userid=u.id and u.deleted=0 "
                    . "and pdate between $unix_start and $unix_end "
                    . "and  ptype=1";
        } // end if
        else {
            $query = "select sum(p.psum) as total, p.userid, u.id, u.deleted "
                    . "from mdl_partial_payments p, mdl_user u "
                    . "where p.userid=u.id and u.deleted=0 and courseid=$courseid "
                    . "and pdate between $unix_start and $unix_end "
                    . "and  ptype=1";
        } // end else 
        //echo "Query:".$query."<br>";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $cash_amout = $row['total'];
        }
        $payments->cash = round($cash_amout);

        // Cheque payments
        if ($courseid == 0) {
            $query = "select sum(p.psum) as total, p.userid, u.id, u.deleted "
                    . "from mdl_partial_payments p, mdl_user u "
                    . "where p.userid=u.id and u.deleted=0 "
                    . "and pdate between $unix_start and $unix_end "
                    . "and ptype=2";
        } // end if
        else {
            $query = "select sum(p.psum) as total, p.userid, u.id, u.deleted "
                    . "from mdl_partial_payments p, mdl_user u "
                    . "where p.userid=u.id and u.deleted=0 and courseid=$courseid "
                    . "and pdate between $unix_start and $unix_end "
                    . "and  ptype=2";
        } // end else
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $cheque_amount = $row['total'];
        }
        $payments->cheque = round($cheque_amount);

        // Invoice payments
        if ($courseid == 0) {
            $query = "select sum(p.i_sum) as total, p.userid, u.id, u.deleted "
                    . "from mdl_invoice p, mdl_user u "
                    . "where p.userid=u.id and u.deleted=0 and i_status=1 "
                    . "and i_pdate between $unix_start  and $unix_end ";
        } // end if
        else {
            $query = "select sum(p.i_sum) as total, p.userid, u.id, u.deleted "
                    . "from mdl_invoice p, mdl_user u "
                    . "where p.userid=u.id and u.deleted=0 and courseid=$courseid "
                    . "and i_status=1 and i_pdate between $unix_start and $unix_end ";
        }
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $invoice_amout = $row['total'];
        }
        $payments->invoice = round($invoice_amout);

        // Re-certification payments from mdl_card_payments
        $query = "select p.courseid, p.userid, sum(p.psum) as total, "
                . "p.pdate, p.refunded, "
                . "c.userid, c.courseid from mdl_card_payments p, "
                . "mdl_certificates c "
                . "where p.courseid=c.courseid "
                . "and p.userid=c.userid "
                . "and p.refunded=0 and p.psum='$renew_payment' and "
                . "p.pdate between $unix_start and $unix_end";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $recert1 = $row['total'];
        }

        // Re-certification payments from mdl_partial_payments
        $query = "select p.courseid, p.userid, sum(p.psum) as total, "
                . "p.pdate, "
                . "c.userid, c.courseid from mdl_partial_payments p, "
                . "mdl_certificates c "
                . "where p.courseid=c.courseid "
                . "and p.userid=c.userid and p.psum='$renew_payment' and "
                . "p.pdate between $unix_start and $unix_end";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $recert2 = $row['total'];
        }
        $recert = $recert1 + $recert2;
        $payments->recert = $recert;
        $payments->total = $payments->card + $payments->cash + $payments->cheque + $payments->invoice;
        $payments->grandtotal = $payments->total + $payments->recert;
        return $payments;
    }

    function get_payments_between_dates($item) {
        $list = "";
        $total_card = 0;
        $total_cash = 0;
        $total_cheque = 0;
        $total_invoice = 0;

        $original_unix_start = strtotime($item->start);
        $original_unix_end = strtotime($item->end);
        $unix_start = strtotime($item->start);
        $unix_end = strtotime($item->end);
        $diff = $unix_end - $unix_start;
        $courseid = $item->courseid;

        $list.="<table>";
        $list.="<tr>";
        $list.="<th style='padding:15px;'>Period</th>";
        $list.="<th style='padding:15px;'>Cards</th>";
        $list.="<th style='padding:15px;'>Cash</th>";
        $list.="<th style='padding:15px;'>Cheque</th>";
        $list.="<th style='padding:15px;'>Invoices</th>";
        $list.="<th style='padding:15px;'>Re-Cert</th>";
        $list.="<th style='padding:15px;'>Total</th>";
        $list.="<th style='padding:15px;'>Grand Total</th>";
        $list.="</tr>";

        switch ($item->interval) {
            case "year":
                $year_start = date('Y', $unix_start);
                $year_end = date('Y', $unix_end);
                $yearnumber = $year_end - $year_start;
                //echo "Year number: " . $yearnumber . "<br>";
                if ($yearnumber == 0) {
                    $yearnumber = 1;
                }
                for ($i = 1; $i <= $yearnumber; $i++) {
                    $y = date('Y', $unix_start);
                    $year_days_number = date("z", mktime(0, 0, 0, 12, 31, $y)) + 1;
                    $yearperiod = 86400 * $year_days_number;
                    $added = $unix_start + $yearperiod;
                    $payments = $this->get_period_payments($courseid, $unix_start, $added);
                    if ($added < $unix_end) {
                        $list.="<tr>";
                        $list.="<td style='padding:15px;'>" . date('m-d-Y', $unix_start) . " " . date('m-d-Y', $added) . "</td>";
                        $list.="<td style='padding:15px;'>$$payments->card</td>";
                        $list.="<td style='padding:15px;'>$$payments->cash</td>";
                        $list.="<td style='padding:15px;'>$$payments->cheque</td>";
                        $list.="<td style='padding:15px;'>$$payments->invoice</td>";
                        $list.="<td style='padding:15px;'>$$payments->recert</td>";
                        $list.="<td style='padding:15px;'>$$payments->total</td>";
                        $list.="<td style='padding:15px;'>$$payments->grandtotal</td>";
                        $list.="</tr>";
                        $unix_start = $added;
                    } // end if
                    else {
                        $added = $unix_end;
                        $list.="<tr>";
                        $list.="<td style='padding:15px;'>" . date('m-d-Y', $unix_start) . " " . date('m-d-Y', $added) . "</td>";
                        $list.="<td style='padding:15px;'>$$payments->card</td>";
                        $list.="<td style='padding:15px;'>$$payments->cash</td>";
                        $list.="<td style='padding:15px;'>$$payments->cheque</td>";
                        $list.="<td style='padding:15px;'>$$payments->invoice</td>";
                        $list.="<td style='padding:15px;'>$$payments->recert</td>";
                        $list.="<td style='padding:15px;'>$$payments->total</td>";
                        $list.="<td style='padding:15px;'>$$payments->grandtotal</td>";
                        $list.="</tr>";
                    } /// end else
                } // end for
                $total_payments = $this->get_period_payments($courseid, $original_unix_start, $added);
                $list.="<tr>";
                $list.="<th style='padding-left:15px;padding-right:15px;' colspan='10'><hr/></th>";
                $list.="</tr>";
                $list.="<tr style='font-weight:bolder;'>";
                $list.="<th style='padding:15px;'>" . date('m-d-Y', $original_unix_start) . " " . date('m-d-Y', $added) . "</th>";
                $list.="<th style='padding:15px;'>$$total_payments->card</th>";
                $list.="<th style='padding:15px;'>$$total_payments->cash</th>";
                $list.="<th style='padding:15px;'>$$total_payments->cheque</th>";
                $list.="<th style='padding:15px;'>$$total_payments->invoice</th>";
                $list.="<th style='padding:15px;'>$$total_payments->recert</th>";
                $list.="<th style='padding:15px;'>$$total_payments->total</th>";
                $list.="<th style='padding:15px;'>$$total_payments->grandtotal</th>";
                $list.="</tr>";
                $list.="</table>";
                break;
            case "month":
                $year_start = date('Y', $unix_start);
                $year_end = date('Y', $unix_end);
                if ($year_start == $year_end) {
                    $monthnumber = idate('m', $diff);
                } // end if
                else {
                    $year_diff = $year_end - $year_start;
                    $monthnumber = idate('m', $diff) + 12 * $year_diff;
                } // end else
                for ($i = 1; $i <= ($monthnumber - 1); $i++) {
                    $m = date('m', $unix_start);
                    $y = date('Y', $unix_start);
                    $days_in_month = cal_days_in_month(CAL_GREGORIAN, $m, $y);
                    $monthperiod = 86400 * $days_in_month;
                    $added = $unix_start + $monthperiod;
                    $payments = $this->get_period_payments($courseid, $unix_start, $added);
                    if ($added < $unix_end) {
                        $list.="<tr>";
                        $list.="<td style='padding:15px;'>" . date('m-d-Y', $unix_start) . " " . date('m-d-Y', $added) . "</td>";
                        $list.="<td style='padding:15px;'>$$payments->card</td>";
                        $list.="<td style='padding:15px;'>$$payments->cash</td>";
                        $list.="<td style='padding:15px;'>$$payments->cheque</td>";
                        $list.="<td style='padding:15px;'>$$payments->invoice</td>";
                        $list.="<td style='padding:15px;'>$$payments->recert</td>";
                        $list.="<td style='padding:15px;'>$$payments->total</td>";
                        $list.="<td style='padding:15px;'>$$payments->grandtotal</td>";
                        $list.="</tr>";
                        $unix_start = $added;
                    } // end if
                    else {
                        $added = $unix_end;
                        $list.="<tr>";
                        $list.="<td style='padding:15px;'>" . date('m-d-Y', $unix_start) . " " . date('m-d-Y', $added) . "</td>";
                        $list.="<td style='padding:15px;'>$$payments->card</td>";
                        $list.="<td style='padding:15px;'>$$payments->cash</td>";
                        $list.="<td style='padding:15px;'>$$payments->cheque</td>";
                        $list.="<td style='padding:15px;'>$$payments->invoice</td>";
                        $list.="<td style='padding:15px;'>$$payments->recert</td>";
                        $list.="<td style='padding:15px;'>$$payments->total</td>";
                        $list.="<td style='padding:15px;'>$$payments->grandtotal</td>";
                        $list.="</tr>";
                    } /// end else
                } // end for
                $total_payments = $this->get_period_payments($courseid, $original_unix_start, $added);
                $list.="<tr>";
                $list.="<th style='padding-left:15px;padding-right:15px;' colspan='10'><hr/></th>";
                $list.="</tr>";
                $list.="<tr style='font-weight:bolder;'>";
                $list.="<th style='padding:15px;'>" . date('m-d-Y', $original_unix_start) . " " . date('m-d-Y', $added) . "</th>";
                $list.="<th style='padding:15px;'>$$total_payments->card</th>";
                $list.="<th style='padding:15px;'>$$total_payments->cash</th>";
                $list.="<th style='padding:15px;'>$$total_payments->cheque</th>";
                $list.="<th style='padding:15px;'>$$total_payments->invoice</th>";
                $list.="<th style='padding:15px;'>$$total_payments->recert</th>";
                $list.="<th style='padding:15px;'>$$total_payments->total</th>";
                $list.="<th style='padding:15px;'>$$total_payments->grandtotal</th>";
                $list.="</tr>";
                $list.="</table>";
                break;
            case "week":
                $year_start = date('Y', $unix_start);
                $year_end = date('Y', $unix_end);
                if ($year_start != $year_end) {
                    $list.="<tr>";
                    $list.="<th style='padding:15px;text-align:center;' colspan='5' >Weeks comparison available within the same year</th>";
                    $list.="</tr>";
                } // end if 
                else {
                    //$month_start = date('m', $unix_start);
                    //$month_end = date('m', $unix_end);
                    $weeksnumber = idate('W', $diff);
                    $weekperiod = 86400 * 7;
                    for ($i = 1; $i <= ($weeksnumber - 1); $i++) {
                        $added = $unix_start + $weekperiod;
                        $payments = $this->get_period_payments($courseid, $unix_start, $added);
                        if ($added < $unix_end) {
                            $list.="<tr>";
                            $list.="<td style='padding:15px;'>" . date('m-d-Y', $unix_start) . " " . date('m-d-Y', $added) . "</td>";
                            $list.="<td style='padding:15px;'>$$payments->card</td>";
                            $list.="<td style='padding:15px;'>$$payments->cash</td>";
                            $list.="<td style='padding:15px;'>$$payments->cheque</td>";
                            $list.="<td style='padding:15px;'>$$payments->invoice</td>";
                            $list.="<td style='padding:15px;'>$$payments->recert</td>";
                            $list.="<td style='padding:15px;'>$$payments->total</td>";
                            $list.="<td style='padding:15px;'>$$payments->grandtotal</td>";
                            $list.="</tr>";
                            $unix_start = $added;
                        } // end if
                        else {
                            $added = $unix_end;
                            if ($unix_start != $added) {
                                $list.="<tr>";
                                $list.="<td style='padding:15px;'>" . date('m-d-Y', $unix_start) . " " . date('m-d-Y', $added) . "</td>";
                                $list.="<td style='padding:15px;'>$$payments->card</td>";
                                $list.="<td style='padding:15px;'>$$payments->cash</td>";
                                $list.="<td style='padding:15px;'>$$payments->cheque</td>";
                                $list.="<td style='padding:15px;'>$$payments->invoice</td>";
                                $list.="<td style='padding:15px;'>$$payments->recert</td>";
                                $list.="<td style='padding:15px;'>$$payments->total</td>";
                                $list.="<td style='padding:15px;'>$$payments->grandtotal</td>";
                                $list.="</tr>";
                                //return;
                            } // end if
                            else {
                                //return;
                            } // end ekse
                        } /// end else
                    } // end for
                    $total_payments = $this->get_period_payments($courseid, $original_unix_start, $added);
                    $list.="<tr>";
                    $list.="<th style='padding-left:15px;padding-right:15px;' colspan='10'><hr/></th>";
                    $list.="</tr>";
                    $list.="<tr style='font-weight:bolder;'>";
                    $list.="<th style='padding:15px;'>" . date('m-d-Y', $original_unix_start) . " " . date('m-d-Y', $added) . "</th>";
                    $list.="<th style='padding:15px;'>$$total_payments->card</th>";
                    $list.="<th style='padding:15px;'>$$total_payments->cash</th>";
                    $list.="<th style='padding:15px;'>$$total_payments->cheque</th>";
                    $list.="<th style='padding:15px;'>$$total_payments->invoice</th>";
                    $list.="<th style='padding:15px;'>$$total_payments->recert</th>";
                    $list.="<th style='padding:15px;'>$$total_payments->total</th>";
                    $list.="<th style='padding:15px;'>$$total_payments->grandtotal</th>";
                    $list.="</tr>";
                    $list.="</table>";
                    break;
                } // end else
        } // end switch
        return $list;
    }

    function get_stat_data($item) {
        $list = "";
        $unix_start = strtotime($item->start);
        $unix_end = strtotime($item->end);

        if ($unix_start > $unix_end) {
            $list.="Period end date must be later then period begin date";
            return;
        } // end if 

        $datetime1 = date_create($item->start);
        $datetime2 = date_create($item->end);
        $interval = date_diff($datetime1, $datetime2);
        $posted_interval = $item->interval;
        $days = $interval->format('%a');

        switch ($posted_interval) {
            case 'year':
                if ($days < 270) {
                    $list.="Selected period should contain at least one year";
                    return $list;
                } // end if
                else {
                    $list.=$this->get_payments_between_dates($item);
                } // end else
                break;
            case 'month':
                if ($days < 40) {
                    $list.="Selected period should contain at least two months";
                    return $list;
                } // end if
                else {
                    $list.=$this->get_payments_between_dates($item);
                } // end else
                break;
            case 'week':
                if ($days < 14) {
                    $list.="Selected period should contain at least two weeks";
                    return $list;
                } // end if
                else {
                    $list.=$this->get_payments_between_dates($item);
                } // end else
                break;
        } // end of switch
        return $list;
    }

}
