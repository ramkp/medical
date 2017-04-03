<?php

/**
 * Description of Payments
 *
 * @author sirromas
 */
require_once ($_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/lms/custom/invoices/classes/Invoice.php');
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/authorize/Classes/ProcessPayment.php';

class Payments extends Util {

    public $type;
    public $typeid;
    public $limit = 3;

    function __construct($payment_type) {
        parent::__construct();
        $this->typeid = $payment_type;
    }

    function get_invoice_payments($payment_type) {
        $list = "";
        if ($this->session->justloggedin == 1) {
            $invoice_payments = array();
            $query = "select * from mdl_invoice "
                    . "where i_status=1 and "
                    . "i_ptype=$payment_type order by i_pdate desc limit 0, $this->limit";
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
            } // end if $num > 0
            $this->typeid = $payment_type;
            $this->type = $this->get_payments_type($payment_type);
            $list.= $this->get_invoice_payments_page($invoice_payments);
        } // end if 
        else {
            $list.="<p>You are not authenticated. &nbsp; <a href='https://medical2.com/login'><button class='btn btn-primary' id='relogin'>Login</button></a></p>";
        } // end else
        return $list;
    }

    function get_payments_type($payment_type) {
        $query = "select * from mdl_payments_type where id=$payment_type";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $type = $row['type'];
        }
        return $type;
    }

    function get_invoice_id($date) {
        $query = "select id from mdl_invoice where i_pdate='$date'";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $id = $row['id'];
        }
        return $id;
    }

    function get_invoice_payments_page($invoice_payments, $toolbar = true, $search = false) {
        $list = "";
        $invoice = new Invoices();
        if (count($invoice_payments) > 0) {
            if ($toolbar == true) {
                $list.="<div class='container-fluid' style='text-align:center;'>";
                $list.="<span class='span2'>Search</span>";
                $list.="<input type='hidden' id='ptype' value='$this->typeid'>";
                $list.="<span class='span2'><input type='text' id='search_payment' class='typeahead' autocomplete='off' spellcheck='false' style='width:125px;' /></span>";
                $list.="<span class='span3'><button class='btn btn-primary' id='search_payment_button'>Search</button></span>";
                $list.="<span class='span2'><button class='btn btn-primary' id='clear_payment_button'>Clear filter</button></span>";
                $list.="</div>";
                $list.="<div class='container-fluid' style='text-align:center;'>";
                $list.="<span class='span8' style='color:red;' id='payment_err'></span>";
                $list.="</div>";
                $list.="<div class='container-fluid' style='display:none;text-align:center;' id='ajax_loader'>";
                $list.="<span class='span10'><img src='http://$this->host/assets/img/ajax.gif' /></span>";
                $list.="</div>";
            } // end if $toolbar==true
            $list.="<div id='payment_container'>";
            $total = count($invoice_payments);
            if ($total <= $this->limit && $search == false) {
                $total = $this->get_total_payments($this->typeid);
            }
            $list.="<div class='container-fluid' style='text-align:center;font-weight:bold;'>";
            $list.="<span class='span10'>Total invoice payments: $total</span>";
            $list.="</div>";
            foreach ($invoice_payments as $payment) {
                $user = $this->get_user_details($payment->userid);
                $course = $this->get_course_name($payment->courseid);
                $date = date('Y-m-d', $payment->i_pdate);
                $address_block = $this->get_user_address_block($payment->userid);
                $list.="<div class='container-fluid'>";
                $list.="<span class='span1'>User</span><span class='span3'><a href='https://" . $_SERVER['SERVER_NAME'] . "/lms/user/profile.php?id=$payment->userid' target='_blank'>$user->firstname $user->lastname ($user->email)</a></span>";
                $list.="</div>";
                $list.="<div class='container-fluid'>";
                $list.="<span class='span1'>Address</span><span class='span3'>$address_block</span>";
                $list.="</div>";

                $list.="<div class='container-fluid'>";
                $list.="<span class='span1'>Program</span><span class='span3'>$course</span>";
                $list.="</div>";
                $sum = $invoice->get_invoice_sum($payment->courseid, $payment->userid, $payment->i_sum);
                $list.="<div class='container-fluid'>";
                $list.="<span class='span1'>Payment</span><span class='span7'>Invoice # $payment->i_num for $$sum payment date $date</span>";
                $list.="</div>";
                $list.="<div class='container-fluid'>";
                $list.="<span class='span6'><hr/></span>";
                $list.="</div>";
            } // end foreach
            $list.="</div>";
            if ($toolbar == true) {
                $list.="<div class='container-fluid'>";
                $list.="<span class='span6'  id='pagination'></span>";
                $list.="</div>";
            } // end if $toolbar == true
        } // end if count($invoice_payments)>0
        else {
            $list.="<div class='container-fluid'>";
            $list.="<span class='span6'>There are no $this->type payments</span>";
            $list.="</div>";
            $list.="<div class='container-fluid'>";
        } // end else 
        return $list;
    }

    function get_total_payments($ptype = null) {
        if ($ptype == null) {
            $type = $this->typeid;
        } // end if $ptype==null
        else {
            $type = $ptype;
        } // end else 
        $query = "select * from mdl_invoice "
                . "where i_status=1 and i_ptype=$type";
        //echo "Query: " . $query . "<br>";
        $num = $this->db->numrows($query);
        return $num;
    }

    function get_payment_item($page, $typeid) {
        $payments = array();
        $rec_limit = $this->limit;
        if ($page == 1) {
            $offset = 0;
        } // end if $page==1
        else {
            $page = $page - 1;
            $offset = $rec_limit * $page;
        }
        $query = "select * from mdl_invoice "
                . "where i_status=1 and "
                . "i_ptype=$typeid order by i_pdate desc LIMIT $offset, $rec_limit";
        //echo "Query: ".$query ."<br>";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $payment = new stdClass();
            foreach ($row as $key => $value) {
                $payment->$key = $value;
            } // end foreach      
            $payments[] = $payment;
        } // end while
        $list = $this->get_invoice_payments_page($payments, false);
        return $list;
    }

    function get_payment_log_page() {
        $list = "";
        if ($this->session->justloggedin == 1) {
            $payments = array();
            $query = "select * from mdl_payments_log "
                    . "order by date_added desc limit 0, $this->limit";
            $num = $this->db->numrows($query);
            if ($num > 0) {
                $result = $this->db->query($query);
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $payment = new stdClass();
                    foreach ($row as $key => $value) {
                        $payment->$key = $value;
                    } // end foreach 
                    $payments[] = $payment;
                } // end while
            } // end if $num > 0
            $list.= $this->create_payment_log_page($payments);
        } // end if
        else {
            $list.="<p>You are not authenticated. &nbsp; <a href='https://medical2.com/login'><button class='btn btn-primary' id='relogin'>Login</button></a></p>";
        }

        return $list;
    }

    function create_payment_log_page($payments, $toolbar = true) {
        $list = "";
        if (count($payments) > 0) {
            $invoice = new Invoices();
            $list.="<div id='payment_log_container'>";
            foreach ($payments as $payment) {
                $user = $this->get_user_details($payment->userid);
                $modifier = $this->get_user_details($payment->modifierid);
                $course = $this->get_course_name($payment->courseid);
                $invoice_id = $this->get_invoice_id($payment->date_added);
                $invoice_detailes = $invoice->get_invoice_detailes($invoice_id);

                //print_r($invoice_detailes);
                //echo "<br>";
                $date = date('Y-m-d', $invoice_detailes->i_date);
                $list.="<div class='container-fluid'>";
                $list.="<span class='span2'>User</span><span class='span3'>$user->firstname &nbsp $user->lastname</span>";
                $list.="</div>";
                $list.="<div class='container-fluid'>";
                $list.="<span class='span2'>Program</span><span class='span3'>$course </span>";
                $list.="</div>";
                $sum = $invoice->get_invoice_sum($invoice_detailes->courseid, $invoice_detailes->userid, $invoice_detailes->i_sum);
                //echo "Invoice sum: ".$sum."<br>";
                $list.="<div class='container-fluid'>";
                $list.="<span class='span2'>Invoice</span><span class='span5'>Invoice # $invoice_detailes->i_num for $$sum &nbsp; from $date</span>";
                $list.="</div>";
                $list.="<div class='container-fluid'>";
                $list.="<span class='span2'>Access granted by</span><span class='span3'>$modifier->firstname &nbsp; $modifier->lastname</span>";
                $list.="</div>";
                $list.="<div class='container-fluid'>";
                $list.="<span class='span7'><hr/></span>";
                $list.="</div>";
            } // end foreach
            $list.="</div>";
            if ($toolbar == true) {
                $list.="<div class='container-fluid'>";
                $list.="<span class='span6'  id='pagination'></span>";
                $list.="</div>";
            } // end if $toolbar==true
        } // end if count($payments)>0
        else {
            $list.="<div class='container-fluid'>";
            $list.="<span class='span6'>Payments log is empty</span>";
            $list.="</div>";
        } // end else
        return $list;
    }

    function get_payment_log_item($page) {
        $payments = array();
        $rec_limit = $this->limit;
        if ($page == 1) {
            $offset = 0;
        } // end if $page==1
        else {
            $page = $page - 1;
            $offset = $rec_limit * $page;
        }
        $query = "select * from mdl_payments_log "
                . "order by date_added desc "
                . "LIMIT $offset, $rec_limit";
        //echo "Query: ".$query ."<br>";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $payment = new stdClass();
            foreach ($row as $key => $value) {
                $payment->$key = $value;
            } // end foreach      
            $payments[] = $payment;
        } // end while
        $list = $this->create_payment_log_page($payments, false);
        return $list;
    }

    function get_total_log_entries() {
        $query = "select id from mdl_payments_log order by id asc";
        $num = $this->db->numrows($query);
        return $num;
    }

    function get_card_payments_page() {
        $list = "";
        if ($this->session->justloggedin == 1) {
            $payments = array();
            $query = "select * "
                    . "from mdl_card_payments where refunded=0 "
                    . "order by pdate desc limit 0, $this->limit";
            $num = $this->db->numrows($query);
            if ($num > 0) {
                $result = $this->db->query($query);
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $payment = new stdClass();
                    foreach ($row as $key => $value) {
                        $payment->$key = $value;
                    } // end foreach      
                    $payments[] = $payment;
                } // end while
            } // end if $num>0
            $list.= $this->create_card_payments_page($payments);
        } // end if 
        else {
            $list.="<p>You are not authenticated. &nbsp; <a href='https://medical2.com/login'><button class='btn btn-primary' id='relogin'>Login</button></a></p>";
        } // end else

        return $list;
    }

    function get_refund_page() {
        $list = "";

        if ($this->session->justloggedin == 1) {
            $payments1 = array();
            $payments2 = array();

            $query = "select * "
                    . "from mdl_card_payments where refunded=1 "
                    . "order by refund_date desc ";
            $num = $this->db->numrows($query);
            if ($num > 0) {
                $result = $this->db->query($query);
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $payment = new stdClass();
                    foreach ($row as $key => $value) {
                        $payment->$key = $value;
                    } // end foreach      
                    $payments1[] = $payment;
                } // end while
            } // end if $num>0

            $query = "select * "
                    . "from mdl_partial_refund_payments "
                    . "order by refund_date desc ";
            $num = $this->db->numrows($query);
            if ($num > 0) {
                $result = $this->db->query($query);
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $payment = new stdClass();
                    foreach ($row as $key => $value) {
                        $payment->$key = $value;
                    } // end foreach      
                    $payments2[] = $payment;
                } // end while
            } // end if $num>0
            $payments = array_merge($payments2, $payments1);
            //ksort($payments);
            $list.= $this->create_refunded_payments_page($payments);
        } // end if 
        else {
            $list.="<p>You are not authenticated. &nbsp; <a href='https://medical2.com/login'><button class='btn btn-primary' id='relogin'>Login</button></a></p>";
        }


        return $list;
    }

    function create_refunded_payments_page($payments, $toolbar = true, $search = false) {
        $list = "";
        $list.="<div class='container-fluid' style='text-align:center;' id='pwd_container'>";
        $list.="<span class='span3'>Password*: </span><span class='span5'><input type='password' id='refund_pwd'></span><span class='span1'><button class='btn btn-primary' id='show_refund_page' >Ok</button></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:center;' id='pwd_container'>";
        $list.="<span class='span12' id='refund_pwd_err' style='color:red;'></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='display:none;' id='refund_container'>";
        $list.="<span class='span12'>";
        if (count($payments) > 0) {
            if ($toolbar == true) {
                $list.="<div class='container-fluid' style='text-align:center;'>";
                $list.="<span class='span2'>Search</span>";
                $list.="<span class='span2'><input type='text' id='search_payment' style='width:125px;' /></span>";
                $list.="<span class='span3'><button class='btn btn-primary' id='search_refund_payment_button'>Search</button></span>";
                $list.="<span class='span2'><button class='btn btn-primary' id='clear_refund_payment_button'>Clear filter</button></span>";
                $list.="<span class='span2'><button class='btn btn-primary' id='make_refund_button' style='width:175px;'>Make Refund</button></span>";
                $list.="</div>";
                $list.="<div class='container-fluid' style='text-align:center;'>";
                $list.="<span class='span8' style='color:red;' id='payment_err'></span>";
                $list.="</div>";
                $list.="<div class='container-fluid' style='display:none;text-align:center;' id='ajax_loader'>";
                $list.="<span class='span10'><img src='http://$this->host/assets/img/ajax.gif' /></span>";
                $list.="</div>";
            } // end if $toolbar==true            
            $list.="<div id='card_payments_container'>";
            $total = count($payments);
            if ($total <= $this->limit && $search == false) {
                $total = $this->get_total_refund_payments();
            }
            $list.="<div class='container-fluid' style='text-align:center;font-weight:bold;'>";
            $list.="<span class='span10'>Total payments: $total</span>";
            $list.="</div>";
            foreach ($payments as $payment) {
                $user = $this->get_user_details($payment->userid);
                $course = $this->get_course_name($payment->courseid);
                $date = date('Y-m-d', $payment->refund_date);
                $user_payments = $this->get_user_address_block($payment->userid);
                $list.="<div class='container-fluid' style=''>";
                $list.="<span class='span2'>User</span><span class='span3'><a href='https://" . $_SERVER['SERVER_NAME'] . "/lms/user/profile.php?id=$payment->userid' target='_blank'>$user->firstname &nbsp $user->lastname ($user->email)</a></span>";
                $list.="</div>";
                $list.="<div class='container-fluid'>";
                $list.="<span class='span2'>Address</span><span class='span3'>$user_payments</span>";
                $list.="</div>";

                $list.="<div class='container-fluid'>";
                $list.="<span class='span2'>Program</span><span class='span6'>$course </span>";
                $list.="</div>";
                $list.="<div class='container-fluid'>";
                $list.="<span class='span2'>Refund sum</span><span class='span3'>$$payment->psum from $date</span>";
                $list.="</div>";
                $list.="<div class='container-fluid'>";
                $list.="<span class='span7'><hr/></span>";
                $list.="</div>";
            } // end foreach
            $list.="</div>";
            if ($toolbar == true) {
                $list.="<div class='container-fluid'>";
                $list.="<span class='span6'  id='pagination'></span>";
                $list.="</div>";
            }
        } // end if count($payments)>0
        else {
            $list.="<div class='container-fluid'>";
            $list.="<span class='span3'>There are no refund payments</span>";
            $list.="<span class='span2'><button class='btn btn-primary' id='make_refund_button' style='width:175px;'>Make Refund</button></span>";
            $list.="</div>";
        }
        $list.="</span></div>";
        return $list;
    }

    function create_card_payments_page($payments, $toolbar = true, $search = false) {
        $list = "";
        if (count($payments) > 0) {
            if ($toolbar == true) {
                $list.="<div class='container-fluid' style='text-align:center;'>";
                $list.="<span class='span2'>Search</span>";
                $list.="<span class='span2'><input type='text' id='search_payment' style='width:125px;' /></span>";
                $list.="<span class='span3'><button class='btn btn-primary' id='search_card_payment_button'>Search</button></span>";
                $list.="<span class='span2'><button class='btn btn-primary' id='clear_card_payment_button'>Clear filter</button></span>";
                $list.="</div>";
                $list.="<div class='container-fluid' style='text-align:center;'>";
                $list.="<span class='span8' style='color:red;' id='payment_err'></span>";
                $list.="</div>";
                $list.="<div class='container-fluid' style='display:none;text-align:center;' id='ajax_loader'>";
                $list.="<span class='span10'><img src='http://$this->host/assets/img/ajax.gif' /></span>";
                $list.="</div>";
            } // end if $toolbar==true            
            $list.="<div id='card_payments_container'>";
            $total = count($payments);
            if ($total <= $this->limit && $search == false) {
                $total = $this->get_total_card_payments();
            }
            $list.="<div class='container-fluid' style='text-align:center;font-weight:bold;'>";
            $list.="<span class='span10'>Total payments: $total</span>";
            $list.="</div>";
            foreach ($payments as $payment) {
                $user = $this->get_user_details($payment->userid);
                $course = $this->get_course_name($payment->courseid);
                $date = date('Y-m-d', $payment->pdate);
                $user_payments = $this->get_user_address_block($payment->userid);
                $list.="<div class='container-fluid'>";
                $list.="<span class='span2'>User</span><span class='span3'><a href='https://" . $_SERVER['SERVER_NAME'] . "/lms/user/profile.php?id=$payment->userid' target='_blank'>$user->firstname &nbsp $user->lastname ($user->email)</a></span>";
                $list.="</div>";
                $list.="<div class='container-fluid'>";
                $list.="<span class='span2'>Address</span><span class='span3'>$user_payments</span>";
                $list.="</div>";

                $list.="<div class='container-fluid'>";
                $list.="<span class='span2'>Program</span><span class='span6'>$course </span>";
                $list.="</div>";
                $list.="<div class='container-fluid'>";
                $list.="<span class='span2'>Paid sum</span><span class='span3'>$$payment->psum from $date</span>";
                $list.="</div>";
                $list.="<div class='container-fluid'>";
                $list.="<span class='span7'><hr/></span>";
                $list.="</div>";
            } // end foreach
            $list.="</div>";
            if ($toolbar == true) {
                $list.="<div class='container-fluid'>";
                $list.="<span class='span6'  id='pagination'></span>";
                $list.="</div>";
            }
        } // end if count($payments)>0
        else {
            $list.="<div class='container-fluid'>";
            $list.="<span class='span6'>There are no card payments</span>";
            $list.="</div>";
        }
        return $list;
    }

    function get_total_card_payments() {
        $query = "select id from mdl_card_payments order by pdate desc";
        $num = $this->db->numrows($query);
        return $num;
    }

    function get_total_refund_payments() {
        $query = "select id from mdl_card_payments where refunded=1 order by pdate desc";
        $num = $this->db->numrows($query);
        return $num;
    }

    function get_card_payments_item($page) {
        $payments = array();
        $rec_limit = $this->limit;
        if ($page == 1) {
            $offset = 0;
        } // end if $page==1
        else {
            $page = $page - 1;
            $offset = $rec_limit * $page;
        }
        $query = "select * from mdl_card_payments where refunded=0 "
                . "order by pdate desc "
                . "LIMIT $offset, $rec_limit";
        //echo "Query: ".$query ."<br>";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $payment = new stdClass();
            foreach ($row as $key => $value) {
                $payment->$key = $value;
            } // end foreach      
            $payments[] = $payment;
        } // end while
        $list = $this->create_card_payments_page($payments, false);
        return $list;
    }

    function get_refund_item($page) {
        $payments = array();
        $rec_limit = $this->limit;
        if ($page == 1) {
            $offset = 0;
        } // end if $page==1
        else {
            $page = $page - 1;
            $offset = $rec_limit * $page;
        }
        $query = "select * from mdl_card_payments where refunded=1 "
                . "order by refund_date desc "
                . "LIMIT $offset, $rec_limit";
        //echo "Query: ".$query ."<br>";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $payment = new stdClass();
            foreach ($row as $key => $value) {
                $payment->$key = $value;
            } // end foreach      
            $payments[] = $payment;
        } // end while
        $list = $this->create_refunded_payments_page($payments, false);
        return $list;
    }

    function get_renew_fee_page() {
        $list = "";

        if ($this->session->justloggedin == 1) {
            $query = "select * from mdl_renew_fee where id=1";
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $fee = $row['fee_sum'];
            }
            $list.="<div class='container-fluid'>";
            $list.="<span class='span3'>Renew Fee Amount</span><span class='span3'><input type='text' id='renew_fee2' name='renew_fee2' value='$fee' style='width:45px;'></span>";
            $list.="</div>";
            $list.="<div class='container-fluid'>";
            $list.="<span class='span3'><button type='button' id='update_renew_fee' class='btn btn-primary'>Update</button></span><span class='span5' id='fee_err'></span>";
            $list.="</div>";
        } // end if 
        else {
            $list.="<p>You are not authenticated. &nbsp; <a href='https://medical2.com/login'><button class='btn btn-primary' id='relogin'>Login</button></a></p>";
        }


        return $list;
    }

    function update_renew_fee($fee) {
        $query = "update mdl_renew_fee set fee_sum='$fee' where id=1";
        $this->db->query($query);
        $list = "Item successfully updated";
        return $list;
    }

    function search_item($item, $typeid) {
        $list = "";
        $invoices = array();
        $invoice = new Invoices();
        $users_list = implode(",", $invoice->search_invoice_users($item));
        $courses_list = implode(",", $invoice->search_invoice_courses($item));
        if ($users_list != '') {
            $query = "select * from mdl_invoice "
                    . "where i_status=1 and i_ptype=$typeid "
                    . "and userid in ($users_list) order by i_date desc ";
        } // end if $users_list != ''
        if ($courses_list != '') {
            $query = "select * from mdl_invoice "
                    . "where i_status=1 and i_ptype=$typeid "
                    . "and courseid in ($courses_list) order by i_date desc ";
        } // end if $courses_list != ''
        if ($users_list != '' && $courses_list != '') {
            $query = "select * from mdl_invoice "
                    . "where i_status=1 and i_ptype=$typeid "
                    . "and (courseid in ($courses_list) "
                    . "or userid in ($users_list)) order by i_date desc ";
        } // end if $users_list != '' && $courses_list != ''
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $invoice = new stdClass();
                foreach ($row as $key => $value) {
                    $invoice->$key = $value;
                }
                $invoices[] = $invoice;
            } // end while
            $list.=$this->get_invoice_payments_page($invoices, false, true);
        } // end if $num > 0
        else {
            $list.="<div class='container-fluid' style='text-align:center;'>";
            $list.="<span class='span6'>No invoices found</span>";
            $list.="</div>";
        } // end else 
        return $list;
    }

    function search_credit_card_payment($item) {
        $list = "";
        $payments = array();
        $invoice = new Invoices();
        $users_list = implode(",", $invoice->search_invoice_users($item));
        $courses_list = implode(",", $invoice->search_invoice_courses($item));
        if ($users_list != '' || $courses_list != '') {
            if ($users_list != '') {
                $query = "select * from mdl_card_payments "
                        . "where userid in ($users_list) order by pdate desc ";
            } // end if $users_list != ''
            if ($courses_list != '') {
                $query = "select * from mdl_card_payments "
                        . "where courseid in ($courses_list) order by pdate desc ";
            } // end if $courses_list != ''
            if ($users_list != '' && $courses_list != '') {
                $query = "select * from mdl_card_payments "
                        . "where courseid in ($courses_list) "
                        . "or userid in ($users_list) order by pdate desc ";
            } // end if $users_list != '' && $courses_list != ''        
            //echo "Query: ".$query."<br>";
            $num = $this->db->numrows($query);
        } // end if $users_list!='' || $courses_list!=''
        else {
            $num = 0;
        }
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $payment = new stdClass();
                foreach ($row as $key => $value) {
                    $payment->$key = $value;
                }
                $payments[] = $payment;
            } // end while
            $list.=$this->create_card_payments_page($payments, false, true);
        } // end if $num > 0
        else {
            $list.="<div class='container-fluid' style='text-align:center;'>";
            $list.="<span class='span6'>No payments found</span>";
            $list.="</div>";
        } // end else 
        return $list;
    }

    function search_refund_payment($item) {
        $list = "";
        $payments = array();
        $invoice = new Invoices();
        $users_list = implode(",", $invoice->search_invoice_users($item));
        $courses_list = implode(",", $invoice->search_invoice_courses($item));
        if ($users_list != '' || $courses_list != '') {
            if ($users_list != '') {
                $query = "select * from mdl_card_payments "
                        . "where userid in ($users_list) and refunded=1 order by pdate desc ";
            } // end if $users_list != ''
            if ($courses_list != '') {
                $query = "select * from mdl_card_payments "
                        . "where courseid in ($courses_list) and refunded=1 order by pdate desc ";
            } // end if $courses_list != ''
            if ($users_list != '' && $courses_list != '') {
                $query = "select * from mdl_card_payments "
                        . "where refunded=1 and ( courseid in ($courses_list) "
                        . "or userid in ($users_list))  order by pdate desc ";
            } // end if $users_list != '' && $courses_list != ''        
            //echo "Query: ".$query."<br>";
            $num = $this->db->numrows($query);
        } // end if $users_list!='' || $courses_list!=''
        else {
            $num = 0;
        }
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $payment = new stdClass();
                foreach ($row as $key => $value) {
                    $payment->$key = $value;
                }
                $payments[] = $payment;
            } // end while
            $list.=$this->create_refunded_payments_page($payments, false, true);
        } // end if $num > 0
        else {
            $list.="<div class='container-fluid' style='text-align:center;'>";
            $list.="<span class='span6'>No refund payments found</span>";
            $list.="</div>";
        } // end else 
        return $list;
    }

    function get_refund_courses() {
        $list = "";
        $list.="<select id='refund_courses' style='width:375px;'>";
        $list.="<option value='0' selected>Programs</option>";
        $query = "select * from mdl_course where cost>0 and visible=1";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $list.="<option value='" . $row['id'] . "'>" . $row['fullname'] . "</option>";
            } // end while
        } // end if $num > 0
        $list.="</select>";
        return $list;
    }

    function get_course_payments($courseid = null) {
        $users = array();
        if ($courseid != null) {
            $query = "select * from mdl_card_payments "
                    . "where courseid=$courseid "
                    . "and exp_date>0 "
                    . "and refunded=0 order by pdate desc";
            $num = $this->db->numrows($query);
            if ($num > 0) {
                $result = $this->db->query($query);
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $userdata = $this->get_user_details($row['userid']);
                    $date = date('m-d-Y', $row['pdate']);
                    $user = new stdClass();
                    $user->id = $row['id'];
                    $user->firstname = $userdata->firstname;
                    $user->lastname = $userdata->lastname;
                    $user->amount = $row['psum'];
                    $user->date = $date;
                    $item = mb_convert_encoding($user->firstname, 'UTF-8') . " " . mb_convert_encoding($user->lastname, 'UTF-8') . " " . mb_convert_encoding('$' . $user->amount, 'UTF-8') . " " . mb_convert_encoding($user->date, 'UTF-8');
                    $users[] = $item;
                } // end while

                file_put_contents('/home/cnausa/public_html/lms/custom/utils/payments.json', json_encode($users));
            } // end if $num > 0
        } // end if $courseid != null
    }

    function get_refund_modal_dialog() {
        $list = "";
        $courses = $this->get_refund_courses();
        $this->get_course_payments();
        $list.="<div id='myModal' class='modal fade' style='height:375px;'>
        <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-header'>                
                <h4 class='modal-title'>Make refund</h4>
                </div>                
                <div class='modal-body' style='height:275px;'>                                
                
                <div class='container-fluid' style='text-align:left;'>
                <span class='span5'>$courses</span>    
                </div>            
                
                <div class='container-fluid' style='text-align:left;'>
                <span class='span5' id='course_payments_span'><input type='text' id='course_payments' style='width:365px;'></span>                    
                </div>       
                
                <div class='container-fluid' style='text-align:left;'>
                <span class='span2'>Refund amount $</span>
                <span class='span2'><input type='text' id='refund_amount' style='width:160px;'></span>                    
                </div>
                
                <div class='container-fluid' style='text-align:left;'>
                <span class='span5' id='refund_err'></span>
                </div>


                </div>
                
                <div class='modal-footer'>
                <span align='center'><button type='button' class='btn btn-primary' data-dismiss='modal' id='cancel'>Cancel</button></span>
                <span align='center'><button type='button' class='btn btn-primary'  id='make_new_refund'>Go</button></span>
                </div>
        </div>
        </div>
        </div>";
        return $list;
    }

    function make_refund($payment, $amount) {
        $payment_arr = explode(" ", $payment);
        $firstname = $payment_arr[0];
        $lastname = $payment_arr[1];
        $data = $lastname . " " . $firstname;
        $userid = $this->get_userid_by_fio($data);
        $fullamount = str_replace('$', '', $payment_arr[2]);
        $date = $payment_arr[3];
        $query = "select * from mdl_card_payments "
                . "where userid=$userid "
                . "and psum='$fullamount' "
                . "and FROM_UNIXTIME(pdate, '%m-%d-%Y')='$date'";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $paymentid = $row['id'];
            $card_last_four = $row['card_last_four'];
            $exp_date = $row['exp_date'];
            $db_amount = $row['psum'];
            $trans_id = $row['trans_id'];
            $courseid = $row['courseid'];
        } // ebd while
        $pr = new ProcessPayment();
        $status = $pr->makeRefund($amount, $card_last_four, $exp_date, $trans_id);
        //$status = true; // temp workarount for testing
        if ($status == true) {
            if ($amount == $db_amount) {
                $query = "update mdl_card_payments set refunded=1 "
                        . "where id=$paymentid";
                //echo "Query0: ".$query."<br>";
                $this->db->query($query);
            } // end if $amount == $db_amount             
            else {
                // Update card payments amount
                $rest_sum = $db_amount - $amount;
                $query = "update mdl_card_payments set psum='$rest_sum' "
                        . "where id=$paymentid";
                $this->db->query($query);
                // Add data to partial refunds table
                $date = time();
                $query = "INSERT INTO mdl_partial_refund_payments "
                        . "(userid,"
                        . "courseid,"
                        . "psum,"
                        . "refund_date) "
                        . "values ($userid,$courseid,'$amount','$date')";
                $this->db->query($query);
            } // end else when it was partial refund
            return true;
        } // end if $status==true
        else {
            return false;
        }
    }

    function get_update_refund_pwd_page() {
        $list = "";

        if ($this->session->justloggedin == 1) {
            $list.="<div class='container-fluid' style='text-align:center;'>";
            $list.="<div class='container-fluid'>";
            $list.="<span class='span3'>Old password*:</span><span class='span3'><input type='password' id='old_pwd' style=''></span>";
            $list.="</div>";

            $list.="<div class='container-fluid'>";
            $list.="<span class='span3'>New password*:</span><span class='span3'><input type='password' id='new_pwd1' style=''></span>";
            $list.="</div>";

            $list.="<div class='container-fluid'>";
            $list.="<span class='span3'>New password again*:</span><span class='span3'><input type='password' id='new_pwd2' style=''></span>";
            $list.="</div>";

            $list.="<div class='container-fluid' style='text-align:center;'>";
            $list.="<span class='span6' style='color:red;' id='pwd_err'></span>";
            $list.="</div>";

            $list.="<div class='container-fluid'>";
            $list.="<span class='span3'>&nbsp;</span><span class='span3'><button type='button' class='btn btn-primary'  id='update_refund_pwd'>Update</button></span>";
            $list.="</div>";

            $list.="</div>";
        } // end if 
        else {
            $list.="<p>You are not authenticated. &nbsp; <a href='https://medical2.com/login'><button class='btn btn-primary' id='relogin'>Login</button></a></p>";
        }

        return $list;
    }

    function update_refund_pwd($pwd) {
        $query = "update mdl_refund_pwd set pwd='$pwd'";
        $this->db->query($query);
        $list = "Password has been updated.";
        return $list;
    }

    function get_old_refund_pwd() {
        $query = "select * from mdl_refund_pwd";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $pwd = $row['pwd'];
        }
        return $pwd;
    }

}
