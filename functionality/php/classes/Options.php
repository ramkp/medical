<?php

/**
 * Description of Options
 *
 * @author sirromas
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/class.pdo.database.php';

class Options {

    public $db;

    function __construct() {
        $this->db = new pdo_db();
    }

    function get_payment_options($courseid, $group = null) {
        $query = "select installment, num_payments "
                . "from mdl_course "
                . "where id=$courseid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $inst = $row['installment'];
            $num_payments = $row['num_payments'];
        }
        $data = array('id' => $courseid, 'inst' => $inst, 'num_payments' => $num_payments, 'group' => $group);
        $list = $this->create_payment_options_block($data);
        return $list;
    }

    function get_course_enrollment_period($courseid) {
        $day = 86400; // day secs num
        $query = "select enrol, courseid, enrolperiod, roleid "
                . "from mdl_course "
                . "where courseid=$courseid and enrol='manual' and roleid=5";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $period = round($row['enrolperiod'] / $day);
            }
        } // end if $num>0
        else {
            $period = 90; // default period 90 days
        }
        return $period;
    }

    function create_payment_options_block($data) {
        $list = "";
        $courseid = $data['id'];
        $installment = $data['inst'];
        $num_payments = $data['num_payments'];
        $group = $data['group'];
        $list.="<div class='panel panel-default' id='program_section' style='margin-bottom:0px;'>";
        $list.="<div class='panel-heading' style='text-align:left;'><h5 class='panel-title'>Payment Options</h5></div>";
        $list.="<div class='panel-body'>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'><input type='radio' name='online_personal' id='online_personal' value='online_personal' checked >Online payment</span>";
        $list.="<span class='span2'><input type='radio' name='offline_personal' id='offline_personal' value='offline_personal'>Offline payment</span>";
        if ($installment == 1) {
            $enroll_period = $this->get_course_enrollment_period($courseid);
            $list.="<span class='span5'><input type='radio' name='online_personal_installment' id='online_personal_installment' value='online_personal_installment'>Online installment payment for $enroll_period day(s) with $num_payments of equal payemnt(s)</span>";
        }
        $list.="<span class='span2' id='cat_course'>$courses</span>";
        $list.="<span class='span2' id='program_err' style='color:red;'></span>";
        $list.="</div>"; // end of container-fluid

        if ($group != null) {
            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span2'><input type='radio'  id='online_whole_group_payment'   value='online_whole_group_payment' checked>Online whole group payment</span>";
            $list.="<span class='span2'><input type='radio'  id='online_group_members_payment' value='online_group_members_payment'>Online group members payment</span>";
            $list.="<span class='span2'><input type='radio'  id='offline_whole_group_payment'  value='offline_whole_group_payment'>Offline payment</span>";
            if ($installment == 1) {
                $enroll_period = $this->get_course_enrollment_period($courseid);
                $list.="<span class='span5'><input type='radio' name='online_group_installment' id='online_group_installment' value='online_group_installment'>Online installment payment for $enroll_period day(s) with $num_payments of equal payemnt(s)</span>";
            }
            $list.="<span class='span2' id='cat_course'>$courses</span>";
            $list.="<span class='span2' id='program_err' style='color:red;'></span>";
            $list.="</div>"; // end of container-fluid
        }


        $list.="</div>"; // end of panel-body
        $list.="</div>"; // end of panel panel-default
        return $list;
    }

}
