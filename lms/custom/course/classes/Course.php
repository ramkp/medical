<?php

/**
 * Description of Course
 *
 * @author sirromas
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php';

class Course extends Util {

    function __construct() {
        parent::__construct();
    }

    function get_course_certificates() {
        $certificates = array();
        date_default_timezone_set('Pacific/Wallis');
        $query = "select * from mdl_certificates order by issue_date ";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $certificate = new stdClass();
            foreach ($row as $key => $value) {
                $certificate->$key = $value;
            } // end foreach
            $certificates[] = $certificate;
        } // end while 
        return $certificates;
    }

    function make_student_course_passed($courseid, $userid, $issued) {
        $query = "select * from mdl_course_completions "
                . "where course=$courseid "
                . "and userid=$userid";
        $num = $this->db->numrows($query);
        if ($num == 0) {
            $query = "insert into mdl_course_completions "
                    . "(userid,"
                    . "course,"
                    . "timeenrolled,"
                    . "timecompleted) "
                    . "values ($userid,"
                    . "$courseid,"
                    . "'$issued',"
                    . "'$issued')";
            $this->db->query($query);
            return true;
        } // end if $num==0
        else {
            return false;
        }
    }

    function process_course_compleations() {
        $list = "";
        $certificates = $this->get_course_certificates();
        if (count($certificates) > 0) {
            $list.="<table align='left'>";
            $list.="<tr>";
            $list.="<th align='left'>Firstname</th><th align='left'>Lastname</th align='left'><th align='left'>Program applied</th><th align='left'>Issue date</th><th>Expiration date</th>";
            $list.="</tr>";

            foreach ($certificates as $certificate) {
                $userdata = $this->get_user_details($certificate->userid);
                $coursename = $this->get_course_name($certificate->courseid);
                $issue_date = date('d-m-Y', $certificate->issue_date);
                $exp_date = date('d-m-Y', $certificate->expiration_date);
                $list.="<tr>";
                $list.="<td align='left'>$userdata->firstname</td><td align='left'>$userdata->lastname</td><td align='left'>$coursename</td><td align='left'>$issue_date</td><td align='left'>$exp_date</td>";
                $list.="</tr>";
                $added = $this->make_student_course_passed($certificate->courseid, $certificate->userid, $certificate->issue_date);
                if ($added == true) {
                    $list.="<tr><td>$userdata->firstname</td><td>$userdata->lastname</td><td>has been added into course completions table</td></tr>";
                } // end if $added==true
                $list.="<tr><td colspan='5'><hr></td></tr>";
            } // end foreach
            $list.= "<tr><td colspan='5' align='left' style='text-weight:bold;'>Total Certificates: " . count($certificates) . "</td></tr>";
            $list.="</table>";
        } // end if count($certificates)>0
        return $list;
    }

}
