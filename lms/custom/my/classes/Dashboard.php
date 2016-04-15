<?php

/**
 * Description of Dashboard
 *
 * @author sirromas
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/Payment.php';

class Dashboard extends Util {

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
        $roleid = $this->get_user_role($this->user->id);
        if ($roleid == 5) {
            $status = $this->is_user_paid();
        } // end if $roleid == 5
        return $status;
    }

    function get_user_warning_message() {
        $list = "";        
        $userid = $this->user->id;
        $list.="<div class='container-fluid'>";
        $list.="<span class='span12'>Your account is not active because we did not receive payment from you. Please <a href='http://".$_SERVER['SERVER_NAME']."/index.php/payments/index/$userid' target='_blank'>click</a> here to pay by card. </span>";
        $list.="</div>";                
        $list.="<div class='container-fluid'>";
        $list.="<span class='span12' >If you need help please contact support team.</span>";
        $list.="</div>";
        $list.="<div class='container-fluid'>";
        $list.="<span class='span12' id='logged_user_payment_card'></span>";
        $list.="</div>";
        return $list;
    }

}
