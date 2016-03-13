
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

    function get_state_taxes_list() {
        $taxes = array();
        $query = "select * from mdl_state_taxes where id=1 order by state ";
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

    function create_taxes_block($items, $wrap = 1) {
        $list = "";
        $i = 0;
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
            if ($wrap == 1) {
                $list.="</div>"; // div id='state_taxes'
            }   
            $list.="<div class='container-fluid'>";
            $list.="<span class='span12'><div id='pagination-demo' class='pagination' style='height: 10px;'></div></span>";
            $list.="</div>";
        } // end if count($items)>0        
        return $list;
    }

    function update_state_tax($id, $value) {
        $query = "update mdl_state_taxes set tax=$value where id=$id";
        $this->db->query($query);
        $list = "Item successfully updated";
        return $list;
    }

    function get_tax_item($id) {
        $list = "";
        $query = "select * from mdl_state_taxes where id=$id";
        //echo "Query: " . $query;
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $item = new stdClass();
            foreach ($row as $key => $value) {
                $item->$key = $value;
            } // end foreach
        }
        $items[] = $item;
        $list.= $this->create_taxes_block($items, 0);
        return $list;
    }

}
