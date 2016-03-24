<?php

/**
 * Description of Report
 *
 * @author sirromas
 */
require_once ($_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php');

class Report extends Util {

	public $card_sum = 0;
	public $cash_sum = 0;
	public $cheque_sum = 0;
	public $program_sum=0;

	function get_courses_list() {
		$list = "";
		$items = array();
		$query = "select id, fullname from mdl_course where cost>0";
		$num = $this->db->numrows($query);
		if ($num > 0) {
			$result = $this->db->query($query);
			while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
				$item = new stdClass();
				foreach ($row as $key => $value) {
					$item->$key = $value;
				} // end foreach
				$items[] = $item;
			} // end while
			$list.="<select id='courses' style='width:175px;'>";
			$list.="<option value='0' selected>Program</option>";
			foreach ($items as $item) {
				$list.="<option value='$item->id'>$item->fullname</option>";
			} // end foreach
			$list.="</select>";
		} // end if $num>0
		return $list;
	}

	function get_revenue_report() {
		$list = "";
		$courses = $this->get_courses_list();
		$list.="<div class='container-fluid'>";
		$list.="<span class='span6' id='revenue_report_err'></span>";
		$list.="</div>";
		$list.="<div class='container-fluid'>";
		$list.="<span class='span3'>$courses</span><span class='span1'>From</span><span class='span2'><input type='text' id='datepicker1' style='width:75px;'></span><span class='span1'>To</span><span class='span2'><input type='text' id='datepicker2' style='width:75px;'></span><span class='span1'><button type='button' id='rev_go' class='btn btn-primary'>Go</button></span>";
		$list.="</div>";
		$list.="<div id='revenue_report_container'>";
		$list.="</div>";
		return $list;
	}

	function get_revenue_report_data($courseid, $from, $to, $status = true) {
		$list = "";
		$list2 = "";
		$coursename = $this->get_course_name($courseid);
		if ($status == true) {
			$list.="<div class='container-fluid' style='font-weight:bold'>";
			$list.="<span class='span9'>$coursename - $from - $to</span>";
			$list.="</div>";
		}
		//1. Get credit cards payment
		$query = "select * from mdl_card_payments "
		. "where courseid=$courseid "
		. "and pdate>='" . strtotime($from) . "' "
		. "and pdate<='" . strtotime($to) . "'  "
		. "order by pdate desc ";
		//echo "<br/>Query: $query<br/>";
		$num = $this->db->numrows($query);
		if ($num > 0) {
			$result = $this->db->query($query);
			while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
				$this->card_sum = $this->card_sum + $row['psum'];
			} // end while
		} // end if $num > 0
		//2. Get cash payments - ptype=1
		$query = "select * from mdl_invoice "
		. "where courseid=$courseid  "
		. "and i_status=1 "
		. "and i_pdate>='" . strtotime($from) . "' "
		. "and i_pdate<='" . strtotime($to) . "' "
		. "and i_ptype=1";
		//echo "<br/>Query: $query<br/>";
		$num = $this->db->numrows($query);
		if ($num > 0) {
			$result = $this->db->query($query);
			while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
				$this->cash_sum = $this->cash_sum + $row['i_sum'];
			} // end while
		} // end if $num > 0
		//3. Get cheque payments - ptype=2
		$query = "select * from mdl_invoice "
		. "where courseid=$courseid  "
		. "and i_status=1 "
		. "and i_pdate>='" . strtotime($from) . "' "
		. "and i_pdate<='" . strtotime($to) . "' "
		. "and i_ptype=2";
		//echo "<br/>Query: $query<br/>";
		$num = $this->db->numrows($query);
		if ($num > 0) {
			$result = $this->db->query($query);
			while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
				$this->cheque_sum = $this->cheque_sum + $row['i_sum'];
			} // end while
		} // end if $num > 0
		$grand_total = $this->card_sum + $this->cash_sum + $this->cheque_sum;
		$list2.="<div class='container-fluid' style='padding-right:0px;'>";
		$list2.="<span class='span3'>Card</span><span class='span1'>$$this->card_sum</span>";
		$list2.="</div>";
		$list2.="<div class='container-fluid' style='padding-right:0px;'>";
		$list2.="<span class='span3'>Cash</span><span class='span1'>$$this->cash_sum</span>";
		$list2.="</div>";
		$list2.="<div class='container-fluid' style='padding-right:0px;'>";
		$list2.="<span class='span3'>Cheque</span><span class='span1'>$$this->cheque_sum</span>";
		$list2.="</div>";
		$list2.="<div class='container-fluid' style='font-weight:bold;' style='padding-right:0px;'>";
		$list2.="<span class='span3'>Total</span><span class='span1'>$$grand_total</span>";
		$list2.="</div>";

		$list.="<table border='0' style='padding-left: 20px;'>";
		$list.="<tr>";
		$list.="<td width='215px;'>$list2</td><td><span id='chart_div' align='left'></span></td>";
		$list.="</tr>";
		$list.="<tr>";
		$list.="<td colspan='2'><div class='container-fluid'><span class='span3'><a href='#' onClick='return false;' id='revenue_report_export'>Export to CSV</a></span></div></td>";
		$list.="</tr>";
		$list.="<tr>";
		$list.="<td colspan='2'><hr/></td>";
		$list.="</tr>";
		$list.="</table>";
		return $list;
	}

	function get_revenue_payments_stats() {
		$payments = array();
		$card_payments = new stdClass();
		$card_payments->src = 'Card payments';
		$card_payments->counter = $this->card_sum;
		$payments[] = $card_payments;
		$cash_payments = new stdClass();
		$cash_payments->src = 'Cash payments';
		$cash_payments->counter = $this->cash_sum;
		$payments[] = $cash_payments;
		$cheque_payments = new stdClass();
		$cheque_payments->src = 'Cheque payments';
		$cheque_payments->counter = $this->cheque_sum;
		$payments[] = $cheque_payments;
		return $payments;
	}

	function get_program_report() {
		$list = "";
		$courses = $this->get_courses_list();
		$list.="<div class='container-fluid'>";
		$list.="<span class='span6' id='program_report_err'></span>";
		$list.="</div>";
		$list.="<div class='container-fluid'>";
		$list.="<span class='span3'>$courses</span><span class='span1'>From</span><span class='span2'><input type='text' id='datepicker1' style='width:75px;'></span><span class='span1'>To</span><span class='span2'><input type='text' id='datepicker2' style='width:75px;'></span><span class='span1'><button type='button' id='program_go' class='btn btn-primary'>Go</button></span>";
		$list.="</div>";
		$list.="<div id='program_report_container'>";
		$list.="</div>";
		return $list;
	}

	function payment_types ($typeid) {
		$query="select * from mdl_payments_type where id=$typeid";
		$result = $this->db->query($query);
		while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
			$type=$row['type'];
		}
		return $type;
	}

	function get_user_payment_status ($courseid, $userid) {
		$status="User has free access";
		//1. Check card payments
		$query="select * from mdl_card_payments
			where userid=$userid and courseid=$courseid";	
		$num = $this->db->numrows($query);
		if ($num > 0) {
			$result = $this->db->query($query);
			while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
				$date=date('d/m/Y', $row['pdate']);
				$status="Payment using card $".$row['psum']." <br>from $date";
				$this->program_sum=$this->program_sum+$row['psum'];
			} // end while
		} // end if $num > 0
		else {
			//2. Check invoice payments
			$query="select * from mdl_invoice
			where courseid=$courseid 
			and userid=$userid 
			and i_status=1";
			$num = $this->db->numrows($query);
			if ($num > 0) {
				$result = $this->db->query($query);
				while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
					$ptype=$this->payment_types($row['i_ptype']);
					$date=date('d/m/Y', $row['i_pdate']);
					$status="Payment using $ptype $".$row['i_sum']."<br> from $date";
					$this->program_sum=$this->program_sum+$row['i_sum'];
				} // end while
			} // end if $num > 0
		} // end else
		return $status;
	}

	function get_program_user_data($courseid, $userid) {
		$query = "select confirmed,firstname,lastname,email,timecreated "
		. "from mdl_user where id=$userid";
		$result = $this->db->query($query);
		while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
			$user=new stdClass();
			foreach ($row as $key=>$value) {
				$user->$key=$value;
			} // end foreach
			if ($row['confirmed']==1) {
				$payment_status=$this->get_user_payment_status($courseid, $userid);
				$user->payment_status=$payment_status;
			} // end if $row['confirmed']==1
			else {
				$user->payment_status='User does not have access';
			}
		} // end while
		return $user;
	}

	function get_user_signup_date ($userid) {
		$query="select timecreated from mdl_user where id=$userid";
		$result = $this->db->query($query);
		while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
			$signup_date=$row['timecreated'];
		}
		return $signup_date;
	}

	function get_program_report_data($courseid, $from, $to, $status = true) {
		$program_users=array();
		$coursename = $this->get_course_name($courseid);
		if ($status == true) {
			$list.="<div class='container-fluid' style='font-weight:bold'>";
			$list.="<span class='span9'>$coursename - $from - $to</span>";
			$list.="</div>";
		}
		$users = $this->get_course_users($courseid, false);
		if (count($users) > 0) {
			foreach ($users as $user) {
				$signup_date=$this->get_user_signup_date($user->userid);
				if ($signup_date>=strtotime($from) && $signup_date<=strtotime($to)) {
					$user_data=$this->get_program_user_data($courseid, $user->userid);
					$program_users[]=$user_data;
				} // end if $signup_date>=strtotime($from)
			} // end foreach
		} // end if count(users)>0
		$list=$this->create_program_users_block($program_users);
		return $list;
	}

	function create_program_users_block ($users) {
		$list="";
		$sum=0;
		if  (count($users)>0) {
			$list.="<div class='container-fluid' style='font-weight:bold;'>";
			$list.="<span class='span4'>User credentials</span><span class='span4'>Payment status</span><span class='span2'>Signup date</span>";
			$list.="</div>";
			$list.="<div class='container-fluid'>";
			$list.="<span class='span10'><hr/></span>";
			$list.="</div>";
			foreach ($users as $user) {
				$date=date('m/d/Y', $user->timecreated);
				if ($user->firstname!='' && $user->lastname!='') {
					$list.="<div class='container-fluid'>";
					$list.="<span class='span4'>$user->firstname $user->lastname $user->email</span><span class='span4'>$user->payment_status</span><span class='span2'>$date</span>";
					$list.="</div>";
					$list.="<div class='container-fluid'>";
					$list.="<span class='span10'><hr/></span>";
					$list.="</div>";
				} // end if $user->firstname!='' && $user->lastname!=''
			} // end foreach
			$list.="<div class='container-fluid' style='font-weight:bold;'>";
			$list.="<span class='span4'>Total users  ".count($users)."</span><span class='span4'>Total program sum $$this->program_sum</span><span class='span2' style='font-weight:normal;'><a href='#' onClick='return false;' id='program_report_export'>Export to CSV</a></span>";
			$list.="</div>";			
		} // end if count($users)>0
		else {
			$list.="<div class='container-fluid' style=''>";
			$list.="<span class='span4'>There are no users at selected program</span>";
			$list.="</div>";
		}
		return $list;
	}

}
