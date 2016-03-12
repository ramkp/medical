<?php

/**
 * Description of Taxes
 *
 * @author sirromas
 */
require_once ($_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php');

class Taxes extends Util {

    function get_state_taxes_list() {
        $taxes = array();
        $query = "select * from mdl_state_taxes order by state";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $item = new stdClass();
            foreach ($row as $key => $value) {
                $item->$key = $value;
            } // end foreach
            $taxes[] = $item;
        } // end while
        $list = $this->create_taxes_block($taxes);
        return $list;
    }

    function create_taxes_block($items) {
        $list = "";        
        $tax_page = "";
        $i = 0;
        if (count($items) > 0) {
            $list.="<div class='container-fluid' style='font-weight:bold;'>";
            $list.="<span class='span2'>State</span><span class='span1'>Tax%</span>";
            $list.="</div>";
            foreach ($items as $item) {
                $visivility = ($i == 0) ? "style='visibility:visible'" : "style='visibility:hidden'";
                //$list.="<div class='container-fluid'  $visivility>";
                $list.="<div class='container-fluid'>";
                $list.="<span class='span2'>$item->state</span>";
                $list.="<span class='span1' style='text-align:center;'><input type='text' id='tax_val$item->id' value='$item->tax' size='5' /></span>";
                $list.="<span class='span1'><button type='button' id=tax_$item->id class='btn btn-primary'>Save</button></span>";
                $list.="<span class='span3' style='padding-left:10px;'><span id='tax_status_$item->id'></span></span>";
                $list.="</div>";
                $counter = $i + 1;
                $page.="<li><a href='#' onClick=return false;>$counter</a></li>";
                $i++;
            } // end foreach        
        } // end if count($items)>0
        $tax_page.=$list;        
        return $tax_page;
    }
    
    function update_state_tax ($id, $value) {
        $query="update mdl_state_taxes set tax=$value where id=$id";
        $this->db->query($query);
        $list="Item successfully updated";
        return $list;
    }

}
