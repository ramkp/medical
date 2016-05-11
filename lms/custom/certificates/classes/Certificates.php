<?php

/**
 * Description of Certificate
 *
 * @author sirromas
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/pdf/mpdf/mpdf.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/PDF_Label.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/Mailer.php';
require_once ($_SERVER['DOCUMENT_ROOT'] . '/lms/custom/invoices/classes/Invoice.php');

class Certificates extends Util {

    public $cert_path;
    public $limit = 3;

    function __construct() {
        parent::__construct();
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

    function get_total_certificates() {
        $query = "select * from mdl_certificates order by issue_date";
        $num = $this->db->numrows($query);
        return $num;
    }

    function get_certificates_list() {
        $certificates = array();
        $query = "select * from mdl_certificates order by issue_date desc limit 0, $this->limit";
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
        $_SESSION['total'] = $this->get_total_certificates();
        return $list;
    }

    function get_pagination_bar() {
        $list = "";
        $query = "select * from mdl_certificates order by issue_date";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $list.="<ul class='pagination'>";
            $result = $this->db->query($query);
            $i = 1;
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $list.="<li style='display:inline;margin-right:10px;'><a href='#' id='cert_page_" . $row['id'] . "' onClick='return false;'>$i</a></li>";
                $i++;
            } // end while
            $list.="</ul>";
        } // end if $num > 0
        return $list;
    }

    function create_certificates_list($certificates, $toolbar = true, $search = false) {
        $list = "";
        if (count($certificates) > 0) {
            if ($toolbar == true) {
                $list.="<div class='container-fluid' style='text-align:center;'>";
                $list.="<span class='span2'>Search</span>";
                $list.="<span class='span2'><input type='text' id='search_certificate' style='width:125px;' /></span>";
                $list.="<span class='span3'><button class='btn btn-primary' id='search_certificate_button'>Search</button></span>";
                $list.="<span class='span2'><button class='btn btn-primary' id='clear_certificate_button'>Clear filter</button></span>";
                $list.="</div>";
                $list.="<div class='container-fluid' style='text-align:center;'>";
                $list.="<span class='span8' style='color:red;' id='cert_err'></span>";
                $list.="</div>";
                $list.="<div class='container-fluid' style='display:none;text-align:center;' id='ajax_loader'>";
                $list.="<span class='span10'><img src='http://cnausa.com/assets/img/ajax.gif' /></span>";
                $list.="</div>";
            } // end if $toolbar==true            
            $list.="<div id='certificates_container'>";
            $total = count($certificates);
            if ($total <= $this->limit && $search == false) {
                $total = $this->get_total_certificates();
            } // end if $total<=$this->limit && $search==false
            $list.="<div class='container-fluid' style='text-align:center;font-weight:bold;'>";
            $list.="<span class='span10'>Total certificates: $total</span>";
            $list.="</div>";
            foreach ($certificates as $certificate) {
                $user = $this->get_user_details($certificate->userid);
                $coursename = $this->get_course_name($certificate->courseid);
                $date = date('Y-m-d', $certificate->issue_date);
                $exp_date = date('Y-m-d', $certificate->expiration_date);
                $_exp_date = ($exp_date == '') ? "n/a" : $exp_date;
                $cert_link = trim(str_replace($_SERVER['DOCUMENT_ROOT'], '', $certificate->path));
                $addr_link = "/lms/custom/certificates/$certificate->userid/label.pdf";
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
                $list.="<span class='span2'>Program name</span><span class='span6'>$coursename</span>";
                $list.="</div>";

                $list.="<div class='container-fluid'>";
                $list.="<span class='span2'>Certificate</span><span class='span6'><a href='$cert_link' target='_blank'>Print Certificate</a></span>";
                $list.="</div>";

                $list.="<div class='container-fluid'>";
                $list.="<span class='span2'>Address Label</span><span class='span6'><a href='$addr_link' target='_blank'>Print Address Label</a></span>";
                $list.="</div>";

                $list.="<div class='container-fluid'>";
                $list.="<span class='span2'>Issue date</span><span class='span2'>$date</span>";
                $list.="</div>";
                $list.="<div class='container-fluid'>";
                $list.="<span class='span2'>Expiration date</span><span class='span2'>$_exp_date</span>";
                $list.="</div>";
                $list.="<div class='container-fluid'>";
                $list.="<span class='span6'><hr/></span>";
                $list.="</div>";
            } // end foreach
            $list.="</div>";
            if ($toolbar == true) {
                $list.="<div class='container-fluid'>";
                $list.="<span class='span9'  id='pagination'></span>";
                $list.="</div>";
                $list.="<div class='container-fluid'>";
                $list.="<span class='span6'><a href='#' onClick='return false;' id='cert_send_page'>Send Certificate</a></span>";
                $list.="</div>";
                $list.=$this->get_send_certificate_page();
            } // end if $toolbar==true
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
        $list.="<span class='span6' id='send_cert_err'></span>";
        $list.="</div>";
        $list.="<div class='container-fluid'>";
        $list.="<span class='span8'>$programs_category</span>";
        $list.="</div>";
        $list.="<div class='container-fluid'>";
        $list.="<span class='span8' id='category_courses'></span>";
        $list.="</div>";
        $list.="<div class='container-fluid'>";
        $list.="<span class='span8' id='enrolled_users'></span>";
        $list.="</div>";
        $list.="<div class='container-fluid'>";
        $list.="<span class='span2'><button type='button' id='send_cert' class='btn btn-primary'>Send</button></span>";
        //$list.="<span class='span2'><button type='button' id='print_label' class='btn btn-primary'>Address Label</button></span>";
        //$list.="<span class='span2'><button type='button' id='print_cert' class='btn btn-primary'>Print Certificate</button></span>";
        $list.="</div></div>";
        return $list;
    }

    function get_course_completion($courseid, $userid, $raw = false) {
        $date = 0;
        $query = "select * from mdl_course_completions where "
                . "course=$courseid and userid=$userid";
        //echo "Query: ".$query."<br>";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                if ($raw == FALSE) {
                    $date = date('Y-m-d', $row['reaggregate']);
                } // end if $raw==FALSE
                else {
                    $date = $row['reaggregate'];
                }
            } // end while
        } // end if $num > 0)
        return $date;
    }

    function get_user_details($id) {
        $query = "select firstname, lastname, email from mdl_user where id=$id";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $user = new stdClass();
            if ($row['firstname'] != '' && $row['lastname'] != '') {
                $user->firstname = $row['firstname'];
                $user->lastname = $row['lastname'];
                $user->email = $row['email'];
            } // end if $row['firstname'] != '' && $row['lastname'] != ''
        } // end while
        return $user;
    }

    function get_course_renew_status($courseid) {
        $query = "select expired from mdl_course where id=$courseid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $expire = $row['expired'];
        }
        return $expire;
    }

    function get_course_code($courseid, $userid) {
        $query = "select fullname from mdl_course where id=$courseid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $name = $row['fullname'];
        }
        //$course_name=trim(str_replace('Workshop', '', $name));
        $course_name = $name;
        $date = time();
        $day = date('d', $date);
        $month = date('m', $date);
        $year = date('y', $date);

        $expr = '/(?<=\s|^)[a-z]/i';
        preg_match_all($expr, $course_name, $matches);
        $result = implode('', $matches[0]);
        $result = strtoupper($result);
        $code = "$month$day$year-$result$userid";
        return $code;
    }

    function get_certificate_title($courseid) {
        $query = "select category from mdl_course where id=$courseid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $category = $row['category'];
        }
        if ($category == 2 || $category == 4) {
            $title = "Medical2 Certification Agency";
        } else {
            $title = "Medical2 Career College";
        }
        return $title;
    }

    function prepare_ceriticate($courseid, $userid, $date = 0) {
        $list = "";
        $coursename = $this->get_course_name($courseid); // string
        $userdetails = $this->get_user_details($userid); // object
        $firstname = strtoupper($userdetails->firstname);
        $lastname = strtoupper($userdetails->lastname);
        if ($date == 0) {
            $date = time();
        }
        $day = date('d', $date);
        $month = date('M', $date);
        $year = date('Y', $date);
        $renew_status = $this->get_course_renew_status($courseid);
        $title = $this->get_certificate_title($courseid);
        $code = $this->get_course_code($courseid, $userid);
        //echo "Certificate code: ".$code."<br>";
        if ($renew_status == 1) {
            $expiration_date_sec = $date + 31536000;
        }
        $expiration_date = strtoupper(date('m-d-Y', $expiration_date_sec));
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
        $list.="<br><br><br><br><p style='align:center;text-decoration:underline;font-size:15pt;font-weight:normal;'>CERTIFICATION #: $code<br>";
        if ($renew_status == true) {
            $list.="EXPIRATION DATE $expiration_date</p>";
        }
        //$list.="<div align='left' style='font-family:king;text-decoration:underline;border-bottom:thick;font-size:10pt;'>Shahid Malik<br/><span style='float:left;font-size:12pt;font-family: Geneva, Arial, Helvetica, sans-serif;'>Shahid Malik, President</span> &nbsp; <span align='left' style='font-family:king;text-decoration:underline;border-bottom:thick;font-size:10pt;'>Donna Steele<br/><span style='float:left;font-size:12pt;font-family: Geneva, Arial, Helvetica, sans-serif;'>Donna Steele, Director</span></span></div>";
        //$list.="</div>";        
        $list.="<div align='left'><table border='0' width='675px;'><tr><td style='font-family:king;text-decoration:underline;border-bottom:thick;font-size:10pt;'>Shahid Malik<br/><span style='float:left;font-size:12pt;font-family: Geneva, Arial, Helvetica, sans-serif;text-decoration:none; '>President</td><td align='left' style='font-family:king;text-decoration:underline;border-bottom:thick;font-size:10pt;margin-left:40px;'>Donna Steele<br/><span style='float:right;font-size:12pt;font-family: Geneva, Arial, Helvetica, sans-serif;text-decoration:none;'>Director</span></td></tr></table></div>";
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

    function get_course_category($id) {
        $query = "select category from mdl_course where id=$id";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $categoryid = $row['category'];
        } // end while
        if ($categoryid == 2 || $categoryid == 4) {
            return true;
        } // end if $categoryid==2 || $categoryid==4
        else {
            return false;
        } // end else
    }

    function is_certificate_exists($courseid, $userid) {
        $query = "select * from mdl_certificates "
                . "where courseid=$courseid and userid=$userid";
        $num = $this->db->numrows($query);
        return $num;
    }

    function send_certificate($courseid, $userid, $date = 0) {
        //echo "Date: ".$date."<br>";
        if ($date == 0) { // course is not yet completed
            $date = $this->get_user_course_entollment_date($courseid, $userid);
        } // end if $date == 0
        $renew_status = $this->get_course_category($courseid);
        if ($renew_status) {
            $expiration_date_sec = $date + 31536000;
        } // end if $renew_status
        else {
            $expiration_date_sec = 'n/a';
        } // end else
        $path = $this->prepare_ceriticate($courseid, $userid, $date);
        $this->create_label($courseid, $userid);
        $user = $this->get_user_details($userid);
        $user->path = $path;
        $code = $this->get_course_code($courseid, $userid);
        $certificate_exists = $this->is_certificate_exists($courseid, $userid);
        if ($certificate_exists == 0) {
            $query = "insert into mdl_certificates (courseid,"
                    . "userid,"
                    . "path, cert_no, "
                    . "issue_date,"
                    . "expiration_date) "
                    . "values('$courseid',"
                    . " '$userid',"
                    . " '$path', '$code', "
                    . " '$date',"
                    . " '$expiration_date_sec')";
            //echo "Query: ".$query."<br>";
            $this->db->query($query);
        } // end if $certificate_exists==0
        $mailer = new Mailer();
        $mailer->send_certificate($user);
        $list = "Certificate has been sent";
        return $list;
    }

    function get_certificate_item($page) {
        $certificates = array();
        $rec_limit = $this->limit;
        if ($page == 1) {
            $offset = 0;
        } // end if $page==1
        else {
            $page = $page - 1;
            $offset = $rec_limit * $page;
        }
        $query = "select * from mdl_certificates order by issue_date desc LIMIT $offset, $rec_limit";
        //echo "Query: ".$query."<br>";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $certificate = new stdClass();
            foreach ($row as $key => $value) {
                $certificate->$key = $value;
            } // end foreach
            $certificates[] = $certificate;
        } // end while
        $list = $this->create_certificates_list($certificates, false);
        return $list;
    }

    function verify_certificate($cert_fio, $cert_no) {
        $list = "";
        $query = "select * from mdl_certificates where cert_no='$cert_no'";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $exp_date = $row['expiration_date'];
            } //end while
            if ($exp_date != 'n/a') {
                $cert_exp_date = date('Y-m-d', $exp_date);
                $list.="Your Certificate will expire at $cert_exp_date";
            } // end if $exp_date!='n/a'
            else {
                $list.="Your Certificate has no expiration date";
            }
        } // end if $num > 0
        else {
            $list.="Certificate is not found";
        }
        return $list;
    }

    function get_user_address_data($userid) {
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

    function create_label($courseid, $userid) {
        $user_address = $this->get_user_address_data($userid);
        $pdf = new PDF_Label('L7163');
        $pdf->AddPage();
        $text = sprintf("%s\n%s\n%s\n%s", "$user_address->firstname $user_address->lastname", "$user_address->address", "$user_address->state/" . $user_address->city . "", "$user_address->zip");
        $pdf->Add_Label($text);
        $dir_path = $this->cert_path . "/$userid";
        if (!is_dir($dir_path)) {
            if (!mkdir($dir_path)) {
                die('Could not write to disk');
            } // end if !mkdir($dir_path)
        } // end if !is_dir($dir_path)
        $path = $dir_path . "/label.pdf";
        $pdf->Output($path, 'F');
    }

    function get_certificate_classes($item) {
        $schedulers = array();
        $courses = array();
        $query = "select * from mdl_scheduler_slots where notes like '%$item%'";
        //echo "Query: ".$query."<br>";
        $num = $this->db->numrows($query);
        //echo "Slots num: ".$num."<br>";
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $schedulers[] = $row['schedulerid'];
            } // end while
            //echo "<br>----------Schedulers--------------------<br>";
            //print_r($schedulers);
            //echo "<br>";
            foreach ($schedulers as $scheduler) {
                $query = "select * from mdl_scheduler where id=$scheduler";
                //echo "Scheduler Query: ".$query."<br>";
                $result = $this->db->query($query);
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $courses[] = $row['course'];
                } // end while
            } // end foreach
        } // end if $num > 0
        return $courses;
    }

    function search_certificate($item) {
        $list = "";
        $certificates = array();
        $invoice = new Invoices();
        $users_array = array_unique($invoice->search_invoice_users($item));
        $courses_array = array_unique($invoice->search_invoice_courses($item));
        $classes_array = array_unique($this->get_certificate_classes($item));
        $programs_array = array_unique(array_merge($courses_array, $classes_array));
        $users_list = implode(",", $users_array);
        $courses_list = implode(",", $programs_array);
        
        /*
         * 
        echo "<br>--------------Users array--------------<br>";
        print_r($users_array);
        echo "<br>--------------Classes array--------------<br>";
        print_r($classes_array);
        echo "<br>--------------Courses array--------------<br>";
        print_r($courses_array);
        echo "<br>--------------Programs array--------------<br>";
        print_r($programs_array);
        echo "<br>";
         * 
         */

        if ($users_list != '') {
            $query = "select * from mdl_certificates "
                    . "where userid in ($users_list) order by issue_date desc ";
        } // end if $users_list != ''
        if ($courses_list != '') {
            $query = "select * from mdl_certificates "
                    . "where courseid in ($courses_list) order by issue_date desc ";
        } // end if $courses_list != ''
        if ($users_list != '' && $courses_list != '') {
            $query = "select * from mdl_certificates "
                    . "where  (courseid in ($courses_list) "
                    . "or userid in ($users_list)) order by issue_date desc ";
        } // end if $users_list != '' && $courses_list != ''
        //echo "Certificates query: " . $query . "<br>";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $certificate = new stdClass();
                foreach ($row as $key => $value) {
                    $certificate->$key = $value;
                }
                $certificates[] = $certificate;
            } // end while
            $list.=$this->create_certificates_list($certificates, false, true);
        } // end if num>0
        else {
            $list.="<div class='container-fluid' style='text-align:center;'>";
            $list.="<span class='span6'>No Ecrtificates found</span>";
            $list.="</div>";
        }
        return $list;
    }

}
