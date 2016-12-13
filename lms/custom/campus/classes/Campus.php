<?php

require_once ($_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php');

class Campus extends Util {

    function __construct() {
        parent::__construct();
    }

    function get_campuses_list() {
        $list = "";

        $list.="<div class='panel panel-default' id='payment_options' style='margin-bottom:0px;'>";
        $list.="<div class='panel-heading' style='text-align:left;'><h5 class='panel-title'>Campus Locations &nbsp; <button id='add_campus'>Add</button></h5></div>";
        $list.="<div class='panel-body'>";

        $list.="<div class='container-fluid' style='text-align:left;font-weight:bold;'>";
        $list.="<span class='span1'>Lat</span>";
        $list.="<span class='span1'>Lng</span>";
        $list.="<span class='span2'>Name</span>";
        $list.="<span class='span5'>Short Description</span>";
        $list.="</div>";

        $query = "select * from mdl_campus";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $list.="<div class='container-fluid' style='text-align:left;'>";
                $list.="<span class='span1'>" . $row['lat'] . "</span>";
                $list.="<span class='span1'>" . $row['lon'] . "</span>";
                $list.="<span class='span2'>" . $row['name'] . "</span>";
                $list.="<span class='span5'>" . $row['campus_desc'] . "</span>";
                $list.="<span class='span1'><button class='edit_campus' data-id='" . $row['id'] . "'>Edit</button></span>";
                $list.="<span class='span1'><button class='del_campus' data-id='" . $row['id'] . "'>Delete</button></span>";
                $list.="</div>";
            } // end while
        } // end if 
        else {
            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span4'>There are no campus locations added</span>";
            $list.="<span class='span1'><button id='add_campus' style='cursor:pointer;'>Add</button></span>";
            $list.="</div>";
        } // end else

        $list.="</div>";
        $list.="</div>";

        return $list;
    }

    function get_add_dialog() {
        $list = "";

        $list.="<div id='myModal' class='modal fade'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h4 class='modal-title'>Add Campus Location</h4>
                </div>
                <div class='modal-body'>
                
                   
                <div class='container-fluid'>
                <span class='span1'>Lattitude*</span>
                <span class='span3'><input type='text' id='lat' style='width:275px;'></span>
                </div>
                
                <div class='container-fluid'>
                <span class='span1'>Longittude*</span>
                <span class='span3'><input type='text' id='lon' style='width:275px;'></span>
                </div>
                
                <div class='container-fluid'>
                <span class='span1'>Name*</span>
                <span class='span3'><input type='text' id='name' style='width:275px;'></span>
                </div>
                
                <div class='container-fluid'>
                <span class='span1'>Description*</span>
                <span class='span3'><textarea id='desc' style='width:275px;'></textarea></span>
                </div>
                
                <div class='container-fluid' style=''>
                <span class='span1'>&nbsp;</span>
                <span class='span3' style='color:red;' id='campus_err'></span>
                </div>
             
                <div class='modal-footer' style='text-align:center;'>
                    <span align='center'><button type='button' class='btn btn-primary' data-dismiss='modal' id='cancel'>Cancel</button></span>
                    <span align='center'><button type='button' class='btn btn-primary' id='add_campus_location'>OK</button></span>
                </div>
            </div>
        </div>
    </div>";

        return $list;
    }

    function get_edit_dialog($id) {
        $list = "";

        $query = "select * from mdl_campus where id=$id";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $c = new stdClass();
            foreach ($row as $key => $value) {
                $c->$key = $value;
            }
        }

        $list.="<div id='myModal' class='modal fade'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h4 class='modal-title'>Edit Campus Location</h4>
                </div>
                <div class='modal-body'>
                <input type='hidden' id='id' value='$id'>
                   
                <div class='container-fluid'>
                <span class='span1'>Lattitude*</span>
                <span class='span3'><input type='text' id='lat' style='width:275px;' value='$c->lat'></span>
                </div>
                
                <div class='container-fluid'>
                <span class='span1'>Longittude*</span>
                <span class='span3'><input type='text' id='lon' style='width:275px;' value='$c->lon'></span>
                </div>
                
                <div class='container-fluid'>
                <span class='span1'>Name*</span>
                <span class='span3'><input type='text' id='name' style='width:275px;' value='$c->name'></span>
                </div>
                
                <div class='container-fluid'>
                <span class='span1'>Description*</span>
                <span class='span3'><textarea id='desc' style='width:275px;'>$c->campus_desc</textarea></span>
                </div>
                
                <div class='container-fluid' style=''>
                <span class='span1'>&nbsp;</span>
                <span class='span3' style='color:red;' id='campus_err'></span>
                </div>
             
                <div class='modal-footer' style='text-align:center;'>
                    <span align='center'><button type='button' class='btn btn-primary' data-dismiss='modal' id='cancel'>Cancel</button></span>
                    <span align='center'><button type='button' class='btn btn-primary' id='edit_campus_location'>OK</button></span>
                </div>
            </div>
        </div>
    </div>";

        return $list;
    }

    function edit_campus($c) {
        $query = "update mdl_campus "
                . "set lat='$c->lat', "
                . "lon='$c->lon', "
                . "name='$c->name', "
                . "campus_desc='$c->desc' where id=$c->id";
        $this->db->query($query);
    }

    function add_new_campus($campus) {
        $query = "insert into mdl_campus"
                . " (lat,"
                . "lon,"
                . "name,"
                . "campus_desc) "
                . "values ('$campus->lat',"
                . "'$campus->lon',"
                . "'$campus->name',"
                . "'$campus->desc') ";
        //echo "Query: " . $query . "<br>";
        $this->db->query($query);
    }

    function del_campus($id) {
        $query = "delete from mdl_campus where id=$id";
        $this->db->query($query);
    }

}
