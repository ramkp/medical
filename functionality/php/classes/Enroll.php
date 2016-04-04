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
		$this->signup_url = 'http://' . $_SERVER['SERVER_NAME'] . '/lms/login/my_signup.php';
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

	function single_signup($user) {
		//echo "<br>---Enroll user----<br>";
		//print_r($user);
		//echo "<br>-------------------<br>";
		 
		$user->pwd = $this->get_password();
		//echo "Provided country: " . $user->country . "<br/>";
		if ($user->country != '' && $user->country != 'US') {
			$user->country = $this->get_country_code($user->country);
		} // end if $user->country!=''
		else {
			$user->country = 'US';
		} // end else
		//echo "<br/>User country:" . $user->country . "<br/>";
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
		$response = file_get_contents($this->signup_url, false, $context);
		
		//echo "<pre>";
		//print_r($response);
		//echo "<pre>";
		//die();

		if ($response!==false) {
			// 2. Enroll user into course
			$this->enroll_user_to_course($user);
		}
		else {
			echo "Signup error happened ";
		}	


	}

	function update_user_data($userid, $user) {
		$query = "update mdl_user "
		. "set purepwd='$user->pwd' ,"
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

	function enroll_user_to_course($user) {
		$userid = $this->getUserId($user->email);
		$this->assign_roles($userid, $user->courseid);
		$this->update_user_data($userid, $user);
		$this->send_confirmation_email($user);
	}

	function group_signup($users) {
		foreach ($users as $user) {
			$this->single_signup($user);
		}
	}

	function prepare_users_data_from_file() {
		$file = $_SESSION['file_path'];
		$users = array();
		$handle = fopen($file, "r");
		if ($handle) {
			while (($line = fgets($handle)) !== false) {
				$user = new stdClass();
				$items = explode(",", $line);
				$user->firstname = $items[0];
				$user->lastname = $items[1];
				$user->email = $items[2];
				$user->phone = $items[3];
			} // end while
		} // end if $handle
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
