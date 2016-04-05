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
                . "from  mdl_user where deleted=0 and come_from <>''";
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
        $whole_div = "";
        $list = "";
        $list.="<div class='container-fluid' style='text-align:left;font-weight:bold;'>";
        $list.="<span class='span9'>Users - Source</span>";
        $list.="</div>";
        if (count($src_users) > 0) {
            $src_data = $this->sources;
            foreach ($src_data as $data) {
                if ($data->counter > 0) {
                    $list.="<div class='container-fluid' style='text-align:left;padding-right:0;margin:0;'>";
                    $list.="<span class='span4'>$data->src</span><span class='span1' style='padding-right:0px;'>$data->counter</span>";
                    $list.="</div>";
                } // end if $data->counter>0
            } // end foreach            
        } // end if count($src_users)>0        
        $whole_div.="<table align='center' width='100%' border='0' style='padding-right:0px;margin:0px;'>";
        $whole_div.="<tr>";
        $whole_div.="<td style='width:245px;'>$list</td><td align='left'><span class='span6' id='chart_div' style='border:0px solid;text-align:center;' vertical-align='top' valign='top'></span></td>";
        $whole_div.="</tr>";
        $whole_div.="<tr>";
        $whole_div.="<td colspan='2'><div class='container-fluid' style='text-align:center;'><span class='span12'><br/><hr/></span></div></td>";
        $whole_div.="</tr>";
        $whole_div.="</table>";
        return $whole_div;
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
        $whole_page = "";
        $list = "";
        $list.="<div class='container-fluid' style='text-align:left;font-weight:bold;'>";
        $list.="<span class='span9'>Users - States</span>";
        $list.="</div>";
        if (count($state_users > 0)) {
            $states_data = $this->states;
            foreach ($states_data as $data) {
                if ($data->counter > 0) {
                    $list.="<div class='container-fluid' style='text-align:left;'>";
                    $list.="<span class='span4'>$data->state</span><span class='span1'>$data->counter</span>";
                    $list.="</div>";
                } // end if $data->counter>0
            } // end foreach            
        } // end if count($state_users>0)
        $whole_div.="<table align='center' width='100%' border='0'>";
        $whole_div.="<tr>";
        $whole_div.="<td style='width:245px;'>$list</td><td align='left'><span class='span6' id='chart_div2' style='border:0px solid;text-align:center;width:600px;height:300px;vertical-align:top;' vertical-align='top' valign='top'></span></td>";
        $whole_div.="</tr>";
        $whole_div.="<tr>";
        $whole_div.="<td colspan='2'><a href='#' onClick='return false;' id='source_report_export'>Export to CSV</a></td>";
        $whole_div.="</tr>";
        $whole_div.="<tr>";
        $whole_div.="<td colspan='2'><div class='container-fluid' style='text-align:center;'><span class='span12'><br/><hr/></span></div></td>";
        $whole_div.="</tr>";        
        $whole_div.="</table>";
        return $whole_div;
    }

}
