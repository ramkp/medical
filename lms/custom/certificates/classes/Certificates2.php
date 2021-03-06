<?php

/**
 * Description of Certificates2
 *
 * @author moyo
 */
require_once('/home/cnausa/public_html/lms/class.pdo.database.php');
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/pdf/mpdf/mpdf.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/PDF_Label.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/Mailer.php';
//require_once ($_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php');
include $_SERVER['DOCUMENT_ROOT'] . "/lms/editor/fckeditor.php";

class Certificates2
{

    public $db;
    public $cert_path;

    function __construct()
    {
        $this->db = new pdo_db();
        $this->cert_path = $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/certificates';
    }

    function get_course_name($id)
    {
        $query = "select fullname from mdl_course where id=$id";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $name = $row['fullname'];
        }
        return $name;
    }

    function get_user_details($id)
    {
        $query = "select * from mdl_user where id=$id";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $user = new stdClass();
            if ($row['firstname'] != '' && $row['lastname'] != '') {
                $user->firstname = $row['firstname'];
                $user->lastname = $row['lastname'];
                $user->email = $row['email'];
                $user->id = $row['id'];
            } // end if $row['firstname'] != '' && $row['lastname'] != ''
        } // end while
        return $user;
    }

    function get_certificate_detailes($courseid, $userid)
    {
        $query = "select * from mdl_certificates "
            . "where courseid=$courseid and userid=$userid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $cert = new stdClass();
                $cert->id = $row['id'];
                $cert->issue_date = $row['issue_date'];
                $cert->expire_date = $row['expiration_date'];
                $cert->code = $row['cert_no'];
            }
        } // end if
        else {
            $cert = 'n/a';
        } // end else
        return $cert;
    }

    function update_certificate_data($courseid, $userid, $expiration)
    {
        $query = "update mdl_certificates "
            . "set expiration_date='$expiration' "
            . "where courseid=$courseid and userid=$userid";
        $this->db->query($query);
    }

    function get_certificate_title($courseid)
    {
        $query = "select category from mdl_course where id=$courseid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $category = $row['category'];
        } // end while
        if ($category == 2 || $category == 4) {
            $title = "Medical2 Certification Agency";
        } // end if $category == 2 || $category == 4        
        else {
            $title = "Medical2 Career College";
        }
        return $title;
    }

    function get_course_renew_status($courseid)
    {
        $query = "select expired from mdl_course where id=$courseid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $expire = $row['expired'];
        }
        return $expire;
    }

    function get_certificate_template($courseid, $userid, $expire_year)
    {
        $list = "";
        $coursename = $this->get_course_name($courseid); // string
        $userdetails = $this->get_user_details($userid); // object
        $firstname = strtoupper($userdetails->firstname);
        $lastname = strtoupper($userdetails->lastname);
        $renew_status = $this->get_course_renew_status($courseid);
        $cert_data = $this->get_certificate_detailes($courseid, $userid); //object

        if ($cert_data != 'n/a') {

            $code = $cert_data->code;

            $day = date('dS', $cert_data->issue_date);
            $month = date('F', $cert_data->issue_date);
            $year = date('Y', $cert_data->issue_date);
            $title = $this->get_certificate_title($courseid);

            $expiration_date_sec = $cert_data->expire_date + 31536000 * $expire_year;
            $expiration_date = date('m-d-Y', $expiration_date_sec);

            switch ($courseid) {

                case 41:
                    $list .= "<!DOCTYPE HTML SYSTEM>";
                    $list .= "<head>";
                    $list .= "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                    $list .= "</head>";
                    $list .= "<body>";
                    $list .= "<div>";
                    $list .= "<p style='text-align:center'><span style='text-align:center'><img src='/assets/logo/5.png' width='55%' height='15%'></span><br>";
                    $list .= "<span style='align:center;font-weight:bold;font-size:8pt;'>Licensed by the Mississippi Commission on Proprietary School and College Registration, License No. C675</span>";
                    $list .= "<br><br><span style='align:center;font-weight:bold;font-size:35pt;'>Certification of Completion</span><br>";
                    $list .= "<span style='align:center;font-size:16pt;'>Presents and declares on this the $day of $month, $year</span><br>";
                    $list .= "<span style='align:center;font-size:40pt;'>$firstname $lastname</span><br>";
                    $list .= "<span style='align:center;font-size:16pt;'>has successfully completed the </span><br>";
                    $list .= "<span style='align:center;font-size:26pt;'>Certified Nurse Assistant Program</span><br>";
                    $list .= "<br><br><span style='align:center;text-decoration:underline;font-size:15pt;font-weight:normal;'>CERTIFICATION # $code</span><br>";
                    if ($renew_status == true) {
                        $list .= "EXPIRATION DATE $expiration_date</p>";
                    } // end if $renew_status == true                                 
                    // President signature
                    $list .= "<br><br><p align='center'><table border='0' width='675px;'><tr><td align='right' style='font-family:king;text-decoration:underline;border-bottom:thick;font-size:10pt;'> Mrs. P. Nicole Morrison<br/><span style='float:left;font-size:12pt;font-family: Geneva, Arial, Helvetica, sans-serif;text-decoration:none; '>Director of Programs</span></td><td align='right' style='font-family:king;text-decoration:underline;border-bottom:thick;font-size:10pt;'>Donna Steele<br/><span style='float:left;font-size:12pt;font-family: Geneva, Arial, Helvetica, sans-serif;text-decoration:none; '>Director, Agent # C-3110</span></td></tr></table></p";
                    $list .= "</div>";

                    $list .= "</body>";
                    $list .= "</html>";

                    break;

                case 44:
                    $list .= "<!DOCTYPE HTML SYSTEM>";
                    $list .= "<head>";
                    $list .= "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                    $list .= "<link rel='stylesheet' type='text/css' href='cert.css'>";
                    $list .= "</head>";
                    $list .= "<body>";
                    $list .= "<div class='cert'>";
                    $list .= "<br><br><br><br><p><span style='align:center;font-weight:bold;font-size:35pt;'>$title</span><br>";
                    $list .= "<span style='align:center;font-weight:bold;font-size:15pt;'>Presents this Phlebotomy certification the $day of $month $year To:</span>";
                    $list .= "<br><br><span style='align:center;font-size:20pt;'>$firstname $lastname</span><br>";
                    $list .= "<br><span style='width:675px;align:center;padding-left:35px;padding-right:35px;font-size:15pt;'>
                For the successful completion of hands-on workshop & exam.
                Medical2 Certification Agency's workshop covered topics: basic anatomy of the arm, 
                needle/syringe, lancet, winged infusion (butterfly), routine venipuncture, evacuated tube
                system, order of the draw, safety and corrective techniques</span>";
                    $list .= "<br><p style='align:center;text-decoration:underline;font-size:15pt;font-weight:normal;'>CERTIFICATION # $code<br>";
                    if ($renew_status == true) {
                        $list .= "EXPIRATION DATE $expiration_date</p>";
                    } // end if $renew_status == true                                 
                    // President signature
                    $list .= "<br><br><br><div align='left'><table border='0' width='675px;'><tr><td align='left' style='font-family:king;border-bottom:thick;font-size:10pt;'>&nbsp;&nbsp;&nbsp;&nbsp;Shahid Malik<br/><span style='float:left;font-size:12pt;font-family: Geneva, Arial, Helvetica, sans-serif;text-decoration:none; '>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;President</span></td></tr></table></div>";
                    $list .= "</div>";

                    $list .= "</body>";
                    $list .= "</html>";

                    break;

                case 45:
                    $list .= "<!DOCTYPE HTML SYSTEM>";
                    $list .= "<head>";
                    $list .= "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                    $list .= "<link rel='stylesheet' type='text/css' href='cert.css'>";
                    $list .= "</head>";
                    $list .= "<body>";
                    $list .= "<div class='cert'>";
                    $list .= "<br><br><br><br><p><span style='align:center;font-weight:bold;font-size:35pt;'>$title</span><br>";
                    $list .= "<span style='align:center;font-weight:bold;font-size:15pt;'>Presents this Phlebotomy with EKG certification the $day of $month $year To:</span>";
                    $list .= "<br><br><span style='align:center;font-size:20pt;'>$firstname $lastname</span><br>";
                    $list .= "<br><span style='width:675px;align:center;padding-left:35px;padding-right:35px;font-size:15pt;'>
                For the successful completion of hands-on workshop & exam.
                Medical2 Certification Agency's workshop covered topics: basic anatomy of the arm, 
                needle/syringe, lancet, winged infusion (butterfly), routine venipuncture, evacuated tube
                system, order of the draw, safety and corrective techniques.<br><br>"
                        . "The workshop also discusses procedures to perform an Electrocardiogram.</span>";
                    $list .= "<br><p style='align:center;text-decoration:underline;font-size:15pt;font-weight:normal;'>CERTIFICATION # $code<br>";
                    if ($renew_status == true) {
                        $list .= "EXPIRATION DATE $expiration_date</p>";
                    } // end if $renew_status == true                                 
                    // President signature
                    $list .= "<br><br><br><div align='left'><table border='0' width='675px;'><tr><td align='left' style='font-family:king;border-bottom:thick;font-size:10pt;'>&nbsp;&nbsp;&nbsp;&nbsp;Shahid Malik<br/><span style='float:left;font-size:12pt;font-family: Geneva, Arial, Helvetica, sans-serif;text-decoration:none; '>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;President</span></td></tr></table></div>";
                    $list .= "</div>";

                    $list .= "</body>";
                    $list .= "</html>";

                    break;

                case 47:
                    $list .= "<!DOCTYPE HTML SYSTEM>";
                    $list .= "<head>";
                    $list .= "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                    $list .= "<link rel='stylesheet' type='text/css' href='cert.css'>";
                    $list .= "</head>";
                    $list .= "<body>";
                    $list .= "<div class='cert'>";
                    $list .= "<br><br><br><br><p style='align:center;font-weight:bold;font-size:35pt;'>$title ";
                    $list .= "<br><span style='align:center;font-weight:bold;font-size:35pt;font-family: Geneva, Arial, Helvetica, sans-serif;'>$coursename<br>";
                    $list .= "<span style='align:center;font-weight:bold;font-size:15pt;'>Presents this certificate this the $day of $month $year To:</span>";
                    $list .= "<br><span style='align:center;font-weight:bold;font-size:35pt;'>$firstname $lastname</span>";
                    $list .= "<br><span style='align:center;font-weight:bold;font-size:15pt;'>For successfully meeting all requirements to hold this certification.</span>";
                    $list .= "<br><p style='align:center;text-decoration:underline;font-size:15pt;font-weight:normal;'>CERTIFICATION # $code<br>";
                    if ($renew_status == true) {
                        $list .= "EXPIRATION DATE $expiration_date</p>";
                    }
                    $list .= "<div align='left'><table border='0' width='675px;'><tr><td align='left' style='font-family:king;border-bottom:thick;font-size:10pt;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Shahid Malik<br/><span style='float:left;font-size:12pt;font-family: Geneva, Arial, Helvetica, sans-serif;text-decoration:none; '>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;President</span></td></tr></table></div>";
                    $list .= "</div>";
                    $list .= "</body>";
                    $list .= "</html>";

                    break;

                case 48:
                    $coursename = "IV Therapy Exam";
                    $list .= "<!DOCTYPE HTML SYSTEM>";
                    $list .= "<head>";
                    $list .= "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                    $list .= "<link rel='stylesheet' type='text/css' href='cert.css'>";
                    $list .= "</head>";
                    $list .= "<body>";
                    $list .= "<div class='cert'>";
                    $list .= "<p style='align:center;font-family:king;font-weight:bolder;font-size:25pt;'>$title ";
                    $list .= "<br><span style='align:center;font-weight:bold;font-size:35pt;font-family: Geneva, Arial, Helvetica, sans-serif;'>$coursename<br>";
                    $list .= "<span style='align:center;font-weight:bold;font-size:15pt;'>Presents this certificate this the $day th of $month $year To:</span>";
                    $list .= "<br><span style='align:center;font-weight:bold;font-size:35pt;'>$firstname $lastname</span>";
                    $list .= "<br><span style='align:center;font-weight:bold;font-size:15pt;'>For successfully meeting all requirements to hold this certification.</span>";
                    $list .= "<br><br><br><br><p style='align:center;text-decoration:underline;font-size:15pt;font-weight:normal;'>CERTIFICATION # $code<br>";
                    if ($renew_status == true) {
                        $list .= "EXPIRATION DATE $expiration_date</p>";
                    }
                    $list .= "<div align='left'><table border='0' width='675px;'><tr><td align='center' style='font-family:king;text-decoration:underline;border-bottom:thick;font-size:10pt;'>Shahid Malik<br/><span style='float:left;font-size:12pt;font-family: Geneva, Arial, Helvetica, sans-serif;text-decoration:none; '>President</span></td></tr></table></div>";
                    $list .= "</div>";
                    $list .= "</body>";
                    $list .= "</html>";

                    break;

                case 49:
                    $list .= "<!DOCTYPE HTML SYSTEM>";
                    $list .= "<head>";
                    $list .= "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                    $list .= "<link rel='stylesheet' type='text/css' href='cert.css'>";
                    $list .= "</head>";
                    $list .= "<body>";
                    $list .= "<div class='cert'>";
                    $list .= "<br><br><br><br><br><p><span style='align:center;font-weight:bold;font-size:35pt;'>$title</span><br>";
                    $list .= "<span style='align:center;font-weight:bold;font-size:25pt;'>Obstetrics Technician Certificate</span>";
                    $list .= "<br><br><span style='align:center;font-weight:bold;font-size:15pt;'>Presented this the $day of $month $year To:</span>";
                    $list .= "<br><br><span style='align:center;font-weight:bold;font-size:20pt;'>$firstname $lastname</span><br>";
                    $list .= "<br><span style='align:center;font-weight:bold;font-size:15pt;'>Obstetric Surgical Technician</span>";
                    $list .= "<br><p style='align:center;text-decoration:underline;font-size:15pt;font-weight:normal;'>CERTIFICATION # $code<br>";
                    if ($renew_status == true) {
                        $list .= "EXPIRATION DATE $expiration_date</p>";
                    } // end if $renew_status == true                                 
                    // President signature
                    $list .= "<br><br><br><br><div align='left'><table border='0' width='675px;'><tr><td align='center' style='font-family:king;text-decoration:underline;border-bottom:thick;font-size:10pt;'>Shahid Malik<br/><span style='float:left;font-size:12pt;font-family: Geneva, Arial, Helvetica, sans-serif;text-decoration:none; '>President</span></td></tr></table></div>";
                    $list .= "</div>";

                    $list .= "</body>";
                    $list .= "</html>";

                    break;


                case 51:
                    $coursename = "Phlebotomy Technician Exam";
                    $list .= "<!DOCTYPE HTML SYSTEM>";
                    $list .= "<head>";
                    $list .= "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                    $list .= "<link rel='stylesheet' type='text/css' href='cert.css'>";
                    $list .= "</head>";
                    $list .= "<body>";
                    $list .= "<div class='cert'>";
                    $list .= "<p style='align:center;font-family:king;font-weight:bolder;font-size:25pt;'>$title ";
                    $list .= "<br><span style='align:center;font-weight:bold;font-size:35pt;font-family: Geneva, Arial, Helvetica, sans-serif;'>$coursename<br>";
                    $list .= "<span style='align:center;font-weight:bold;font-size:15pt;'>Presents this certificate this the $day th of $month $year To:</span>";
                    $list .= "<br><span style='align:center;font-weight:bold;font-size:35pt;'>$firstname $lastname</span>";
                    $list .= "<br><span style='align:center;font-weight:bold;font-size:15pt;'>For successfully completing the Phlebotomy Technician Certification Exam.</span>";
                    $list .= "<br><br><br><br><p style='align:center;text-decoration:underline;font-size:15pt;font-weight:normal;'>CERTIFICATION # $code<br>";
                    if ($renew_status == true) {
                        $list .= "EXPIRATION DATE $expiration_date</p>";
                    }
                    $list .= "<div align='left'><table border='0' width='675px;'><tr><td align='center' style='font-family:king;text-decoration:underline;border-bottom:thick;font-size:10pt;'>Shahid Malik<br/><span style='float:left;font-size:12pt;font-family: Geneva, Arial, Helvetica, sans-serif;text-decoration:none; '>President</span></td></tr></table></div>";
                    $list .= "</div>";
                    $list .= "</body>";
                    $list .= "</html>";

                    break;

                case 55:

                    $list .= "<!DOCTYPE HTML SYSTEM>";
                    $list .= "<head>";
                    $list .= "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                    $list .= "</head>";
                    $list .= "<body>";
                    $list .= "<div>";
                    $list .= "<p style='text-align:center'><span style='text-align:center'><img src='/assets/logo/5.png' width='55%' height='15%'></span><br>";
                    $list .= "<span style='align:center;font-weight:bold;font-size:8pt;'>Licensed by the Mississippi Commission on Proprietary School and College Registration, License No. C675</span>";
                    $list .= "<span style='align:center;font-size:16pt;'><br><br>Presents in recognition on this the $day of $month, $year</span><br>";
                    $list .= "<br><span style='align:center;font-size:16pt;'>this <span style='align:center;font-weight:bold;font-size:30pt;'>Diploma</span> to</span><br>";
                    $list .= "<span style='align:center;font-size:35pt;'>$firstname $lastname</span>";
                    $list .= "<span style='align:center;font-size:16pt;'><br>for successfully completion of the requirements in the program of</span><br>";
                    $list .= "<span style='align:center;font-size:40pt;font-weight:bold;'>Medical Assistant</span><br>";
                    $list .= "<span style='align:center;text-decoration:underline;font-size:15pt;font-weight:normal;'><br>DIPLOMA # $code</span><br>";
                    if ($renew_status == true) {
                        $list .= "EXPIRATION DATE $expiration_date</p>";
                    } // end if $renew_status == true
                    // President signature
                    $list .= "<br><br><p align='center'><table border='0' width='675px;'><tr><td align='right' style='font-family:king;text-decoration:underline;border-bottom:thick;font-size:10pt;'> Mrs. Donna Steele<br/><span style='float:left;font-size:12pt;font-family: Geneva, Arial, Helvetica, sans-serif;text-decoration:none; '>Director</span></td><td align='right' style='font-family:king;text-decoration:underline;border-bottom:thick;font-size:10pt;'>P. Nicole Morrison<br/><span style='float:left;font-size:12pt;font-family: Geneva, Arial, Helvetica, sans-serif;text-decoration:none; '>Program Instructional Director</span></td></tr></table></p";
                    $list .= "</div>";

                    $list .= "</body>";
                    $list .= "</html>";

                    break;

                case 56:

                    $list .= "<!DOCTYPE HTML SYSTEM>";
                    $list .= "<head>";
                    $list .= "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                    $list .= "</head>";
                    $list .= "<body>";
                    $list .= "<div>";
                    $list .= "<p style='text-align:center'><span style='text-align:center'><img src='/assets/logo/5.png' width='55%' height='15%'></span><br>";
                    $list .= "<span style='align:center;font-weight:bold;font-size:8pt;'>Licensed by the Mississippi Commission on Proprietary School and College Registration, License No. C675</span>";
                    $list .= "<span style='align:center;font-size:16pt;'><br><br>Presents in recognition on this the $day of $month, $year</span><br>";
                    $list .= "<br><span style='align:center;font-size:16pt;'>this <span style='align:center;font-weight:bold;font-size:30pt;'>Certificate</span> to</span><br>";
                    $list .= "<span style='align:center;font-size:35pt;'>$firstname $lastname</span>";
                    $list .= "<span style='align:center;font-size:16pt;'><br>for successfully completion of the requirements in the program of</span><br>";
                    $list .= "<span style='align:center;font-size:40pt;font-weight:bold;'>Medical Administrative Assistant</span><br>";
                    $list .= "<span style='align:center;text-decoration:underline;font-size:15pt;font-weight:normal;'><br>CERTIFICATE # $code</span><br>";
                    if ($renew_status == true) {
                        $list .= "EXPIRATION DATE $expiration_date</p>";
                    } // end if $renew_status == true
                    // President signature
                    $list .= "<br><br><p align='center'><table border='0' width='675px;'><tr><td align='right' style='font-family:king;text-decoration:underline;border-bottom:thick;font-size:10pt;'> Mrs. Donna Steele<br/><span style='float:left;font-size:12pt;font-family: Geneva, Arial, Helvetica, sans-serif;text-decoration:none; '>Director</span></td><td align='right' style='font-family:king;text-decoration:underline;border-bottom:thick;font-size:10pt;'>P. Nicole Morrison<br/><span style='float:left;font-size:12pt;font-family: Geneva, Arial, Helvetica, sans-serif;text-decoration:none; '>Program Instructional Director</span></td></tr></table></p";
                    $list .= "</div>";

                    $list .= "</body>";
                    $list .= "</html>";

                    break;

                case 57:
                    $list .= "<!DOCTYPE HTML SYSTEM>";
                    $list .= "<head>";
                    $list .= "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                    $list .= "<link rel='stylesheet' type='text/css' href='cert.css'>";
                    $list .= "</head>";
                    $list .= "<body>";
                    $list .= "<div class='cert'>";
                    $list .= "<br><br><br><br><p style='align:center;font-weight:bold;font-size:35pt;'>$title ";
                    $list .= "<br><span style='align:center;font-weight:bold;font-size:25pt;font-family: Geneva, Arial, Helvetica, sans-serif;'>Phlebotomy Technician Certificate<br>";
                    $list .= "<span style='align:center;font-weight:bold;font-size:15pt;'>Presented this the $day of $month $year To:</span>";
                    $list .= "<br><span style='align:center;font-weight:bold;font-size:35pt;'>$firstname $lastname</span>";
                    $list .= "<br><span style='align:center;font-weight:bold;font-size:15pt;'>For successfully completing the Phlebotomy Technician Certification Exam. </span>";
                    $list .= "<br><p style='align:center;text-decoration:underline;font-size:15pt;font-weight:normal;'>CERTIFICATION # $code<br>";
                    if ($renew_status == true) {
                        $list .= "EXPIRATION DATE $expiration_date</p>";
                    }
                    $list .= "<br><br><div align='left'><table border='0' width='675px;'><tr><td align='center' style='font-family:king;text-decoration:underline;border-bottom:thick;font-size:10pt;'>Shahid Malik<br/><span style='float:left;font-size:12pt;font-family: Geneva, Arial, Helvetica, sans-serif;text-decoration:none; '>President</span></td></tr></table></div>";
                    $list .= "</div>";
                    $list .= "</body>";
                    $list .= "</html>";

                    break;

                default:
                    $list .= "<!DOCTYPE HTML SYSTEM>";
                    $list .= "<head>";
                    $list .= "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                    $list .= "<link rel='stylesheet' type='text/css' href='cert.css'>";
                    $list .= "</head>";
                    $list .= "<body>";
                    $list .= "<div class='cert'>";
                    $list .= "<p style='align:center;font-weight:bolder;font-size:25pt;'>$title ";
                    $list .= "<br><span style='align:center;font-weight:bold;font-size:35pt;font-family: Geneva, Arial, Helvetica, sans-serif;'>$coursename<br>";
                    $list .= "<span style='align:center;font-weight:bold;font-size:15pt;'>Presents this certificate this the $day th of $month $year To:</span>";
                    $list .= "<br><span style='align:center;font-weight:bold;font-size:35pt;'>$firstname $lastname</span>";
                    $list .= "<br><span style='align:center;font-weight:bold;font-size:15pt;'>For successfully meeting all requirements to hold this certification.</span>";
                    $list .= "<br><br><br><br><p style='align:center;text-decoration:underline;font-size:15pt;font-weight:normal;'>CERTIFICATION # $code<br>";
                    if ($renew_status == true) {
                        $list .= "EXPIRATION DATE $expiration_date</p>";
                    }
                    $list .= "<div align='left'><table border='0' width='675px;'><tr><td align='center' style='font-family:king;text-decoration:underline;border-bottom:thick;font-size:10pt;'>Shahid Malik<br/><span style='float:left;font-size:12pt;font-family: Geneva, Arial, Helvetica, sans-serif;text-decoration:none; '>President</td><td align='left' style='font-family:king;text-decoration:underline;border-bottom:thick;font-size:10pt;margin-left:40px;'>Donna Steele<br/><span style='float:right;font-size:12pt;font-family: Geneva, Arial, Helvetica, sans-serif;text-decoration:none;'>Director</span></td></tr></table></div>";
                    $list .= "</div>";
                    $list .= "</body>";
                    $list .= "</html>";

                    break;
            } // end switch

            $pdf = new mPDF('utf-8', 'A4-L');
            $stylesheet = file_get_contents($this->cert_path . '/cert.css');
            $pdf->WriteHTML($stylesheet, 1);
            $pdf->WriteHTML($list, 2);
            $dir_path = $this->cert_path . "/$userid/$courseid";
            if (!is_dir($dir_path)) {
                if (!mkdir($dir_path)) {
                    die('Could not write to disk');
                } // end if !mkdir($dir_path)
            } // end if !is_dir($dir_path)
            $path = $dir_path . "/certificate.pdf";
            $pdf->Output($path, 'F');
            $this->update_certificate_data($courseid, $userid, $expiration_date_sec);
        } // end if

        return $list;
    }

    function renew_certificate($courseid, $userid, $expire_year)
    {
        $this->make_certificate_copy($courseid, $userid);
        $template = $this->get_certificate_template($courseid, $userid, $expire_year);
        $m = new Mailer();
        $m->send_updated_certificate($courseid, $userid);
        return $template;
    }

    function make_certificate_copy($courseid, $userid)
    {
        $src = $_SERVER['DOCUMENT_ROOT'] . "/lms/custom/certificates/$userid/$courseid/certificate.pdf";
        $dir = $_SERVER['DOCUMENT_ROOT'] . "/lms/custom/certificates/$userid/$courseid/copy";
        if (!is_dir($dir)) {
            //echo "Directory $dir is not exists ... <br>";
            $dir_status = mkdir($dir, 0777, true);
            if ($dir_status) {
                //echo "Directory $dir was successfully created ... <br>";
                $dest = $dir . '/certificate.pdf';
                if (!copy($src, $dest)) {
                    //echo "File $src was not successfully copied ...<br>";
                } // end if
                else {
                    //echo "File $src was successfully copied ...<br>";
                } // end else
            } // end if $dir_status
            else {
                //echo "Directory $dir was not created ... <br>";
            } // end else
        } // end if !is_dir($dir)
    }

    function get_certificate_src_file($courseid, $userid)
    {
        $file = $_SERVER['DOCUMENT_ROOT'] . "/lms/custom/certificates/$userid/$courseid/certificate.pdf";
        $src = (is_file($file) === TRUE) ? $file : FALSE;
        return $src;
    }

    function migrate_certificates()
    {
        $i = 0;
        $query = "select * from mdl_certificates";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $courseid = $row['courseid'];
            $userid = $row['userid'];
            $src = $this->get_certificate_src_file($courseid, $userid);
            if ($src !== false) {
                echo "Certificate file exists: $src ...<br>";
                $this->make_certificate_copy($courseid, $userid);
                echo "<br>------------------------------------------------<br>";
                $i++;
            } // end if $src!==false
            else {
                echo "Certificate file is not exists ....<br>";
                echo "<br>------------------------------------------------<br>";
            } // end else
        } // end while
        echo "Total certificates found: " . $i . "<br>";
    }

    // ************** Renew certification page *************************

    function get_renew_certification_page()
    {
        $list = "";
        $query = "select id, content from mdl_renew_certificate_page where id=1";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $content = $row['content'];
        }
        $list = $list . "<table class='table table-hover' border='0'>";
        $list = $list . "<tr>";
        $oFCKeditor = new FCKeditor('editor');
        $oFCKeditor->BasePath = $this->editor_path;
        $oFCKeditor->Value = $content;
        $editor = $oFCKeditor->Create(false);
        $list = $list . "</td >&nbsp;&nbsp;$editor</td>";
        $list = $list . "</tr>";
        $list = $list . "<tr>";
        $list = $list . "<td align='left' style='padding-left:0px'><button type='button' id='save_renew_cert_page' class='btn btn-primary' style='spacing-left:0px;'>Save</button></td>";
        $list = $list . "</tr>";
        $list = $list . "</table>";
        return $list;
    }

    function save_page_changes($data)
    {
        $clean_data = addslashes($data);
        $query = "update mdl_renew_certificate_page "
            . "set content='$clean_data' where id=1";
        $this->db->query($query);
        $list = "<p align='center'>Data successfully saved. </p>";
        return $list;
    }

}
