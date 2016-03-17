<?php

/**
 * Description of Installment
 *
 * @author sirromas
 */

require_once ($_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php');

class Installment extends Util {

    function get_installment_page() {
        $users = array();
        $query = "select * from mdl_installment_users order by id asc limit 0,1";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $user = new stdClass();
                foreach ($row as $key => $value) {
                    $user->$key = $value;
                } // end foreach 
                $users[] = $user;
            } /// end while
        } // end if $num > 0        
        $list = $this->create_installment_page($users);
        return $list;
    }

    function get_course_name($courseid) {
        $query = "select fullname from mdl_course where id=$courseid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $name = $row['fullname'];
        }
        return $name;
    }

    function create_installment_page($users, $toolbar = true) {
        $list = "";
        //print_r($users);
        //echo "<br>";
        //echo "Toolbar: ".$toolbar;
        if (count($users) > 0) {
            $list.="<div id='installment_users_container'>";
            foreach ($users as $user) {
                $userdata = $this->get_user_details($user->userid);
                $coursename = $this->get_course_name($user->courseid);
                if ($user->modifierid == 0) {
                    $modifier = new stdClass();
                    $modifier->firstname = 'Super';
                    $modifier->lastname = 'Admin';
                } // end if $user->modifierid==0
                else {
                    $modifier = $this->get_user_details($user->modifierid);
                } // end else 
                $date_created = date('Y-m-d', $user->created);
                $list.="<div class='container-fluid'>";
                $list.="<span class='span4' id='inst_status_$user->id'></span>";
                $list.="</div>";
                $list.="<div class='container-fluid'>";
                $list.="<span class='span2'>User firstname</span><span class='span2'>$userdata->firstname</span>";
                $list.="</div>";
                $list.="<div class='container-fluid'>";
                $list.="<span class='span2'>User lastname</span><span class='span2'>$userdata->lastname</span>";
                $list.="</div>";
                $list.="<div class='container-fluid'>";
                $list.="<span class='span2'>User email</span><span class='span2'>$userdata->email</span>";
                $list.="</div>";
                $list.="<div class='container-fluid'>";
                $list.="<span class='span2'>User program</span><span class='span2'>$coursename</span>";
                $list.="</div>";
                $list.="<div class='container-fluid'>";
                $list.="<span class='span2'>Installment sum</span><span class='span2'>$$user->sum</span>";
                $list.="</div>";
                $list.="<div class='container-fluid'>";
                $list.="<span class='span2'>Installment num</span><span class='span2'>$user->num</span>";
                $list.="</div>";
                $list.="<div class='container-fluid'>";
                $list.="<span class='span2'>Created by</span><span class='span2'>$modifier->firstname &nbsp;$modifier->lastname</span>";
                $list.="</div>";
                $list.="<div class='container-fluid'>";
                $list.="<span class='span2'>Creation date</span><span class='span2'>$date_created</span>";
                $list.="</div>";

                //$list.="<div class='container-fluid'>";
                //$list.="<span class='span4'><button type='button' id='update_user_installment' class='btn btn-primary'>Update</button></span>";
                //$list.="</div>";
            } // end foreach 
            $list.="</div>";
            if ($toolbar == true) {
                $list.="<div class='container-fluid'>";
                $list.="<span class='span9'  id='pagination'></span>";
                $list.="</div>";
                $list.="<div class='container-fluid'>";
                $list.="<span class='span6'><a href='#' onClick='return false;' id='add_installment_user'>Add Installment User</a></span>";
                $list.="</div>";
                $list.=$this->get_add_installment_user_page();
            }
        } // end if count($users)>0
        else {
            $list.="<div class='container-fluid'>";
            $list.="<span class='span4'>No installment users found</span>";
            $list.="</div>";
            if ($toolbar == true) {
                $list.="<div class='container-fluid'>";
                $list.="<span class='span9'  id='pagination'></span>";
                $list.="</div>";
                $list.="<div class='container-fluid'>";
                $list.="<span class='span6'><a href='#' onClick='return false;' id='add_installment_user'>Add Installment User</a></span>";
                $list.="</div>";
                $list.=$this->get_add_installment_user_page();
            }
            $list.=$this->get_add_installment_user_page();
        }
        return $list;
    }

    function get_total_installment_users() {
        $query = "select * from mdl_installment_users ";
        $num = $this->db->numrows($query);
        return $num;
    }

    function get_add_installment_user_page() {
        $list = "";
        $program_types = $this->get_course_categories();
        $list.="<div id='add_installment_user_container' style='display:none;'>";
        $list.="<div class='container-fluid'>";

        $list.="<span class='span6' id='add_inst_user_status'></span>";
        $list.="</div>";
        $list.="<div class='container-fluid'>";
        $list.="<span class='span6'>$program_types</span>";
        $list.="</div>";
        $list.="<div class='container-fluid'>";
        $list.="<span class='span6' id='category_courses'></span>";
        $list.="</div>";
        $list.="<div class='container-fluid'>";
        $list.="<span class='span6' id='enrolled_users'></span>";
        $list.="</div>";

        $list.="<div id='installment_params' style='display:none;'>";
        $list.="<div class='container-fluid'>";
        $list.="<span class='span6'><span class='span3'>Payments num</span><span class='span4'><input type='text' id='inst_num' ></span></span>";
        $list.="</div></div>";

        $list.="<div class='container-fluid'>";
        $list.="<span class='span6'><button type='button' id='add_installment_user' class='btn btn-primary'>Add User</button></span>";
        $list.="</div>";
        $list.="</div>";
        return $list;
    }

    function get_installment_item($page) {
        //echo "Page: ".$page."<br>";
        $installment_users = array();
        $rec_limit = 1;
        $page = $page - 1;
        $offset = $rec_limit * $page;
        $query = "select * from mdl_installment_users LIMIT $offset, $rec_limit";
        //echo "Query: ".$query."<br>";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $user = new stdClass();
            foreach ($row as $key => $value) {
                $user->$key = $value;
            } // end foreach      
            $installment_users[] = $user;
        } // end while
        $list = $this->create_installment_page($installment_users, false);
        return $list;
    }

    function get_course_installment_sum($courseid, $num) {
        $query = "select cost from mdl_course where id=$courseid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $cost = $row['cost'];
        }
        $sum = round($cost / $num, 2);
        return $sum;
    }

    function add_installment_user($courseid, $userid, $num) {
        $modifierid = $this->user->id;
        $sum = $this->get_course_installment_sum($courseid, $num);
        $created = time();
        $query = "insert into mdl_installment_users "
                . "(courseid,"
                . "userid,"
                . "sum,"
                . "num,"
                . "modifierid,"
                . "created) "
                . "values ('" . $courseid . "',"
                . "'" . $userid . "',"
                . "'" . $sum . "',"
                . "'" . $num . "',"
                . "'" . $modifierid . "',"
                . "'" . $created . "')";
        $this->db->query($query);
        $list = "User successfully added";
        return $list;
    }

}
