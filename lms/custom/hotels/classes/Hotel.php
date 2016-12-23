<?php

require_once ($_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php');

class Hotel extends Util {

    public $limit = 3;

    function __construct() {
        parent::__construct();
        $this->create_states_data();
        $this->create_city_data();
        $this->create_hotels_data();
    }

    function create_hotels_data() {
        $query = "select * from mdl_hotels";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $location = mb_convert_encoding($row['address'], 'UTF-8');
                $ws[] = $location;
            }
            file_put_contents('/home/cnausa/public_html/lms/custom/utils/hotels.json', json_encode($ws));
        }
    }

    function create_states_data() {
        $query = "select * from mdl_states order by state";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $location = mb_convert_encoding($row['state'], 'UTF-8');
            $ws[] = $location;
        }
        file_put_contents('/home/cnausa/public_html/lms/custom/utils/states.json', json_encode($ws));
    }

    function create_city_data() {
        $query = "select * from mdl_user_cities order by city";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $location = mb_convert_encoding($row['city'], 'UTF-8');
            $ws[] = $location;
        }
        file_put_contents('/home/cnausa/public_html/lms/custom/utils/cities.json', json_encode($ws));
    }

    function get_hotels_page() {
        $list = "";
        $hotels = array();
        $query = "select * from mdl_hotels limit 0, $this->limit";
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
        $list = "";

        if ($toolbar) {
            $list.="<div class='panel panel-default' id='payment_options' style='margin-bottom:0px;'>";
            $list.="<div class='panel-heading' style='text-align:left;'><h5 class='panel-title'>Hotels &nbsp; <button id='add_hotel'>Add</button></h5></div>";
            $list.="<div class='panel-body'>";

            $list.="<div class='container-fluid' style='text-align:left;font-weight:bold;'>";
            $list.="<span class='span2'>State/City</span>";
            $list.="<span class='span3'>Address</span>";
            $list.="<span class='span2'>Phone</span>";
            $list.="<span class='span2'>Contact</span>";
            $list.="<span class='span1'>Charge</span>";
            $list.="<span class='span1'>Room</span>";
            $list.="</div>";
        }

        $list.="<div id='hotels_container'>";
        if (count($hotels) > 0) {
            foreach ($hotels as $h) {
                $list.="<div class='container-fluid' style='text-align:left;'>";
                $list.="<span class='span2'>" . $h->state . "/" . $h->city . "</span>";
                $list.="<span class='span3'>" . $h->address . "</span>";
                $list.="<span class='span2'>" . $h->phone . "</span>";
                $list.="<span class='span2'>" . $h->contact . "</span>";
                $list.="<span class='span1'>$" . $h->charge . "</span>";
                $list.="<span class='span1'>" . $h->room . "</span>";
                $list.="<span class='span1'>";
                $list.="<a href='#' onClick='return false;' ><img title='Edit' src='https://medical2.com/lms/theme/image.php/lambda/core/1468523658/t/edit' id='hotel_edit_$h->id' ></a>";
                $list.="&nbsp;&nbsp;<a href='#' onClick='return false;' ><img title='Delete' src='https://medical2.com/lms/theme/image.php/lambda/core/1468523658/t/delete' id='hotel_del_$h->id'></a>";
                $list.="</span>";
                $list.="</div>";
            }
        } // end if count($hotels)>0
        else {
            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span4'>There are no any hotels added</span>";
            $list.="</div>";
        } // end else
        $list.="</div>";
        if ($toolbar) {
            if ($toolbar == true) {
                $list.="<div class='container-fluid'>";
                $list.="<span class='span9'  id='pagination'></span>";
                $list.="</div>";
            } // end if $toolbar==true
        }


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
                
                 <div class='container-fluid' style=''>
                  <span class='span1'>State*</span>
                  <span class='span1'><input type='text' id='state'></span>  
                </div>
                
                <div class='container-fluid'>
                  <span class='span1'>City*</span>
                  <span class='span1'><input type='text' id='city'></span>  
                </div>
                   
                <div class='container-fluid'>
                    <span class='span1'>Address*</span>
                    <span class='span1'><input type='text' id='addr'></span>  
                </div>
                
                <div class='container-fluid'>
                  <span class='span1'>Phone*</span>
                    <span class='span1'><input type='text' id='phone'></span> 
                </div>
                
                <div class='container-fluid'>
                  <span class='span1'>Contact*</span>
                    <span class='span1'><input type='text' id='contact'></span> 
                </div>
                
                <div class='container-fluid'>
                  <span class='span1'>Charge*</span>
                    <span class='span1'><input type='text' id='charge'></span> 
                </div>
                
                <div class='container-fluid'>
                  <span class='span1'>Room*</span>
                    <span class='span1'><input type='text' id='room'></span> 
                </div>
                
                <div class='container-fluid' style=''>
                <span class='span6' style='color:red;' id='hotel_err'></span>
                </div>
             
                <div class='modal-footer' style='text-align:center;'>
                    <span align='center'><button type='button' class='btn btn-primary' data-dismiss='modal' id='cancel'>Cancel</button></span>
                    <span align='center'><button type='button' class='btn btn-primary' id='add_hotel_button'>OK</button></span>
                </div>
            </div>
        </div>
    </div>";

        return $list;
    }

    function get_edit_hotel_dialog($id) {
        $query = "select * from mdl_hotels where id=$id";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $hotel = new stdClass();
            foreach ($row as $key => $value) {
                $hotel->$key = $value;
            }
        }

        $list = "";

        $list.="<div id='myModal' class='modal fade'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h4 class='modal-title'>Edit Hotel</h4>
                </div>
                <div class='modal-body'>
                <input type='hidden' id='id' value='$hotel->id'>
                 <div class='container-fluid' style=''>
                  <span class='span1'>State*</span>
                  <span class='span1'><input type='text' id='state' value='$hotel->state'></span>  
                </div>
                
                <div class='container-fluid'>
                  <span class='span1'>City*</span>
                  <span class='span1'><input type='text' id='city' value='$hotel->city'></span>  
                </div>
                   
                <div class='container-fluid'>
                    <span class='span1'>Address*</span>
                    <span class='span1'><input type='text' id='addr' value='$hotel->address'></span>  
                </div>
                
                <div class='container-fluid'>
                  <span class='span1'>Phone*</span>
                    <span class='span1'><input type='text' id='phone' value='$hotel->phone'></span> 
                </div>
                
                <div class='container-fluid'>
                  <span class='span1'>Contact*</span>
                    <span class='span1'><input type='text' id='contact' value='$hotel->contact'></span> 
                </div>
                
                <div class='container-fluid'>
                  <span class='span1'>Charge*</span>
                    <span class='span1'><input type='text' id='charge' value='$hotel->charge'></span> 
                </div>
                
                <div class='container-fluid'>
                  <span class='span1'>Room*</span>
                    <span class='span1'><input type='text' id='room' value='$hotel->room'></span> 
                </div>
                
                <div class='container-fluid' style=''>
                <span class='span6' style='color:red;' id='hotel_err'></span>
                </div>
             
                <div class='modal-footer' style='text-align:center;'>
                    <span align='center'><button type='button' class='btn btn-primary' data-dismiss='modal' id='cancel'>Cancel</button></span>
                    <span align='center'><button type='button' class='btn btn-primary' id='update_hotel_button'>OK</button></span>
                </div>
            </div>
        </div>
    </div>";

        return $list;
    }

    function add_hotel($hotel) {
        $query = "insert into mdl_hotels "
                . "(state,"
                . "city,"
                . "address,"
                . "phone,"
                . "contact,"
                . "charge,"
                . "room) "
                . "values('$hotel->state',"
                . "'$hotel->city',"
                . "'$hotel->addr',"
                . "'$hotel->phone',"
                . "'$hotel->contact',"
                . "'$hotel->charge',"
                . "'$hotel->room')";
        $this->db->query($query);
    }

    function update_hotel($hotel) {
        $query = "update mdl_hotels "
                . "set state='$hotel->state', "
                . "city='$hotel->city', "
                . "address='$hotel->addr', "
                . "phone='$hotel->phone', "
                . "contact='$hotel->contact', "
                . "charge='$hotel->charge', "
                . "room='$hotel->room' "
                . "where id=$hotel->id";
        $this->db->query($query);
    }

    function del_hotel($id) {
        $query = "delete from mdl_hotels where id=$id";
        $this->db->query($query);
    }

    function get_hotels_total() {
        $query = "select count(id) as total from mdl_hotels";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $num = $row['total'];
            } // end while
        } // end if
        else {
            $num = 0;
        }

        return $num;
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
        $query = "select * from mdl_hotels "
                . "LIMIT $offset, $rec_limit";
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

}
