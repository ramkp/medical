<?php

/**
 * Description of Certificate
 *
 * @author sirromas
 */
require_once ($_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/pdf/mpdf/mpdf.php');

class Certificates extends Util {

    public $cert_path;

    function __construct() {
        parent::__construct();
        $this->cert_path = $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/certificates';
    }

    function get_user_detailes($id) {
        $query = "select firstname, lastname, email from mdl_user where id=$id";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $user = new stdClass();
            foreach ($row as $key => $value) {
                $user->$key = $value;
            } // end foreach 
        } // end while
        return $user;
    }

    function get_course_name($id) {
        $query = "select fullname from mdl_course where id=$id";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $name = $row['fullname'];
        }
        return $name;
    }

    function get_certificates_list() {
        $certificates = array();
        $query = "select * from mdl_certificates order by issue_date";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $certificate = new stdClass();
                foreach ($row as $key => $value) {
                    $certificate->$key = $value;
                } // end foreach 
                $certificates[] = $certificate;
            } // end while
        } // end if $num > 0
        $list = $this->create_certificates_list($certificates);
        return $list;
    }

    function create_certificates_list($certificates) {
        $list = "";
        if (count($certificates) > 0) {
            foreach ($certificates as $certificate) {
                $user = $this->get_user_detailes($certificate->userid);
                $coursename = $this->get_course_name($certificate->courseid);
                $list.="<div class='container-fluid'>";
                $list.="<span class='span2'>Firstname</span><span class='span2'>$user->firstname</span>";
                $list.="</div>";
                $list.="<div class='container-fluid'>";
                $list.="<span class='span2'>Lastname</span><span class='span2'>$user->lastname</span>";
                $list.="</div>";
                $list.="<div class='container-fluid'>";
                $list.="<span class='span2'>Email</span><span class='span2'>$user->email</span>";
                $list.="</div>";
                $list.="<div class='container-fluid'>";
                $list.="<span class='span2'>Program name</span><span class='span2'>$coursename</span>";
                $list.="</div>";
                $list.="<div class='container-fluid'>";
                $list.="<span class='span2'>Issue date</span><span class='span2'>$certificate->issue_date</span>";
                $list.="</div>";
                $list.="<div class='container-fluid'>";
                $list.="<span class='span2'>Expiration date</span><span class='span2'>$certificate->expiration_date</span>";
                $list.="</div>";
            } // end foreach
            $list.=$this->get_send_certificate_page();
        } // end if count($certificates)>0
        else {
            $list.="<div class='container-fluid'>";
            $list.="<span class='span6'>There are no certificates issued</span>";
            $list.="</div>";
            $list.="<div class='container-fluid'>";
            $list.="<span class='span6'><a href='#' onClick='return false;' id='cert_send_page'>Send Certificate</a></span>";
            $list.="</div>";
            $list.=$this->get_send_certificate_page();
        } // end else 
        return $list;
    }

    function get_send_certificate_page() {
        $list = "";
        $programs_category = $this->get_course_categories();
        $list.="<div id='send_cert_container' style='display:none;'>";
        $list.="<div class='container-fluid'>";
        $list.="<span class='span2'>Program type</span><span class='span3'>$programs_category</span>";
        $list.="</div>";
        $list.="<div class='container-fluid'>";
        $list.="<span class='span8' id='category_courses'></span>";
        $list.="</div>";
        $list.="<div class='container-fluid'>";
        $list.="<span class='span8' id='enrolled_users'></span>";
        $list.="</div>";
        $list.="<div class='container-fluid'>";
        $list.="<span class='span2'><button type='button' id='send_cert' class='btn btn-primary'>Send</button></span>";
        $list.="</div></div>";
        return $list;
    }

    function get_course_completion($courseid, $userid) {
        $date = 0;
        $query = "select * from mdl_course_completions where "
                . "course=$courseid and userid=$userid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $date = date('Y-m-d', $row['timecompleted']);
            } // end while
        } // end if $num > 0)
        return $date;
    }

    function prepare_ceriticate($courseid, $userid, $date) {
        $list = "";
        $coursename = $this->get_course_name($courseid); // string
        $userdetails = $this->get_user_detailes($userid); // object
        $userdetails->firstname = 'Teresa';     // temp workaround
        $userdetails->lastname = 'Littleton';   // temp workaround
        $firstname = strtoupper($userdetails->firstname);
        $lastname = strtoupper($userdetails->lastname);
        $day = date('d', $date);
        $month = date('M', $date);
        $year = date('Y', $date);
        $expiration_date_sec = $date + 31536000;
        $expiration_date = strtoupper(date('m-d-Y', $expiration_date_sec));
        $list.="<!DOCTYPE HTML SYSTEM>";
        $list.="<head>";
        $list.="<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
        $list.="<link rel='stylesheet' type='text/css' href='cert.css'>";
        $list.="</head>";
        $list.="<body>";
        $list.="<div class='cert'>";
        $list.="<p style='align:center;font-family:king;font-weight:bolder;font-size:25pt;'>Medical2 Career College ";
        $list.="<br><span style='align:center;font-weight:bold;font-size:35pt;font-family: Geneva, Arial, Helvetica, sans-serif;'>$coursename<br>";
        $list.="<span style='align:center;font-weight:bold;font-size:15pt;'>Presents this certificate this the $day th of $month $year To:</span>";
        $list.="<br><span style='align:center;font-weight:bold;font-size:35pt;'>$firstname $lastname</span>";
        $list.="<br><span style='align:center;font-weight:bold;font-size:15pt;'>For successfully meeting all requirements to hold this certification.</span>";
        $list.="<br><br><br><br><br><p style='align:center;text-decoration:underline;font-size:15pt;font-weight:normal;'>CERTIFICATION #: 010836-IV12<br>";
        $list.="EXPIRATION DATE $expiration_date</p>";
        $list.="<div align='left' style='font-family:king;text-decoration:underline;border-bottom:thick;font-size:10pt;'>Shahid Malik<br/><span style='float:left;font-size:12pt;font-family: Geneva, Arial, Helvetica, sans-serif;'>Shahid Malik, President</span></div>";
        $list.="</div>";
        $list.="</body>";
        $list.="</html>";

        $pdf = new mPDF('utf-8', 'A4-L');
        $stylesheet = file_get_contents($this->cert_path . '/cert.css');
        $pdf->WriteHTML($stylesheet, 1);
        $pdf->WriteHTML($list, 2);
        $dir_path = $this->cert_path . "/$userid";
        if (!is_dir($dir_path)) {
            if (!mkdir($dir_path)) {
                die('Could not write to disk');
            } // end if !mkdir($dir_path)
        } // end if !is_dir($dir_path)
        $path = $dir_path . "/certificate.pdf";
        $pdf->Output($path, 'F');
        return $path;
    }

    function get_user_course_entollment_date($courseid, $userid) {
        $contextid = $this->get_course_context($courseid);
        $query = "select * from mdl_role_assignments "
                . "where roleid=$this->student_role "
                . "and contextid=$contextid "
                . "and userid=$userid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $date = $row['timemodified'];
        } // end while
        return $date;
    }

    function send_certificate($courseid, $userid, $date) {
        if ($date == 0) {
            $date = $this->get_user_course_entollment_date($courseid, $userid);
        }
        $expiration_date_sec = $date + 31536000;
        $path = $this->prepare_ceriticate($courseid, $userid, $date);
        $query = "insert into mdl_certificates (courseid,"
                . "userid,"
                . "path,"
                . "issue_date,"
                . "expiration_date) "
                . "values('$courseid',"
                . " '$userid',"
                . " '$path',"
                . " '$date',"
                . " '$expiration_date_sec')";
        $this->db->query($query);
        $list = "Certificate has been sent";
        return $list;
    }

}