<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/Mailer.php';

class Survey extends Util {

    public $non_stat_fields;
    public $host;

    function __construct() {
        parent::__construct();
        $this->non_stat_fields = ['id', 'courseid', 'userid', 'attend', 'city', 'ws_more', 'improve', 'comments', 'viewed', 'viewed_date'];
        $this->host = 'medical2.com';
    }

    function get_survey_page() {
        $list = "";
        $items = array();
        $query = "SELECT *  FROM  mdl_ws_survey "
                . "WHERE viewed =1 GROUP BY courseid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $items[] = $row['courseid'];
            } // end while
        } // end if $num > 0
        $list.=$this->create_survey_page($items);
        return $list;
    }

    function get_survey_courses_box($items) {
        $list = "";
        $list.="<select id='survey_courses'>";
        $list.="<option value='0' selected>All courses</option>";
        if (count($items) > 0) {
            foreach ($items as $item) {
                $coursename = $this->get_course_name($item);
                $list.="<option value='$item'>$coursename</option>";
            } // end foreach
        } // end if count($items)>0
        $list.="</select>";
        return $list;
    }

    function get_rate_text($item) {
        switch ($item) {
            case 1:
                $stat = "Excellent";
                break;
            case 2:
                $stat = "Good";
                break;
            case 3:
                $stat = "Needs improvoment";
                break;
            case 4:
                $stat = "Very bad";
                break;
        }
        return $stat;
    }

    function get_recommend_status($item) {
        $status = ($item == 1) ? 'Yes' : 'No';
        return $status;
    }

    function get_survey_data($courseid) {
        $list = "";
        if ($courseid == 0) {
            $query = "select * from mdl_ws_survey "
                    . "WHERE viewed =1 ";
            $num = $this->db->numrows($query);
            if ($num > 0) {
                $list.="<div class='row-fluid' style='font-weight:bold;'>";
                //$list.="<span class='span2'>Program Name</span>";
                $list.="<span class='span2'>Professionalism</span>";
                $list.="<span class='span2'>Questions answered</span>";
                $list.="<span class='span2'>It was clear</span>";
                $list.="<span class='span2'>Me expectations</span>";
                $list.="<span class='span2'>Draw blood</span>";
                $list.="<span class='span2'>Recommend</span>";
                $list.="</div>";

                $result = $this->db->query($query);
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $coursename = $this->get_course_name($row['courseid']);
                    $list.="<div class='row-fluid'>";
                    $list.="<span class='span2'>" . $this->get_rate_text($row['in_prof']) . "</span>";
                    $list.="<span class='span2'>" . $this->get_rate_text($row['qu_answer']) . "</span>";
                    $list.="<span class='span2'>" . $this->get_rate_text($row['in_clear']) . "</span>";
                    $list.="<span class='span2'>" . $this->get_rate_text($row['training_met']) . "</span>";
                    $list.="<span class='span2'>" . $row['draw_blood'] . "</span>";
                    $list.="<span class='span2'>" . $this->get_recommend_status($row['recommend']) . "</span>";
                    $list.="";
                    $list.="</div>";
                } // end while

                $list.="<div class='row-fluid' style='font-weight:bold;'>";
                $list.="<span class='span12'><hr/></span>";
                $list.="</div>";

                $list.="<div class='row-fluid' style='font-weight:bold;'>";
                $list.="<span class='span2'>" . $this->get_rate_text($this->get_average_coulmn_value($courseid, 'in_prof')) . "</span>";
                $list.="<span class='span2'>" . $this->get_rate_text($this->get_average_coulmn_value($courseid, 'qu_answer')) . "</span>";
                $list.="<span class='span2'>" . $this->get_rate_text($this->get_average_coulmn_value($courseid, 'in_clear')) . "</span>";
                $list.="<span class='span2'>" . $this->get_rate_text($this->get_average_coulmn_value($courseid, 'training_met')) . "</span>";
                $list.="<span class='span2'>" . $this->get_average_coulmn_value($courseid, 'draw_blood') . "</span>";
                $list.="<span class='span2'>" . $this->get_recommend_status($this->get_average_coulmn_value($courseid, 'recommend')) . "</span>";
                $list.="</div>";
            } // end if $num > 0 
        } // end if $courseid==0
        else {
            
        } // end else
        return $list;
    }

    function get_average_coulmn_value($courseid, $column) {
        if ($courseid == 0) {
            $query = "select avg($column) as stat from mdl_ws_survey "
                    . "where viewed=1";
        } // end if $courseid==0
        else {
            $query = "select avg($column) as stat from mdl_ws_survey "
                    . "where courseid=$courseid and viewed=1";
        }
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $stat = round($row['stat']);
        }
        return $stat;
    }

    function create_survey_page($items) {
        $list = "";

        /*
          $coursesbox = $this->get_survey_courses_box($items);
          $list.="<div class='row-fluid'>";
          $list.="<span class='span5'>$coursesbox</span>";
          $list.="<span class='span1'><button>Show</button></span>";
          $list.="</div>";

          $list.="<div class='row-fluid' style='display:none;' id='survey_ajax'>";
          $list.="<span class='span12'><img src='https://$this->host/assets/img/ajax.gif'></span>";
          $list.="</div>";
         */

        $list.="<div id='survey_content'>";
        $list.=$this->get_survey_data(0);
        $list.="</div>";
        $list.="</div>";



        return $list;
    }

    function update_survey_stats() {
        
    }

    function get_item_score($score) {
        switch ($score) {
            case "1":
                $item = "Excellent";
                break;
            case "2":
                $item = "Good";
                break;
            case "3":
                $item = "Needs improvement";
                break;
        }
        return $item;
    }

    function get_course_stats($courseid) {
        $query = "select * from mdl_ws_survey "
                . "WHERE viewed =1 "
                . "and courseid=$courseid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                
            } // end while
        } // end if $num > 0
    }

    function get_column_stat($columnname, $row) {
        
    }

    function get_columns_list() {
        $fields = array();
        $query = "DESCRIBE mdl_ws_survey";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            if (!in_array($row['Field'], $this->non_stat_fields)) {
                $fields[] = $row['Field'];
            }
        }
        return $fields;
    }

    /* ***************************************************************
     * 
     *          Code related to career college survey
     *    
     * ************************************************************** */

    function is_user_part_of_survey() {
        // Initialize default value
        $user = new stdClass();
        $user->userid = 0;
        $user->courseid = 0;

        $userid = $this->user->id;
        $query = "select * from mdl_course where category between 5 and 7";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $courses[] = $row['id'];
        }
        $courseslist = implode(',', $courses);
        $query = "select co.course, co.userid, ce.courseid, ce.userid "
                . "from mdl_course_completions co, mdl_certificates ce "
                . "where course in ($courseslist) "
                . "and co.userid=ce.userid and ce.courseid=co.course "
                . "and co.userid=$userid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $courseid = $row['course'];
            } // end while 
            $user->userid = $userid;
            $user->courseid = $courseid;
        } // end if $num > 0
        return $user;
    }

    function get_career_courses_array() {
        $query = "select * from mdl_course where category between 5 and 7";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $courses[] = $row['id'];
        }
        return $courses;
    }

    function is_completed($courseid, $userid) {
        $query = "select * from mdl_career_survey "
                . "where courseid=$courseid "
                . "and userid=$userid "
                . "and completed=1";
        $num = $this->db->numrows($query);
        return $num;
    }

    function survey_applicable() {
        $user = $this->is_user_part_of_survey();
        $current_courseid = $_REQUEST['id'];
        //echo "Current course: " . $current_courseid . "<br>";
        $career_courses = $this->get_career_courses_array();
        if (!in_array($current_courseid, $career_courses)) {
            return false;
        } // end if 
        $completed = $this->is_completed($user->courseid, $user->userid);
        if ($user->userid > 0 && $completed == 0) {
            return true;
        } // end if
        else {
            return false;
        } // end else
    }

    function get_career_collge_survey() {
        $list = "";
        $user = $this->is_user_part_of_survey();

        $list.="<div class='row-fluid' style='font-weight:bold;'>";
        $list.="<span class='span12'>The following is a list of items relative to the instructor and program.</span>";
        $list.="<input type='hidden' id='courseid' value='$user->courseid'>";
        $list.="<input type='hidden' id='userid' value='$user->userid'>";
        $list.="</div>";

        $list.="<div class='row-fluid'>";
        $list.="<span class='span8'>Did the instructor display a positive, professional attitude?*</span><span class='span2'><input type='radio' name='inst_att' value='1'>Yes</span><span class='span2'><input type='radio' name='inst_att' value='0'>No</span>";
        $list.="</div>";

        $list.="<div class='row-fluid'>";
        $list.="<span class='span8'>Did the instructor present the content in a clear and knowledgeable manner?*</span><span class='span2'><input type='radio' name='inst_man' value='1'>Yes</span><span class='span2'><input type='radio' name='inst_man' value='0'>No</span>";
        $list.="</div>";

        $list.="<div class='row-fluid'>";
        $list.="<span class='span8'>Did the program cover all the curriculum explained in the syllabus/program description?*</span><span class='span2'><input type='radio' name='prog_desc' value='1'>Yes</span><span class='span2'><input type='radio' name='prog_desc' value='0'>No</span>";
        $list.="</div>";

        $list.="<div class='row-fluid'>";
        $list.="<span class='span8'>Did the program meet your expectations?*</span><span class='span2'><input type='radio' name='prog_exp' value='1'>Yes</span><span class='span2'><input type='radio' name='prog_exp' value='0'>No</span>";
        $list.="</div>";

        $list.="<div class='row-fluid'>";
        $list.="<span class='span8'>Was the facility satisfactory?*</span><span class='span2'><input type='radio' name='facility' value='1'>Yes</span><span class='span2'><input type='radio' name='facility' value='0'>No</span>";
        $list.="</div>";

        $list.="<div class='row-fluid'>";
        $list.="<span class='span3'>Please explain any 'No' answers</span><span class='span9'><textarea id='reason_no' name='reason_no' style='width:400px;height:75px;'></textarea></span>";
        $list.="</div>";

        $list.="<div class='row-fluid'>";
        $list.="<span class='span3'>Would you recommend Medical 2 to friends, family, etc?</span><span class='span9'><textarea id='recommend' name='recommend' style='width:400px;height:75px;'></textarea></span>";
        $list.="</div>";

        $list.="<div class='row-fluid'>";
        $list.="<span class='span3'>How could we improve the program?</span><span class='span9'><textarea id='improve' name='improve' style='width:400px;height:75px;'></textarea></span>";
        $list.="</div>";

        $list.="<div class='row-fluid'>";
        $list.="<span class='span3'>Comments</span><span class='span9'><textarea id='comments' name='comments' style='width:400px;height:75px;'></textarea></span>";
        $list.="</div>";

        // Common block start
        $list.="<div class='row-fluid'>";
        $list.="<span class='span3'>Did you graduate?*</span>";
        $list.="<span class='span9'>";

        $list.="<div class='row-fluid'>";
        $list.="<span class='span12'><input type='radio' name='graduate_status' value='1'>Yes&nbsp; When &nbsp;<input type='text' id='graduate_date' style='width:312px;'></span>";
        $list.="</div>";

        $list.="<div class='row-fluid'>";
        $list.="<span class='span12'><input type='radio' name='graduate_status' value='0'>No &nbsp; Why? &nbsp; <textarea id='graduate_fail_reason' style='width:312px;height:75px;'></textarea></span>";
        $list.="</div>";

        $list.="</span>";
        $list.="</div>";
        // Common block end

        $list.="<div class='row-fluid'>";
        $list.="<span class='span3'>Have you taken your certification exam?*</span>";
        $list.="<span class='span9'>";

        $list.="<div class='row-fluid'>";
        $list.="<span class='span12'><input type='radio' name='exam_status' value='1'>Yes&nbsp;</span>";
        $list.="</div>";

        $list.="<div class='row-fluid'>";
        $list.="<span class='span12'><input type='radio' name='exam_status' value='0'>No &nbsp; If no Why or When? &nbsp; <textarea id='exam_fail_reason' style='width:227px;height:75px;'></textarea></span>";
        $list.="</div>";

        $list.="</span>";
        $list.="</div>";

        $list.="<div class='row-fluid'>";
        $list.="<span class='span3'>Are you working in the field of your program?*</span>";
        $list.="<span class='span9'>";

        $list.="<div class='row-fluid'>";
        $list.="<span class='span12'><input type='radio' name='has_job' value='1'>Yes&nbsp;Where &nbsp; <textarea id='job_place' style='width:312px;height:75px;'></textarea></span>";
        $list.="</div>";

        $list.="<div class='row-fluid'>";
        $list.="<span class='span12'><input type='radio' name='has_job' value='0'>No &nbsp; If no Why ? &nbsp; <textarea id='no_job_reason' style='width:280px;height:75px;'></textarea></span>";
        $list.="</div>";

        $list.="</span>";
        $list.="</div>";

        $list.="<div class='row-fluid'>";
        $list.="<span class='span12' id='survey_err' style='color:red;'></span>";
        $list.="</div>";

        $list.="<div class='row-fluid'>";
        $list.="<span class='span3'>&nbsp;</span>";
        $list.="<span class='span9'><button id='submit_career_survey'>Submit</button></span>";

        $list.="<div class='row-fluid'>";
        $list.="<span class='span12'><br/><br/><br/></span>";
        $list.="</div>";
        ?>

        <script type="text/javascript">

            $(document).ready(function () {
                console.log("ready!");
                $('#graduate_date').datepicker();
            });

        </script>

        <?php

        return $list;
    }

    function add_career_survey($d) {
        /*
         * 
          [courseid] => 41
          [userid] => 13734
          [inst_att] => 1
          [inst_man] => 1
          [prog_desc] => 1
          [prog_exp] => 0
          [facility] => 0
          [reason_no] => aaaa
          [recommend] => bbbb
          [improve] => bccccc
          [comments] => ddddd
          [graduate_status] => 1
          [graduate_date] => 23/04/2017
          [exam_status] => 1
          [exam_fail_reason] => eeeee
          [has_job] => 0
          [job_place] => dddd
          [no_job_reason] => sssss
         * 
         */
        $list = "";
        $date = date('m-d-Y', time());
        $completed = 1;

        $query = "insert into mdl_career_survey "
                . "(courseid,"
                . "userid,"
                . "inst_att,"
                . "inst_man,"
                . "prog_desc,"
                . "prog_exp,"
                . "facility,"
                . "completed,cdate) "
                . "values ($d->courseid,"
                . "$d->userid,"
                . "$d->inst_att,"
                . "$d->inst_man,"
                . "$d->prog_desc,"
                . "$d->prog_exp,"
                . "$d->facility,"
                . "$completed,'$date')";
        $this->db->query($query);

        /* Prepare email message to be sent */
        $program = $this->get_course_name($d->courseid);
        $user = $this->get_user_details($d->userid);
        $inst_att = $this->get_human_survey_value($d->inst_att);
        $inst_man = $this->get_human_survey_value($d->inst_man);
        $prog_desc = $this->get_human_survey_value($d->prog_desc);
        $prog_exp = $this->get_human_survey_value($d->prog_exp);
        $facility = $this->get_human_survey_value($d->facility);
        $graduate_status = $this->get_human_survey_value($d->graduate_status);
        $exam_status = $this->get_human_survey_value($d->exam_status);
        $has_job = $this->get_human_survey_value($d->has_job);

        $list.="<html>";
        $list.="<body>";

        $list.="<table align='center'>";

        $list.="<tr>";
        $list.="<td style='padding:15px;'>Program: </td><td style='padding:15px;'>$program</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td style='padding:15px;'>Student name</td><td style='padding:15px;'>$user->firstname $user->lastname</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td style='padding:15px;'>Date</td><td style='padding:15px;'>$date</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td style='padding:15px;'>Did the instructor display a positive, professional attitude?</td><td style='padding:15px;'>$inst_att</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td style='padding:15px;'>Did the instructor present the content in a clear and knowledgeable manner?</td><td style='padding:15px;'>$inst_man</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td style='padding:15px;'>Did the program cover all the curriculum explained in the syllabus/program description?</td><td style='padding:15px;'>$prog_desc</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td style='padding:15px;'>Did the program meet your expectations?</td><td style='padding:15px;'>$prog_exp</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td style='padding:15px;'>Was the facility satisfactory?</td><td style='padding:15px;'>$facility</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td style='padding:15px;'>Please explain any 'No' answers:</td><td style='padding:15px;'>$d->reason_no</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td style='padding:15px;'>Would you recommend Medical 2 to friends, family, etc?</td><td style='padding:15px;'>$d->recommend</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td style='padding:15px;'>How could we improve the program?</td><td style='padding:15px;'>$d->improve</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td style='padding:15px;'>Any comments</td><td style='padding:15px;'>$d->comments</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td style='padding:15px;'>Did you graduate?</td><td style='padding:15px;'>$graduate_status</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td style='padding:15px;'>When</td><td style='padding:15px;'>$d->graduate_date</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td style='padding:15px;'>If no, Why</td><td style='padding:15px;'>$d->reason_no</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td style='padding:15px;'>Have you taken your certification exam?</td><td style='padding:15px;'>$exam_status</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td style='padding:15px;'>If no, Why or When?</td><td style='padding:15px;'>$d->exam_fail_reason</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td style='padding:15px;'>Are you working in the field of your program?</td><td style='padding:15px;'>$has_job</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td style='padding:15px;'>Where</td><td style='padding:15px;'>$d->job_place</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td style='padding:15px;'>If no, Why?</td><td style='padding:15px;'>$d->no_job_reason</td>";
        $list.="</tr>";

        $list.="</table>";

        $list.="</html>";
        $list.="</body>";

        $m = new Mailer();
        $m->send_career_survey_result($list);
    }

    function get_human_survey_value($val) {
        $hval = ($val == 0) ? 'No' : 'Yes';
        return $hval;
    }

}
