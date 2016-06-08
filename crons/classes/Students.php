<?php

require_once ('/home/cnausa/public_html/class.pdo.database.php');
require_once ('/home/cnausa/public_html/functionality/php/classes/mailer/vendor/PHPMailerAutoload.php');

class Students {

    public $db;
    public $mail_smtp_host = 'mail.medical2.com';
    public $mail_smtp_port = 25;
    public $mail_smtp_user = 'info@medical2.com';
    public $mail_smtp_pwd = 'aK6SKymc';
    public $contextlevel = 50;

    function __construct() {
        $db = new pdo_db();
        $this->db = $db;
    }

    /*     * ********************** Remove Installment users functionality ********************* */

    function get_user_card_payments($userid, $courseid) {
        $sum = 0;
        $query = "select * from mdl_card_payments "
                . "where "
                . "userid=$userid "
                . "and courseid=$courseid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $sum = $sum + $row['psum'];
            } // end while 
        } // end if $num > 0
        return $sum;
    }

    function get_course_cost($courseid) {
        $query = "select * from mdl_course where id=$courseid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $cost = $row['cost'];
        }
        return $cost;
    }

    function verify_installment_users() {
        $query = "select * from mdl_installment_users";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $course_cost = $this->get_course_cost($row['courseid']);
                $paid_sum = $this->get_user_card_payments($row['userid'], $row['courseid']);
                if ($paid_sum == $course_cost) {
                    $query = "delete from mdl_installment_users "
                            . "where  userid=" . $row['userid'] . ""
                            . " and courseid=" . $row['courseid'] . "";
                    $this->db->numrows($query);
                    echo "User with ID (" . $row['userid'] . ") have been deleted from installment users \n";
                } // end if $paid_sum == $course_cost
                else {
                    echo "User with ID (" . $row['userid'] . ") is not yet paid in full for course with ID (" . $row['courseid'] . ") \n";
                } // end elese 
            } // end while
        } // end if $num>0
        else {
            echo "There are no installment users available ....\n";
        }
    }

    /*     * ********************** Owe Users functionality ************************ */

    function get_course_name($courseid) {
        $query = "select fullname from mdl_course where id=$courseid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $name = $row['fullname'];
        }
        return $name;
    }

    function get_user_data($userid) {
        $query = "select * from mdl_user where id=$userid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $user = new stdClass();
            foreach ($row as $key => $value) {
                $user->$key = $value;
            } // end foreach
        } // end while
        return $user;
    }

    function get_course_context($courseid) {
        $query = "select * from mdl_context "
                . "where contextlevel=$this->contextlevel "
                . "and instanceid=$courseid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $contextid = $row['id'];
        }
        return $contextid;
    }

    function get_course_teachers($courseid) {
        $users[] = array();
        $contextid = $this->get_course_context($courseid);
        $query = "select * from mdl_role_assignments "
                . "where roleid=1 or roleid=4 "
                . "and contextid=$contextid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $users[] = $row['userid'];
            } // end while
        } // end if $num > 0
        return $users;
    }

    function get_renew_fee() {
        $query = "select * from mdl_renew_fee";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $fee = $row['fee_sum'];
        } // end while
        return $fee;
    }

    function get_user_slot($courseid, $userid) {
        $slotid = 0;
        $query = "select * from mdl_slots "
                . "where courseid=$courseid and userid=$userid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $slotid = $row['slotid'];
            } // end while
        } // end if $num > 0
        return $slotid;
    }

    function get_partial_cc_payments() {
        $partials = array();
        $query = "select * from mdl_card_payments "
                . "where pdate>1464074847 order by pdate desc";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $renew_fee = $this->get_renew_fee();
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $user_payment = $row['psum'];                
                $course_cost = $this->get_course_cost($row['courseid']);
                $slotid = $this->get_user_slot($row['courseid'], $row['userid']);
                if ($user_payment < $course_cost && $user_payment != $renew_fee) {
                    $partial = new stdClass();
                    $partial->userid = $row['userid'];
                    $partial->courseid = $row['courseid'];
                    $partial->payment = $row['psum'];
                    $partial->cost = $course_cost;
                    $partial->slotid = $slotid;
                    $partial->pdate = $row['pdate'];
                    $partials[] = $partial;
                } // end if $user_payment!=$course_cost
            } // end while
        } // end if $num > 0
        return $partials;
    }

    function get_partial_offline_payments() {
        $partials = array();
        $query = "select * from mdl_partial_payments";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $user_payment = $row['psum'];
                $user_data = $this->get_user_data($row['userid']);
                $course_cost = $this->get_course_cost($row['courseid']);
                $slotid = $this->get_user_slot($row['courseid'], $row['userid']);
                if ($user_payment < $course_cost) {
                    $partial = new stdClass();
                    $partial->userid = $row['userid'];
                    $partial->courseid = $row['courseid'];
                    $partial->payment = $row['psum'];
                    $partial->cost = $course_cost;
                    $partial->pdate = $row['pdate'];
                    $partial->slotid = $slotid;
                    $partials[] = $partial;
                } // end if $user_payment!=$course_cost
            } // end while
        } // end if $num > 0        
        return $partials;
    }

    function check_owe_students() {
        $list = "";
        $cc_partials = $this->get_partial_cc_payments();
        $of_partials = $this->get_partial_offline_payments();
        $partials = array_merge($cc_partials, $of_partials);
        if (count($partials) > 0) {
            $list.=$this->process_receivers($partials);
        } // end if count($partials)>0
        else {
            $list.="There are no users with partial payments \n";
        }
        return $list;
    }

    function process_receivers($partials) {
        $list = "";
        $clean_teachers = array();
        if (count($partials) > 0) {
            foreach ($partials as $partial) {
                if ($partial->slotid > 0) {
                    $teachers = $this->get_course_teachers($partial->courseid);
                    array_push($teachers, 2); // add admin user to list of receivers
                    if (count($teachers) > 0) {
                        foreach ($teachers as $teacher) {
                            if (is_numeric($teacher)) {
                                $clean_teachers[] = $teacher;
                            } // end if is_number($teacher)
                        } // end foreach
                        $list.=$this->notify_user($partial, $clean_teachers);
                    } // end if count($teachers) > 0
                } // end if $partial->slotid>0
                else {
                    $list.="Current user (User ID: $partial->userid) does not have scheduled workshops \n";
                } // end else
            } // end foreach
        } // end if count($partials)>0
        else {
            $list.="There are no partial payments \n";
        }
        return $list;
    }

    function get_workshop_date($slotid) {
        $query = "select * from mdl_scheduler_slots where id=$slotid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $date = $row['starttime'];
        } // end while
        return $date;
    }

    function prepare_message($partial) {
        $list = "";
        date_default_timezone_set('Pacific/Wallis');
        $diff = $partial->cost - $partial->payment;
        $user_data = $this->get_user_data($partial->userid);
        $coursename = $this->get_course_name($partial->courseid);
        $date_h = date('m-d-Y', $partial->pdate);
        $wsdate = $this->get_workshop_date($partial->slotid);
        $start_date = date('m-d-Y h:i:s', $wsdate);
        $list.="<table ><tr>";
        $list.="<td >Student</td>";
        $list.="<td>&nbsp;&nbsp;<a href='https://" . $_SERVER['SERVER_NAME'] . "/lms/user/profile.php?id=$partial->userid' target='_blank'>$user_data->firstname $user_data->lastname</a></td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td>Applied program</td>";
        $list.="<td style='margin:10px;'>&nbsp;&nbsp;$coursename</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td>Program start date</td>";
        $list.="<td style='margin:10px;'>&nbsp;&nbsp;$start_date</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td>Program fee</td>";
        $list.="<td>&nbsp;&nbsp;$$partial->cost</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td>Student paid</td>";
        $list.="<td>&nbsp;&nbsp;$$partial->payment</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td>Student owes</td>";
        $list.="<td>&nbsp;&nbsp;$$diff  </td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td>Payment date</td>";
        $list.="<td>&nbsp;&nbsp;$date_h</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td colspan='2'><hr/></td>";
        $list.="</tr>";
        $list.="</table>";

        return $list;
    }

    function notify_user($partial, $teachers) {
        $list = "";
        date_default_timezone_set('Pacific/Wallis');
        $now = time();
        $diff = 43200; // 12h in secs        
        $wsdate = $this->get_workshop_date($partial->slotid);
        $start_date = date('m-d-Y h:i:s', $wsdate);
        $wsname = $this->get_course_name($partial->courseid);
        $user_data = $this->get_user_data($partial->userid);
        if ($wsdate - $now <= $diff) {
            $message = $this->prepare_message($partial);
            foreach ($teachers as $teacherid) {
                $list.=$this->send_notification_message($message, $teacherid);
            } // end foreach
        } // end if $wsdate-$now<=$diff
        else {
            $list.="User $user_data->firstname $user_data->lastname scheduled Workshop $wsname $start_date is still ahead \n";
        } // end else
        return $list;
    }

    function send_notification_message($message, $teacherid) {
        $user_date = $this->get_user_data($teacherid);
        $email = $user_date->email;

        //$email = 'sirromas@gmail.com'; // for testing;

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

        $mail->setFrom($this->mail_smtp_user, 'Medical2 Career College');
        $mail->addAddress($email);
        $mail->addReplyTo($this->mail_smtp_user, 'Medical2 Career College');

        $mail->isHTML(true);

        $mail->Subject = 'Workshop owe students';
        $mail->Body = $message;

        if (!$mail->send()) {
            echo "Message could not be sent to $email \n";
            echo 'Mailer Error: ' . $mail->ErrorInfo . "\n";
        } // end if !$mail->send()        
        else {
            echo "Message has been sent to ' . $email \n";
        }
    }

}
