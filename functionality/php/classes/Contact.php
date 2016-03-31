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
		$list="Thank you for contacting us. We back to you within 24h.";
		return $list; 
	}

}