<?php

require_once ($_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php');

class Inventory extends Util {

    public $limit = 3;

    function __construct() {
        parent::__construct();
    }

    function get_hotel_page() {
        $list = "";
        $hotels = array();
        $query = "select * from mdl_hotel_expenses "
                . "order by pdate desc limit 0, $this->limit";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $h = new stdClass();
                foreach ($row as $key => $value) {
                    $h->$key = $value;
                }
                $hotels[] = $h;
            } // end while
        } // end if 
        $list.=$this->create_hotels_page($hotels);
        return $list;
    }

    function create_hotels_page($hotels, $toolbar = true) {
        if ($toolbar) {
            $list.="<div class='container-fluid'>";
            $list.="<span class='span4'><input type='text' id='hotel_search' placeholder='Hotel Address' style='width:258px;'></span>";
            $list.="<span class='span1'><input type='text' id='h_start' placeholder='Start' style='width:45px'></span>";
            $list.="<span class='span1'><input type='text' id='h_end' placeholder='End' style='width:45px'></span>";
            $list.="<span class='span2'><button id='inventory_hotel_search_button' style='width:80px;'>Search</button></span>";
            $list.="<span class='span2'><button id='inventory_hotel_cancel_search_button' style='width:80px;'>Reset</button></span>";
            $list.="<span class='span2'><button id='inventory_add_hotel_button'    style='width:80px;'>Add</button></span>";
            $list.="</div><br>";
        }

        $list.="<div id='inventory_hotels_container'>";

        if (count($hotels) > 0) {
            $total = 0;
            foreach ($hotels as $h) {
                $date = date('m-d-Y', $h->pdate);
                $list.="<div class='container-fluid'>";
                $list.="<span class='span4'>$h->addr</span>";
                $list.="<span class='span1'>$$h->amount</span>";
                $list.="<span class='span2'>$date</span>";
                $list.="<span class='span1'><a href='#' onClick='return false;'><img src='https://medical2.com/lms/theme/image.php/lambda/core/1468523658/t/edit' id='inv_hotel2_edit_$h->id'></a></span>";
                $list.="<span class='span1'><a href='#' onClick='return false;'><img src='https://medical2.com/lms/theme/image.php/lambda/core/1468523658/t/delete' id='inv_hotel2_del_$h->id'></a></span>";
                $list.="</div>";
                $list.="<div class='container-fluid'>";
                $list.="<span class='span9'><hr/></span>";
                $list.="</div>";
                $total = $total + $h->amount;
            } // end foreach
            $list.="<div class='container-fluid' style='font-weight:bold;'>";
            $list.="<span class='span4'>Total</span>";
            $list.="<span class='span1'>$$total</span>";
            $list.="</div>";
            $list.="<div class='container-fluid' style='display:none;text-align:center;' id='hotel_ajax_loader'>";
            $list.="<span class='span9'><img src='https://$this->host/assets/img/ajax.gif' /></span>";
            $list.="</div>";
        } // end if
        else {
            $list.="<div class='container-fluid'>";
            $list.="<span class='span9'>No hotel expenses found</span>";
            $list.="</div>";
        }
        $list.="</div>";

        if ($toolbar) {
            $list.="<div class='container-fluid'>";
            $list.="<span class='span9'  id='h_pagination'></span>";
            $list.="</div>";
        }

        return $list;
    }

    function get_total_hotels() {
        $query = "select count(id) as total from mdl_hotel_expenses";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $total = $row['total'];
        }
        return $total;
    }

    function get_hotel_item($page) {
        $hotels = array();
        $rec_limit = $this->limit;
        if ($page == 1) {
            $offset = 0;
        } // end if $page==1
        else {
            $page = $page - 1;
            $offset = $rec_limit * $page;
        }
        $query = "select * from mdl_hotel_expenses "
                . "order by pdate desc LIMIT $offset, $rec_limit";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $h = new stdClass();
            foreach ($row as $key => $value) {
                $h->$key = $value;
            } // end foreach
            $hotels[] = $h;
        } // end while
        $list = $this->create_hotels_page($hotels, false);
        return $list;
    }

    function get_add_hotel_dialog() {
        $list = "";

        $list.="<div id='myModal' class='modal fade'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h4 class='modal-title'>Add Hotel</h4>
                </div>
                <div class='modal-body'>
                
                <div class='container-fluid'>
                <span class='span1'>Hotel*</span>
                <span class='span3'><input type='text' id='inventory_hotels_list' style='width:275px;'></span>    
                </div>
                
                <div class='container-fluid'>
                <span class='span1'>Paid*:</span>
                <span class='span3'><input type='text' id='amount' style='width:275px;'></span>
                </div>
                
                <div class='container-fluid'>
                <span class='span1'>Date*:</span>
                <span class='span3'><input type='text' id='pdate' style='width:275px;'></span>
                </div>
               
                <div class='container-fluid' style=''>
                <span class='span6' style='color:red;' id='inv_err'></span>
                </div>
             
                <div class='modal-footer' style='text-align:center;'>
                    <span align='center'><button type='button' class='btn btn-primary' data-dismiss='modal' id='cancel'>Cancel</button></span>
                    <span align='center'><button type='button' class='btn btn-primary' id='add_hotel_to_inventory'>OK</button></span>
                </div>
            </div>
        </div>
    </div>";

        return $list;
    }

    function get_edit_hotel_dialog($id) {
        $list = "";

        $query = "select * from mdl_hotel_expenses where id=$id";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $addr = $row['addr'];
            $amount = $row['amount'];
            $pdate = date('Y/m/d', $row['pdate']);
        }

        $list.="<div id='myModal' class='modal fade'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h4 class='modal-title'>Edit Hotel</h4>
                    <input type='hidden' id='id' value='$id'>
                </div>
                <div class='modal-body'>
                
                <div class='container-fluid'>
                <span class='span1'>Hotel*</span>
                <span class='span3'><input type='text' id='inventory_hotels_list' style='width:275px;' value='$addr'></span>    
                </div>
                
                <div class='container-fluid'>
                <span class='span1'>Paid*:</span>
                <span class='span3'><input type='text' id='amount' style='width:275px;' value='$amount'></span>
                </div>
                
                <div class='container-fluid'>
                <span class='span1'>Date*:</span>
                <span class='span3'><input type='text' id='pdate' style='width:275px;' value='$pdate'></span>
                </div>
               
                <div class='container-fluid' style=''>
                <span class='span6' style='color:red;' id='inv_err'></span>
                </div>
             
                <div class='modal-footer' style='text-align:center;'>
                    <span align='center'><button type='button' class='btn btn-primary' data-dismiss='modal' id='cancel'>Cancel</button></span>
                    <span align='center'><button type='button' class='btn btn-primary' id='update_hotel_inventory'>OK</button></span>
                </div>
            </div>
        </div>
    </div>";

        return $list;
    }

    function add_hotel($hotel) {
        $date = strtotime($hotel->pdate);
        $query = "insert into mdl_hotel_expenses "
                . "(addr,"
                . "amount,"
                . "pdate) "
                . "values('$hotel->hotel',"
                . "'$hotel->amount',"
                . "'$date')";
        $this->db->query($query);
    }

    function update_hotel($hotel) {
        $date = strtotime($hotel->pdate);
        $query = "update mdl_hotel_expenses "
                . "set addr='$hotel->hotel', "
                . "amount='$hotel->amount', "
                . "pdate='$date' "
                . "where id='$hotel->id'";
        $this->db->query($query);
    }

    function delete_hotel($id) {
        $query = "delete from mdl_hotel_expenses where id=$id";
        $this->db->query($query);
    }

    function search_hotel_item($item) {
        $hotels = array();

        if ($item->addr != '' && $item->start == '' && $item->end == '') {
            $query = "select * from mdl_hotel_expenses "
                    . "where addr like '%" . trim($item->addr) . "%'";
        }

        if ($item->addr != '' && $item->start != '' && $item->end == '') {
            $start = strtotime($item->start);
            $end = time();
            $query = "select * from mdl_hotel_expenses "
                    . "where addr like '%" . trim($item->addr) . "%' "
                    . "and pdate between $start and $end";
        }

        if ($item->addr != '' && $item->start != '' && $item->end != '') {
            $start = strtotime($item->start);
            $end = strtotime($item->end);
            $query = "select * from mdl_hotel_expenses "
                    . "where addr like '%" . trim($item->addr) . "%' "
                    . "and pdate between $start and $end";
        }

        if ($item->addr == '' && $item->start != '' && $item->end == '') {
            $start = strtotime($item->start);
            $end = time();
            $query = "select * from mdl_hotel_expenses "
                    . "where addr like '%" . trim($item->addr) . "%' "
                    . "and pdate between $start and $end";
        }

        if ($item->addr == '' && $item->start != '' && $item->end != '') {
            $start = strtotime($item->start);
            $end = strtotime($item->end);
            $query = "select * from mdl_hotel_expenses "
                    . "where addr like '%" . trim($item->addr) . "%' "
                    . "and pdate between $start and $end";
        }
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $h = new stdClass();
            foreach ($row as $key => $value) {
                $h->$key = $value;
            } // end foreach
            $hotels[] = $h;
        } // end while
        $list = $this->create_hotels_page($hotels, false);
        return $list;
    }

    function get_inventory_page() {
        $list = "";
        $hpage = $this->get_hotel_page();
        $list.="<ul class='nav nav-tabs'>
          <li class='active'><a data-toggle='tab' href='#books_tab' >Books</a></li>
          <li><a data-toggle='tab' href='#hotels_tab' >Hotels</a></li>
          </ul>

        <div class='tab-content'>
          <div id='books_tab' class='tab-pane fade in active'>
            <h3>Books</h3>
            <p>Some content.</p>
          </div>
          <div id='hotels_tab' class='tab-pane fade'>
            $hpage
          </div>
         </div>";

        return $list;
    }

}
