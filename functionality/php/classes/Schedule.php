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
        //echo "<br>Query:" . $query . " <br>";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $item = new stdClass();
                foreach ($row as $key => $value) {
                    $item->$key = $value;
                }
            } // end foreach        
            //echo "<br>------Get Item Detail page item:";
            //print_r($item);
            //echo "<br>";
            //$list = $this->create_item_detail_page($item, $form_div, $state);
            $list = $this->create_state_item_block($item, $form_div, $state);
        } // end if $num > 0        
        return $list;
    }

    public function create_state_item_block($item, $form_div = true, $state = false) {

        $list = "";
        if ($form_div == true) {
            $list.="<br/><div  class='form_div'>";
        }

        $blocks = $this->get_item_cost_blocks($item);
        $list.="<div class='panel panel-default' id='program_section' style='margin-bottom:0px;'>";
        $list.="<div class='panel-heading' style='text-align:left;'><h5 class='panel-title'>$item->fullname</h5></div>";
        $list.="<div class='panel-body'>";
        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.= "<span class='span4'>Start date <strong>" . date('Y-m-d', $item->startdate) . "</strong></span>";
        $list.="</div>";
        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.= "<span class='span6'>" . $blocks['item_cost'] . "</span>";
        $list.="</div>";
        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.= "<span class='span6'>" . $blocks['item_group_cost'] . "</span>";
        $list.="</div>";
        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.= "<span class='span6'><a href='http://" . $_SERVER['SERVER_NAME'] . "/programs/detailes/$item->id'>More</a></span>";
        $list.="</div>";

        $list.="<br/><div class='container-fluid' style='text-align:left;'>";
        if ($state == false) {
            $list.= "<span class='span2'><a href='http://" . $_SERVER['SERVER_NAME'] . "/register2/brain_register/$item->id'><button id='program_$item->id' class='btn btn-primary'>Register</button></a></span>";
        }  // end if $state == false        
        else {
            $list.= "<span class='span2'><a href='http://" . $_SERVER['SERVER_NAME'] . "/register2/brain_register/$item->id'><button id='program_$item->id/$state' class='btn btn-primary'>Register</button></a></span>";
        }
        $list.="</div>";

        $list.="</div>"; // end of panel-body
        $list.="</div>"; // end of panel panel-default  

        if ($form_div == true) {
            $list.= "</div>"; // end of form div
        } // end if $form_div == true

        return $list;
    }

    public function create_item_detail_page($item, $form_div = true, $state = false) {

        //echo "<br>-----------Create item detail page ------------<br>";
        //print_r($item);
        //echo "<br>----------------------<br>";

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
            $list.= "<span class='span2'><a href='http://" . $_SERVER['SERVER_NAME'] . "/register2/brain_register/$item->id'><button id='program_$item->id' class='btn btn-primary'>Register</button></a></span>";
        }  // end if $state == false        
        else {
            $list.= "<span class='span2'><a href='http://" . $_SERVER['SERVER_NAME'] . "/register2/brain_register/$item->id'><button id='program_$item->id/$state' class='btn btn-primary'>Register</button></a></span>";
        }
        $list.="</div>";

        $list.="</div>"; // end of panel-body
        $list.="</div>"; // end of panel panel-default                

        if ($form_div == true) {
            $list.= "</div>"; // end of form div
        } // end if $form_div == true
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
            //echo "<br>Get state program---------------<br>";
            //print_r($courses);
            //echo "<br>---------------<br>";
            foreach ($courses as $courseid) {
                if ($courseid != '') {
                    $list.=$this->get_item_detail_page($courseid, false, true);
                } // end if $courseid!=''
            } // end foreach
        } // end if $num>0
        else {
            $list.="<div class='container-fluid' style='text-align:center;'>";
            $list.= "<span class='span9'>There are no programs found in selected state</span>";
            $list.="</div>";
        } // end else 
        return $list;
    }

    public function get_course_name($courseid) {
        $query = "select fullname from mdl_course where id=$courseid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $name = $row['fullname'];
        }
        return $name;
    }

    public function get_state_name($stateid) {
        $query = "select * from mdl_states where id=$stateid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $state = $row['state'];
        }
        return $state;
    }

    function get_shared_schedule($courseid, $state = null) {
        $list = "";
        $sch_arr = array();
        // 1.Get scheduler id
        $query = "select id from mdl_scheduler where course in (44,45)";
        $result = $this->db->query($query);
        $num = $this->db->numrows($query);
        $now = time() - 86400;
        if ($num > 0) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $sch_arr[] = $row['id'];
            } // end while
            $sch_list = implode(',', $sch_arr);
            // 2. Get slots list
            if ($state == null) {
                $query = "select * from mdl_scheduler_slots "
                        . "where schedulerid in ($sch_list) "
                        . "and starttime>$now order by starttime";
            } // end if $state==null
            else {
                $statename = $this->get_state_name($state);
                $query = "select * from mdl_scheduler_slots "
                        . "where schedulerid in ($sch_list) "
                        . "and appointmentlocation like '%$statename%' "
                        . "and starttime>$now order by starttime";
            } // end else 
            $coursename = $this->get_course_name($courseid);
            $list.="<div class='panel panel-default' id='schedule_section' style='margin-bottom:0px;'>";
            $list.="<div class='panel-heading' style='text-align:left;'><h5 class='panel-title'>Phlebotomy & EKG Certification Workshops</h5></div>";
            $list.="<div class='panel-body'>";

            $result = $this->db->query($query);
            $num = $this->db->numrows($query);

            if ($num > 0) {
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $human_date = date('m-d-Y', $row['starttime']);
                    $hours_num = round($row['duration'] / 60);
                    $human_start_time = date('m-d-Y H:i', $row['timemodified']);
                    $end_time = $row['timemodified'] + $hours_num * 3600;
                    $human_end_date = date('m-d-Y H:i', $end_time);
                    $locations = explode("/", $row['appointmentlocation']);
                    if (count($locations) == 0) {
                        $locations = explode(",", $row['appointmentlocation']);
                    }
                    $state = $locations[0];
                    $city = $locations[1];
                    $location = $city . " , " . $state;
                    
                    if ($row['schedulerid']==6) {
                              $notes="<b>Phlebotomy Certification Workshop</b><br>".$row['notes']."";
                        }                        
                        if ($row['schedulerid']==5) {
                            $notes="<b>Phlebotomy With EKG Certification Workshop</b><br>".$row['notes']."";
                        }
                    
                    $list.="<div class='container-fluid' style='text-align:left;'>";
                    $list.= "<span class='span1'>$human_date</span>";
                    $list.= "<span class='span2'>$location</span>";
                    //$list.= "<span class='span3'>" . $row['notes'] . "</span>";
                    $list.= "<span class='span3'>" . $notes . "</span>";
                    $list.= "<span class='span1'>9am -  5pm</span>";
                    if ($row['schedulerid'] == 6) {
                        $list.= "<span class='span1'><a href='http://" . $_SERVER['SERVER_NAME'] . "/register2/brain_register/44/" . $row['id'] . "'><button class='btn btn-primary'>Register</button></a></span>";
                    }
                    if ($row['schedulerid'] == 5) {
                        $list.= "<span class='span1'><a href='http://" . $_SERVER['SERVER_NAME'] . "/register2/brain_register/45/" . $row['id'] . "'><button class='btn btn-primary'>Register</button></a></span>";
                    }
                    $list.="</div>";

                    $list.="<div class='container-fluid' style='text-align:left;'>";
                    $list.= "<span class='span9'><hr/></span>";
                    $list.="</div>";
                } // end while

                $list.="</div>"; // end of panel-body
                $list.="</div>"; // end of panel panel-default           
            } // end if $num > 0 when slots are available at the course
            else {
                $list.="<div class='container-fluid' style='text-align:left;'>";
                $list.= "<span class='span6'>This program does not have schedule in selected state</span>";
                $list.="</div>";

                $list.="</div>"; // end of panel-body
                $list.="</div>"; // end of panel panel-default           
            }
        } // end if $num > 0 when scheduler is available at the course
        return $list;
    }

    function get_course_schedule($courseid, $state = null) {
        //echo "Course id: ".$courseid."<br>";
        
        $list = "";
        //if ($courseid == 44 || $courseid == 45) {
          //  $list.=$this->get_shared_schedule($courseid, $state);
        //} // end if $courseid==44 || $courseid==45
        //else {
            // 1.Get scheduler id
            $query = "select id from mdl_scheduler where course=$courseid";
            $result = $this->db->query($query);
            $num = $this->db->numrows($query);
            $now = time() - 86400;
            if ($num > 0) {
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $schedulerid = $row['id'];
                } // end while
                // 2. Get slots list
                if ($state == null) {
                    $query = "select * from mdl_scheduler_slots "
                            . "where schedulerid=$schedulerid "
                            . "and starttime>$now order by starttime";
                } // end if $state==null
                else {
                    $statename = $this->get_state_name($state);
                    $query = "select * from mdl_scheduler_slots "
                            . "where schedulerid=$schedulerid "
                            . "and appointmentlocation like '%$statename%' "
                            . "and starttime>$now order by starttime";
                } // end else 
                $coursename = $this->get_course_name($courseid);
                $list.="<div class='panel panel-default' id='schedule_section' style='margin-bottom:0px;'>";
                $list.="<div class='panel-heading' style='text-align:left;'><h5 class='panel-title'>$coursename</h5></div>";
                $list.="<div class='panel-body'>";

                $result = $this->db->query($query);
                $num = $this->db->numrows($query);

                if ($num > 0) {
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        $human_date = date('m-d-Y', $row['starttime']);
                        $hours_num = round($row['duration'] / 60);
                        $human_start_time = date('m-d-Y H:i', $row['timemodified']);
                        $end_time = $row['timemodified'] + $hours_num * 3600;
                        $human_end_date = date('m-d-Y H:i', $end_time);
                        $locations = explode("/", $row['appointmentlocation']);
                        if (count($locations) == 0) {
                            $locations = explode(",", $row['appointmentlocation']);
                        }
                        $state = $locations[0];
                        $city = $locations[1];
                        $location = $city . " , " . $state;
                        $list.="<div class='container-fluid' style='text-align:left;'>";
                        $list.= "<span class='span1'>$human_date</span>";
                        $list.= "<span class='span2'>$location</span>";
                        $list.= "<span class='span3'>" . $row['notes'] . "</span>";
                        $list.= "<span class='span1'>9am -  5pm</span>";
                        $list.= "<span class='span1'><a href='https://" . $_SERVER['SERVER_NAME'] . "/register2/brain_register/$courseid/" . $row['id'] . "'><button class='btn btn-primary'>Register</button></a></span>";
                        $list.="</div>";

                        $list.="<div class='container-fluid' style='text-align:left;'>";
                        $list.= "<span class='span9'><hr/></span>";
                        $list.="</div>";
                    } // end while

                    $list.="</div>"; // end of panel-body
                    $list.="</div>"; // end of panel panel-default           
                } // end if $num > 0 when slots are available at the course
                else {
                    $list.="<div class='container-fluid' style='text-align:left;'>";
                    $list.= "<span class='span6'>This program does not have schedule in selected state</span>";
                    $list.="</div>";

                    $list.="</div>"; // end of panel-body
                    $list.="</div>"; // end of panel panel-default           
                }
            } // end if $num > 0 when scheduler is available at the course
        //} // end else 
        return $list;
    }

}
