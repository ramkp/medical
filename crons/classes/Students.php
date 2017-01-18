<?php

ini_set('memory_limit', '1024M'); // or you could use 1G
//require_once ('/home/cnausa/public_html/class.pdo.database.php');
require_once ('/home/cnausa/public_html/functionality/php/classes/mailer/vendor/PHPMailerAutoload.php');
require_once ('/home/cnausa/public_html/lms/custom/certificates/classes/Renew.php');

class Students {

    public $db;
    public $mail_smtp_host = 'mail.medical2.com';
    public $mail_smtp_port = 25;
    public $mail_smtp_user = 'info@medical2.com';
    public $mail_smtp_pwd = 'aK6SKymc';
    public $contextlevel = 50;
    public $phleb_courseid = 57;
    public $obs_courseid = 49;
    public $threrapy_courseid = 47;
    public $therapy_quizid = 103;
    public $obs_quizid = 102;
    public $phleb_quizid = 101;
    public $passing_grade = 75;
    public $exam_module = 24;

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

    function get_renew_fee($courseid) {
        $renew = new Renew();
        $amount = $renew->get_renew_amount($courseid);
        return $amount;
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
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $user_payment = $row['psum'];
                $course_cost = $this->get_course_cost($row['courseid']);
                $renew_fee = $this->get_renew_fee($row['courseid']);
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
            $renew_fee = $this->get_renew_fee($courseid);
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                // Weird workaround for only three students
                if ($row['userid'] == 13325 || $row['userid'] == 13326 || $row['userid'] == 13327) {
                    $row['psum'] = 500; // We make their payment as $500, but in fact they paid only $450
                } // end if $userid==13325 || $userid==13326 || $userid==13326

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
            $renew_fee = $this->get_renew_fee($courseid);
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

    function get_workshop_cost($id) {
        $query = "select * from mdl_scheduler_slots where id=$id";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $cost = $row['cost'];
        }
        return $cost;
    }

    function prepare_report($workshops) {
        $list = "";
        $i = 0;

        foreach ($workshops as $ws) {

            $participants = $this->get_workshop_participants($ws);
            if (count($participants) > 0) {

                $owe_sum = 0;
                $ws_cost = $this->get_workshop_cost($ws);
                $courseid = $this->get_workshop_course($ws);
                $coursename = $this->get_course_name($courseid);
                $course_cost = $this->get_course_cost($courseid);
                $cost = ($ws_cost > 0) ? $ws_cost : $course_cost;
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

                    /*
                     * 
                      echo "<pre>";
                      print_r($cc_payments);
                      echo "/<pre><br>";

                      echo "<pre>";
                      print_r($other_payments);
                      echo "/<pre><br>";
                     * 
                     */

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
                        //$list.="</tr>";ß
                        if (($partial->cost - $total_paid) >= 0) {
                            $owe_sum = $owe_sum + round($partial->cost - $total_paid);
                        }
                        if ($owe_sum >= 0 && ($partial->cost - $total_paid) >= 0) {
                            $list.="<tr>";
                            $list.="<td>Student owes</td>";
                            $list.="<td>&nbsp;&nbsp; $" . round($partial->cost - $total_paid) . "  </td>";
                            $list.="</tr>";
                        } // end if $owe_sum>=0
                        else {
                            $list.="<tr>";
                            $list.="<td>Student owes</td>";
                            $list.="<td>&nbsp;&nbsp; $0 </td>";
                            $list.="</tr>";
                        } // end else
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
                        if ($owe_sum >= 0 && ($partial->cost - $total_paid) >= 0) {
                            $owe_sum = $owe_sum + round($partial->cost - $total_paid);
                            $list.="<tr>";
                            $list.="<td>Student owes</td>";
                            $list.="<td>&nbsp;&nbsp; $" . round($partial->cost - $total_paid) . "  </td>";
                            $list.="</tr>";
                        } // end if $owe_sum >= 0
                        else {
                            $list.="<tr>";
                            $list.="<td>Student owes</td>";
                            $list.="<td>&nbsp;&nbsp; $0</td>";
                            $list.="</tr>";
                        } // end else
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
            $b_email = 'help@medical2.com';
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
                //echo "Message could not be sent to $b_email \n";
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
        return base64_decode($content);
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

    /*     * ************Code related to financial report ******************** */

    function get_report_credit_card_payments($start, $end) {
        $payments = array();
        $query = "select * from mdl_card_payments "
                . "where refunded=0 and pdate between $start and $end";
        //echo "CC Query: " . $query . "<br>";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $payment = new stdClass();
                foreach ($row as $key => $value) {
                    $payment->$key = $value;
                } // end foreach
                $payments[] = $payment;
            } // end while
        } // end if $num > 0
        return $payments;
    }

    function get_refund_data($start, $end) {
        $payments = array();
        //$adjusted_start=$start-50000;
        $partial_payments = array();
        $query = "select * from mdl_card_payments "
                . "where refunded=1 and pdate between $start and $end";
        //echo "CC Refund Query: " . $query . "<br>";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $payment = new stdClass();
                foreach ($row as $key => $value) {
                    $payment->$key = $value;
                } // end foreach
                $payments[] = $payment;
            } // end while
        } // end if $num > 0

