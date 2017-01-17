<?php

/**
 * Description of Certificates2
 *
 * @author moyo
 */
require_once ('/home/cnausa/public_html/lms/class.pdo.database.php');
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/pdf/mpdf/mpdf.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/PDF_Label.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/Mailer.php';

class Certificates2 {

    public $db;
    public $cert_path;

    function __construct() {
        $db = new pdo_db();
        $this->db = $db;
        $this->cert_path = $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/certificates';
    }

    function get_course_name($id) {
        $query = "select fullname from mdl_course where id=$id";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $name = $row['fullname'];
        }
        return $name;
    }

    function get_user_details($id) {
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

    function get_certificate_detailes($courseid, $userid) {
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

    function update_certificate_data($courseid, $userid, $expiration) {
        $query = "update mdl_certificates "
                . "set expiration_date='$expiration' "
                . "where courseid=$courseid and userid=$userid";
        $this->db->query($query);
    }

    function get_certificate_title($courseid) {
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

    function get_course_renew_status($courseid) {
        $query = "select expired from mdl_course where id=$courseid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $expire = $row['expired'];
        }
        return $expire;
    }

    function get_certificate_template($courseid, $userid, $expire_year) {
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
                    $list.="<!DOCTYPE HTML SYSTEM>";
                    $list.="<head>";
                    $list.="<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                    $list.="</head>";
                    $list.="<body>";
                    $list.="<div>";
                    $list.="<p style='text-align:center'><span style='text-align:center'><img src='/assets/logo/5.png' width='55%' height='15%'></span><br>";
                    $list.="<span style='align:center;font-weight:bold;font-size:8pt;'>Licensed by the Mississippi Commission on Proprietary School and College Registration, License No. C675</span>";
                    $list.="<br><br><span style='align:center;font-weight:bold;font-size:35pt;'>Certification of Completion</span><br>";
                    $list.="<span style='align:center;font-size:16pt;'>Presents and declares on this the $day of $month, $year</span><br>";
                    $list.="<span style='align:center;font-size:40pt;'>$firstname $lastname</span><br>";
                    $list.="<span style='align:center;font-size:16pt;'>has successfully completed the </span><br>";
                    $list.="<span style='align:center;font-size:26pt;'>Certified Nurse Assistant Program</span><br>";
                    $list.="<br><br><span style='align:center;text-decoration:underline;font-size:15pt;font-weight:normal;'>CERTIFICATION # $code</span><br>";
                    if ($renew_status == true) {
                        $list.="EXPIRATION DATE $expiration_date</p>";
                    } // end if $renew_status == true                                 
                    // President signature
                    $list.="<br><br><p align='center'><table border='0' width='675px;'><tr><td align='right' style='font-family:king;text-decoration:underline;border-bottom:thick;font-size:10pt;'> Terri McCord, RN<br/><span style='float:left;font-size:12pt;font-family: Geneva, Arial, Helvetica, sans-serif;text-decoration:none; '>Program Coodinator</span></td><td align='right' style='font-family:king;text-decoration:underline;border-bottom:thick;font-size:10pt;'>Donna Steele<br/><span style='float:left;font-size:12pt;font-family: Geneva, Arial, Helvetica, sans-serif;text-decoration:none; '>Director, Agent # C-3110</span></td></tr></table></p";
                    $list.="</div>";

                    $list.="</body>";
                    $list.="</html>";

                    break;

                case 44:
                    $list.="<!DOCTYPE HTML SYSTEM>";
                    $list.="<head>";
                    $list.="<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                    $list.="<link rel='stylesheet' type='text/css' href='cert.css'>";
                    $list.="</head>";
                    $list.="<body>";
                    $list.="<div class='cert'>";
                    $list.="<p><span style='align:center;font-family:king;font-weight:bolder;font-size:25pt;'>$title</span><br>";
                    $list.="<span style='align:center;font-weight:bold;font-size:15pt;'>Presents this Phlebotomy Workshop certification the $day th of $month $year To:</span>";
                    $list.="<br><br><span style='align:center;font-weight:bold;font-size:20pt;'>$firstname $lastname</span><br>";
                    $list.="<br><span style='align:center;font-weight:bold;font-size:15pt;'>For the successful completion of hands-on clinical exam and written certification exam.
                        Medical2 Career College hands-on workshop covered topics; basic anatomy of arm, safety techniques with routine venipuncture, 
                        patient bill of rights, order of draws, needle/syringe, winged infusion (butterfly), 
                        lancet, evacuated tube system, and corrective techniques.
                        </span>";
                    $list.="<br><br><br><br><p style='align:center;text-decoration:underline;font-size:15pt;font-weight:normal;'>CERTIFICATION # $code<br>";
                    if ($renew_status == true) {
                        $list.="EXPIRATION DATE $expiration_date</p>";
                    } // end if $renew_status == true                                 
                    // President signature
                    $list.="<br><br><div align='left'><table border='0' width='675px;'><tr><td align='center' style='font-family:king;text-decoration:underline;border-bottom:thick;font-size:10pt;'>Shahid Malik<br/><span style='float:left;font-size:12pt;font-family: Geneva, Arial, Helvetica, sans-serif;text-decoration:none; '>President</td></tr></table></div>";
                    $list.="</div>";

                    $list.="</body>";
                    $list.="</html>";

                    break;

                case 45:
                    $list.="<!DOCTYPE HTML SYSTEM>";
                    $list.="<head>";
                    $list.="<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                    $list.="<link rel='stylesheet' type='text/css' href='cert.css'>";
                    $list.="</head>";
                    $list.="<body>";
                    $list.="<div class='cert'>";
                    $list.="<p><span style='align:center;font-family:king;font-weight:bolder;font-size:25pt;'>$title</span><br>";
                    $list.="<span style='align:center;font-weight:bold;font-size:15pt;'>Presents this Phlebotomy with EKG certification the $day th of $month $year To:</span>";
                    $list.="<br><br><span style='align:center;font-weight:bold;font-size:20pt;'>$firstname $lastname</span><br>";
                    $list.="<br><span style='align:center;font-weight:bold;font-size:15pt;'>For the successful completion of hands-on clinical exam and written certification exam.<br>"
                            . "Medical2 Career College hands-on workshop covered topics; basic anatomy of arm, safety techniques with routine venipuncture,<br> "
                            . "Patient bill of rights, order of draws, needle/syringe, winged infusion (butterfly), lancet, evacuated tube system, and corrective techniques.<br><br>"
                            . "The workshop also discusses procedures to preform an Electrocardiogram.</span>";
                    $list.="<br><br><br><br><p style='align:center;text-decoration:underline;font-size:15pt;font-weight:normal;'>CERTIFICATION # $code<br>";
                    if ($renew_status == true) {
                        $list.="EXPIRATION DATE $expiration_date</p>";
                    } // end if $renew_status == true                                 
                    // President signature
                    $list.="<br><br><div align='left'><table border='0' width='675px;'><tr><td align='center' style='font-family:king;text-decoration:underline;border-bottom:thick;font-size:10pt;'>Shahid Malik<br/><span style='float:left;font-size:12pt;font-family: Geneva, Arial, Helvetica, sans-serif;text-decoration:none; '>President</td></tr></table></div>";
                    $list.="</div>";

                    $list.="</body>";
                    $list.="</html>";

                    break;

                case 47:
                    $list.="<!DOCTYPE HTML SYSTEM>";
                    $list.="<head>";
                    $list.="<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                    $list.="<link rel='stylesheet' type='text/css' href='cert.css'>";
                    $list.="</head>";
                    $list.="<body>";
                    $list.="<div class='cert'>";
                    $list.="<p style='align:center;font-family:king;font-weight:bolder;font-size:25pt;'>$title ";
                    $list.="<br><span style='align:center;font-weight:bold;font-size:35pt;font-family: Geneva, Arial, Helvetica, sans-serif;'>$coursename<br>";
                    $list.="<span style='align:center;font-weight:bold;font-size:15pt;'>Presents this certificate this the $day th of $month $year To:</span>";
                    $list.="<br><span style='align:center;font-weight:bold;font-size:35pt;'>$firstname $lastname</span>";
                    $list.="<br><span style='align:center;font-weight:bold;font-size:15pt;'>For successfully meeting all requirements to hold this certification.</span>";
                    $list.="<br><br><br><br><p style='align:center;text-decoration:underline;font-size:15pt;font-weight:normal;'>CERTIFICATION # $code<br>";
                    if ($renew_status == true) {
                        $list.="EXPIRATION DATE $expiration_date</p>";
                    }
                    $list.="<div align='left'><table border='0' width='675px;'><tr><td align='center' style='font-family:king;text-decoration:underline;border-bottom:thick;font-size:10pt;'>Shahid Malik<br/><span style='float:left;font-size:12pt;font-family: Geneva, Arial, Helvetica, sans-serif;text-decoration:none; '>President</span></td></tr></table></div>";
                    $list.="</div>";
                    $list.="</body>";
                    $list.="</html>";

                    break;

                case 48:
                    $coursename = "IV Therapy Exam";
                    $list.="<!DOCTYPE HTML SYSTEM>";
                    $list.="<head>";
                    $list.="<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                    $list.="<link rel='stylesheet' type='text/css' href='cert.css'>";
                    $list.="</head>";
                    $list.="<body>";
                    $list.="<div class='cert'>";
                    $list.="<p style='align:center;font-family:king;font-weight:bolder;font-size:25pt;'>$title ";
                    $list.="<br><span style='align:center;font-weight:bold;font-size:35pt;font-family: Geneva, Arial, Helvetica, sans-serif;'>$coursename<br>";
                    $list.="<span style='align:center;font-weight:bold;font-size:15pt;'>Presents this certificate this the $day th of $month $year To:</span>";
                    $list.="<br><span style='align:center;font-weight:bold;font-size:35pt;'>$firstname $lastname</span>";
                    $list.="<br><span style='align:center;font-weight:bold;font-size:15pt;'>For successfully meeting all requirements to hold this certification.</span>";
                    $list.="<br><br><br><br><p style='align:center;text-decoration:underline;font-size:15pt;font-weight:normal;'>CERTIFICATION # $code<br>";
                    if ($renew_status == true) {
                        $list.="EXPIRATION DATE $expiration_date</p>";
                    }
                    $list.="<div align='left'><table border='0' width='675px;'><tr><td align='center' style='font-family:king;text-decoration:underline;border-bottom:thick;font-size:10pt;'>Shahid Malik<br/><span style='float:left;font-size:12pt;font-family: Geneva, Arial, Helvetica, sans-serif;text-decoration:none; '>President</span></td></tr></table></div>";
                    $list.="</div>";
                    $list.="</body>";
                    $list.="</html>";

                    break;

                case 49:
                    $list.="<!DOCTYPE HTML SYSTEM>";
                    $list.="<head>";
                    $list.="<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                    $list.="<link rel='stylesheet' type='text/css' href='cert.css'>";
                    $list.="</head>";
                    $list.="<body>";
                    $list.="<div class='cert'>";
                    $list.="<p><span style='align:center;font-family:king;font-weight:bolder;font-size:25pt;'>$title</span><br>";
                    $list.="<span style='align:center;font-weight:bold;font-size:25pt;'>Obstetrics Technician Certificate</span>";
                    $list.="<br><br><span style='align:center;font-weight:bold;font-size:15pt;'>Presented this the $day th of $month $year To:</span>";
                    $list.="<br><br><span style='align:center;font-weight:bold;font-size:20pt;'>$firstname $lastname</span><br>";
                    $list.="<br><span style='align:center;font-weight:bold;font-size:15pt;'>for the successful completion of the Obstetrics Technician Written Exam. </span>";
                    $list.="<br><br><br><br><p style='align:center;text-decoration:underline;font-size:15pt;font-weight:normal;'>CERTIFICATION # $code<br>";
                    if ($renew_status == true) {
                        $list.="EXPIRATION DATE $expiration_date</p>";
                    } // end if $renew_status == true                                 
                    // President signature
                    $list.="<br><br><br><br><br><div align='left'><table border='0' width='675px;'><tr><td align='center' style='font-family:king;text-decoration:underline;border-bottom:thick;font-size:10pt;'>Shahid Malik<br/><span style='float:left;font-size:12pt;font-family: Geneva, Arial, Helvetica, sans-serif;text-decoration:none; '>President</td></tr></table></div>";
                    $list.="</div>";

                    $list.="</body>";
                    $list.="</html>";

                    break;


                case 51:
                    $coursename = "Phlebotomy Technician Exam";
                    $list.="<!DOCTYPE HTML SYSTEM>";
                    $list.="<head>";
                    $list.="<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                    $list.="<link rel='stylesheet' type='text/css' href='cert.css'>";
                    $list.="</head>";
                    $list.="<body>";
                    $list.="<div class='cert'>";
                    $list.="<p style='align:center;font-family:king;font-weight:bolder;font-size:25pt;'>$title ";
                    $list.="<br><span style='align:center;font-weight:bold;font-size:35pt;font-family: Geneva, Arial, Helvetica, sans-serif;'>$coursename<br>";
                    $list.="<span style='align:center;font-weight:bold;font-size:15pt;'>Presents this certificate this the $day th of $month $year To:</span>";
                    $list.="<br><span style='align:center;font-weight:bold;font-size:35pt;'>$firstname $lastname</span>";
                    $list.="<br><span style='align:center;font-weight:bold;font-size:15pt;'>For successfully completing the Phlebotomy Technician Certification Exam.</span>";
                    $list.="<br><br><br><br><p style='align:center;text-decoration:underline;font-size:15pt;font-weight:normal;'>CERTIFICATION # $code<br>";
                    if ($renew_status == true) {
                        $list.="EXPIRATION DATE $expiration_date</p>";
                    }
                    $list.="<div align='left'><table border='0' width='675px;'><tr><td align='center' style='font-family:king;text-decoration:underline;border-bottom:thick;font-size:10pt;'>Shahid Malik<br/><span style='float:left;font-size:12pt;font-family: Geneva, Arial, Helvetica, sans-serif;text-decoration:none; '>President</span></td></tr></table></div>";
                    $list.="</div>";
                    $list.="</body>";
                    $list.="</html>";

                    break;

                case 57:
                    $list.="<!DOCTYPE HTML SYSTEM>";
                    $list.="<head>";
                    $list.="<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                    $list.="<link rel='stylesheet' type='text/css' href='cert.css'>";
                    $list.="</head>";
                    $list.="<body>";
                    $list.="<div class='cert'>";
                    $list.="<p style='align:center;font-family:king;font-weight:bolder;font-size:25pt;'>$title ";
                    $list.="<br><span style='align:center;font-weight:bold;font-size:25pt;font-family: Geneva, Arial, Helvetica, sans-serif;'>Phlebotomy Technician Certificate<br><br><br>";
                    $list.="<span style='align:center;font-weight:bold;font-size:15pt;'>Presented this the $day th of $month $year To:</span>";
                    $list.="<br><br><br><span style='align:center;font-weight:bold;font-size:35pt;'>$firstname $lastname</span>";
                    $list.="<br><span style='align:center;font-weight:bold;font-size:15pt;'>For successfully completing the Phlebotomy Technician Certification Exam. </span>";
                    $list.="<br><br><br><p style='align:center;text-decoration:underline;font-size:15pt;font-weight:normal;'>CERTIFICATION # $code<br>";
                    if ($renew_status == true) {
                        $list.="EXPIRATION DATE $expiration_date</p>";
                    }
                    $list.="<br><br><div align='left'><table border='0' width='675px;'><tr><td align='center' style='font-family:king;text-decoration:underline;border-bottom:thick;font-size:10pt;'>Shahid Malik<br/><span style='float:left;font-size:12pt;font-family: Geneva, Arial, Helvetica, sans-serif;text-decoration:none; '>President</td></tr></table></div>";
                    $list.="</div>";
                    $list.="</body>";
                    $list.="</html>";

                    break;

                default:
                    $list.="<!DOCTYPE HTML SYSTEM>";
                    $list.="<head>";
                    $list.="<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                    $list.="<link rel='stylesheet' type='text/css' href='cert.css'>";
                    $list.="</head>";
                    $list.="<body>";
                    $list.="<div class='cert'>";
                    $list.="<p style='align:center;font-family:king;font-weight:bolder;font-size:25pt;'>$title ";
                    $list.="<br><span style='align:center;font-weight:bold;font-size:35pt;font-family: Geneva, Arial, Helvetica, sans-serif;'>$coursename<br>";
                    $list.="<span style='align:center;font-weight:bold;font-size:15pt;'>Presents this certificate this the $day th of $month $year To:</span>";
                    $list.="<br><span style='align:center;font-weight:bold;font-size:35pt;'>$firstname $lastname</span>";
                    $list.="<br><span style='align:center;font-weight:bold;font-size:15pt;'>For successfully meeting all requirements to hold this certification.</span>";
                    $list.="<br><br><br><br><p style='align:center;text-decoration:underline;font-size:15pt;font-weight:normal;'>CERTIFICATION # $code<br>";
                    if ($renew_status == true) {
                        $list.="EXPIRATION DATE $expiration_date</p>";
                    }
                    $list.="<div align='left'><table border='0' width='675px;'><tr><td align='center' style='font-family:king;text-decoration:underline;border-bottom:thick;font-size:10pt;'>Shahid Malik<br/><span style='float:left;font-size:12pt;font-family: Geneva, Arial, Helvetica, sans-serif;text-decoration:none; '>President</td><td align='left' style='font-family:king;text-decoration:underline;border-bottom:thick;font-size:10pt;margin-left:40px;'>Donna Steele<br/><span style='float:right;font-size:12pt;font-family: Geneva, Arial, Helvetica, sans-serif;text-decoration:none;'>Director</span></td></tr></table></div>";
                    $list.="</div>";
                    $list.="</body>";
                    $list.="</html>";

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

    function renew_certificate($courseid, $userid, $expire_year) {
        $template = $this->get_certificate_template($courseid, $userid, $expire_year);
        $m = new Mailer();
        $m->send_updated_certificate($courseid, $userid);
        return $template;
    }

}
