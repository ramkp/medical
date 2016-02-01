<?php

/**
 * Description of Programs
 *
 * @author sirromas
 */
require_once $_SERVER['DOCUMENT_ROOT']. '/lms/class.pdo.database.php';

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
                . "from mdl_course where category=$cat_id";
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

    function create_items_block($items) {
        $list = "";
        if (count($items) > 0) {
            foreach ($items as $item) {
                $item_costs=$this->calculate_item_cost($item->id, $item->cost, $item->discount_size);
                $item_cost=$item_cost['item_cost'];
                $item_group_cost=$item_cost['group_cost'];
                $list.="<class='container-fluid'>";
                $list.="<span class='span6'><h5>$item->fullname</h5></span><span class='span2'>$item_cost</span>";                                        
                $list.="</div>";
                $list.="<div class='container-fluid'>";
                $list.= "<span class='span6'>$item->summary</span>";
                $list.="</div>";                
                $list.="<div class='container-fluid'>";
                $list.= "<span class='span6'>Start date ".date ('Y-m-d', $item->startdate)."</span>";
                $list.="</div>";
                $list.="<div class='container-fluid'>";
                $list.= "<span class='span6'><hr></span>";
                $list.="</div>";
            }
        } // end if count($items)>0
        else {
            $list.="<p align='center'>No items found</p>";
        }
        return $list;
    }

}
