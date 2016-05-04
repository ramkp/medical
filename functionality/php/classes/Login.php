<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/class.pdo.database.php';

class Login {

	public $db;

	function __construct() {
		$this->db = new pdo_db();
	}

	function verify_user ($login,$pwd) {
		$query="select * from mdl_user
    	where username='".trim($login)."' 
    	and purepwd='".trim($pwd)."'";
		//echo $query;
		return $this->db->numrows($query);
	}
}
