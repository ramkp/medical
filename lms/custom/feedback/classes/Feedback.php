<?php


require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php';

class Feedback extends Util {

	public $limit = 3;

	function __construct() {
		parent::__construct();
	}

	function get_total_feedbacks() {
		$query = "select * from mdl_contact order by id desc";
		$num = $this->db->numrows($query);
		return $num;
	}

	function get_feedback_list () {
		$feedbacks = array();
		$query = "select * from mdl_contact order by id desc limit 0, $this->limit";
		$num = $this->db->numrows($query);
		if ($num > 0) {
			$result = $this->db->query($query);
			while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
				$feedback = new stdClass();
				foreach ($row as $key => $value) {
					$feedback->$key = $value;
				} // end foreach
				$feedbacks[] = $feedback;
			} // end while
		} // end if $num > 0
		$list = $this->create_feedbacks_list($feedbacks);
		return $list;
	}

	function create_feedbacks_list($feedbacks, $toolbar=true) {
		$list = "";
		if (count($feedbacks) > 0) {
			$list.="<div id='feedback_container'>";
			foreach ($feedbacks as $feedback) {
				$date=date('d/m/Y h:i:s' ,$feedback->added);
				$list.="<div class='container-fluid'>";
				$list.="<span class='span2'>Firstname</span><span class='span2'>$feedback->fname</span>";
				$list.="</div>";
				$list.="<div class='container-fluid'>";
				$list.="<span class='span2'>Lastname</span><span class='span2'>$feedback->lname</span>";
				$list.="</div>";
				$list.="<div class='container-fluid'>";
				$list.="<span class='span2'>Email</span><span class='span2'>$feedback->email</span>";
				$list.="</div>";
				$list.="<div class='container-fluid'>";
				$list.="<span class='span2'>Phone</span><span class='span6'>$feedback->phone</span>";
				$list.="</div>";
				$list.="<div class='container-fluid'>";
				$list.="<span class='span2'>Message</span><span class='span6'>$feedback->message</span>";
				$list.="</div>";
				$list.="<div class='container-fluid'>";
				$list.="<span class='span2'>Message date</span><span class='span6'>$date</span>";
				$list.="</div>";
				$list.="<div class='container-fluid'>";
				$list.="<span class='span6'><hr/></span>";
				$list.="</div>";
			} // end foreach
			$list.="</div>";
			if ($toolbar == true) {
				$list.="<div class='container-fluid'>";
				$list.="<span class='span9'  id='pagination'></span>";
				$list.="</div>";
			} // end if $toolbar==true
		} // end if count($certificates)>0
		else {
			$list.="<div class='container-fluid'>";
			$list.="<span class='span6'>There are no users feedback yet</span>";
			$list.="</div>";
		} // end else
		return $list;
	}

	function get_feedback_item($page) {
		$feedbacks = array();
		$rec_limit = $this->limit;
		if ($page == 1) {
			$offset = 0;
		} // end if $page==1
		else {
			$page = $page - 1;
			$offset = $rec_limit * $page;
		}
		$query = "select * from mdl_contact order by id desc LIMIT $offset, $rec_limit";		
		$result = $this->db->query($query);
		while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
			$feedback = new stdClass();
			foreach ($row as $key => $value) {
				$feedback->$key = $value;
			} // end foreach
			$feedbacks[] = $feedback;
		} // end while
		$list = $this->create_feedbacks_list($feedbacks, false);
		return $list;
	}

}