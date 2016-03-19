<?php

/**
 * Description of Stats
 *
 * @author sirromas
 */
require_once ($_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php');

class Stats extends Util {

    public $sources;
    public $states;

    function __construct() {
        parent::__construct();
        $this->sources = $this->get_users_sources();
        $this->states = $this->get_user_states();
    }

    /*     * ***************************************************************
     * 
     *                   Users source page
     * 
     * ************************************************************** */

    function get_users_sources() {
        $sources = array();
        $query = "select * from mdl_come_from order by src";
        //echo "Query: ".$query."<br>";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $source = new stdClass();
            $source->id = $row['id'];
            $source->src = $row['src'];
            $source->counter = 0;
            $sources[$row['src']] = $source;
        } // end while
        return $sources;
    }

    function get_users_source_page() {
        $src_users = array();
        $query = "select id, firstname, lastname, email, come_from "
                . "from  mdl_user where come_from <>''";
        //echo "Query: ".$query."<br>";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $user = new stdClass();
                $user->id = $row['id'];
                $user->firstname = $row['firstname'];
                $user->lastname = $row['lastname'];
                $user->email = $row['email'];
                $user->come_from = $row['come_from'];
                if ($user->come_from == $this->sources[$row['come_from']]->src) {
                    $this->sources[$row['come_from']]->counter++;
                } // end if $user->come_from==$this->sources->src
                $src_users[] = $user;
            } // end while
        } // end if $num > 0
        $list = $this->create_users_course_page($src_users);
        return $list;
    }

    function create_users_course_page($src_users) {
        $list = "";
        $list.="<div class='container-fluid' style='text-align:left;font-weight:bold;'>";
        $list.="<span class='span9'>Users - Source</span>";
        $list.="</div>";
        if (count($src_users) > 0) {
            $src_data = $this->sources;
            foreach ($src_data as $data) {
                if ($data->counter > 0) {
                    $list.="<div class='container-fluid' style='text-align:left;'>";
                    $list.="<span class='span3'>$data->src</span><span class='span3'>$data->counter</span>";
                    $list.="</div>";
                } // end if $data->counter>0
            } // end foreach
            $list.="<div class='container-fluid' style='text-align:center;'>";
            $list.="<span class='span6'><hr/></span>";
            $list.="</div>";
        } // end if count($src_users)>0        
        return $list;
    }

    /*     * **************************************************************
     * 
     *                   Users states page
     * 
     * ************************************************************** */

    function get_user_states() {
        $states = array();
        $query = "select * from mdl_states order by state";
        //echo "Query: ".$query."<br>";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $state = new stdClass();
            $state->id = $row['id'];
            $state->state = $row['state'];
            $state->counter = 0;
            $states[$row['state']] = $state;
        } // end while
        return $states;
    }

    function get_users_states_page() {
        $state_users = array();
        $query = "select id, firstname, lastname, email, state "
                . "from mdl_user where state <>''";
        //echo "Query: ".$query."<br>";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $user = new stdClass();
                $user->id = $row['id'];
                $user->firstname = $row['firstname'];
                $user->lastname = $row['lastname'];
                $user->email = $row['email'];
                $user->state = $row['state'];
                if ($user->state == $this->states[$row['state']]->state) {
                    $this->states[$row['state']]->counter++;
                } // end if $user->state==$this->states[$row['state']]->state
                $state_users[] = $user;
            } // end while
        } // end if $num > 0
        $list = $this->create_users_states_page($state_users);
        return $list;
    }

    function create_users_states_page($state_users) {
        $list = "";
        $list.="<div class='container-fluid' style='text-align:left;font-weight:bold;'>";
        $list.="<span class='span9'>Users - States</span>";
        $list.="</div>";
        if (count($state_users > 0)) {
            $states_data = $this->states;
            foreach ($states_data as $data) {
                if ($data->counter > 0) {
                    $list.="<div class='container-fluid' style='text-align:left;'>";
                    $list.="<span class='span3'>$data->state</span><span class='span3'>$data->counter</span>";
                    $list.="</div>";
                } // end if $data->counter>0
            } // end foreach
            $list.="<div class='container-fluid' style='text-align:center;'>";
            $list.="<span class='span6'><hr/></span>";
            $list.="</div>";
        } // end if count($state_users>0)
        return $list;
    }

}
