<?php

/**
 * Description of Signup
 *
 * @author sirromas
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/Upload.php';

class Payment {

    public $db;
    public $enroll;
    public $user;

    function __construct() {
        $this->db = new pdo_db();
        $this->enroll = new Enroll();
    }

    function enroll_user($user) {
        $this->user = $user;
        $this->enroll->single_signup($user);
        $list = $this->get_payment_section_personal($user);
        return $list;
    }

    function get_course_name($courseid) {
        $query = "select id, fullname from mdl_course "
                . "where id=$courseid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $name = $row['fullname'];
        }
        return $name;
    }

    function get_personal_course_cost($courseid) {
        $query = "select id, discount_size, cost from mdl_course "
                . "where id=$courseid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $cost = $row['cost'];
            $discount = $row['discount_size'];
        }
        if ($discount > 0) {
            $final_cost = $cost - round(($cost * $discount) / 100, 2);
        } // end if $discount>0
        else {
            $final_cost = $cost;
        }
        $course_cost = array('cost' => $final_cost, 'discount' => $discount);
        return $course_cost;
    }

    function get_card_types_dropbox() {
        $drop_down = "";
        $drop_down.="<div class='dropdown'>
        <a href='#' id='card_type' data-toggle='dropdown' class='dropdown-toggle' onClick='return false;'>Card type<b class='caret'></b></a>
        <ul class='dropdown-menu'>";
        $drop_down.="<li><a href='#' id='Visa'>Visa</a></li>";
        $drop_down.="<li><a href='#' id='Discover'>Discover</a></li>";
        $drop_down.="<li><a href='#' id='Master'>Master</a></li>";
        $drop_down.="<li><a href='#' id='American Express'>American Express</a></li>";
        $drop_down.="<li><a href='#' id='Diner Club'>Diner Club</a></li>";
        $drop_down.="</ul></div>";
        return $drop_down;
    }

    function get_year_drop_box() {
        $drop_down = "";
        $drop_down.= "<select id='card_year' style='width: 75px;'>";
        $drop_down.="<option value='--' selected>--</option>";
        $drop_down.="<option value='2016'>2016</option>";
        $drop_down.="<option value='2017'>2017</option>";
        $drop_down.="<option value='2018'>2018</option>";
        $drop_down.="<option value='2019'>2019</option>";
        $drop_down.="<option value='2020'>2020</option>";
        $drop_down.="</select>";
        return $drop_down;
    }

    function get_month_drop_box() {
        $drop_down = "";
        $items = "<option value='01'>01</option>
                <option value='--' selected>--</option>
                <option value='03'>02</option>
                <option value='03'>03</option>
                <option value='04'>04</option>
                <option value='05'>05</option>
                <option value='06'>06</option>
                <option value='07'>07</option>
                <option value='08'>08</option>
                <option value='09'>09</option>
                <option value='10'>10</option>
                <option value='11'>11</option>
                <option value='12'>12</option>";
        $drop_down.= "<select id='card_month' style='width: 65px;'>";
        $drop_down.=$items;
        $drop_down.="</select>";
        return $drop_down;
    }

    function get_course_group_discount($courseid, $tot_participants) {

        // 1. Get course cost
        $query = "select id, cost from mdl_course "
                . "where id=$courseid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $cost = $row['cost'];
        }

        // 2. Get course group discount
        $query = "select courseid, group_discount_size "
                . "from mdl_group_discount "
                . "where courseid=$courseid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $discount = $row['group_discount_size'];
        }

        $group_cost = $cost * $tot_participants;
        if ($discount > 0) {
            $final_cost = $group_cost - round(($group_cost * $discount) / 100, 2);
        } // end if $discount>0
        else {
            $final_cost = round($group_cost, 2);
        }
        $course_cost = array('cost' => $final_cost, 'discount' => $discount);
        return $course_cost;
    }

    function get_payment_section_personal($user, $group = null, $tot_participants = null) {
        $list = "";
        $cost_block = "";
        $course_name = $this->get_course_name($user->courseid);
        $card_types = $this->get_card_types_dropbox();
        $card_year = $this->get_year_drop_box();
        $card_month = $this->get_month_drop_box();
        if ($group == NULL) {
            $course_cost = $this->get_personal_course_cost($user->courseid);
            $list.= "<input type='hidden' value='' id='user_group' name='user_group' />";
        } // end if $group==NULL 
        else {
            $course_cost = $this->get_course_group_discount($user->courseid, $tot_participants);
            $list.= "<input type='hidden' value='$user->group_name' id='user_group' name='user_group' />";
        } // end else

        if ($course_cost['discount'] == 0) {
            $cost_block.="$" . $course_cost['cost'];
        } // end if $course_cost['discount']==0
        else {
            $cost_block.="$" . $course_cost['cost'] . "&nbsp; (discount is " . $course_cost['discount'] . "%)";
        }

        $list.="<div class='panel panel-default' id='personal_payment_details'>";
        $list.="<div class='panel-heading'style='text-align:left;'><h5 class='panel-title'>Payment Detailes</h5></div>";
        $list.="<div class='panel-body'>";

        $list.="<div class='container-fluid' style='text-align:left;font-weight:bold;'>";
        $list.="<span class='span2'>Selected program</span>";
        $list.="<span class='span2'>$course_name</span>";
        $list.="<span class='span2'>Sum to be charged</span>";
        $list.="<span class='span2'>$cost_block</span>";
        $list.= "<input type='hidden' value='" . $course_cost['cost'] . "' id='payment_sum' />";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>Card type*</span>";
        $list.="<span class='span2'>$card_types</span>";
        $list.="<span class='span2'>Card number*</span>";
        $list.="<span class='span2'><input type='text' id='card_no' name='card_no'  ></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>Card Holder Name*</span>";
        $list.="<span class='span2'><input type='text' id='card_holder' name='card_holder'  ></span>";
        $list.="<span class='span2'>Expiration Date*</span>";
        $list.="<span class='span2'>" . $card_year . "&nbsp;&nbsp;&nbsp;" . $card_month . "</span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>Address*</span>";
        $list.="<span class='span2'><input type='text' id='bill_addr' name='bill_addr'  ></span>";
        $list.="<span class='span2'>City*</span>";
        $list.="<span class='span2'><input type='text' id='bill_city' name='bill_city'  ></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>Zip code*</span>";
        $list.="<span class='span2'><input type='text' id='bill_zip' name='bill_zip'  ></span>";
        $list.="<span class='span2'>Contact email*</span>";
        $list.="<span class='span2'><input type='text' id='bill_email' name='bill_email'  ></span>";
        $list.="</div>";

        $list.= "<div class='container-fluid' style='text-align:left;'>";
        $list.= "<span class='span2'><button class='btn btn-primary' id='make_payment_personal'>Make payment</button></span>";
        $list.= "&nbsp <span style='color:red;' id='personal_payment_err'></span>";
        $list.= "</div>";
        
        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span8' style='text-align:center;display:none;' id='ajax_loading_payment'><img src='http://cnausa.com/assets/img/ajax.gif' /></span>";
        $list.="</div>";
        
        $list.="</div>";
        $list.="</div>";
        return $list;
    }

    function get_payment_section_group($user, $tot_participants) {
        $list = $this->get_payment_section_personal($user, 1, $tot_participants);
        return $list;
    }

    function get_group_payment_section_file($group_common_section) {

        $upload = new Upload();
        $users = $upload->get_users_file_data();
        $tot_participants = count($users);
        $groupid = $this->enroll->create_course_group($group_common_section->courseid, $group_common_section->group_name);

        foreach ($users as $group_participant) {
            $user = new stdClass();
            $user->email = $group_participant->email;
            $user->first_name = $group_participant->first_name;
            $user->last_name = $group_participant->last_name;
            $user->courseid = $group_common_section->courseid;
            $user->addr = $group_common_section->addr;
            $user->inst = $group_common_section->inst;
            $user->zip = $group_common_section->zip;
            $user->city = $group_common_section->city;
            $user->state = $group_common_section->state;
            $user->country = 'US';
            $email_exists = $this->enroll->is_email_exists($group_participant->email);
            if ($email_exists == 0) {
                $this->enroll->single_signup($user);
                $userid = $this->enroll->getUserId($group_participant->email);
                $this->enroll->add_user_to_group($groupid, $userid);
            } // end if $email_exists==0
        } // end foreach

        $list = $this->get_payment_section_personal($group_common_section, 1, $tot_participants);
        return $list;
    }

    function get_group_payment_section($group_common_section, $users, $tot_participants) {
        $groupid = $this->enroll->create_course_group($group_common_section->courseid, $group_common_section->group_name);

        foreach ($users as $group_participant) {
            $user = new stdClass();
            $user->email = $group_participant->email;
            $user->first_name = $group_participant->first_name;
            $user->last_name = $group_participant->last_name;
            $user->courseid = $group_common_section->courseid;
            $user->addr = $group_common_section->addr;
            $user->inst = $group_common_section->inst;
            $user->zip = $group_common_section->zip;
            $user->city = $group_common_section->city;
            $user->state = $group_common_section->state;
            $user->country = 'US';
            $email_exists = $this->enroll->is_email_exists($group_participant->email);
            if ($email_exists == 0) {
                $this->enroll->single_signup($user);
                $userid = $this->enroll->getUserId($group_participant->email);
                $this->enroll->add_user_to_group($groupid, $userid);
            } // end if $email_exists==0
        } // end foreach

        $list = $this->get_payment_section_personal($group_common_section, 1, $tot_participants);
        return $list;
    }

    function is_group_exist($group_name) {
        $query = "select id, name from mdl_groups where name='$group_name'";
        return $num = $this->db->numrows($query);
    }

    function confirm_user($email) {
        $userid = $this->enroll->getUserId($email);
        $query = "update mdl_user set confirmed=1 where id=$userid";
        $this->db->query($query);
    }

    function get_group_users($group_name) {
        //1. Get group id
        $query = "select id, name from mdl_groups where name='$group_name'";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $groupid = $row['id'];
        } // end while
        //2. Get group users
        $query = "select groupid, userid from mdl_groups_members where groupid=$groupid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $users[] = $row['userid'];
        }
        return $users;
    }

    function get_user_detailes($userid) {
        $query = "select id, username, firstname, lastname from mdl_user "
                . "where id=$userid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $user = new stdClass();
            foreach ($row as $key => $value) {
                $user->$key = $value;
            } // end foreach
        } // end while 
        return $user;
    }

    function make_stub_payment($card) {
        $mailer = new Mailer();
        $user_group = $card->user_group;
        
        if ($user_group == '') {
            $this->confirm_user($card->email);
            $mailer->send_payment_confirmation_message($card);
        } // end if $user_group==''
        else {
            $group_users = $this->get_group_users($user_group);
            $mailer->send_payment_confirmation_message($card, 1);
            foreach ($group_users as $userid) {
                $user = $this->get_user_detailes($userid);
                $this->confirm_user($user->username);
                $mailer->send_group_payment_confirmation_message($user);
            } // end foreach
        } // end else
        $list = "";
        $list.="<div class='panel panel-default' id='personal_payment_details'>";
        $list.="<div class='panel-heading'style='text-align:left;'><h5 class='panel-title'>Payment Detailes</h5></div>";
        $list.="<div class='panel-body'>";
        $list.= "<div class='container-fluid' style='text-align:left;'>";
        $list.= "<span class='span8'>Payment is successfull (this is fake payment, no real card charging :) ). Confirmation email is sent to $card->email.</span>";
        $list.="</div>";
        $list.="</div>";
        $list.="</div>";
        return $list;
    }

}
