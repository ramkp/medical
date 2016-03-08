<?php

/**
 * Description of Program
 *
 * @author sirromas
 */
class program_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_category_id($cat_name) {
        $query = "select id, name "
                . "from mdl_course_categories "
                . "where name like '%$cat_name%'";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $id = $row->id;
        }
        return $id;
    }

    public function get_category_items($cat_name) {
        $items = array();
        $cat_id = $this->get_category_id($cat_name);
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
        $list.= "<span class='span2'><a href='http://" . $_SERVER['SERVER_NAME'] . "/index.php/register/index/$cat_id'><button id='program_$cat_id' class='btn btn-primary'>Register</button></a></span>";
        $list.="</div>";


        $list.="<div class='container-fluid' style='text-align:center;'>";
        $list.= "<span class='span9'><hr/></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:center;'>";
        $list.= "<span class='span9' id='map' style='position: relative;height:675px;'></span>";
        $list.="</div>";

        return $list;
    }

    public function create_program_items_page($items, $cat_name) {
        $list = "";
        $list.="<br/><div  class='form_div'>";

        if (count($items) > 0) {
            foreach ($items as $item) {
                $blocks = $this->get_item_cost_blocks($item);
                $list.="<div class='panel panel-default' id='program_section' style='margin-bottom:0px;'>";
                $list.="<div class='panel-heading' style='text-align:left;'><h5 class='panel-title'>$item->fullname</h5></div>";
                $list.="<div class='panel-body'>";
                $list.="<div class='container-fluid' style='text-align:left;'>";
                $list.= "<span class='span4'>Start date <strong>" . date('Y-m-d', $item->startdate) . "</strong></span><span class='span4' style='text-align:right;'><a href='http://" . $_SERVER['SERVER_NAME'] . "/index.php/programs/schedule/$item->id'><button class='btn btn-primary'>Schedule/Register</button></a></span>";
                $list.="</div>";
                $list.="<div class='container-fluid' style='text-align:left;'>";
                $list.= "<span class='span6'>" . $blocks['item_cost'] . "</span>";
                $list.="</div>";
                $list.="<div class='container-fluid' style='text-align:left;'>";
                $list.= "<span class='span6'>" . $blocks['item_group_cost'] . "</span>";
                $list.="</div>";
                $list.="<div class='container-fluid' style='text-align:left;'>";
                $list.= "<span class='span6'><a href='http://" . $_SERVER['SERVER_NAME'] . "/index.php/programs/detailes/$item->id'>More</a></span>";
                $list.="</div>";
                $list.="</div>"; // end of panel-body
                $list.="</div>"; // end of panel panel-default                
            } // end foreach
        } //end if count($items) > 0)
        else {
            $list.="<div class='panel panel-default' id='program_section' style='margin-bottom:0px;'>";
            $list.="<div class='panel-heading' style='text-align:left;'><h5 class='panel-title'>$cat_name</h5></div>";
            $list.="<div class='panel-body'>";
            $list.="<class='container-fluid'>";
            $list.="<span class='span6'>No items found</span>";
            $list.="</div>"; // end of container-fluid
            $list.="</div>"; // end of panel-body
            $list.="</div>"; // end of panel panel-default
        } // end else
        $list.="</div>"; // end of form div
        return $list;
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
                $list.= "<span class='span2'><a href='http://" . $_SERVER['SERVER_NAME'] . "/index.php/register/index/$item->id'><button id='program_$item->id' class='btn btn-primary'>Register</button></a></span>";
                $list.="</div>";

                $list.="</div>"; // end of panel-body
                $list.="</div>"; // end of panel panel-default                
            } // end foreach
        } // end if count($items)>0
        else {
            $list.="<div class='panel panel-default' id='program_section' style='margin-bottom:0px;'>";
            $list.="<div class='panel-heading' style='text-align:left;'><h5 class='panel-title'>$cat_name</h5></div>";
            $list.="<div class='panel-body'>";

            $list.="<class='container-fluid'>";
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
        $list = $this->create_item_detail_page($item,$form_div, $state);
        return $list;
    }

    public function get_states_list() {
        $drop_down = "";
        $drop_down.="<div class='dropdown'>
        <a href='#' id='states' data-toggle='dropdown' 
        class='dropdown-toggle'>States 
        <b class='caret'></b></a>
        <ul class='dropdown-menu'>";
        $query = "select * from mdl_states order by state";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $drop_down.="<li><a href='#' id='state_$row->id' onClick='return false;'>$row->state</a></li>";
        }
        $drop_down.="</ul></div>";
        return $drop_down;
    }

    public function get_courses_list() {
        $items = array();
        $query = "select id, fullname from mdl_course where cost>0 order by fullname";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $item = new stdClass();
            foreach ($row as $key => $value) {
                $item->$key = $value;
            }
            $items[] = $item;
        } // end foreach
        $drop_down = "";
        $drop_down.="<div class='dropdown'>
        <a href='#' id='courses' data-toggle='dropdown' 
        class='dropdown-toggle'>Programs 
        <b class='caret'></b></a>
        <ul class='dropdown-menu'>";
        foreach ($items as $item) {
            $drop_down.="<li><a href='#' id='course_$item->id' onClick='return false;'>$item->fullname</a></li>";
        } // end foreach
        $drop_down.="</ul></div>";
        return $drop_down;
    }

    public function get_schedule_page($courseid) {
        $list = "";
        $states = $this->get_states_list();
        $courses = $this->get_courses_list();
        $item = $this->get_item_detail_page($courseid, false, false);
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
        $list.="<span class='span8' style='text-align:center;display:none;' id='ajax_loading_schedule'><img src='http://cnausa.com/assets/img/ajax.gif' /></span>";
        $list.="</div>";

        $list.="</div>"; // end of panel-body
        $list.="</div>"; // end of panel panel-default                
        $list.= $item; // add detailes about selected item

        $list.= "</div>"; // end of form div
        return $list;
    }

}