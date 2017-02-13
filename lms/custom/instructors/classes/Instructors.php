<?php

require_once ('/home/cnausa/public_html/lms/custom/utils/classes/Util.php');
require_once ('/home/cnausa/public_html/lms/custom/calendar/classes/Calendar.php');

/**
 * Description of Instructors
 *
 * @author moyo
 */
class Instructors extends Util {

    public $limit = 3;

    function __construct() {
        parent::__construct();
    }

    function get_instructors_page() {
        $list = "";
        $instructors = array();
        $query = "SELECT u.id, a.userid, a.roleid
                    FROM mdl_user u, mdl_role_assignments a
                    WHERE a.roleid =3
                    AND u.id = a.userid
                    GROUP BY u.id
                    LIMIT 0 , $this->limit";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $not_deleted = $this->is_user_deleted($row['userid']);
                if ($not_deleted == 0) {
                    $in = new stdClass();
                    foreach ($row as $key => $value) {
                        $in->$key = $value;
                    } // end foreach
                    $instructors[] = $in;
                } // end if $not_deleted == 0
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

    function get_total() {
        $query = "SELECT u.id, a.userid, a.roleid
                    FROM mdl_user u, mdl_role_assignments a
                    WHERE a.roleid =3
                    AND u.id = a.userid GROUP BY u.id";
        $num = $this->db->numrows($query);
        return $num;
    }

    function get_instructor_item($page) {
        $instructors = array();
        $rec_limit = $this->limit;
        if ($page == 1) {
            $offset = 0;
        } // end if $page==1
        else {
            $page = $page - 1;
            $offset = $rec_limit * $page;
        }
        $query = "SELECT u.id, a.userid, a.roleid
                    FROM mdl_user u, mdl_role_assignments a
                    WHERE a.roleid =3
                    AND u.id = a.userid
                    GROUP BY u.id
                    LIMIT $offset, $rec_limit";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $in = new stdClass();
            foreach ($row as $key => $value) {
                $in->$key = $value;
            } // end foreach
            $instructors[] = $in;
        } // end while
        $list = $this->create_instructors_page($instructors, false);
        return $list;
    }

