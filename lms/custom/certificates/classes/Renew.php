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

        //echo "Current date: " . time() . "-" . date('m-d-Y', time()) . "<br>";
        //echo "Expire  date: " . $expire . "-" . date('m-d-Y', $expire) . "<br>";

        $diff = $expire - time();
        if ($diff > 0) {
            $late_fee = 0;
            $delay_days = 0;
        } // end if
        else {
            $delay_days = abs(floor(($expire - time()) / $day_sec));
            foreach ($fees as $fee) {
                if ($delay_days >= $fee->length) {
                      /*  
                      echo "<pre>";
                      print_r($fee);
                      echo "</pre><br>-----------------------------<br>";
                      */
                      
                      $late_fee = $fee->amount;
                    
                } // end if
            } // end foreach
        } // end else
        //echo "Delay days: " . $delay_days . "<br>";

        return $late_fee;
    }

}
