<?php

/**
 * Description of Schedule
 *
 * @author sirromas
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/balance/classes/Balance.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/certificates/classes/Certificates.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/PDF_Label.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/Mailer.php';

class Schedule extends Util {

    public $courseid;
    public $labels_path;
    public $modid;

    function __construct() {
        global $COURSE;
        parent::__construct();
        $this->courseid = $COURSE->id;
        $this->labels_path = $_SERVER['DOCUMENT_ROOT'] . '/print';
        $this->create_scheduler_data();
    }

    function create_scheduler_data() {
        $query = "select * from mdl_scheduler_slots";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $location[] = mb_convert_encoding($row['appointmentlocation'], 'UTF-8');
        }
        //array_unique($location);
        file_put_contents('/home/cnausa/public_html/lms/custom/utils/wslocation.json', json_encode($location));
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

        $username = $this->user->username;
        $userid = $this->user->id;
        $slots = array();
        $now = time() - 86400;
        if ($username == 'admin' || $username == 'manager') {
            if ($search == null) {
                if ($start == null && $end == null) {
                    $query = "select * from mdl_scheduler_slots "
                            . "where schedulerid=$schedulerid "
                            . "and starttime>$now order by starttime";
                } // end if $start == null && $end == null
                if ($start != null && $end == null) {
                    $query = "select * from mdl_scheduler_slots "
                            . "where schedulerid=$schedulerid "
                            . "and starttime>" . strtotime($start) . " order by starttime";
                } // end if $start!=null && $end==null
                if ($start != null && $end != null) {
                    $query = "select * from mdl_scheduler_slots "
                            . "where schedulerid=$schedulerid "
                            . "and starttime>" . strtotime($start) . " "
                            . "and starttime<" . strtotime($end) . " order by starttime";
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
                            . "and starttime>" . strtotime($start) . " order by starttime";
                } // end if $start!=null && $end==null
                if ($start != null && $end != null) {
                    $query = "select * from mdl_scheduler_slots "
                            . "where schedulerid=$schedulerid "
                            . "and starttime>" . strtotime($start) . " "
                            . "and 	(appointmentlocation like '%$search%' "
                            . "or notes like '%$search%') "
                            . "and starttime<" . strtotime($end) . " order by starttime";
                } // end if $start != null && $end != null
            } // end else
        } // end if $username=='admin' && $username=='manager'
        else {
            if ($search == null) {
                if ($start == null && $end == null) {
                    $query = "select * from mdl_scheduler_slots "
                            . "where schedulerid=$schedulerid "
                            . "and starttime>$now and teacherid=$userid order by starttime";
                } // end if $start == null && $end == null
                if ($start != null && $end == null) {
                    $query = "select * from mdl_scheduler_slots "
                            . "where schedulerid=$schedulerid "
                            . "and starttime>" . strtotime($start) . " "
                            . "and teacherid=$userid order by starttime";
                } // end if $start!=null && $end==null
                if ($start != null && $end != null) {
                    $query = "select * from mdl_scheduler_slots "
                            . "where schedulerid=$schedulerid "
                            . "and starttime>" . strtotime($start) . " "
                            . "and starttime<" . strtotime($end) . " "
                            . "and teacherid=$userid order by starttime";
                } // end if $start != null && $end != null
            } // end if ($search == null) {
            else {
                if ($start == null && $end == null) {
                    $query = "select * from mdl_scheduler_slots "
                            . "where schedulerid=$schedulerid "
                            . "and 	(appointmentlocation like '%$search%' "
                            . "or notes like '%$search%') and starttime>$now "
                            . "and teacherid=$userid";
                } // end if $start == null && $end == null
                if ($start != null && $end == null) {
                    $query = "select * from mdl_scheduler_slots "
                            . "where schedulerid=$schedulerid "
                            . "and 	(appointmentlocation like '%$search%' "
                            . "or notes like '%$search%') "
                            . "and starttime>" . strtotime($start) . " "
                            . "and teacherid=$userid order by starttime";
                } // end if $start!=null && $end==null
                if ($start != null && $end != null) {
                    $query = "select * from mdl_scheduler_slots "
                            . "where schedulerid=$schedulerid "
                            . "and starttime>" . strtotime($start) . " "
                            . "and 	(appointmentlocation like '%$search%' "
                            . "or notes like '%$search%') "
                            . "and starttime<" . strtotime($end) . " "
                            . "and teacherid=$userid order by starttime";
                } // end if $start != null && $end != null
            } // end else
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

    function is_user_deleted($userid) {
        $query = "select * from mdl_user where id=$userid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $deleted = $row['deleted'];
        }
        return $deleted;
    }

    function get_slot_students($slotid) {
        $apps = array();
        $query = "select * from mdl_scheduler_appointment "
                . "where slotid=$slotid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $deleted = $this->is_user_deleted($row['studentid']);
                if ($deleted == 0) {
                    $app = new stdClass();
                    foreach ($row as $key => $value) {
                        $app->$key = $value;
                    } // end foreach
                    $apps[] = $app;
                } // end if $deleted == 0
            } // end while
        } // end if $num > 0
        return $apps;
    }

    function get_student_course_completion_status($courseid, $userid) {
        $query = "select * from mdl_course_completions "
                . "where course=$courseid "
                . "and userid=$userid";
        //echo "Query: ".$query."<br>";
        $num = $this->db->numrows($query);
        //echo "Num: ".$num."<br>";
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

    function get_course_id($slotid) {
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

    function get_scheduler_module_id($courseid) {
        //echo "Function course id: ".$courseid."<br>";
        if ($courseid == 1) {
            //echo "Inside if ....<br>";
            //print_r($_SERVER);

            $qs = $_SERVER['HTTP_REFERER'];
            //echo "Query string: ".$qs."<br>";
            $modid = trim(str_replace("https://medical2.com/lms/mod/scheduler/view.php?id=", "", $qs));
            //echo "Module id: ".$modid."<br>";
        } // end if $courseid == 1
        else {
            //echo "Inside else ...<br>";
            $query = "select * from mdl_course_modules "
                    . "where module=23 "
                    . "and course=$courseid";
            //echo "Query: " . $query . "<br>";
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $modid = $row['id'];
            }
        } // end else
        return $modid;
    }

    function create_slots_page($slots, $tools = true) {
        global $COURSE, $USER;
        $courseid = $COURSE->id;
        $userid = $USER->id;
        $contextid = $this->get_course_context($courseid);
        $roleid = $this->get_user_role($userid, $contextid);
        //echo "Course id: " . $courseid . "<br>";
        $qs = $_SERVER['QUERY_STRING'];
        $modid = trim(str_replace("id=", "", $qs));

        if ($modid == '') {
            $modid = $this->get_scheduler_module_id($courseid);
        }

        $this->modid = $modid;

        //echo "Total workshops: ".count($slots)."<br>";

        $list = "";
        if ($tools == true) {
            $list.="<div class='panel panel-default'>";
            $list.="<div class='panel-heading'style='text-align:left;'><h5 class='panel-title'>Control Panel</h5></div>";
            $list.="<div class='panel-body'>";
            $list.= "<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span2'><input type='text' id='search' style='width:125px;'></span>";
            $list.="<span class='span2'><button type='button' class='btn btn-primary'  id='search_btn'>Search</button></span>";
            $schedulerid = $this->get_course_scheduler($this->course->id);
            $courseid = $this->course->id;
            $list.="<span><input type='hidden' id='scheduler' value='$schedulerid'></span>";
            $list.="<span><input type='hidden' id='courseid' value='$courseid'></span>";
            $list.="<span class='span1'>Start</span>";
            $list.="<span class='span2'><input type='text' id='start' style='width:75px;'></span>";
            $list.="<span class='span1'>End</span>";
            $list.="<span class='span2'><input type='text' id='end'  style='width:75px;'></span>";
            $list.="<span class='span2'><button type='button' class='btn btn-primary'  id='date_btn'>Go</button></span>";
            $list.="<input type='hidden' id='sesskey' value='" . sesskey() . "'>";
            $list.="</div>";
            $list.= "<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span2'><a href='#' id='students_all' onClick='return false;'>Select all</a></span>";
            $list.="<span class='span2'><a href='#' id='students_none' onClick='return false;'>Deselct all</a></span>";
            $list.="<span class='span2'><a href='#' id='complete' onClick='return false;'>Make passed</a></span>";
            $list.="<span class='span2'><a href='#' id='pending' onClick='return false;'>Make pending</a></span>";
            $list.="<span class='span2'><a href='#' id='move' onClick='return false;'>Move</a></span>";
            //$list.="<span class='span2'><a href='#' id='delete' onClick='return false;'>Remove</a></span>";
            $list.="<span class='span2'><a href='#' id='print' onClick='return false;'>Print certificates</a></span>";
            $list.="<span class='span2'><a href='#' id='labels' onClick='return false;'>Print labels</a></span>";
            $list.="<span class='span2'><a href='#' id='send' onClick='return false;'>Send certificates</a></span>";
            $list.="<span class='span2'><a href='#' id='add_students' onClick='return false;'>Add students</a></span>";
            $list.="</div>";
            $list.= "<div class='container-fluid' style='text-align:center;'>";
            $list.="<span class='span12' id='sch_err' style='color:red;'></span>";
            $list.="</div>";
            $list.= "<div class='container-fluid' style='text-align:center;'>";
            $list.="<span class='span12' id='ajax_loading' style='display:none;'><img src='https://medical2.com/assets/img/ajax.gif' /></span>";
            $list.="</div>";
        } // end if $tools == true

        if (count($slots) > 0) {
            $list.="<div id='schedule_container'>";
            foreach ($slots as $slot) {
                $slotid = $slot->id;
                $has_students = $this->is_has_students($slotid);
                $modid = $this->get_scheduler_module_id($this->course->id);
                //echo "Module id: ".$modid."<br>";
                $editactionurl = "https://medical2.com/lms/mod/scheduler/view.php?id=" . $modid . "&what=updateslot&subpage=myappointments&offset=-1&sesskey=" . sesskey() . "&slotid=" . $slotid . "";
                $addr_array = explode("/", $slot->appointmentlocation);
                $addr_block = $addr_array[1] . " , " . $addr_array[0];

                $list.="<div class='panel panel-default'>";

                if ($roleid <= 3) {
                    $balance_block = $this->get_workshop_balance($slotid);
                    if ($has_students > 0) {
                        $list.="<div class='panel-heading'style='text-align:left;'><h5 class='panel-title'>$addr_block, " . date('m-d-Y h:i:s', $slot->starttime) . "&nbsp;<a href='$editactionurl'><img src='https://medical2.com/lms/theme/image.php/lambda/core/1464336624/t/edit' title='Edit'></a>&nbsp;" . $balance_block . "<br> $slot->notes</h5></div>";
                    } // end if $has_students>0
                    else {
                        $list.="<div class='panel-heading'style='text-align:left;'><h5 class='panel-title'>$addr_block, " . date('m-d-Y h:i:s', $slot->starttime) . "&nbsp;<a href='$editactionurl'><img src='https://medical2.com/lms/theme/image.php/lambda/core/1464336624/t/edit' title='Edit'></a>&nbsp;<a href='#' onClick='return false;'><img id='del_slot_$slotid' src='https://medical2.com/lms/theme/image.php/lambda/core/1468523658/t/delete' title='Delete'></a>&nbsp;" . $balance_block . "<br> $slot->notes</h5></div>";
                    } // end else 
                } // end if $roleid <= 3
                else {
                    if ($has_students > 0) {
                        $list.="<div class='panel-heading'style='text-align:left;'><h5 class='panel-title'>$addr_block, " . date('m-d-Y h:i:s', $slot->starttime) . "&nbsp;<a href='$editactionurl'><img src='https://medical2.com/lms/theme/image.php/lambda/core/1464336624/t/edit' title='Edit'></a><br> $slot->notes</h5></div>";
                    } // end if $has_students>0
                    else {
                        $list.="<div class='panel-heading'style='text-align:left;'><h5 class='panel-title'>$addr_block, " . date('m-d-Y h:i:s', $slot->starttime) . "&nbsp;<a href='$editactionurl'><img src='https://medical2.com/lms/theme/image.php/lambda/core/1464336624/t/edit' title='Edit'></a>&nbsp;<a href='#' onClick='return false;'><img id='del_slot_$slotid' src='https://medical2.com/lms/theme/image.php/lambda/core/1468523658/t/delete' title='Delete'></a><br> $slot->notes</h5></div>";
                    } // end else 
                } // end else

                $list.="<div class='panel-body' id='$slotid'>";
                $slot_students = $this->get_slot_students($slot->id);
                $courseid = $this->get_course_id($slot->id);
                if (count($slot_students) > 0) {
                    $list.= "<div class='container-fluid' style='text-align:left;font-weight:bold;'>";
                    $list.="<span class='span1'></span>";
                    $list.="<span class='span5'>Student</span>";
                    $list.="<span class='span4'>Course completion status</span>";
                    $list.="</div>";
                    $list.= "<div class='container-fluid' style='text-align:left;'>";
                    $list.="<span class='span1'><input type='checkbox' name='studentid' id='slot_students_$slotid' value=''></span>";
                    $list.="<span class='span2'>Select all</span>";
                    $list.="</div>";
                    foreach ($slot_students as $student) {
                        $user_data = $this->get_user_details($student->studentid);
                        $completion_arr = $this->get_student_course_completion_status($courseid, $student->studentid);
                        //print_r($completion_arr);
                        if ($completion_arr['passed'] == 1) {
                            $status = "Passed " . date('m-d-Y', completed) . "";
                        } // end if $completion_arr['passed']==1
                        else {
                            $status = "Pending";
                        } // end else                        
                        $student_balance = $this->get_student_balance($courseid, $student->studentid, $slot->id);
                        $list.= "<div class='container-fluid' style='text-align:left;'>";
                        $list.="<span class='span1'><input type='checkbox' class='students' name='studentid' value='$student->studentid'></span>";
                        $list.="<span class='span5'><a href='https://medical2.com/lms/user/profile.php?id=$student->studentid'  target='_blank'>$user_data->firstname $user_data->lastname $user_data->phone1&nbsp; $user_data->email</a>&nbsp; $student_balance</span>";
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
            $list.= "<div class='container-fluid' style='text-align:left;' id='schedule_container'>";
            $list.="<span class='span8'>There are no scheduled workshops</span>";
            $list.="</div>";
        }
        return $list;
    }

    function is_slot_has_has_students($slotid) {
        $query = "select * from mdl_scheduler_appointment "
                . "where slotid=$slotid";
        $num = $this->db->numrows($query);
        return $num;
    }

    function get_slots_by_date($schedulerid, $start, $end) {
        $list = "";
        $list.= $this->get_course_slots(false, $schedulerid, null, $start, $end);
        return $list;
    }

    function search_slot($schedulerid, $search) {
        $list = "";
        $list.= $this->get_course_slots(false, $schedulerid, $search, null, null);
        return $list;
    }

    function change_students_course_status($courseid, $students) {
        $students_arr = explode(",", $students);
        $now = time();
        if (count($students_arr) > 0) {
            $cert = new Certificates();
            foreach ($students_arr as $studentid) {
                $query = "insert into mdl_course_completions "
                        . "(userid,"
                        . "course,"
                        . "timeenrolled,"
                        . "timestarted,"
                        . "timecompleted,"
                        . "reaggregate) "
                        . "values($studentid,"
                        . "$courseid,"
                        . "$now,"
                        . "0,"
                        . "$now,"
                        . "0)";
                $this->db->query($query);
                $date = $this->get_course_completion_date($courseid, $studentid);
                $cert->send_certificate($courseid, $studentid, $date, false);
            } // end foreach
        } // end if count($students_arr)>0
    }

    function get_course_completion_date($courseid, $userid) {
        $query = "select * from mdl_course_completions "
                . "where course=$courseid and userid=$userid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $completed = $row['timecompleted'];
        }
        return $completed;
    }

    function send_certificate($courseid, $students) {
        $list = "";
        $students_arr = explode(",", $students);
        if (count($students_arr) > 0) {
            $cert = new Certificates();
            foreach ($students_arr as $studentid) {
                $date = $this->get_course_completion_date($courseid, $studentid);
                $cert->send_certificate($courseid, $studentid, $date);
            } // end foreach
        } // end if count($students_arr) > 0
        $list.="Certificates were sent to selected users";
        return $list;
    }

    function print_certificate($courseid, $students) {
        $certs = array();
        $students_arr = explode(",", $students);
        if (count($students_arr) > 0) {
            $now = time();
            $cert = new Certificates();
            foreach ($students_arr as $studentid) {
                //echo "Course id: ".$courseid."<br>";
                //echo "Student id: ".$studentid."<br>";
                $cert->send_certificate($courseid, $studentid, $now, false);
                //$pdf_file = $_SERVER['DOCUMENT_ROOT'] . "/lms/custom/certificates/$studentid/certificate.pdf";
                $pdf_file = $_SERVER['DOCUMENT_ROOT'] . "/lms/custom/certificates/$studentid/$courseid/certificate.pdf";
                $certs[] = $pdf_file;
            }
            //print_r($certs);
            $datadir = $_SERVER['DOCUMENT_ROOT'] . "/print/";
            //$outputName = $datadir . "merged.pdf";
            $outputName = $datadir . $now . "_merged.pdf";
            $cmd = "gs -q -dNOPAUSE -dBATCH -sDEVICE=pdfwrite -sOutputFile=$outputName ";
            foreach ($certs as $certificate) {
                $cmd .= $certificate . " ";
            } // end foreach
            shell_exec($cmd);

            /*
              $query = "select * from mdl_print_job";
              $num = $this->db->numrows($query);
              if ($num > 0) {
              $query2 = "update mdl_print_job set students='$students'";
              } // end if $num > 0
              else {
              $query2 = "insert into mdl_print_job (students) values('$students')";
              } // end else
              $this->db->query($query2);
             */
        } // end if count($students_arr) > 0
        else {
            echo "No students selected ...";
        }
        return $now . "_merged.pdf";
    }

    function get_print_job() {
        $query = "select * from mdl_print_job";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $students = $row['students'];
        }
        return $students;
    }

    function get_students_course_slots($schedulerid) {
        $list = "";

        $query = "select * from mdl_scheduler_slots "
                . "where schedulerid=$schedulerid order by starttime";
        $num = $this->db->numrows($query);

        if ($num > 0) {

            $list.="<span class='span3'>Workshops: </span><span class='span4'><select id='slots' style='width:295px;'>";
            $list.="<option value='0' slected>Workshops</option>";
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $date = date('m-d-Y', $row['starttime']);
                $app = explode('/' . $row['appointmentlocation']);
                $location = $app[1] . "," . $app[0];
                $ws = $date . "&nbsp;" . $location .
                        $list.="<option value='" . $row['id'] . "'>" . $date . " " . $row['appointmentlocation'] . "</option>";
            } // end while
            $list.="</select>";
        } // end if $num > 0
        else {
            $list.="n/a";
        }
        return $list;
    }

    function get_students_box($courseid, $schedulerid) {
        $list = "";
        //$slots = $this->get_students_course_slots($schedulerid);
        $students = $this->get_course_users($courseid);
        $list.="<div id='myModal' class='modal fade'>
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
                <h4 class='modal-title'>Add student</h4>
            </div>
            <div class='modal-body'>
            <div class='container-fluid' style='text-align:left;'>
            <span class='span2'>$students</span> 
            <input type='hidden' id='schedulerid' value='$schedulerid'>    
            </div>
            
            <div class='container-fluid' style='text-align:left;'>
            <span class='span2' style='padding-left:30px;'><input type='text' id='slots' class='typeahead' style='width:265px;'></span>    
            <br/><br/><br/><br/>
            </div>
                
            </div>
            <div class='modal-footer'>
                <span align='center'><button type='button' class='btn btn-primary' data-dismiss='modal' id='cancel'>Cancel</button></span>
                <span align='center'><button type='button' class='btn btn-primary' data-dismiss='modal' id='add_user_to_slot'>Add</button></span>
            </div>
        </div>
    </div>
</div>";
        return $list;
    }

    function create_ws_json_data($schedulerid) {
        $ws = array();
        $now = time();
        $query = "select * from mdl_scheduler_slots "
                . "where schedulerid=$schedulerid "
                . "and  starttime>=$now order by starttime";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $location = mb_convert_encoding($row['appointmentlocation'], 'UTF-8');
            $date = mb_convert_encoding(date('m-d-Y', trim($row['starttime'])), 'UTF-8');
            $ws[] = $date . "--" . $location;
        }
        file_put_contents('/home/cnausa/public_html/lms/custom/utils/workshops.json', json_encode($ws));
    }

    function get_workshops_list($students, $schedulerid) {
        $list = "";
        $this->create_ws_json_data($schedulerid);
        $list.="<div id='myModal' class='modal fade'>
        <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-header'>
                <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
                <h4 class='modal-title'>Workshops list</h4>
            </div>
            <div class='modal-body'>
            
            <div class='container-fluid' style='text-align:left;'>
            <span class='span2'><input type='hidden' id='students' value='$students'></span>                
            </div>
            
            <div class='container-fluid' style='text-align:left;'>
            <span class='span2'><input type='text' id='slots' style='width:375px;'></span>  
            <br><br><br><br>
            </div>
                
            </div>
            <div class='modal-footer'>
                <span align='center'><button type='button' class='btn btn-primary' data-dismiss='modal' id='cancel'>Cancel</button></span>
                <span align='center'><button type='button' class='btn btn-primary' data-dismiss='modal' id='move_user_to_slot'>Move</button></span>
            </div>
        </div>
    </div>
</div>";
        return $list;
    }

    function add_user_to_slot($slotname, $userid, $schedulerid) {
        $lsotid = $this->get_slotid_by_name($slotname, $schedulerid);
        $query = "select * from mdl_scheduler_appointment "
                . "where slotid=$lsotid and studentid=$userid";
        $num = $this->db->numrows($query);
        if ($num == 0) {
            $query = "insert into mdl_scheduler_appointment "
                    . "(slotid,studentid,attended) "
                    . "values ($lsotid,$userid,0)";
            $this->db->query($query);
        } // end if $num==0
    }

    function make_students_pending($courseid, $students) {
        $students_arr = explode(",", $students);
        if (count($students_arr) > 0) {
            foreach ($students_arr as $studentid) {
                $query = "delete from mdl_course_completions "
                        . "where course=$courseid and userid=$studentid";
                $this->db->query($query);
            } // end foreach
        } // end if count($students_arr) > 0
    }

    function get_current_student_slot($studentid, $schedulerid) {
        $slots = array();
        // Get course slots
        $query = "select * from mdl_scheduler_slots "
                . "where schedulerid=$schedulerid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $slots[] = $row['id'];
        }
        $slots_str = implode(',', $slots);
        // Get student slot
        $query = "select * from mdl_scheduler_appointment "
                . "where studentid=$studentid and slotid in ($slots_str)";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $slotid = $row['slotid'];
        }
        return $slotid;
    }

    function get_slotid_by_name($slot, $schedulerid = null) {
        $slot_data = explode('--', $slot);
        if ($schedulerid != null) {
            $query = "select * from mdl_scheduler_slots "
                    . "where schedulerid=$schedulerid";
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $slots_array[] = $row['id'];
            }
            $slots = implode(',', $slots_array);
            $query = "select * from mdl_scheduler_slots "
                    . "where FROM_UNIXTIME(starttime,'%m-%d-%Y') ='$slot_data[0]' "
                    . "and appointmentlocation='$slot_data[1]' and id in ($slots)";
        } // end if
        else {
            $query = "select * from mdl_scheduler_slots "
                    . "where FROM_UNIXTIME(starttime,'%m-%d-%Y') ='$slot_data[0]' "
                    . "and appointmentlocation='$slot_data[1]' ";
        }  // end else          
        //echo "Query: " . $query . "<br>";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $slotid = $row['id'];
        }
        return $slotid;
    }

    function move_students($newslot, $students, $schedulerid) {
        $students_arr = explode(",", $students);
        $newslotid = $this->get_slotid_by_name($newslot, $schedulerid);
        //echo "New slot id: " . $newslotid . "<br>";
        //die ();
        if (count($students_arr) > 0) {
            foreach ($students_arr as $studentid) {
                $oldslotid = $this->get_current_student_slot($studentid, $schedulerid);
                $query = "update mdl_scheduler_appointment "
                        . "set slotid=$newslotid "
                        . "where studentid=$studentid and slotid=$oldslotid";
                //echo "Query: " . $query . "<br>";
                $this->db->query($query);

                // Custom slots table used to store user slotid     
                $query2 = "update mdl_slots set slotid=$newslotid "
                        . "where userid=$studentid "
                        . "and slotid=$oldslotid";
                //echo "Query: " . $query2 . "<br>";
                $this->db->query($query2);
            } // end foreach
        } // end if count($students_arr) > 0
    }

    function remove_students($students, $schedulerid) {
        $students_arr = explode(",", $students);
        if (count($students_arr) > 0) {
            foreach ($students_arr as $studentid) {
                $oldslotid = $this->get_current_student_slot($studentid, $schedulerid);
                $query = "delete from mdl_scheduler_appointment "
                        . "where studentid=$studentid "
                        . "and slotid=$oldslotid";
                $this->db->query($query);
            } // end foreach
        }
    }

    function is_slot_exists($slot) {

        // $schedulerid = 5 - Phleb with EKG
        // $schedulerid = 6 - Phleb 
        $slotid = 0;
        $query = "select * from mdl_scheduler_slots "
                . "where schedulerid=$slot->schedulerid "
                . "and appointmentlocation='$slot->appointmentlocation' "
                . "and starttime='$slot->starttime'";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $slotid = $row['id'];
            } // end while
        } // end if $num > 0
        return $num;
    }

    function get_original_slot_data($slotid) {
        $query = "select * from mdl_scheduler_slots where id=$slotid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $slot = new stdClass();
            foreach ($row as $key => $value) {
                $slot->$key = $value;
            }
        }
        return $slot;
    }

    function save_additional_slot($slot) {

        if ($_REQUEST['what'] == 'addslot') {
            $query = "insert into mdl_scheduler_slots "
                    . "(schedulerid, cost,"
                    . "starttime,"
                    . "duration, "
                    . "exclusivity, "
                    . "emaildate, "
                    . "teacherid, "
                    . "appointmentlocation,"
                    . "timemodified,"
                    . "notes,"
                    . "hideuntil) "
                    . "values($slot->schedulerid, $slot->cost,"
                    . "'$slot->starttime', "
                    . "'$slot->duration', "
                    . "'$slot->exclusivity', "
                    . "'$slot->emaildate', "
                    . "$slot->teacherid, "
                    . "'$slot->appointmentlocation',"
                    . "'$slot->timemodified',"
                    . "'$slot->notes',"
                    . "'$slot->timemodified')";
            $this->db->query($query);

            // Add additional workshop for EKG only course
            /*
              $ekg_schedulerid = 22;
              $start = time()+86400*2;

              $query = "insert into mdl_scheduler_slots "
              . "(schedulerid,"
              . "starttime,"
              . "duration, "
              . "exclusivity, "
              . "emaildate, "
              . "teacherid, "
              . "appointmentlocation,"
              . "timemodified,"
              . "notes,"
              . "hideuntil) "
              . "values($ekg_schedulerid,"
              . "'$start', "
              . "'$slot->duration', "
              . "'$slot->exclusivity', "
              . "'$slot->emaildate', "
              . "$slot->teacherid, "
              . "'$slot->appointmentlocation',"
              . "'$slot->timemodified',"
              . "'$slot->notes',"
              . "'$slot->timemodified')";
              $this->db->query($query);
             */
        } // end if $_REQUEST['what'] == 'addslot'

        if ($_REQUEST['what'] == 'updateslot') {

            // Find vice-versa workshop to be updated
            $original_slot = $this->get_original_slot_data($slot->slotid);
            $query = "select * from mdl_scheduler_slots "
                    . "where "
                    . "appointmentlocation='$original_slot->appointmentlocation' "
                    . "and schedulerid=$slot->schedulerid "
                    . "and starttime='$original_slot->starttime'";
            //echo "Query: " . $query . "<br>";
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $slotid = $row['id']; // This is id of workshop to be updated
            }

            $query = "update mdl_scheduler_slots "
                    . "set starttime='$slot->starttime', "
                    . "duration='$slot->duration', "
                    . "exclusivity='$slot->exclusivity', "
                    . "emaildate='$slot->emaildate', "
                    . "teacherid='$slot->teacherid', "
                    . "hideuntil='$slot->hideuntil', "
                    . "appointmentlocation='$slot->appointmentlocation', "
                    . "timemodified='$slot->timemodified', "
                    . "notes='$slot->notes' "
                    . " where id=$slotid ";
            //echo "Query: " . $query . "<br>";
            //die();
            $this->db->query($query);
            //$slots_array = array($slot->slotid, $slotid);
            //$this->notify_students($slots_array);
            $students = $this->get_workshop_students_list($slot->slotid);
            $this->send_workshop_students_list($slotid, $students);
        } // end if $_REQUEST['what'] == 'updateslot') 
    }

    function get_workshop_students_list($slotid) {
        $students = array();
        $query = "select * from mdl_scheduler_appointment where slotid=$slotid";
        //echo "Query:".$query."<br>";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $students[] = $row['studentid'];
            } // end while
        } // end if $num > 0
        return $students;
    }

    function get_workshop_course_name($schedulerid) {
        $query = "select * from mdl_scheduler where id=$schedulerid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $courseid = $row['course'];
        }
        $coursename = $this->get_course_name($courseid);
        return $coursename;
    }

    function send_workshop_students_list($slotid, $students) {
        $list = "";
        if (count($students) > 0) {
            $ws = $this->get_workshop_detailes($slotid); // object
            $coursename = $this->get_workshop_course_name($ws->schedulerid);
            $date = date('m-d-Y', $ws->starttime);
            $list.="<html>";
            $list.="<body>";
            $list.="<p align='center'>$coursename - $date - $ws->appointmentlocation</p>";
            $list.="<table align='center'>";
            foreach ($students as $userid) {
                $user = $this->get_user_details($userid);
                $date = date('m-d-Y', $user->timecreated);
                $list.="<tr>";
                $list.="<td style='padding:15px'>$user->firstname  $user->lastname</td>";
                $list.="<td style='padding:15px'>$user->email</td>";
                $list.="<td style='padding:15px'>$user->phone1</td>";
                $list.="<td style='padding:15px'>$date</td>";
                $list.="</tr>";
            }

            $list.="</table>";
            $list.="</body>";
            $list.="</html>";

            $m = new Mailer();
            $m->send_workshop_students_list($list);
        } // end if count($students)>0
    }

    function set_workshop_cost($id, $slot) {
        $query = "update mdl_scheduler_slots set cost='$slot->cost' where id=$id";
        $this->db->query($query);
    }

    function get_worskhop_update_message($slotid) {
        $list = "";

        $query = "select * from mdl_scheduler_slots where id=$slotid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $date = date('m-d-Y', $row['starttime']);
            $location = $row['appointmentlocation'];
            $notes = $row['notes'];
        }

        $list.="<html>";
        $list.="<body>";
        $list.="<p align='center'>Dear Student!</p>";
        $list.="<p align='center'>The Workshop you registered was changed:</p>";

        $list.="<table align='center'>";

        $list.="<tr>";
        $list.="<td style='padding:15px;'>Workshop date</td><td style='padding:15px;'>$date</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td style='padding:15px;'>Workshop location</td><td style='padding:15px;'>$location</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td style='padding:15px;'>Additional info</td><td style='padding:15px;'>$notes</td>";
        $list.="</tr>";

        $list.="</table>";

        $list.="<p>Best regards, <br>Medical2 team</p>";
        $list.="</body>";
        $list.="</html>";

        return $list;
    }

    function notify_students($slots_array) {

        /*
         * 
          echo "<pre>";
          print_r($slots_array);
          echo "</pre>";
          die();
         * 
         */

        $mailer = new Mailer();
        $message = $this->get_worskhop_update_message($slots_array[0]);
        if (count($slots_array) > 0) {
            foreach ($slots_array as $slotid) {
                if ($slotid > 0) {
                    $query = "select * from mdl_scheduler_appointment "
                            . "where slotid=$slotid";
                    $result = $this->db->query($query);
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        $user = $this->get_user_details($row['studentid']);
                        $students[] = $user->email;
                    } // end while
                } // end if $slotid>0
            } // end foreach 
            $mailer->send_workshop_notification($students, $message);
        } // end if count >0
    }

    function get_user_address_data($userid) {
        $query = "SELECT firstname, lastname, email, phone1, address, city, state, zip
                FROM  `mdl_user` where id=$userid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $user = new stdClass();
            foreach ($row as $key => $value) {
                $user->$key = $value;
            }
            $user->group_name = '';
        } // end while
        return $user;
    }

    function print_certificate_labels($courseid, $students) {
        $students_arr = explode(',', $students);
        if (count($students_arr) > 0) {
            $pdf = new PDF_Label('L7163');
            $pdf->AddPage();
            if (!is_dir($this->labels_path)) {
                if (!mkdir($dir_path)) {
                    die('Could not write to disk');
                } // end if !mkdir($dir_path)
            } // end if !is_dir($dir_path)                
            foreach ($students_arr as $userid) {
                $user_address = $this->get_user_address_data($userid);
                $text = sprintf("%s\n%s\n%s %s %s", "$user_address->firstname  $user_address->lastname", "$user_address->address", "$user_address->city ,", "$user_address->state", "$user_address->zip");
                $pdf->Add_Label($text);
            } // end foreach
            $now = time();
            $path = $this->labels_path . "/" . $now . "_merged.pdf";
            $pdf->Output($path, 'F');
        } // end if count($students_arr)>0
        return $now . "_merged.pdf";
    }

    function is_has_students($slotid) {
        $query = "select * from mdl_scheduler_appointment where slotid=$slotid";
        $num = $this->db->numrows($query);
        return $num;
    }

    function get_workshop_detailes($slotid) {
        $query = "select *  from mdl_scheduler_slots where id=$slotid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $ws = new stdClass();
            foreach ($row as $key => $value) {
                $ws->$key = $value;
            }
        } // end while
        return $ws;
    }

    function get_ekg_workshop($ws) {
        $schedulerid = 5; // courseid=45 - Phleb with EKG
        $query = "select *  from mdl_scheduler_slots "
                . "where schedulerid=$schedulerid "
                . "and appointmentlocation='$ws->appointmentlocation' "
                . "and notes='$ws->notes' and starttime='$ws->starttime'";
        $num = $this->db->numrows($query);
        return $num;
    }

    function fix_phleb_ws() {
        $non_exists = 0;
        $exists = 0;
        $workshops = array();
        $schedulerid = 6; // courseid=44 - Phleb
        $query = "select * from mdl_scheduler_slots  "
                . "where schedulerid=$schedulerid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $slotid = $row['id'];
            $has_students = $this->is_has_students($slotid);
            if ($has_students == 0) {
                // No students
                $ws = $this->get_workshop_detailes($slotid);
                $is_ekg_workshop_exists = $this->get_ekg_workshop($ws);
                if ($is_ekg_workshop_exists == 0) {
                    $non_exists++;
                    $query = "delete from mdl_scheduler_slots where id=$slotid";
                    echo "Query: " . $query . "<br>";
                    $this->db->query($query);
                } // end if $is_ekg_workshop_exists==0
                else {
                    $exists++;
                } // end else

                echo "<br><pre>";
                print_r($ws);
                echo "</pre>";

                echo "EKG workshop exists: $is_ekg_workshop_exists";
                echo "<br>------------------------------------------------<br>";
            } // end if $has_students==0
        } // end while
        echo "<br>Total existed ws: " . $exists . "<br>";
        echo "<br>Total non-exists: " . $non_exists . "<br>";
    }

    function delete_workshop($id) {
        $query = "delete from mdl_scheduler_slots where id=$id";
        //echo "Query: ".$query."<br>";
        $this->db->query($query);
        echo "ok";
    }

    function get_workshop_balance($slotid) {
        $list = "";
        $paid_amount = 0;
        $unpaid_amount = 0;
        $courseid = $this->get_course_id($slotid);
        //echo "Course id: ".$courseid."<br>";
        $query = "select * from mdl_scheduler_appointment where slotid=$slotid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $b = New Balance();
            $course_cost = $b->get_item_cost($courseid, $slotid);
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $status = $this->is_user_deleted($row['studentid']);
                if ($status == 0) {
                    $student_payment = $b->get_student_payments($courseid, $row['studentid']);
                    $paid_amount = $paid_amount + $student_payment;
                    $diff = $student_payment - $course_cost;
                    if ($diff < 0) {
                        $unpaid_amount = $unpaid_amount + abs($diff);
                    } // end if
                } // end if user->deleted=0 
            } // end while
            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span2'>Total students:</span>";
            $list.="<span class='span1'>$num</span>";
            $list.="<span class='span2'>Total paid:</span>";
            $list.="<span class='span1'>$$paid_amount</span>";
            $list.="<span class='span2'>Total unpaid: </span>";
            $list.="<span class='span1'>$$unpaid_amount</span>";
            $list.="</div>";
        } // end if $num>0
        else {
            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span2'>Total students:</span>";
            $list.="<span class='span1'>0</span>";
            $list.="<span class='span2'>Total paid:</span>";
            $list.="<span class='span1'>$0</span>";
            $list.="<span class='span2'>Total unpaid: </span>";
            $list.="<span class='span1'>$0</span>";
            $list.="</div>";
        } // end else

        return $list;
    }

    function get_course_cost($courseid) {
        $query = "select * from mdl_course where id=$courseid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $cost = $row['cost'];
        }
        return $cost;
    }

    function get_student_slot($courseid, $studentid) {

        $query = "select * from mdl_scheduler where course=$courseid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $schedulerid = $row['id'];
        }

        $query = "select * from mdl_scheduler_slots "
                . "where schedulerid=$schedulerid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $course_slots_arr[] = $row['id'];
        }

        $course_slots = implode(',', $course_slots_arr);

        $query = "select * from mdl_scheduler_appointment "
                . "where studentid=$studentid and slotid in ($course_slots)";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $slotid = $row['slotid'];
            } // end while
        } // end if $num > 0
        else {
            $slotid = 0;
        }
        return $slotid;
    }

    function check_students_balance($courseid, $students) {
        $b = new Balance();
        $students_arr = explode(',', $students);
        $total_balance = 0;
        if (is_array($students_arr)) {
            foreach ($students_arr as $studentid) {
                $slotid = $this->get_student_slot($courseid, $studentid);
                $balance = $b->get_user_balance($courseid, $studentid, $slotid);
                $total_balance = $total_balance + $balance;
            } // end foreach
            return $total_balance;
        } // is_array($students)
        else {
            $slotid = $this->get_student_slot($courseid, $students);
            $balance = $b->get_user_balance($courseid, $students, $slotid);
            return $balance;
        } // end else 
    }

    function get_student_payment($courseid, $userid) {
        $paid = 0;

        // Weird workaround for only three students
        if ($userid == 13325 || $userid == 13326 || $userid == 13327) {
            $paid = 500; // We make their payment as $500, but in fact they paid only $450
        } // end if $userid==13325 || $userid==13326 || $userid==13326
        else {
            // 1. Get payment from credit cards
            $query = "select * from mdl_card_payments "
                    . "where courseid=$courseid and userid=$userid";
            $num = $this->db->numrows($query);
            if ($num > 0) {
                $result = $this->db->query($query);
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $paid = $paid + $row['psum'];
                }
            }

            // 2. Get payment from cash or partial payments
            $query = "select * from mdl_partial_payments "
                    . "where courseid=$courseid and userid=$userid";
            $num = $this->db->numrows($query);
            if ($num > 0) {
                $result = $this->db->query($query);
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $paid = $paid + $row['psum'];
                }
            }

            // 3.Get payment from invoice table
            $query = "select * from mdl_invoice "
                    . "where courseid=$courseid "
                    . "and userid=$userid and i_status=1";
            $num = $this->db->numrows($query);
            if ($num > 0) {
                $result = $this->db->query($query);
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $paid = $paid + $row['i_sum'];
                }
            }
        } // end else 

        return $paid;
    }

    function get_user_payment_year($userid) {
        // Check credit card payments
        $query = "select * from mdl_card_payments where userid=$userid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $pdate = date('Y', $row['pdate']);
            }
        } // end if$num > 0
        // Check cash/cheque payments
        $query = "select * from mdl_partial_payments where userid=$userid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $pdate = date('Y', $row['pdate']);
            }
        } // end if$num > 0

        return $pdate;
    }

    function get_student_balance($courseid, $userid, $slotid) {
        $list = "";
        $b = new Balance();
        $course_cost = $b->get_item_cost($courseid, $slotid);
        $student_payment = $b->get_student_payments($courseid, $userid);
        //echo "User ID: ".$userid."<br>";
        //echo "Student payment: ".$student_payment."<br>";
        $diff = $student_payment - $course_cost;
        if ($diff < 0) {
            $unpaid_amount = abs($diff);
        } // end if $diff < 0
        else {
            $unpaid_amount = 0;
        } // end else
        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>Paid:</span>";
        $list.="<span class='span2'>$$student_payment</span>";
        $list.="<span class='span2'>Owe: </span>";
        $list.="<span class='span2'>$$unpaid_amount</span>";
        $list.="</div>";
        return $list;
    }

    //* ******************* Code related to Schedule ******************** *//

    function get_course_schedulerid($id) {
        $query = "select * from mdl_course_modules "
                . "where course=$id and module=23";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $id = $row['id'];
        }
        return $id;
    }

    function get_scheduled_courses() {

        $list = "";

        $query = "select * from mdl_scheduler";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $sch_id[] = $row['course'];
        }
        $sch_id_list = implode(',', $sch_id);
        $query = "select * from mdl_course "
                . "where visible=1 "
                . "and cost>0 "
                . "and id in ($sch_id_list)";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $name = $this->get_course_name($row['id']);
                $pageid = $this->get_course_schedulerid($row['id']);
                $list.="<div class='container-fluid' style='text-align:left;'>";
                $list.="<span class='span9'><a href='https://" . $_SERVER['SERVER_NAME'] . "/lms/mod/scheduler/view.php?id=$pageid' target='_blank'>$name</a></span>";
                $list.="</div>";
                $list.="<div class='container-fluid' style='text-align:left;'>";
                $list.="<span class='span9'><hr/></span>";
                $list.="</div>";
            } // end while
        } // end if $num > 0
        else {
            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span9'>N/A</span>";
            $list.="</div>";
        } // end else

        return $list;
    }

    function get_scheduler_venus() {
        $query = "select notes from mdl_scheduler_slots";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $notes[] = $row['notes'];
        }
        return $notes;
    }

    function get_schedule_page() {
        $list = "";
        $courses = $this->get_scheduled_courses();
        $list.="<div class='panel panel-default'>";
        $list.="<div class='panel-heading'style='text-align:left;'><h5 class='panel-title'>Workshops schedule</h5></div>";
        $list.="<div class='panel-body'>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span12'>$courses</span>";
        $list.="</div>";

        $list.="</div>";
        $list.="</div>";


        return $list;
    }

}
