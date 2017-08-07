<?php

require_once ($_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php');

class Wsdata extends Util {

    function __construct() {
        parent::__construct();
    }

    function get_programs_list() {
        $list = "";
        $list.="<select id='wslist' style='width:175px;'>";
        $list.="<option value='0' selected>Please select</option>";
        $query = "select c.id, c.category, c.fullname, s.course "
                . "from mdl_course c, mdl_scheduler s "
                . "where c.category>=5 and c.id=s.course";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $list.="<option value='" . $row['id'] . "'>" . $row['fullname'] . "</option>";
            } // end while
        } // end if $num > 0
        $list.="</select>";

        return $list;
    }

    function get_workshop_status_page() {
        $list = "";
        $programs = $this->get_programs_list();
        $list.="<div class='row-fluid'>";
        $list.="<span class='span2'>$programs</span>";
        $list.="<span class='span1'>Start</span><span class='span2'><input type='text' id='date1' style='width:175px;'></span>";
        $list.="<span class='span1'>End</span><span class='span2'><input type='text' id='date2' style='width:175px;'></span>";
        $list.="<span class='span2'><button class='btn btn-primary' id='get_ws_data_btn'>Go</button></span>";
        $list.="</div>";

        $list.="<div class='row-fluid'>";
        $list.="<span class='span12' id='wsdata_err' style='color:red;'></span>";
        $list.="</div>";

        $list.="<div class='row-fluid' id='ajax_loader' style='display:none;text-align:center;'>";
        $list.="<span class='span12'><img src='https://$this->host/assets/img/ajax.gif' /></span>";
        $list.="</div>";

        $list.="<div class='row-fluid' id='ws_data_container'>";

        $list.="</div>";

        return $list;
    }

    function get_schedulerid($courseid) {
        $query = "select * from mdl_scheduler where course=$courseid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $schedulerid = $row['id'];
        }
        return $schedulerid;
    }

    function get_workshops_list($udate1, $udate2, $schedulerid) {
        $items = array();
        $query = "select * from mdl_scheduler_slots "
                . "where schedulerid=$schedulerid "
                . "and starttime between $udate1 and $udate2";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $items[] = $row['id'];
            } // end while
        } // end if $num > 0
        return $items;
    }

    function get_workshop_students($slotid) {
        $users = array();
        $query = "select * from mdl_scheduler_appointment where slotid=$slotid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $users[] = $row['studentid'];
            } // end while
        } // end if $num > 0
        return $users;
    }

    function get_course_enrollment_methods($courseid) {
        $query = "select * from mdl_enrol where courseid=$courseid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $methods[] = $row['id'];
        }
        return $methods;
    }

    function is_user_graduated($courseid, $userid) {
        $query = "select * from mdl_course_completions "
                . "where course=$courseid "
                . "and userid=$userid";
        $num = $this->db->numrows($query);
        return $num;
    }

    function get_open_enrolled_students($courseid, $udate) {
        $users = array();
        $methods = $this->get_course_enrollment_methods($courseid);
        $methods_list = implode(',', $methods);
        $query = "select * from mdl_user_enrolments "
                . "where enrolid in ($methods_list) "
                . "and timestart<=$udate";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $userid = $row['userid'];
                $is_graduated = $this->is_user_graduated($courseid, $userid);
                if ($is_graduated == 0) {
                    $users[] = $userid;
                }
            } // end while
        } // end if $num > 0
        return $users;
    }

    function get_students_enrolled_between_dates($courseid, $udate1, $udate2) {
        $users = array();
        $methods = $this->get_course_enrollment_methods($courseid);
        $methods_list = implode(',', $methods);
        $query = "select * from mdl_user_enrolments "
                . "where enrolid in ($methods_list) "
                . "and timestart between $udate1 and $udate2";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $userid = $row['userid'];
                $userdata = $this->get_user_details($userid);
                if ($userdata->firstname != '' && $userdata->lastname != '') {
                    $users[] = $userid;
                }
            } // end while
        } // end if $num > 0
        return $users;
    }

    function get_students_enrolled_before_some_date($courseid, $udate1) {
        $users = array();
        $methods = $this->get_course_enrollment_methods($courseid);
        $methods_list = implode(',', $methods);
        $query = "select * from mdl_user_enrolments "
                . "where enrolid in ($methods_list) "
                . "and timestart <=$udate1";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $users[] = $row['userid'];
            } // end while
        } // end if $num > 0
        return $users;
    }

    function get_students_graduated_between_dates($courseid, $udate1, $udate2) {
        $users = array();
        $methods = $this->get_course_enrollment_methods($courseid);
        $methods_list = implode(',', $methods);
        $query = "select * from mdl_user_enrolments "
                . "where enrolid in ($methods_list) "
                . "and timestart between $udate1 and $udate2";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $is_graduated = $this->is_user_graduated($courseid, $row['userid']);
                if ($is_graduated > 0) {
                    $users[] = $userid;
                }
            } // end while
        } // end if $num > 0
        return $users;
    }

    function get_user_attendance($courseid, $date1, $date2 = null) {
        if ($date2 == null) {
            $query = "select * from mdl_user_lastaccess "
                    . "where courseid=$courseid "
                    . "and timeaccess<=$date1";
        } // end if 
        else {
            $query = "select * from mdl_user_lastaccess "
                    . "where courseid=$courseid "
                    . "and timeaccess between $date1 and $date2";
        } // end  else
        //echo "Query: ".$query."<br>";
        $num = $this->db->numrows($query);
        return $num;
    }

    function get_full_user_data($courseid, $users, $date1, $date2) {
        $report_users = array();
        foreach ($users as $userid) {
            $userdata = $this->get_user_details($userid);
            if ($userdata->firstname != '' && $userdata->lastname != '') {
                $graduate = $this->is_user_graduated($courseid, $userid);
                $attended = ($graduate > 0) ? 'G' : 'E';
                $userObj = new stdClass();
                $userObj->id = $userid;
                $userObj->url = "https://medical2.com/lms/user/profile.php?id=$userid";
                $userObj->fname = $userdata->firstname;
                $userObj->lname = $userdata->lastname;
                $userObj->attend = $attended;
                $userObj->graduate = $graduate;
                $report_users[] = $userObj;
            } // end if $userdata->firstname != '' && $userdata->lastname != ''
        } // end foreach
        return $report_users;
    }

    function get_workshop_data($data) {
        $list = "";
        $date1 = $data->date1;
        $date2 = $data->date2;
        $udate1 = strtotime($data->date1);
        $udate2 = strtotime($data->date2);
        $courseid = $data->course;
        $coursename = $this->get_course_name($courseid);

        $list.="<div class='row-fluid' style='font-weight:bold;'>";
        $list.="<span class='span12'>$coursename</span>";
        $list.="</div>";

        $list.="<div class='row-fluid' style='font-weight:bold;'>";
        $list.="<span class='span1'>Beginning</span><span class='span1'>$date1</span>";
        $list.="<span class='span1'>Ending</span><span class='span1'></span>$date2</span>";
        $list.="</div>";

        $list.="<div class='row-fluid'>";
        $list.="<span class='span12'><hr/></span>";
        $list.="</div>";

        $students = $this->get_students_enrolled_between_dates($courseid, $udate1, $udate2);
        if (count($students) > 0) {
            $usersdata = $this->get_full_user_data($courseid, $students, $date1, $date2);
            $list.="<table id='myTable' class='display' cellspacing='0' width='100%'>";
            $list.="<thead>";
            $list.="<tr>";
            $list.="<th>Name</th>";
            $list.="<th>Attending</th>";
            $list.="<th>Enrolled</th>";
            $list.="<th>Graduated</th>";
            $list.="</tr>";
            $list.="</thead>";
            $list.="<tbody>";
            foreach ($usersdata as $item) {
                $names = $item->fname . ' ' . $item->lname;
                $url = $item->url;
                $attend = $item->attend;
                $graduate = $item->graduate;
                $list.="<tr>";
                $list.="<td><a href='$url' target='_blank'>$names</td>";
                $list.="<td>$attend</td>";
                $list.="<td>1</td>";
                $list.="<td>$graduate</td>";
                $list.="</tr>";
            } // end foreach 
            $list.="</tbody>";
            $list.="</table>";

            $opened_date1 = count($this->get_open_enrolled_students($courseid, $udate1));
            $opened_date2 = count($this->get_open_enrolled_students($courseid, $udate2));
            $enrolled_between_dates = count($this->get_students_enrolled_between_dates($courseid, $udate1, $udate2));
            $graduated_between_dates = count($this->get_students_graduated_between_dates($courseid, $udate1, $udate2));

            $list.="<div class='row-fluid'>";
            $list.="<span class='span6'>Students openly enrolled on $date1</span><span class='span1'>$opened_date1</span>";
            $list.="</div>";

            $list.="<div class='row-fluid'>";
            $list.="<span class='span6'>Students enrolled between $date1 and $date1</span><span class='span1'>$enrolled_between_dates</span>";
            $list.="</div>";

            $list.="<div class='row-fluid'>";
            $list.="<span class='span6'>Students graduated between  $date1 and $date1</span><span class='span1'>$graduated_between_dates</span>";
            $list.="</div>";

            $list.="<div class='row-fluid'>";
            $list.="<span class='span6'>Students openly enrolled at end of $date2</span><span class='span1'>$opened_date2</span>";
            $list.="</div>";
        } // end if count($students)>0
        else {
            $list.="<div class='row-fluid'>";
            $list.="<span class='span6'>There are no any data found</span>";
            $list.="</div>";
        } // end else

        return $list;
    }

}
