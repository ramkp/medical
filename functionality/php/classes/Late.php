<?php

/**
 * Description of Late
 *
 * @author sirromas
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/class.pdo.database.php';

class Late {

    public $db;

    function __construct() {
        $this->db = new pdo_db();
    }

    function is_course_scheduled($courseid) {
        $status = 0;
        $query = "select * from mdl_scheduler where course=$courseid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $schedulerid = $row['id'];
            } // end while

            $query = "select * from mdl_scheduler_slots where schedulerid=$schedulerid";
            $num = $this->db->numrows($query);
            $status = $num;
        } // end if $num > 0
        return $status;
    }

    function get_course_start_date($slotid) {
        $query = "select * from mdl_scheduler_slots where id=$slotid";
        //echo "Query: ".$query."<br>";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $start = $row['starttime'];
        }
        return $start;
    }

    function get_signup_delay_period() {
        $query = "select * from mdl_late_fee where id=1";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $delay_period = $row['fee_delay'] * 86400;
        }
        return $delay_period;
    }

    function get_delay_fee() {
        $query = "select * from mdl_late_fee where id=1";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $fee = $row['fee_amount'];
        }
        return $fee;
    }

    function is_apply_delay_fee($courseid, $slotid) {
        $apply = false;
        $signup_date = time();
        $course_scheduled = $this->is_course_scheduled($courseid);
        if ($course_scheduled > 0) {
            $course_start = $this->get_course_start_date($slotid);
            $delay_period = $this->get_signup_delay_period();
            $late_date = $course_start - $delay_period;
            if ($signup_date > $late_date) {
                $apply = true;
            }  // end if $signup_date > $late_date
            else {
                $apply = false;
            } // end else 
        } // end if $course_scheduled > 0
        else {
            $apply = false;
        }
        return $apply;
    }

}
