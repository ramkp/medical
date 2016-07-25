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
        if ($userid > 0) {
            $query = "select * from mdl_user where id=$userid";
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $user = new stdClass();
                foreach ($row as $key => $value) {
                    $user->$key = $value;
                } // end foreach
            } // end while
        } // end if $userid>0
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
                . "where pdate>1464074847 order by pdate asc";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $renew_fee = $this->get_renew_fee();
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $user_payment = $row['psum'];
                $course_cost = $this->get_course_cost($row['courseid']);
                $slotid = $this->get_user_slot($row['courseid'], $row['userid']);
                if ($user_payment < $course_cost && $user_payment != $renew_fee) {
                    $wsdate = $this->get_workshop_date($slotid);
                    $partial = new stdClass();
                    $partial->userid = $row['userid'];
                    $partial->courseid = $row['courseid'];
                    $partial->payment = $row['psum'];
                    $partial->cost = $course_cost;
                    $partial->slotid = $slotid;
                    $partial->pdate = $row['pdate'];
                    $partials[$wsdate] = $partial;
                } // end if $user_payment!=$course_cost
            } // end while
        } // end if $num > 0    
        ksort($partials);
        return $partials;
    }

    function get_partial_offline_payments() {
        $partials = array();
        $query = "select * from mdl_partial_payments "
                . "where pdate>1464074847 order by pdate asc";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $user_payment = $row['psum'];
                $user_data = $this->get_user_data($row['userid']);
                $course_cost = $this->get_course_cost($row['courseid']);
                $slotid = $this->get_user_slot($row['courseid'], $row['userid']);
                if ($user_payment < $course_cost) {
                    $wsdate = $this->get_workshop_date($slotid);
                    $partial = new stdClass();
                    $partial->userid = $row['userid'];
                    $partial->courseid = $row['courseid'];
                    $partial->payment = $row['psum'];
                    $partial->cost = $course_cost;
                    $partial->pdate = $row['pdate'];
                    $partial->slotid = $slotid;
                    $partials[$wsdate] = $partial;
                } // end if $user_payment!=$course_cost
            } // end while
        } // end if $num > 0    
        return $partials;
    }

    function get_future_workshops() {
        $now = time() - 86400;
        $ws = array();
        $query = "select * from mdl_scheduler_slots where starttime>$now order by starttime";
        //echo "Query: " . $query . "<br>";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $ws[] = $row['id'];
            } // end while            
        } // end if $num > 0
        return $ws;
    }

    // *************************** Entry point *******************************

    function get_workshop_students() {
        $list = "";
        $workshops = $this->get_future_workshops();
        if (count($workshops) > 0) {
            $list.=$this->prepare_report($workshops);
            $this->send_notification_message($list);
        } // end if count($workshops)>0
    }

    function check_owe_students() {
        $list = "";
        $clear_workshops = array();
        $partials = array();
        // Get CC & Offline partials
        $cc_partials = $this->get_partial_cc_payments();
        $of_partials = $this->get_partial_offline_payments();
        $partials_arr = array_merge($cc_partials, $of_partials);
        // Sort workshops by date
        if (count($partials_arr) > 0) {
            foreach ($partials_arr as $p) {
                $wsdate = $this->get_workshop_date($p->slotid);
                $partials[$wsdate] = $p;
            }
        } // end if count($partials_arr)>0
        ksort($partials);


        //echo "<br>-----------Partial payment users-----------<br>";
        //print_r($partials);
        //echo "<br>--------------------------------------------<br>";
        // Get Workshops in future periods
        $workshops = $this->get_future_workshops();
        //echo "<br>------------ Future workshops --------------<br>";
        //print_r($workshops);
        //echo "<br>---------------------------------------------<br>";



        if (count($workshops) > 0) {
            foreach ($workshops as $slotid) {
                $total_students = $this->get_total_workshop_participants($slotid);

                if ($total_students > 0) {
                    // Get workshop date and participants
                    date_default_timezone_set('Pacific/Wallis');
                    //echo "Workshop name: $ws_detailes->notes<br>";
                    //echo "Workshop location: $ws_detailes->appointmentlocation <br>";
                    //echo "Workshop date: " . date('m-d-Y', $ws_detailes->starttime) . "<br>";
                    $wsdate = $this->get_workshop_date($slotid);
                    //echo "Workshop date: " . date('m-d-Y', $wsdate) . "<br>";
                    $participants = $this->get_workshop_participants($slotid); // array
                    //echo "<br>------------------ Workshop participants >------------------<br>";
                    //print_r($participants);
                    //echo "<br>-------------------------------------------------------------<br>";
                    //echo "Total students in current workshop: $total_students<br>";
                    // Check if workshop contains partial payment students?
                    foreach ($participants as $p) {
                        //echo "Current user id: $p<br>";
                        if (in_array($p, $partials)) {
                            //echo "Inside if .... <br>";
                            $clear_workshops[$wsdate] = $slotid;
                        } // end if in_array($p, $partials)
                    } // end foreach
                } // end if $total_students>0
                //echo "<br>/////////////////////////////////////////////////////////////////////////////////////////////////////<br>";
            } // end foreach
        } // end if count($workshops)>0
        //print_r($partials);
        if (count($clear_workshops) > 0) {
            $list.=$this->prepare_report($clear_workshops); // prepare report
            $this->send_notification_message($list); // send report
        } // end if count($partials)>0
        else {
            $list.="There are no users with partial payments \n";
        }
        return $list;
    }

    function get_workshop_details($id) {
        $query = "select * from mdl_scheduler_slots where id=$id";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $ws = new stdClass();
            foreach ($row as $key => $value) {
                $ws->$key = $value;
            } // end foreach
        } // end while
        return $ws;
    }

    function get_workshop_course($slotid) {
        $courseid = 0;
        $query = "select * from mdl_scheduler_slots where id=$slotid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $sch = $row['schedulerid'];
        } // end while 

        $query = "select * from mdl_scheduler where id=$sch";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $courseid = $row['course'];
            } // end while 
        } // end if $num > 0
        return $courseid;
    }

    function get_student_payments($courseid, $userid) {

        $partial1 = array(); // CC payments
        $partial2 = array(); // Other payments
        // CC payments
        $query = "select * from mdl_card_payments "
                . "where courseid=$courseid "
                . "and userid=$userid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $renew_fee = $this->get_renew_fee();
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $user_payment = $row['psum'];
                $course_cost = $this->get_course_cost($row['courseid']);
                $slotid = $this->get_user_slot($row['courseid'], $row['userid']);
                if ($user_payment != $renew_fee) {
                    $partial = new stdClass();
                    $partial->userid = $row['userid'];
                    $partial->courseid = $row['courseid'];
                    $partial->payment = $row['psum'];
                    $partial->cost = $course_cost;
                    $partial->slotid = $slotid;
                    $partial->pdate = $row['pdate'];
                } // end if $user_payment!=$course_cost
                $partial1[] = $partial;
            } // end while
        } // end if $num > 0    
        // Other payments
        $query = "select * from mdl_partial_payments "
                . "where courseid=$courseid "
                . "and userid=$userid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $renew_fee = $this->get_renew_fee();
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $user_payment = $row['psum'];
                $course_cost = $this->get_course_cost($row['courseid']);
                $slotid = $this->get_user_slot($row['courseid'], $row['userid']);
                //if ($user_payment != $renew_fee) {
                $partial = new stdClass();
                $partial->userid = $row['userid'];
                $partial->courseid = $row['courseid'];
                $partial->payment = $row['psum'];
                $partial->cost = $course_cost;
                $partial->pdate = $row['pdate'];
                $partial->slotid = $slotid;
                //} // end if $user_payment != $renew_fee
                $partial2[] = $partial;
            } // end while
        } // end if $num > 0
        $payments = array('p1' => $partial1, 'p2' => $partial2);
        return $payments;
    }

    function prepare_intructor_report($courseid, $slotid) {
        $list = "";
        $owe_sum = 0;
        date_default_timezone_set('Pacific/Wallis');
        $query = "select * from mdl_scheduler_appointment "
                . "where slotid=$slotid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $userid = $row['studentid'];
            $roleid = $this->get_user_course_role($courseid, $userid);
            //echo "User id: " . $userid . "<br>";
            //echo "Role id: " . $roleid . "<br>";
            //echo "<br>--------------------------------------------------<br>";
            if ($roleid == 3) {
                // It is instructor
                $tutorid = $userid;
                $participants = $this->get_workshop_participants($slotid);
                $coursename = $this->get_course_name($courseid);
                $cost = $this->get_course_cost($courseid);
                $wsdate = $this->get_workshop_date($slotid);
                $start_date = date('m-d-Y h:i:s', $wsdate);
                $list.="<table>";

                $list.="<tr>";
                $list.="<td>Program/workshop</td>";
                $list.="<td style='margin:10px;'>&nbsp;&nbsp;$coursename</td>";
                $list.="</tr>";

                $list.="<tr>";
                $list.="<td>Program start date</td>";
                $list.="<td style='margin:10px;'>&nbsp;&nbsp;$start_date</td>";
                $list.="</tr>";

                $list.="<tr>";
                $list.="<td>Workshop venue</td>";
                $location = $this->get_workshop_location($slotid);
                $list.="<td style='margin:10px;'>&nbsp;&nbsp;$location</td>";
                $list.="</tr>";

                $list.="<tr>";
                $list.="<td>Program fee</td>";
                $list.="<td>&nbsp;&nbsp;$$cost</td>";
                $list.="</tr>";

                $list.="<tr>";
                $list.="<td>Total students</td>";
                $list.="<td style='margin:10px;'>&nbsp;&nbsp;" . count($participants) . "</td>";
                $list.="</tr>";

                foreach ($participants as $p) {

                    $user_data = $this->get_user_data($p);

                    $list.="<tr>";
                    $list.="<td >Student</td>";
                    $list.="<td>&nbsp;&nbsp;<a href='https://" . $_SERVER['SERVER_NAME'] . "/lms/user/profile.php?id=$p' target='_blank'>$user_data->firstname $user_data->lastname</a></td>";
                    $list.="</tr>";

                    $partial = $this->get_student_payments($courseid, $p);
                    if (is_object($partial)) {
                        if ($partial->cost >= $partial->payment) {
                            $diff = $partial->cost - $partial->payment;
                        } // end if $partial->cost>=$partial->payment
                        else {
                            $diff = 0;
                        } // end else
                        $list.="<tr>";
                        $list.="<td>Student paid</td>";
                        $list.="<td>&nbsp;&nbsp;$" . round($partial->payment) . "</td>";
                        $list.="</tr>";

                        $list.="<tr>";
                        $list.="<td>Student owes</td>";
                        $list.="<td>&nbsp;&nbsp;$$diff  </td>";
                        $list.="</tr>";

                        $owe_sum = $owe_sum + $diff;
                    } //end if is_object($partial)
                    else {
                        $list.="<tr>";
                        $list.="<td>Student paid</td>";
                        $list.="<td>&nbsp;&nbsp;N/A</td>";
                        $list.="</tr>";

                        $list.="<tr>";
                        $list.="<td>Student owes</td>";
                        $list.="<td>&nbsp;&nbsp;N/A  </td>";
                        $list.="</tr>";
                    } // end else
                } // end foreach

                $list.="<tr>";
                $list.="<td>Total to be collected</td>";
                $list.="<td>$$owe_sum</td>";
                $list.="</tr>";

                $list.="<tr>";
                $list.="<td colspan='2'><hr/></td>";
                $list.="</tr>";

                $list.="</table>";
            } // end if $roleid==3
        } // end while

        $report = array('report' => $list, 'tutor' => $tutorid);
        return $report;
    }

    function get_user_course_role($courseid, $userid) {
        $contextid = $this->get_course_context($courseid);
        $query = "select * from mdl_role_assignments "
                . "where contextid=$contextid and userid=$userid";
        //echo "Query: ".$query."<br>";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $roleid = $row['roleid'];
        }
        return $roleid;
    }

    function get_course_instructors($courseid) {
        $instructors = array();
        $contextid = $this->get_course_context($courseid);
        $query = "select * from mdl_role_assignments "
                . "where contextid=$contextid and roleid=3";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $instructors[] = $row['userid'];
            } // end while
        } // end if $num > 0
        return $instructors;
    }

    function prepare_report($workshops) {
        $list = "";
        $i = 0;

        foreach ($workshops as $ws) {

            $participants = $this->get_workshop_participants($ws);
            if (count($participants) > 0) {

                $owe_sum = 0;
                date_default_timezone_set('Pacific/Wallis');
                $courseid = $this->get_workshop_course($ws);
                $coursename = $this->get_course_name($courseid);
                $cost = $this->get_course_cost($courseid);
                $wsdate = $this->get_workshop_date($ws);
                $start_date = date('m-d-Y h:i:s', $wsdate);
                $list.="<table >";

                $list.="<tr>";
                $list.="<td>Program/workshop</td>";
                $list.="<td style='margin:10px;'>&nbsp;&nbsp;$coursename</td>";
                $list.="</tr>";

                $list.="<tr>";
                $list.="<td>Program start date</td>";
                $list.="<td style='margin:10px;'>&nbsp;&nbsp;$start_date</td>";
                $list.="</tr>";

                $list.="<tr>";
                $list.="<td>Workshop venue</td>";
                $location = $this->get_workshop_location($ws);
                $list.="<td style='margin:10px;'>&nbsp;&nbsp;$location</td>";
                $list.="</tr>";

                $list.="<tr>";
                $list.="<td>Program fee</td>";
                $list.="<td>&nbsp;&nbsp;$$cost</td>";
                $list.="</tr>";

                $list.="<tr>";
                $list.="<td>Total students</td>";
                $list.="<td style='margin:10px;'>&nbsp;&nbsp;" . count($participants) . "</td>";
                $list.="</tr>";

                foreach ($participants as $p) {

                    $user_data = $this->get_user_data($p);

                    $list.="<tr>";
                    $list.="<td >Student</td>";
                    $list.="<td>&nbsp;&nbsp;<a href='https://" . $_SERVER['SERVER_NAME'] . "/lms/user/profile.php?id=$p' target='_blank'>$user_data->firstname $user_data->lastname</a></td>";
                    $list.="</tr>";

                    $payments = $this->get_student_payments($courseid, $p);
                    $cc_payments = $payments['p1'];
                    $other_payments = $payments['p2'];

                    $fname = $user_data->firstname;
                    $lname = $user_data->lastname;
                    echo "Student: " . $fname . "&nbsp;" . $lname . "<br>";

                    echo "<pre>";
                    print_r($cc_payments);
                    echo "/<pre><br>";

                    echo "<pre>";
                    print_r($other_payments);
                    echo "/<pre><br>";

                    if (count($cc_payments) > 0) {
                        $total_paid = 0;
                        foreach ($cc_payments as $partial) {
                            if (is_object($partial)) {
                                if ($partial->cost >= $partial->payment) {
                                    $diff = $partial->cost - $partial->payment;
                                } // end if $partial->cost>=$partial->payment
                                else {
                                    $diff = 0;
                                } // end else
                                $list.="<tr>";
                                $list.="<td>Student paid</td>";
                                $list.="<td>&nbsp;&nbsp;$" . round($partial->payment) . "</td>";
                                $list.="</tr>";

                                $total_paid = $total_paid + $partial->payment;
                            } //end if is_object($partial)
                            else {
                                $list.="<tr>";
                                $list.="<td>Student paid</td>";
                                $list.="<td>&nbsp;&nbsp;N/A</td>";
                                $list.="</tr>";
                            } // end else
                        } // end foreach
                        //$list.="<tr>";
                        //$list.="<td>Total paid :</td>";
                        //$list.="<td>&nbsp;&nbsp; $" . $total_paid . "  </td>";
                        //$list.="</tr>";
                        $owe_sum = $owe_sum + round($partial->cost - $total_paid);
                        $list.="<tr>";
                        $list.="<td>Student owes</td>";
                        $list.="<td>&nbsp;&nbsp; $" . round($partial->cost - $total_paid) . "  </td>";
                        $list.="</tr>";
                    } // end if count($cc_payments)>0

                    if (count($other_payments) > 0) {
                        $total_paid = 0;
                        foreach ($other_payments as $partial) {
                            if (is_object($partial)) {
                                if ($partial->cost >= $partial->payment) {
                                    $diff = $partial->cost - $partial->payment;
                                } // end if $partial->cost>=$partial->payment
                                else {
                                    $diff = 0;
                                } // end else
                                $list.="<tr>";
                                $list.="<td>Student paid</td>";
                                $list.="<td>&nbsp;&nbsp;$" . round($partial->payment) . "</td>";
                                $list.="</tr>";

                                $total_paid = $total_paid + $partial->payment;
                            } //end if is_object($partial)
                            else {
                                $list.="<tr>";
                                $list.="<td>Student paid</td>";
                                $list.="<td>&nbsp;&nbsp;N/A</td>";
                                $list.="</tr>";

                                $list.="<tr>";
                                $list.="<td>Student owes</td>";
                                $list.="<td>&nbsp;&nbsp;N/A  </td>";
                                $list.="</tr>";
                            } // end else
                        } // end foreach
                        //$list.="<tr>";
                        //$list.="<td>Total paid :</td>";
                        //$list.="<td>&nbsp;&nbsp; $" . $total_paid . "  </td>";
                        //$list.="</tr>";

                        $owe_sum = $owe_sum + round($partial->cost - $total_paid);
                        $list.="<tr>";
                        $list.="<td>Student owes</td>";
                        $list.="<td>&nbsp;&nbsp; $" . round($partial->cost - $total_paid) . "  </td>";
                        $list.="</tr>";
                    } // end if count($other_payments)>0               
                } // end foreach patricipants

                //echo "Owe sum: ".$owe_sum."<br>";
                if ($owe_sum >= 0) {
                    $list.="<tr>";
                    $list.="<td>Total to be collected</td>";
                    $list.="<td>$$owe_sum</td>";
                    $list.="</tr>";
                } // end if $owe_sum>0
                else {
                    $list.="<tr>";
                    $list.="<td>Total to be collected</td>";
                    $list.="<td>Students overpayments?</td>";
                    $list.="</tr>";
                }

                $list.="<tr>";
                $list.="<td colspan='2'><hr/></td>";
                $list.="</tr>";

                $list.="</table>";

                $tutor_report = $this->prepare_intructor_report($courseid, $ws);
                $this->send_instructor_report($tutor_report);

                $i++;
            } // end if count($participants)>0                       
        } // end foreach
        echo "<br><p style='font-weight:bold;'>Total items: " . $i . "</p><br>";
        return $list;
    }

    function process_receivers($partials) {
        $list = "";
        date_default_timezone_set('Pacific/Wallis');
        $now = time();
        $diff = 43200; // 24h in secs 
        $i = 0;
        if (count($partials) > 0) {
            foreach ($partials as $partial) {
                if ($partial->slotid > 0) {
                    $wsdate = $this->get_workshop_date($partial->slotid);
                    echo "Current date: " . date('m-d-Y', $now) . "<br>";
                    echo "Workshop date: " . date('m-d-Y', $wsdate) . "<br>";
                    if ($wsdate > $now) {
                        echo "Inside if ...<br>";
                        $list.=$this->prepare_message($partial);
                        $i++;
                    } // end if $wsdate - $now <= $diff
                    echo "<br>-----------------------------------------------<br>";
                } // end if $partial->slotid>0
            } // end foreach
            echo "Total students: $i<br>";
            $list.=$this->notify_user($list);
        } // end if count($partials)>0        
    }

    function get_workshop_date($slotid) {
        $query = "select * from mdl_scheduler_slots where id=$slotid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $date = $row['starttime'];
        } // end while
        return $date;
    }

    function get_workshop_location($slotid) {
        $query = "select * from mdl_scheduler_slots where id=$slotid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $loc = $row['appointmentlocation'];
        }
        $loc_arr = explode("/", $loc);
        $location = $loc_arr[1] . "," . $loc_arr[0];
        return $location;
    }

    function get_total_workshop_participants($slotid) {
        $query = "select count(id) as total from mdl_scheduler_appointment "
                . "where slotid=$slotid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $total = $row['total'];
        }
        return $total;
    }

    function get_workshop_participants($slotid) {
        $students = array();
        $query = "select * from mdl_scheduler_appointment "
                . "where slotid=$slotid";
        //echo "Query: " . $query . "<br>";
        $num = $this->db->numrows($query);
        //echo "Students num: " . $num . "<br>";
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $students[] = $row['studentid'];
            } // end while
        } // end if $num > 0
        return $students;
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
        $list.="<td>Workshop venue</td>";
        $location = $this->get_workshop_location($partial->slotid);
        $list.="<td style='margin:10px;'>&nbsp;&nbsp;$location</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td>Total students</td>";
        $total = $this->get_workshop_participants($partial->slotid);
        $list.="<td style='margin:10px;'>&nbsp;&nbsp;$total</td>";
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

    function notify_user($message) {
        $list = "";
        $list.=$this->send_notification_message($message);
        return $list;
    }

    function send_instructor_report($tutor_report) {
        if ($tutor_report['tutor'] != '' && $tutor_report['report'] != '') {
            $user = $this->get_user_data($tutor_report['tutor']);
            $message = $tutor_report['report'];
            $email = $user->email;
            echo "Tutor email: " . $email . "<br>";
            $email2 = 'sirromas@gmail.com'; // copy for me

            $mail = new PHPMailer;
            $mail->isSMTP();
            $mail->Host = $this->mail_smtp_host;
            $mail->SMTPAuth = true;
            $mail->Username = $this->mail_smtp_user;
            $mail->Password = $this->mail_smtp_pwd;
            $mail->SMTPSecure = 'tls';
            $mail->Port = $this->mail_smtp_port;

            $mail->setFrom($this->mail_smtp_user, 'Medical2 Career College');
            $mail->addAddress($email);
            $mail->addAddress($email2);
            $mail->addReplyTo($this->mail_smtp_user, 'Medical2 Career College');

            $mail->isHTML(true);

            $mail->Subject = 'Workshop students';
            $mail->Body = $message;

            if (!$mail->send()) {
                echo "Message could not be sent to $email \n";
                echo 'Mailer Error: ' . $mail->ErrorInfo . "\n";
            } // end if !$mail->send()        
            else {
                echo "Message has been sent to ' . $email \n";
            }
        } // end if $tutor_report['tutor']!='' && $tutor_report['report']!=''
        else {
            echo "<br>There are no instructors at current workshop<br>";
        }
    }

    function send_notification_message($message) {

        if ($message != '') {
            $a_email = 'a1b1c777@gmail.com';
            $b_email = 'donnasteele7817@gmail.com';
            $m_email = 'sirromas@gmail.com';
            $mail = new PHPMailer;
            $mail->isSMTP();
            $mail->Host = $this->mail_smtp_host;
            $mail->SMTPAuth = true;
            $mail->Username = $this->mail_smtp_user;
            $mail->Password = $this->mail_smtp_pwd;
            $mail->SMTPSecure = 'tls';
            $mail->Port = $this->mail_smtp_port;

            $mail->setFrom($this->mail_smtp_user, 'Medical2 Career College');
            $mail->addAddress($a_email);
            $mail->addAddress($b_email);
            $mail->addAddress($m_email);
            $mail->addReplyTo($this->mail_smtp_user, 'Medical2 Career College');

            $mail->isHTML(true);

            $mail->Subject = 'Workshop owe students';
            $mail->Body = $message;

            if (!$mail->send()) {
                echo "Message could not be sent to $a_email \n";
                echo "Message could not be sent to $b_email \n";
                echo "Message could not be sent to $m_email \n";
                echo 'Mailer Error: ' . $mail->ErrorInfo . "\n";
            } // end if !$mail->send()        
            else {
                echo "Message has been sent to ' . $a_email \n";
                echo "Message could not be sent to $b_email \n";
                echo "Message has been sent to ' . $m_email \n";
            }
        } // end if $message!=''
        else {
            echo "There no workshop students in near 24h ... <br>";
        }
    }

    /*     * ************* Process Campaign emails functionality **************** */

    function get_campaign_content($id) {
        $query = "select * from mdl_campaign where id=$id";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $content = $row['content'];
        }
        return $content;
    }

    function update_campaign_status($camid) {

        $query = "select count(id) as total "
                . "from mdl_campaign_log "
                . "where camid=$camid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $total = $row['total'];
        }

        $query = "select count(id) as total "
                . "from mdl_campaign_log "
                . "where camid=$camid and status='ok'";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $processed = $row['total'];
        }



        if ($processed < $total) {
            $query = "update mdl_campaign "
                    . "set processed=$processed where id=$camid";
        } else {
            $query = "update mdl_campaign "
                    . "set processed=$processed , "
                    . "status='finished' "
                    . "where id=$camid";
        }
        $this->db->query($query);
    }

    function get_user_message($detailes, $content) {
        $list = "";
        $list.="<html>";
        $list.="<body>";
        $list.="<p align='center'>Dear " . ucfirst($detailes->firstname) . "&nbsp;" . ucfirst($detailes->lastname) . "!</p>";
        $list.="<p align='justify'>$content</p>";
        $list.="<p align='justify'>Best regards,</p>";
        $list.="<p align='justify'>Mediacl2 team.</p>";
        $list.="</body>";
        $list.="</html>";
        return $list;
    }

    function send_email($camid, $userid) {
        $content = $this->get_campaign_content($camid);
        $user_details = $this->get_user_data($userid);
        //$user_details->email = "sirromas@gmail.com"; // temp workaroud 
        $message = $this->get_user_message($user_details, $content);
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->Host = $this->mail_smtp_host;
        $mail->SMTPAuth = true;
        $mail->Username = $this->mail_smtp_user;
        $mail->Password = $this->mail_smtp_pwd;
        $mail->SMTPSecure = 'tls';
        $mail->Port = $this->mail_smtp_port;
        $mail->setFrom($this->mail_smtp_user, 'Medical2 Career College');
        $mail->addAddress($user_details->email);
        $mail->addReplyTo($this->mail_smtp_user, 'Medical2 Career College');
        $mail->isHTML(true);
        $mail->Subject = 'Medical2 Career College';
        $mail->Body = $message;
        if (!$mail->send()) {
            $query = "update mdl_campaign_log "
                    . "set status='failed' "
                    . "where camid=$camid "
                    . "and userid=$userid";
            $this->db->query($query);
        } // end if !$mail->send()        
        else {
            $query = "update mdl_campaign_log "
                    . "set status='ok' "
                    . "where camid=$camid "
                    . "and userid=$userid";
            $this->db->query($query);
            $this->update_campaign_status($camid);
        } // end else        
    }

    function process_emails() {
        $query = "select * from mdl_campaign_log "
                . "where status='pending' "
                . "order by dated desc limit 0,1";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $camid = $row['camid'];
                $userid = $row['userid'];
            } // end while
            $this->send_email($camid, $userid);
        } // end if $num > 0
    }

}
