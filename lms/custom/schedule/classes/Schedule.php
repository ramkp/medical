<?php

/**
 * Description of Schedule
 *
 * @author sirromas
 */
require_once ($_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php');

class Schedule extends Util {

    function __construct() {
        parent::__construct();
    }

    function get_course_scheduler($courseid) {
        $schedulerid = 0;
        $query = "select * from mdl_scheduler where course=$courseid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $schedulerid = $row['id'];
            } // end while
        } // end if $num > 0
        return $schedulerid;
    }

    function get_course_slots($toolbar, $schedulerid, $search = null, $start = null, $end = null) {
        date_default_timezone_set('Pacific/Wallis');
        $slots = array();
        $now = time();
        if ($search == null) {
            if ($start == null && $end == null) {
                $query = "select * from mdl_scheduler_slots "
                        . "where schedulerid=$schedulerid "
                        . "and starttime>$now";
            } // end if $start == null && $end == null
            if ($start != null && $end == null) {
                $query = "select * from mdl_scheduler_slots "
                        . "where schedulerid=$schedulerid "
                        . "and starttime>" . strtotime($start) . "";
            } // end if $start!=null && $end==null
            if ($start != null && $end != null) {
                $query = "select * from mdl_scheduler_slots "
                        . "where schedulerid=$schedulerid "
                        . "and starttime>" . strtotime($start) . " "
                        . "and starttime<" . strtotime($end) . "";
            } // end if $start != null && $end != null
        } // end if ($search == null) {
        else {
            if ($start == null && $end == null) {
                $query = "select * from mdl_scheduler_slots "
                        . "where schedulerid=$schedulerid "
                        . "and 	(appointmentlocation like '%$search%' "
                        . "or notes like '%$search%') and starttime>$now";
            } // end if $start == null && $end == null
            if ($start != null && $end == null) {
                $query = "select * from mdl_scheduler_slots "
                        . "where schedulerid=$schedulerid "
                        . "and 	(appointmentlocation like '%$search%' "
                        . "or notes like '%$search%') "
                        . "and starttime>" . strtotime($start) . "";
            } // end if $start!=null && $end==null
            if ($start != null && $end != null) {
                $query = "select * from mdl_scheduler_slots "
                        . "where schedulerid=$schedulerid "
                        . "and starttime>" . strtotime($start) . " "
                        . "and 	(appointmentlocation like '%$search%' "
                        . "or notes like '%$search%') "
                        . "and starttime<" . strtotime($end) . "";
            } // end if $start != null && $end != null
        } // end else
        //echo "Query: " . $query . "<br>";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $slot = new stdClass();
                foreach ($row as $key => $value) {
                    $slot->$key = $value;
                } // end foreach
                $slots[] = $slot;
            } // end whiel
        } // end if $num > 0        
        $list = $this->create_slots_page($slots, $toolbar);
        return $list;
    }

    function get_slot_students($slotid) {
        $apps = array();
        $query = "select * from mdl_scheduler_appointment "
                . "where slotid=$slotid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $app = new stdClass();
                foreach ($row as $key => $value) {
                    $app->$key = $value;
                } // end foreach
                $apps[] = $app;
            } // end while
        } // end if $num > 0
        return $apps;
    }

    function get_student_course_completion_status($courseid, $userid) {
        $query = "select * from mdl_course_completions "
                . "where course=$courseid "
                . "and userid=$userid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $timecompleted = $row['timecompleted'];
            } // end while
            $status = array('passed' => 1, 'completed' => $timecompleted);
        } // end if $num > 0
        else {
            $status = array('passed' => 0, 'completed' => null);
        } // end else
        return $status;
    }

    function create_slots_page($slots, $tools = true) {
        $list = "";
        if ($tools == true) {
            $list.="<div class='panel panel-default'>";
            $list.="<div class='panel-heading'style='text-align:left;'><h5 class='panel-title'>Control Panel</h5></div>";
            $list.="<div class='panel-body'>";
            $list.= "<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span2'><input type='text' id='search' style='width:125px;'></span>";
            $list.="<span class='span2'><button type='button' class='btn btn-primary'  id='search_btn'>Search</button></span>";
            $schedulerid = $this->get_course_scheduler($this->course->id);
            $list.="<span><input type='hidden' id='scheduler' value='$schedulerid'></span>";
            $list.="<span class='span1'>Start</span>";
            $list.="<span class='span2'><input type='text' id='start' style='width:75px;'></span>";
            $list.="<span class='span1'>End</span>";
            $list.="<span class='span2'><input type='text' id='end'  style='width:75px;'></span>";
            $list.="<span class='span2'><button type='button' class='btn btn-primary'  id='date_btn'>Go</button></span>";
            $list.="</div>";
            $list.= "<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span2'><a href='#' id='students_all' onClick='return false;'>Select all</a></span>";
            $list.="<span class='span2'><a href='#' id='students_none' onClick='return false;'>Deselct all</a></span>";
            $list.="<span class='span3'><a href=''>Change completion status</a></span>";
            $list.="<span class='span2'><a href=''>Print certificates</a></span>";
            $list.="<span class='span2'><a href=''>Send certificates</a></span>";
            $list.="</div>";
            $list.= "<div class='container-fluid' style='text-align:center;'>";
            $list.="<span class='span12' id='ajax_loading' style='display:none;'><img src='https://medical2.com/assets/img/ajax.gif' /></div>";
            $list.="</div>";
            $list.="</div>";
        } // end if $tools == true

        if (count($slots) > 0) {
            $list.="<div id='schedule_container'>";
            foreach ($slots as $slot) {
                //echo "<br>--------------------------<br>";
                //print_r($slot);
                //echo "<br>--------------------------<br>";
                $addr_array = explode("/", $slot->appointmentlocation);
                $addr_block = $addr_array[1] . " , " . $addr_array[0];
                $list.="<div class='panel panel-default'>";
                $list.="<div class='panel-heading'style='text-align:left;'><h5 class='panel-title'>$addr_block, " . date('m-d-Y h:i:s', $slot->starttime) . "<br> $slot->notes</h5></div>";
                $list.="<div class='panel-body'>";
                $slot_students = $this->get_slot_students($slot->id);
                if (count($slot_students) > 0) {
                    $list.= "<div class='container-fluid' style='text-align:left;font-weight:bold;'>";
                    $list.="<span class='span1'></span>";
                    $list.="<span class='span3'>Student</span>";
                    $list.="<span class='span4'>Course completion status</span>";
                    $list.="</div>";
                    foreach ($slot_students as $student) {
                        $user_data = $this->get_user_details($student->studentid);
                        $completion_arr = $this->get_student_course_completion_status($this->course->id, $student->studentid);
                        if ($completion_arr['passed'] == 1) {
                            $status = "Passed " . date('m-d-Y', completed) . "";
                        } // end if $completion_arr['passed']==1
                        else {
                            $status = "Student did not pass the workshop";
                        } // end else
                        $list.= "<div class='container-fluid' style='text-align:left;'>";
                        $list.="<span class='span1'><input type='checkbox' class='students' name='studentid' value='$student->studentid'></span>";
                        $list.="<span class='span3'><a href='https://medical2.com/lms/user/profile.php?id=$student->studentid'  target='_blank'>$user_data->firstname $user_data->lastname</a></span>";
                        $list.="<span class='span4'>$status</span>";
                        $list.="</div>";
                    } // end foreach
                } // end if count($slot_students)>0
                else {
                    $list.= "<div class='container-fluid' style='text-align:left;'>";
                    $list.="<span class='span8'>There are no students at this workshop</span>";
                    $list.="</div>";
                }
                $list.="</div>";
                $list.="</div>";
            } // end foreeach
            $list.="</div>";
        } // end if count($slots)>0
        else {
            $list.= "<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span8'>There are no scheduled workshops</span>";
            $list.="</div>";
        }
        //echo "List: ".$list."<br>";
        return $list;
    }

    function get_slots_by_date($schedulerid, $start, $end) {
        $list = "";
        $list.= $this->get_course_slots(false, $schedulerid, null, $start, $end);
        return $list;
    }

    function search_slot($schedulerid, $search) {
        $list = "";
        //echo "Scheduler: ".$schedulerid."<br>";
        //echo "Search: ".$search."<br>";
        $list.= $this->get_course_slots(false, $schedulerid, $search, null, null);
        return $list;
    }

}
