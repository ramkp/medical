<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php';

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

}
