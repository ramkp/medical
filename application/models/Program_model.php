<?php

/**
 * Description of Program
 *
 * @author sirromas
 */
class program_model extends CI_Model {

    public $host;

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->host = $_SERVER['SERVER_NAME'];
    }

    public function get_category_id($cat_name) {
        $query = "select id, name "
                . "from mdl_course_categories "
                . "where name like '%$cat_name%'";
        //echo $query;
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $id = $row->id;
        }
        return $id;
    }

    public function get_category_name($cat_id) {
        $query = "select name from mdl_course_categories where id=$cat_id";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $name = $row->name;
        }
        return $name;
    }

    public function get_course_image_path($courseid) {
        switch ($courseid) {
            case 41:
                $path = "https://" . $_SERVER['SERVER_NAME'] . "/assets/logo/cna best.jpg";
                break;
            case 44:
                $path = "https://" . $_SERVER['SERVER_NAME'] . "/assets/logo/phlebotomy.jpg";
                break;
            case 45:
                $path = "https://" . $_SERVER['SERVER_NAME'] . "/assets/logo/ekg.jpg";
                break;
            case 46:
                $path = "https://" . $_SERVER['SERVER_NAME'] . "/assets/logo/picc line.jpg";
                break;
            case 47:
                $path = "https://" . $_SERVER['SERVER_NAME'] . "/assets/logo/IV%20Picture.jpg";
                break;
            case 48:
                $path = "https://" . $_SERVER['SERVER_NAME'] . "/assets/logo/IV%20Picture.jpg";
                break;
            case 49:
                $path = "https://" . $_SERVER['SERVER_NAME'] . "/assets/logo/ob tech.jpg";
                break;
            case 50:
                $path = "https://" . $_SERVER['SERVER_NAME'] . "/assets/logo/phlebotomy.jpg";
                break;
            case 51:
                $path = "https://" . $_SERVER['SERVER_NAME'] . "/assets/logo/phlebotomy.jpg";
                break;
            case 52:
                $path = "https://" . $_SERVER['SERVER_NAME'] . "/assets/logo/IV%20Picture.jpg";
                break;
            case 53:
                $path = "https://" . $_SERVER['SERVER_NAME'] . "/assets/logo/ob tech.jpg";
                break;
            case 54:
                $path = "https://" . $_SERVER['SERVER_NAME'] . "/assets/logo/IV%20Picture.jpg";
                break;
            case 55:
                $path = "https://" . $_SERVER['SERVER_NAME'] . "/assets/logo/Med%20asst%201.jpg";
                break;
            case 56:
                $path = "https://" . $_SERVER['SERVER_NAME'] . "/assets/logo/Medical%20office%20asst%20pic.jpg";
                break;
            case 57:
                $path = "https://" . $_SERVER['SERVER_NAME'] . "/assets/logo/phlebotomy.jpg";
                break;
        }
        return $path;
    }

    public function get_category_items($cat_id) {
        $items = array();
        //$cat_id = $this->get_category_id($cat_name);
        $cat_name = $this->get_category_name($cat_id);
        $query = "select * "
                . "from mdl_course where category=$cat_id and visible=1 and cost>0 order by fullname desc";
        $result = $this->db->query($query);
        $num = $result->num_rows();
        if ($num > 0) {
            foreach ($result->result() as $row) {
                $item = new stdClass();
                foreach ($row as $key => $value) {
                    $item->$key = $value;
                }
                $item->path = $this->get_course_image_path($item->id);
                $items[] = $item;
            } // end foreach
        } // end if $num>0
        //$list = $this->create_items_block($items, $cat_name);
        $list = $this->create_program_items_page($items, $cat_name);
        return $list;
    }

    public function get_group_discount($id) {
        $query = "select group_discount_size "
                . "from mdl_group_discount "
                . "where courseid=$id";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $discount = $row->group_discount_size;
        }
        return $discount;
    }

    public function calculate_item_cost($id, $item_cost, $item_discount) {
        $group_discount = $this->get_group_discount($id);
        $clean_item_cost = $item_cost - round(($item_cost * $item_discount) / 100, 2);
        $group_cost = $item_cost - round(($item_cost * $group_discount) / 100, 2);
        $cost = array('item_cost' => $clean_item_cost, 'group_cost' => $group_cost);
        return $cost;
    }

    public function get_item_cost_blocks($item) {
        $cost_block = "";
        $cost_group_block = "";
        $item_costs = $this->calculate_item_cost($item->id, $item->cost, $item->discount_size);
        if ($item->discount_size > 0) {
            $cost_block.="Regular cost <strong>$$item->cost .</strong> Your personal cost <strong>$" . $item_costs['item_cost'] . "</strong>";
        } // end if $item->discount_size > 0
        else {
            $cost_block.="Cost <strong>$$item->cost</strong>";
        }
        $group_discount = $this->get_group_discount($item->id);
        if ($group_discount > 0) {
            $cost_group_block.="Want to register group? Get additional discount of <strong>$group_discount%</strong>";
        }  // end if $group_discount > 0        
        $blocks = array('item_cost' => $cost_block, 'item_group_cost' => $cost_group_block);
        return $blocks;
    }

    public function get_school_items($cat_id) {
        $query = "select id,fullname,summary,startdate,cost,discount_size "
                . "from mdl_course where category=$cat_id and cost>0";
        $result = $this->db->query($query);
        $num = $result->num_rows();
        if ($num > 0) {
            foreach ($result->result() as $row) {
                $item = new stdClass();
                foreach ($row as $key => $value) {
                    $item->$key = $value;
                }
                $items[] = $item;
            } // end foreach
        } // end if $num>0
        return $items;
    }

    public function get_school_page($cat_name) {

        $cat_id = $this->get_category_id($cat_name);
        $list = "";

        $list.="<br/><div class='container-fluid' style='text-align:left;'>";
        $list.= "<span class='span9'>A <strong>nursing school</strong> is a type of educational "
                . "institution, or part thereof, providing education and "
                . "training to become a fully qualified nurse. The nature of "
                . "nursing education and nursing qualifications varies "
                . "considerably across the world. Please select on the map your closest location.</span>";
        $list.="</div>";

        $list.="<br/><div class='container-fluid' style='text-align:left;'>";
        $list.= "<span class='span2'><a href='https://" . $_SERVER['SERVER_NAME'] . "/index.php/register/index/$cat_id'><button id='program_$cat_id' class='btn btn-primary'>Register</button></a></span>";
        $list.="</div>";


        $list.="<div class='container-fluid' style='text-align:center;'>";
        $list.= "<span class='span9'><hr/></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:center;'>";
        $list.= "<span class='span9' id='map' style='position: relative;height:675px;'></span>";
        $list.="</div>";

        return $list;
    }

    function get_size() {
        $list = "height='110' width='154'";
        return $list;
    }

    public function create_program_items_page($items, $cat_name) {
        $list = "";
        $list.="<br/><div  class='form_div'>";
        //print_r($items);
        $size = $this->get_size();
        if (count($items) > 0) {
            $list.="<div class='courses category-browse category-browse-3'>";
            foreach ($items as $item) {
                //echo "Item id: ".$item->id."<br>";
                $blocks = $this->get_item_cost_blocks($item);
                $has_schedule = $this->is_course_has_schedule($item->id);
                if ($has_schedule > 0) {
                    $register_button = "<a href='http://" . $_SERVER['SERVER_NAME'] . "/index.php/programs/schedule/$item->id'><button class='btn btn-primary'>Schedule/Register</button></a>";
                } // end if $has_schedule>0
                else {
                    $register_button = "<a href='https://" . $_SERVER['SERVER_NAME'] . "/index.php/register/index/$item->id/0'><button class='btn btn-primary'>Register</button></a>";
                } // end else    
                if ($cat_name == 'Hands-On Certification Workshops') {
                    if ($item->id == 45) {
                        $summary_string = (strlen(strip_tags($item->summary)) > 375) ? substr(strip_tags($item->summary), 0, 275) . ' ...' : strip_tags($item->summary);
                        $list.= "<div class='coursebox clearfix odd first' data-courseid='12' data-type='1'><div class='info'><h3 class='coursename'><a class='' href='https://" . $_SERVER['SERVER_NAME'] . "/index.php/programs/detailes/$item->id'>$item->fullname</a></h3><div class='moreinfo'></div><div class='enrolmenticons'>$register_button</div></div><div class='content'><div class='summary'><div class='no-overflow'><div class='course-summary-heading'><strong> $cat_name</strong></div>
                    <p><img src='$item->path' alt='program' style='vertical-align:text-bottom; margin: 0 .5em;' $size></p></div></div><ul class='teachers'><p align='justify'>$summary_string</p><p align='left'>" . $blocks['item_cost'] . "</p><p align='left'>" . $blocks['item_group_cost'] . "</p><p align='left'><a href='http://" . $_SERVER['SERVER_NAME'] . "/index.php/programs/detailes/$item->id'>More</a></p></ul></div></div>";
                    } // end if $item->id==45
                    if ($item->id == 44) {
                        $summary_string = (strlen(strip_tags($item->summary)) > 375) ? substr(strip_tags($item->summary), 0, 275) . ' ...' : strip_tags($item->summary);
                        $list.= "<div class='coursebox clearfix odd first' data-courseid='12' data-type='1'><div class='info'><h3 class='coursename'><a class='' href='https://" . $_SERVER['SERVER_NAME'] . "/index.php/programs/detailes/$item->id'>$item->fullname</a></h3><div class='moreinfo'></div><div class='enrolmenticons'>$register_button</div></div><div class='content'><div class='summary'><div class='no-overflow'><div class='course-summary-heading'><strong> $cat_name</strong></div>
                    <p><img src='$item->path' alt='program' style='vertical-align:text-bottom; margin: 0 .5em;'  $size></p></div></div><ul class='teachers'><p align='justify'>$summary_string</p><p align='left'>" . $blocks['item_cost'] . "</p><p align='left'>" . $blocks['item_group_cost'] . "</p><p align='left'><a href='http://" . $_SERVER['SERVER_NAME'] . "/index.php/programs/detailes/$item->id'>More</a></p></ul></div></div>";
                    } // end if $item->id==45
                    if ($item->id == 54) {
                        $summary_string = (strlen(strip_tags($item->summary)) > 375) ? substr(strip_tags($item->summary), 0, 275) . ' ...' : strip_tags($item->summary);
                        $list.= "<div class='coursebox clearfix odd first' data-courseid='12' data-type='1'><div class='info'><h3 class='coursename'><a class='' href='https://" . $_SERVER['SERVER_NAME'] . "/index.php/programs/detailes/$item->id'>$item->fullname</a></h3><div class='moreinfo'></div><div class='enrolmenticons'>$register_button</div></div><div class='content'><div class='summary'><div class='no-overflow'><div class='course-summary-heading'><strong> $cat_name</strong></div>
                    <p><img src='$item->path' alt='program' style='vertical-align:text-bottom; margin: 0 .5em;'   $size></p></div></div><ul class='teachers'><p align='justify'>$summary_string</p><p align='left'>" . $blocks['item_cost'] . "</p><p align='left'>" . $blocks['item_group_cost'] . "</p><p align='left'><a href='http://" . $_SERVER['SERVER_NAME'] . "/index.php/programs/detailes/$item->id'>More</a></p></ul></div></div>";
                    } // end if $item->id==45
                    if ($item->id == 46) {
                        $summary_string = (strlen(strip_tags($item->summary)) > 375) ? substr(strip_tags($item->summary), 0, 275) . ' ...' : strip_tags($item->summary);
                        $list.= "<div class='coursebox clearfix odd first' data-courseid='12' data-type='1'><div class='info'><h3 class='coursename'><a class='' href='https://" . $_SERVER['SERVER_NAME'] . "/index.php/programs/detailes/$item->id'>$item->fullname</a></h3><div class='moreinfo'></div><div class='enrolmenticons'>$register_button</div></div><div class='content'><div class='summary'><div class='no-overflow'><div class='course-summary-heading'><strong> $cat_name</strong></div>
                    <p><img src='$item->path' alt='program' style='vertical-align:text-bottom; margin: 0 .5em;'   $size></p></div></div><ul class='teachers'><p align='justify'>$summary_string</p><p align='left'>" . $blocks['item_cost'] . "</p><p align='left'>" . $blocks['item_group_cost'] . "</p><p align='left'><a href='http://" . $_SERVER['SERVER_NAME'] . "/index.php/programs/detailes/$item->id'>More</a></p></ul></div></div>";
                    } // end if $item->id==45
                } // end if $cat_name == 'Hands-On Certification Workshops'                
                else {
                    $summary_string = (strlen(strip_tags($item->summary)) > 375) ? substr(strip_tags($item->summary), 0, 275) . ' ...' : strip_tags($item->summary);
                    $list.= "<div class='coursebox clearfix odd first' data-courseid='12' data-type='1'><div class='info'><h3 class='coursename'><a class='' href='https://" . $_SERVER['SERVER_NAME'] . "/index.php/programs/detailes/$item->id'>$item->fullname</a></h3><div class='moreinfo'></div><div class='enrolmenticons'>$register_button</div></div><div class='content'><div class='summary'><div class='no-overflow'><div class='course-summary-heading'><strong> $cat_name</strong></div>
                    <p><img src='$item->path' alt='program' style='vertical-align:text-bottom; margin: 0 .5em;'    $size></p></div></div><ul class='teachers'><p align='justify'>$summary_string</p><p align='left'>" . $blocks['item_cost'] . "</p><p align='left'>" . $blocks['item_group_cost'] . "</p><p align='left'><a href='http://" . $_SERVER['SERVER_NAME'] . "/index.php/programs/detailes/$item->id'>More</a></p></ul></div></div>";
                }
            } // end foreach            

            $list.="</div>";
        } //end if count($items) > 0)
        else {
            $list.="<div class='panel panel-default' id='program_section' style='margin-bottom:0px;'>";
            $list.="<div class='panel-heading' style='text-align:left;'><h5 class='panel-title'>$cat_name</h5></div>";
            $list.="<div class='panel-body'>";
            $list.="<div class='container-fluid'>";
            $list.="<span class='span6'>No items found</span>";
            $list.="</div>"; // end of container-fluid
            $list.="</div>"; // end of panel-body
            $list.="</div>"; // end of panel panel-default
        } // end else
        $list.="</div>"; // end of form div
        return $list;
    }

    public function is_course_has_schedule($courseid, $state = null) {
        $query = "select id from mdl_scheduler where course=$courseid";
        $result = $this->db->query($query);
        $num = $result->num_rows();
        if ($num > 0) {
            foreach ($result->result() as $row) {
                $schedulerid = $row->id;
            } // end foreach
            // 2. Get slots list
            if ($state == null) {
                $query = "select * from mdl_scheduler_slots "
                        . "where schedulerid=$schedulerid order by starttime";
            } // end if $state==null
            else {
                $statename = $this->get_state_name();
                $query = "select * from mdl_scheduler_slots "
                        . "where schedulerid=$schedulerid "
                        . "and appointmentlocation like '%$statename%' "
                        . "order by starttime";
            } // end else             
            $result = $this->db->query($query);
            $num = $result->num_rows();
        } // end if $num > 0
        return $num;
    }

    public function create_items_block($items, $cat_name = null) {
        $list = "";
        $list.="<br/><div  class='form_div'>";
        if (count($items) > 0) {
            foreach ($items as $item) {
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
                $list.= "<span class='span2'><a href='https://" . $_SERVER['SERVER_NAME'] . "/index.php/register/index/$item->id'><button id='program_$item->id' class='btn btn-primary'>Register</button></a></span>";
                $list.="</div>";

                $list.="</div>"; // end of panel-body
                $list.="</div>"; // end of panel panel-default                
            } // end foreach
        } // end if count($items)>0
        else {
            $list.="<div class='panel panel-default' id='program_section' style='margin-bottom:0px;'>";
            $list.="<div class='panel-heading' style='text-align:left;'><h5 class='panel-title'>$cat_name</h5></div>";
            $list.="<div class='panel-body'>";

            $list.="<div class='container-fluid'>";
            $list.="<span class='span6'>No items found</span>";
            $list.="</div>"; // end of container-fluid

            $list.="</div>"; // end of panel-body
            $list.="</div>"; // end of panel panel-default
        }

        $list.= "</div>"; // end of form div
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
        $has_schedule = $this->is_course_has_schedule($item->id);
        if ($has_schedule > 0) {
            $list.= "<span class='span2'><a href='https://" . $_SERVER['SERVER_NAME'] . "/index.php/programs/schedule/$item->id'><button id='program_$item->id' class='btn btn-primary'>Schedule/Register</button></a></span>";
        } // end if $has_schedule>0
        else {
            $list.="<a href='https://" . $_SERVER['SERVER_NAME'] . "/index.php/register/index/$item->id/0'><button class='btn btn-primary'>Register</button></a>";
        } // end else         
        $list.="</div>";

        $list.="</div>"; // end of panel-body
        $list.="</div>"; // end of panel panel-default                

        if ($form_div == true) {
            $list.= "</div>"; // end of form div
        }
        return $list;
    }

    public function get_item_detail_page($courseid, $form_div = true, $state = false) {
        $query = "select id,fullname,summary,startdate,cost,discount_size "
                . "from mdl_course where id=$courseid and cost>0";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $item = new stdClass();
            foreach ($row as $key => $value) {
                $item->$key = $value;
            }
        } // end foreach        
        $list = $this->create_item_detail_page($item, $form_div, $state);
        return $list;
    }

    public function get_states_list() {
        $drop_down = "";
        $drop_down.="<select id='schedule_states' style='width:120px;'>";
        $drop_down.="<option value='0' selected>All states</option>";
        $query = "select * from mdl_states order by state";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $drop_down.="<option value='$row->id'>$row->state</option>";
        }
        $drop_down.="</select>";
        return $drop_down;
    }

    public function get_courses_list($courseid) {
        $items = array();
        $query = "select id, fullname from mdl_course where cost>0 order by fullname";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $item = new stdClass();
            foreach ($row as $key => $value) {
                $item->$key = $value;
            }
            $has_schedule = $this->is_course_has_schedule($item->id);
            if ($has_schedule > 0) {
                $items[] = $item;
            }
        } // end foreach
        $drop_down = "";
        $drop_down.="<select id='schedule_courses' style='width:120px;'>";
        $drop_down.="<option value='0' selected>Programs</option>";
        foreach ($items as $item) {
            if ($item->id == $courseid) {
                $drop_down.="<option value='$item->id' selected>$item->fullname</option>";
            } // end if 
            else {
                $drop_down.="<option value='$item->id'>$item->fullname</option>";
            }
        } // end foreach
        $drop_down.="</select>";
        return $drop_down;
    }

    public function get_all_courses() {
        $list = "";
        $query = "select * from mdl_course where cost>0 order by fullname ";
        $result = $this->db->query($query);
        $num = $result->num_rows();
        if ($num > 0) {
            foreach ($result->result() as $row) {
                $item = new stdClass();
                foreach ($row as $key => $value) {
                    $item->$key = $value;
                }
                $items[] = $item;
            } // end foreach
            foreach ($items as $item) {
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
                $list.= "<span class='span6'><a href='https://" . $_SERVER['SERVER_NAME'] . "/index.php/programs/detailes/$item->id'>More</a></span>";
                $list.="</div>";

                $list.="<br/><div class='container-fluid' style='text-align:left;'>";
                $list.= "<span class='span2'><a href='https://" . $_SERVER['SERVER_NAME'] . "/index.php/register/index/$item->id'><button id='program_$item->id' class='btn btn-primary'>Register</button></a></span>";
                $list.="</div>";

                $list.="</div>"; // end of panel-body
                $list.="</div>"; // end of panel panel-default  
            } // end foreach
        } // end if $num > 0
        return $list;
    }

    public function get_course_name($courseid) {
        $query = "select fullname from mdl_course where id=$courseid";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $name = $row->fullname;
        }
        return $name;
    }

    public function get_state_name($stateid) {
        $query = "select * from mdl_states where id=$stateid";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $state = $row->state;
        }
        return $state;
    }

    public function get_shared_schedule($courseid, $state = null) {
        $list = "";
        $sch_arr=array();
        // 1.Get scheduler id
            $query = "select id from mdl_scheduler where course in (44,45)";
            $result = $this->db->query($query);
            $num = $result->num_rows();
            $now = time()-86400;
            if ($num > 0) {
                foreach ($result->result() as $row) {
                    $sch_arr[]= $row->id;
                } // end foreach
                $sch_list= implode(',', $sch_arr);
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
                            . "order by starttime";
                } // end else 
                $coursename = $this->get_course_name($courseid);
                $list.="<div class='panel panel-default' id='schedule_section' style='margin-bottom:0px;'>";
                $list.="<div class='panel-heading' style='text-align:left;'><h5 class='panel-title'>Phlebotomy & EKG Certification Workshops</h5></div>";
                $list.="<div class='panel-body'>";

                $result = $this->db->query($query);
                $num = $result->num_rows();
                date_default_timezone_set('Pacific/Wallis');
                if ($num > 0) {
                    foreach ($result->result() as $row) {
                        $human_date = date('m-d-Y', $row->starttime);
                        $hours_num = round($row->duration / 60);
                        $human_start_time = date('m-d-Y H:i', $row->timemodified);
                        $end_time = $row->timemodified + $hours_num * 3600;
                        $human_end_date = date('m-d-Y H:i', $end_time);
                        //$locations = preg_split("/\W|_/", $row->appointmentlocation);
                        $locations = explode("/", $row->appointmentlocation);
                        if (count($locations) == 0) {
                            $locations = explode(",", $row->appointmentlocation);
                        }
                        $state = $locations[0];
                        $city = $locations[1];
                        $location = $city . " , " . $state;
                        
                        if ($row->schedulerid==6) {
                              $notes="<b>Phlebotomy Certification Workshop</b><br>$row->notes";
                        }                        
                        if ($row->schedulerid==5) {
                            $notes="<b>Phlebotomy With EKG Certification Workshop</b><br>$row->notes";
                        }
                        
                        $list.="<div class='container-fluid' style='text-align:left;'>";
                        $list.= "<span class='span1'>$human_date</span>";
                        $list.= "<span class='span2'>$location</span>";
                        //$list.= "<span class='span3'>$row->notes</span>";
                        $list.= "<span class='span3'>$notes</span>";
                        $list.= "<span class='span1'>9am -  5pm</span>";
                        //echo "Scheduler id: ".$row->schedulerid."<br>";
                        if ($row->schedulerid==6) {
                            $list.= "<span class='span1'><a href='https://" . $_SERVER['SERVER_NAME'] . "/index.php/register/index/44/$row->id'><button class='btn btn-primary'>Register</button></a></span>";
                        }                        
                        if ($row->schedulerid==5) {
                            $list.= "<span class='span1'><a href='https://" . $_SERVER['SERVER_NAME'] . "/index.php/register/index/45/$row->id'><button class='btn btn-primary'>Register</button></a></span>";
                        }                        
                        $list.="</div>";

                        $list.="<div class='container-fluid' style='text-align:left;'>";
                        $list.= "<span class='span9'><hr/></span>";
                        $list.="</div>";
                    } // end foreach

                    $list.="</div>"; // end of panel-body
                    $list.="</div>"; // end of panel panel-default           
                } // end if $num > 0 when slots are available at the course
                else {
                    $list.="<div class='container-fluid' style='text-align:center;'>";
                    $list.= "<span class='span6'>This program does not have schedule in selected state</span>";
                    $list.="</div>";

                    $list.="</div>"; // end of panel-body
                    $list.="</div>"; // end of panel panel-default           
                }
            } // end if $num > 0 when scheduler is available at the course
            return $list;
    }

    public function get_course_schedule($courseid, $state = null) {
        $list = "";
        if ($courseid == 44 || $courseid == 45) {
            $list.=$this->get_shared_schedule($courseid, $state);
        } // end if $courseid==44 || $courseid==45
        else {
            // 1.Get scheduler id
            $query = "select id from mdl_scheduler where course=$courseid";
            $result = $this->db->query($query);
            $num = $result->num_rows();
            $now = time()-86400;
            if ($num > 0) {
                foreach ($result->result() as $row) {
                    $schedulerid = $row->id;
                } // end foreach
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
                            . "order by starttime";
                } // end else 
                $coursename = $this->get_course_name($courseid);
                $list.="<div class='panel panel-default' id='schedule_section' style='margin-bottom:0px;'>";
                $list.="<div class='panel-heading' style='text-align:left;'><h5 class='panel-title'>$coursename</h5></div>";
                $list.="<div class='panel-body'>";

                $result = $this->db->query($query);
                $num = $result->num_rows();
                date_default_timezone_set('Pacific/Wallis');
                if ($num > 0) {
                    foreach ($result->result() as $row) {
                        $human_date = date('m-d-Y', $row->starttime);
                        $hours_num = round($row->duration / 60);
                        $human_start_time = date('m-d-Y H:i', $row->timemodified);
                        $end_time = $row->timemodified + $hours_num * 3600;
                        $human_end_date = date('m-d-Y H:i', $end_time);
                        //$locations = preg_split("/\W|_/", $row->appointmentlocation);
                        $locations = explode("/", $row->appointmentlocation);
                        if (count($locations) == 0) {
                            $locations = explode(",", $row->appointmentlocation);
                        }
                        $state = $locations[0];
                        $city = $locations[1];
                        $location = $city . " , " . $state;
                        $list.="<div class='container-fluid' style='text-align:left;'>";
                        $list.= "<span class='span1'>$human_date</span>";
                        $list.= "<span class='span2'>$location</span>";
                        $list.= "<span class='span3'>$row->notes</span>";
                        $list.= "<span class='span1'>9am -  5pm</span>";
                        $list.= "<span class='span1'><a href='https://" . $_SERVER['SERVER_NAME'] . "/index.php/register/index/$courseid/$row->id'><button class='btn btn-primary'>Register</button></a></span>";
                        $list.="</div>";

                        $list.="<div class='container-fluid' style='text-align:left;'>";
                        $list.= "<span class='span9'><hr/></span>";
                        $list.="</div>";
                    } // end foreach

                    $list.="</div>"; // end of panel-body
                    $list.="</div>"; // end of panel panel-default           
                } // end if $num > 0 when slots are available at the course
                else {
                    $list.="<div class='container-fluid' style='text-align:center;'>";
                    $list.= "<span class='span6'>This program does not have schedule in selected state</span>";
                    $list.="</div>";

                    $list.="</div>"; // end of panel-body
                    $list.="</div>"; // end of panel panel-default           
                }
            } // end if $num > 0 when scheduler is available at the course
        }// end else
        return $list;
    }

    public function get_schedule_page($courseid) {
        $list = "";
        $states = $this->get_states_list();
        $courses = $this->get_courses_list($courseid);
        $item = $this->get_all_courses();
        $list.="<br/><div  class='form_div'>";
        $list.="<div class='panel panel-default' id='schedule_section' style='margin-bottom:0px;'>";
        $list.="<div class='panel-heading' style='text-align:left;'><h5 class='panel-title'>Program Schedule</h5></div>";
        $list.="<div class='panel-body'>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.= "<span class='span3'>Please select state:</span><span class='span3'>$states</span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.= "<span class='span3'>Please select program:</span><span class='span3'>$courses</span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:center;'>";
        $list.= "<span class='span6' style='colore:red;' id='schedule_err'></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span8' style='text-align:center;display:none;' id='ajax_loading_schedule'><img src='https://$this->host/assets/img/ajax.gif' /></span>";
        $list.="</div>";

        $list.="</div>"; // end of panel-body
        $list.="</div>"; // end of panel panel-default                
        $list.= "<div id='course_schedule'>" . $this->get_course_schedule($courseid) . "</div>";

        $list.= "</div>"; // end of form div
        return $list;
    }

    public function get_search_result($item) {
        
    }

}
