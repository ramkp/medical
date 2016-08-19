<?php

/**
 * Description of Schedule
 *
 * @author sirromas
 */
//session_start();
require_once ($_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php');
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/certificates/classes/Certificates.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/PDF_Label.php';

class Schedule extends Util {

    public $courseid;
    public $labels_path;

    function __construct() {
        global $COURSE;
        parent::__construct();
        $this->courseid = $COURSE->id;
        $this->labels_path = $_SERVER['DOCUMENT_ROOT'] . '/print';
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
        $now = time() - 86400;
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

    function is_user_paid() {
        
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
        $query = "select * from mdl_course_modules "
                . "where module=23 "
                . "and course=$courseid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $id = $row['id'];
        }
        return $id;
    }

    function create_slots_page($slots, $tools = true) {
        global $COURSE;
        $courseid = $COURSE->id;
        //echo "Course id: " . $courseid . "<br>";
        $qs = $_SERVER['QUERY_STRING'];
        $modid = trim(str_replace("id=", "", $qs));

        if ($modid == '') {
            $modid = $this->get_scheduler_module_id($courseid);
        }

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
            $list.="<span class='span2'><a href='#' id='delete' onClick='return false;'>Remove</a></span>";
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
                $editactionurl = "https://medical2.com/lms/mod/scheduler/view.php?id=" . $modid . "&what=updateslot&subpage=myappointments&offset=-1&sesskey=" . sesskey() . "&slotid=" . $slotid . "";
                $addr_array = explode("/", $slot->appointmentlocation);
                $addr_block = $addr_array[1] . " , " . $addr_array[0];
                $list.="<div class='panel panel-default'>";
                $list.="<div class='panel-heading'style='text-align:left;'><h5 class='panel-title'>$addr_block, " . date('m-d-Y h:i:s', $slot->starttime) . "&nbsp;<a href='$editactionurl'><img src='https://medical2.com/lms/theme/image.php/lambda/core/1464336624/t/edit' title='Edit'></a><br> $slot->notes</h5></div>";
                $list.="<div class='panel-body' id='$slotid'>";
                $slot_students = $this->get_slot_students($slot->id);
                $courseid = $this->get_course_id($slot->id);
                if (count($slot_students) > 0) {
                    $list.= "<div class='container-fluid' style='text-align:left;font-weight:bold;'>";
                    $list.="<span class='span1'></span>";
                    $list.="<span class='span3'>Student</span>";
                    $list.="<span class='span4'>Course completion status</span>";
                    $list.="</div>";
                    $list.= "<div class='container-fluid' style='text-align:left;'>";
                    $list.="<span class='span1'><input type='checkbox' name='studentid' id='slot_students_$slotid' value=''></span>";
                    $list.="<span class='span3'>Select all</span>";
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
        return $list;
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
                $cert->send_certificate($courseid, $studentid, $now, false);
                $pdf_file = $_SERVER['DOCUMENT_ROOT'] . "/lms/custom/certificates/$studentid/certificate.pdf";
                //$jpg_file = $_SERVER['DOCUMENT_ROOT'] . "/lms/custom/certificates/$studentid/certificate.jpg";
                //exec("convert -density 300 $pdf_file $jpg_file");
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
        date_default_timezone_set('Pacific/Wallis');
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
        $slots = $this->get_students_course_slots($schedulerid);
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
            </div>
            
            <div class='container-fluid' style='text-align:left;'>
            <span class='span2'>$slots</span>    
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

    function get_workshops_list($students, $schedulerid) {
        $list = "";
        $slots = $this->get_students_course_slots($schedulerid);
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
            <span class='span2'>$slots</span>    
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

    function add_user_to_slot($lsotid, $userid) {
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

    function move_students($newslotid, $students, $schedulerid) {
        $students_arr = explode(",", $students);
        if (count($students_arr) > 0) {
            foreach ($students_arr as $studentid) {
                $oldslotid = $this->get_current_student_slot($studentid, $schedulerid);
                $query = "update mdl_scheduler_appointment "
                        . "set slotid=$newslotid "
                        . "where studentid=$studentid and slotid=$oldslotid";
                //echo "Query: " . $query . "<br>";
                $this->db->query($query);

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

    function save_additional_slot($slot) {

        //print_r($slot);
        //die();
        //$date=date('m-d-Y', $slot->starttime);
        //echo "Slot date: ".$date."<br>";
        $query = "insert into mdl_scheduler_slots "
                . "(schedulerid,"
                . "starttime,"
                . "duration,"
                . "teacherid,"
                . "appointmentlocation,"
                . "timemodified,"
                . "notes,"
                . "hideuntil) "
                . "values($slot->schedulerid,"
                . "'$slot->starttime',"
                . "'480',"
                . "'234',"
                . "'$slot->appointmentlocation',"
                . "'$slot->timemodified',"
                . "'$slot->notes',"
                . "'$slot->timemodified')";
        //echo "Query: ".$query."<br>";
        $this->db->query($query);
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

}