    function get_closest_workshops($user) {
        $list = "";
        $state = $user->state;
        $city = $user->city;
        $list.="<div class='row-fluid'>";
        $list.="<span class='span4'>";
        $list.="<select id='cws_$user->id' style='width:175px;'>";
        $list.="<option value='0'>Please select</option>";
        $query = "select * from mdl_scheduler_slots order by starttime desc";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $place_arr = explode('/', $row['appointmentlocation']);
                if ($place_arr[0] == $state || $place_arr[1] == $city) {
                    $list.="<option value='" . $row['id'] . "'>" . date('m-d-Y', $row['starttime']) . " $place_arr[1], $place_arr[0]</option>";
                } // end if $place_arr[0] == $state ...
            } // end while
        } // end if $num>0
        $list.="</select>";
        $list.="</span>";
        $list.="<span class='span1' style='padding-left:100px;'>";
        $list.="<button class='add_instructor' data-inst_userid='$user->id'>Assign</button>";
        $list.="</span>";
        $list.="</div>";
        return $list;
    }

    function create_instructors_page($instructors, $toolbar = true) {
        $list = "";

        if (count($instructors) > 0) {

            if ($toolbar) {
                $list.="<div class='row-fluid' style='font-weight:bold;'>";
                $list.="<span class='span1'>Search</span>";
                $list.="<span class='span2'><input type='text' id='instructor_state' placeholder='State' style='width:125px;'></span>";
                $list.="<span class='span2'><input type='text' id='instructor_city' placeholder='City' style='width:125px;'></span>";
                $list.="<span class='span2'><button id='search_instuctor'>Search</button></span>";
                $list.="<span class='span2'><button id='reset_instuctor'>Reset</button></span>";
                $list.="</div>";
                $list.="<div class='row-fluid'>";
                $list.="<span class='span9' id='inst_err'></span>";
                $list.="</div>";
                $list.="<div class='container-fluid' style='display:none;text-align:center;' id='ajax_loader'>";
                $list.="<span class='span9'><img src='https://$this->host/assets/img/ajax.gif' /></span>";
                $list.="</div><br>";
            }
            $list.="<div id='inst_container'>";
            foreach ($instructors as $in) {
                $user = $this->get_user_details($in->userid);
                $wsblock = $this->get_closest_workshops($user);
                $courses_block = $this->get_instructor_courses_block($in->userid);
                $list.="<div class='container-fluid'>";
                $list.="<span class='span2'><a href='https://" . $_SERVER['SERVER_NAME'] . "/lms/user/profile.php?id=$in->userid' target='_blank'>$user->firstname $user->lastname<br>$user->city, $user->state $user->zip</a></span>";
                $list.="<span class='span1'><img style='cursor:pointer;' title='Availability' src='https://" . $_SERVER['SERVER_NAME'] . "/lms/theme/image.php/lambda/core/1468523658/t/edit' id='instructor_dialog_$in->userid'></span>";
                $list.="<span class='span5'>$courses_block</span>";
                $list.="<span class='span4'>$wsblock</span>";
                $list.="</div>";
                $list.="<div class='container-fluid'>";
                $list.="<span class='span12'><hr/></span>";
                $list.="</div>";
            } // end foreach
            $list.="</div>";

            if ($toolbar) {
                $list.="<div class='container-fluid'>";
                $list.="<span class='span9'  id='pagination'></span>";
                $list.="</div>";
            }
        } // end count($instructors)>0
        else {
            $list.="<div class='container-fluid'>";
            $list.="<span class='span9'>There are no instructors in the system</span>";
            $list.="</div>";
        }

        return $list;
    }

    function add_instructor_to_workshop($in) {

        echo "<pre>";
        print_r($in);
        echo "</pre>";

        // 1. Enroll into course as teacher if not enrolled
        // 2. Add user to mdl_scheduler_appointment 
    }

    function get_settings_dialog($userid) {
        $list = "";

        $cal = new Calendar();
        $dates = $cal->get_availabily_dates($userid);

        $list.="<div id='myModal' class='modal fade'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h4 class='modal-title'>Instructor's Availability</h4>
                </div>
                <div class='modal-body'>
                <input type='hidden' id='inst_settings_userid' value='$userid'>
                <div class='container-fluid' style=''>
                <span class='span6'>$dates</span>
                </div>
                
                <div class='container-fluid' style=''>
                <span class='span6' style='color:red;' id='inst_err'></span>
                </div>
             
                <div class='modal-footer' style='text-align:center;'>
                    <span align='center'><button type='button' class='btn btn-primary' data-dismiss='modal' id='cancel'>OK</button></span>
                </div>
            </div>
        </div>
    </div>";

        return $list;
    }

    function is_user_in_state($userid, $state) {
        $query = "select * from mdl_user where id=$userid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $dbstate = $row['state'];
        }
        if ($dbstate != '') {
            $query = "select * from mdl_user where id=$userid and "
                    . "state like '%$state%'";
            //echo "Query: " . $query . "<br>";
            $num = $this->db->numrows($query);
        } // end if $dbstate != ''
        else {
            $num = 0;
        }
        return $num;
    }

    function is_user_in_city($userid, $city) {
        $query = "select * from mdl_user where id=$userid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $dbcity = $row['city'];
        }
        if ($dbcity != '') {
            $query = "select * from mdl_user where id=$userid "
                    . "and city like '%$city%'";
            //echo "Query: " . $query . "<br>";
            $num = $this->db->numrows($query);
        } // end if $dbcity!=''
        else {
            $num = 0;
        }
        return $num;
    }

    function search_item($item) {
        $instructors = array();
        $query = "SELECT u.id, a.userid, a.roleid
                    FROM mdl_user u, mdl_role_assignments a
                    WHERE a.roleid =3
                    AND u.id = a.userid GROUP BY u.id";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $users[] = $row['userid'];
            } // end while
        }
        if (count($users) > 0) {
            foreach ($users as $userid) {
                $not_deleted = $this->is_user_deleted($userid);
                if ($not_deleted == 0) {
                    if ($item->city == '') {
                        $status = $this->is_user_in_state($userid, $item->state);
                        if ($status > 0) {
                            $in = new stdClass();
                            $in->userid = $userid;
                            $instructors[] = $in;
                        } // end if $status>0
                    } // end if $item->city == ''
                    else {
                        $status = $this->is_user_in_city($userid, $item->city);
                        if ($status > 0) {
                            $in = new stdClass();
                            $in->userid = $userid;
                            $instructors[] = $in;
                        } // end else
                    } // end else
                } // end id not_deleted == 0
            } // end foreach
        } // end if count($users)>0
        $list = $this->create_instructors_page($instructors, FALSE);
        return $list;
    }

    function get_instructor_group_students($courseid, $instid) {
        $query = "select g.id, "
                . "g.courseid, "
                . "g.name, "
                . "gm.groupid, "
                . "gm.userid "
                . "from mdl_groups g, "
                . "mdl_groups_members gm "
                . "where g.courseid=$courseid "
                . "and g.id=gm.groupid "
                . "and gm.userid=$instid";
        //echo "Query: " . $query . "<br>";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                
            }
        }
    }

    function get_instructor_students_attendance_block() {

        parse_str($_SERVER['QUERY_STRING']);
        $userid = $this->user->id;
        $groups = $this->get_instructor_group_students($id, $userid);
        $list = "";

        if (count($groups) > 0) {
            foreach ($groups as $groupid) {
                
            } // end foreach
        } // end if count($groups)>0

        return $list;
    }

}
