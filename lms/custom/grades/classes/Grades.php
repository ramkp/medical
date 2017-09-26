<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/pdf/mpdf/mpdf.php';

class Grades extends Util
{

    /**
     * @var string
     */
    public $report_dir;

    /**
     * Grades constructor.
     */
    function __construct()
    {
        parent::__construct();
        $this->report_dir = $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/grades';
    }

    /**
     * @param $courseid
     * @return array
     */
    function get_course_grade_items($courseid)
    {
        $items = array();
        if ($courseid > 0) {
            $query = "select * from mdl_grade_items "
                . "where courseid=$courseid and (itemmodule='quiz' or itemmodule='assign') ";
            $num = $this->db->numrows($query);
            if ($num > 0) {
                $result = $this->db->query($query);
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $items[] = $row['id'];
                } // end while
            } // end if $num > 0
        } // end if $courseid > 0
        return $items;
    }

    /**
     * @param $id
     * @return mixed
     */
    function get_quiz_item_name($id)
    {
        $query = "select * from mdl_grade_items where id=$id";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $name = $row['itemname'];
        }
        return $name;
    }

    /**
     * @param $val
     * @param string $f
     * @return float
     */
    function truncate($val, $f = "0")
    {
        if (($p = strpos($val, '.')) !== false) {
            $val = floatval(substr($val, 0, $p + 1 + $f));
        }
        return $val;
    }

    /**
     * @param $item
     * @param $userid
     * @return null|stdClass
     */
    function get_item_grade($item, $userid)
    {
        $query = "select * from mdl_grade_grades "
            . "where itemid=$item "
            . "and userid=$userid "
            . "and finalgrade is not null ";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $pr = new stdClass();
                $name = $this->get_quiz_item_name($item);
                $date = date('m-d-Y', $row['timemodified']);
               $grade = $row['finalgrade'];
                $pr->id = $item;
                $pr->name = $name;
                $pr->grade = $grade;
                $pr->date = $date;
                $pr->max = $row['rawgrademax'];
            } // end while
        } // end if $num > 0
        else {
            $pr = null;
        }
        return $pr;
    }

    /**
     * @param $courseid
     * @param $userid
     * @return array
     */
    function get_student_grades($courseid, $userid)
    {
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

    /**
     * @param $enrolid
     * @return mixed
     */
    function get_course_by_enrolid($enrolid)
    {
        $query = "select * from mdl_enrol where id=$enrolid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $courseid = $row['courseid'];
        }
        return $courseid;
    }

    /**
     * @param $userid
     * @return array
     */
    function get_user_courses($userid)
    {
        $courses = $this->get_user_courses_by_student_role($userid);
        return $courses;
    }

    /**
     * @param $userid
     * @return array
     */
    function get_user_courses_by_student_role($userid)
    {
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

    /**
     * @param $contextid
     * @return mixed
     */
    function get_course_by_contextid($contextid)
    {
        $query = "select * from mdl_context where contextlevel=50 "
            . "and id=$contextid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $courseid = $row['instanceid'];
        }
        return $courseid;
    }

    /**
     * @param $refid
     * @param $courseid
     * @param $userid
     * @return string
     */
    function create_user_separate_grade_reports($refid, $courseid, $userid)
    {
        $list = "";
        $file = '';
        $user = $this->get_user_details($userid);
        $grades = $this->get_student_grades($courseid, $userid);
        $coursename = $this->get_course_name($courseid);

        if (count($grades) > 0) {
            $list .= "<html>";

            $list .= "<head>";

            $list .= "<style>";

            $list .= "</style>";

            $list .= "</head>";

            $list .= "<body>";


            $list .= "<table align='center' border='0' width='100%'>

                    <tr>

                        <td style='padding-top:10px;'><img src='https://medical2.com/assets/icons/logo3.png' width='115' height='105'></td>
                        
                        <td valign='top'>
                        
                        <table style='padding:15px;font-size:12px;' align='left'>

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

                            </table>  
                            
                            </td>
                     
                        <td align='right' valign='top'>

                            <table style='padding:15px;font-size:12px;'>

                                <tr>
                                    <td style='padding-top:5px;'>Phone: 877-741-1996</td>
                                </tr>  

                                <tr>
                                    <td>Fax: 1-407-233-1192</td>
                                </tr> 

                                <tr>
                                    <td style='padding-top:25px;'>Email: info@medical2.com</td>
                                </tr> 

                                <tr>
                                    <td>Web: www.medical2.com</td>
                                </tr>  

                            </table>    

                        </td>

                        </tr>

                     </table>";


            $list .= "<p style='text-align:center;'>$user->firstname $user->lastname</p>";


            $list .= "<table align='center'>";
            $list .= "<tr>";
            $list .= "<th colspan='3'>$coursename</th>";
            $list .= "</tr>";
            $total_grade = 0;
            $total_grade_max = 0;

            foreach ($grades as $gr) {
                $total_grade = $total_grade + $gr->grade;
                $total_grade_max = $total_grade_max + $gr->max;
                $list .= "<tr>";
                $list .= "<td style='padding:15px;'>$gr->name</td>";
                $list .= "<td style='padding:15px;'>$gr->grade%</td>";
                $list .= "<td style='padding:15px;'>$gr->date</td>";
                $list .= "</tr>";
            } // end foreach

            $average = round(($total_grade / $total_grade_max) * 100);
            $list .= "<tr>";
            $list .= "<td style='padding:15px;' colspan='3'><hr/></td>";
            $list .= "</tr>";
            $list .= "<tr>";
            $list .= "<td style='padding:15px;'>Total grade $total_grade points</td>";
            $list .= "</tr>";
            $list .= "<tr>";
            $list .= "<td style='padding:15px;'>Total course max $total_grade_max points</td>";
            $list .= "</tr>";
            $list .= "<tr>";
            $list .= "<td style='padding:15px;'>Average $average %</td>";
            $list .= "</tr>";
            $list .= "</table>";

            $list .= "</body>";

            $list .= "</html>";

            $dir = $this->report_dir . "/$userid";
            if (!is_dir($dir)) {
                if (!mkdir($dir)) {
                    die('Could not write to disk');
                } // end if !mkdir($dir_path)
            }
            $file = "grades_report_$refid.pdf";
            $path = $dir . "/$file";
            $pdf = new mPDF('utf-8', 'A4-P');
            $pdf->WriteHTML($list);
            $pdf->Output($path, 'F');
        } // end if count of grades

        return $file;
    }

    /**
     * @param $userid
     * @return string
     */
    function create_pdf_report($userid)
    {
        $courses = $this->get_user_courses($userid);

        if (count($courses) > 0) {
            $flash = time() . $userid;
            $i = 0;
            foreach ($courses as $courseid) {
                $refID = $flash . $i;
                $file = $this->create_user_separate_grade_reports($refID, $courseid, $userid);
                if ($file != '') {
                    $files[] = $_SERVER['DOCUMENT_ROOT'] . "/lms/custom/grades/$userid/" . $file;
                }
                $i++;
            } // end foreach
        } // end if count($courses)>0

        $outputName = $this->report_dir . "/$userid/" . $flash . "_merged.pdf";
        $cmd = "gs -q -dNOPAUSE -dBATCH -sDEVICE=pdfwrite -sOutputFile=$outputName ";
        foreach ($files as $file) {
            $cmd .= $file . " ";
        } // end foreach
        shell_exec($cmd);
        return $flash . "_merged.pdf";
    }

}
