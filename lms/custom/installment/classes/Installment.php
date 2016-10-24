<?php

/**
 * Description of Installment
 *
 * @author sirromas
 */
require_once ('/home/cnausa/public_html/lms/custom/utils/classes/Util.php');
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/register/classes/Register.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/authorize/Classes/ProcessPayment.php';

class Installment extends Util {

    public $limit = 3;
    public $period = 28; // installment period in days

    function __construct() {
        parent::__construct();
        $this->create_courses_data();
        $this->create_installment_users_data();
    }

    function create_courses_data() {
        $query = "select * from mdl_course where visible=1 order by fullname";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $courses[] = mb_convert_encoding(trim($row['fullname']), 'UTF-8');
        }
        file_put_contents('/home/cnausa/public_html/lms/custom/utils/courses.json', json_encode($courses));
    }

    function create_installment_users_data() {
        $query = "select * from mdl_installment_users ";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $user = $this->get_user_details($row['userid']);
            $firstname = mb_convert_encoding(trim($user->firstname), 'UTF-8');
            $lastname = mb_convert_encoding(trim($user->lastname), 'UTF-8');
            $users[] = $lastname . " " . $firstname;
        }
        file_put_contents('/home/cnausa/public_html/lms/custom/utils/installment_users.json', json_encode($users));
    }

    function get_year_drop_box() {
        $drop_down = "";
        $drop_down.= "<select id='card_year' style='width: 75px;'>";
        $drop_down.="<option value='--' selected>Year</option>";
        $drop_down.="<option value='2016'>2016</option>";
        $drop_down.="<option value='2017'>2017</option>";
        $drop_down.="<option value='2018'>2018</option>";
        $drop_down.="<option value='2019'>2019</option>";
        $drop_down.="<option value='2020'>2020</option>";
        $drop_down.="<option value='2021'>2021</option>";
        $drop_down.="<option value='2022'>2022</option>";
        $drop_down.="<option value='2023'>2023</option>";
        $drop_down.="<option value='2024'>2024</option>";
        $drop_down.="<option value='2025'>2025</option>";
        $drop_down.="<option value='2026'>2026</option>";
        $drop_down.="<option value='2027'>2027</option>";
        $drop_down.="<option value='2028'>2028</option>";
        $drop_down.="<option value='2029'>2029</option>";
        $drop_down.="<option value='2030'>2030</option>";
        $drop_down.="</select>";
        return $drop_down;
    }

    function get_month_drop_box() {
        $drop_down = "";
        $items = "<option value='01'>01</option>
                <option value='--' selected>Month</option>
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

    function get_payments_num() {
        $list = "";
        $list.="<select id='payments_num'>";
        $list.="<option value='2' selected>2</option>";
        for ($i = 3; $i <= 10; $i++) {
            $list.="<option value='$i'>$i</option>";
        }
        $list.="</select>";
        return $list;
    }

    function get_subs_num() {
        $query = "select count(id) as total from mdl_installment_users";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $total = $row['total'];
        }
        return $total;
    }

    function get_add_subscription_page() {
        $card_year = $this->get_year_drop_box();
        $card_month = $this->get_month_drop_box();
        $r = new Register();
        //$states = $r->get_states_list();
        $payment_num = $this->get_payments_num();

        $list = "";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>Program*</span>";
        $list.="<span class='span2'><input type='text' id='installment_program' class='typeahead'></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>User*</span>";
        $list.="<span class='span2'><input type='text' id='installment_user' class='typeahead'></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>Full fee*</span>";
        $list.="<span class='span2'><input type='text' id='amount' ></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>Installment period*</span>";
        $list.="<span class='span1'><input type='text' id='subs_start' style='width:65px;' placeholder='Start'></span>";
        $list.="<span class='span2'><input type='text' id='subs_exp' style='width:65px;' placeholder='Expiration'></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>Payments q-ty*</span>";
        $list.="<span class='span2'>$payment_num</span>";
        $list.="</div>";

        // Below items are not necessary consider payment gateway configuration
        /*
         * 
          $list.="<div class='container-fluid' style='text-align:left;'>";
          $list.="<span class='span2'>Address*</span>";
          $list.="<span class='span2'><input type='text' id='subs_addr' name='subs_addr'  ></span>";
          $list.="</div>";

          $list.="<div class='container-fluid' style='text-align:left;'>";
          $list.="<span class='span2'>City*</span>";
          $list.="<span class='span2'><input type='text' id='subs_city' name='subs_city'  ></span>";
          $list.="</div>";

          $list.="<div class='container-fluid' style='text-align:left;'>";
          $list.="<span class='span2'>State*</span>";
          $list.="<span class='span2'>$states</span>";
          $list.="</div>";

          $list.="<div class='container-fluid' style='text-align:left;'>";
          $list.="<span class='span2'>ZIP*</span>";
          $list.="<span class='span2'><input type='text' id='subs_zip' name='subs_zip'  ></span>";
          $list.="</div>";

          $list.="<div class='container-fluid' style='text-align:left;'>";
          $list.="<span class='span2'>Email*</span>";
          $list.="<span class='span2'><input type='text' id='subs_email' name='subs_email'  ></span>";
          $list.="</div>";

          $list.="<div class='container-fluid' style='text-align:left;'>";
          $list.="<span class='span2'>Phone*</span>";
          $list.="<span class='span2'><input type='text' id='subs_phone' name='subs_phone'  ></span>";
          $list.="</div>";

         * 
         */


        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>Card Holder Name*</span>";
        $list.="<span class='span2'><input type='text' id='card_holder' name='card_holder'  ></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>Card number*</span>";
        $list.="<span class='span2'><input type='text' id='card_no' name='card_no'  ></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>Card CVV Code*</span>";
        $list.="<span class='span2'><input type='text' id='cvv' name='cvv'  ></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>Expiration Date*</span>";
        $list.="<span class='span3'>" . $card_year . "&nbsp;&nbsp;&nbsp;" . $card_month . "</span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:center;'>";
        $list.="<span class='span4' id='subs_err' style='color:red;'></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:center;display:none;' id='ajax_loader2'>";
        $list.="<span class='span4'><img src='https://medical2.com/assets/img/ajax.gif'></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>&nbsp;</span>";
        $list.="<span class='span2'><buton class='btn btn-primary' id='add_subs_button'>Submit</button></span>";
        $list.="</div>";

        return $list;
    }

    function get_course_id($coursename) {
        $query = "select * from mdl_course where fullname like '%$coursename%'";
        //echo "Query: " . $query . "<br>";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $id = $row['id'];
        }
        return $id;
    }

    function create_subscription($subs) {
        $list = "";

        // Verify submitted data
        //1. Subscription start could not be earlier submissiom date
        //2. Interval length must be 7 to 365 for day based subscriptions
        // Above verifications delegated to Payment gateway 

        $pr = new ProcessPayment();
        $subs_id = $pr->createSubscription($subs);
        if ($subs_id != FALSE) {
            $courseid = $this->get_course_id($subs->coursename);
            $userid = $this->get_userid_by_fio($subs->user);
            $card_last_four = substr($subs->card_no, -4);
            $subs->subsid = $subs_id;
            $subs->courseid = $courseid;
            $subs->userid = $userid;
            $subs->last_four = $card_last_four;
            $list.=$this->add_installment_user($subs);
        } // end if
        else {
            $list.="Credit card declined";
        }

        return $list;
    }

    function get_installment_users() {
        $list = "";
        $users = array();
        $query = "select * from mdl_installment_users "
                . "order by created desc limit 0, $this->limit";
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
            $list.=$this->create_installment_users_list($users);
        } // end if $num>0
        else {
            $list.="<div class='container-fluid' style='text-align:center;'>";
            $list.="<span class='span8' >N/A</span>";
            $list.="</div>";
        }
        return $list;
    }

    function create_installment_users_list($users, $headers = true) {

        $list = "";
        if ($headers) {
            $list.="<div class='panel panel-default' id='program_section' style='margin-bottom:0px;'>";
            $list.="<div class='panel-heading' style='text-align:left;'><h5 class='panel-title'>Installment users</h5></div>";
            $list.="<div class='panel-body'>";

            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span4'><input type='text' id='installment_search' class='typeahead'></span>";
            $list.="<span class='span2'><button class='btn btn-primary' id='installment_search_button'>Search</button></span>";
            $list.="<span class='span2'><button class='btn btn-primary' id='installment_clear_button'>Clear</button></span>";
            $list.="</div>";

            $list.="<div class='container-fluid' id='ajax_loader' style='text-align:center;display:none;'>";
            $list.="<span class='span6'><img src='https://medical2.com/assets/img/ajax.gif'></span>";
            $list.="</div>";

            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span6' id='installment_err' style='color:red;'></span>";
            $list.="</div>";
        }

        $list.="<div id='installment_container'>";
        if (count($users) > 0) {
            foreach ($users as $userObject) {
                $user = $this->get_user_details($userObject->userid);
                $coursename = $this->get_course_name($userObject->courseid);
                $start = date('m-d-Y', $userObject->subscription_start);
                $end = date('m-d-Y', $userObject->subscription_end);
                $one_time_payment = round($userObject->full_amount / $userObject->payments_num);


                $list.="<div class='container-fluid' style='text-align:left;'>";
                $list.="<span class='span4'>User</span>";
                $list.="<span class='span4'>$user->lastname $user->firstname</span>";
                $list.="</div>";

                $list.="<div class='container-fluid' style='text-align:left;'>";
                $list.="<span class='span4'>Program applied</span>";
                $list.="<span class='span6'>$coursename</span>";
                $list.="</div>";

                $list.="<div class='container-fluid' style='text-align:left;'>";
                $list.="<span class='span4'>Full amount</span>";
                $list.="<span class='span4'>$$userObject->full_amount</span>";
                $list.="</div>";

                $list.="<div class='container-fluid' style='text-align:left;'>";
                $list.="<span class='span4'>Subscription start</span>";
                $list.="<span class='span4'>$start</span>";
                $list.="</div>";

                $list.="<div class='container-fluid' style='text-align:left;'>";
                $list.="<span class='span4'>Subscription end</span>";
                $list.="<span class='span4'>$end</span>";
                $list.="</div>";

                $list.="<div class='container-fluid' style='text-align:left;'>";
                $list.="<span class='span4'>Payments q-ty</span>";
                $list.="<span class='span4'>$userObject->payments_num</span>";
                $list.="</div>";

                $list.="<div class='container-fluid' style='text-align:left;'>";
                $list.="<span class='span4'>One time payment</span>";
                $list.="<span class='span4'>$$one_time_payment</span>";
                $list.="</div>";

                $list.="<div class='container-fluid' style='text-align:left;'>";
                $list.="<span class='span10'><hr/></span>";
                $list.="</div>";
            } // end foreach
        } // end if count($users)>0
        else {
            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span8' >N/A</span>";
            $list.="</div>";
        } // end else

        $list.="</div>"; // container div

        if ($headers) {
            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span10' id='pagination'></span>";
            $list.="</div>";
        }

        $list.="</div>"; // end of panel-body
        $list.="</div>"; // end of panel panel-default

        return $list;
    }

    function get_installment_page() {
        $list = "";
        $r = new Register();
        $form = $r->get_register_form();
        $subs = $this->get_add_subscription_page();
        $installment_users = $this->get_installment_users();
        $list.="<ul class='nav nav-tabs'>
          <li class='active'><a data-toggle='tab' href='#home'><h5>Installment users</h5></a></li>
          <li><a data-toggle='tab' href='#menu1'><h5>Register user</h5></a></li>
          <li><a data-toggle='tab' href='#menu2'><h5>Add subscription</h5></a></li>
        </ul>

        <div class='tab-content'>
          
         <div id='home' class='tab-pane fade in active'>
            <p>$installment_users</p>
          </div>
        
          <div id='menu1' class='tab-pane fade'>
            <p>$form</p>
          </div>
        
          <div id='menu2' class='tab-pane fade'>
            <p>$subs</p>
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
        $query = "select * from mdl_installment_users "
                . "order by created desc  LIMIT $offset, $rec_limit";
        //echo "Query: ".$query."<br>";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $user = new stdClass();
            foreach ($row as $key => $value) {
                $user->$key = $value;
            } // end foreach      
            $installment_users[] = $user;
        } // end while
        $list = $this->create_installment_users_list($installment_users, false);
        return $list;
    }

    function search_subs($item) {
        $list = "";
        $userid = $this->get_userid_by_fio($item);
        $query = "select * from mdl_installment_users where userid=$userid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $user = new stdClass();
                foreach ($row as $key => $value) {
                    $user->$key = $value;
                } // end foreach      
                $installment_users[] = $user;
            } // end while
            $list = $this->create_installment_users_list($installment_users, false);
        } // end if $num > 0
        else {
            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span8' >N/A</span>";
            $list.="</div>";
        }
        return $list;
    }

    function add_installment_user($subs) {

        //echo "<pre>";
        //print_r($subs);
        //echo "</pre>";

        $list = "";
        $modifierid = $this->user->id;
        $created = time();
        $query = "insert into mdl_installment_users "
                . "(courseid,"
                . "userid,"
                . "full_amount,"
                . "payments_num, "
                . "last_four, "
                . "subscription_id, "
                . "subscription_start, "
                . "subscription_end, "
                . "modifierid,"
                . "created) "
                . "values ('" . $subs->courseid . "',"
                . "'" . $subs->userid . "', "
                . "'" . $subs->amount . "', "
                . "'" . $subs->payments_num . "', "
                . "'$subs->last_four', "
                . "'$subs->subsid', "
                . "'" . strtotime($subs->start) . "',"
                . "'" . strtotime($subs->end) . "', "
                . "'" . $modifierid . "', "
                . "'" . $created . "')";
        //echo "Query: " . $query . "<br>";

        $this->db->query($query);
        $list .= "Subscription successfully created";
        return $list;
    }

}
