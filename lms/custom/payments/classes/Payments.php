<?php

/**
 * Description of Payments
 *
 * @author sirromas
 */
require_once ($_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/lms/custom/invoices/classes/Invoice.php');

class Payments extends Util {

    public $type;
    public $typeid;
    public $limit = 3;

    function get_invoice_payments($payment_type) {
        $invoice_payments = array();
        $query = "select * from mdl_invoice "
                . "where i_status=1 and "
                . "i_ptype=$payment_type limit 0, $this->limit";
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
        $list = $this->get_invoice_payments_page($invoice_payments);
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

    function get_invoice_payments_page($invoice_payments, $toolbar = true) {
        $list = "";
        if (count($invoice_payments) > 0) {
            $list.="<div id='payment_container'>";
            foreach ($invoice_payments as $payment) {
                $user = $this->get_user_details($payment->userid);
                $course = $this->get_course_name($payment->courseid);
                $date = date('Y-m-d', $payment->i_pdate);
                $list.="<div class='container-fluid'>";
                $list.="<span class='span1'>User</span><span class='span3'>$user->firstname $user->lastname</span>";
                $list.="</div>";
                $list.="<div class='container-fluid'>";
                $list.="<span class='span1'>Program</span><span class='span3'>$course</span>";
                $list.="</div>";
                $list.="<div class='container-fluid'>";
                $list.="<span class='span1'>Payment</span><span class='span7'>Invoice # $payment->i_num for $$payment->i_sum payment date $date</span>";
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

    function get_total_payments() {
        $query = "select * from mdl_invoice "
                . "where i_status=1 and i_ptype=$this->typeid";
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
                . "i_ptype=$typeid LIMIT $offset, $rec_limit";
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
        $list = $this->create_payment_log_page($payments);
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
                $date = date('Y-m-d', $invoice_detailes->i_date);
                $list.="<div class='container-fluid'>";
                $list.="<span class='span2'>User</span><span class='span3'>$user->firstname &nbsp $user->lastname</span>";
                $list.="</div>";
                $list.="<div class='container-fluid'>";
                $list.="<span class='span2'>Program</span><span class='span3'>$course </span>";
                $list.="</div>";
                $list.="<div class='container-fluid'>";
                $list.="<span class='span2'>Invoice</span><span class='span5'>Invoice # $invoice_detailes->i_num for $$invoice_detailes->i_sum &nbsp; from $date</span>";
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
        $payments = array();
        $query = "select * "
                . "from mdl_card_payments "
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
        $list = $this->create_card_payments_page($payments);
        return $list;
    }

    function create_card_payments_page($payments, $toolbar = true) {
        $list = "";
        if (count($payments) > 0) {
            $list.="<div id='card_payments_container'>";
            foreach ($payments as $payment) {
                $user = $this->get_user_details($payment->userid);
                $course = $this->get_course_name($payment->courseid);
                $date = date('Y-m-d', $payment->pdate);
                $list.="<div class='container-fluid'>";
                $list.="<span class='span2'>User</span><span class='span3'>$user->firstname &nbsp $user->lastname</span>";
                $list.="</div>";
                $list.="<div class='container-fluid'>";
                $list.="<span class='span2'>Program</span><span class='span3'>$course </span>";
                $list.="</div>";
                $list.="<div class='container-fluid'>";
                $list.="<span class='span2'>Paid sum</span><span class='span3'>$$payment->psum from $date</span>";
                $list.="</div>";
                $list.="<div class='container-fluid'>";
                $list.="<span class='span5'><hr/></span>";
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
        $query = "select * from mdl_card_payments "
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

    function get_renew_fee_page() {
        $list = "";
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
        return $list;
    }
    
    function update_renew_fee ($fee) {
        $query="update mdl_renew_fee set fee_sum='$fee' where id=1";
        $this->db->query($query);
        $list="Item successfully updated";
        return $list;
    }

}
