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

    function get_instructor_course_workshops($courseid, $userid) {
        $slots = array();
        $query = "select * from mdl_scheduler where course=$courseid";
        //echo "Query: " . $query . "<br>";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $schedulerid = $row['id'];
        }

        if ($schedulerid > 0) {
            $query = "select * from mdl_scheduler_slots "
                    . "where schedulerid=$schedulerid";
            //echo "Query: " . $query . "<br>";
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $slots_arr[] = $row['id'];
            }

            if (count($slots_arr) > 0) {
                $slots_list = implode(',', $slots_arr);
                $query = "select * from mdl_scheduler_appointment "
                        . "where studentid=$userid and slotid in ($slots_list)";
                //echo "Query: " . $query . "<br>";
                $num = $this->db->numrows($query);
                echo "Slots num: ".$num."<br>";
                if ($num > 0) {
                    $result = $this->db->query($query);
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        $slots[] = $row['slotid'];
                    } // end while
                } // end if $num > 0
            } // end if count($slots_arr) > 0) 
        } // end if $schedulerid>0

        return $slots;
    }

    function get_intructors_workshops($userid) {
        $workshops = array();
        $query = "select * from mdl_role_assignments "
                . "where userid=$userid and roleid=3";
        //echo "Query: " . $query . "<br>";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

            /*
              echo "<pre>";
              print_r($row);
              echo "</pre>";
             */

            $courseid = $this->get_instance_id_by_context($row['contextid']);
            $coursews = $this->get_instructor_course_workshops($courseid, $userid);
            $ws = new stdClass();
            $ws->courseid = $courseid;
            $ws->coursews = $coursews;
            $workshops[] = $ws;
        }
        return $workshops;
    }

    function get_workshop_detailes($slotid) {
        $query = "select * from mdl_scheduler_slots where id=$slotid";
        echo "Query: " . $query . "<br>";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $ws = new stdClass();
            foreach ($row as $key => $value) {
                $ws->$key = $value;
            } // end foreach
        } // end while
        return $ws;
    }

    function get_workshops_block($courseid, $workshops) {
        $list = "";
        echo "<pre>";
        print_r($workshops);
        echo "</pre>";
        if (count($workshops) > 0) {
            $coursename = $this->get_course_name($courseid);
            foreach ($workshops as $slotid) {
                $ws = $this->get_workshop_detailes($slotid);
                $date = date('m-d-Y', $ws->starttime);
                $list.="<div class='container-fluid'>";
                $list.="<span class='2'>$coursename</span>";
                $list.="<span class='2'>$date</span>";
                $list.="<span class='2'>$ws->appointmentlocation</span>";
                $list.="</div>";
            } // end foreach
        } // end if count($workshops) > 0

        return $list;
    }

    function create_instructors_page($instructors) {
        $list = "";

        /*
          echo "<pre>";
          print_r($instructors);
          echo "</pre>";
         */

        if (count($instructors) > 0) {
            foreach ($instructors as $in) {
                $user = $this->get_user_details($in->userid);
                $instructor_workshops = $this->get_intructors_workshops($in->userid);
                
                echo "<pre>";
                print_r($instructor_workshops);
                echo "</pre>";
                
                $worshop_block = $this->get_workshops_block($instructor_workshops->courseid, $instructor_workshops->coursews);
                $list.="<div class='container-fluid'>";
                $list.="<span class='span6'>$user->firstname $user->lastname $user->email</span>";
                $list.="<span class='span6'>$worshop_block</span>";
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
