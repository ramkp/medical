<?php

/**
 * Created by PhpStorm.
 * User: moyo
 * Date: 9/27/17
 * Time: 20:13
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php';

class Attendance extends Util {

    function __construct() {
        parent::__construct();
    }

    /**
     * @param $courseid
     * @param $userid
     * @return string
     */
    function get_user_attendance_logs($courseid, $userid) {
        $items = array();
        $list = "";
        $query = "select * from mdl_logstore_standard_log "
                . "where (courseid=$courseid or courseid=0) "
                . "and userid=$userid order by timecreated desc";
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
        $list .= $this->create_attendance_report($courseid, $items);
        return $list;
    }

    function get_log_item_course_id($item) {
        $query = "select * from mdl_logstore_standard_log where id=$item->id";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $courseid = $row['courseid'];
        }
        return $courseid;
    }

    /**
     * @param $items
     * @return string
     */
    function create_attendance_report($courseid, $items) {
        $list = "";
        $list .= "<table id='att_table_$courseid' class='display' cellspacing='0' width='100%'>";
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
                $coursename = $this->get_course_name($courseid);
                $itemcourseid = $this->get_log_item_course_id($item);
                if ($itemcourseid == 0) {
                    $component = 'system';
                } // end if
                else {
                    $component = ($item->component == 'core') ? 'program content' : $item->component;
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
