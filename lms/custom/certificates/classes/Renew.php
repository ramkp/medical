<?php

/**
 * Description of Renew
 *
 * @author moyo
 */
require_once ('/home/cnausa/public_html/lms/class.pdo.database.php');

class Renew {

    public $db;

    function __construct() {
        $this->db = new pdo_db();
    }

    function get_renew_amount($courseid) {
        $query = "select * from mdl_renew_amount where courseid=$courseid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $amount = $row['amount'];
        }
        return $amount;
    }

    function get_renew_late_fee($courseid, $expire) {
        $day_sec = 86400;
        $query = "select * from mdl_renew_late_fees where courseid=$courseid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $fee = new stdClass();
            foreach ($row as $key => $value) {
                $fee->$key = $value;
            }
            $fees[] = $fee;
        }

        $delay_days = abs(round(($expire - time()) / $day_sec));

        foreach ($fees as $fee) {
            if ($delay_days >= $fee->length) {
                $late_fee = $fee->amount;
            }
        }
        return $late_fee;
    }

}
