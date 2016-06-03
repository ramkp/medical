<?php

/**
 * Description of Import
 *
 * @author sirromas
 */
set_time_limit(0);
error_reporting('all');
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/pdf/mpdf/mpdf.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/PDF_Label.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/pdf/dompdf/autoload.inc.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/pdf/dompdf/autoload.inc.php';
require_once($CFG->dirroot . '/user/editlib.php');
require_once $_SERVER['DOCUMENT_ROOT'] . '/connect.php';

use Dompdf\Dompdf;

class Import extends Util {
    /*     * ******************************************************************
     * 
     *             Code related to users import
     * 
     * ***************************************************************** */

    public $cert_path;
    public $invoice_path;
    public $manager_id = 234;

    function __construct() {
        parent::__construct();
        $this->cert_path = $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/certificates';
        $this->invoice_path = $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/invoices';
    }

    function get_password($length = 8) {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
    }

    function get_user_address_data($userid) {
        $query = "SELECT *
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

    function get_invoice_num() {
        $id = 1000;
        $query = "select id from mdl_invoice order by id desc limit 0,1";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $id = $row['id'] + 1001;
            }
        } // end if $num>0
        $invoice_num = "M2CC_$id";
        return $invoice_num;
    }

    function get_invoice_credentials() {
        $query = "select * from mdl_invoice_credentials";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $data = new stdClass();
            foreach ($row as $key => $value) {
                $data->$key = $value;
            }
        }
        return $data;
    }

    function valid_email($email) {
        return (bool) filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    function read_data_from_file($filepath) {
        $users = array();
        $handle = @fopen($filepath, "r");
        if ($handle) {
            while (($buffer = fgets($handle, 4096)) !== false) {
                $clean_buffer = str_replace(',,', ',', $buffer);
                $user = $this->check_file_data($clean_buffer);
                if ($user != null) {
                    $users[] = $user;
                } // end if $user!=null
            } // end while            
            fclose($handle);
        } // end if $handle
        else {
            die("Error: can't open file <br>");
        } // end else
        return $users;
    }

    function check_file_data($buffer) {
        global $CFG;
        $user = null;
        $line_arr = explode(",", $buffer);
        $uid = $line_arr[0];
        $firstname = $line_arr[1];
        $lastname = $line_arr[2];
        $email = trim(strtolower($line_arr[7]));
        $phone = $line_arr[6];
        $pwd = $this->get_password();
        $address = $line_arr[3];
        $city = $line_arr[4];
        $zip = $line_arr[5];
        $regdate = $line_arr[8];

        if ($firstname != '' && $lastname != '' && $email != '' && $this->valid_email($email) && $phone != '' && $address != '' && $city != '' && $zip != '') {
            if ($this->valid_email($email)) {
                $user = new stdClass();
                $user->confirmed = 1;
                $user->uid = $uid;
                $user->regdate = $regdate;
                $user->username = $email;
                $user->password = $pwd;
                $user->purepwd = $pwd;
                $user->email = $email;
                $user->email1 = $email;
                $user->email2 = $email;
                $user->phone = $phone;
                $user->firstname = $firstname;
                $user->lastname = $lastname;
                $user->address = $address;
                $user->institution = '---';
                $user->zip = $zip;
                $user->city = $city;
                $user->state = '';
                $user->country = 'US';
                $user->lang = current_language();
                $user->firstaccess = 0;
                $user->timecreated = time();
                $user->mnethostid = $CFG->mnet_localhost_id;
                $user->secret = random_string(15);
                $user->auth = $CFG->registerauth;
                return $user;
            } // end if valid_email($email)            
        } // end if $firstname != '' && $lastname != '' ...        
        else {
            return $user;
        }
    }

    function is_user_exists($username) {
        $query = "select id from mdl_user where username='$username'";
        $num = $this->db->numrows($query);
        return $num;
    }

    function signup_user($user) {
        global $CFG;
        $authplugin = get_auth_plugin($CFG->registerauth);
        $namefields = array_diff(get_all_user_name_fields(), useredit_get_required_name_fields());
        foreach ($namefields as $namefield) {
            $user->$namefield = '';
        }

        try {
            $authplugin->user_signup($user, false);
            $this->update_users_data($user);
            echo "User $user->username has been imported";
            echo "<br>-------------------------------------------------------<br>";
        } // end try        
        catch (Exception $e) {
            echo 'Error occured: ', $e->getMessage(), "\n";
        }
    }

    function update_users_data($user) {
        $query = "update mdl_user "
                . "set purepwd='$user->purepwd' , "
                . "phone1='$user->phone' , "
                . "uid=$user->uid, "
                . "timecreated='" . strtotime($user->regdate) . "', "
                . "timemodified='" . strtotime($user->regdate) . "' "
                . "where username='$user->username'";
        $this->db->query($query);
    }

    function process_user_data($filepath) {
        $users = $this->read_data_from_file($filepath);
        if (count($users) > 0) {
            $counter = 0;
            $already_imported_counter = 0;
            foreach ($users as $user) {
                if ($this->is_user_exists($user->username) == 0) {
                    /*
                     * 
                      echo "<pre>----------------------------------------------<br>";
                      print_r($user);
                      echo "<pre>----------------------------------------------<br>";
                     * 
                     */
                    $this->signup_user($user);
                    //echo "User $user->username will be imported ...<br>";
                    $counter++;
                } // end if $this->is_user_exists($user->username)==0
                else {
                    echo "User $user->username already exists <br>";
                    $already_imported_counter++;
                }
            } // end foreach
            //echo "<p align='center'>Total already imported (simulation): " . $already_imported_counter . "</p>";
            //echo "<p align='center'>Total users to be imported (simulation): " . $counter . "</p>";

            echo "<p align='center'>Total already imported (real operation): " . $already_imported_counter . "</p>";
            echo "<p align='center'>Total users to be imported (real operation): " . $counter . "</p>";
        } // end if count($users) > 0
        else {
            echo "There are no users selected from the file ... <br>";
        }
    }

    function get_user_data($username) {
        $user = null;
        $query = "select id, uid,username from mdl_user where username='$username'";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $user = new stdClass();
                $user->username = $row['username'];
                $user->uid = $row['uid'];
                $user->id = $row['id'];
            }
        } // end if $num>0        
        return $user;
    }

    function update_users_uid($filepath) {
        $handle = @fopen($filepath, "r");
        if ($handle) {
            while (($buffer = fgets($handle, 4096)) !== false) {
                $clean_buffer = str_replace(',,', ',', $buffer);
                $data = explode(",", $clean_buffer);
                $uid = $data[0];
                $username = trim(strtolower($data[7]));
                $user = $this->get_user_data($username);
                if ($user) {
                    echo "File username: " . $username . "<br>";
                    echo "Object username: " . $user->username . "<br>";
                    if ($user->username == $username) {
                        $query = "update mdl_user set uid='$uid' where username='$username'";
                        $this->db->query($query);
                        echo "User ($username) has been updated with uid $uid ...<br>";
                    } // end if $user->username=='$username' && $user->uid==''
                    else {
                        echo "User ($username) already has uid ... <br>";
                    }
                    echo "<br>--------------------------------------<br>";
                } // end if $user!=null
            } // end while            
            fclose($handle);
        } // end if $handle
        else {
            die("Error: can't open file <br>");
        } // end else
    }

    /*     * ******************************************************************
     * 
     *             Code related to users activity import
     * 
     * ***************************************************************** */

    function get_invoice_user_data($userid) {
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

    function is_course_exists($uid) {
        $query = "select * from mdl_course where uid=$uid";
        $num = $this->db->numrows($query);
        return $num;
    }

    function is_user_uid_exists($uid) {
        $query = "select * from mdl_user where uid='$uid'";
        $num = $this->db->numrows($query);
        return $num;
    }

    function is_user_already_enrolled($courseid, $userid) {
        $contextid = $this->get_course_context($courseid);
        $query = "select * from mdl_role_assignments "
                . "where contextid=$contextid and userid=$userid";
        $num = $this->db->numrows($query);
        return $num;
    }

    function is_certiticate_exists($courseid, $userid) {
        $query = "select * from mdl_certificates "
                . "where courseid=$courseid and userid=$userid";
        $num = $this->db->numrows($query);
        return $num;
    }

    function is_invoice_exists($courseid, $userid) {
        $query = "select * from mdl_invoice "
                . "where courseid=$courseid and userid=$userid";
        $num = $this->db->numrows($query);
        return $num;
    }

    function is_card_payment_exists($courseid, $userid) {
        $query = "select * from mdl_card_payments "
                . "where courseid=$courseid and userid=$userid";
        $num = $this->db->numrows($query);
        return $num;
    }

    function getEnrolId($courseid) {
        $query = "select id from mdl_enrol
                     where courseid=" . $courseid . " and enrol='manual'";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $enrolid = $row['id'];
        }
        return $enrolid;
    }

    function class_exists($uid) {
        $query = "select * from mdl_scheduler_slots where uid=$uid";
        echo "Query: " . $query . "<br>";
        $num = $this->db->numrows($query);
        return $num;
    }

    function get_users_activity_data($filepath) {
        $activities = array();
        $handle = @fopen($filepath, "r");
        $counter = 0;
        if ($handle) {

            while (($buffer = fgets($handle, 4096)) !== false) {
                $clean_buffer = str_replace(',,', ',', $buffer);
                $data = explode(",", $clean_buffer);
                $uid = trim($data[0]);
                $amount = $data[1];
                $pdate = $data[2];
                $pstatus = $data[3];
                $ptype = $data[4];
                $regdate = $data[5];
                $courseid = $data[6];
                $classid = $data[7];
                $certno = $data[8];
                $cstart = $data[9];
                $cend = $data[10];
                if ($courseid != 'Course_Def_Number') {
                    $course_exists = $this->is_course_exists($courseid);
                    $user_exists = $this->is_user_uid_exists($uid);
                    $class_exists = $this->class_exissts($classid);
                    if ($course_exists > 0 && $user_exists > 0) {
                        //echo "User with $uid exists - creating activity object <br>";
                        $user_activity = new stdClass();
                        $user_activity->uid = $uid;
                        $user_activity->amount = $amount;
                        $user_activity->pdate = $pdate;
                        $user_activity->pstatus = $pstatus;
                        $user_activity->ptype = $ptype;
                        $user_activity->regdate = $regdate;
                        $user_activity->courseid = $courseid;
                        $user_activity->classid = $classid;
                        $user_activity->certno = $certno;
                        $user_activity->cstart = $cstart;
                        $user_activity->cend = $cend;
                        $activities[] = $user_activity;
                        $counter++;
                        //if ($counter > 100) {
                        //  exit('Stopped by condition...');
                        //}
                    } // end if $course_exists>0 && $user_exists>0  
                } // end if $courseid!='Course_Def_Number'
            } // end while            
            fclose($handle);
            return $activities;
        } // end if $handle
        else {
            die("Error: can't open file <br>");
        } // end else
    }

    function get_user_id_by_uid($uid) {
        $query = "select id, uid from mdl_user where uid='$uid'";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $id = $row['id'];
        }
        return $id;
    }

    function get_course_id($uid) {
        $query = "select id, uid from mdl_course where uid=$uid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $id = $row['id'];
        }
        return $id;
    }

    function update_user_registration_date($activity) {
        $query = "update mdl_user "
                . "set timecreated='$activity->regdate', "
                . "timemodified='$activity->regdate' "
                . "where uid='$activity->uid'";
        $this->db->query($query);
    }

    function enroll_user_to_course($activity) {
        $courseid = $this->get_course_id($activity->courseid);
        $userid = $this->get_user_id_by_uid($activity->uid);
        $contextid = $this->get_course_context($courseid);
        $enrolid = $this->getEnrolId($courseid);
        $enrollment_status = $this->is_user_already_enrolled($courseid, $userid);
        //echo "Course enrollment status: " . $enrollment_status . "<br>";

        if ($enrollment_status == 0) {
            $query = "insert into mdl_user_enrolments
             (enrolid,
              userid,
              timestart,
              modifierid,
              timecreated,
              timemodified)
               values ('" . $enrolid . "',
                       '" . $userid . "',
                        '" . strtotime($activity->regdate) . "',   
                        '2',
                         '" . strtotime($activity->regdate) . "',
                         '" . strtotime($activity->regdate) . "')";
            $this->db->query($query);

            $query = "insert into mdl_role_assignments"
                    . " (roleid,"
                    . "contextid,"
                    . "userid,"
                    . "timemodified,"
                    . "modifierid) "
                    . "values('5',"
                    . "'$contextid',"
                    . "'$userid',"
                    . "'" . strtotime($activity->regdate) . "','2')";
            $this->db->query($query);
            echo "<br>User ($activity->uid) was successfully enroled into course #$activity->courseid<br>";
        } // end if $enrollment_status==0
        else {
            echo "<br>User ($activity->uid) already enrolled into course #$activity->courseid <br>";
        }
    }

    function create_user_certification_data($activity) {
        $list = "";

        echo "Certificate issue date: " . $activity->cstart . "<br>";
        echo "Certificate issue timestamp date:" . strtotime($activity->cstart) . "<br>";

        if (trim($activity->certno) != '1/1/1900' && trim($activity->cstart) != '1/1/1900') {

            $courseid = $this->get_course_id($activity->courseid);
            $userid = $this->get_user_id_by_uid($activity->uid);
            $certificate_status = $this->is_certiticate_exists($courseid, $userid);
            //echo "Certificate exists: " . $certificate_status . "<br>";

            if ($certificate_status == 0) {

                $coursename = $this->get_course_name($courseid);
                $userdetails = $this->get_user_details($userid);
                $firstname = strtoupper($userdetails->firstname);
                $lastname = strtoupper($userdetails->lastname);
                $date = strtotime($activity->cstart);
                $day = date('d', $date);
                $month = date('M', $date);
                $year = date('Y', $date);
                $expiration_date = date('m-d-Y', strtotime($activity->cend));
                $path = $this->cert_path . "/$userid/certificate.pdf";

                // Make course as completed for selected user
                $query = "insert into mdl_course_completions "
                        . "(userid, "
                        . "course,"
                        . "timeenrolled,"
                        . "timestarted,"
                        . "timecompleted) "
                        . "values($userid, "
                        . "$courseid,"
                        . "'" . strtotime($activity->regdate) . "',"
                        . "'" . $activity->cstart . "',"
                        . "'" . strtotime($activity->cend) . "')";
                $this->db->query($query);

                // Insert data into certificatopn table
                $query = "insert into mdl_certificates"
                        . " (courseid,"
                        . "userid, "
                        . "cert_no,"
                        . "path,"
                        . "issue_date,"
                        . "expiration_date) "
                        . "values($courseid,"
                        . "$userid, "
                        . "'$activity->certno',"
                        . "'$path',"
                        . "'" . strtotime($activity->cstart) . "',"
                        . "'" . strtotime($activity->cend) . "')";
                $this->db->query($query);

                // Create Certificate
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
                $list.="<br><br><br><br><p style='align:center;text-decoration:underline;font-size:15pt;font-weight:normal;'>CERTIFICATION #: $activity->certno<br>";
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

                // Create Address label
                $user_address = $this->get_user_address_data($userid);
                $pdf2 = new PDF_Label('L7163');
                $pdf2->AddPage();
                $text = sprintf("%s\n%s\n%s\n%s", "$user_address->firstname $user_address->lastname", "$user_address->address", "$user_address->state/" . $user_address->city . "", "$user_address->zip");
                $pdf2->Add_Label($text);
                $dir_path = $this->cert_path . "/$userid";
                if (!is_dir($dir_path)) {
                    if (!mkdir($dir_path)) {
                        die('Could not write to disk');
                    } // end if !mkdir($dir_path)
                } // end if !is_dir($dir_path)
                $path = $dir_path . "/label.pdf";
                $pdf2->Output($path, 'F');
                echo "<br>Certificate data for User ($activity->uid) was created<br>";
            } // end if $certificate_status==0
            else {
                echo "<br>User $activity->uid already has certificate from course #$activity->courseid<br>";
            }
        } // end if $activity->certno!='1/1/1900'
        else {
            echo "Certificate number or issue date are not correct ... <br>";
        }
    }

    function add_user_payment($activity) {
        if ($activity->pstatus == 0) {
            $courseid = $this->get_course_id($activity->courseid);
            $userid = $this->get_user_id_by_uid($activity->uid);

            if (strtolower($activity->ptype) != 'cc') {

                $invoice_status = $this->is_invoice_exists($courseid, $userid);
                //echo "Invoice exists: " . $invoice_status . "<br>";

                if ($invoice_status == 0) {
                    $invoice_credentials = $this->get_invoice_credentials();
                    $invoice_num = $this->get_invoice_num();
                    $user_data = $this->get_invoice_user_data($userid);

                    switch (strtolower($activity->ptype)) {
                        case 'cash':
                            $ptype = 1;
                            break;
                        case 'cheque':
                            $ptype = 2;
                            break;
                        case 'free':
                            $ptype = 3;
                            break;
                        default:
                            $ptype = 1;
                    }

                    if ($ptype == 3) {
                        $activity->amount = 0;
                    }

                    // ********************  Make invoice *********************
                    $list = "";
                    $list.="<html>";
                    $list.="<body>";
                    $list.= "<p></p>";
                    $list.="<br/><br/><table border='0' align='center' style='width:100%;table-layout:fixed;'>";
                    $list.="<tr>";
                    $list.="<td colspan='2' width='55%' style=''><img src='" . $_SERVER['DOCUMENT_ROOT'] . "/assets/logo/5.png' width='350' height=90></td><td  style='padding-left:10px;padding-right:10px;border-left:1px solid;' width='45%'>Phone: $invoice_credentials->phone<br/>Fax: $invoice_credentials->fax</td>";
                    $list.="</tr>";
                    $list.="<tr>";
                    $list.="<td colspan='3' style='border-bottom:1px solid;padding-top:1px;height:10px;'></td>";
                    $list.="</tr>";
                    $list.="<tr>";
                    $list.="<td style='padding-top:6px;' colspan='2'>No: $invoice_num</td><td  style='padding-left:10px;'>Date: " . $activity->regdate . "</td>";
                    $list.="</tr>";
                    $list.="<tr style=''>";
                    $list.="<td colspan='3' style='padding-top:1px;height:35px;'></td>";
                    $list.="</tr>";
                    $list.="<tr bgcolor='black'>";
                    $list.="<td style='text-align:center;color:black;' width='15' height='15'>&nbsp;</td><td style='padding-left:15px;text-align:left;' width='10%' bgcolor='white'><span style='color:#ff8000;font-weight:bolder;'>INVOICE TO </span></td><td style='text-align:left;color:black;padding-left:15px;'>&nbsp;</td>";
                    $list.="</tr>";

                    $list.="<tr>";
                    $list.="<td colspan='3'>$user_data->firstname $user_data->lastname<br/>$user_data->address<br/> $user_data->state" . "/" . $user_data->city . "<br/>$user_data->zip</td>";
                    $list.="</tr>";

                    $list.="<tr>";
                    $list.="<td colspan='3' style='border-bottom:0px solid;padding-top:1px;height:40px;'></td>";
                    $list.="</tr>";
                    $list.="<tr bgcolor='black'>";
                    $list.="<td style='color:white;text-align:center' width='10%'>No</td><td style='padding-left:15px;text-align:left;color:white' width='60%'>Description</td><td style='text-align:left;color:white;padding-left:15px;' width='5%'>Amount</td>";
                    $list.="</tr>";
                    $grand_total = $activity->amount;
                    $item_name = $this->get_course_name($courseid);
                    $item_block = "$user_data->firstname $user_data->lastname<br/> payment for $item_name";
                    $list.="<tr bgcolor='#FAF7F5'>";
                    $list.="<td style='text-align:center;color:black;' width='10%' height='55'>1</td><td style='padding-left:15px;text-align:left;color:black' width='60%'>$item_block</td><td style='text-align:left;color:black;padding-left:15px;' width='5%'>$$grand_total </td>";
                    $list.="</tr>";
                    $list.="<tr>";
                    $list.="<td></td><td style='padding:10px;' align='right'>Tax</td><td bgcolor='black' style='padding-left:15px;color:white;'>$0</td>";
                    $list.="</tr>";
                    $list.="<tr>";
                    $list.="<td></td><td style='padding:10px;' align='right'>Total</td><td bgcolor='black' style='padding-left:15px;color:white;'>$" . $grand_total . "</td>";
                    $list.="</tr>";

                    $list.="<tr bgcolor='#ff8000'>";
                    $list.="<td colspan='3' height='35px' style='color:white;fonr-weight:bold;' align='center'>email: " . $invoice_credentials->email . "&nbsp;&nbsp;&nbsp; " . $invoice_credentials->site . "<br>Mailing Address: Medical2 1830A North Gloster St,  Tupelo, MS 38804 </td>";
                    $list.="</tr>";
                    $list.="</table>";
                    $list.="</body>";
                    $list.="</html>";

                    $dompdf = new Dompdf();
                    $dompdf->loadHtml($list);

                    // (Optional) Setup the paper size and orientation
                    $dompdf->setPaper('A4', 'portrait');

                    // Render the HTML as PDF
                    $dompdf->render();
                    $output = $dompdf->output();

                    $file_path = $this->invoice_path . "/$invoice_num.pdf";
                    file_put_contents($file_path, $output);

                    $query = "insert into mdl_invoice "
                            . "(i_num,"
                            . "userid,"
                            . "courseid,"
                            . "renew,"
                            . "i_sum,"
                            . "i_status,"
                            . "i_file,"
                            . "i_ptype,"
                            . "i_date,"
                            . "i_pdate) "
                            . "values('$invoice_num',"
                            . "'$userid',"
                            . "'$courseid',"
                            . "'0',"
                            . "'$activity->amount',"
                            . "'1',"
                            . "'$file_path',"
                            . "'$ptype',"
                            . "'" . strtotime($activity->regdate) . "',"
                            . "'" . strtotime($activity->pdate) . "')";
                    $this->db->query($query);

                    echo "<br>Payment data for user ($activity->uid) was created<br>";
                }  // end if $invoice_status==0
                else {
                    echo "<br>Invoice data for for user ($activity->uid) already exists  <br>";
                }
            } // end if strtolower($activity->ptype)!='cc'
            else {
                $card_payment_exists = $this->is_card_payment_exists($courseid, $userid);
                //echo "Card Payment status: " . $card_payment_exists . "<br>";

                if ($card_payment_exists == 0) {
                    $query = "insert into mdl_card_payments"
                            . "(userid,"
                            . "courseid,"
                            . "psum,"
                            . "trans_id,"
                            . "auth_code,"
                            . "pdate) "
                            . "values('$userid',"
                            . "'$courseid',"
                            . "'$activity->amount',"
                            . "'imported',"
                            . "'00',"
                            . "'" . strtotime($activity->pdate) . "')";
                    $this->db->query($query);
                    echo "<br>Payment data for user ($activity->uid) was created<br>";
                } // end if $card_payment_exists==0
                else {
                    echo "<br>Credit card payment data for user ($activity->uid) already exists  <br>";
                    echo "Echo Credit Card payment date: " . $activity->pdate . "<br>";
                }
            } // end else payment made by CC card
        } // end if $activity->pstatus==0
    }

    function add_user_to_class($activity) {
        //$class_exists = $this->class_exists($activity->classid);
        $userid = $this->get_user_id_by_uid($activity->uid);
        if ($userid > 0) {
            $query = "insert into mdl_scheduler_appointment "
                    . "(slotid,"
                    . "studentid,"
                    . "attended) "
                    . "values ($activity->classid,"
                    . "$userid,"
                    . "0)";
            $this->db->query($query);
        } // end if $class_exists>0
    }

    function process_user_activities($filepath) {
        $activities = $this->get_users_activity_data($filepath);
        if (count($activities) > 0) {
            echo "Total items found: " . count($activities) . "<br>";
            foreach ($activities as $activity) {
                $this->enroll_user_to_course($activity);
                $this->create_user_certification_data($activity);
                $this->add_user_payment($activity);
                $this->add_user_to_class($activity);
                echo "<br>------------------------------------------------<br>";
            } // end foreach
        } // end if count($activities)>0
        else {
            echo "There are no any user activity ....<br>";
        }
    }

    function get_state_name($stateid) {
        $query = "select * from mdl_states where id=$stateid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $state = $row['state'];
        }
        return $state;
    }

    function get_courseid_by_context($contextid) {
        $query = "select * from mdl_context where id=$contextid";
        echo "Query: " . $query . "<br>";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $courseid = $row['instanceid'];
        }
        return $courseid;
    }

    function add_user_notes($email, $note) {
        $user_data = $this->get_user_data($email);
        $userid = $user_data->id;
        $query = "select * from mdl_role_assignments "
                . "where roleid=5 and userid=$userid";
        echo "Query: " . $query . "<br>";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $contextid = $row['contextid'];
        }
        if ($contextid > 0) {
            $courseid = $this->get_courseid_by_context($contextid);
            $query = "insert into mdl_post"
                    . "(module,"
                    . "userid,"
                    . "courseid,"
                    . "content,"
                    . "format,"
                    . "publishstate,"
                    . "lastmodified,"
                    . "created,"
                    . "usermodified) "
                    . "values('notes',"
                    . "'$userid',"
                    . "'$courseid',"
                    . "'$note',"
                    . "'2',"
                    . "'draft',"
                    . "'" . time() . "',"
                    . "'" . time() . "',"
                    . "'2')";
            echo "Query: " . $query . "<br>";
            $this->db->query($query);
        } // end if $contextid > 0
    }

    function update_user_states($filepath) {
        $handle = @fopen($filepath, "r");
        $counter = 0;
        if ($handle) {
            while (($buffer = fgets($handle, 4096)) !== false) {
                $clean_buffer = str_replace(',,', ',', $buffer);
                $data = explode(",", $clean_buffer);
                echo "<br>---------------------------------------------<br>";
                print_r($data);
                echo "<br>---------------------------------------------<br>";
                $counter++;

                $uid = $data[0];
                $email = strtolower(trim($data[1]));
                $stateid = trim($data[2]);
                $note = trim($data[3]);
                if ($uid != '' && $email != '' && $this->valid_email($email) && $stateid != '') {
                    $statename = $this->get_state_name($stateid);
                    $user_exists = $this->is_user_uid_exists($email);
                    echo "User exists: " . $user_exists . "<br>";
                    if ($user_exists > 0) {
                        $query = "update mdl_user set state='$statename' where email='$email'";
                        $this->db->query($query);
                        echo "User UID: " . $uid . "<br>";
                        echo "User State: " . $statename . "<br>";
                        echo "Query: " . $query . "<br>";
                        echo "User ($uid) has been updated <br>";
                        if ($note != '') {
                            echo "Inside when note is not empty ...<br>";
                            $this->add_user_notes($email, $note);
                            echo "User ($uid) has been updated with notes <br>";
                        } // end if $note!=''
                        echo "<br>----------------------------------------------<br>";
                    } // end if $user_exists>0
                    else {
                        echo "User ($uid) does not exist <br>";
                    } // end else
                } // end if $stateid != ''
            } // end while            
            echo "Total items in file: $counter";
            fclose($handle);
        } // end if $handle
        else {
            die("Error: can't open file <br>");
        } // end else
    }

    function update_users_addresses() {
        $certificates = array();
        $query = "select * from mdl_certificates order by userid desc";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $certificate = new stdClass();
            foreach ($row as $key => $value) {
                $certificate->$key = $value;
            }
            $certificates[] = $certificate;
        }
        $pdf2 = new PDF_Label('L7163');

        /*
          echo "<pre>";
          print_r($certificates);
          echo "</pre><br>";
         */

        $i = 0;
        echo "Total Certificates found: " . count($certificates);
        $pdf = new mPDF('utf-8', 'A4-P');
        foreach ($certificates as $certificate) {
            $user_address_block = $this->get_user_address_block($certificate->userid);

            echo "<br>-----------------------------------<br>";
            echo "User ID: " . $certificate->userid . "<br>";
            echo $user_address_block;

            $pdf2 = new PDF_Label('L7163');
            $pdf2->AddPage();
            $user_address = $this->get_user_address_data($certificate->userid);
            $text = sprintf("%s\n%s\n%s\n%s\n%s", "$user_address->firstname $user_address->lastname", "Phone: $user_address->phone1", "Email: $user_address->email", "$user_address->address", "$user_address->city, $user_address->state, $user_address->zip");
            //$text = sprintf("%s\n%s\n%s\n%s", $user_address_block);
            $pdf2->Add_Label($text);
            $dir_path = $this->cert_path . "/$certificate->userid";
            if (!is_dir($dir_path)) {
                if (!mkdir($dir_path)) {
                    die('Could not write to disk');
                } // end if !mkdir($dir_path)
            } // end if !is_dir($dir_path)
            $path = $dir_path . "/label.pdf";
            $pdf2->Output($path, 'F');
            $i++;
            /*
             * 
              $pdf->WriteHTML($user_address_block);
              $dir_path = $this->cert_path . "/$certificate->userid";
              if (!is_dir($dir_path)) {
              if (!mkdir($dir_path)) {
              die('Could not write to disk');
              } // end if !mkdir($dir_path)
              } // end if !is_dir($dir_path)
              $path = $dir_path . "/label.pdf";
              echo "Label location: ".$path."<br>";
              $pdf->Output($path, 'F');
              $pdf->Close();
              echo "<br>Address label for User ($certificate->userid) was created";
              echo "<br>-----------------------------------<br>";
              $i++;
              } // end foreach
             * 
             */
        } // end foreach
        echo "<br>Total Labels created: " . $i . "<br>";
    }

    function get_scheduler_id($uid) {
        switch ($uid) {
            case 556:
                $schedulerid = 8;
                break;
            case 671:
                $schedulerid = 5;
                break;
            case 672:
                $schedulerid = 4;
                break;
            case 673:
                $schedulerid = 16;
                break;
            case 676:
                $schedulerid = 12;
                break;
            case 677:
                $schedulerid = 14;
                break;
            case 678:
                $schedulerid = 11;
                break;
            case 696:
                $schedulerid = 10;
                break;
            case 697:
                $schedulerid = 13;
                break;
            case 698:
                $schedulerid = 9;
                break;
            case 699:
                $schedulerid = 6;
                break;
            case 756:
                $schedulerid = 7;
                break;
        }
        return $schedulerid;
    }

    function is_slot_exist($schedulerid, $location, $date) {
        $num = 0;
        $query = "SELECT `id` , `schedulerid` , 
            FROM_UNIXTIME( `starttime` , '%m/%d/%Y' ) as date , 
            `appointmentlocation` , 
            `notes` FROM `mdl_scheduler_slots` 
            where schedulerid='$schedulerid' "
                . "and appointmentlocation='$location' "
                . "and FROM_UNIXTIME( `starttime` , '%m/%d/%Y' )='$date'";
        //echo "Query: " . $query . "<br>";
        $num = $this->db->numrows($query);
        return $num;
    }

    function get_state_name_by_id($stateid) {
        $state = null;
        if ($stateid != '') {
            $query = "select * from mdl_states where id=$stateid";
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $state = $row['state'];
            }
        } // end if $stateid != ''
        return $state;
    }

    function add_one_day($date) {
        $date_arr = explode('/', $date);
        $day = $date_arr[1] + 1;
        $newdate = $date_arr[0] . "/" . $day . "/" . $date_arr[2];
        return $newdate;
    }

    function add_slot_item($slot) {
        $starttime = strtotime($slot->starttime);
        $teacherid = 234;
        $duration = 480;
        $query = "insert into mdl_scheduler_slots "
                . "(schedulerid,"
                . "uid,"
                . "starttime,"
                . "duration,"
                . "teacherid,"
                . "appointmentlocation,"
                . "notes) "
                . "values('$slot->schedulerid', "
                . "$slot->uid,"
                . "'$starttime',"
                . "'$duration',"
                . "'$teacherid',"
                . "'$slot->location',"
                . "'$slot->notes')";
        echo "Query: " . $query . "<br>";
        $this->db->query($query);
    }

    function add_scheduler_slots($filepath) {
        $handle = @fopen($filepath, "r");
        $i = 0;
        if ($handle) {
            while (($buffer = fgets($handle, 4096)) !== false) {
                $class_arr = explode(',', $buffer);
                $classid = $class_arr[0];
                $courseid = $class_arr[1];
                $schedulerid = $this->get_scheduler_id($courseid);
                //$classdate = $this->add_one_day($class_arr[3]);
                $classdate = $class_arr[3];
                $stateid = $class_arr[4];
                $statename = $this->get_state_name_by_id($stateid);
                $city = $class_arr[5];
                $location = $statename . "/" . $city;
                $venue = str_replace('"', '', $class_arr[6]);
                //$exists = $this->class_exists($classid);
                $exists = 0;
                if ($statename != null && $city != '' && $venue != '' && $exists == 0) {
                    echo "Class ID: " . $classid . "&nbsp;Course ID: " . $courseid . "&nbsp;Scheduler ID: " . $schedulerid . "&nbsp;Class location: $location &nbsp; Class notes: $venue &nbsp; Class Date: " . $classdate . "&nbsp; Class exists: $exists<br>";
                    $slot = new stdClass();
                    $slot->uid = $classid;
                    $slot->schedulerid = $schedulerid;
                    $slot->starttime = $classdate;
                    $slot->location = $location;
                    $slot->notes = $venue;
                    $this->add_slot_item($slot);
                    $i++;
                    echo "------------------------------------------------------------------------------------------------------------------------<br>";
                } // end if $statename!=null && $city!='' && $venue!=''
                else {
                    echo "Slot with ID: " . $classid . " already exists, skip this record ....<br>";
                }
            } // end while            
            echo "Total new slots: " . $i . "<br>";
            fclose($handle);
        } // end if $handle
        else {
            die("Error: can't open file <br>");
        } // end else
    }

    /*     * *********************************************************************
     * 
     *              Code related to exams import
     * 
     * ********************************************************************* */

    function get_question_category($courseid) {
        $catid = 0;
        switch ($courseid) {
            case 676:
                $catid = 10;
                break;
            case 677:
                $catid = 14;
                break;
            case 678:
                $catid = 15;
                break;
        }
        return $catid;
    }

    function get_stamp() {
        $now = time();
        $string = $this->get_password(6);
        $stamp = "medical2.com+" . $now . "+" . $string;
        return $stamp;
    }

    function process_exam_questions($filepath) {
        $handle = @fopen($filepath, "r");
        $i = 0;
        if ($handle) {
            while (($buffer = fgets($handle, 4096)) !== false) {
                $clean_buffer = str_replace(',,', ',', $buffer);
                $clean_buffer2 = str_replace('"', '', $clean_buffer);
                $q_arr = explode(',', $clean_buffer2);
                $cid = $q_arr[0];
                $q = $q_arr[1];
                $a = $q_arr[2];
                $b = $q_arr[3];
                $c = $q_arr[4];
                $d = $q_arr[5];
                $ca = trim($q_arr[6]);
                $catid = $this->get_question_category($cid);
                if ($catid > 0 && $q != '' && $a != '' && $b != '' && $c != '' && $d != '' && $ca != '') {
                    $question = new stdClass();
                    $question->category = $catid;
                    $question->name = $q;

                    echo "<br>--------------------------------------------------------------------------<br>";

                    $questionid = $this->add_question($question);
                    echo "Question ID: " . $questionid . "<br>";

                    if ($questionid > 0) {
                        $this->add_multichoice_options($questionid);
                        $answers = array();

                        $a1 = new stdClass();
                        $a1->id = $questionid;
                        $a1->answer = $a;
                        $a1->feedback = '';
                        if ($ca == 'A') {
                            $a1->fraction = '1.0000000';
                        } // end if
                        else {
                            $a1->fraction = '0.0000000';
                        } // end else

                        $a2 = new stdClass();
                        $a2->id = $questionid;
                        $a2->answer = $b;
                        $a2->feedback = '';
                        if ($ca == 'B') {
                            $a2->fraction = '1.0000000';
                        } // end if
                        else {
                            $a2->fraction = '0.0000000';
                        } // end else

                        $a3 = new stdClass();
                        $a3->id = $questionid;
                        $a3->answer = $c;
                        $a3->feedback = '';
                        if ($ca == 'C') {
                            $a3->fraction = '1.0000000';
                        } // end if
                        else {
                            $a3->fraction = '0.0000000';
                        } // end else

                        $a4 = new stdClass();
                        $a4->id = $questionid;
                        $a4->answer = $d;
                        $a4->feedback = '';
                        if ($ca == 'D') {
                            $a4->fraction = '1.0000000';
                        } // end if
                        else {
                            $a4->fraction = '0.0000000';
                        } // end else

                        array_push($answers, $a1, $a2, $a3, $a4);
                        $this->add_question_answers($answers);
                        $i++;
                        echo "<br>--------------------------------------------------------------------------<br>";
                    } // end if $questionid>0
                } // end if $catid > 0 && $q != '' ...
            } // end while
            echo "Total questions imported: " . $i . "<br>";
        } // end if $handle
        else {
            die("Error: can't open file <br>");
        } // end else
    }

    function add_question($q) {

        echo "<br>------<br>";
        print_r($q);
        echo "<br>------<br>";

        $catid = $q->category;
        $userid = $this->manager_id;
        $parent = 0;
        $qtype = "multichoice";
        $stamp = $this->get_stamp();
        $now = time();
        $query = "insert into mdl_question "
                . "(category,"
                . "parent,"
                . "name,"
                . "questiontext,"
                . "questiontextformat,"
                . "generalfeedback,"
                . "generalfeedbackformat,"
                . "qtype,"
                . "stamp,"
                . "version,"
                . "timecreated,"
                . "timemodified,"
                . "createdby,"
                . "modifiedby) "
                . "values('" . $catid . "',"
                . "'" . $parent . "',"
                . "'" . $q->name . "',"
                . "'" . $q->name . "',"
                . "'" . 1 . "',"
                . "'" . '' . "',"
                . "'" . 1 . "',"
                . "'" . $qtype . "',"
                . "'" . $stamp . "',"
                . "'" . $stamp . "',"
                . "'" . $now . "',"
                . "'" . $now . "',"
                . "'" . $userid . "',"
                . "'" . $userid . "')";
        echo "Query: " . $query . "<br>";
        mysql_query($query);
        $id = mysql_insert_id();
        return $id;
    }

    function add_multichoice_options($questionid) {
        $query = "insert into mdl_qtype_multichoice_options "
                . "(questionid,"
                . "layout,"
                . "single,"
                . "shuffleanswers,"
                . "correctfeedback,"
                . "correctfeedbackformat,"
                . "partiallycorrectfeedback,"
                . "partiallycorrectfeedbackformat,"
                . "incorrectfeedback,"
                . "incorrectfeedbackformat,"
                . "answernumbering,"
                . "shownumcorrect) "
                . "values ("
                . "$questionid,"
                . "0,"
                . "1,"
                . "1,"
                . "'',"
                . "1,"
                . "'',"
                . "1,"
                . "'',"
                . "1,"
                . "'abc',"
                . "0)";
        echo "Query: " . $query . "<br>";
        mysql_query($query);
    }

    function add_question_answers($answers) {
        foreach ($answers as $a) {
            $query = "insert into mdl_question_answers "
                    . "(question,"
                    . "answer,"
                    . "answerformat,"
                    . "fraction,"
                    . "feedback,"
                    . "feedbackformat) "
                    . "values ($a->id,"
                    . "'" . mysql_real_escape_string($a->answer) . "',"
                    . "1,"
                    . "$a->fraction,"
                    . "'" . $a->feedback . "',"
                    . "1)";
            echo "Query: " . $query . "<br>";
            $this->db->query($query);
        } // end foreach
    }

}
