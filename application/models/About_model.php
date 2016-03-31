<?php

class About_model extends CI_Model {

	public function __construct() {
		parent::__construct();
		$this->load->database();
	}

	public function  get_about_page() {
		$list="";
		$query="select * from mdl_about where id=1";
		$result = $this->db->query($query);
		foreach ($result->result() as $row) {
			$content=$row->content;
		}
		$list.="<div class='container-fluid'>";
		$list.="<span class='span9'>$content</span>";
		$list.="</div>";
		return $list;
	}


}