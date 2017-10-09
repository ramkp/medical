<?php

/**
 * Created by PhpStorm.
 * User: moyo
 * Date: 9/27/17
 * Time: 20:13
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php';

class Attendance extends Util
{


    function __construct()
    {
        parent::__construct();

    }


    /**
     * @param $courseid
     * @param $userid
     * @return array
     */
    function get_user_login_attempts($userid)
    {
        $query = "select * from mdl_logstore_standard_log "
            . "where courseid=0 "
            . "and userid=$userid and action<>'viewed' 
            order by timecreated desc";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $item = new stdClass();
                foreach ($row as $key => $value) {
                    $item->$key = $value;
                }
                $items[] = $item;
            } // end while
        } // end if $num > 0
        return $items;
    }

    /**
     * @param $courseid
     * @param $userid
     * @return string
     */
    function get_user_attendance_logs($courseid, $userid)
    {
        $items = array();
        $list = "";
        $query = "select * from mdl_logstore_standard_log "
            . "where courseid=$courseid "
            . "and userid=$userid and component in 
            ('mod_assign',
            'mod_forum',
            'mod_quiz',
            'mod_scheduler', 
            'gradereport_grader',
            'gradereport_user') and action<>'viewed'
            order by timecreated desc";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $item = new stdClass();
                foreach ($row as $key => $value) {
                    $item->$key = $value;
                }
                $items[] = $item;
            } // end while
        } // end if $num > 0
        if ($courseid == 41 || $courseid == 55 || $courseid == 56) {
            $system_items = $this->get_user_login_attempts($userid);
            $list .= $this->create_attendance_report($courseid, $system_items, true);
        } // end if    if ($courseid==41 || $courseid==55 || $courseid==56) {
        else {
            $list .= $this->create_attendance_report($courseid, $items);
        }
        return $list;
    }

    /**
     * @param $item
     * @return mixed
     */
    function get_log_item_course_id($item)
    {
        $query = "select * from mdl_logstore_standard_log where id=$item->id";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $courseid = $row['courseid'];
        }
        return $courseid;
    }

    /**
     * @param $mod
     * @return string
     */
    function get_component_name($mod)
    {
        switch ($mod) {
            case 'mod_assign':
                $name = 'Assignment';
                break;
            case 'mod_forum':
                $name = 'Forum';
                break;
            case 'mod_quiz':
                $name = 'Quiz';
                break;
            case 'mod_scheduler':
                $name = 'Course Scheduler';
                break;
            case 'gradereport_grader':
                $name = 'Grader Report';
                break;
            case 'gradereport_user':
                $name = 'Grader Report';
                break;
        }
        return $name;
    }

    /**
     * @param $items
     * @return string
     */
    function create_attendance_report($courseid, $items, $system = false)
    {
        $list = "";
        if ($system == false) {
            $list .= "<table id='att_table_$courseid' class='display' cellspacing='0' width='100%'>";
        } // end if
        else {
            $list .= "<table id='system_att_table_$courseid' class='display' cellspacing='0' width='100%'>";
        }
        $list .= "<thead>";
        $list .= "<tr>";
        $list .= "<th>Course name</th>";
        $list .= "<th>Course item</th>";
        $list .= "<th>Action</th>";
        $list .= "<th>Date</th>";
        $list .= "<th>IP</th>";
        $list .= "</tr>";
        $list .= "</thead>";
        $list .= "<tbody>";
        if (count($items) > 0) {
            foreach ($items as $item) {
                $date = date('m-d-Y H:i:s', $item->timecreated);
                if ($system == false) {
                    $coursename = $this->get_course_name($courseid);
                } // end if
                else {
                    $coursename = 'System';
                }
                $itemcourseid = $this->get_log_item_course_id($item);
                if ($itemcourseid == 0) {
                    $component = 'system';
                } // end if
                else {
                    //$component = ($item->component == 'core') ? 'program content' : $item->component;
                    $component = $this->get_component_name($item->component);
                }
                $list .= "<tr>";
                $list .= "<td>$coursename</td>";
                $list .= "<td>$component</td>";
                $list .= "<td>$item->action</td>";
                $list .= "<td>$date</td>";
                $list .= "<td>$item->ip</td>";
                $list .= "</tr>";
            }
        } // end if count($items)>0
        $list .= "</tbody>";
        $list .= "</table>";
        return $list;
    }

}
