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
	public $program_sum = 0;
	public $cert_path;

	function __construct() {
		parent::__construct();
		$this->cert_path = $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/certificates';
	}

	/************************************* Service functions *********************************/
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

	function get_states_list () {
		$list="";
		$states=array();
		$query="select * from mdl_states order by state";
		$result = $this->db->query($query);
		while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
			$state=new stdClass();
			foreach ($row as $key => $value) {
				$state->$key = $value;
			} // end foreach
			$states[] = $state;
		} // end while
		$list.="<select id='states' style='width:100px;'>";
		$list.="<option value='0' selected>All states</option>";
		foreach ($states as $state) {
			$list.="<option value='$state->id'>$state->state</option>";
		} // end foreach
		$list.="</select>";
		return $list;
	}

	function get_workshops_list () {
		$list="";
		$workshops=array();
		$query="select * from mdl_course where category=2 and cost>0";
		$num = $this->db->numrows($query);
		if ($num > 0) {
			$result = $this->db->query($query);
			while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
				$workshop=new stdClass();
				foreach ($row as $key => $value) {
					$workshop->$key = $value;
				} // end foreach
				$workshops[]=$workshop;
			} // end while
			$list.="<select id='workshops' style='width:175px;'>";
			$list.="<option value='0' selected>Workshop</option>";
			foreach ($workshops as $workshop) {
				$list.="<option value='$workshop->id'>$workshop->fullname</option>";
			}
			$list.="</select>";
		} // end if $num > 0
		return $list;
	}

	function get_course_category ($courseid) {
		$query="select category from mdl_course where id=$courseid";
		$result = $this->db->query($query);
		while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
			$cat=$row['category'];
		}
		return $cat;
	}

	function get_workshop_by_state ($id) {
		$list="";
		//echo "State id: ".$id."<br>";
		$state_courses=array();
		$state_workshops=array();
		if ($id>0) {
			$query="select * from mdl_course_to_state where stateid=$id";
		} // end if $id>0
		else {
			$query="select * from mdl_course_to_state";
		}
		$num = $this->db->numrows($query);
		// echo "State num: ".$num."<br>";
		if ($num > 0) {
			$result = $this->db->query($query);
			while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
				$state_courses[]=$row['courseid'];
			} // end while
			foreach ($state_courses as $courseid) {
				$cat=$this->get_course_category($courseid);
				if ($cat==2) {
					$state_workshops[]= $courseid;
				} // end if $cat==2
			} // end foreach
			// echo "Total workshops: ".count($state_workshops)."<br>";
			if (count($state_workshops)>0) {
				$list.="<select id='workshops' style='width:175px;'>";
				$list.="<option value='0' selected>Workshop</option>";
				foreach ($state_workshops as $workshop) {
					$workshop_name=$this->get_course_name($workshop);
					$list.="<option value='$workshop'>$workshop_name</option>";
				} // end foreach
				$list.="</select>";
			} // end if $state_workshops)>0
			else {
				$list.="n/a";
			}
		} // end if $num > 0
		else {
			$list.="n/a";
		}
		return $list;
	}

	function get_user_certification_data ($courseid,$userid) {
		$query="select * from mdl_certificates where courseid=$courseid and userid=$userid";
		$num = $this->db->numrows($query);
		$cert=new stdClass();
		if ($num > 0) {
			$result = $this->db->query($query);
			while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
				$cert->certified=1;
				$cert->no=str_replace($this->cert_path, "", $row['path']);
				$cert->issue_date=date('d/m/Y',$row['issue_date']);
				$cert->exp_date=date('d/m/Y',$row['expiration_date']);
				$cert->payment_status=$this->get_user_payment_status($courseid, $userid);
			} // end while
		} // end if $num > 0
		else {
			$cert->certified=0;
			$cert->no='n/a';
			$cert->issue_date='n/a';
			$cert->exp_date='n/a';
			$cert->payment_status=$this->get_user_payment_status($courseid, $userid);
		} // end else
		return $cert;
	}

	function get_user_balance ($courseid,$userid) {
		$list="";
		$cert=$this->get_user_certification_data($courseid, $userid);
		$cert_status=($cert->certified==0)? 'User is not certified': 'User is certified';
		$query="select * from mdl_user_balance where courseid=$courseid and userid=$userid";
		$num = $this->db->numrows($query);
		if ($num > 0) {
			$result = $this->db->query($query);
			while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
				if ($cert->certified==1) {
					$expiration_status=($cert->exp_date==null) ? "n/a" : $cert->exp_date;
				} // end if $cert->certified==1
				else {
					$expiration_status="n/a";
				} // end else
				$balance_status=($row['balance_sum']==null) ? "n/a":$row['balance_sum'];
			} // end while
			$query="update mdl_user_balance 
			set is_certified=$cert->certified 
			where courseid=$courseid and userid=$userid";
			$this->db->query($query);
		} // end if $num > 0
		else {
			// Set User balance
			$expiration_status="n/a";
			$balance_status="n/a";			
			$query="insert into mdl_user_balance
				(courseid,
				 userid,
				 is_certified,
				 cert_no,
				 cert_exp,
				 balance_sum) values
				  ('".$courseid."',
				  '".$userid."',
				  '".$cert->certified."',
				  '".$cert->no."',
				  '".$cert->exp_date."',
				   'n/a')";
			//echo "Query: ".$query."<br>";
			$this->db->query($query);

		}
		if ($cert->certified==1){ 		
		$list.="$cert_status<br>Expiration date $expiration_status<br>Balance: $$balance_status";
		}
		else {
		$list.="$cert_status<br>Balance: $$balance_status";	
		}
		return $list;
	}

	/************************************* Revenue report  ***********************************/
	function get_revenue_report() {
		$list = "";
		$courses = $this->get_courses_list();
		$list.="<div class='container-fluid'>";
		$list.="<span class='span6' id='revenue_report_err'></span>";
		$list.="</div>";
		$list.="<div class='container-fluid'>";
		$list.="<span class='span3'>$courses</span><span class='span1'>From</span><span class='span2'><input type='text' id='datepicker1' style='width:75px;'></span><span class='span1'>To</span><span class='span2'><input type='text' id='datepicker2' style='width:75px;'></span><span class='span1'><button type='button' id='rev_go' class='btn btn-primary'>Go</button></span>";
		$list.="</div>";
		$list.="<div class='container-fluid' style='text-align:left;'>";
		$list.="<span class='span8' style='text-align:center;display:none;' id='ajax_loading'><img src='http://cnausa.com/assets/img/ajax.gif' /></span>";
		$list.="</div>";
		$list.="<div id='revenue_report_container'>";
		$list.="</div>";
		return $list;
	}

	function get_revenue_report_data($courseid, $from, $to, $status = true, $output=true) {
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
				$user_status=$this->is_user_deleted($row['userid']);
				if ($user_status==0) {
					$this->card_sum = $this->card_sum + $row['psum'];
				} // end if $user_status==0
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
				$user_status=$this->is_user_deleted($row['userid']);
				if ($user_status==0) {
					$this->cash_sum = $this->cash_sum + $row['i_sum'];
				}
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
				$user_status=$this->is_user_deleted($row['userid']);
				if ($user_status==0) {
					$this->cheque_sum = $this->cheque_sum + $row['i_sum'];
				}
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
		if ($output==true) {
			return $list;
		}
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

	/************************************* Program report  ***********************************/
	function get_program_report() {
		$list = "";
		$courses = $this->get_courses_list();
		$list.="<div class='container-fluid'>";
		$list.="<span class='span6' id='program_report_err'></span>";
		$list.="</div>";
		$list.="<div class='container-fluid'>";
		$list.="<span class='span3'>$courses</span><span class='span1'>From</span><span class='span2'><input type='text' id='datepicker1' style='width:75px;'></span><span class='span1'>To</span><span class='span2'><input type='text' id='datepicker2' style='width:75px;'></span><span class='span1'><button type='button' id='program_go' class='btn btn-primary'>Go</button></span>";
		$list.="</div>";
		$list.="<div class='container-fluid' style='text-align:left;'>";
		$list.="<span class='span8' style='text-align:center;display:none;' id='ajax_loading'><img src='http://cnausa.com/assets/img/ajax.gif' /></span>";
		$list.="</div>";
		$list.="<div id='program_report_container'>";
		$list.="</div>";
		return $list;
	}

	function payment_types($typeid) {
		$query = "select * from mdl_payments_type where id=$typeid";
		$result = $this->db->query($query);
		while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
			$type = $row['type'];
		}
		return $type;
	}

	function get_user_payment_status($courseid, $userid) {
		$status = "User has free access";
		//1. Check card payments
		$query = "select * from mdl_card_payments
			where userid=$userid and courseid=$courseid";
		$num = $this->db->numrows($query);
		if ($num > 0) {
			$result = $this->db->query($query);
			while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
				$date = date('d/m/Y', $row['pdate']);
				$status = "Payment using card $" . $row['psum'] . " <br>from $date";
				//$this->program_sum = $this->program_sum + $row['psum'];
			} // end while
		} // end if $num > 0
		else {
			//2. Check invoice payments
			$query = "select * from mdl_invoice
			where courseid=$courseid 
			and userid=$userid 
			and i_status=1";
			$num = $this->db->numrows($query);
			if ($num > 0) {
				$result = $this->db->query($query);
				while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
					$ptype = $this->payment_types($row['i_ptype']);
					$date = date('d/m/Y', $row['i_pdate']);
					$status = "Payment using $ptype $" . $row['i_sum'] . "<br> from $date";
					//$this->program_sum = $this->program_sum + $row['i_sum'];
				} // end while
			} // end if $num > 0
		} // end else
		return $status;
	}

	function get_program_user_data($courseid, $userid) {
		$query = "select confirmed,firstname,lastname,email,phone1,timecreated "
		. "from mdl_user where id=$userid";
		$result = $this->db->query($query);
		while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
			$user = new stdClass();
			foreach ($row as $key => $value) {
				$user->$key = $value;
			} // end foreach
			if ($row['confirmed'] == 1) {
				$payment_status = $this->get_user_payment_status($courseid, $userid);
				$user->payment_status = $payment_status;

				$balance=$this->get_user_balance($courseid, $userid);
				$user->balance=$balance;
			} // end if $row['confirmed']==1
			else {
				$user->payment_status = 'User does not have access';
				$user->balance='n/a';
			}
		} // end while
		return $user;
	}

	function get_user_signup_date($userid) {
		//echo "User id: ".$userid."<br>";
		$query = "select timecreated from mdl_user where id=$userid";
		$result = $this->db->query($query);
		while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
			$signup_date = $row['timecreated'];
		}
		return $signup_date;
	}

	function get_program_payments ($courseid,$from, $to) {
		$this->get_revenue_report_data($courseid, $from, $to, false, false);
		$this->program_sum=$this->card_sum+$this->cash_sum+$this->cheque_sum;
	}

	function get_program_report_data($courseid, $from, $to, $status = true) {
		$program_users = array();
		$coursename = $this->get_course_name($courseid);
		if ($status == true) {
			$list.="<div class='container-fluid' style='font-weight:bold'>";
			$list.="<span class='span9'>$coursename - $from - $to</span>";
			$list.="</div>";
		}
		$users = $this->get_course_users($courseid, false);
		if (count($users) > 0) {
			$this->get_program_payments($courseid, $from, $to);
			foreach ($users as $user) {
				$signup_date = $this->get_user_signup_date($user->userid);
				if ($signup_date >= strtotime($from) && $signup_date <= strtotime($to)) {
					$user_data = $this->get_program_user_data($courseid, $user->userid);
					$program_users[] = $user_data;
				} // end if $signup_date>=strtotime($from)
			} // end foreach
		} // end if count(users)>0
		$list = $this->create_program_users_block($program_users);
		return $list;
	}

	function create_program_users_block($users) {
		$list = "";
		$sum = 0;
		if (count($users) > 0) {
			$list.="<div class='container-fluid' style='font-weight:bold;'>";
			$list.="<span class='span4'>User credentials</span><span class='span4'>Payment status</span><span class='span2'>Signup date</span>";
			$list.="</div>";
			$list.="<div class='container-fluid'>";
			$list.="<span class='span10'><hr/></span>";
			$list.="</div>";
			foreach ($users as $user) {
				$date = date('m/d/Y', $user->timecreated);
				if ($user->firstname != '' && $user->lastname != '') {
					$list.="<div class='container-fluid'>";
					$list.="<span class='span4'>$user->firstname $user->lastname $user->email</span><span class='span4'>$user->payment_status</span><span class='span2'>$date</span>";
					$list.="</div>";
					$list.="<div class='container-fluid'>";
					$list.="<span class='span10'><hr/></span>";
					$list.="</div>";
				} // end if $user->firstname!='' && $user->lastname!=''
			} // end foreach
			$list.="<div class='container-fluid' style='font-weight:bold;'>";
			$list.="<span class='span4'>Total users  " . count($users) . "</span><span class='span4'>Total program sum $$this->program_sum</span><span class='span2' style='font-weight:normal;'><a href='#' onClick='return false;' id='program_report_export'>Export to CSV</a></span>";
			$list.="</div>";
		} // end if count($users)>0
		else {
			$list.="<div class='container-fluid' style=''>";
			$list.="<span class='span4'>There are no users at selected program</span>";
			$list.="</div>";
		}
		return $list;
	}

	/********************************* Workshops report *********************************/
	function get_workshops_report () {
		$list="";
		$states_list=$this->get_states_list();
		$workshops_list=$this->get_workshops_list();
		$list.="<div class='container-fluid'>";
		$list.="<span class='span6' id='workshop_report_err'></span>";
		$list.="</div>";
		$list.="<div class='container-fluid'>";
		$list.="<span class='span2'>$states_list</span><span class='span3' id='workshops_dropdown'>$workshops_list</span><span class='span1'>From</span><span class='span2'><input type='text' id='datepicker1' style='width:75px;'></span><span class='span1'>To</span><span class='span2'><input type='text' id='datepicker2' style='width:75px;'></span><span class='span1'><button type='button' id='workshops_go' class='btn btn-primary'>Go</button></span>";
		$list.="</div>";
		$list.="<div class='container-fluid' style='text-align:left;'>";
		$list.="<span class='span8' style='text-align:center;display:none;' id='ajax_loading'><img src='http://cnausa.com/assets/img/ajax.gif' /></span>";
		$list.="</div>";
		$list.="<div id='workshops_report_container'>";
		$list.="</div>";
		return $list;
	}

	function get_workshop_report_data ($courseid,$from,$to,$status=false) {
		$list="";
		$workshop_users=array();
		$coursename = $this->get_course_name($courseid);
		if ($status == true) {
			$list.="<div class='container-fluid' style='font-weight:bold'>";
			$list.="<span class='span9'>$coursename - $from - $to</span>";
			$list.="</div>";
		}
		$users=$this->get_course_users($courseid, false);
		if (count($users)>0) {
			foreach ($users as $user) {
				$signup_date=$this->get_user_signup_date($user->userid);
				if ($signup_date >= strtotime($from) && $signup_date <= strtotime($to)) {
					$workshop_users[]=$user;
				} // end if $signup_date >= strtotime($from) && $signup_date <= strtotime($to)
			} // end foreach
			//print_r($workshop_users);
			if (count($workshop_users)>0) {
				$this->get_program_payments($courseid, $from, $to);
				$list.="<div class='container-fluid' style='font-weight:bold;'>";
				$list.="<span class='span4'>User credentials</span><span class='span4'>Payment status</span><span class='span2'>Signup date</span><span class='span2'>Balance</span>";
				$list.="</div>";
				$list.="<div class='container-fluid'>";
				$list.="<span class='span12'><hr/></span>";
				$list.="</div>";
				foreach ($workshop_users as $user) {
					$user_data=$this->get_program_user_data($courseid, $user->userid);
					$date = date('m/d/Y', $user_data->timecreated);
					if ($user_data->firstname != '' && $user_data->lastname != '') {
						$list.="<div class='container-fluid'>";
						$list.="<span class='span4'>$user_data->firstname $user_data->lastname $user_data->email</span><span class='span4'>$user_data->payment_status</span><span class='span2'>$date</span><span class='span2'>$user_data->balance</span>";
						$list.="</div>";
						$list.="<div class='container-fluid'>";
						$list.="<span class='span12'><hr/></span>";
						$list.="</div>";
					} // end if $user->firstname!='' && $user->lastname!=''
				} // end foreach
				$list.="<div class='container-fluid' style='font-weight:bold;'>";
				$list.="<span class='span4'>Total users  " . count($workshop_users) . "</span><span class='span4'>Total program sum $$this->program_sum</span><span class='span2' style='font-weight:normal;'><a href='#' onClick='return false;' id='workshop_report_export'>Export to CSV</a></span>";
				$list.="</div>";
			} // end if count($workshop_users)>0
			else {
				$list.="<div class='container-fluid' style=''>";
				$list.="<span class='span4'>There are no users at selected program</span>";
				$list.="</div>";
			}
		} // end if count($users)>0
		else {
			$list.="<div class='container-fluid' style=''>";
			$list.="<span class='span4'>There are no users at selected program</span>";
			$list.="</div>";
		}
		return $list;
	}



}
