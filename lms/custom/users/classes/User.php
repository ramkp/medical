<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of User
 *
 * @author sirromas
 */
ini_set('error_reporting', E_ALL);
require_once ($_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/lms/custom/search/classes/Search.php');

class User extends Util {

    public $limit = 3;
    public $total;

    function get_users_list() {
        $list = "";
        if ($this->session->justloggedin == 1) {
            $users = array();
            $query = "select * from mdl_user where deleted=0 order by email LIMIT 0, $this->limit";
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $user = new stdClass();
                foreach ($row as $key => $value) {
                    $user->$key = $value;
                } // end foreach
                $users[] = $user;
            } // end while
            $total = $this->get_users_total();
            $list.= $this->create_users_list($users, $total, true, null);
        } // end if
        else {
            $list.="<p>You are not authenticated. &nbsp; <a href='https://medical2.com/login'><button class='btn btn-primary' id='relogin'>Login</button></a></p>";
        } // end else
        return $list;
    }

    function create_users_list($users, $total, $toolbar = true, $search_criteria = null) {
        $list = "";
        if ($toolbar == true) {
            $list.="<div class='container-fluid' style='text-align:center;'>";
            $list.="<span class='span2'>Search</span>";
            $list.="<span class='span2'><input type='text' id='search_user_input' class='typeahead' autocomplete='off' spellcheck='false' style='width:125px;' ></span>";
            $list.="<span class='span3'><button class='btn btn-primary' id='search_user'>Search</button></span>";
            $list.="<span class='span2'><button class='btn btn-primary' id='clear_user'>Clear filter</button></span>";
            $list.="</div>";
            $list.="<br><div class='container-fluid' style='text-align:center;'>";
            $list.="<span class='span8' style='color:red;' id='user_search_err'></span>";
            $list.="</div>";
            $list.="<div class='container-fluid' style='font-weight:bold;'>";
            $list.="<span class='span2'>Firstname</span>";
            $list.="<span class='span2'>Lastname</span>";
            $list.="<span class='span4'>Username</span>";
            $list.="<span class='span2'>Passsword</span>";
            $list.="</div>";
            $list.="<div class='container-fluid' style='display:none;text-align:center;' id='ajax_loader'>";
            $list.="<span class='span10'><img src='http://$this->host/assets/img/ajax.gif' /></span>";
            $list.="</div>";
        } // end if $toolbar==true
        $list.="<div id='users_container'>";
        //echo "Total: ".$this->total."<br>";
        $list.="<input type='hidden' value='$total' id='total'>";
        $list.="<input type='hidden' value='$search_criteria' id='item'>";
        $list.="<div class='container-fluid' style='text-align:center;font-weight:bold;'>";
        $total = count($users);
        if ($total <= $this->limit && $search_criteria == null) {
            $total = $this->get_users_total();
        }
        $list.="<span class='span8' style=''>Total users found: " . $total . "</span>";
        $list.="</div>";
        foreach ($users as $user) {
            $list.="<div class='container-fluid'>";
            $list.="<a href='https://" . $_SERVER['SERVER_NAME'] . "/lms/user/profile.php?id=$user->id' target='_blank'><span class='span2'>$user->firstname</a></span>";
            $list.="<span class='span2'><a href='https://" . $_SERVER['SERVER_NAME'] . "/lms/user/profile.php?id=$user->id' target='_blank'>$user->lastname</a></span>";
            $list.="<span class='span4'>$user->email</span>";
            $list.="<span class='span2'>$user->purepwd</span>";
            $list.="</div>";
            $list.="<div class='container-fluid'>";
            $list.="<span class='span10'><hr/></span>";
            $list.="</div>";
        } // end foreach
        $list.="</div>";
        if ($toolbar == true) {
            $list.="<div class='container-fluid'>";
            $list.="<span class='span8'  id='pagination'></span>";
            $list.="</div>";
        } // end if $toolbar==true
        return $list;
    }

    function get_user_item($page) {
        $users = array();
        $rec_limit = $this->limit;
        if ($page == 1) {
            $offset = 0;
        } // end if $page==1
        else {
            $page = $page - 1;
            $offset = $rec_limit * $page;
        }
        $query = "select * from mdl_user where deleted=0 order by email LIMIT $offset, $rec_limit";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $user = new stdClass();
            foreach ($row as $key => $value) {
                $user->$key = $value;
            } // end foreach
            $users[] = $user;
        } // end while
        $total = $this->get_users_total();
        $list = $this->create_users_list($users, $total, false, null);
        return $list;
    }

    function get_search_user_item($page, $email) {
        $users = array();
        $rec_limit = $this->limit;
        if ($page == 1) {
            $offset = 0;
        } // end if $page==1
        else {
            $page = $page - 1;
            $offset = $rec_limit * $page;
        }
        $query = "select * from mdl_user where deleted=0 "
                . "and email like '%" . trim($email) . "%' "
                . "order by email LIMIT $offset, $rec_limit";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $user = new stdClass();
            foreach ($row as $key => $value) {
                $user->$key = $value;
            } // end foreach
            $users[] = $user;
        } // end while
        $total = $this->get_users_total();
        $list = $this->create_users_list($users, $total, false, $email);
        return $list;
    }

    function get_users_total() {
        $query = "select * from mdl_user where deleted=0 order by email";
        $num = $this->db->numrows($query);
        return $num;
    }

    function search_user_by_email($email) {
        $list = "";
        $users = array();
        $query = "select * from mdl_user "
                . "where deleted=0"
                . " and email like '%" . trim($email) . "%' "
                . "or firstname like '%" . trim($email) . "%' "
                . "or lastname like '%" . trim($email) . "%'"
                . " order by email ";
        //echo "Query: ".$query."<br>";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $user = new stdClass();
                foreach ($row as $key => $value) {
                    $user->$key = $value;
                } // end foreach
                $users[] = $user;
            } // end while
            $list = $this->create_users_list($users, $num, false, $email);
        } // end if $num>0
        else {
            $list.="<div class='container-fluid' style='text-align:center;'>";
            $list.="<span class='span8'>No users found</span>";
            $list.="</div>";
        }
        return $list;
    }

}
