<?php

/**
 * Description of Schedule
 *
 * @author sirromas
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/Programs.php';

class Schedule extends Programs {

    public function get_item_detail_page($courseid, $form_div = true, $state = false) {
        $query = "select id,fullname,summary,startdate,cost,discount_size "
                . "from mdl_course where id=$courseid and cost>0";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $item = new stdClass();
            foreach ($row as $key => $value) {
                $item->$key = $value;
            }
        } // end foreach        
        $list = $this->create_item_detail_page($item, $form_div, $state);
        return $list;
    }

    public function create_item_detail_page($item, $form_div = true, $state = false) {
        $list = "";
        if ($form_div == true) {
            $list.="<br/><div  class='form_div'>";
        }
        $blocks = $this->get_item_cost_blocks($item);

        $list.="<div class='panel panel-default' id='program_section' style='margin-bottom:0px;'>";
        $list.="<div class='panel-heading' style='text-align:left;'><h5 class='panel-title'>$item->fullname</h5></div>";
        $list.="<div class='panel-body'>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.= "<span class='span6'>$item->summary</span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.= "<span class='span4'>Start date <strong>" . date('Y-m-d', $item->startdate) . "</strong></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.= "<span class='span6'>" . $blocks['item_cost'] . "</span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.= "<span class='span6'>" . $blocks['item_group_cost'] . "</span>";
        $list.="</div>";

        $list.="<br/><div class='container-fluid' style='text-align:left;'>";
        if ($state == false) {
            $list.= "<span class='span2'><a href='http://" . $_SERVER['SERVER_NAME'] . "/index.php/register/index/$item->id'><button id='program_$item->id' class='btn btn-primary'>Register</button></a></span>";
        } else {
            $list.= "<span class='span2'><a href='http://" . $_SERVER['SERVER_NAME'] . "/index.php/register/index/$item->id'><button id='program_$item->id/$state' class='btn btn-primary'>Register</button></a></span>";
        }
        $list.="</div>";

        $list.="</div>"; // end of panel-body
        $list.="</div>"; // end of panel panel-default                

        if ($form_div == true) {
            $list.= "</div>"; // end of form div
        }
        return $list;
    }

    public function get_state_programs($stateid) {
        $list = "";
        $courses = array();
        $query = "select * from mdl_course_to_state where stateid=$stateid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $courses[] = $row['courseid'];
            } // end while
            foreach ($courses as $courseid) {
                $list.=$this->get_item_detail_page($courseid, false, true);
            } // end foreach
        } // end if $num>0
        else {
            $list.="<div class='container-fluid' style='text-align:center;'>";
            $list.= "<span class='span9'>There are no programs found in selected state</span>";
            $list.="</div>";
        } // end else 
        return $list;
    }

}
