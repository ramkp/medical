<?php

require_once ($_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php');

class Wsdata extends Util {

    function __construct() {
        parent::__construct();
    }

    function get_programs_list() {
        $list = "";
        $list.="<select id='wslist' style='width:175px;'>";
        $list.="<option value='0' selected>Please select</option>";
        $query = "select c.id, c.fullname, s.course "
                . "from mdl_course c, mdl_scheduler s "
                . "where c.id=s.course";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $list.="<option value='" . $row['id'] . "'>" . $row['fullname'] . "</option>";
            } // end while
        } // end if $num > 0
        $list.="</select>";

        return $list;
    }

    function get_workshop_status_page() {
        $list = "";
        $programs = $this->get_programs_list();
        $list.="<div class='row-fluid'>";
        $list.="<span class='span2'>$programs</span>";
        $list.="<span class='span1'>Start</span><span class='span2'><input type='text' id='date1' style='width:175px;'></span>";
        $list.="<span class='span1'>End</span><span class='span2'><input type='text' id='date2' style='width:175px;'></span>";
        $list.="<span class='span2'><button class='btn btn-primary' id='get_ws_data_btn'>Go</button></span>";
        $list.="</div>";

        $list.="<div class='row-fluid'>";
        $list.="<span class='span12' id='wsdata_err' style='color:red;'></span>";
        $list.="</div>";

        $list.="<div class='row-fluid' id='ajax_loader' style='display:none;text-align:center;'>";
        $list.="<span class='span12'><img src='https://$this->host/assets/img/ajax.gif' /></span>";
        $list.="</div>";

        $list.="<div class='row-fluid' id='ws_data_container'>";

        $list.="</div>";

        return $list;
    }

    function get_workshop_data($dates) {
        echo "<pre>";
        print_r($dates);
        echo "</pre>";
    }

}
