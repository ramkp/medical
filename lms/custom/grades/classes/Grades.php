<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/pdf/mpdf/mpdf.php';

class Grades extends Util {

    public $report_dir;

    function __construct() {
        parent::__construct();
        $this->report_dir = $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/grades';
    }

    function get_course_grade_items($courseid) {
        $query = "select * from mdl_grade_items "
                . "where courseid=$courseid and (itemmodule='quiz' or itemmodule='assign') ";
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
                . "and finalgrade is not null";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $pr = new stdClass();
                $name = $this->get_quiz_item_name($item);
                $date = date('m-d-Y', $row['timemodified']);
                $grade = round($row['finalgrade']);
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

        //echo "Course id: ".$courseid."<br>";
        //echo "User id: ".$userid."<br>";

        $items = $this->get_course_grade_items($courseid);

        /*
          echo "<pre>";
          print_r($items);
          echo "</pre><br>------------------------<br>";
         */

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
        /*
         * 
          $courses = array();
          $query = "select * from mdl_user_enrolments where userid=$userid";
          $num = $this->db->numrows($query);
          if ($num > 0) {
          $result = $this->db->query($query);
          while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
          $courses[] = $this->get_course_by_enrolid($row['enrolid']);
          } // end while
          } // end if $num > 0
         * 
         */
        $courses = $this->get_user_courses_by_student_role($userid);
        return $courses;
    }

    function get_user_courses_by_student_role($userid) {
        $courses = array();
        $query = "select * from mdl_role_assignments "
                . "where roleid=5 and userid=$userid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $courses[] = $this->get_course_by_contextid($row['contextid']);
            } // end while 
        } // end if $num > 0
        return $courses;
    }

    function get_course_by_contextid($contextid) {
        $query = "select * from mdl_context where contextlevel=50 "
                . "and id=$contextid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $courseid = $row['instanceid'];
        }
        return $courseid;
    }

    function create_pdf_report($userid) {
        $list = "";
        $user = $this->get_user_details($userid);
        $courses = $this->get_user_courses($userid);
        $list.="<p align='center' style='font-weight:bold;'>$user->firstname $user->lastname</p>";
        if (count($courses) > 0) {
            $list.="<table align='center'>";
            foreach ($courses as $courseid) {
                $grades = $this->get_student_grades($courseid, $userid);
                $coursename = $this->get_course_name($courseid);
                $list.="<tr>";
                $list.="<th colspan='3'>$coursename</th>";
                $list.="</tr>";
                if (count($grades) > 0) {
                    foreach ($grades as $gr) {
                        $list.="<tr>";
                        $list.="<td style='padding:15px;'>$gr->name</td>";
                        $list.="<td style='padding:15px;'>$gr->grade%</td>";
                        $list.="<td style='padding:15px;'>$gr->date</td>";
                        $list.="</tr>";
                    } // end foreach
                } // end if count($grades)>0
            } // end foreach
            $list.="</table>";
        } // end if count($courses)>0
        $dir = $this->report_dir . "/$userid";
        if (!is_dir($dir)) {
            if (!mkdir($dir)) {
                die('Could not write to disk');
            } // end if !mkdir($dir_path)
        }
        $file = "grades_report.pdf";
        $path = $dir . "/$file";
        $pdf = new mPDF('utf-8', 'A4-P');
        $pdf->WriteHTML($list);
        $pdf->Output($path, 'F');
        return $file;
    }

}
