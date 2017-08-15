<?php

require_once ($_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php');
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/pdf/mpdf/mpdf.php';

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

    function get_certificate_issue_date($courseid, $userid, $unix = false) {
        $query = "select * from mdl_certificates "
                . "where courseid=$courseid "
                . "and userid=$userid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                if ($unix) {
                    $date = $row['issue_date'];
                } // end if
                else {
                    $date = date('m/d/Y', $row['issue_date']);
                } // end else
            } // end while
        } // end if $num > 0
        else {
            $date = 0;
        }
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
                    . "and starttime>=$udate1 and starttime<=$udate2";
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
                //echo "Workshop id: " . $row['id'] . " workshop date: " . date('m/d/Y', $row['starttime']) . " <br>";
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

    function is_graduate_date_after_beginning($courseid, $userid, $udate) {
        $query = "select * from mdl_certificates "
                . "where courseid=$courseid "
                . "and userid=$userid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $dbdate = $row['issue_date'];
        }
        $status = ($dbdate >= $udate) ? 1 : 0;
        return $status;
    }

    function is_user_graduated($courseid, $userid) {
        $num = 0;
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

        //echo "Total number students enrolled before with all statuses:  ".date('m/d/Y', $udate).": " . count($slotstudents);

        $graduate = 0;
        $other_status = 0;
        $after_last_date = 0;
        foreach ($slotstudents as $userid) {
            $gr = $this->is_user_graduated($courseid, $userid);
            if ($gr > 0) {
                $graduated = $this->is_graduate_date_after_beginning($courseid, $userid, $udate);
                if ($graduated > 0) {
                    $graduate++;
                }
            } // end if $gr > 0
            $has_other_statuses = $this->has_other_statuses($userid);
            if ($has_other_statuses == 1) {
                $other_status++;
            }
            $before_last_date = $this->is_before_last_date($userid, $udate);
            if ($before_last_date == 0) {
                $after_last_date++;
            }
            //echo "User id: $userid has other status: $has_other_statuses enroll before last date $before_last_date <br>";
            //echo "<br>--------------------------------------------------------------<br>";
            if ($has_other_statuses == 0 && $before_last_date == 1 && $gr > 0) {
                $users[] = $userid;
            } // end if 
        } // end foreach
        //echo "Total with other status: " . $other_status . "<br>";
        //echo "Total Graduate: " . $graduate . "<br>";
        //echo "Total enrolled after last date: " . $after_last_date . "<br>";
        return array_unique($users);
    }

    function get_open_enrolled_students_date2($courseid, $udate) {
        $users = array();
        $schedulerid = $this->get_schedulerid($courseid);
        $workshops = $this->get_workshops_list($schedulerid, $udate);
        $slotstudents = $this->normalize_students_data($workshops);
        foreach ($slotstudents as $userid) {
            $graduated = $this->is_user_graduated($courseid, $userid);
            $has_other_statuses = $this->has_other_statuses($userid);
            $before_last_date = $this->is_before_last_date($userid, $udate);
            if ($graduated == 0 && $has_other_statuses == 0 && $before_last_date == 1) {
                $users[] = $userid;
            } // end if 
        } // end foreach
        return array_unique($users);
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

    function get_student_class_date($courseid, $userid) {
        $query = "select * from mdl_demographic where userid=$userid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $date = strtotime($row['startdate']);
                //echo "User ID $userid class date from mdl_demographic: $date <br>";
                if ($date == '') {
                    $date_arr = explode('-', $row['startdate']);
                    $newdate = "$date_arr[0]/$date_arr[1]/$date_arr[2]";
                    //echo "User ID $userid class new date: $newdate <br>";
                    $date = strtotime($newdate);
                }
            } // end while
        } // end if $num > 0   
        else {
            $schedulerid = $this->get_schedulerid($courseid);
            $slotslist = $this->get_course_slots($schedulerid);
            $query = "select * from mdl_scheduler_appointment "
                    . "where slotid in ($slotslist) "
                    . "and studentid=$userid";
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $slotid = $row['slotid'];
            }

            $query = "select * from mdl_scheduler_slots where id=$slotid";
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $date = $row['starttime'];
            }
        } // end else

        return $date;
    }

    function get_full_user_data($courseid, $users, $udate1, $udate2) {
        $report_users = array();
        $attend1 = 0;
        $attend2 = 0;
        $enrolled = 0;
        $graduated = 0;

        foreach ($users as $userid) {
            $userdata = $this->get_user_details($userid);
            if ($userdata->firstname != '' && $userdata->lastname != '') {
                $classdate = $this->get_student_class_date($courseid, $userid);
                $certdate = $this->get_certificate_issue_date($courseid, $userid, true);
                $lastdate = $this->get_student_last_date($userid);

                
                  //echo "Begin date : " . $udate1 . "<br>";
                  //echo "End date: " . $udate2 . "<br>";
                  //echo "User ID: " . $userid . "<br>";
                  //echo "Class date (unixtime)  $classdate" . "<br>";
                  //echo "Cert date (unixtime) $certdate <br>";
                  //echo "Last date (unixtime) $lastdate <br>";
                 

                //$userdata = $this->get_user_details($userid);
                //echo "User $userdata->firstname $userdata->lastname class date : " . date('m/d/Y', $classdate) . " certificate date: " . date('m/d/Y', $certdate) . " last date: " . date('m/d/Y', $lastdate) . "";
                //echo "<br>---------------------------------------------------------------------<br>";

                if ($lastdate == 0) {
                    if ($certdate == 0) {
                        if ($classdate <= $udate1) {
                            $attend1 = 1;
                            $attend2 = 1;
                            $enrolled = 0;
                            $graduated = 0;
                        }

                        if ($classdate > $udate1 && $classdate <= $udate2) {
                            $attend1 = 0;
                            $attend2 = 1;
                            $enrolled = 1;
                            $graduated = 0;
                        }
                    } // end if $certdate == 0

                    if ($certdate != 0) {
                        if ($classdate <= $udate1 && $certdate < $udate2) {
                            $attend1 = 1;
                            $attend2 = 0;
                            $enrolled = 0;
                            $graduated = 1;
                        }

                        if ($classdate <= $udate1 && $certdate > $udate2) {
                            $attend1 = 1;
                            $attend2 = 1;
                            $enrolled = 0;
                            $graduated = 0;
                        }

                        if ($classdate > $udate1 && $classdate <= $udate2 && $certdate < $udate2) {
                            $attend1 = 0;
                            $attend2 = 0;
                            $enrolled = 1;
                            $graduated = 1;
                        }

                        if ($classdate > $udate1 && $classdate <= $udate2 && $certdate > $udate2) {
                            $attend1 = 0;
                            $attend2 = 1;
                            $enrolled = 1;
                            $graduated = 0;
                        }
                    } // end if $certdate != 0
                } // end if $lastdate == 0
                else {
                    // Case when we have last medical2 date
                    if ($classdate <= $udate1 && $lastdate < $udate2) {
                        $attend1 = 1;
                        $attend2 = 0;
                        $enrolled = 0;
                        $graduated = 0;
                    }

                    if ($classdate <= $udate1 && $lastdate > $udate2) {
                        $attend1 = 1;
                        $attend2 = 1;
                        $enrolled = 0;
                        $graduated = 0;
                    }

                    if ($classdate > $udate1 && $classdate <= $udate2 && $lastdate < $udate2) {
                        $attend1 = 0;
                        $attend2 = 0;
                        $enrolled = 1;
                        $graduated = 0;
                    }

                    if ($classdate > $udate1 && $classdate <= $udate2 && $lastdate > $udate2) {
                        $attend1 = 0;
                        $attend2 = 1;
                        $enrolled = 1;
                        $graduated = 0;
                    }
                } // end else when last date is not empty
                //echo "<br>-------------------------------------------------<br>";
                $userObj = new stdClass();
                $userObj->id = $userid;
                $userObj->url = "https://medical2.com/lms/user/profile.php?id=$userid";
                $userObj->fname = $userdata->firstname;
                $userObj->lname = $userdata->lastname;
                $userObj->attend1 = $attend1;
                $userObj->attend2 = $attend2;
                $userObj->enrolled = $enrolled;
                $userObj->graduate = $graduated;

                /*    
                echo "<pre>";
                print_r($userObj);
                echo "</pre>";
                echo "<br>-------------------------------------------------<br>";
                */
                $report_users[] = $userObj;
            } // end if $userdata->firstname != '' && $userdata->lastname != ''
        } // end foreach
        return $report_users;
    }

    function is_before_last_date($userid, $udate) {
        $status = 1;
        $query = "select * from mdl_demographic where userid=$userid ";
        $num = $this->db->numrows($query);
        //echo "Before last date num: " . $num . "<br>";
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $m2date = $row['m2_last_date']; // m/d/Y
            } // end while
            if ($m2date != '') {
                $um2date = strtotime($m2date);
                //echo "Unix time stamp of last date (if any) .$um2date" . "<br>";
                $status = ($udate < $um2date) ? 1 : 0;
            } // end if 
            else {
                $status = 1;
            } // end else
        } // end if $num > 0
        else {
            $status = 1; // we assume student is still attending school
        }
        return $status;
    }

    function has_other_statuses($userid) {
        $status = 0;
        $query = "select * from mdl_demographic where userid=$userid ";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $dbstatus = $row['school_status'];
            }
            if ($dbstatus == 'A' || $dbstatus == 'G') {
                $status = 0;
            } // end if 
            else {
                $status = 1;
            } // end else
        } // end if $num > 0
        else {
            $status = 0; // we assume student is still attending school
        }
        return $status;
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
        $percentage = round(($graduated_between_dates / $total_attending) * 100);
        //echo "Total enrolled: " . $total_attending . "<br>";
        //echo "Toal graduated: " . $graduated_between_dates . "<br>";
        return $percentage;
    }

    function get_student_last_date($userid) {
        $query = "select * from mdl_demographic where userid=$userid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $m2date = $row['m2_last_date'];
                if ($m2date != '') {
                    $date = strtotime($m2date);
                } // end if
                else {
                    $date = 0;
                } // end else
            } // end while
        } // end if $num > 0
        else {
            $date = 0;
        }
        return $date;
    }

    function get_students_before_beginning_date($courseid, $schedulerid, $udate1) {
        $students = array();
        $workshops = $this->get_workshops_list($schedulerid, $udate1);
        $raw_student_before = $this->normalize_students_data($workshops);
        foreach ($raw_student_before as $userid) {
            $graduated = $this->is_user_graduated($courseid, $userid);
            if ($graduated > 0) {
                $certdate = $this->get_certificate_issue_date($courseid, $userid, true);
            } // end if
            else {
                $certdate = 0;
            } // end else
            $lastdate = $this->get_student_last_date($userid);
            $classdate = $this->get_student_class_date($courseid, $userid);
            //echo "User id: $userid <br>";
            //echo "Cert date: $certdate <br>";
            //echo "class date $classdate <br>";
            //echo  "Last date $lastdate <br>";
            if ($lastdate == 0) {
                if ($classdate <= $udate1 && $certdate > $udate1) {
                    $students[] = $userid;
                }
            } // end if
            else {
                // No certificate at all
                if ($classdate <= $udate1 && $lastdate > $udate1) {
                    $students[] = $userid;
                }
            } // end else
        } // end foreach
        //echo "Students before : <br>";
        //print_r($students);
        //echo "<br>--------------------------------------------------------<br>";

        return $students;
    }

    function get_students_between_dates($schedulerid, $udate1, $udate2) {
        $workshops = $this->get_workshops_list($schedulerid, $udate1, $udate2);
        $students = $this->normalize_students_data($workshops);

        /*
          echo "Students between dates : <br>";
          print_r($students);
          echo "<br>--------------------------------------------------------<br>";
         */
        return $students;
    }

    function get_workshop_data($data) {
        $list = "";
        $date1 = $data->date1;
        $date2 = $data->date2;
        $udate1 = strtotime($data->date1);
        $udate2 = strtotime($data->date2);
        $courseid = $data->course;
        $coursename = $this->get_course_name($courseid);

        $total_attend1 = 0;
        $total_attend2 = 0;
        $total_enrolled = 0;
        $total_graduate = 0;

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
        $students_before = $this->get_students_before_beginning_date($courseid, $schedulerid, $udate1);
        $students_between = $this->get_students_between_dates($schedulerid, $udate1, $udate2);
        $raw_students = array_merge((array) $students_before, (array) $students_between);
        $students = array_unique($raw_students);

        if (count($students) > 0) {
            $usersdata = $this->get_full_user_data($courseid, $students, $udate1, $udate2);
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
                $enrolled = $item->enrolled;
                $attend2 = $item->attend2;
                $graduate = $item->graduate;

                if ($attend1 == 1) {
                    $total_attend1++;
                }

                if ($attend2 == 1) {
                    $total_attend2++;
                }

                if ($graduate == 1) {
                    $total_graduate++;
                }

                if ($enrolled == 1) {
                    $total_enrolled++;
                }

                $list.="<tr>";
                $list.="<td><a href='$url' target='_blank'>$names</td>";
                $list.="<td>$attend1</td>";
                $list.="<td>$enrolled</td>";
                $list.="<td>$graduate</td>";
                $list.="<td>$attend2</td>";
                $list.="</tr>";
            } // end foreach 

            $list.="</tbody>";
            $list.="</table>";

            $list.="<div class='row-fluid'>";
            $list.="<span class='span6'>Students openly enrolled on $date1</span><span class='span1'>$total_attend1</span>";
            $list.="</div>";

            $list.="<div class='row-fluid'>";
            $list.="<span class='span6'>Students enrolled between $date1 and $date2</span><span class='span1'>$total_enrolled</span>";
            $list.="</div>";

            $list.="<div class='row-fluid'>";
            $list.="<span class='span6'>Students graduated between  $date1 and $date2</span><span class='span1'>$total_graduate</span>";
            $list.="</div>";

            $list.="<div class='row-fluid'>";
            $list.="<span class='span6'>Students openly enrolled at end of $date2</span><span class='span1'>$total_attend2</span>";
            $list.="</div>";
            $summary = $this->calculate_retention($total_attend1, $total_enrolled, $total_graduate, $total_attend2);
            $list.="<div class='row-fluid' style='font-wieght:bold;'>";
            $list.="<span class='span2'>Percentage of retention:</span>";
            $list.="<span class='span1'>$summary %</span>";
            $list.="</div>";

            $list.="<div class='row-fluid'>";
            $list.="<span class='span3'><button id='print_ws_data'>Print</button></span>";
            $list.="</div>";
        } // end if count($students)>0
        else {
            $list.="<div class='row-fluid'>";
            $list.="<span class='span6'>There are no any data found</span>";
            $list.="</div>";
        } // end else
        return $list;
    }

    function get_report_table($data) {
        $list = "";

        $date1 = $data->date1;
        $date2 = $data->date2;
        $udate1 = strtotime($data->date1);
        $udate2 = strtotime($data->date2);
        $courseid = $data->courseid;
        $coursename = $this->get_course_name($courseid);

        $total_attend1 = 0;
        $total_attend2 = 0;
        $total_graduate = 0;

        $list.="<div class='row-fluid' style='font-weight:bold;text-align:center;'>";
        $list.="<span class='span12'>$coursename</span>";
        $list.="</div>";

        $schedulerid = $this->get_schedulerid($courseid);
        $workshops = $this->get_workshops_list($schedulerid, $udate1, $udate2);
        $students_between = $this->normalize_students_data($workshops);
        $students = array_unique($students_between);

        $enrolled_between_dates = count($this->get_students_enrolled_between_dates($courseid, $udate1, $udate2));

        if (count($students) > 0) {
            $usersdata = $this->get_full_user_data($courseid, $students);
            foreach ($usersdata as $item) {
                $names = $item->fname . ' ' . $item->lname;
                $attend1 = $item->attend1;
                $attend2 = $item->attend2;
                $graduate = $item->graduate;

                if ($attend1 == 1) {
                    $total_attend1++;
                }

                if ($attend2 == 1) {
                    $total_attend2++;
                }

                if ($graduate == 1) {
                    $total_graduate++;
                }
            } // end foreach
        } // end if count

        $list.="<div class='row-fluid' style='text-align:center;font-weight:bold;'>";
        $list.="<span class='span6'>Students openly enrolled on $date1</span><span class='span1'>&nbsp; $total_attend1</span>";
        $list.="</div>";

        $list.="<div class='row-fluid' style='text-align:center;font-weight:bold;'>";
        $list.="<span class='span6'>Students enrolled between $date1 and $date2</span><span class='span1'>&nbsp; $enrolled_between_dates</span>";
        $list.="</div>";

        $list.="<div class='row-fluid' style='text-align:center;font-weight:bold;'>";
        $list.="<span class='span6'>Students graduated between  $date1 and $date2</span><span class='span1'> &nbsp; $total_attend2</span>";
        $list.="</div>";

        $list.="<div class='row-fluid' style='text-align:center;font-weight:bold;'>";
        $list.="<span class='span6'>Students openly enrolled at end of $date2</span><span class='span1'>&nbsp; $total_attend2</span>";
        $list.="</div>";
        $summary = $this->calculate_retention($total_attend1, $enrolled_between_dates, $total_graduate, $total_attend2);
        $list.="<div class='row-fluid' style='font-wieght:bold;text-align:center;font-weight:bold;'>";
        $list.="<span class='span2'>Percentage of retention:</span>";
        $list.="<span class='span1'>&nbsp; $summary %</span>";
        $list.="</div><br><br>";

        if (count($students) > 0) {
            $usersdata = $this->get_full_user_data($courseid, $students);
            $list.="<table id='myTable' class='display' cellspacing='0' width='100%' align='center;' border='1' style='align:center;'>";
            $list.="<thead>";
            $list.="<tr>";
            $list.="<th style='text-align:left;'>Name</th>";
            $list.="<th>Attending</th>";
            $list.="<th>Enrolled</th>";
            $list.="<th>Graduated</th>";
            $list.="<th>Attending</th>";
            $list.="</tr>";
            $list.="</thead>";
            $list.="<tbody>";
            foreach ($usersdata as $item) {
                $names = $item->fname . ' ' . $item->lname;
                $attend1 = $item->attend1;
                $attend2 = $item->attend2;
                $graduate = $item->graduate;

                $list.="<tr>";
                $list.="<td>$names</td>";
                $list.="<td>$attend1</td>";
                $list.="<td>1</td>";
                $list.="<td>$graduate</td>";
                $list.="<td>$attend2</td>";
                $list.="</tr>";
            } // end foreach 
            $list.="</tbody>";
            $list.="</table>";
        }
        return $list;
    }

    function get_pdf_report($criteria) {
        $list = "";

        $table = $this->get_report_table($criteria);

        $list.="<html>";

        $list.="<head>";

        $list.="</head>";

        $list.="<body>";

        $list.="<div style='80%;margin:auto;'>";
        $list.="<br><table align='center' border='0' width='100%' >

                    <tr>

                        <td style='padding-top:10px;text-align:right;'><img src='https://medical2.com/assets/icons/logo3.png' width='115' height='105'></td>
                        
                        <td valign='center' style='text-align:left;'>
                        
                        <table style='padding:15px;font-size:12px;' align='center'>

                                <tr>
                                    <td style='font-size:20px;font-weight:bold;letter-spacing:8px;padding-left:65px;'>Medical2</td>
                                </tr>
                                
                                <tr>
                                    <td style='font-size:15px;font-weight:bold;letter-spacing:6px;padding-left:40px;'>Career College</td>
                                </tr>

                                <tr>
                                    <td style='padding-top:10px;padding-left:75px;'>1830A North Gloster St</td>
                                </tr>  

                                <tr>
                                    <td style='padding-left:90px;'>Tupelo, MS 38804</td>
                                </tr>  

                            </table>";
        $list.="</td>";
        $list.="</tr>";
        $list.="</table>";

        $list.="<br><div class='row-fluid;text-align:center;'>";
        $list.="<span class='span12'>$table</span>";
        $list.="</div>";

        $list.="</div>";

        $list.="</body>";

        $list.="</html>";

        $file = "report.pdf";

        $path = $_SERVER['DOCUMENT_ROOT'] . "/lms/custom/wsdata/$file";
        $pdf = new mPDF('utf-8', 'A4-P');
        $pdf->WriteHTML($list);
        $pdf->Output($path, 'F');
        return $file;
    }

}
