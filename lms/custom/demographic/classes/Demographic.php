<?php

require_once ($_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php');

/**
 * Description of Demographic
 *
 * @author moyo
 */
class Demographic extends Util {

    function __construct() {
        parent::__construct();
    }

    function get_marriage_status($id) {
        $list = "";
        switch ($id) {
            case 0:
                $list.="N/A";
                break;
            case 1:
                $list.="Single";
                break;
            case 2:
                $list.="Maried";
                break;
        }
        return $list;
    }

    function get_race($id) {
        $list = "";
        switch ($id) {
            case 0:
                $list.="N/A";
                break;
            case 1:
                $list.="White Caucasian";
                break;
            case 2:
                $list.="Black";
                break;
            case 3:
                $list.="Asia";
                break;
            case 4:
                $list.="Mexican";
                break;
            case 5:
                $list.="Other";
                break;
        }
        return $list;
    }

    function get_sex($id) {
        $list = "";
        switch ($id) {
            case 0:
                $list.="N/A";
                break;
            case 1:
                $list.="Female";
                break;
            case 2:
                $list.="Male";
                break;
        }
        return $list;
    }

    function get_education($id) {
        $list = "";
        switch ($id) {
            case 0:
                $list.="N/A";
                break;
            case 1:
                $list.="Only attended high school";
                break;
            case 2:
                $list.="GED";
                break;
            case 3:
                $list.="High school diploma";
                break;
            case 4:
                $list.="College";
                break;
            case 5:
                $list.="College certification";
                break;
            case 6:
                $list.="College diploma";
                break;
        }
        return $list;
    }

    function get_income($id) {
        $list = "";
        switch ($id) {
            case 0:
                $list.="N/A";
                break;
            case 1:
                $list.="$0-$25000";
                break;
            case 2:
                $list.="$25000-$50000";
                break;
            case 3:
                $list.="$50000-$100000";
                break;
            case 4:
                $list.="Above $100000";
                break;
        }
        return $list;
    }

    function get_job_type($id) {
        $list = "";
        switch ($id) {
            case 0:
                $list.="N/A";
                break;
            case 1:
                $list.="Part time";
                break;
            case 2:
                $list.="Full time";
                break;
        }
        return $list;
    }

    function get_status($id) {
        $list = "";
        switch ($id) {
            case '0':
                $list.="N/A";
                break;
            case 'A':
                $list.="Attending";
                break;
            case 'G':
                $list.="Graduate";
                break;
            case 'W':
                $list.="Withdrawal";
                break;
            case 'T':
                $list.="Termination";
                break;
            case 'F':
                $list.="Failed out";
                break;
        }
        return $list;
    }

    function get_attempt($id) {
        $list = "";
        switch ($id) {
            case 0:
                $list.="N/A";
                break;
            case 1:
                $list.="Yes";
                break;
            case 2:
                $list.="No";
                break;
        }
        return $list;
    }

    function get_passed($id) {
        $list = "";
        switch ($id) {
            case 0:
                $list.="N/A";
                break;
            case 1:
                $list.="Yes";
                break;
            case 2:
                $list.="No";
                break;
        }
        return $list;
    }

    function get_user_details($userid) {
        $query = "select * from mdl_user where id=$userid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $user = new stdClass();
            foreach ($row as $key => $value) {
                $user->$key = $value;
            }
            return $user;
        }
    }

    function get_demographic_page() {
        $list = "";

        $list.="<div class='row-fluid'>";
        $list.="<span class='span12'>";
        $list.="<table id='myTable' class='display' cellspacing='0' width='100%'>";

        $list.="<thead>";
        $list.="<tr>";
        $list.="<th>Name</th>";
        $list.="<th>Marriage</th>";
        $list.="<th>Race</th>";
        $list.="<th>Sex</th>";
        $list.="<th>Education</th>";
        $list.="<th>Income</th>";
        $list.="<th>Start Date</th>";
        $list.="<th>Part/full time</th>";
        $list.="<th>Graduation date</th>";
        $list.="<th>Status</th>";
        $list.="<th>Comments</th>";
        $list.="<th>Attempted exam</th>";
        $list.="<th>Passed exam</th>";
        $list.="</tr>";
        $list.="</thead>";


        $list.="<tbody>";

        $query = "select * from mdl_demographic";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $user = $this->get_user_details($row['userid']);
                $name = $user->firstname . ' ' . $user->lastname;
                $marriage = $this->get_marriage_status($row['mstatus']);
                $race = $this->get_race($row['race']);
                $sex = $this->get_sex($row['sex']);
                $edu = $this->get_education($row['edlevel']);
                $income = $this->get_income($row['incomelevel']);
                $start = $row['startdate'];
                $job_type = $this->get_job_type($row['job_type']);
                $graduate_date = $row['graduatedate'];
                $status = $this->get_status($row['school_status']);
                $comments = $row['comments'];
                $attempt_exam = $this->get_attempt($row['attemptexam']);
                $passed_exam = $this->get_passed($row['passedexam']);

                $list.="<tr>";
                $list.="<td>$name</td>";
                $list.="<td>$marriage</td>";
                $list.="<td>$race</td>";
                $list.="<td>$sex</td>";
                $list.="<td>$edu</td>";
                $list.="<td>$income</td>";
                $list.="<td>$start</td>";
                $list.="<td>$job_type</td>";
                $list.="<td>$graduate_date</td>";
                $list.="<td>$status</td>";
                $list.="<td>$comments</td>";
                $list.="<td>$attempt_exam</td>";
                $list.="<td>$passed_exam</td>";
                $list.="</tr>";
            }
        } // end if $num>0

        $list.="</tbody>";

        $list.="</table>";
        $list.="</span>";
        $list.="</div>";

        return $list;
    }

}
