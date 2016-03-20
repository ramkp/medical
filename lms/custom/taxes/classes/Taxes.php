
<style>
    ul.hr {
        margin: 0; /* Обнуляем значение отступов */
        padding: 4px; /* Значение полей */
    }
    ul.hr li {
        display: inline; /* Отображать как строчный элемент */
        margin-right: 5px; /* Отступ слева */
        border: 1px solid #000; /* Рамка вокруг текста */
        padding: 3px; /* Поля вокруг текста */
    }
</style>

<?php

/**
 * Description of Taxes
 *
 * @author sirromas
 */
require_once ($_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php');

class Taxes extends Util {

    public $limit = 10;

    function get_state_taxes_list() {
        $taxes = array();
        $query = "select * from mdl_state_taxes order by state limit 0, $this->limit";
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

    function get_pagination_bar() {
        $list = "";
        $query = "select * from mdl_state_taxes order by state ";
        $result = $this->db->query($query);
        $i = 1;
        $list.="<ul class='pagination'>";
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $list.="<li style='display:inline;margin-right:10px;'><a href='#' id='tax_page_" . $row['id'] . "' onClick='return false;'>$i</a></li>";
            $i++;
        }
        $list.="</ul>";
        return $list;
    }

    function create_taxes_block($items, $wrap = 1) {
        $list = "";
        $i = 0;
        $pagination = $this->get_pagination_bar();
        //echo "Total items: " . count($items);
        if (count($items) > 0) {
            //print_r($items);
            if ($wrap == 1) {
                $list.="<div id='state_taxes' style='text-align:center;'>";
            }
            $list.="<div class='container-fluid' style='font-weight:bold;'>";
            $list.="<span class='span2'>State</span><span class='span1'>Tax%</span>";
            $list.="</div>";
            foreach ($items as $item) {
                $list.="<div class='container-fluid' >";
                $list.="<span class='span2'>$item->state</span>";
                $list.="<span class='span1' style='text-align:center;'><input type='text' id='tax_val$item->id' value='$item->tax' size='5' /></span>";
                $list.="<span class='span1'><button type='button' id=tax_$item->id class='btn btn-primary'>Save</button></span>";
                $list.="<span class='span3' style='padding-left:10px;'><span id='tax_status_$item->id'></span></span>";
                $list.="</div>";
                $i++;
            } // end foreach             
            $list.="</div>"; // div id='state_taxes'
            if ($wrap == 1) {
                $list.="<div class='container-fluid'>";
                $list.="<span class='span9' id='pagination'></span>";
                $list.="</div>";
            } // end if $wrap == 1
        } // end if count($items)>0        
        return $list;
    }

    function update_state_tax($id, $value) {
        $query = "update mdl_state_taxes set tax=$value where id=$id";
        $this->db->query($query);
        $list = "Item successfully updated";
        return $list;
    }

    function get_states_count() {
        $query = "select count(id) as total from mdl_state_taxes";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $total = $row['total'];
        }
        return $total;
    }

    function get_tax_item($page) {
        $rec_limit = $this->limit;
        if ($page == 1) {
            $offset = 0;
        } // end if $page==1
        else {
            $page = $page - 1;
            $offset = $rec_limit * $page;
        }
        $list = "";
        $query = "select * from mdl_state_taxes order by state LIMIT $offset, $rec_limit";
        //echo "Query: ".$query."<br>";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $item = new stdClass();
            foreach ($row as $key => $value) {
                $item->$key = $value;
            } // end foreach
            $items[] = $item;
        } // end while
        $list.= $this->create_taxes_block($items, 0);
        return $list;
    }

}
