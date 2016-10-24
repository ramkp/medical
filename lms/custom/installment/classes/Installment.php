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
        $this->create_courses_data();
    }

    function create_courses_data() {
        $query = "select * from mdl_course where visible=1 order by fullname";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $courses[] = mb_convert_encoding(trim($row['fullname']), 'UTF-8');
        }
        file_put_contents('/home/cnausa/public_html/lms/custom/utils/courses.json', json_encode($courses));
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

    function get_add_subscription_page() {
        $card_year = $this->get_year_drop_box();
        $card_month = $this->get_month_drop_box();
        $r = new Register();
        $states = $r->get_states_list();

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

        $list.="<div class='container-fluid' style='text-align:center;display:none;' id='ajax_loader'>";
        $list.="<span class='span4'><img src='https://medical2.com/assets/img/ajax.gif'></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>&nbsp;</span>";
        $list.="<span class='span2'><buton class='btn btn-primary' id='add_subs_button'>Submit</button></span>";
        $list.="</div>";



        return $list;
    }

    function create_subscription($subs) {

        echo "<pre>";
        print_r($subs);
        echo "</pre>";
    }

    function get_installment_page() {
        $list = "";
        $r = new Register();
        $form = $r->get_register_form();
        $subs = $this->get_add_subscription_page();

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
