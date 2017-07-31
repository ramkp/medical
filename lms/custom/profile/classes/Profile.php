<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/my/classes/Dashboard.php';

Class Profile extends Util {

    function __construct() {
        parent::__construct();
    }

    function is_additional_tabs_enabled() {
        $roleid = 3;
        $query = "select p.id, p.category, p.item, r.permid, r.roleid "
                . "from mdl_special_permissions p, mdl_role2perm r "
                . "where p.category='users' "
                . "and p.item='profile' "
                . "and r.roleid=$roleid";
        //echo "Query:".$query."<br>";
        $num = $this->db->numrows($query);
        return $num;
    }

    function get_cna_user_additional_tabs($courseid, $userid) {
        $list = "";

        if ($courseid == 41) {

            $currentuser = $this->user->id;
            $ds = new Dashboard();
            $status = $ds->is_cna_instructor($courseid, $currentuser);
            $enabled = $this->is_additional_tabs_enabled();
            if ($status == 1 && $enabled == 1) {

                $payments = $ds->get_user_payments_block($userid);
                $grades = $ds->get_user_grades($userid);
                $attend = $ds->get_student_attendance($userid);

                $list.="<ul class='nav nav-tabs'>";
                $list.="<li class='active'><a data-toggle='tab' href='#home'>Payments</a></li>";
                $list.="<li><a data-toggle='tab' href='#grades'>Grades</a></li>";
                $list.="<li><a data-toggle='tab' href='#attend'>Attendance</a></li>";
                $list.="<input type='hidden' id='userid' value='$userid'>";
                $list.="</ul>";

                $list.="<div class='tab-content'>
              <div id='home' class='tab-pane fade in active'>
                <h3>Payments &nbsp;&nbsp;<button id='print_payment'>Print</button></h3>
                <p>$payments</p>
              </div>
              <div id='grades' class='tab-pane fade'>
                <h3>Grades &nbsp;&nbsp;<button id='print_grades'>Print</button></h3>
                <p>$grades</p>
              </div>
             <div id='attend' class='tab-pane fade'>
                <h3>Attendance &nbsp;&nbsp;<button id='print_att'>Print</button></h3>
                <p>$attend</p>
              </div>
              
            </div>";
            }
        }


        return $list;
    }

}
