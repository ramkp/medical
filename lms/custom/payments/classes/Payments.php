<?php

/**
 * Description of Payments
 *
 * @author sirromas
 */
require_once ($_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php');

class Payments extends Util {

    public $type;
    public $typeid;
    public $limit = 3;

    function get_invoice_payments($payment_type) {
        $invoice_payments = array();
        $query = "select * from mdl_invoice "
                . "where i_status=1 and i_ptype=$payment_type limit 0, $this->limit";
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
                $list.="<span class='span1'>Payment</span><span class='span5'>Invoice # $payment->i_num for $$payment->i_sum payment date $date</span>";
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
        $query = "select * from mdl_invoice where i_status=1 and i_ptype=$typeid LIMIT $offset, $rec_limit";
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

}
