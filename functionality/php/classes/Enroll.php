<?php

/**
 * Description of Enroll
 *
 * @author sirromas
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/class.pdo.database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/Mailer.php';

class Enroll {

    public $db;
    public $student_role = 5;
    public $signup_url;

    function __construct() {
        $this->db = new pdo_db();
        $this->signup_url = 'https://' . $_SERVER['SERVER_NAME'] . '/lms/login/my_signup.php';
    }

    function get_password($length = 8) {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
    }

    function getUserId($email) {
        $query = "select id from mdl_user where username='" . $email . "'";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $userid = $row['id'];
        }
        return $userid;
    }

    function getCourseContext($courseid) {
        $query = "select id from mdl_context
                     where contextlevel=50
                     and instanceid='" . $courseid . "' ";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $contextid = $row['id'];
        }
        return $contextid;
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

    function assign_roles($userid, $courseid) {
        $roleid = $this->student_role;
        $enrolid = $this->getEnrolId($courseid);
        $contextid = $this->getCourseContext($courseid, $roleid);

        // 1. Insert into mdl_user_enrolments table
        $query = "insert into mdl_user_enrolments
             (enrolid,
              userid,
              timestart,
              modifierid,
              timecreated,
              timemodified)
               values ('" . $enrolid . "',
                       '" . $userid . "',
                        '" . time() . "',   
                        '2',
                         '" . time() . "',
                         '" . time() . "')";
        //echo "Query: ".$query."<br/>";
        $this->db->query($query);

        // 2. Insert into mdl_role_assignments table
        $query = "insert into mdl_role_assignments
                  (roleid,
                   contextid,
                   userid,
                   timemodified,
                   modifierid)                   
                   values ('" . $roleid . "',
                           '" . $contextid . "',
                           '" . $userid . "',
                           '" . time() . "',
                            '2'         )";
        // echo "Query: ".$query."<br/>";
        $this->db->query($query);
    }

    function get_country_code($country) {
        $query = "select * from mdl_countries where name='$country'";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $code = $row['code'];
        }
        return $code;
    }

    function get_state_name($id) {

        if (is_numeric($id)) {
            $query = "select * from mdl_states where id=$id";
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $state = $row['state'];
            }
        } // end if !is_nan($id)
        else {
            $state = $id;
        }
        return $state;
    }

    function get_country_name($id) {
        $query = "select * from mdl_countries where id=$id";
        //echo "Query: " . $query . "<br>";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $code = $row['code'];
        }
        return $code;
    }

    function single_signup($user) {
        $user->state = $this->get_state_name($user->state);
        if (is_numeric($user->country)) {
            $user->country = $this->get_country_name($user->country);
        } //end if is_number($user->country)

        $list = "";
        $user->pwd = $this->get_password();
        if ($user->country != '' && $user->country != 'US') {
            $user->country = $this->get_country_code($user->country);
        } // end if $user->country!=''
        else {
            $user->country = 'US';
        } // end else

        $encoded_user = base64_encode(json_encode($user));
        $data = array('user' => $encoded_user);

        // 1. Signup user into moodle    
        $options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data),
            ),
        );

        $context = stream_context_create($options);
        $response = @file_get_contents($this->signup_url, false, $context);
        //$response = file_get_contents($this->signup_url, false, $context);
        //print_r($response);

        if ($response !== false) {
            // 2. Enroll user into course
            $this->enroll_user_to_course($user);
        }  // end if $response !== false        
        else {
            $list.="<div class='container-fluid'>";            
            $list.="<span class='span9'>Signup error happened </span>";
            $list.="</div>";
            echo $list;
            die();
        }
    }

    function update_user_data($userid, $user) {
        $query = "update mdl_user "
                . "set purepwd='$user->pwd' ,"
                . "slotid='$user->slotid' ,"
                . "address='$user->addr', "
                . "business='$user->inst', "
                . "zip='$user->zip', "
                . "city='$user->city', "
                . "phone1='$user->phone', "
                . "state='$user->state', "
                . "come_from='$user->come_from' "
                . "where id=$userid";
        $this->db->query($query);
    }

    function send_confirmation_email($user) {
        $mailer = new Mailer();
        $mailer->send_account_confirmation_message($user);
    }

    function add_user_to_course_schedule($userid, $user) {
        if ($user->slotid > 0) {
            $query = "select * from mdl_scheduler_appointment "
                    . "where slotid=$user->slotid "
                    . "and studentid=$userid";
            $num = $this->db->numrows($query);
            if ($num == 0) {
                $query = "insert into mdl_scheduler_appointment "
                        . "(slotid,"
                        . "studentid,"
                        . "attended) values ($user->slotid,$userid,0)";
                //echo "Query: ".$query."<br>";
                $this->db->query($query);
            } // end if $num==0
        } // end if $user->slotid>0
    }

    function update_slots_table($courseid, $userid, $slotid) {
        $query = "insert into mdl_slots "
                . "(slotid,"
                . "courseid,"
                . "userid) "
                . "values($slotid,"
                . "$courseid,"
                . "$userid)";
        $this->db->query($query);
    }

    function enroll_user_to_course($user) {
        $userid = $this->getUserId($user->email);
        $this->assign_roles($userid, $user->courseid);
        $this->update_user_data($userid, $user);
        //$this->add_user_to_course_schedule($userid, $user);
        $this->update_slots_table($user->courseid, $userid, $user->slotid);
        $user->userid = $userid;
        $this->send_confirmation_email($user);
    }

    function group_signup($users) {
        foreach ($users as $user) {
            $this->single_signup($user);
        }
    }

    function create_course_group($courseid, $groupname) {
        $query = "insert into mdl_groups
                     (courseid,
                      idnumber,
                      name,  
                      description,
                      descriptionformat,  
                      timecreated,
                      timemodified)
                      values ('" . $courseid . "',
                              'GP',
                              '" . $groupname . "',
                              '" . $groupname . "',
                              '1',    
                              '" . time() . "',
                              '" . time() . "')";
        $this->db->query($query);

        $query = "select id, name from mdl_groups where name='$groupname'";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $groupid = $row['id'];
        } // end while
        return $groupid;
    }

    function add_user_to_group($groupid, $userid) {
        $query = "insert into mdl_groups_members  (groupid,userid,timeadded)"
                . " values ('" . $groupid . "' , '" . $userid . "' ,'" . time() . "')";
        $this->db->query($query);
    }

    function is_email_exists($email) {
        $query = "select id, username, deleted "
                . "from mdl_user where username='$email' and deleted=0";
        return $this->db->numrows($query);
    }

}
