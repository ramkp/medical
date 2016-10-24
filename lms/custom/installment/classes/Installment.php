<?php

/**
 * Description of Installment
 *
 * @author sirromas
 */
require_once ('/home/cnausa/public_html/lms/custom/utils/classes/Util.php');
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/register/classes/Register.php';

class Installment extends Util {

    public $limit = 3;
    public $period = 28; // installment period in days

    function __construct() {
        parent::__construct();
    }

    function get_installment_page() {
        $list = "";
        $r = new Register();
        $form = $r->get_register_form();

        $list.="<ul class='nav nav-tabs'>
          <li class='active'><a data-toggle='tab' href='#home'><h5>Installment users</h5></a></li>
          <li><a data-toggle='tab' href='#menu1'><h5>Register user</h5></a></li>
          <li><a data-toggle='tab' href='#menu2'><h5>Add subscription</h5></a></li>
        </ul>

        <div class='tab-content'>
          
         <div id='home' class='tab-pane fade in active'>
            <p>Installment users</p>
          </div>
        
          <div id='menu1' class='tab-pane fade'>
            <p>$form</p>
          </div>
        
          <div id='menu2' class='tab-pane fade'>
            <p>Add subscription</p>
          </div>

        </div>";

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

}
