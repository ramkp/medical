<?php

/**
 * Description of Installment
 *
 * @author sirromas
 */
//require_once ($_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php');
require_once ('/home/cnausa/public_html/lms/custom/utils/classes/Util.php');

class Installment extends Util {

    public $limit = 3;
    public $period = 28; // installment period in days

    function get_installment_page() {
        $list = "";
        if ($this->session->justloggedin == 1) {
            $users = array();
            $query = "select * from mdl_installment_users order by id asc limit 0, $this->limit";
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
            $list.= $this->create_installment_page($users);
        } // end if
        else {
            $list.="<p>You are not authenticated. &nbsp; <a href='https://medical2.com/login'><button class='btn btn-primary' id='relogin'>Login</button></a></p>";
        } // end else

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
                $list.="<span class='span3'>User firstname</span><span class='span2'>$userdata->firstname</span>";
                $list.="</div>";
                $list.="<div class='container-fluid'>";
                $list.="<span class='span3'>User lastname</span><span class='span2'>$userdata->lastname</span>";
                $list.="</div>";
                $list.="<div class='container-fluid'>";
                $list.="<span class='span3'>User email</span><span class='span2'>$userdata->email</span>";
                $list.="</div>";
                $list.="<div class='container-fluid'>";
                $list.="<span class='span3'>User program</span><span class='span4'>$coursename</span>";
                $list.="</div>";
                $list.="<div class='container-fluid'>";
                $list.="<span class='span3'>Installment duration</span><span class='span2'>$this->period days</span>";
                $list.="</div>";
                $list.="<div class='container-fluid'>";
                $list.="<span class='span3'>Installment payments num</span><span class='span2'>$user->num</span>";
                $list.="</div>";
                $list.="<div class='container-fluid'>";
                $list.="<span class='span3'>Installment Interval</span><span class='span2'>" . round($this->period / $user->num) . " days</span>";
                $list.="</div>";
                $list.="<div class='container-fluid'>";
                $list.="<span class='span3'>Installment sum</span><span class='span2'>$$user->sum</span>";
                $list.="</div>";

                if ($user->subscription_id != '') {
                    $list.="<div class='container-fluid'>";
                    $list.="<span class='span3'>Subscription ID</span><span class='span2'>$user->subscription_id</span>";
                    $list.="</div>";
                    $list.="<div class='container-fluid'>";
                    $list.="<span class='span3'>Subscription start date</span><span class='span2'>" . date('Y-m-d', $user->subscription_start) . "</span>";
                    $list.="</div>";
                } // end if $user->subscription_id!=''

                $list.="<div class='container-fluid'>";
                $list.="<span class='span3'>Created by</span><span class='span2'>$modifier->firstname &nbsp;$modifier->lastname</span>";
                $list.="</div>";
                $list.="<div class='container-fluid'>";
                $list.="<span class='span3'>Creation date</span><span class='span2'>$date_created</span>";
                $list.="</div>";
                $list.="<div class='container-fluid'>";
                $list.="<span class='span7'><hr/></span>";
                $list.="</div>";
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
        $rec_limit = $this->limit;
        if ($page == 1) {
            $offset = 0;
        } // end if $page==1
        else {
            $page = $page - 1;
            $offset = $rec_limit * $page;
        }
        $query = "select * from mdl_installment_users order by id asc  LIMIT $offset, $rec_limit";
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

    function get_course_cost($courseid) {
        $query = "select * from mdl_course where id=$courseid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $cost = $row['cost'];
        }
        return $cost;
    }

    function get_user_card_payments($userid, $courseid) {
        $sum = 0;
        $query = "select * from mdl_card_payments "
                . "where "
                . "userid=$userid "
                . "and courseid=$courseid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $sum = $sum + $row['psum'];
            } // end while 
        } // end if $num > 0
        return $sum;
    }

    function verify_installment_users() {
        $query = "select * from mdl_installment_users";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $course_cost = $this->get_course_cost($row['courseid']);
                $paid_sum = $this->get_user_card_payments($row['userid'], $row['courseid']);
                if ($paid_sum == $course_cost) {
                    $query = "delete from mdl_installment_users "
                            . "where  userid=" . $row['userid'] . ""
                            . " and courseid=" . $row['courseid'] . "";
                    $this->db->numrows($query);
                    echo "User with ID (" . $row['userid'] . ") hase been deleted from installment users \n";
                } // end if $paid_sum == $course_cost
                else {
                    echo "User with ID (" . $row['userid'] . ") is not yet paid in full for course with ID (" . $row['courseid'] . ")";
                } // end elese 
            } // end while
        } // end if $num>0
        else {
            echo "There are no installment users available ....\n";
        }
    }

}
