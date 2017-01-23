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

    function get_student_payments($courseid, $userid) {

        $totalpaid = 0;
        // 1. Check credit card payments
        // 2. Check cash payments
        // 3. Check free payments
        // 4. Check invoice payments

        $query = "select * from mdl_card_payments "
                . "where courseid=$courseid "
                . "and userid=$userid "
                . "and refunded=0";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $totalpaid = $totalpaid + $row['psum'];
            }
        }

        $query = "select * from mdl_partial_payments "
                . "where courseid=$courseid "
                . "and userid=$userid";
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $totalpaid = $totalpaid + $row['psum'];
            }
        }

        $query = "select * from mdl_free "
                . "where courseid=$courseid "
                . "and userid=$userid";
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $totalpaid = $totalpaid + $row['psum'];
            }
        }

        $query = "select * from mdl_invoice "
                . "where courseid=$courseid "
                . "and userid=$userid and i_status=1";
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $totalpaid = $totalpaid + $row['i_sum'];
            }
        }

        return $totalpaid;
    }

    function get_item_cost($courseid, $slotid) {
        if ($slotid == null) {
            $itemcost = $this->get_course_cost($courseid);
        } // end if
        else {
            $coursecost = $this->get_course_cost($courseid);
            $wscost = $this->get_workshop_cost($slotid);
            $itemcost = ($wscost > 0) ? $wscost : $coursecost;
        } // end else
        return $itemcost;
    }

    function get_user_balance($courseid, $userid, $slotid = null) {
        $itemcost = $this->get_item_cost($courseid, $slotid);
        $totalpaid = $this->get_student_payments($courseid, $userid);
        $balance = $itemcost - $totalpaid;
        $balance_due = ($balance >= 0) ? $balance : 0;
        return $balance_due;
    }

    function get_user_balance_with_promo($courseid, $userid, $slotid = null, $code = null) {
        
    }

}
