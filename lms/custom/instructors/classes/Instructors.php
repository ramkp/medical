<?php

require_once ('/home/cnausa/public_html/lms/custom/utils/classes/Util.php');

/**
 * Description of Instructors
 *
 * @author moyo
 */
class Instructors extends Util {

    function __construct() {
        parent::__construct();
    }

    function get_instructors_page() {
        $list = "";
        $instructors = array();
        $query = "select * from mdl_role_assignments "
                . "where roleid=3 group by userid";
        //echo "Query:" . $query . "<br>";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $in = new stdClass();
                foreach ($row as $key => $value) {
                    $in->$key = $value;
                } // end foreach
                $instructors[] = $in;
            } // end while
        } // end if $num > 0
        $list.=$this->create_instructors_page($instructors);
        return $list;
    }

    function get_instance_id_by_context($contextid) {
        $query = "select * from mdl_context where id=$contextid";
        //echo "Query: " . $query . "<br>";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $courseid = $row['instanceid'];
        }
        return $courseid;
    }

    function get_instructor_course_workshops($userid) {
        $slots = array();
        $query = "select * from mdl_scheduler_appointment "
                . "where studentid=$userid";
        //echo "Query: ".$query."<br>";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $slots[] = $row['slotid'];
            } // end while
        } // end if $num > 0
        return $slots;
    }

    function get_intructors_workshops($userid) {
        $coursews = $this->get_instructor_course_workshops($userid);
        $ws = new stdClass();
        $ws->courseid = 0;
        $ws->coursews = $coursews;
        return $ws;
    }

    function get_workshop_detailes($slotid) {
        $query = "select * from mdl_scheduler_slots where id=$slotid";
        //echo "Query: " . $query . "<br>";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $ws = new stdClass();
            foreach ($row as $key => $value) {
                $ws->$key = $value;
            } // end foreach
        } // end while
        return $ws;
    }

    function get_courseid_by_slot($slotid) {
        $query = "select * from mdl_scheduler_slots where id=$slotid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $schedulerid = $row['schedulerid'];
        }

        $query = "select * from mdl_scheduler where id=$schedulerid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $courseid = $row['course'];
        }
        return $courseid;
    }

    function get_workshops_block($workshops) {
        $list = "";

        /*
          echo "<pre>";
          print_r($workshops);
          echo "</pre>";
         */

        if (count($workshops) > 0) {
            foreach ($workshops as $slotid) {
                $courseid = $this->get_courseid_by_slot($slotid);
                $coursename = $this->get_course_name($courseid);
                $ws = $this->get_workshop_detailes($slotid);
                $date = date('m-d-Y', $ws->starttime);
                $list.="<div class='container-fluid'>";
                $list.="<span class='span5'>$coursename</span>";
                $list.="<span class='span2'>$date</span>";
                $list.="<span class='span3'>$ws->appointmentlocation</span>";
                $list.="</div>";
            } // end foreach
        } // end if count($workshops) > 0

        return $list;
    }

    function is_course_disabled($courseid) {
        $query = "select * from mdl_course where id=$courseid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $visible = $row['visible'];
        }
        return $visible;
    }

    function get_instructor_courses_block($userid) {
        $list = "";
        $query = "select * from mdl_role_assignments "
                . "where userid=$userid and roleid=3";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $courseid = $this->get_instance_id_by_context($row['contextid']);
                $enabled = $this->is_course_disabled($courseid);
                if ($enabled == 1) {
                    $coursename = $this->get_course_name($courseid);
                    $list.="<div class='row-fluid'>";
                    $list.="<span class='span10'><a href='https://" . $_SERVER['SERVER_NAME'] . "/lms/course/view.php?id=$courseid' target='_blank'>$coursename</a></span>";
                    $list.="</div>";
                } // end if $enabled == 1)
            } // end while
        } // end if $num > 0
        else {
            $list.="<div class='row-fluid'>";
            $list.="<span class='span6'>N/A</span>";
            $list.="</div>";
        } // end else
        return $list;
    }

    function create_instructors_page($instructors) {
        $list = "";

        if (count($instructors) > 0) {
            $list.="<div class='row-fluid' style='font-weight:bold;'>";
            $list.="<span class='span12'>Please be aware all instructors should have Teacher role at the specific course</span>";
            $list.="</div><br>";
            $list.="<div class='row-flluid' style='font-weight:bold;'>";
            $list.="<span class='span6' style='padding-left:20px;'>Instructor name</span>";
            $list.="<span class='span6'>Instructor's courses</span>";
            $list.="</div>";
            foreach ($instructors as $in) {
                $user = $this->get_user_details($in->userid);
                $courses_block = $this->get_instructor_courses_block($in->userid);
                $list.="<div class='container-fluid'>";
                $list.="<span class='span6'><a href='https://" . $_SERVER['SERVER_NAME'] . "/lms/user/profile.php?id=$in->userid' target='_blank'>$user->firstname $user->lastname $user->email</a></span>";
                $list.="<span class='span6'>$courses_block</span>";
                $list.="</div>";
                $list.="<div class='container-fluid'>";
                $list.="<span class='span12'><hr/></span>";
                $list.="</div>";
            } // end foreach
        } // end count($instructors)>0
        else {
            $list.="<div class='container-fluid'>";
            $list.="<span class='span9'>There are no instructors in the system</span>";
            $list.="</div>";
        }

        return $list;
    }

}
