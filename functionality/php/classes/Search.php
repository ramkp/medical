<?php

/**
 * Description of Search
 *
 * @author sirromas
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/class.pdo.database.php';

class Search {

    public $db;

    function __construct() {
        $this->db = new pdo_db();
    }

    function get_search_item($item) {
        $items = array();
        $query = "select * from mdl_course "
                . "where fullname like '%$item%' "
                . "or summary like '%$item%' and cost>0";
        //$query = "select * from mdl_course where fullname like '%$item%' ";
        //echo "Query: ".$query."<br/>";        
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $search_item = new stdClass();
                foreach ($row as $key => $value) {
                    $search_item->$key = $value;
                } // end foreach
                $items[] = $search_item;
            } // end while
        } // end if $num > 0        
        $list = $this->create_search_result($items, $item, true);
        return $list;
    }

    function create_search_result($items, $search_item, $toolbar = true) {
        $list = "";
        if (count($items) > 0) {
            foreach ($items as $item) {

                /*
                  $desc_part = (stripos($item->fullname, $search_item)===true) ? "name" : "desc";
                  if ($desc_part=='name') {
                  $link_text=$item->fullname;
                  } // end if $desc_part=='name'
                  else {
                  $link_text=$item->summary;
                  } // end else
                 */
                $link_text = $item->fullname;
                $link_addr = "http://" . $_SERVER['SERVER_NAME'] . "/index.php/programs/detailes/$item->id";
                $link_item = "<a href='$link_addr' target='_blank'>$link_text</a>";
                $list.="<div class='container-fluid'>";
                $list.="<div class='text-center'>";
                $list.="$link_item";
                $list.="</div>";
                $list.="</div>";
            } // end foreach
        } // end if count($items)>0
        else {
            $list.="There are no items found using '$search_item'";
        }
        return $list;
    }

}
