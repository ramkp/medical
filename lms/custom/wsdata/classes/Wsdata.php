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

    function update_user_status($courseid, $userid, $status) {
        $startdate = $this->get_student_start_date($courseid, $userid);
        $exists = $this->is_demographic_record_exists($userid);
        if ($exists > 0) {
            $query = "update mdl_demographic "
                    . "set startdate='$startdate', "
                    . "school_status='$status' "
                    . "where userid=$userid";
        } // end if 
        else {
            $query = "insert into mdl_demographic "
                    . "(userid, startdate, school_status) "
                    . "values ($userid, '$startdate', '$status')";
        }
        $this->db->query($query);
    }

    function get_certificate_issue_date($courseid, $userid) {
        $date = null;
        $query = "select * from mdl_certificates "
                . "where courseid=$courseid "
                . "and userid=$userid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $date = date('m/d/Y', $row['issue_date']);
            } // end while
        } // end if $num > 0
        return $date;
    }

    function get_course_slots($schedulerid) {
        $query = "select * from mdl_scheduler_slots "
                . "where schedulerid=$schedulerid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $slots[] = $row['id'];
        }
        $slotslist = implode(',', $slots);
        return $slotslist;
    }

    function get_student_start_date($courseid, $userid) {
        $schedulerid = $this->get_schedulerid($courseid);
        $slotslist = $this->get_course_slots($schedulerid);
        $query = "select * from mdl_scheduler_appointment "
                . "where studentid=$userid and slotid in ($slotslist)";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $slotid = $row['slotid'];
        }

        $query = "select * from mdl_scheduler_slots where id=$slotid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $start = date('m/d/Y', $row['starttime']);
        }
        return $start;
    }

    function is_demographic_record_exists($userid) {
        $query = "select * from mdl_demographic where userid=$userid";
        $num = $this->db->numrows($query);
        return $num;
    }

    function update_initial_student_statuses() {
        //1. Get all school courses
        $query = "select * from mdl_course where category>=5";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $courses[] = $row['id'];
        }
        $courseslist = implode(',', $courses);


        //2. Get all school schedulers
        $query = "select * from mdl_scheduler where course in ($courseslist)";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $schedulers[] = $row['id'];
        }
        $schedulerslist = implode(',', $schedulers);

        //3.  Get all school workshops
        $query = "select * from mdl_scheduler_slots "
                . "where schedulerid in ($schedulerslist)";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $workshops[] = $row['id'];
        }
        //4.  Get all school students
        $students = $this->normalize_students_data($workshops);

        //5.  Update all student statuses 
        if (count($students) > 0) {
            foreach ($students as $userid) {
                $userdata = $this->get_user_details($userid);
                $startdate = $this->get_student_start_date($userid);
            }
        } // end if count($students)>0
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

    function get_workshops_list($schedulerid, $udate1, $udate2 = null) {
        $items = array();
        if ($udate2 != null) {
            $query = "select * from mdl_scheduler_slots "
                    . "where schedulerid=$schedulerid "
                    . "and starttime between $udate1 and $udate2";
        } // end if
        else {
            $query = "select * from mdl_scheduler_slots "
                    . "where schedulerid=$schedulerid "
                    . "and starttime<=$udate1";
        } // end else

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
        $query = "select * from mdl_certificates "
                . "where courseid=$courseid "
                . "and userid=$userid";
        $num = $this->db->numrows($query);
        if ($num > 1) {
            $num = 1;
        }
        return $num;
    }

    function get_open_enrolled_students_date1($courseid, $udate) {
        $users = array();
        $schedulerid = $this->get_schedulerid($courseid);
        $workshops = $this->get_workshops_list($schedulerid, $udate);

        $slotstudents = $this->normalize_students_data($workshops);
        //echo "Total students found both graduate and non-graduate (date1) :" . count($slotstudents);
        $grad = 0;
        $total = 0;
        foreach ($slotstudents as $userid) {
            $graduated = $this->is_user_graduated($courseid, $userid);
            if ($graduated == 0) {
                $total++;
                $users[] = $userid;
            } // end if 
            else {
                $grad++;
            }
        } // end foreach
        //echo "<br>Total open enrolled students date1: " . $total . "<br>";
        //echo "Total graduate students date1: " . $grad . "<br>";
        return $users;
    }

    function get_open_enrolled_students_date2($courseid, $udate) {
        $users = array();
        $schedulerid = $this->get_schedulerid($courseid);
        $workshops = $this->get_workshops_list($schedulerid, $udate);

        $slotstudents = $this->normalize_students_data($workshops);
        //echo "Total students found both graduate and non-graduate (date2) :" . count($slotstudents);
        $grad = 0;
        $total = 0;
        foreach ($slotstudents as $userid) {
            $graduated = $this->is_user_graduated($courseid, $userid);
            if ($graduated == 0) {
                $total++;
                $users[] = $userid;
            } // end if 
            else {
                $grad++;
            }
        } // end foreach
        //echo "<br>Total open enrolled students date2 " . $total . "<br>";
        //echo "Total graduate students date2: " . $grad . "<br>";
        return $users;
    }

    function get_students_graduated_between_dates($courseid, $udate1, $udate2) {
        $users = array();
        $schedulerid = $this->get_schedulerid($courseid);
        $workshops = $this->get_workshops_list($schedulerid, $udate1, $udate2);
        $students = $this->normalize_students_data($workshops);
        foreach ($students as $userid) {
            $graduated = $this->is_user_graduated($courseid, $userid);
            if ($graduated > 0) {
                $users[] = $userid;
            } // end if
        } // end foreach
        return $users;
    }

    function get_students_enrolled_between_dates($courseid, $udate1, $udate2) {
        $schedulerid = $this->get_schedulerid($courseid);
        $workshops = $this->get_workshops_list($schedulerid, $udate1, $udate2);
        $students = $this->normalize_students_data($workshops);
        return $students;
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

    function get_user_last_access($courseid, $date1, $date2 = null) {
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
        $num = $this->db->numrows($query);
        return $num;
    }

    function get_student_status($courseid, $userid) {
        $query = "select * from mdl_demographic where userid=$userid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $status = $row['school_status'];
            } // end while
            if ($status == null || $status == '') {
                $graduated = $this->is_user_graduated($courseid, $userid);
                $status = ($graduated > 0) ? 'G' : 'A';
            } // end if $status == null
        } // end if $num > 0
        else {
            $graduated = $this->is_user_graduated($courseid, $userid);
            $status = ($graduated > 0) ? 'G' : 'A';
        } // end else
        return $status;
    }

    function get_full_user_data($courseid, $users) {
        $report_users = array();
        $attend1 = 0;
        $attend2 = 0;
        foreach ($users as $userid) {
            $userdata = $this->get_user_details($userid);
            if ($userdata->firstname != '' && $userdata->lastname != '') {
                $graduate = $this->is_user_graduated($courseid, $userid);
                $status = trim($this->get_student_status($courseid, $userid));
                //echo "User id: " . $userid . "<br>";
                //echo "User status: " . $status . "<br>";
                if ($status == 'A') {
                    //echo "Case A, attend2 should be 1 <br>";
                    $attend1 = 1;
                    $attend2 = 1;
                }
                if ($status == 'G') {
                    $attend1 = 1;
                    $attend2 = 0;
                    //echo "Case G, attend2 should be 0 <br>";
                }
                if ($status != 'A' && $status != 'G') {
                    //echo "Case not A or G, attend2 should be 0 <br>";
                    $attend1 = 0;
                    $attend2 = 0;
                }
                //echo "<br>-------------------------------------------------<br>";
                $userObj = new stdClass();
                $userObj->id = $userid;
                $userObj->url = "https://medical2.com/lms/user/profile.php?id=$userid";
                $userObj->fname = $userdata->firstname;
                $userObj->lname = $userdata->lastname;
                $userObj->attend1 = $attend1;
                $userObj->attend2 = $attend2;
                $userObj->graduate = $graduate;
                $report_users[] = $userObj;
            } // end if $userdata->firstname != '' && $userdata->lastname != ''
        } // end foreach
        return $report_users;
    }

    function normalize_students_data($workshops) {
        $students = array();
        if (count($workshops) > 0) {
            $students = array();
            foreach ($workshops as $slotid) {
                $wsstudents[] = $this->get_workshop_students($slotid);
            } // end foreach

            /*
              echo "<pre>";
              print_r($wsstudents);
              echo "</pre><br>";
             */

            foreach ($wsstudents as $student_arr) {
                foreach ($student_arr as $userid) {
                    $students[] = $userid;
                } // end foreach
            } // end foreach
        } // end if count($workshops) > 0
        return $students;
    }

    function calculate_retention($opened_date1, $enrolled_between_dates, $graduated_between_dates, $opened_date2) {
        $total_attending = $opened_date1 + $enrolled_between_dates - $opened_date2;
        $percentage = ($graduated_between_dates / $total_attending) * 100;
        //echo "Total enrolled: " . $total_attending . "<br>";
        //echo "Toal graduated: " . $graduated_between_dates . "<br>";
        return $percentage;
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

        $schedulerid = $this->get_schedulerid($courseid);
        $workshops = $this->get_workshops_list($schedulerid, $udate1, $udate2);
        $students = $this->normalize_students_data($workshops);

        if (count($students) > 0) {
            $usersdata = $this->get_full_user_data($courseid, $students);
            $list.="<table id='myTable' class='display' cellspacing='0' width='100%'>";
            $list.="<thead>";
            $list.="<tr>";
            $list.="<th>Name</th>";
            $list.="<th>Attending</th>";
            $list.="<th>Enrolled</th>";
            $list.="<th>Graduated</th>";
            $list.="<th>Attending</th>";
            $list.="</tr>";
            $list.="</thead>";
            $list.="<tbody>";
            foreach ($usersdata as $item) {
                $names = $item->fname . ' ' . $item->lname;
                $url = $item->url;
                $attend1 = $item->attend1;
                $attend2 = $item->attend2;
                $graduate = $item->graduate;
                $list.="<tr>";
                $list.="<td><a href='$url' target='_blank'>$names</td>";
                $list.="<td>$attend1</td>";
                $list.="<td>1</td>";
                $list.="<td>$graduate</td>";
                $list.="<td>$attend2</td>";
                $list.="</tr>";
            } // end foreach 
            $list.="</tbody>";
            $list.="</table>";

            $opened_date1 = count($this->get_open_enrolled_students_date1($courseid, $udate1));
            $opened_date2 = count($this->get_open_enrolled_students_date2($courseid, $udate2));
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
            $summary = $this->calculate_retention($opened_date1, $enrolled_between_dates, $graduated_between_dates, $opened_date2);
            $list.="<div class='row-fluid' style='font-wieght:bold;'>";
            $list.="<span class='span2'>Percentage of retention:</span>";
            $list.="<span class='span1'>$summary %</span>";
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
