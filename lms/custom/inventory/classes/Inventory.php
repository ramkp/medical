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
            $list.="<div class='panel panel-default' id='payment_options' style='margin-bottom:0px;'>";
            $list.="<div class='panel-heading' style='text-align:left;'>";
            $list.="<div class='container-fluid' style='padding-top:15px;'>";
            $list.="<span class='span4'><input type='text' id='hotel_search' placeholder='Hotel Address' style='width:258px;'></span>";
            $list.="<span class='span1'><input type='text' id='h_start' placeholder='Start' style='width:45px'></span>";
            $list.="<span class='span1'><input type='text' id='h_end' placeholder='End' style='width:45px'></span>";
            $list.="<span class='span2'><button id='inventory_hotel_search_button' style='width:80px;'>Search</button></span>";
            $list.="<span class='span2'><button id='inventory_hotel_cancel_search_button' style='width:80px;'>Reset</button></span>";
            $list.="<span class='span2'><button id='inventory_add_hotel_button'    style='width:80px;'>Add</button></span>";
            $list.="</div></h5></div>";
        }

        $list.="<div class='panel-body'>";
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
        $list.="</div>"; // end of body div

        return $list;
    }

    function get_books_page() {
        $list = "";
        $books = array();
        $query = "select * from mdl_books order by pdate desc "
                . "limit 0, $this->limit";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $b = new stdClass();
                foreach ($row as $key => $value) {
                    $b->$key = $value;
                }
                $books[] = $b;
            } // end while
        } // end if 
        $list.=$this->create_books_page($books);
        return $list;
    }

    function create_books_page($books, $toolbar = true) {
        if ($toolbar) {
            $list.="<div class='container-fluid'>";
            $list.="<span class='span4'><input type='text' id='b_search' placeholder='Book title' style='width:258px;'></span>";
            $list.="<span class='span1'><input type='text' id='b_start' placeholder='Start' style='width:45px'></span>";
            $list.="<span class='span1'><input type='text' id='b_end' placeholder='End' style='width:45px'></span>";
            $list.="<span class='span2'><button id='inventory_b_search_button' style='width:80px;'>Search</button></span>";
            $list.="<span class='span2'><button id='inventory_b_cancel_search_button' style='width:80px;'>Reset</button></span>";
            $list.="<span class='span2'><button id='inventory_add_b_button' style='width:80px;'>Add</button></span>";
            $list.="</div>";

            $list.="<div class='container-fluid' style='font-weight:bold;'>";
            $list.="<span class='span2'>Program</span>";
            $list.="<span class='span2'>Publisher</span>";
            $list.="<span class='span2'>Title</span>";
            $list.="<span class='span1'>Cost</span>";
            $list.="<span class='span2'>Purchase date</span>";
            $list.="<span class='span1'>Student</span>";
            $list.="<span class='span2'>&nbsp;</span>";
            $list.="</div>";
            $list.="<div class='container-fluid'>";
            $list.="<span class='span12'><hr/></span>";
            $list.="</div><br>";
        }

        $list.="<div id='b_container'>";

        if (count($books) > 0) {
            $total = 0;
            foreach ($books as $b) {
                $date = date('m-d-Y', $b->pdate);
                if ($b->userid > 0) {
                    $user = $this->get_user_details($b->userid);
                    $userblock = $user->firstname . " " . $user->lastname;
                } // end if
                else {
                    $userblock = "N/A";
                } // end else
                $coursename = $this->get_course_name($b->courseid);
                $list.="<div class='container-fluid'>";
                $list.="<span class='span2'>$coursename</span>";
                $list.="<span class='span2'>$b->publisher</span>";
                $list.="<span class='span2'>$b->title</span>";
                $list.="<span class='span1'>$$b->cost</span>";
                $list.="<span class='span2'>$date</span>";
                $list.="<span class='span1'>$userblock</span>";
                $list.="<span class='span1'><a href='#' onClick='return false;'><img src='https://medical2.com/lms/theme/image.php/lambda/core/1468523658/t/edit' id='inv_book_edit_$b->id'></a></span>";
                $list.="<span class='span1'><a href='#' onClick='return false;'><img src='https://medical2.com/lms/theme/image.php/lambda/core/1468523658/t/delete' id='inv_book_del_$b->id'></a></span>";
                $list.="</div>";
                $list.="<div class='container-fluid'>";
                $list.="<span class='span12'><hr/></span>";
                $list.="</div>";
                $total = $total + $b->cost;
            } // end foreach
            $list.="<div class='container-fluid' style='font-weight:bold;'>";
            $list.="<span class='span6'>Total</span>";
            $list.="<span class='span1'>$$total</span>";
            $list.="</div>";
            $list.="<div class='container-fluid' style='display:none;text-align:center;' id='book_ajax_loader'>";
            $list.="<span class='span9'><img src='https://$this->host/assets/img/ajax.gif' /></span>";
            $list.="</div>";
        } // end if
        else {
            $list.="<div class='container-fluid'>";
            $list.="<span class='span9'>There are no books added</span>";
            $list.="</div>";
        }
        $list.="</div>";

        if ($toolbar) {
            $list.="<div class='container-fluid'>";
            $list.="<span class='span9'  id='b_pagination'></span>";
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

    function get_books_hotels() {
        $query = "select count(id) as total from mdl_books";
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

    function get_book_item($page) {
        $books = array();
        $rec_limit = $this->limit;
        if ($page == 1) {
            $offset = 0;
        } // end if $page==1
        else {
            $page = $page - 1;
            $offset = $rec_limit * $page;
        }
        $query = "select * from mdl_books "
                . "order by pdate desc LIMIT $offset, $rec_limit";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $b = new stdClass();
            foreach ($row as $key => $value) {
                $b->$key = $value;
            } // end foreach
            $books[] = $b;
        } // end while
        $list = $this->create_books_page($books, false);
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

    function get_quantity_box() {
        $list = "";

        $list.="<select id='total_books'>";
        $list.="<option value='1' selected>1</option>";
        for ($i = 2; $i <= 1000; $i++) {
            $list.="<option value='$i'>$i</option>";
        }
        $list.="</select>";

        return $list;
    }

    function get_add_book_dialog() {
        $list = "";
        $box = $this->get_quantity_box();
        $list.="<div id='myModal' class='modal fade'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h4 class='modal-title'>Add Book</h4>
                </div>
                <div class='modal-body'>
                
                <div class='container-fluid'>
                <span class='span1'>Program*</span>
                <span class='span3'><input type='text' id='courses_list' style='width:275px;'></span>    
                </div>
                
                <div class='container-fluid'>
                <span class='span1'>Publisher*</span>
                <span class='span3'><input type='text' id='pub' style='width:275px;'></span>    
                </div>
                
                <div class='container-fluid'>
                <span class='span1'>Title*</span>
                <span class='span3'><input type='text' id='title' style='width:275px;'></span>    
                </div>
                
                <div class='container-fluid'>
                <span class='span1'>Cost*:</span>
                <span class='span3'><input type='text' id='cost' style='width:275px;'></span>
                </div>
                
                <div class='container-fluid'>
                <span class='span1'>Date*:</span>
                <span class='span3'><input type='text' id='pdate' style='width:275px;'></span>
                </div>
                
                <div class='container-fluid'>
                <span class='span1'>Total*:</span>
                <span class='span3'>$box</span>
                </div>
               
                <div class='container-fluid' style=''>
                <span class='span6' style='color:red;' id='inv_err'></span>
                </div>
             
                <div class='modal-footer' style='text-align:center;'>
                    <span align='center'><button type='button' class='btn btn-primary' data-dismiss='modal' id='cancel'>Cancel</button></span>
                    <span align='center'><button type='button' class='btn btn-primary' id='add_book_to_inventory'>OK</button></span>
                </div>
            </div>
        </div>
    </div>";

        return $list;
    }

    function get_edit_book_dialog($id) {
        $list = "";

        $query = "select * from mdl_books where id=$id";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $b = new stdClass();
            foreach ($row as $key => $value) {
                $b->$key = $value;
            }
        }

        /*
          echo "Query:" . $query . "<br>";
          echo "<pre>";
          print_r($b);
          echo "</pre>";
         */

        $coursename = $this->get_course_name($b->courseid);
        $date = date('m/d/Y', $b->pdate);
        if ($b->userid > 0) {
            $user = $this->get_user_details($b->userid);
            $userdata = $user->firstname . " " . $user->lastname;
        } // end if
        else {
            $userdata = 'N/A';
        } // end else 
        $list.="<div id='myModal' class='modal fade'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h4 class='modal-title'>Edit Book</h4>
                </div>
                <div class='modal-body'>
                <input type='hidden' id='id' value='$id'>
                <div class='container-fluid'>
                <span class='span1'>Program*</span>
                <span class='span3'><input type='text' id='courses_list' style='width:275px;' value='$coursename'></span>    
                </div>
                
                <div class='container-fluid'>
                <span class='span1'>Publisher*</span>
                <span class='span3'><input type='text' id='pub' style='width:275px;' value='$b->publisher'></span>    
                </div>
                
                <div class='container-fluid'>
                <span class='span1'>Title*</span>
                <span class='span3'><input type='text' id='title' style='width:275px;' value='$b->title'></span>    
                </div>
                
                <div class='container-fluid'>
                <span class='span1'>Cost*:</span>
                <span class='span3'><input type='text' id='cost' style='width:275px;' value='$b->cost'></span>
                </div>
                
                <div class='container-fluid'>
                <span class='span1'>Date*:</span>
                <span class='span3'><input type='text' id='pdate' style='width:275px;' value='$date'></span>
                </div>
                
                <div class='container-fluid'>
                <span class='span1'>Student:</span>
                <span class='span3'><input type='text' id='student' value='$userdata'></span>
                </div>
               
                <div class='container-fluid' style=''>
                <span class='span6' style='color:red;' id='inv_err'></span>
                </div>
             
                <div class='modal-footer' style='text-align:center;'>
                    <span align='center'><button type='button' class='btn btn-primary' data-dismiss='modal' id='cancel'>Cancel</button></span>
                    <span align='center'><button type='button' class='btn btn-primary' id='update_book_to_inventory'>OK</button></span>
                </div>
            </div>
        </div>
    </div>";

        return $list;
    }

    function get_course_id($coursename) {
        $query = "select * from mdl_course where fullname like '%" . trim($coursename) . "%'";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $id = $row['id'];
        }
        return $id;
    }

    function add_book($book) {
        $courseid = $this->get_course_id($book->coursename);
        $date = strtotime($book->pdate);
        $query = "insert into mdl_books "
                . "(publisher,"
                . "title,"
                . "cost,"
                . "pdate,"
                . "courseid) "
                . "values ('$book->pub',"
                . "'$book->title',"
                . "'$book->cost',"
                . "'$date',"
                . "$courseid)";
        if ($book->total > 1) {
            for ($i = 1; $i <= $book->total; $i++) {
                $this->db->query($query);
            }
        } // end if
        else {
            $this->db->query($query);
        } // end else
    }

    function update_book($book) {
        if ($book->student != 'N/A') {
            $userid = $this->get_userid_by_fio($book->student);
        } // end if
        else {
            $userid = 0;
        } // end else
        $courseid = $this->get_course_id($book->coursename);
        $date = strtotime($book->pdate);
        $query = "update mdl_books "
                . "set publisher='$book->pub', "
                . "title='$book->title', "
                . "cost='$book->cost', "
                . "pdate='$date', "
                . "courseid=$courseid,"
                . "userid=$userid  where id=$book->id";
        $this->db->query($query);
    }

    function delete_book($id) {
        $query = "delete from mdl_books where id=$id";
        $this->db->query($query);
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

    function search_book_item($item) {
        $books = array();

        if ($item->title != '' && $item->start == '' && $item->end == '') {
            $query = "select * from mdl_books "
                    . "where title like '%" . trim($item->title) . "%'";
        }

        if ($item->title != '' && $item->start != '' && $item->end == '') {
            $start = strtotime($item->start);
            $end = time();
            $query = "select * from mdl_books "
                    . "where title like '%" . trim($item->title) . "%' "
                    . "and pdate between $start and $end";
        }

        if ($item->title != '' && $item->start != '' && $item->end != '') {
            $start = strtotime($item->start);
            $end = strtotime($item->end);
            $query = "select * from mdl_books "
                    . "where title like '%" . trim($item->title) . "%' "
                    . "and pdate between $start and $end";
        }

        if ($item->title == '' && $item->start != '' && $item->end == '') {
            $start = strtotime($item->start);
            $end = time();
            $query = "select * from mdl_books "
                    . "where title like '%" . trim($item->title) . "%' "
                    . "and pdate between $start and $end";
        }

        if ($item->title == '' && $item->start != '' && $item->end != '') {
            $start = strtotime($item->start);
            $end = strtotime($item->end);
            $query = "select * from mdl_books "
                    . "where title like '%" . trim($item->title) . "%' "
                    . "and pdate between $start and $end";
        }
        //echo "Query: " . $query . "<br>";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $b = new stdClass();
            foreach ($row as $key => $value) {
                $b->$key = $value;
            } // end foreach
            $books[] = $b;
        } // end while
        $list = $this->create_books_page($books, FALSE);
        return $list;
    }

    function get_inventory_page() {
        $list = "";
        $hpage = $this->get_hotel_page();
        $books = $this->get_books_page();
        $list.="<ul class='nav nav-tabs'>
          <li class='active'><a data-toggle='tab' href='#books_tab' >Books</a></li>
          
          </ul>

        <div class='tab-content'>
          <div id='books_tab' class='tab-pane fade in active'>
            $books
          </div>
          <div id='hotels_tab' class='tab-pane fade'>
            $hpage
          </div>
         </div>";

        return $list;
    }

}
