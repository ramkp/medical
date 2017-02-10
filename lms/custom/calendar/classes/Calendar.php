<?php

require_once ($_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php');

class Calendar extends Util {

    function __construct() {
        parent::__construct();
    }

    function get_user_calendar_dates($userid) {
        $dates = array();
        $now = time();
        $inst = $this->get_instructors_list();
        $query = "select * from mdl_calendar where userid=$userid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                if (in_array($userid, $inst) && strtotime($row['adate']) > $now) {
                    $dates[] = $row['adate'];
                } // end if 
            } // end while
        } // end if $num > 0
        return json_encode($dates);
    }

    function get_availabily_dates($userid) {
        $list = "";
        $dates = json_decode($this->get_user_calendar_dates($userid));
        if (count($dates) > 0) {
            $list.="<div class='row-fluid'>";
            $list.="<span class='span5'>My availability dates</span>";
            $list.="</div>";
            foreach ($dates as $d) {
                $list.="<div class='row-fluid' style=''>";
                $list.="<span class='span4'>$d</span>";
                $list.="</div>";
            }
        } // end if count($dates)>0
        else {
            $list.="<div class='row-fluid'>";
            $list.="<span class='span3'>N/A</span>";
            $list.="</div>";
        }

        return $list;
    }

    function create_user_calendar() {
        $list = "";
        $userid = $this->user->id;
        $dates = $this->get_availabily_dates($userid);

        $list.="<div class='row-fluid'>";
        $list.="<span class='span12'>";
        $list.="<table border='0'>";
        $list.="<tr valign='top'>";
        $list.="<td width='40%' style='padding:15px'>Please select availability dates<br><div id='user_calendar' data-userid='$userid'></div></td>";
        //$list.="<td style='padding:15px'></td>";
        $list.="<td style='padding:15px;'>$dates</td>";
        $list.="</tr>";
        $list.="</table>";
        $list.="</span>";
        $list.="</div>";

        return $list;
    }

    function get_instructors_list() {
        $inst = array();
        $query = "SELECT u.id, a.userid, a.roleid
                    FROM mdl_user u, mdl_role_assignments a
                    WHERE a.roleid =3
                    AND u.id = a.userid
                    GROUP BY u.id";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $inst[] = $row['userid'];
            } // end while
        } // end if $num > 0
        return $inst;
    }

    function is_record_exists($userid, $date) {
        $query = "select * from mdl_calendar "
                . "where userid=$userid "
                . "and adate='$date'";
        $num = $this->db->numrows($query);
        return $num;
    }

    function is_instructor($userid) {
        $inst = $this->get_instructors_list();
        return in_array($userid, $inst);
    }

    function get_record_id($userid, $date) {
        $query = "select * from mdl_calendar "
                . "where userid=$userid "
                . "and adate='$date'";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $id = $row['id'];
        }
        return $id;
    }

    function set_user_calendar($userid, $date) {
        $exists = $this->is_record_exists($userid, $date);
        if ($exists == 0) {
            $query = "insert into mdl_calendar (userid, adate) "
                    . "values ($userid,'$date')";
        } // end if $exists==0
        else {
            $id = $this->get_record_id($userid, $date);
            $query = "delete from mdl_calendar where id=$id";
        } // end else
        $this->db->query($query);
    }

    function update_user_calendar($c) {
        $userid = $c->userid;
        $date = $c->date;
        $this->set_user_calendar($userid, $date);
    }

}
