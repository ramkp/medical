<?php

/**
 * Description of Mailer
 *
 * @author sirromas
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/certificates/classes/Renew.php';
//require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/codes/classes/Codes.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/pdf/dompdf/autoload.inc.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/mailer/vendor/PHPMailerAutoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/Payment.php';

use Dompdf\Dompdf;

class Mailer {

    public $mail_smtp_host = 'mail.medical2.com';
    public $mail_smtp_port = 25;
    public $mail_smtp_user = 'info@medical2.com';
    //public $mail_smtp_user = 'medical2@medical2.com';
    public $mail_smtp_pwd = 'aK6SKymc';
    public $invoice_path;
    public $registration_path;
    public $renewal_path;
    public $db;

    function __construct() {
        $this->db = new pdo_db();
        $this->invoice_path = $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/invoices';
        $this->registration_path = $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/invoices/registrations';
        $this->renewal_path = $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/invoices/renewal';
    }

    function get_workshop_cost($id) {
        $query = "select * from mdl_scheduler_slots where id=$id";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $cost = $row['cost'];
        }
        return $cost;
    }

    function get_course_name($user) {
        $query = "select * from mdl_course where id=$user->courseid";
        //echo "Query: " . $query . "<br>";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $name = $row['fullname'];
        }
        return $name;
    }

    function get_classs_info($user) {
        $list = "";

        if ($user->slotid > 0) {
            $query = "select * from mdl_scheduler_slots where id=$user->slotid";
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $notes = $row['notes'];
                $start = date('m-d-Y', $row['starttime']);
                $location_arr = explode("/", $row['appointmentlocation']);
                $location = $location_arr[1] . "," . $location_arr[0];
            } // end while
            if ($user->courseid == 45) {
                $list.="<p align='left'>Date: $start 9 A.M<br>Location: $location<br>Venue: $notes</p>";
            } // end if
            else {
                $list.="<p align='left'>Date: $start <br>Location: $location<br>Venue: $notes</p>";
            } // end else
        } // end if $user->slotid>0
        else {
            $list.="n/a";
        }
        return $list;
    }

    function is_group_user($user) {
        $query = "select * from mdl_groups_members where userid=$user->userid";
        $num = $this->db->numrows($query);
        return $num;
    }

    function get_group_user_num($user) {
        $query = "select * from mdl_groups_members where userid=$user->userid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $groupid = $row['groupid'];
        } // end while 
        $query = "select count(id) as total from mdl_groups_members "
                . "where groupid=$groupid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $total = $row['total'];
        } // end while
        return $total;
    }

    function get_course_cost($user) {
        $query = "select * from mdl_course where id=$user->courseid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $cost = $row['cost'];
        }
        return $cost;
    }

    function get_course_category($user) {
        $query = "select * from mdl_course where id=$user->courseid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $catid = $row['category'];
        }
        return $catid;
    }

    function send_partial_payment_confirmation2($user) {
        $list = "";
        $course_name = $this->get_course_name($user);
        $class_info = $this->get_classs_info($user);
        $course_cost = $this->get_course_cost($user);
        $userdata = $this->get_user_details($user->userid);
        /* ******************************************************************
         *  Apply workaround if slot is not selected - use course cost
         * ****************************************************************** */
        if ($user->slotid > 0) {
            $ws_cost = $this->get_workshop_cost($user->slotid);
        } // end if $user->slotid>0
        else {
            $ws_cost = 0;
        } // end else
        $cost = ($ws_cost > 0) ? $ws_cost : $course_cost;
        $catid = $this->get_course_category($user);
        $receipt_preface = $this->get_receipt_preface($catid, $user->userid);
        $p = new Payment();
        $state = $p->get_state_name_by_id($user->state);

        if ($user->period == 0) {

            $list.= "<!DOCTYPE HTML><html><head><title>Payment Confirmation</title>";
            $list.="</head>";
            $list.="<body><br/><br/><br/><br/>";
            $list.="<div class='datagrid'>            
            <table style='table-layout: fixed;' width='360'>
            <thead>";

            if ($catid == 5) {
                $list.="<tr>";
                $list.="<th colspan='2' align='left'><img src='http://medical2.com/assets/logo/receipt_college.png' width='360' height='130'></th>";
                $list.="</tr>";
            } // end if
            else {
                $list.="<tr>";
                $list.="<th colspan='2' align='left'><img src='http://medical2.com/assets/logo/receipt_agency.png' width='360' height='120'></th>";
                $list.="</tr>";
            } // end else

            $list.="</thead>
            <tbody>

            <tr style='font-weight:bold;text-align:left;background-color:#F5F5F5;'>
            <td colspan='2'>$receipt_preface</td>
            </tr>

            <tr style=''>
            <td>First name</td><td>$userdata->firstname</td>
            </tr>

            <tr>
            <td>Last name</td><td>$userdata->lastname</td>
            </tr>

            <tr>
            <td>Username</td><td>$userdata->email</td>
            </tr>

            <tr>
            <td>Password</td><td>$userdata->purepwd</td>
            </tr>

            <tr>
            <td>Phone</td><td>$userdata->phone1</td>
            </tr>

            <tr>
            <td>Address</td><td>$userdata->address</td>
            </tr>

            <tr>
            <td>City</td><td>$userdata->city</td>
            </tr>

            <tr>
            <td>State</td><td>$userdata->state</td>
            </tr>

            <tr>
            <td>Zip</td><td>$userdata->zip</td>
            </tr>

            </table>
        
            <br><table style='table-layout: fixed;' width='375'>

            <tr style='font-weight:bold;text-align:left;background-color:#F5F5F5;'>
            <td colspan='2'>Billing<br></td>
            </tr>";

            if ($user->firstname != '' && $user->lastname != '') {
                $list.="<tr>
                <td>Billing Name</td><td>$user->firstname $user->lastname</td>
                </tr>";
            } // end if
            else {
                $list.="<tr>
                <td>Billing Name</td><td>$userdata->firstname $userdata->lastname</td>
                </tr>";
            } // end else

            if ($user->receipt_email != 'n/a' && $user->receipt_email != '') {
                $list.="<tr style=''>
            <td>Email</td><td>$user->receipt_email</td>
            </tr>";
            } // end if
            else {
                $list.="<tr style=''>
            <td>Email</td><td>$userdata->email</td>
            </tr>";
            }

            $list.="<tr>
            <td>Phone</td><td>$user->phone1</td>
            </tr>

            <tr style=''>
            <td>Address</td><td>$user->address</td>
            </tr>
            <tr >
            <td>City</td><td>$user->city</td>
            </tr>        
            <tr style=''>
            <td>State</td><td>$state</td>
            </tr>
            <tr >
            <td>Zip</td><td>$user->zip</td>
            </tr>

            <tr style=''>
            <td>Progarm</td><td>$course_name</td>
            </tr>

            <tr>
            <td>Program Fee</td><td>$$cost</td>
            </tr>";

            if (property_exists($user, 'payment_amount')) {
                date_default_timezone_set("America/New_York");
                $date = date('m-d-Y h:i:s', time());

                $list.="<tr style=''>
                <td>Payment: </td><td>Paid by cash/cheque: $$user->payment_amount</td>
                </tr>";

                $list.="<tr style=''>";
                $list.="<td>Date Order:</td><td>$date</td>";
                $list.="</tr>";
            } // end if $payment_amount != null

            $list.="<tr style=''>
            <td>Class info</td><td>$class_info</td>
            </tr>";

            if ($catid == 2) {
                $list.="<tr style=''>";
                $list.="<td colspan='2'>Dress is casual with close toe shoes. Bring a photo ID. Arrive 10 minutes early.</td>";
                $list.="</tr>";
            }

            $list.="</tbody>
            </table>
            </div>";
            $list.="<p>If you need assistance please contact us by email <a href='mailto:help@medical2.com'>help@medical2.com</a> or call us 877-741-1996</p>";
            $list.="</body></html>";
            $subject = 'Medical2 - Payment Confirmation';
        }// end if $user->period==0
        else {
            $list.= "<!DOCTYPE HTML><html><head><title>Certificate Renew Confirmation</title>";
            $list.="</head>";
            $list.="<body><br/><br/><br/><br/>";
            $list.="<div class='datagrid'>            
            <table style='table-layout: fixed;' width='360'>
            <thead>";

            $list.="<tr>";
            $list.="<th colspan='2' align='left'><img src='http://medical2.com/assets/logo/receipt_agency.png' width='360' height='120'></th>";
            $list.="</tr>";

            $list.="</thead>
            <tbody>
        
            <tr style='background-color:#F5F5F5;'>
            <td>First name</td><td>$user->firstname</td>
            </tr>

            <tr>
            <td>Last name</td><td>$user->lastname</td>
            </tr>

            <tr style='background-color:#F5F5F5;'>
            <td>Email</td><td>$user->email</td>
            </tr>

            <tr>
            <td>Phone</td><td>$user->phone1</td>
            </tr>

            <tr style='background-color:#F5F5F5;'>
            <td>Applied Program</td><td>Certification renewal</td>
            </tr> 

            <tr>
            <td>Amount paid</td><td>$$user->payment_amount</td>
            </tr> 

            <tr>
            <td colspan='2' align='left'>&nbsp;</td>
            </tr> 

            <tr>
            <td colspan='2' align='left'>This certification have been renewed</td>
            </tr> 

            </table></body></html>";
            $subject = 'Medical2 - Certificate Renew Payment';
        } // end else

        $this->send_common_message($subject, $list, $user->email);
    }

    function send_partial_payment_confirmation($user) {
        $list = "";
        $course_name = $this->get_course_name($user);
        $class_info = $this->get_classs_info($user);
        $course_cost = $this->get_course_cost($user);
        /* ****************************************************************
         *  Apply workaround if slot is not selected - use course cost
         * ****************************************************************** */
        if ($user->slotid > 0) {
            $ws_cost = $this->get_workshop_cost($user->slotid);
        } // end if $user->slotid>0
        else {
            $ws_cost = 0;
        } // end else
        $cost = ($ws_cost > 0) ? $ws_cost : $course_cost;
        $catid = $this->get_course_category($user);
        $p = new Payment();
        $state = $p->get_state_name_by_id($user->state);

        if ($user->period == 0) {

            $list.= "<!DOCTYPE HTML><html><head><title>Payment Confirmation</title>";
            $list.="</head>";
            $list.="<body><br/><br/><br/><br/>";
            $list.="<div class='datagrid'>            
            <table style='table-layout: fixed;' width='360'>
            <thead>";

            if ($catid == 5) {
                $list.="<tr>";
                $list.="<th colspan='2' align='left'><img src='http://medical2.com/assets/logo/receipt_college.png' width='360' height='130'></th>";
                $list.="</tr>";
            } // end if
            else {
                $list.="<tr>";
                $list.="<th colspan='2' align='left'><img src='http://medical2.com/assets/logo/receipt_agency.png' width='360' height='120'></th>";
                $list.="</tr>";
            } // end else

            $list.="</thead>
            <tbody>
            <tr style='background-color:#F5F5F5;'>
            <td>First name</td><td>$user->firstname</td>
            </tr>
            <tr>
            <td>Last name</td><td>$user->lastname</td>
            </tr>
            <tr style='background-color:#F5F5F5;'>
            <td>Email</td><td>$user->email</td>
            </tr>
            <tr>
            <td>Phone</td><td>$user->phone1</td>
            </tr>
            <tr style='background-color:#F5F5F5;'>
            <td>Username</td><td>$user->email</td>
            </tr>
            <tr >
            <td>Password</td><td>$user->purepwd</td>
            </tr>
            <tr style='background-color:#F5F5F5;'>
            <td>Address</td><td>$user->address</td>
            </tr>
            <tr >
            <td>City</td><td>$user->city</td>
            </tr>        
            <tr style='background-color:#F5F5F5;'>
            <td>State</td><td>$state</td>
            </tr>
            <tr >
            <td>Zip</td><td>$user->zip</td>
            </tr>
            <tr style='background-color:#F5F5F5;'>
            <td>Applied Progarm</td><td>$course_name</td>
            </tr>
            <tr >
            <td>Program fee</td><td>$$cost</td>
            </tr>";

            if (property_exists($user, 'payment_amount')) {
                date_default_timezone_set("America/New_York");
                $date = date('m-d-Y h:i:s', time());

                $list.="<tr style='background-color:#F5F5F5;'>
                <td>Payment status: </td><td>Paid by cash/cheque: $$user->payment_amount</td>
                </tr>";

                $list.="<tr style='background-color:#F5F5F5;'>";
                $list.="<td>Order Date:</td><td>$date</td>";
                $list.="</tr>";
            } // end if $payment_amount != null

            $list.="<tr style='background-color:#F5F5F5;'>
            <td>Class info</td><td>$class_info</td>
            </tr>";

            if ($catid == 2) {
                $list.="<tr style=''>";
                $list.="<td colspan='2'>Dress is casual with close toe shoes. Bring a photo ID. Arrive 10 minutes early.</td>";
                $list.="</tr>";
            }

            $list.="</tbody>
            </table>
            </div>";
            $list.="<p>If you need assistance please contact us by email <a href='mailto:help@medical2.com'>help@medical2.com</a> or call us 877-741-1996</p>";
            $list.="</body></html>";
            $subject = 'Medical2 - Payment Confirmation';
        }// end if $user->period==0
        else {
            $list.= "<!DOCTYPE HTML><html><head><title>Certificate Renew Confirmation</title>";
            $list.="</head>";
            $list.="<body><br/><br/><br/><br/>";
            $list.="<div class='datagrid'>            
            <table style='table-layout: fixed;' width='360'>
            <thead>";

            $list.="<tr>";
            $list.="<th colspan='2' align='left'><img src='http://medical2.com/assets/logo/receipt_agency.png' width='360' height='120'></th>";
            $list.="</tr>";

            $list.="</thead>
            <tbody>

            <tr style='background-color:#F5F5F5;'>
            <td>First name</td><td>$user->firstname</td>
            </tr>

            <tr>
            <td>Last name</td><td>$user->lastname</td>
            </tr>

            <tr style='background-color:#F5F5F5;'>
            <td>Email</td><td>$user->email</td>
            </tr>

            <tr>
            <td>Phone</td><td>$user->phone1</td>
            </tr>

            <tr style='background-color:#F5F5F5;'>
            <td>Applied Program</td><td>Certification renewal</td>
            </tr> 

            <tr>
            <td>Amount paid</td><td>$$user->payment_amount</td>
            </tr> 

            <tr>
            <td colspan='2' align='left'>&nbsp;</td>
            </tr> 

            <tr>
            <td colspan='2' align='left'>This certification have been renewed</td>
            </tr> 

            </table></body></html>";
            $subject = 'Medical2 - Certificate Renew Payment';
        } // end else

        $this->send_common_message($subject, $list, $user->email);
    }

    function get_group_students_num_by_amount($program_cost, $paid_amount) {
        $num = round($paid_amount / $program_cost);
        return $num;
    }

    function get_group_students($groupname) {
        $list = "";
        $query = "select * from mdl_groups where name='$groupname'";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $groupid = $row['id'];
        }

        $query = "select * from mdl_groups_members where groupid=$groupid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $users[] = $row['userid'];
        }

        if (count($users) > 0) {
            foreach ($users as $userid) {
                $user = new stdClass();
                $user->userid = $userid;
                $userdata = $this->get_user_data($user);

                $list.="<tr>";
                $list.="<td>First name</td><td>$userdata->firstname</td>";
                $list.="</tr>";

                $list.="<tr>";
                $list.="<td>Last name</td><td>$userdata->lastname</td>";
                $list.="</tr>";

                $list.="<tr>";
                $list.="<td>Email</td><td>$userdata->email</td>";
                $list.="</tr>";

                $list.="<tr>";
                $list.="<td colspan='2'><br></td>";
                $list.="</tr>";
            } // end foreach
        } // end if count($users)>0

        return $list;
    }

    function get_group_students2($users, $groupname) {
        $list = "";

        $list.="<tr style='font-weight:bold;'>";
        $list.="<td>Group name:</td><td>$groupname</td>";
        $list.="</tr>";

        foreach ($users as $user) {
            $list.="<tr>";
            $list.="<td>First name</td><td>$user->fname</td>";
            $list.="</tr>";

            $list.="<tr>";
            $list.="<td>Last name</td><td>$user->lname</td>";
            $list.="</tr>";

            $list.="<tr>";
            $list.="<td>Email</td><td>$user->email</td>";
            $list.="</tr>";

            $list.="<tr>";
            $list.="<td>Phone</td><td>$user->phone</td>";
            $list.="</tr>";

            $list.="<tr>";
            $list.="<td colspan='2'><br></td>";
            $list.="</tr>";
        } // end foreach
        return $list;
    }

    function send_new_group_payment_confirmation_message($buyer, $users, $printed_data = null) {
        $list = "";
        $venue = $this->get_classs_info($buyer);
        $students = $this->get_group_students2($users, $buyer->groupname);
        $course_name = $this->get_course_name($buyer);
        $course_cost = $this->get_course_cost($buyer);
        if ($buyer->slotid > 0) {
            $ws_cost = $this->get_workshop_cost($buyer->slotid);
        } // end if $user->slotid>0
        else {
            $ws_cost = 0;
        } // end else
        $cost = ($ws_cost > 0) ? $ws_cost : $course_cost;
        $students_num = $buyer->total;
        $catid = $this->get_course_category($buyer);

        // **************** Presentation level ***********************
        $list.= "<!DOCTYPE HTML><html><head><title>Group registration</title>";
        $list.="</head>";
        $list.="<body><br/><br/><br/><br/>";
        $list.="<div class='datagrid'>";

        $list.="<table style='table-layout: fixed;' width='360'>
        <thead>";

        if ($printed_data == NULL) {
            if ($catid == 5) {
                $list.="<tr>";
                $list.="<th colspan='2' align='left'><img src='http://medical2.com/assets/logo/receipt_college.png' width='360' height='130'></th>";
                $list.="</tr>";
            } // end if
            else {
                $list.="<tr>";
                $list.="<th colspan='2' align='left'><img src='http://medical2.com/assets/logo/receipt_agency.png' width='360' height='120'></th>";
                $list.="</tr>";
            } // end else
        } // end if $printed_data == NULL


        $list.="</thead>
        <tbody>";

        $list.=$students;

        $list.="<tr style='background-color:#F5F5F5;'>
        <td>Total students</td><td>$students_num</td>
        </tr>
        
        <tr style=''>
        <td colspan='2' style='font-weight:bold;'><br>Billing info</td>
        </tr>
        
        <tr style='background-color:#F5F5F5;'>
        <td>First name</td><td>$buyer->firstname</td>
        </tr>
        
        <tr>
        <td>Last name</td><td>$buyer->lastname</td>
        </tr>
        
        <tr style='background-color:#F5F5F5;'>
        <td>Email</td><td>$buyer->email</td>
        </tr>
        
        <tr style='background-color:#F5F5F5;'>
        <td>Phone</td><td>$buyer->phone</td>
        </tr>
        
        <tr style='background-color:#F5F5F5;'>
        <td>Applied Progarm</td><td>$course_name</td>
        </tr>
        
        <tr>
        <td>Program fee</td><td>$$cost</td>
        </tr>";

        if (property_exists($buyer, 'payment_amount')) {
            date_default_timezone_set("America/New_York");
            $date = date('m-d-Y h:i:s', time());
            if ($buyer->ptype == 'card') {
                $list.="<tr style='background-color:#F5F5F5;'>
            <td>Payment status: </td><td>Paid by card: $$buyer->payment_amount</td>
            </tr>";
            } // end if
            else {
                $list.="<tr style='background-color:#F5F5F5;'>
            <td>Payment status: </td><td>Paid by paypal: $$buyer->payment_amount</td>
            </tr>";
            } // end else

            $list.="<tr style='background-color:#F5F5F5;'>";
            $list.="<td>Order Date:</td><td>$date</td>";
            $list.="</tr>";
        } // end if $payment_amount != null

        $list.="<tr>";
        $list.="<td>Class info</td><td>$venue</td>";
        $list.="</tr>";

        if ($catid == 2) {
            $list.="<tr style=''>";
            $list.="<td colspan='2'>Dress is casual with close toe shoes. Bring a photo ID. Arrive 10 minutes early.</td>";
            $list.="</tr>";
        }

        $list.="</tbody>
        </table>
        </div>";
        $list.="<p>If you need assistance please contact us by email <a href='mailto:help@medical2.com'>help@medical2.com</a> or call us 877-741-1996</p>";
        $list.="</body></html>";

        $subject = "Medical2 - Group Registration";
        $recipient = $buyer->email;
        $payment_amount = $buyer->payment_amount;
        $this->send_signup_confirmation_email($subject, $list, $recipient, $payment_amount);
        return $list;
    }

    function send_group_payment_message($user, $printed_data = null) {
        $list = "";
        $venue = $this->get_classs_info($user);
        $groupname = $user->groupname;
        $students = $this->get_group_students($groupname);
        $course_name = $this->get_course_name($user);
        $course_cost = $this->get_course_cost($user);
        /* ******************************************************************
         *  Apply workaround if slot is not selected - use course cost
         * ****************************************************************** */
        if ($user->slotid > 0) {
            $ws_cost = $this->get_workshop_cost($user->slotid);
        } // end if $user->slotid>0
        else {
            $ws_cost = 0;
        } // end else
        $cost = ($ws_cost > 0) ? $ws_cost : $course_cost;
        $students_num = $this->get_group_students_num_by_amount($cost, $user->payment_amount);
        $catid = $this->get_course_category($user);
        $p = new Payment();
        $state = $p->get_state_name_by_id($user->state);

        // **************** Presentation level ***********************
        $list.= "<!DOCTYPE HTML><html><head><title>Account Confirmation</title>";
        $list.="</head>";
        $list.="<body><br/><br/><br/><br/>";
        $list.="<div class='datagrid'>";

        $list.="<table style='table-layout: fixed;' width='360'>
        <thead>";

        if ($printed_data == NULL) {
            if ($catid == 5) {
                $list.="<tr>";
                $list.="<th colspan='2' align='left'><img src='http://medical2.com/assets/logo/receipt_college.png' width='360' height='130'></th>";
                $list.="</tr>";
            } // end if
            else {
                $list.="<tr>";
                $list.="<th colspan='2' align='left'><img src='http://medical2.com/assets/logo/receipt_agency.png' width='360' height='120'></th>";
                $list.="</tr>";
            } // end else
        } // end if $printed_data == NULL


        $list.="</thead>
        <tbody>";

        $list.=$students;

        $list.="<tr style='background-color:#F5F5F5;'>
        <td>Total students</td><td>$students_num</td>
        </tr>
        
        <tr style=''>
        <td colspan='2' style='font-weight:bold;'><br>Billing info</td>
        </tr>
        
        <tr style='background-color:#F5F5F5;'>
        <td>First name</td><td>$user->first_name</td>
        </tr>
        
        <tr>
        <td>Last name</td><td>$user->last_name</td>
        </tr>
        
        <tr style='background-color:#F5F5F5;'>
        <td>Email</td><td>$user->bill_email</td>
        </tr>
        
        <tr style='background-color:#F5F5F5;'>
        <td>Address</td><td>$user->bill_addr</td>
        </tr>
        
        <tr>
        <td>City</td><td>$user->bill_city</td>
        </tr>
        
        <tr style='background-color:#F5F5F5;'>
        <td>State</td><td>$state</td>
        </tr>
        
        <tr>
        <td>Zip</td><td>$user->bill_zip</td>
        </tr>
        
        <tr style='background-color:#F5F5F5;'>
        <td>Applied Progarm</td><td>$course_name</td>
        </tr>
        
        <tr>
        <td>Program fee</td><td>$$cost</td>
        </tr>";

        if (property_exists($user, 'payment_amount')) {
            date_default_timezone_set("America/New_York");
            $date = date('m-d-Y h:i:s', time());

            $list.="<tr style='background-color:#F5F5F5;'>
            <td>Payment status: </td><td>Paid by card: $$user->payment_amount</td>
            </tr>";

            $list.="<tr style='background-color:#F5F5F5;'>";
            $list.="<td>Order Date:</td><td>$date</td>";
            $list.="</tr>";
        } // end if $payment_amount != null

        $list.="<tr>";
        $list.="<td>Class info</td><td>$venue</td>";
        $list.="</tr>";

        if ($catid == 2) {
            $list.="<tr style=''>";
            $list.="<td colspan='2'>Dress is casual with close toe shoes. Bring a photo ID. Arrive 10 minutes early.</td>";
            $list.="</tr>";
        }

        $list.="</tbody>
        </table>
        </div>";
        $list.="<p>If you need assistance please contact us by email <a href='mailto:help@medical2.com'>help@medical2.com</a> or call us 877-741-1996</p>";
        $list.="</body></html>";
        $subject = "Medical2 - Group Registration";
        $recipient = $user->bill_email;
        $payment_amount = $user->payment_amount;
        $this->send_signup_confirmation_email($subject, $list, $recipient, $payment_amount);
    }

    function get_user_details($id) {
        $query = "select * from mdl_user where id=$id";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $user = new stdClass();
            foreach ($row as $key => $value) {
                $user->$key = $value;
            } // end if $row['firstname'] != '' && $row['lastname'] != ''
        } // end while
        return $user;
    }

    function verify_used_code($code) {
        $query = "select * from mdl_code "
                . "where code='$code' "
                . "and used=1";
        $num = $this->db->numrows($query);
        return $num;
    }

    function is_code_exists($courseid, $code, $used = false) {
        if ($used) {
            $status = $this->verify_used_code($code);
        } // end if $used
        else {
            $now = time();
            $query = "select * from mdl_code "
                    . "where code='$code' "
                    . "and used=0 and $now between date1 and date2";
            $num = $this->db->numrows($query);
            //echo "Num: " . $num . "<br>";
            if ($num > 0) {
                $result = $this->db->query($query);
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $id = $row['id'];
                } // end while
                $codecourseid = $this->get_code_courseid($id);
                if ($codecourseid == 0) {
                    $status = 1;
                } // end if $codecourseid==0
                else {
                    $query = "select * from mdl_code2course "
                            . "where courseid=$courseid and codeid=$id";
                    //echo "Query: " . $query . "<br>";
                    $coursenum = $this->db->numrows($query);
                    $status = ($coursenum > 0) ? 1 : 0;
                }
            } // end if $num>0
            else {
                $status = 0;
            }
        } // end else
        return $status;
    }

    function get_code_details($code) {
        $query = "select * from mdl_code where code='$code'";
        //echo "Query: ".$query."<br>";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $item = new stdClass();
            foreach ($row as $key => $value) {
                $item->$key = $value;
            } // end foreach
        } // end while
        return $item;
    }

    function get_code_courseid($codeid) {
        $query = "select * from mdl_code2course where codeid=$codeid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $courseid = $row['courseid'];
        }
        return $courseid;
    }

    function get_course_fee_block($oldprice, $user) {
        $code = $user->promo_code;
        $courseid = $user->courseid;
        $status = $this->is_code_exists($courseid, $code, TRUE);
        // You need to find workaround how to fix late fee issue
        /*
         * 
          if ($status > 0) {
          $codedata = $this->get_code_details($code);
          if ($codedata->type == 'amount') {
          $newprice = (int) $oldprice - (int) $codedata->amount;
          $list = round($newprice) . " Discount applied: $$codedata->amount off";
          } // end if
          else {
          $newprice = (int) $oldprice - (int) ($oldprice * $codedata->amount) / 100;
          $list = round($newprice) . " Discount applied: $codedata->amount%";
          } // end else
          } // end if $status>0
          else {
          $list = $oldprice;
          } // end else
         * 
         */
        $list = $oldprice;
        return $list;
    }

    function get_former_user_payments($userid) {

        // 1. Check authorize payments
        $query = "select * from mdl_card_payments "
                . "where userid=$userid and refunded=0";
        $num1 = $this->db->numrows($query);

        // 2. Check braintree payments
        $query = "select * from mdl_card_payments2 "
                . "where userid=$userid and refunded=0";
        $num2 = $this->db->numrows($query);

        // 3. Check cash payments
        $query = "select * from mdl_partial_payments "
                . "where userid=$userid";
        $num3 = $this->db->numrows($query);

        if ($num1 > 1 || $num2 > 1 || $num3 > 1) {
            return true;
        } // end if $num1>1 || $num2>1 || $num3>1
        else {
            return false;
        } // end else
    }

    function get_receipt_preface($catid, $userid) {
        $list = "";
        if ($catid < 5) {
            $list.="Registration";
        } // end if $catid<5
        else {
            // We change receipt header only for college programs
            $former_payments = $this->get_former_user_payments($userid);
            if ($former_payments) {
                $list.="Account payment";
            } // end if $former_payments
            else {
                $list.="Registration";
            } // end else
        } // end else
        return $list;
    }

    function get_account_confirmation_message2($user, $printed_data = null, $paypal = false) {
        $list = "";
        $course_name = $this->get_course_name($user);
        $class_info = $this->get_classs_info($user);
        $course_cost = $this->get_course_cost($user);
        /* ******************************************************************
         *  Apply workaround if slot is not selected - use course cost
         * ****************************************************************** */
        if ($user->slotid > 0) {
            $ws_cost = $this->get_workshop_cost($user->slotid);
        } // end if $user->slotid>0
        else {
            $ws_cost = 0;
        } // end else
        $init_cost = ($ws_cost > 0) ? $ws_cost : $course_cost;
        $cost = $this->get_course_fee_block($init_cost, $user);
        $catid = $this->get_course_category($user);
        $receipt_preface = $this->get_receipt_preface($catid, $user->userid);
        $p = new Payment();
        $state = $p->get_state_name_by_id($user->state);
        $userdata = $this->get_user_details($user->userid);
        $list.= "<!DOCTYPE HTML><html><head><title>Account Confirmation</title>";
        $list.="</head>";
        $list.="<body><br/>";
        $list.="<div>            
        <table style='table-layout: fixed;' width='375' align='center'>
        <thead>";

        if ($printed_data == null) {
            if ($catid == 5) {
                $list.="<tr>";
                $list.="<th colspan='2' align='left'><img src='https://medical2.com/assets/logo/receipt_college.png' width='360' height='130'></th>";
                $list.="</tr>";
            } // end if
            else {
                $list.="<tr>";
                $list.="<th colspan='2' align='left'><img src='https://medical2.com/assets/logo/receipt_agency.png' width='360' height='120'></th>";
                $list.="</tr>";
            } // end else
        } // end if $printed_data == NULL

        $list.="</thead>
        <tbody>

        <tr style='font-weight:bold;text-align:left;background-color:#F5F5F5;'>
        <td colspan='2' align='left'>$receipt_preface</td>
        </tr>

        <tr style=''>
        <td align='left'>First name</td><td align='left'>$userdata->firstname</td>
        </tr>
        
        <tr>
        <td align='left'>Last name</td><td align='left'>$userdata->lastname</td>
        </tr>
        
        <tr>
        <td align='left'>Username</td><td align='left'>$userdata->email</td>
        </tr>
        
        <tr>
        <td align='left'>Password</td><td align='left'>$userdata->purepwd</td>
        </tr>
        
        <tr>
        <td align='left'>Phone</td><td align='left'>$userdata->phone1</td>
        </tr>
        
        <tr>
        <td align='left'>Address</td><td align='left'>$userdata->address</td>
        </tr>
        
        <tr>
        <td align='left'>City</td><td align='left'>$userdata->city</td>
        </tr>
        
        <tr>
        <td align='left'>State</td><td align='left'>$userdata->state</td>
        </tr>
        
        <tr>
        <td align='left'>Zip</td><td align='left'>$userdata->zip</td>
        </tr>
        
        </table>
        
        <br><table style='table-layout: fixed;' width='375' align='center'>
        
        <tr style='font-weight:bold;text-align:left;background-color:#F5F5F5;'>
        <td colspan='2' align='left'>Billing<br></td>
        </tr>";

        $list.="<tr>";
        if ($user->billing_name != '') {
            $list.="<td align='left'>Billing Name</td><td align='left'>$user->billing_name</td>";
        } // end if
        else {
            $list.="<td align='left'>Billing Name</td><td align='left'>$userdata->firstname $userdata->lastname</td>";
        } // end else
        $list.="</tr>";

        if ($user->receipt_email != 'n/a' && $user->receipt_email != '') {
            $list.="<tr style=''>
            <td align='left'>Email</td><td align='left'>$user->receipt_email</td>
            </tr>";
        } // end if
        else {
            $list.="<tr style=''>
            <td align='left'>Email</td><td align='left'>$userdata->email</td>
            </tr>";
        }

        $list.="<tr style=''>
            <td align='left'>Auth Code</td><td align='left'>$user->auth_code</td>
            </tr>";

        $list.="<tr>
        <td align='left'>Phone</td><td align='left'>$user->phone</td>
        </tr>
       
        <tr style=''>
        <td align='left'>Address</td><td align='left'>$userdata->address</td>
        </tr>
        <tr >
        <td align='left'>City</td><td align='left'>$user->city</td>
        </tr>        
        <tr style=''>
        <td align='left'>State</td><td align='left'>$state</td>
        </tr>
        <tr >
        <td align='left'>Zip</td><td align='left'>$user->zip</td>
        </tr>
        
        <tr style=''>
        <td align='left'>Program</td><td align='left'>$course_name</td>
        </tr>
        
        <tr >
        <td align='left'>Program Fee</td><td align='left'>$$cost</td>
        </tr>";

        if (property_exists($user, 'payment_amount')) {
            date_default_timezone_set("America/New_York");
            $date = date('m-d-Y h:i:s', time());
            if ($paypal) {
                $list.="<tr style=''>
                <td align='left'>Payment: </td><td align='left'>Paid by PayPal: $$user->payment_amount</td>
                </tr>";
            } // end if $paypal
            else {
                $list.="<tr style=''>
                <td align='left'>Payment: </td><td align='left'>Paid by card: $$user->payment_amount</td>
                </tr>";
            } // end else

            $list.="<tr style=''>";
            $list.="<td align='left'>Date Order:</td><td align='left'>$date</td>";
            $list.="</tr>";
        } // end if $payment_amount != null

        $list.="<tr style=''>
        <td align='left'>Class info</td><td align='left'>$class_info</td>
        </tr>";

        if ($catid == 2) {
            $list.="<tr style=''>";
            $list.="<td colspan='2' align='left'>Dress is casual with close toe shoes. Bring a photo ID. Arrive 10 minutes early.</td>";
            $list.="</tr>";
        } // end if $catid == 2

        $list.="</tbody>
        </table>
        </div>";
        $list.="<p style='text-align:center;'>You can print your registration data <a href='https://medical2.com/lms/custom/invoices/registrations/$userdata->email.pdf' target='_blank'>here.</a></p>";
        $list.="<p style='text-align:center;'>If you need assistance please contact us by email <a href='mailto:help@medical2.com'>help@medical2.com</a> or call us 877-741-1996</p>";
        $list.="</body></html>";

        return $list;
    }

    function get_account_confirmation_message($user, $printed_data = null) {
        $list = "";
        $course_name = $this->get_course_name($user);
        $class_info = $this->get_classs_info($user);
        $course_cost = $this->get_course_cost($user);
        /* ******************************************************************
         *  Apply workaround if slot is not selected - use course cost
         * ****************************************************************** */
        if ($user->slotid > 0) {
            $ws_cost = $this->get_workshop_cost($user->slotid);
        } // end if $user->slotid>0
        else {
            $ws_cost = 0;
        } // end else
        $cost = ($ws_cost > 0) ? $ws_cost : $course_cost;
        $catid = $this->get_course_category($user);
        $p = new Payment();
        $state = $p->get_state_name_by_id($user->state);
        $list.= "<!DOCTYPE HTML><html><head><title>Account Confirmation</title>";
        $list.="</head>";
        $list.="<body><br/><br/><br/><br/>";
        $list.="<div class='datagrid'>            
        <table style='table-layout: fixed;' width='360'>
        <thead>";

        if ($printed_data == NULL) {
            if ($catid == 5) {
                $list.="<tr>";
                $list.="<th colspan='2' align='left'><img src='http://medical2.com/assets/logo/receipt_college.png' width='360' height='130'></th>";
                $list.="</tr>";
            } // end if
            else {
                $list.="<tr>";
                $list.="<th colspan='2' align='left'><img src='http://medical2.com/assets/logo/receipt_agency.png' width='360' height='120'></th>";
                $list.="</tr>";
            } // end else
        } // end if $printed_data == NULL


        $list.="</thead>
        <tbody>
        <tr style='background-color:#F5F5F5;'>
        <td>First name</td><td>$user->signup_first</td>
        </tr>
        <tr>
        <td>Last name</td><td>$user->signup_last</td>
        </tr>
        <tr style='background-color:#F5F5F5;'>
        <td>Email</td><td>$user->email</td>
        </tr>
        <tr>
        <td>Phone</td><td>$user->phone</td>
        </tr>
        <tr style='background-color:#F5F5F5;'>
        <td>Username</td><td>$user->email</td>
        </tr>
        <tr >
        <td>Password</td><td>$user->pwd</td>
        </tr>
        <tr style='background-color:#F5F5F5;'>
        <td>Address</td><td>$user->addr</td>
        </tr>
        <tr >
        <td>City</td><td>$user->city</td>
        </tr>        
        <tr style='background-color:#F5F5F5;'>
        <td>State</td><td>$state</td>
        </tr>
        <tr >
        <td>Zip</td><td>$user->zip</td>
        </tr>
        <tr style='background-color:#F5F5F5;'>
        <td>Applied Progarm</td><td>$course_name</td>
        </tr>
        <tr >
        <td>Program fee</td><td>$$cost</td>
        </tr>";

        if (property_exists($user, 'payment_amount')) {
            date_default_timezone_set("America/New_York");
            $date = date('m-d-Y h:i:s', time());

            $list.="<tr style='background-color:#F5F5F5;'>
            <td>Payment status: </td><td>Paid by card: $$user->payment_amount</td>
            </tr>";

            $list.="<tr style='background-color:#F5F5F5;'>";
            $list.="<td>Order Date:</td><td>$date</td>";
            $list.="</tr>";
        } // end if $payment_amount != null

        $list.="<tr style='background-color:#F5F5F5;'>
        <td>Class info</td><td>$class_info</td>
        </tr>";

        if ($catid == 2) {
            $list.="<tr style=''>";
            $list.="<td colspan='2'>Dress is casual with close toe shoes. Bring a photo ID. Arrive 10 minutes early.</td>";
            $list.="</tr>";
        }

        $list.="</tbody>
        </table>
        </div>";
        $list.="<p>If you need assistance please contact us by email <a href='mailto:help@medical2.com'>help@medical2.com</a> or call us 877-741-1996</p>";
        $list.="</body></html>";
        return $list;
    }

    function create_registration_data_details($user, $paypal) {
        $dompdf = new Dompdf();
        $message = $this->get_account_confirmation_message2($user, true, $paypal);
        $dompdf->loadHtml($message);

        $dompdf->setPaper('A4', 'portrait');

        $dompdf->render();
        $output = $dompdf->output();

        $file_path = $this->registration_path . "/$user->email.pdf";
        file_put_contents($file_path, $output);
    }

    function send_account_confirmation_message($user, $paypal = false) {
        $subject = "Medical2 - registration confirmation";
        $message = $this->get_account_confirmation_message2($user, null, $paypal);
        $payment_amount = (property_exists($user, 'payment_amount') == true) ? $user->payment_amount : null;
        if ($user->receipt_email != 'n/a' && $user->receipt_email != '') {
            $recipient = $user->receipt_email;
        } // end if
        else {
            $recipient = $user->email;
        } // end else
        //$recipient = ($user->receipt_email != 'n/a') ? $user->receipt_email : $user->email;
        $this->send_signup_confirmation_email($subject, $message, $recipient, $payment_amount);
        $this->create_registration_data_details($user, $paypal);
    }

    function send_signup_confirmation_email($subject, $message, $recipient, $payment_amount) {

        /* We send confirmation email only if payment is received */

        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->Host = $this->mail_smtp_host;
        $mail->SMTPAuth = true;
        $mail->Username = $this->mail_smtp_user;
        $mail->Password = $this->mail_smtp_pwd;
        $mail->SMTPSecure = 'tls';
        $mail->Port = $this->mail_smtp_port;

        $mail->setFrom($this->mail_smtp_user, 'Medical2');
        if ($payment_amount != null) {
            $mail->addAddress($recipient);
            $mail->AddCC('info@medical2.com');
            $mail->AddCC('sirromas@gmail.com');
            $mail->AddCC('help@medical2.com');
            $mail->addReplyTo($this->mail_smtp_user, 'Medical2');
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $message;
            if (!$mail->send()) {
                //echo 'Message could not be sent.';
                //echo 'Mailer Error: ' . $mail->ErrorInfo;
            } // end if !$mail->send()        
            else {
                //echo 'Message has been sent to ' . $recipient;
            }
        } // end if $payment_amount != null
    }

    function get_payment_confirmation_message($payment, $group = null, $free = null) {
        $list = "";
        $list.="<html><body>";
        $list.="<br/><p>Dear $payment->card_holder!</p>";
        if ($group == null) {
            if ($free == null) {
                $list.="<p>Payment of $$payment->sum have been received. Thank you. Your account is active now.</p>";
            } // end if $free == null
            else {
                $list.="<p>You got free access to the system. Your account is active now.</p>";
            }
        } // end if $group==null
        else {
            if ($free == null) {
                $list.="<p>Payment of $$payment->sum have been received. Thank you. All your group accounts are active now.</p>";
            } // end if $free == null
            else {
                $list.="<p>Your group members got free access to the system. All your group accounts are active now.</p>";
            } // end else
        } // end else
        $list.="<p>If you need help, please contact us via email $this->mail_smtp_user</p>";
        $list.="<p>Best regards,</p>";
        $list.="<p>Support team.</p>";
        $list.="</body></html>";
        return $list;
    }

    function get_renew_fee($courseid) {
        $renew = new Renew();
        $amount = $renew->get_renew_amount($courseid);
        return $amount;
    }

    function get_user_data($user) {
        $query = "select * from mdl_user where id=$user->userid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $user = new stdClass();
            foreach ($row as $key => $value) {
                $user->$key = $value;
            }
        }
        return $user;
    }

    function get_renew_certificate_message($user) {
        $list = "";
        $now = date('m-d-Y H:i:s', time());
        $userdata = $this->get_user_data($user);
        $list.= "<!DOCTYPE HTML><html><head><title>Certificate Renew Confirmation</title>";
        $list.="</head>";
        $list.="<body><br/><br/><br/><br/>";
        $list.="<div class='datagrid'>            
        <table style='table-layout: fixed;' width='360'>
        <thead>";

        $list.="<tr>";
        $list.="<th colspan='2' align='left'><img src='http://medical2.com/assets/logo/receipt_agency.png' width='360' height='120'></th>";
        $list.="</tr>";

        $list.="</thead>
        <tbody>
        
        <tr style='background-color:#F5F5F5;'>
        <td>First name</td><td>$userdata->firstname</td>
        </tr>
        
        <tr>
        <td>Last name</td><td>$userdata->lastname</td>
        </tr>
        
        <tr style='background-color:#F5F5F5;'>
        <td>Email</td><td>$user->email</td>
        </tr>
        
        <tr>
        <td>Phone</td><td>$user->phone</td>
        </tr>
        
        <tr style='background-color:#F5F5F5;'>
        <td>Applied Progarm</td><td>Certification renewal</td>
        </tr>";

        if ($user->auth_code != '') {
            $list.="<tr>
        <td>Auth Code</td><td>$user->auth_code</td>
        </tr>";
        }

        $list.="<tr>
        <td>Amount paid</td><td>$$user->sum</td>
        </tr> 
        
        <tr>
        <td>Order date</td><td>$now</td>
        </tr>
        
        <tr>
        <td colspan='2' align='left'><span style='color:red;'>Note</span>: You can always login to your account at medical2.com to print or renew your certificate under Certificate tab. Your username is your email address. For password click the Recover Password link at login page. If you need further assistance. Please email at help@medical2.com.  
        </td>
        </tr> 
        
        <tr>
        <td colspan='2' align='left'>&nbsp;</td>
        </tr> 
        
        <tr>
        <td colspan='2' align='left'>This certification have been renewed</td>
        </tr> 
        
        </table>";
        return $list;
    }

    function send_group_renewal_message() {
        
    }

    function send_payment_confirmation_message($payment, $group = null, $free = null, $paypal = FALSE) {
        //$renew_fee = $this->get_renew_fee($payment->courseid);
        $recipient = $payment->bill_email;
        if ($payment->renew == null || $payment->renew == 0) {
            $this->send_account_confirmation_message($payment, $paypal); // send user info to info@medical2.com        
        } // end if $payment->sum!=$renew_fee
        else {
            $subject = "Medical2 - Certificate Renew Payment";
            $message = $this->get_renew_certificate_message($payment);
            $this->send_email($subject, $message, $recipient);

            // Send copy of message to info@medical2.com and sirromas@gmail.com
            $recipient = 'info@medical2.com';
            $this->send_email($subject, $message, $recipient);

            $recipient = 'sirromas@gmail.com';
            $this->send_email($subject, $message, $recipient);

            $recipient = 'help@medical2.com';
            $this->send_email($subject, $message, $recipient);
        } // end else
    }

    function get_payment_group_confirmation_message($user) {
        $list = "";
        $list.="<html><body>";
        $list.="<br/><p>Dear $user->firstname $user->lastname!</p>";
        $list.="<p>Payment for your group membership have been received. Thank you. Your account is active now.</p>";
        $list.="<p>If you need help, please contact us via email $this->mail_smtp_user</p>";
        $list.="<p>Best regards,</p>";
        $list.="<p>Support team.</p>";
        $list.="</body></html>";
        return $list;
    }

    function send_group_payment_confirmation_message($user) {
        $subject = "Medical2 - payment confirmation";
        $message = $this->get_payment_group_confirmation_message($user);
        $recipient = $user->username;
        $this->send_email($subject, $message, $recipient);
    }

    function send_group_reply_message($reply, $recipient) {
        $subject = "Medical2 - Private Group Request";
        $this->send_email($subject, $reply, $recipient);
    }

    function get_invoice_message($user, $gowner = null) {
        $list = "";
        $list.="<html><body>";
        $list.="<br/><p>Dear $user->first_name $user->last_name!</p>";
        $list.="<p>Thank you for signup.</p>";
        $list.="<p>Please find out invoice attached to make a payment.</p>";
        $list.="<p>If you need help, please contact us via email $this->mail_smtp_user</p>";
        $list.="<p>Best regards,</p>";
        $list.="<p>Support team.</p>";
        $list.="</body></html>";
        return $list;
    }

    function send_invoice($user, $gowner = null) {
        //print_r($user);
        $subject = "Medical2 - invoice";
        $message = $this->get_invoice_message($user, $gowner);
        $recipient = $user->email;
        $this->send_email($subject, $message, $recipient, $user->invoice);
    }

    function send_certificate($user) {
        $subject = "Medical2 - Certificate";
        $list = "";
        $list.="<html><body>";
        $list.="<br/><p>Dear $user->firstname $user->lastname!</p>";
        $list.="<p>Congratulations! You successfully passed selected program!</p>";
        $list.="<p>Please find out certificate attached.</p>";
        $list.="<p><span style='color:red;'>Note:</span> You can always login to your account at "
                . "<a href='https://medical2.com/' target='_blank'>medical2.com</a> to print or "
                . "renew your certificate under Certificate tab. Your username is your email "
                . "address. For password click the Recover Password link at login page. "
                . "If you need further assistance. Please email at "
                . "<a href='mailto:help@medical2.com'>help@medical2.com</a>.  </p>";
        $list.="<p>If you need help, please contact us via email "
                . "<a htef='mailto:$this->mail_smtp_user'>$this->mail_smtp_user</a></p>";
        $list.="<p>Best regards,</p>";
        $list.="<p>Support team.</p>";
        $list.="</body></html>";
        $this->send_email($subject, $list, $user->email, $user->path, 1);
    }

    function send_email($subject, $message, $recipient, $attachment = null, $certificate = null) {

        $mail = new PHPMailer;
        //$recipient = 'sirromas@gmail.com'; // temp workaround
        //$mail->SMTPDebug = 3;                                

        $mail->isSMTP();
        $mail->Host = $this->mail_smtp_host;
        $mail->SMTPAuth = true;
        $mail->Username = $this->mail_smtp_user;
        $mail->Password = $this->mail_smtp_pwd;
        $mail->SMTPSecure = 'tls';
        $mail->Port = $this->mail_smtp_port;

        $mail->setFrom($this->mail_smtp_user, 'Medical2');
        $mail->addAddress($recipient);
        $mail->addReplyTo($this->mail_smtp_user, 'Medical2');

        if ($attachment != null) {
            $invoice = $this->invoice_path . "/$attachment.pdf";
            $mail->addAttachment($invoice, "invoice.pdf");
        } // end if $attachment != null

        if ($attachment != null && $certificate != null) {
            $mail->addAttachment($attachment, "certificate.pdf");
        } // end if $attachment != null && $certificate!=null
        $mail->isHTML(true);

        $mail->Subject = $subject;
        $mail->Body = $message;

        if (!$mail->send()) {
            //echo 'Message could not be sent.';
            //echo 'Mailer Error: ' . $mail->ErrorInfo;
        } // end if !$mail->send()        
        else {
            //echo 'Message has been sent to ' . $recipient;
        }
    }

    function send_password_recovery_email($user, $supportuser, $subject, $message) {

        $mail = new PHPMailer;
        //$user->email = 'sirromas@gmail.com'; // temp workaround
        //$mail->SMTPDebug = 3;                                

        $mail->isSMTP();
        $mail->Host = $this->mail_smtp_host;
        $mail->SMTPAuth = true;
        $mail->Username = $this->mail_smtp_user;
        $mail->Password = $this->mail_smtp_pwd;
        $mail->SMTPSecure = 'tls';
        $mail->Port = $this->mail_smtp_port;

        $mail->setFrom($this->mail_smtp_user, 'Medical2');
        $mail->addAddress($user->email);
        $mail->addReplyTo($this->mail_smtp_user, 'Medical2');

        $mail->isHTML(true);

        $mail->Subject = $subject;
        $mail->Body = $message;

        if (!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } // end if !$mail->send()        
        else {
            //echo 'Message has been sent to ' . $user->email;
        }
    }

    function send_any_invoice($client, $email, $invoice_file_name) {
        $mail = new PHPMailer;
        $recipient = $email;
        //$recipient = 'sirromas@gmail.com'; // temp workaround

        $message = "";
        $message.="<html>";
        $message.="<body>";
        $message.="<p align='center'>Dear $client!</p>";
        $message.="<p align='center'>Invoice is in attachment.</p>";
        $message.="<p>If you need assistance please contact us by email <a href='mailto:help@medical2.com'>help@medical2.com</a> or by phone 877-741-1996</p>";
        $message.="<p>Best regards,</p>";
        $message.="<p>Medical2 team</p>";
        $message.="</body></html>";

        $mail->isSMTP();
        $mail->Host = $this->mail_smtp_host;
        $mail->SMTPAuth = true;
        $mail->Username = $this->mail_smtp_user;
        $mail->Password = $this->mail_smtp_pwd;
        $mail->SMTPSecure = 'tls';
        $mail->Port = $this->mail_smtp_port;

        $mail->setFrom($this->mail_smtp_user, 'Medical2');
        $mail->addAddress($recipient);
        $mail->addReplyTo($this->mail_smtp_user, 'Medical2');

        $mail->addAttachment($invoice_file_name, "invoice.pdf");

        $mail->isHTML(true);

        $mail->Subject = 'Medical2 - Invoice';
        $mail->Body = $message;

        if (!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } // end if !$mail->send()
        else {
            //echo 'Message has been sent to ' . $recipient;
        }
    }

    function send_contact_request($message, $recipient) {

        $mail = new PHPMailer;
        $addressA = 'info@medical2.com';
        $addressB = 'help@medical2.com';
        $addressC = 'sirromas@gmail.com';

        $mail->isSMTP();
        $mail->Host = $this->mail_smtp_host;
        $mail->SMTPAuth = true;
        $mail->Username = $this->mail_smtp_user;
        $mail->Password = $this->mail_smtp_pwd;
        $mail->SMTPSecure = 'tls';
        $mail->Port = $this->mail_smtp_port;

        $mail->setFrom($this->mail_smtp_user, 'Medical2');
        $mail->addAddress($addressA);
        $mail->addAddress($addressB);
        $mail->addAddress($addressC);
        //$mail->addReplyTo($this->mail_smtp_user, 'Medical2');
        $mail->addReplyTo($recipient, $name = '');


        $mail->isHTML(true);
        $mail->Subject = 'Medical2 - Contact Page Request';
        $mail->Body = $message;

        if (!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } // end if !$mail->send()
        else {
            //echo 'Message has been sent to ' . $recipient;
        }
    }

    function send_test_message($subject, $message, $recipient = null) {
        $mail = new PHPMailer;
        $addressA = 'info@medical2.com';
        $addressB = 'help@medical2.com';
        $addressC = 'sirromas@gmail.com';

        $mail->isSMTP();
        $mail->Host = $this->mail_smtp_host;
        $mail->SMTPAuth = true;
        $mail->Username = $this->mail_smtp_user;
        $mail->Password = $this->mail_smtp_pwd;
        $mail->SMTPSecure = 'tls';
        $mail->Port = $this->mail_smtp_port;

        $mail->setFrom($this->mail_smtp_user, 'Medical2');
        //$mail->addAddress($addressA);
        //$mail->addAddress($addressB);
        $mail->addAddress($addressC);

        if ($recipient != null) {
            $mail->addAddress($recipient);
        }
        $mail->addReplyTo($this->mail_smtp_user, 'Medical2');

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;

        if (!$mail->send()) {
            //echo 'Message could not be sent.';
            //echo 'Mailer Error: ' . $mail->ErrorInfo;
            return false;
        } // end if !$mail->send()
        else {
            echo 'Message have been sent to ' . $addressC;
            return true;
        }
    }

    function send_common_message($subject, $message, $recipient = null) {
        $mail = new PHPMailer;
        $addressA = 'info@medical2.com';
        $addressB = 'help@medical2.com';
        $addressC = 'sirromas@gmail.com';

        $mail->isSMTP();
        $mail->Host = $this->mail_smtp_host;
        $mail->SMTPAuth = true;
        $mail->Username = $this->mail_smtp_user;
        $mail->Password = $this->mail_smtp_pwd;
        $mail->SMTPSecure = 'tls';
        $mail->Port = $this->mail_smtp_port;

        $mail->setFrom($this->mail_smtp_user, 'Medical2');
        $mail->addAddress($addressA);
        $mail->addAddress($addressB);
        $mail->addAddress($addressC);

        if ($recipient != null) {
            $mail->addAddress($recipient);
        }
        $mail->addReplyTo($this->mail_smtp_user, 'Medical2');

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;

        if (!$mail->send()) {
            //echo 'Message could not be sent.';
            //echo 'Mailer Error: ' . $mail->ErrorInfo;
            return false;
        } // end if !$mail->send()
        else {
            //echo 'Message has been sent to ' . $recipient;
            return true;
        }
    }

    function send_meeting_invitation($message, $recipient) {
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->Host = $this->mail_smtp_host;
        $mail->SMTPAuth = true;
        $mail->Username = $this->mail_smtp_user;
        $mail->Password = $this->mail_smtp_pwd;
        $mail->SMTPSecure = 'tls';
        $mail->Port = $this->mail_smtp_port;

        $mail->setFrom($this->mail_smtp_user, 'Medical2');
        $mail->addAddress($recipient);

        $mail->addReplyTo($this->mail_smtp_user, 'Medical2');
        $mail->isHTML(true);
        $mail->Subject = 'Medical2 - Meeting Invitation';
        $mail->Body = $message;
        if (!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
            return false;
        } // end if !$mail->send()
        else {
            //echo 'Message has been sent to ' . $recipient;
            return true;
        } // end else 
    }

    function send_workshop_notification($recipients, $message) {
        $mail = new PHPMailer();

        $address = 'sirromas@gmail.com';
        $mail->isSMTP();
        $mail->Host = $this->mail_smtp_host;
        $mail->SMTPAuth = true;
        $mail->Username = $this->mail_smtp_user;
        $mail->Password = $this->mail_smtp_pwd;
        $mail->SMTPSecure = 'tls';
        $mail->Port = $this->mail_smtp_port;
        $mail->setFrom($this->mail_smtp_user, 'Medical2');

        foreach ($recipients as $recipient) {
            $mail->addAddress($recipient);
        }


        $mail->AddAddress($address); // copy to me to make sure email is sent
        $mail->addReplyTo($this->mail_smtp_user, 'Medical2');

        $mail->isHTML(true);
        $mail->Subject = 'Medical2 - Workshop Update';
        $mail->Body = $message;

        if (!$mail->send()) {
            //echo 'Message could not be sent.';
            //echo 'Mailer Error: ' . $mail->ErrorInfo;
            //die();
            return false;
        } // end if !$mail->send()
        else {
            //echo 'Message has been sent to ' . $address;
            //die();
            return true;
        }
    }

    function send_survey_data($message) {

        $mail = new PHPMailer();

        $addressA = 'info@medical2.com';
        $addressB = 'help@medical2.com';
        $addressC = 'sirromas@gmail.com';

        $mail->isSMTP();
        $mail->Host = $this->mail_smtp_host;
        $mail->SMTPAuth = true;
        $mail->Username = $this->mail_smtp_user;
        $mail->Password = $this->mail_smtp_pwd;
        $mail->SMTPSecure = 'tls';
        $mail->Port = $this->mail_smtp_port;
        $mail->setFrom($this->mail_smtp_user, 'Medical2');
        $mail->AddAddress($addressA);
        $mail->AddAddress($addressB);
        $mail->AddAddress($addressC);

        $mail->addReplyTo($this->mail_smtp_user, 'Medical2');
        $mail->isHTML(true);
        $mail->Subject = 'Medical2 - Workshop Survey Results';
        $mail->Body = $message;
        if (!$mail->send()) {
            return false;
        } // end if !$mail->send()
        else {
            return true;
        }
    }

    function send_school_app_msg($message) {
        $mail = new PHPMailer();

        $addressA = 'info@medical2.com';
        $addressB = 'help@medical2.com';
        $addressC = 'sirromas@gmail.com';

        $mail->isSMTP();
        $mail->Host = $this->mail_smtp_host;
        $mail->SMTPAuth = true;
        $mail->Username = $this->mail_smtp_user;
        $mail->Password = $this->mail_smtp_pwd;
        $mail->SMTPSecure = 'tls';
        $mail->Port = $this->mail_smtp_port;
        $mail->setFrom($this->mail_smtp_user, 'Medical2');
        $mail->AddAddress($addressA);
        $mail->AddAddress($addressB);
        $mail->AddAddress($addressC);

        $mail->addReplyTo($this->mail_smtp_user, 'Medical2');
        $mail->isHTML(true);
        $mail->Subject = 'Medical2 - School Application';
        $mail->Body = $message;
        if (!$mail->send()) {
            return false;
        } // end if !$mail->send()
        else {
            return true;
        }
    }

    function send_updated_certificate($courseid, $userid) {
        $mail = new PHPMailer();
        $user = new stdClass();
        $user->userid = $userid;
        $userdata = $this->get_user_data($user);

        $message = "";

        $message.="<html>";
        $message.="<body>";
        $message.="<p>Dear $userdata->firstname $userdata->lastname,</p>";
        $message.="<p>Your certificate is now renewed, you can download and print your certificate using this <a href='http://" . $_SERVER['SERVER_NAME'] . "/lms/custom/certificates/$userid/$courseid/certificate.pdf' target='_blank'>link</a>.</p>";
        $message.="<p>Note: You have the option to log in to your account to download and print your certificate "
                . "if the link above does not work. Your email address is your “login user name” and if you don’t "
                . "remember your password please use the recover password link located on the  log in page to recover "
                . "your password.</p>";
        $message.="Please do not reply to this email as it is auto generated and replies are not monitored.";
        $message.="<br>";
        $message.="<p>Regards, <br> Medical2 Support.</p>";
        $message.="</body>";
        $message.="</html>";

        $addressA = $userdata->email;
        $addressC = 'sirromas@gmail.com';

        $mail->isSMTP();
        $mail->Host = $this->mail_smtp_host;
        $mail->SMTPAuth = true;
        $mail->Username = $this->mail_smtp_user;
        $mail->Password = $this->mail_smtp_pwd;
        $mail->SMTPSecure = 'tls';
        $mail->Port = $this->mail_smtp_port;
        $mail->setFrom($this->mail_smtp_user, 'Medical2');

        $mail->AddAddress($addressA);
        $mail->AddAddress($addressC);

        $mail->addReplyTo($this->mail_smtp_user, 'Medical2');
        $mail->isHTML(true);
        $mail->Subject = 'Medical2 - Certificate';
        $mail->Body = $message;
        if (!$mail->send()) {
            return false;
        } // end if !$mail->send()
        else {
            return true;
        }
    }

    function send_workshop_students_list($message) {
        $mail = new PHPMailer();

        $addressA = 'a1b1c777@gmail.com ';
        $addressC = 'sirromas@gmail.com';

        $mail->isSMTP();
        $mail->Host = $this->mail_smtp_host;
        $mail->SMTPAuth = true;
        $mail->Username = $this->mail_smtp_user;
        $mail->Password = $this->mail_smtp_pwd;
        $mail->SMTPSecure = 'tls';
        $mail->Port = $this->mail_smtp_port;
        $mail->setFrom($this->mail_smtp_user, 'Medical2');

        $mail->AddAddress($addressA);
        $mail->AddAddress($addressC);

        $mail->addReplyTo($this->mail_smtp_user, 'Medical2');
        $mail->isHTML(true);
        $mail->Subject = 'Medical2 - Workshop students list';
        $mail->Body = $message;
        if (!$mail->send()) {
            return false;
        } // end if !$mail->send()
        else {
            return true;
        }
    }

    function send_schedule_bulk_email($item) {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $this->mail_smtp_host;
        $mail->SMTPAuth = true;
        $mail->Username = $this->mail_smtp_user;
        $mail->Password = $this->mail_smtp_pwd;
        $mail->SMTPSecure = 'tls';
        $mail->Port = $this->mail_smtp_port;
        $mail->setFrom($this->mail_smtp_user, 'Medical2');
        $mail->addReplyTo('help@medical2.com', 'Medical2');
        $mail->isHTML(true);
        $mail->Subject = $item->title;
        $mail->Body = $item->text;
        $email = $item->email;
        $addrA = 'sirromas@gmail.com';
        $addrB = 'info@medical2.com';
        $mail->AddAddress($email);
        $mail->addCC($addrA);
        //$mail->addCC($addrB);
        if (!$mail->send()) {
            return false;
        } // end if !$mail->send()
        else {
            return true;
        }
    }

    function get_group_users_details($users_arr) {
        $list = "";
        $students_num = count($users_arr);
        foreach ($users_arr as $userid) {
            $user = $this->get_user_details($userid);

            $list.="<tr>";
            $list.="<td>First name</td><td>$user->firstname</td>";
            $list.="</tr>";

            $list.="<tr>";
            $list.="<td>Last name</td><td>$user->lastname</td>";
            $list.="</tr>";

            $list.="<tr>";
            $list.="<td>Email</td><td>$user->email</td>";
            $list.="</tr>";

            $list.="<tr>";
            $list.="<td colspan='2'><br></td>";
            $list.="</tr>";
        }  // end foreach

        $list.="<tr style='background-color:#F5F5F5;'>
        <td>Total students</td><td>$students_num</td>
        </tr>";

        return $list;
    }

    function get_state_name($id) {
        $query = "select * from mdl_states where id=$id";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $state = $row['state'];
        }
        return $state;
    }

    function get_group_name($groupid) {
        $query = "select * from mdl_groups where id=$groupid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $name = $row['name'];
        }
        return $name;
    }

    function send_group_renewal_receipt($p, $ptype) {
        $list = "";
        $subject = "Medical2 - Group Renewal";
        $state = $this->get_state_name($p->billing_state);
        $users_arr = explode(',', $p->userslist);
        $groupname = $this->get_group_name($p->groupid);
        $group_users = $this->get_group_users_details($users_arr);
        $catid = $this->get_course_category($p);
        $list.= "<!DOCTYPE HTML><html><head><title>Payment Confirmation</title>";
        $list.="</head>";
        $list.="<body><br/><br/><br/><br/>";
        $list.="<div class='datagrid'>            
            <table style='table-layout: fixed;' width='360'>
            <thead>";

        if ($catid == 5) {
            $list.="<tr>";
            $list.="<th colspan='2' align='left'><img src='http://medical2.com/assets/logo/receipt_college.png' width='360' height='130'></th>";
            $list.="</tr>";
        } // end if
        else {
            $list.="<tr>";
            $list.="<th colspan='2' align='left'><img src='http://medical2.com/assets/logo/receipt_agency.png' width='360' height='120'></th>";
            $list.="</tr>";
        } // end else

        $list.="</thead>";

        $list.="<tbody>";

        $list.="<tr style='font-weight:bold;'>";
        $list.="<td>Group ID</td><td>$groupname</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td colspan='2'><br></td>";
        $list.="</tr>";

        $list.=$group_users;

        $list.="<tr style=''>
        <td colspan='2' style='font-weight:bold;'><br>Billing info</td>
        </tr>";

        $list.="<tr>";
        $list.="<td>Billing name:</td><td>$p->billing_name</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td>Email:</td><td>$p->billing_email</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td>Phone:</td><td>$p->billing_phone</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td>Address:</td><td>$p->billing_addr </td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td>City:</td><td>$p->billing_city</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td>State:</td><td>$state</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td>Zip:</td><td>$p->billing_zip</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td>Applied program:</td><td>Group certificate renewal</td>";
        $list.="</tr>";

        if ($ptype == 0) {
            $list.="<tr>";
            $list.="<td>Payment status:</td><td>Paid by card/paypal $$p->psum</td>";
            $list.="</tr>";
        } // end if $ptype==0
        else {
            $list.="<tr>";
            $list.="<td>Payment status:</td><td>Paid by cheque $$p->psum</td>";
            $list.="</tr>";
        } // end ele

        $list.="<tr>";
        $list.="<td>Order date:</td><td>" . date('m-d-Y h:i:s') . "</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td colspan='2'><br>If you need assistance please contact us by email help@medical2.com or call us 877-741-1996</td>";
        $list.="</tr>";

        $list.="</tbody>";

        $list.="</table>";


        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $this->mail_smtp_host;
        $mail->SMTPAuth = true;
        $mail->Username = $this->mail_smtp_user;
        $mail->Password = $this->mail_smtp_pwd;
        $mail->SMTPSecure = 'tls';
        $mail->Port = $this->mail_smtp_port;
        $mail->setFrom($this->mail_smtp_user, 'Medical2');
        $mail->addReplyTo($this->mail_smtp_user, 'Medical2');
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $list;
        $email = $p->billing_email;
        $addrA = 'sirromas@gmail.com';
        $addrB = 'info@medical2.com';
        $addrC = 'help@medical2.com';
        $mail->AddAddress($email);
        $mail->addCC($addrA);
        $mail->addCC($addrB);
        $mail->addCC($addrC);
        if (!$mail->send()) {
            return false;
        } // end if !$mail->send()
        else {
            return true;
        }
    }

    function send_career_survey_result($m) {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $this->mail_smtp_host;
        $mail->SMTPAuth = true;
        $mail->Username = $this->mail_smtp_user;
        $mail->Password = $this->mail_smtp_pwd;
        $mail->SMTPSecure = 'tls';
        $mail->Port = $this->mail_smtp_port;
        $mail->setFrom($this->mail_smtp_user, 'Medical2');
        $mail->addReplyTo($this->mail_smtp_user, 'Medical2');
        $mail->isHTML(true);
        $mail->Subject = 'Medical2 - Career College Survey Result';
        $mail->Body = $m;

        $addrA = 'sirromas@gmail.com';
        $addrB = 'info@medical2.com';
        $addrC = 'help@medical2.com';
        $mail->AddAddress($addrA);
        $mail->AddAddress($addrB);
        $mail->AddAddress($addrC);
        if (!$mail->send()) {
            return false;
        } // end if !$mail->send()
        else {
            return true;
        }
    }

    function send_braintree_failed_transaction_info($m) {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $this->mail_smtp_host;
        $mail->SMTPAuth = true;
        $mail->Username = $this->mail_smtp_user;
        $mail->Password = $this->mail_smtp_pwd;
        $mail->SMTPSecure = 'tls';
        $mail->Port = $this->mail_smtp_port;
        $mail->setFrom($this->mail_smtp_user, 'Medical2');
        $mail->addReplyTo($this->mail_smtp_user, 'Medical2');
        $mail->isHTML(true);
        $mail->Subject = 'Medical2 - Failed Transaction Info';
        $mail->Body = $m;

        $addrA = 'sirromas@gmail.com';
        $addrB = 'info@medical2.com';
        $addrC = 'help@medical2.com';
        $mail->AddAddress($addrA);
        $mail->addCC($addrB);
        $mail->addCC($addrC);
        if (!$mail->send()) {
            return false;
        } // end if !$mail->send()
        else {
            return true;
        }
    }

    function send_group_certificate_renewal_message_failure_info($m) {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $this->mail_smtp_host;
        $mail->SMTPAuth = true;
        $mail->Username = $this->mail_smtp_user;
        $mail->Password = $this->mail_smtp_pwd;
        $mail->SMTPSecure = 'tls';
        $mail->Port = $this->mail_smtp_port;
        $mail->setFrom($this->mail_smtp_user, 'Medical2');
        $mail->addReplyTo($this->mail_smtp_user, 'Medical2');
        $mail->isHTML(true);
        $mail->Subject = 'Medical2 - Group Certificates Renewal Failed Transaction Info';
        $mail->Body = $m;

        $addrA = 'sirromas@gmail.com';
        $addrB = 'info@medical2.com';
        $addrC = 'help@medical2.com';
        $mail->AddAddress($addrA);
        //$mail->addCC($addrB);
        //$mail->addCC($addrC);
        if (!$mail->send()) {
            return false;
        } // end if !$mail->send()
        else {
            return true;
        }
    }

    function create_renewal_pdf_report($list, $email) {
        $dompdf = new Dompdf();
        $dompdf->loadHtml($list);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $output = $dompdf->output();
        $file_path = $this->renewal_path . "/$email.pdf";
        file_put_contents($file_path, $output);
    }

    function get_group_renewal_students($usersarr, $groupname) {
        $list = "";

        $list.="<tr style='font-weight:bold;'>";
        $list.="<td>Group name:</td><td>$groupname</td>";
        $list.="</tr>";

        foreach ($usersarr as $userid) {
            $userdata = $this->get_user_details($userid);
            $list.="<tr>";
            $list.="<td>First name</td><td>$userdata->firstname</td>";
            $list.="</tr>";

            $list.="<tr>";
            $list.="<td>Last name</td><td>$userdata->lastname</td>";
            $list.="</tr>";

            $list.="<tr>";
            $list.="<td>Email</td><td>$userdata->email</td>";
            $list.="</tr>";

            $list.="<tr>";
            $list.="<td colspan='2'><br></td>";
            $list.="</tr>";
        } // end foreach

        return $list;
    }

    function get_user_group_name($userid) {
        $query = "select * from mdl_groups_members where userid=$userid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $groupid = $row['groupid'];
        }
        $query = "select * from mdl_groups where id=$groupid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $groupname = $row['name'];
        }
        return $groupname;
    }

    function send_group_renew_payment_confirmation_message($data, $printed_data = null) {
        $list = "";

        //print_r($data);
        //die();
        $usersarr = explode(',', $data->userslist);
        $total = count($usersarr);
        $course_name = $this->get_course_name($data);
        $catid = $this->get_course_category($data);
        $groupname = $this->get_user_group_name($usersarr[0]);
        $students = $this->get_group_renewal_students($usersarr, $groupname);
        $state = $data->state;
        $recipient = $data->email;
        $auth_code = $data->auth_code;
        $list.= "<!DOCTYPE HTML><html><head><title>Group Certificate Renewal Confirmation</title>";
        $list.="</head>";
        $list.="<body><br/><br/><br/><br/>";
        $list.="<div class='datagrid'>";

        $list.="<table style='table-layout: fixed;' width='360'>
        <thead>";

        if ($printed_data == NULL) {
            if ($catid == 5) {
                $list.="<tr>";
                $list.="<th colspan='2' align='left'><img src='http://medical2.com/assets/logo/receipt_college.png' width='360' height='130'></th>";
                $list.="</tr>";
            } // end if
            else {
                $list.="<tr>";
                $list.="<th colspan='2' align='left'><img src='http://medical2.com/assets/logo/receipt_agency.png' width='360' height='120'></th>";
                $list.="</tr>";
            } // end else
        } // end if $printed_data == NULL


        $list.="</thead>
        <tbody>";

        $list.=$students;

        $list.="<tr style='background-color:#F5F5F5;'>
        <td>Total students</td><td>$total</td>
        </tr>
        
        <tr style=''>
        <td colspan='2' style='font-weight:bold;'><br>Billing info</td>
        </tr>
        
        <tr style='background-color:#F5F5F5;'>
        <td>First name</td><td>$data->fname</td>
        </tr>
        
        <tr>
        <td>Last name</td><td>$data->lname</td>
        </tr>
        
        <tr style='background-color:#F5F5F5;'>
        <td>Email</td><td>$data->email</td>
        </tr>
        
        <tr style='background-color:#F5F5F5;'>
        <td>Address</td><td>$data->addr</td>
        </tr>
        
        <tr>
        <td>City</td><td>$data->city</td>
        </tr>
        
        <tr style='background-color:#F5F5F5;'>
        <td>State</td><td>$state</td>
        </tr>
        
        <tr>
        <td>Zip</td><td>$data->zip</td>
        </tr>
        
        <tr style='background-color:#F5F5F5;'>
        <td>Applied Progarm</td><td>Group certificates renewal - $course_name</td>
        </tr>
        
        <tr>
        <td>Program fee</td><td>$$data->full_amount</td>
        </tr>
        
        <tr style='background-color:#F5F5F5;'>
        <td>Auth Code</td><td>$auth_code</td>
        </tr>";

        if (property_exists($data, 'full_amount')) {
            date_default_timezone_set("America/New_York");
            $date = date('m-d-Y h:i:s', time());

            $list.="<tr style='background-color:#F5F5F5;'>
            <td>Payment status: </td><td>Paid by card: $$data->full_amount</td>
            </tr>";

            $list.="<tr style='background-color:#F5F5F5;'>";
            $list.="<td>Order Date:</td><td>$date</td>";
            $list.="</tr>";
        } // end if $payment_amount != null

        $list.="</tbody>
        </table>
        </div>";
        $list.="<p>If you need assistance please contact us by email <a href='mailto:help@medical2.com'>help@medical2.com</a> or call us 877-741-1996</p>";
        $list.="</body></html>";
        $subject = "Medical2 - Group Certificate Renewal Payment Confirmation";

        $mail = new PHPMailer;
        $addressA = 'info@medical2.com';
        $addressB = 'help@medical2.com';
        $addressC = 'sirromas@gmail.com';

        $mail->isSMTP();
        $mail->Host = $this->mail_smtp_host;
        $mail->SMTPAuth = true;
        $mail->Username = $this->mail_smtp_user;
        $mail->Password = $this->mail_smtp_pwd;
        $mail->SMTPSecure = 'tls';
        $mail->Port = $this->mail_smtp_port;

        $mail->setFrom($this->mail_smtp_user, 'Medical2');
        $mail->addAddress($addressA);
        $mail->addAddress($addressB);
        $mail->addAddress($addressC);

        if ($recipient != null) {
            $mail->addAddress($recipient);
        }
        $mail->addReplyTo($this->mail_smtp_user, 'Medical2');

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $list;

        if (!$mail->send()) {
            //echo 'Message could not be sent.';
            //echo 'Mailer Error: ' . $mail->ErrorInfo;
            return false;
        } // end if !$mail->send()
        else {
            //echo 'Message has been sent to ' . $recipient;
            return true;
        }
    }

    function send_webhook_notification($data) {
        $list = "";
        $now = date('m-d-Y H:i:s', time());
        $subject = "Medical2 Inc - Payment Event Notification";

        $list.="<html>";
        $list.="<body>";

        $list.="<br><br><table>";
        $list.="<tr>";
        $list.="<td colspan='2'><img src='https://medical2.com/assets/logo/5_edited.png'></td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td>Card Holder</td><td>" . $data['x_first_name'] . " " . $data['x_last_name'] . "</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td>Email</td><td>" . $data['x_email'] . "</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td>Phone</td><td>" . $data['x_phone'] . "</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td>Address</td><td>" . $data['x_address'] . "</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td>City</td><td>" . $data['x_city'] . "</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td>State</td><td>" . $data['x_state'] . "</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td>Zip</td><td>" . $data['x_zip'] . "</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td>Item</td><td>" . $data['x_description'] . "</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td>Amount</td><td>$" . $data['x_amount'] . "</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td>Account Number</td><td>" . $data['x_account_number'] . "</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td>Response Reason Text</td><td>" . $data['x_response_reason_text'] . "</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td>Response Code</td><td>" . $data['x_response_code'] . "</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td>Response Code Reason</td><td>" . $data['x_response_reason_code'] . "</td>";
        $list.="</tr>";


        $list.="<tr>";
        $list.="<td>Auth Code</td><td>" . $data['x_auth_code'] . "</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td>Transaction ID</td><td>" . $data['x_trans_id'] . "</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td>Order date</td><td>$now</td>";
        $list.="</tr>";

        $list.="</table>";

        $list.="</body>";
        $list.="</html>";

        $mail = new PHPMailer;
        $addressA = 'info@medical2.com';
        $addressB = 'help@medical2.com';
        $addressC = 'sirromas@gmail.com';

        $mail->isSMTP();
        $mail->Host = $this->mail_smtp_host;
        $mail->SMTPAuth = true;
        $mail->Username = $this->mail_smtp_user;
        $mail->Password = $this->mail_smtp_pwd;
        $mail->SMTPSecure = 'tls';
        $mail->Port = $this->mail_smtp_port;

        $mail->setFrom($this->mail_smtp_user, 'Medical2');
        $mail->addAddress($addressA);
        $mail->addCC($addressB);
        $mail->addCC($addressC);


        $mail->addReplyTo($this->mail_smtp_user, 'Medical2');

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $list;

        if (!$mail->send()) {
            return false;
        } // end if !$mail->send()
        else {
            return true;
        }
    }

}
