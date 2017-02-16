<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php';

/**
 * Description of Grades
 *
 * @author moyo
 */
class Grades extends Util {

    function __construct() {
        parent::__construct();
    }

    function get_course_grade_items($courseid) {
        $items = array();
        $query = "select * from mdl_grade_items "
                . "where itemmodule='quiz' "
                . "and courseid=$courseid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $items[] = $row['id'];
            } // end while
        } // end if $num > 0
        return $items;
    }

    function get_quiz_item_name($id) {
        $query = "select * from mdl_grade_items where id=$id";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $name = $row['itemname'];
        }
        return $name;
    }

    function get_item_grade($item, $userid) {
        $query = "select * from mdl_grade_grades "
                . "where itemid=$item "
                . "and userid=$userid "
                . "and rawgrade is not null";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $pr = new stdClass();
                $name = $this->get_quiz_item_name($item);
                $date = date('m-d-Y', $row['timemodified']);
                $grade = round($row['rawgrade']);
                $pr->id = $item;
                $pr->name = $name;
                $pr->grade = $grade;
                $pr->date = $date;
            } // end while
        } // end if $num > 0
        else {
            $pr = null;
        }
        return $pr;
    }

    function get_student_grades($courseid, $userid) {
        $grades = array();
        $items = $this->get_course_grade_items($courseid);
        if (count($items) > 0) {
            foreach ($items as $item) {
                $gr = $this->get_item_grade($item, $userid);
                if ($gr != null) {
                    $grades[] = $gr;
                }  // end if $gr!=null
            } // end foreach
        } // end if count($items)>0
        return $grades;
    }

    function get_course_by_enrolid($enrolid) {
        $query = "select * from mdl_enrol where id=$enrolid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $courseid = $row['courseid'];
        }
        return $courseid;
    }

    function get_user_courses($userid) {
        $courses = array();
        $query = "select * from mdl_user_enrolments where userid=$userid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $courses[] = $this->get_course_by_enrolid($row['enrolid']);
            } // end while
        } // end if $num > 0
        return $courses;
    }

}