        $query = "select * from mdl_partial_refund_payments "
                . "where pdate between $start and $end";
        //echo "Query: ".$query."<br>";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $payment = new stdClass();
                foreach ($row as $key => $value) {
                    $payment->$key = $value;
                } // end foreach
                $partial_payments[] = $payment;
            } // end while
        } // end if $num > 0

        $all_payments = array_merge($payments, $partial_payments);
        //print_r($all_payments);
        return $all_payments;
    }

    function get_report_invoice_payments($start, $end) {
        $payments = array();
        $query = "select * from mdl_invoice "
                . "where i_status=1 and i_pdate between $start and $end";
        //echo "Invoice Query: " . $query . "<br>";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $payment = new stdClass();
                foreach ($row as $key => $value) {
                    $payment->$key = $value;
                } // end foreach
                $payments[] = $payment;
            } // end while
        } // end if $num > 0
        return $payments;
    }

    function get_report_partial_payments($start, $end) {
        $payments = array();
        $query = "select * from mdl_partial_payments "
                . "where pdate between $start and $end";
        //echo "Partial Query: " . $query . "<br>";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $payment = new stdClass();
                foreach ($row as $key => $value) {
                    $payment->$key = $value;
                } // end foreach
                $payments[] = $payment;
            } // end while
        } // end if $num > 0
        return $payments;
    }

    function prepare_financial_report($type, $start, $end, $card_payments, $invoice_payments, $parial_payments, $refund_payments) {

        //echo "<br>Type: " . $type . "<br>";
        //echo "Start: " . $start . "<br>";
        //echo "End: " . $end . "<br>";

        $list = "";
        $cc_list = "";
        $refund_list = "";
        $refund_subtotal = 0;
        $cc_subtotal = 0;
        $in_list = "";
        $in_subtotal = 0;
        $pp_list = "";
        $cash_subtotal = 0;

        switch ($type) {
            case 1:
                $stamp = time() - 86400;
                $date = date('m-d-Y', $stamp);
                $title = "<span style='font-weight:bold;'>Daily Financial Report - $date</span>";
                break;
            case 2:
                $h_start = date('m-d-Y', $start);
                $h_end = date('m-d-Y', $end);
                $title = "<span style='font-weight:bold;'>Weekly Financial Report - $h_start - $h_end</span>";
                break;
            case 3:
                $h_start = date('m-d-Y', $start);
                $h_end = date('m-d-Y', $end);
                $title = "<span style='font-weight:bold;'>Monthly Financial Report - $h_start - $h_end</span>";
                break;
            case 4:
                $title = "";
                break;
        } // end switch
        // Credit card payments
        if (count($card_payments) > 0) {
            $cc_list.="<table>";
            $cc_list.="<th>";
            $cc_list.="<td style='padding:15px;font-weight:bold;' colspan='2' align='center'>Credit Card Payments</td>";
            $cc_list.="</th>";
            foreach ($card_payments as $payment) {
                $renew_fee = $this->get_renew_fee($payment->courseid);
                $coursename = $this->get_course_name($payment->courseid);
                $date = date('m-d-Y h:i:s', ($payment->pdate - 86400));
                $userdata = $this->get_user_data($payment->userid);
                $workshop_data = $this->get_student_workshops_data($payment->userid);
                $firstname = $userdata->firstname;
                $lastname = $userdata->lastname;
                $amount = $payment->psum;
                $cc_list.="<tr>";
                if ($amount != $renew_fee) {
                    $cc_list.="<td style='padding:15px;'>Program</td><td style='padding:15px;'>$coursename</td>";
                } // end if $amount!=$renew_fee
                else {
                    $cc_list.="<td style='padding:15px;'>Program</td><td style='padding:15px;'>$coursename - Certificate Renew Payment</td>";
                }
                $cc_list.="</tr>";

                $cc_list.="<tr>";
                $cc_list.="<td style='padding:15px;'>Workshop location</td><td style='padding:15px;'>" . $workshop_data['location'] . "</td>";
                $cc_list.="</tr>";

                $cc_list.="<tr>";
                $cc_list.="<td style='padding:15px;'>Workshop date</td><td style='padding:15px;'>" . $workshop_data['date'] . "</td>";
                $cc_list.="</tr>";

                $cc_list.="<tr>";
                $cc_list.="<td style='padding:15px;'>Student</td><td style='padding:15px;'>$firstname $lastname</td>";
                $cc_list.="</tr>";
                $cc_list.="<tr>";
                $cc_list.="<td style='padding:15px;'>Amount paid:</td><td style='padding:15px;'>$$amount</td>";
                $cc_list.="</tr>";
                $cc_list.="<tr>";
                $cc_list.="<td style='padding:15px;'>Transaction date:</td><td style='padding:15px;'>$date</td>";
                $cc_list.="</tr>";
                $cc_list.="<tr>";
                $cc_list.="<td style='padding:15px;' colspan='2'><hr/></td>";
                $cc_list.="</tr>";
                $cc_subtotal = $cc_subtotal + $amount;
            } // end foreach
            $cc_list.="<tr>";
            $cc_list.="<td style='padding:15px;font-weight:bold;'>Subtotal:</td><td style='padding:15px;font-weight:bold;'>$$cc_subtotal</td>";
            $cc_list.="</tr>";
            $cc_list.="</table>";
        } // end if count($card_payments)>0

        if (count($refund_payments) > 0) {
            $refund_list.="<table>";
            $refund_list.="<th>";
            $refund_list.="<td style='padding:15px;font-weight:bold;' colspan='2' align='center'>Refund Payments</td>";
            $refund_list.="</th>";
            foreach ($refund_payments as $payment) {
                $coursename = $this->get_course_name($payment->courseid);
                $renew_fee = $this->get_renew_fee($payment->courseid);
                $date = date('m-d-Y h:i:s', ($payment->pdate - 86400));
                $userdata = $this->get_user_data($payment->userid);
                $firstname = $userdata->firstname;
                $lastname = $userdata->lastname;
                $amount = $payment->psum;
                $workshop_data = $this->get_student_workshops_data($payment->userid);

                $refund_list.="<tr>";
                $refund_list.="<td style='padding:15px;'>Program</td><td style='padding:15px;'>$coursename</td>";
                $refund_list.="</tr>";

                $refund_list.="<tr>";
                $refund_list.="<td style='padding:15px;'>Workshop location</td><td style='padding:15px;'>" . $workshop_data['location'] . "</td>";
                $refund_list.="</tr>";

                $refund_list.="<tr>";
                $refund_list.="<td style='padding:15px;'>Workshop date</td><td style='padding:15px;'>" . $workshop_data['date'] . "</td>";
                $refund_list.="</tr>";

                $refund_list.="<tr>";
                $refund_list.="<td style='padding:15px;'>Student</td><td style='padding:15px;'>$firstname $lastname</td>";
                $refund_list.="</tr>";
                $refund_list.="<tr>";
                $refund_list.="<td style='padding:15px;'>Amount paid:</td><td style='padding:15px;'>-$$amount</td>";
                $refund_list.="</tr>";
                $refund_list.="<tr>";
                $refund_list.="<td style='padding:15px;'>Transaction date:</td><td style='padding:15px;'>$date</td>";
                $refund_list.="</tr>";
                $refund_list.="<tr>";
                $refund_list.="<td style='padding:15px;' colspan='2'><hr/></td>";
                $refund_list.="</tr>";
                $refund_subtotal = $refund_subtotal + $amount;
            } // end foreach
            $refund_list.="<tr>";
            $refund_list.="<td style='padding:15px;font-weight:bold;'>Subtotal:</td><td style='padding:15px;font-weight:bold;'>-$$refund_subtotal</td>";
            $refund_list.="</tr>";
            $refund_list.="</table>";
        } // end if count($refund_payments)>0
        // Invoice payments
        if (count($invoice_payments) > 0) {
            $in_list.="<table>";
            $in_list.="<th>";
            $in_list.="<td style='padding:15px;font-weight:bold;' colspan='2' align='center'>Invoice Payments</td>";
            $in_list.="</th>";
            foreach ($invoice_payments as $payment) {
                $coursename = $this->get_course_name($payment->courseid);
                $renew_fee = $this->get_renew_fee($payment->courseid);
                $date = date('m-d-Y h:i:s', ($payment->i_pdate - 86400));
                if ($payment->userid > 0) {
                    $userdata = $this->get_user_data($payment->userid);
                    $firstname = $userdata->firstname;
                    $lastname = $userdata->lastname;
                }

                $amount = $payment->i_sum;

                if ($payment->userid > 0) {
                    $workshop_data = $this->get_student_workshops_data($payment->userid);
                }

                $in_list.="<tr>";
                if ($amount != $renew_fee) {
                    $in_list.="<td style='padding:15px;'>Program</td><td style='padding:15px;'>$coursename</td>";
                } // end if  $amount != $renew_fee
                else {
                    $in_list.="<td style='padding:15px;'>Program</td><td style='padding:15px;'>$coursename - Certificate Renew Payment</td>";
                } // end else
                $in_list.="</tr>";

                if ($payment->userid > 0) {
                    $in_list.="<tr>";
                    $in_list.="<td style='padding:15px;'>Workshop location</td><td style='padding:15px;'>" . $workshop_data['location'] . "</td>";
                    $in_list.="</tr>";

                    $in_list.="<tr>";
                    $in_list.="<td style='padding:15px;'>Workshop date</td><td style='padding:15px;'>" . $workshop_data['date'] . "</td>";
                    $in_list.="</tr>";

                    $in_list.="<tr>";
                    $in_list.="<td style='padding:15px;'>Student</td><td style='padding:15px;'>$firstname $lastname</td>";
                    $in_list.="</tr>";
                } // end if $payment->userid > 0
                else {
                    $in_list.="<tr>";
                    $in_list.="<td style='padding:15px;'>Client</td><td style='padding:15px;'>$payment->client</td>";
                    $in_list.="</tr>";
                }

                $in_list.="<tr>";
                $in_list.="<td style='padding:15px;'>Amount paid:</td><td style='padding:15px;'>$$amount</td>";
                $in_list.="</tr>";

                $in_list.="<tr>";
                $in_list.="<td style='padding:15px;'>Invoice No:</td><td style='padding:15px;'>$payment->i_num</td>";
                $in_list.="</tr>";

                $in_list.="<tr>";
                $in_list.="<td style='padding:15px;'>Payment date:</td><td style='padding:15px;'>$date</td>";
                $in_list.="</tr>";

                $in_list.="<tr>";
                $in_list.="<td style='padding:15px;' colspan='2'><hr/></td>";
                $in_list.="</tr>";
                $in_subtotal = $in_subtotal + $amount;
            } // end foreach
            $in_list.="<tr>";
            $in_list.="<td style='padding:15px;font-weight:bold;'>Subtotal:</td><td style='padding:15px;font-weight:bold;'>$$in_subtotal</td>";
            $in_list.="</tr>";
            $in_list.="</table>";
        } // end if count($invoice_payments)>0
        // Partial/Cash payments
        if (count($parial_payments) > 0) {
            $pp_list.="<table>";
            $pp_list.="<th>";
            $pp_list.="<td style='padding:15px;font-weight:bold;' colspan='2' align='center'>Cash/Partial Payments</td>";
            $pp_list.="</th>";
            foreach ($parial_payments as $payment) {

                /*
                 * 
                  echo "<br><pre>";
                  print_r($payment);
                  echo "</pre><br>";
                 * 
                 */

                $coursename = $this->get_course_name($payment->courseid);
                $date = date('m-d-Y h:i:s', ($payment->pdate - 86400));
                $userdata = $this->get_user_data($payment->userid);
                $firstname = $userdata->firstname;
                $lastname = $userdata->lastname;
                $amount = $payment->psum;
                $workshop_data = $this->get_student_workshops_data($payment->userid);

                $pp_list.="<tr>";
                if ($amount != $renew_fee) {
                    $pp_list.="<td style='padding:15px;'>Program</td><td style='padding:15px;'>$coursename</td>";
                } // end if $amount != $renew_fee
                else {
                    $pp_list.="<td style='padding:15px;'>Program</td><td style='padding:15px;'>$coursename - Certificate Renew Payment</td>";
                } // end else
                $pp_list.="</tr>";

                $pp_list.="<tr>";
                $pp_list.="<td style='padding:15px;'>Workshop location</td><td style='padding:15px;'>" . $workshop_data['location'] . "</td>";
                $pp_list.="</tr>";

                $pp_list.="<tr>";
                $pp_list.="<td style='padding:15px;'>Workshop date</td><td style='padding:15px;'>" . $workshop_data['date'] . "</td>";
                $pp_list.="</tr>";

                $pp_list.="<tr>";
                $pp_list.="<td style='padding:15px;'>Student</td><td style='padding:15px;'>$firstname $lastname</td>";
                $pp_list.="</tr>";
                $pp_list.="<tr>";
                $pp_list.="<td style='padding:15px;'>Amount paid:</td><td style='padding:15px;'>$$amount</td>";
                $pp_list.="</tr>";
                $pp_list.="<tr>";
                $pp_list.="<td style='padding:15px;'>Transaction date:</td><td style='padding:15px;'>$date</td>";
                $pp_list.="</tr>";
                $pp_list.="<tr>";
                $pp_list.="<td style='padding:15px;' colspan='2'><hr/></td>";
                $pp_list.="</tr>";
                $cash_subtotal = $cash_subtotal + $amount;
            } // end foreach
            $pp_list.="<tr>";
            $pp_list.="<td style='padding:15px;font-weight:bold;'>Subtotal:</td><td style='padding:15px;font-weight:bold;'>$$cash_subtotal</td>";
            $pp_list.="</tr>";
            $pp_list.="</table>";
        } // end if count($parial_payments)>0


        $total = $cc_subtotal + $in_subtotal + $cash_subtotal;
        $list.="<table>";
        $list.="<tr>";
        $list.="<td align='center'>$title</td>";
        $list.="</tr>";
        $list.="<tr>";
        $list.="<td>$cc_list</td>";
        $list.="</tr>";
        $list.="<tr>";
        $list.="<td>$refund_list</td>";
        $list.="</tr>";
        $list.="<tr>";
        $list.="<td>$in_list</td>";
        $list.="</tr>";
        $list.="<tr>";
        $list.="<td>$pp_list</td>";
        $list.="</tr>";
        $list.="<tr>";
        $list.="<td style='padding:15px;font-weight:bold;'>Grand total &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $$total</td>";
        $list.="</tr>";

        $list.="</table>";
        return $list;
    }

    function get_student_workshops_data($userid) {

        $query = "select * from mdl_scheduler_appointment where studentid=$userid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $slotid = $row['slotid'];
            }
            $query = "select * from mdl_scheduler_slots where id=$slotid";
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $location = $row['appointmentlocation'];
                $date = date('m-d-Y', $row['starttime']);
            }
            $data = array('location' => $location, 'date' => $date);
        } // end if $num > 0
        else {
            $data = array('location' => 'N/A', 'date' => 'N/A');
        }
        return $data;
    }

    function get_report_payments($type) {
        $list = "";

        switch ($type) {
            case 1:
                // Daily report
                $timestamp = time() - 86400;
                //echo "Date to be reported: " . date('m-d-Y', $timestamp)."<br>";
                echo "Current server time: " . date('m-d-Y h:i:s A', time()) . "<br>";
                $start = strtotime("midnight", $timestamp);
                $end = strtotime("tomorrow", $start) - 1;

                echo "Start moment: " . date('m-d-Y h:i:s', $start) . "<br>";
                echo "End moment: " . date('m-d-Y h:i:s', $end) . "<br>";

                $card_payments = $this->get_report_credit_card_payments($start, $end);
                $refund_payments = $this->get_refund_data($start, $end);
                $invoice_payments = $this->get_report_invoice_payments($start, $end);
                $parial_payments = $this->get_report_partial_payments($start, $end);
                $list.=$this->prepare_financial_report($type, $start, $end, $card_payments, $invoice_payments, $parial_payments, $refund_payments);
                $this->send_financial_report($type, $timestamp, $timestamp, $list);
                break;
            case 2:
                // Weekly report
                $start = new DateTime('last sunday');
                $end = new DateTime('this sunday');

                echo "<br><pre>";
                print_r($start);
                echo "</pre><br>";
                echo "<br><pre>";
                print_r($end);
                echo "</pre><br>";

                $card_payments = $this->get_report_credit_card_payments(strtotime($start->date), strtotime($end->date));
                $invoice_payments = $this->get_report_invoice_payments(strtotime($start->date), strtotime($end->date));
                $refund_payments = $this->get_refund_data(strtotime($start->date), strtotime($end->date));
                $parial_payments = $this->get_report_partial_payments(strtotime($start->date), strtotime($end->date));
                $list.=$this->prepare_financial_report($type, strtotime($start->date), strtotime($end->date), $card_payments, $invoice_payments, $parial_payments, $refund_payments);
                $this->send_financial_report($type, strtotime($start->date), strtotime($end->date), $list);
                break;
            case 3:
                // Montly report
                $start = new DateTime('first day of this month');
                $end = new DateTime('last day of this month');

                echo "<br><pre>";
                print_r($start);
                echo "</pre><br>";
                echo "<br><pre>";
                print_r($end);
                echo "</pre><br>";

                $card_payments = $this->get_report_credit_card_payments(strtotime($start->date), strtotime($end->date));
                $invoice_payments = $this->get_report_invoice_payments(strtotime($start->date), strtotime($end->date));
                $refund_payments = $this->get_refund_data(strtotime($start->date), strtotime($end->date));
                $parial_payments = $this->get_report_partial_payments(strtotime($start->date), strtotime($end->date));
                $list.=$this->prepare_financial_report($type, strtotime($start->date), strtotime($end->date), $card_payments, $invoice_payments, $parial_payments, $refund_payments);
                $this->send_financial_report($type, strtotime($start->date), strtotime($end->date), $list);
                break;
            case 4:
                // Year report
                break;
        }


        return $list;
    }

    function send_financial_report($type, $start, $end, $message) {

        switch ($type) {
            case 1:
                $stamp = time() - 86400;
                $date = date('m-d-Y', $stamp);
                $title = "Daily Financial Report - $date";
                break;
            case 2:
                $h_start = date('m-d-Y', $start);
                $h_end = date('m-d-Y', $end);
                $title = "Weekly Financial Report - $h_start - $h_end";
                break;
            case 3:
                $h_start = date('m-d-Y', $start);
                $h_end = date('m-d-Y', $end);
                $title = "Monthly Financial Report - $h_start - $h_end";
                break;
            case 4:
                $title = "";
                break;
        } // end switch

        $a_email = 'a1b1c777@gmail.com';
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
        $mail->addAddress($m_email);
        $mail->addReplyTo($this->mail_smtp_user, 'Medical2 Career College');

        $mail->isHTML(true);

        $mail->Subject = $title;
        $mail->Body = $message;

        if (!$mail->send()) {
            echo "Message could not be sent to $a_email \n";
            echo "Message could not be sent to $m_email \n";
            echo 'Mailer Error: ' . $mail->ErrorInfo . "\n";
        } // end if !$mail->send()        
        else {
            echo "Message has been sent to ' . $a_email \n";
            echo "Message has been sent to ' . $m_email \n";
        }
    }

    /* Code related to exam passed students */

    function get_users_grades($id) {
        $grades = array();
        if ($id > 0) {
            $query = "SELECT * FROM `mdl_grade_grades` WHERE itemid =$id "
                    . "AND rawgrade IS NOT NULL and processed=0";
        } // end if 
        else {
            $query = "SELECT * FROM `mdl_grade_grades` "
                    . "WHERE rawgrade IS NOT NULL and processed=0";
        } // end else
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $grade = new stdClass();
                foreach ($row as $key => $value) {
                    $grade->$key = $value;
                } // end foreach
                $grades[] = $grade;
            } // end while
        } // end if $num > 0
        return $grades;
    }

    function make_students_course_course_completed($courseid, $userid) {
        if ($courseid != '' && $userid != '') {
            $query = "select * from mdl_course_completions "
                    . "where course=$courseid "
                    . "and userid=$userid";
            $num = $this->db->numrows($query);
            $userdata = $this->get_user_data($userid);
            if ($num > 0) {
                echo "$userdata->firstname $userdata->lastname" . " already exists in course completion table <br>";
            } // end if $num > 0
            else {
                $date = time();
                $query = "insert into mdl_course_completions "
                        . "(userid,"
                        . "course,"
                        . "timeenrolled,"
                        . "timecompleted) "
                        . "values ($userid,$courseid,$date,$date)";
                $this->db->query($query);
                echo "$userdata->firstname $userdata->lastname" . " was added into course completion table <br>";
            }
        } // end if $courseid!='' && $userid!=''
    }

    function send_exam_passed_notification($users) {
        $a_email = 'help@medical2.com';
        $b_email = 'a1b1c777@gmail.com';
        $m_email = 'sirromas@gmail.com';

        if (count($users) > 0) {

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

            $message = "<html>";
            $message.="<body>";
            $message.="<br><br><table align='center'>";
            $message.="<tr style='text-align:left;'>";
            $message.="<th style='padding:5px;'>Student</th>";
            $message.="<th style='padding:5px;'>Progam name</th>";
            $message.="<th style='padding:5px;' >Grade %</th>";
            $message.="<th style='padding:5px;'>Exam date</th>";
            $message.="</tr>";

            foreach ($users as $user) {
                $userdata = $this->get_user_data($user->userid);
                $coursename = $this->get_course_name($user->courseid);
                $date = date('m-d-Y h:i:s', $user->date);
                $message.="<tr>";
                $message.="<td style='padding:5px;'>$userdata->firstname $userdata->lastname<br>$userdata->phone1<br>$userdata->email<br>$userdata->address<br>$userdata->state<br>$userdata->city,$userdata->zip</td>";
                $message.="<td style='padding:5px;'>$coursename<br>$user->itemname</td>";
                $message.="<td style='padding:5px;'>$user->grade</td>";
                $message.="<td style='padding:5px;'>$date</td>";
                $message.="</tr>";
                $message.="<tr>";
                $message.="<td style='padding:5px;' colspan='4'><hr/></td>";
                $message.="</tr>";
            } // end foreach
            $message.="</table>";
            $message.="</body>";
            $message.="</html>";

            $mail->Subject = 'Medical2 Career College - students taken assignment/exam/quiz/test';
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
        } // end if (count($users)>0) {
    }

    function get_course_id_by_quiz_id($id) {
        $query = "select * from mdl_grade_items where id=$id";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $courseid = $row['courseid'];
        }
        return $courseid;
    }

    function get_grade_item_name($id) {
        $query = "select * from mdl_grade_items where id=$id";
        //echo 'Query: ' . $query . "<br>";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $name = $row['itemname'];
        }
        return $name;
    }

    function mark_student_grade_item_as_processed($id) {
        $query = "update mdl_grade_grades set processed='1' where id=$id ";
        $this->db->query($query);
    }

    function is_exam_item($itemid) {
        $query = "select * from mdl_course_modules where id=$itemid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $module = $row['module'];
        }
        $status = ($module == $this->exam_module) ? 1 : 0;
        return $status;
    }

    function get_course_category($id) {
        $query = "select * from mdl_course where id=$id";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $cat = $row['category'];
        }
        return $cat;
    }

    function check_exam_students() {
        $exam_users = $this->get_users_grades(0);
        if (count($exam_users) > 0) {
            $taken_exam_users = array();
            echo "<br>..... Users who taken quiz/exam  .....<br>";
            foreach ($exam_users as $user) {
                $courseid = $this->get_course_id_by_quiz_id($user->itemid);
                $itemname = $this->get_grade_item_name($user->itemid);
                if ($courseid > 0 && $user->timemodified != '') {
                    $category = $this->get_course_category($courseid);
                    $taken_user = new stdClass();
                    $taken_user->userid = $user->userid;
                    $taken_user->courseid = $courseid;
                    $taken_user->itemname = $itemname;
                    $taken_user->grade = $user->rawgrade;
                    $taken_user->date = $user->timemodified;
                    $taken_exam_users[$user->timecreated] = $taken_user;
                    $this->mark_student_grade_item_as_processed($user->id);
                    $exam_status = $this->is_exam_item($user->itemid);
                    $pass = $this->is_course_autopass($courseid);
                    if (($user->rawgrade >= $this->passing_grade && $exam_status == 1 && $pass == 1) || ($user->rawgrade >= $this->passing_grade && $category == 4)) {
                        $this->make_students_course_course_completed($courseid, $user->userid);
                    } // end if $user->rawgrade>=$this->passing_grade
                } // end if $courseid>0
            } // end foreach
            ksort($taken_exam_users);
            $this->send_exam_passed_notification($taken_exam_users);
        } // end if count($exam_users
        else {
            echo "There are no users taken exam recently .... <br>";
        }
    }

    function is_course_autopass($id) {
        $query = "select * from mdl_course where id=$id";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $pass = $row['autopass'];
        }
        return $pass;
    }

    /*     * ************* Code related to typehead json data ***************** */

    function create_typehead_data() {
        $courses = array();
        $firstname = array();
        $lastname = array();
        $emails = array();
        $users = array();
        $phones = array();

        $query = "select * from mdl_course where visible=1";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $courses[] = mb_convert_encoding($row['fullname'], 'UTF-8');
        }

        $query = "select * from mdl_user where deleted=0";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $lastname[] = mb_convert_encoding(trim($row['lastname']), 'UTF-8');
            $firstname[] = mb_convert_encoding(trim($row['firstname']), 'UTF-8');
            $users[] = mb_convert_encoding($row['lastname'], 'UTF-8') . " " . mb_convert_encoding($row['firstname'], 'UTF-8');
            $emails[] = mb_convert_encoding($row['email'], 'UTF-8');
            $phones[] = mb_convert_encoding($row['phone1'], 'UTF-8');
        }

        $data = array_merge($users, $emails, $courses, $phones);
        file_put_contents('/home/cnausa/public_html/lms/custom/utils/data.json', json_encode($data));
        echo "Data are created \n";
    }

    function create_ws_json_data() {
        $ws = array();
        $now = time();
        $query = "select * from mdl_scheduler_slots "
                . "where  starttime>=$now order by starttime";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $location = mb_convert_encoding($row['appointmentlocation'], 'UTF-8');
            $date = mb_convert_encoding(date('m-d-Y', trim($row['starttime'])), 'UTF-8');
            $ws[] = $date . "--" . $location;
        }
        file_put_contents('/home/cnausa/public_html/lms/custom/utils/workshops.json', json_encode($ws));
        echo "Total items: " . count($ws);
        echo "<p>Workshop data are created ...</p>";
    }

    /*     * ******* Code related to Certificates expiration messages ******** */

    function get_certificate_reminder_message($user_data) {
        $list = "";

        $list.="<p style='align:left;font-size:23px;font-weight:bold;'>Its Time To Renew your Certification!</p> 

				<p style='align:center;font-size:25px;font-weight:bold;color:red;'>Medical2 Certification Agency</p>
 			
    			<p style='font-size:15px;font-wieght:bold;'>Toll-Free: 1-877-741-1996 Fax: 407-233-1192 E-mail: <a href='mailto:help@medical2.com'>help@medical2.com</a></p> 

			<p align='justify' style='font-size:15px;'>
			You have received this notice because your Certification is about to expire. 
    	    In order to remain certified, you must renew your certification. To qualify for 
    	    recertification, you must login to your account and click on recertification tab 
    		and follow the steps or send in your $50 renewal fee no later than 30 days past 
    		your expiration date. If you do not meet this deadline, the fee will increase 
    	    to $75. After 90 days, the fee will be $100. Additionally, if your certification 
    	    is over 90 days expired, you must submit documentation verifying that you are 
    	    current in the respective field. If you are not current in the field, then you 
    	    will be required to retake the Workshop or the Online Exam. For any questions 
    	    please contact us. 
    		</p>	
    		<hr>	
			<p style='font-weight:bold;font-size:15px;'><span style='color:red;'>Make money orders out to</span> Medical2 Inc.</p> 
			<hr>
                        <p style='align:left;font-weight:bold;font-size:15px;'><a href='https://medical2.com/index.php/payments/index/$user_data->id/$user_data->courseid/0/50/1' target='_blank'>$50 Recertification Fee </a></p>
			<p style='align:left;font-weight:bold;font-size:15px;'><a href='https://medical2.com/index.php/payments/index/$user_data->id/$user_data->courseid/0/75/1' target='_blank'>$75 Recertification Fee (Over 30 Days Expired)</a></p>
                        <p style='align:left;font-weight:bold;font-size:15px;'><a href='https://medical2.com/index.php/payments/index/$user_data->id/$user_data->courseid/0/100/1' target='_blank'>$100 Recertification Fee (Over 90 Days Expired)</a></p>

		    <p style='align:left;font-size:15px;font-weight:bold;color:red;'>Mailing Address: Medical2 Inc.  1830A North Gloster St, Tupelo, MS 38804</p>";

        return $list;
    }

    function get_expired_certificates($interval) {
        $now = time();
        $i = 0;
        $exp_date = time() + $interval;

        if ($interval == 'm') {
            $start = time() + 1209600; // 2 weeks later
            $end = time() + 2592000; // one month later
        }

        if ($interval == 'w') {
            $start = time(); // now
            $end = time() + 604800;  // 7 days later
        }

        $query = "select * from mdl_certificates 
    	where expiration_date between $start and $end";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $user_data = $this->get_user_data($row['userid']);
                $user_data->courseid = $row['courseid'];
                $coursename = $this->get_course_name($row['courseid']);
                $this->send_certificate_expiration_data($user_data, $coursename);
                $i++;
            } // end while
        } // end if $num > 0 
        echo "Total users: " . $i . "<br>";
    }

    function send_certificate_expiration_data($user_data, $coursename) {


        echo "$user_data->firstname $user_data->lastname $user_data->email <br>";
        echo "Coursename: $coursename";


        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->Host = $this->mail_smtp_host;
        $mail->SMTPAuth = true;
        $mail->Username = $this->mail_smtp_user;
        $mail->Password = $this->mail_smtp_pwd;
        $mail->SMTPSecure = 'tls';
        $mail->Port = $this->mail_smtp_port;
        $mail->setFrom($this->mail_smtp_user, 'Medical2 Career College');
        $mail->addAddress($user_data->email);
        $mail->addAddress('sirromas@gmail.com');
        $mail->addReplyTo($this->mail_smtp_user, 'Medical2 Career College');
        $mail->isHTML(true);
        $mail->Subject = 'Renew certification';
        $mail->Body = $this->get_certificate_reminder_message($user_data);
        if (!$mail->send()) {
            echo "<br>Error sending email ($user_data->email) .... <br>\n";
            echo "<br>-------------------------------------------------------------------<br>";
        } else {
            echo "<br>Email was delivered ($user_data->email) <br>\n";
            echo "<br>-------------------------------------------------------------------<br>";
        }
    }

    /*     * ******* Code related to mysqldump backup ******** */

    function backup_tables($host, $user, $pass, $name, $tables = '*') {

        $link = mysql_connect($host, $user, $pass);
        mysql_select_db($name, $link);

        //get all of the tables
        if ($tables == '*') {
            $tables = array();
            $result = mysql_query('SHOW TABLES');
            while ($row = mysql_fetch_row($result)) {
                $tables[] = $row[0];
            }
        } else {
            $tables = is_array($tables) ? $tables : explode(',', $tables);
        }

        //cycle through
        foreach ($tables as $table) {
            $result = mysql_query('SELECT * FROM ' . $table);
            $num_fields = mysql_num_fields($result);

            $return.= 'DROP TABLE ' . $table . ';';
            $row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE ' . $table));
            $return.= "\n\n" . $row2[1] . ";\n\n";

            for ($i = 0; $i < $num_fields; $i++) {
                while ($row = mysql_fetch_row($result)) {
                    $return.= 'INSERT INTO ' . $table . ' VALUES(';
                    for ($j = 0; $j < $num_fields; $j++) {
                        $row[$j] = addslashes($row[$j]);
                        $row[$j] = ereg_replace("\n", "\\n", $row[$j]);
                        if (isset($row[$j])) {
                            $return.= '"' . $row[$j] . '"';
                        } else {
                            $return.= '""';
                        }
                        if ($j < ($num_fields - 1)) {
                            $return.= ',';
                        }
                    }
                    $return.= ");\n";
                }
            }
            $return.="\n\n\n";
        }

        //save file
        $date = date('Y-m-d h:i:s', time());
        $handle = fopen('/home/cnausa/public_html/crons/DB/db-backup-' . $date . '-' . (md5(implode(',', $tables))) . '.sql', 'w+');
        fwrite($handle, $return);
        fclose($handle);
        echo "$date backup is created \n";
    }

}

// end of class
