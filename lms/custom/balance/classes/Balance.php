<?php

require_once ('/home/cnausa/public_html/lms/class.pdo.database.php');

/**
 * Description of Balance
 *
 * @author moyo
 */
class Balance {

    public $db;

    function __construct() {

        $db = new pdo_db();
        $this->db = $db;
    }

    function get_course_cost($courseid) {
        $query = "select * from mdl_course where id=$courseid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $cost = $row['cost'];
        }
        return $cost;
    }

    function get_workshop_cost($slotid) {
        $query = "select * from mdl_scheduler_slots where id=$slotid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $cost = $row['cost'];
        }
        return $cost;
    }

    function get_user_promo_code_discount($courseid, $userid) {
        $list = "";
        $query = "select * from mdl_card_payments "
                . "where userid=$userid and courseid=$courseid "
                . "and refunded=0 "
                . "and promo_code<>''";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $code = $row['promo_code'];
            } // end while
            $query = "select * from mdl_code where code='$code'";
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $type = $row['type'];
                $amount = $row['amount'];
            } // end while
            $list.="<div class='row-fluid'>";
            if ($type == 'amount') {
                $list.="<span class='span6'>$$amount off</span>";
            } // end if $type=='amount
            else {
                $list.="<span class='span6'>$amount% off</span>";
            }
            $list.="</div>";
        } // end if $num > 0
        return $list;
    }

    function get_student_payments($courseid, $userid) {

        // 1. Check credit card payments
        // 2. Check cash payments
        // 3. Check free payments
        // 4. Check invoice payments

        $card_payments = 0;
        $cash_payments = 0;
        $free_payments = 0;
        $invoice_payments = 0;

        $query = "select * from mdl_card_payments "
                . "where courseid=$courseid "
                . "and userid=$userid "
                . "and refunded=0";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $card_payments = $card_payments + $row['psum'];
            }
        }

        // ------------------------------------------------------------- //

        $query = "select * from mdl_partial_payments "
                . "where courseid=$courseid "
                . "and userid=$userid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $cash_payments = $cash_payments + $row['psum'];
            }
        }

        // ------------------------------------------------------------- //

        $query = "select * from mdl_free "
                . "where courseid=$courseid "
                . "and userid=$userid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $free_payments = $free_payments + $row['psum'];
            }
        }

        // ------------------------------------------------------------- //

        $query = "select * from mdl_invoice "
                . "where courseid=$courseid "
                . "and userid=$userid and i_status=1";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $invoice_payments = $invoice_payments + $row['i_sum'];
            }
        }

        $totalpaid = $card_payments + $cash_payments + $free_payments + $invoice_payments;
        return $totalpaid;
    }

    function get_item_cost($courseid, $userid, $slotid) {
        $promo_courses = $this->get_user_promo_paid_courses($userid);
        if (!in_array($courseid, $promo_courses)) {
            if ($slotid == null) {
                $itemcost = $this->get_course_cost($courseid);
            } // end if
            else {
                $coursecost = $this->get_course_cost($courseid);
                $wscost = $this->get_workshop_cost($slotid);
                $itemcost = ($wscost > 0) ? $wscost : $coursecost;
            } // end else
        } // end if !in_array($courseid, $promo_courses)
        else {
            $itemcost = $this->get_promo_course_cost($courseid, $userid);
        } // end else
        return $itemcost;
    }

    function get_promo_course_cost($courseid, $userid) {
        $query = "select * from mdl_card_payments "
                . "where userid=$userid and courseid=$courseid "
                . "and refunded=0 "
                . "and promo_code<>''";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $code = $row['promo_code'];
        } // end while

        $query = "select * from mdl_course where id=$courseid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $oldprice = $row['cost'];
        }

        $query = "select * from mdl_code where code='$code'";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $type = $row['type'];
            $amount = $row['amount'];
        } // end while

        if ($type == 'amount') {
            $newprice = $oldprice - $amount;
        } // end if
        else {
            $newprice = $oldprice - ($oldprice * $amount) / 100;
        } // end else
        return $newprice;
    }

    function get_user_balance($courseid, $userid, $slotid = null) {
        $promo_courses = $this->get_user_promo_paid_courses($userid);
        if (!in_array($courseid, $promo_courses)) {
            $itemcost = $this->get_item_cost($courseid, $slotid);
        } // end if 
        else {
            $itemcost = $this->get_promo_course_cost($courseid, $userid);
        } // end else
        $totalpaid = $this->get_student_payments($courseid, $userid);
        $balance = $itemcost - $totalpaid;
        $balance_due = ($balance >= 0) ? $balance : 0;
        return $balance_due;
    }

    function get_user_promo_paid_courses($userid) {
        $courses = array();
        $query = "select * from mdl_card_payments "
                . "where userid=$userid "
                . "and refunded=0 "
                . "and promo_code<>''";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $courses[] = $row['courseid'];
            } // enw while
        } // end if $num > 0
        return $courses;
    }

}
