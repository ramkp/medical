<?php

/**
 * Description of Late
 *
 * @author sirromas
 */
require_once ($_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php');

class Late extends Util {

	function get_edit_page() {
		$list = "";
		$query = "select * from mdl_late_fee where id=1";
		$result = $this->db->query($query);
		while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
			$fee_delay = $row['fee_delay'];
			$fee_amount = $row['fee_amount'];
		} // end while

		$list.="<div class='container-fluid'>";
		$list.="<span class='span2'>Late fee delay (days)</span><span class='span2'><input type='text' value='$fee_delay' id='fee_delay' style='width:45px;'></span>";
		$list.="</div>";

		$list.="<div class='container-fluid'>";
		$list.="<span class='span2'>Late fee amount ($)</span><span class='span2'><input type='text' value='$fee_amount' id='fee_amount' style='width:45px;'></span>";
		$list.="</div>";

		$list.="<div class='container-fluid'>";
		$list.="<span class='span6' id='late_err' style='color:red;'></span>";
		$list.="</div>";

		$list.="<div class='container-fluid'>";
		$list.="<span class='span2'><button type='button' id='update_late' class='btn btn-primary'>Save</button></span>";
		$list.="</div>";

		return $list;
	}

	function save_changes($period, $amount) {
		$list = "";
		$query="update mdl_late_fee "
		. "set fee_delay=$period, "
		. "fee_amount='$amount' where id=1";
		$this->db->query($query);
		$list.="<p align='center'>Data successfully saved. </p>";
		return $list;
	}

	function import_courses () {
		$query="select id from mdl_course where category>0 and visible=1";
		$result = $this->db->query($query);
		while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
			$query2="insert into mdl_late_fee
            	(courseid,fee_delay,fee_amount) 
            	values(".$row['id'].",7,'25')";
			$this->db->query($query2);
			echo "Course with id ".$row['id']." was updated with late fee ....<br>";
		} // end while 
	}

}
