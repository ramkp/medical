<?php

/**
 * Description of Programs
 *
 * @author sirromas
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/class.pdo.database.php';

class Programs {

    public $db;

    function __construct() {
        $this->db = new pdo_db();
    }

    function get_category_id($cat_name) {
        $query = "select id, name "
                . "from mdl_course_categories "
                . "where name like '%$cat_name%'";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $id = $row['id'];
        }
        return $id;
    }

    function get_category_items($cat_name) {
        $items = array();
        $cat_id = $this->get_category_id($cat_name);
        $query = "select id,fullname,summary,startdate,cost,discount_size "
                . "from mdl_course where category=$cat_id and cost>0";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $item = new stdClass();
                foreach ($row as $key => $value) {
                    $item->$key = $value;
                }
                $items[] = $item;
            } // end while
        } // end if $num>0
        $list = $this->create_items_block($items);
        return $list;
    }

    function get_group_discount($id) {
        $query = "select group_discount_size "
                . "from mdl_group_discount "
                . "where courseid=$id";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $discount = $row['group_discount_size'];
        }
        return $discount;
    }

    function calculate_item_cost($id, $item_cost, $item_discount) {
        $group_discount = $this->get_group_discount($id);
        $clean_item_cost = $item_cost - round(($item_cost * $item_discount) / 100, 2);
        $group_cost = $item_cost - round(($item_cost * $group_discount) / 100, 2);
        $cost = array('item_cost' => $clean_item_cost, 'group_cost' => $group_cost);
        return $cost;
    }

    function get_item_cost_blocks($item) {
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

    function get_nursing_info_block($courseid) {
        $list = "";
        $list.="<!-- Modal -->
                <div id='myModal' class='modal fade' role='dialog'>
                     <div class='modal-dialog'>
                        <!-- Modal content-->
                        <div class='modal-content'>
                        <div class='modal-header'>
                        <button type='button' class='close' data-dismiss='modal'>&times;</button>
                        <h4 class='modal-title'>Modal Header</h4>
                      </div>
                                <div class='modal-body'>
                                     <p>Some text in the modal.</p>
                                </div>
                      <div class='modal-footer'>
                        <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
                       </div>
                       </div>
                       </div>
                   </div>";
        return $list;
    }

    function get_school_page($cat_name) {
        $list = "";
        $list.="<div class='container-fluid'>";
        $list.= "<span class='span9'>A <strong>nursing school</strong> is a type of educational "
                . "institution, or part thereof, providing education and "
                . "training to become a fully qualified nurse. The nature of "
                . "nursing education and nursing qualifications varies "
                . "considerably across the world.</span>";
        $list.="</div>";
        $list.="<br/><div class='container-fluid'>";
        $list.= "<span class='span9'>Please select on the map closest location</span>";
        $list.="</div>";
        $list.="<div class='container-fluid'>";        
        $list.= "<span class='span9' id='map' style='position: relative;height:375px;'></span>";
        $list.="</div>";
        return $list;
    }

    function get_locations_list() {
        $location_objects = array();
        $query = "select * from mdl_nursing_school_map";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $location_object = new stdClass();
                foreach ($row as $key => $value) {
                    $location_object->$key = $value;
                }
                $location_objects[] = $location_object;
            } // end while
        } // end if $num > 0
        return json_encode($location_objects);
    }

    function create_items_block($items) {
        $list = "";
        if (count($items) > 0) {
            foreach ($items as $item) {
                $blocks = $this->get_item_cost_blocks($item);
                $list.="<class='container-fluid'>";
                $list.="<span class='span6'><h5>$item->fullname</h5>&nbsp;&nbsp;<a href='#' id=program_$item->id onClick='return false;'>Register</a></span>";
                $list.="</div>";
                $list.="<div class='container-fluid'>";
                $list.= "<span class='span6'>$item->summary</span>";
                $list.="</div>";
                $list.="<div class='container-fluid'>";
                $list.= "<span class='span4'>Start date <strong>" . date('Y-m-d', $item->startdate) . "</strong></span>";
                $list.="</div>";
                $list.="<div class='container-fluid'>";
                $list.= "<span class='span6'>" . $blocks['item_cost'] . "</span>";
                $list.="</div>";
                $list.="<div class='container-fluid'>";
                $list.= "<span class='span6'>" . $blocks['item_group_cost'] . "</span>";
                $list.="</div>";
                $list.="<div class='container-fluid'>";
                $list.= "<span class='span6'><hr></span>";
                $list.="</div>";

                //<button type='button' id=price_$item->id class='btn btn-primary'>Save</button>
            }
        } // end if count($items)>0
        else {
            $list.="<class='container-fluid'><span class='span3'>No items found</span></div>";
        }
        return $list;
    }

}
