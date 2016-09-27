<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/class.pdo.database.php';

class Contact {

	public $db;

	function __construct() {
		$this->db = new pdo_db();
	}
	
	function post_contact_form ($firstname,$lastname,$email,$phone,$message) {
		$query="insert into mdl_contact (fname,lname,email,phone,message,added) 
		values('".$firstname."',
		       '".$lastname."',
		       '".$email."',
			   '".$phone."',   
			   '".$message."', '".time()."')";
		$this->db->query($query);		
		$list="Your message is sent. Thank you";
		return $list; 
	}

}