<?php

require_once ($_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/lms/custom/my/classes/Dashboard.php');

/**
 * Description of Demographic
 *
 * @author moyo
 */
class Demographic extends Util {

    public $query;

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
                $list.="Married";
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
                $list.="Terminated";
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

    function create_demographic_table($items) {
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
        if (count($items > 0)) {
            foreach ($items as $item) {
                $user = $this->get_user_details($item->userid);
                $userid = $item->userid;
                $name = "<a href='https://medical2.com/lms/user/profile.php?id=" . $userid . "' target='_blank'>" . $user->firstname . ' ' . $user->lastname . "</a>";
                $marriage = $this->get_marriage_status($item->mstatus);
                $race = $this->get_race($item->race);
                $sex = $this->get_sex($item->sex);
                $edu = $this->get_education($item->edlevel);
                $income = $this->get_income($item->incomelevel);
                $start = $item->startdate;
                $job_type = $this->get_job_type($item->job_type);
                $graduate_date = $item->graduatedate;
                $status = $this->get_status($item->school_status);
                $comments = $item->comments;
                $attempt_exam = $this->get_attempt($item->attemptexam);
                $passed_exam = $this->get_passed($item->passedexam);

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
            } // end foreach
        } // end if count($items>0)

        $list.="</tbody>";

        $list.="</table>";
        $list.="</span>";
        $list.="</div>";

        $list.="<div class='row-fluid'>";
        $list.="<span class='span3'><button id='print_demographic_report'>Print</button></span>";
        $list.="</div>";

        return $list;
    }

    function get_demographic_page() {
        $list = "";
        $ds = new Dashboard();
        $mbox = $ds->get_marital_status_box();
        $race_box = $ds->get_race_box();
        $sex_box = $ds->get_sex_box();
        $edu_level = $ds->get_edu_box();
        $income_level = $ds->get_income_box();
        $job_type = $ds->get_job_type_box();
        $school_status = $ds->get_status_box();
        $attempted_exam = $ds->get_attempted_exam_box();
        $passed_exam = $ds->get_passed_box();
        $work15 = $ds->get_work_box();

        $list.="<div class='row-fluid'>";
        $list.="<span class='span1'>Start date</span>";
        $list.="<span class='span1'>";
        $list.="<input type='text' id='start_d' style='width:75px;'>";
        $list.="</span>";


        $list.="<span class='span1'>End Date</span>";
        $list.="<span class='span1'>";
        $list.="<input type='text' id='end_d' style='width:75px;'>";
        $list.="</span>";

        $list.="<span class='span1'><button id='show_filer_bar'>Filter</button></span>";
        $list.="<span class='span1'><button id='reset_filer_bar'>Reset</button></span>";
        $list.="<span class='span1'><button id='get_demo_data'>Go</button></span>";

        $list.="</div>";

        $list.="<div class='row-fluid'>";
        $list.="<span class='span12'><hr/></span>";
        $list.="</div>";

        $list.="<div class='row-fluid' id='filer_bar1' style='display:none;'>";
        $list.="<span class='span2'>Mariage status<br>$mbox</span>";
        $list.="<span class='span2'>Race<br>$race_box</span>";
        $list.="<span class='span2'>Sex<br>$sex_box</span>";
        $list.="<span class='span2'>Education level<br>$edu_level</span>";
        $list.="<span class='span2'>Income level<br>$income_level</span>";
        $list.="</div>";

        $list.="<div class='row-fluid' id='filer_bar2' style='display:none;'>";
        $list.="<span class='span2'>Job type<br>$job_type</span>";
        $list.="<span class='span2'>School status<br>$school_status</span>";
        $list.="<span class='span2'>Attempted exam<br>$attempted_exam</span>";
        $list.="<span class='span2'>Passed exam<br>$passed_exam</span>";
        $list.="<span class='span2'>Worked 15 days<br>$work15</span>";
        $list.="</div>";

        $list.="<div class='row-fluid'>";
        $list.="<span class='span12'><hr/></span>";
        $list.="</div>";

        $list.="<div class='row-fluid'>";
        $list.="<span class='span12' style='display:none;text-align:center;' id='ajax_loader'><img src='https://$this->host/assets/img/ajax.gif' /></span>";
        $list.="</div>";



        $list.="<div class='row-fluid' id='report_data'></div>";

        return $list;
    }

    function includes_where($query) {
        $pos = strpos($query, 'where');
        return $pos;
    }

    function get_demo_data($criteia) {
        $list = "";
        $query = "";
        $items = array();

        /*
          echo "<pre>";
          print_r($criteia);
          echo "</pre>";
         */

        $date1 = $criteia->date1;
        $date2 = $criteia->date2;

        if ($date1 == '' && $date2 == '') {
            $query.="select * from mdl_demographic ";

            if ($criteia->mstatus != 0) {
                $mstatus = $criteia->mstatus;
                $wstatus = $this->includes_where($query);
                $query.=($wstatus === false) ? "where mstatus=$mstatus " : "and mstatus=$mstatus ";
            }
            if ($criteia->racebox != 0) {
                $racebox = $criteia->racebox;
                $wstatus = $this->includes_where($query);
                $query.=($wstatus === false) ? "where race=$racebox " : "and race=$racebox ";
            }
            if ($criteia->edu_box != 0) {
                $edubox = $criteia->edu_box;
                $wstatus = $this->includes_where($query);
                $query.=($wstatus === false) ? "where edlevel=$edubox " : "and edlevel=$edubox ";
            }
            if ($criteia->sexbox != 0) {
                $sexbox = $criteia->sexbox;
                $wstatus = $this->includes_where($query);
                $query.=($wstatus === false) ? "where sex=$sexbox " : "and sex=$sexbox ";
            }
            if ($criteia->income != 0) {
                $income = $criteia->income;
                $wstatus = $this->includes_where($query);
                $query.=($wstatus === false) ? "where incomelevel=$income " : "and incomelevel=$income ";
            }
            if ($criteia->job_type != 0) {
                $jb = $criteia->job_type;
                $wstatus = $this->includes_where($query);
                $query.=($wstatus === false) ? "where job_type=$jb " : "and job_type=$jb ";
            }
            if ($criteia->school_status != 0) {
                $sch = $criteia->school_status;
                $wstatus = $this->includes_where($query);
                $query.=($wstatus === false) ? "where school_status='$sch' " : "and school_status='$sch' ";
            }
            if ($criteia->exam_attempt != 0) {
                $ae = $criteia->exam_attempt;
                $wstatus = $this->includes_where($query);
                $query.=($wstatus === false) ? "where attemptexa=$ae " : "and attemptexa=$ae ";
            }
            if ($criteia->exam_passed != 0) {
                $pe = $criteia->exam_passed;
                $wstatus = $this->includes_where($query);
                $query.=($wstatus === false) ? "where passedexam=$pe " : "and passedexam=$pe ";
            }
            if ($criteia->worked15 != 0) {
                $w15 = $criteia->worked15;
                $wstatus = $this->includes_where($query);
                $query.=($wstatus === false) ? "where  work15=$w15" : "and work15=$w15";
            }
        } // end if
        else {

            $query.="select * from mdl_demographic where ";

            if ($date1 != '' && $date2 == '') {
                $udate1 = strtotime($date1);
                $query.="UNIX_TIMESTAMP(STR_TO_DATE(startdate, '%m/%d/%Y'))>='$udate1' ";
            }
            if ($date1 != '' && $date2 != '') {
                $udate1 = strtotime($date1);
                $udate2 = strtotime($date2);
                $query.="UNIX_TIMESTAMP(STR_TO_DATE(startdate, '%m/%d/%Y')) between '$udate1' and '$udate2' ";
            }
            if ($date1 == '' && $date2 != '') {
                $udate2 = strtotime($date2);
                $query.="UNIX_TIMESTAMP(STR_TO_DATE(startdate, '%m/%d/%Y'))<='$udate2' ";
            }
            if ($criteia->mstatus != 0) {
                $mstatus = $criteia->mstatus;
                $query.="and mstatus=$mstatus ";
            }
            if ($criteia->racebox != 0) {
                $racebox = $criteia->racebox;
                $query.="and race=$racebox ";
            }
            if ($criteia->edu_box != 0) {
                $edubox = $criteia->edu_box;
                $query.="and edlevel=$edubox ";
            }
            if ($criteia->sexbox != 0) {
                $sexbox = $criteia->sexbox;
                $query.="and sex=$sexbox ";
            }
            if ($criteia->income != 0) {
                $income = $criteia->income;
                $query.="and incomelevel=$income ";
            }
            if ($criteia->job_type != 0) {
                $jb = $criteia->job_type;
                $query.="and job_type=$jb ";
            }
            if ($criteia->school_status != 0) {
                $sch = $criteia->school_status;
                $query.="and school_status='$sch' ";
            }
            if ($criteia->exam_attempt != 0) {
                $ae = $criteia->exam_attempt;
                $query.="and attemptexa=$ae ";
            }
            if ($criteia->exam_passed != 0) {
                $pe = $criteia->exam_passed;
                $query.="and passedexam=$pe ";
            }
            if ($criteia->worked15 != 0) {
                $w15 = $criteia->worked15;
                $query.="and work15=$w15";
            }
        } // end else

        $_SESSION['demographic_query'] = $query;
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $item = new stdClass();
                foreach ($row as $key => $value) {
                    $item->$key = $value;
                }
                $items[] = $item;
            }
        }

        $list.=$this->create_demographic_table($items);
        return $list;
    }

    function create_demographic_pdf_report() {
        $list = "";
        $items = array();
        $demograhic_data = "";
        $query = $_SESSION['demographic_query'];
        //echo "Current query: " . $query;
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $item = new stdClass();
                foreach ($row as $key => $value) {
                    $item->$key = $value;
                }
                $items[] = $item;
            }
        }
        $demograhic_data.=$this->create_demographic_table($items);

        $list.="<br><table align='center' border='0' width='100%'>";

        $list.="<tr>";

        $list.="<td>";

        $list.=$demograhic_data;

        $list.="</td>";

        $list.="</tr>";

        $list.="</table>";

        $now = time();
        $pdf = new mPDF('utf-8', 'A4-L');
        $pdf->WriteHTML($list);
        $filename = "demographic_$now.pdf";
        $path = $_SERVER['DOCUMENT_ROOT'] . "/lms/custom/demographic/$filename";
        $pdf->Output($path, 'F');
        return $filename;
    }

}
