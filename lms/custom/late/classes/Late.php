<?php

/**
 * Description of Late
 *
 * @author sirromas
 */
require_once ($_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php');

class Late extends Util {

    function get_edit_page() {
        $fees = array();
        $list = "";
        $query = "select * from mdl_late_fee order by courseid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $fee = new stdClass();
            foreach ($row as $key => $value) {
                $fee->$key = $value;
            } // end foreach
            $fees[] = $fee;
        } // end while
        $list.="<div class='container-fluid' style='font-weight:bold;'>";
        $list.="<span class='span4'>Program</span>";
        $list.="<span class='span2'>Fee delay (days)</span>";
        $list.="<span class='span2'>Fee amount ($)</span>";
        $list.="<span class='span2'>Actions</span>";
        $list.="</div>";
        foreach ($fees as $feeobj) {
            $coursename = $this->get_course_name($feeobj->courseid);
            $list.="<div class='container-fluid'>";
            $list.="<span class='span4'>$coursename</span>";
            $list.="<span class='span2'><input type='text' value='$feeobj->fee_delay' id='fee_delay_$feeobj->courseid' style='width:75px;'></span>";
            $list.="<span class='span2'><input type='text' value='$feeobj->fee_amount' id='fee_amount_$feeobj->courseid' style='width:75px;'></span>";
            $list.="<span class='span2'><button type='button' id='update_late_$feeobj->courseid' class='btn btn-primary'>Save</button></span>";
            $list.="</div>";
            $list.="<div class='container-fluid'>";
            $list.="<span class='span9' id='late_err_$feeobj->courseid' style='color:red;'></span>";
            $list.="</div>";
            $list.="<div class='container-fluid'>";
            $list.="<span class='span9'><hr/></span>";
            $list.="</div>";
        } // end foreach
        return $list;
    }

    function save_changes($period, $amount, $courseid) {
        $list = "";
        $query = "update mdl_late_fee "
                . "set fee_delay=$period, "
                . "fee_amount='$amount' where courseid=$courseid";
        $this->db->query($query);
        $list.="<p align='center'>Data successfully saved. </p>";
        return $list;
    }

    function import_courses() {
        $query = "select id from mdl_course where category>0 and visible=1";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $query2 = "insert into mdl_late_fee
            	(courseid,fee_delay,fee_amount) 
            	values(" . $row['id'] . ",7,'25')";
            $this->db->query($query2);
            echo "Course with id " . $row['id'] . " was updated with late fee ....<br>";
        } // end while 
    }

}
