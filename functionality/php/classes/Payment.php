<?php

/**
 * Description of Signup
 *
 * @author sirromas
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/Upload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/Invoice.php';

class Payment {

    public $db;
    public $enroll;
    public $invoice;
    public $user;

    function __construct() {
        $this->db = new pdo_db();
        $this->enroll = new Enroll();
        $this->invoice=new Invoice();
    }

    function get_payment_options($courseid, $group = null) {
        $query = "select installment, num_payments "
                . "from mdl_course "
                . "where id=$courseid";
        //echo "<br/>Query: $query<br/>";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $inst = $row['installment'];
            $num_payments = $row['num_payments'];
        }
        $data = array('id' => $courseid, 'inst' => $inst, 'num_payments' => $num_payments, 'group' => $group);
        $list = $this->create_payment_options_block($data);
        return $list;
    }

    function get_course_enrollment_period($courseid) {
        $day = 86400; // day secs num
        $query = "select enrol, courseid, enrolperiod, roleid "
                . "from mdl_enrol "
                . "where courseid=$courseid "
                . "and enrol='manual' "
                . "and roleid=5 "
                . "and enrolperiod>0";
        //echo "Query: " . $query . "<br>";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $period = round($row['enrolperiod'] / $day);
            }
        } // end if $num>0
        else {
            $period = 90; // default period 90 days
        }
        return $period;
    }

    function create_payment_options_block($data) {
        $list = "";
        $courseid = $data['id'];
        $installment = $data['inst'];
        $num_payments = $data['num_payments'];
        $group = $data['group'];
        $list.="<div class='panel panel-default' id='payment_options' style='margin-bottom:0px;'>";
        $list.="<div class='panel-heading' style='text-align:left;'><h5 class='panel-title'>Payment Options</h5></div>";
        $list.="<div class='panel-body'>";

        if ($group == null) {
            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span2'><input type='radio' name='payment_option' id='online_personal' value='online_personal' checked >Online payment</span>";
            $list.="<span class='span2'><input type='radio' name='payment_option' id='offline_personal' value='offline_personal'>Offline payment</span>";
            if ($installment == 1) {
                $enroll_period = $this->get_course_enrollment_period($courseid);
                $list.="<span class='span2'><input type='radio' name='payment_option' id='online_personal_installment' value='online_personal_installment'>Installment payments</span>";
            }
            $list.="</div>"; // end of container-fluid
        } // end if $group==null
        if ($group != null) {
            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span2'><input type='radio'  name='payment_option' id='online_whole_group_payment'   value='online_whole_group_payment' checked>Online payment</span>";
            $list.="<span class='span2'><input type='radio'  name='payment_option' id='offline_whole_group_payment'  value='offline_whole_group_payment'>Offline payment</span>";
            $list.="<span class='span2'><input type='radio'  name='payment_option' id='online_group_members_payment' value='online_group_members_payment'>Participants payments</span>";
            if ($installment == 1) {
                $enroll_period = $this->get_course_enrollment_period($courseid);
                $list.="<span class='span2'><input type='radio' name='payment_option' id='online_group_installment' value='online_group_installment'>Installment payments</span>";
            } // end if $installment == 1            
            $list.="</div>"; // end of container-fluid
        } // end if $group != null

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'><a href='#' id='proceed_to_payment' onClick='return false;'>Proceed to payment</a></span>";
        $list.="<span class='span2' id='option_err' style='color:red;'></span>";
        $list.="</div>"; // end of container-fluid
        $list.="</div>"; // end of panel-body
        $list.="</div>"; // end of panel panel-default
        return $list;
    }

    function enroll_user($user) {
        $this->enroll->single_signup($user);
        $userid = $this->enroll->getUserId($user->email);
        $user->id = $userid;
        $_SESSION["single_user_data"] = $user;
        $_SESSION['group_common_section'] = '';
        $_SESSION['users'] = $user;
        $_SESSION['tot_participants'] = 1;
        $this->user = $user;
        $list = $this->get_payment_options($user->courseid);
        return $list;
    }

    function get_course_name($courseid) {
        $query = "select id, fullname from mdl_course "
                . "where id=$courseid";
        //echo "<br/>Query : $query<br/>";
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
        $drop_down.="<li><a href='#' id='Visa' onClick='return false;'>Visa</a></li>";
        $drop_down.="<li><a href='#' id='Discover' onClick='return false;'>Discover</a></li>";
        $drop_down.="<li><a href='#' id='Master' onClick='return false;'>Master</a></li>";
        $drop_down.="<li><a href='#' id='American Express' onClick='return false;'>American Express</a></li>";
        $drop_down.="<li><a href='#' id='Diner Club' onClick='return false;'>Diner Club</a></li>";
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
            $user->come_from = $group_common_section->come_from;
            $email_exists = $this->enroll->is_email_exists($group_participant->email);
            if ($email_exists == 0) {
                $this->enroll->single_signup($user);
                $userid = $this->enroll->getUserId($group_participant->email);
                $this->enroll->add_user_to_group($groupid, $userid);
                $user->id = $userid;
            } // end if $email_exists==0
        } // end foreach
        //$list = $this->get_payment_section_personal($group_common_section, 1, $tot_participants);
        $_SESSION['group_common_section'] = $group_common_section;
        $_SESSION['users'] = $users;
        $_SESSION['tot_participants'] = $tot_participants;
        $list = $this->get_payment_options($group_common_section->courseid, 1);
        return $list;
    }

    function get_course_installment_data($courseid) {
        $query = "select installment, num_payments "
                . "from mdl_course "
                . "where id=$courseid";
        //echo "<br/>Query: $query<br/>";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $inst = $row['installment'];
            $num_payments = $row['num_payments'];
        }
        $period = $this->get_course_enrollment_period($courseid);
        $data = array('id' => $courseid, 'inst' => $inst, 'num_payments' => $num_payments, 'period' => $period);
        return $data;
    }

    function get_user_id_by_email($email) {
        $query = "select id, username from mdl_user where username='$email'";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $id = $row['id'];
        }
        return $id;
    }

    function get_payment_with_options($payment_option) {
        $list = "";
        $installment_data = array();
        $group_data = $_SESSION['group_common_section'];
        $users = $_SESSION['users'];
        $participants = $_SESSION['tot_participants'];
        if ($participants == 1) {
            // Single registration
            $users->id = $this->get_user_id_by_email($users->email);
            if ($payment_option == 'online_personal') {
                $list .= $this->get_payment_section($group_data, $users, $participants);
            }
            if ($payment_option == 'offline_personal') {                                
                $invoice_path=$this->invoice->get_personal_invoice($user);
                $user->invoice=$invoice_path;
                $mailer = new Mailer();
                $mailer->send_invoice($users);

                $list.="<div class='panel panel-default' id='payment_detailes'>";
                $list.="<div class='panel-heading'style='text-align:left;'><h5 class='panel-title'>Payment Detailes</h5></div>";
                $list.="<div class='panel-body'>";

                $list.="<div class='container-fluid' style='text-align:center;'>";
                $list.="<span class='span4'>Thank you! Invoice has been sent to $users->email.</span>";
                $list.="</div>";

                $list.="</div>";
                $list.="</div>";
            }
            if ($payment_option == 'online_personal_installment') {
                $installment = $this->get_course_installment_data($users->courseid);
                $list.= $this->get_payment_section($group_data, $users, $participants, $installment);
            }
        } // end if $participants==1 
        else {
            // Group registration
            if ($payment_option == 'online_whole_group_payment') {
                $list .= $this->get_payment_section($group_data, $users, $participants);
            }

            if ($payment_option == 'offline_whole_group_payment') {
                $list.=$this->get_group_offline_payment_section($group_data, $users, $participants);
            }

            if ($payment_option == 'online_group_members_payment') {
                $mailer = new Mailer();
                foreach ($users as $user) {
                    $user->id = $this->get_user_id_by_email($user->email);
                    $user->courseid = $group_data->courseid;
                    $mailer->send_invoice($user);
                } // end foreach
                $list.="<div class='panel panel-default' id='payment_detailes'>";
                $list.="<div class='panel-heading'style='text-align:left;'><h5 class='panel-title'>Payment Detailes</h5></div>";
                $list.="<div class='panel-body'>";

                $list.="<div class='container-fluid' style='text-align:center;'>";
                $list.="<span class='span4'>Thank you! Invoices are sent to Group participants.</span>";
                $list.="</div>";

                $list.="</div>";
                $list.="</div>";
            }

            if ($payment_option == 'online_group_installment') {
                $installment = $this->get_course_installment_data($group_data->courseid);
                $list.= $this->get_payment_section($group_data, $users, $participants, $installment);
            }
        } // end else
        return $list;
    }

    function get_group_offline_payment_section($group_data, $users, $participants) {
        $list = "";
        $list.="<div class='panel panel-default' id='payment_detailes'>";
        $list.="<div class='panel-heading'style='text-align:left;'><h5 class='panel-title'>Group Owner Detailes</h5></div>";
        $list.="<div class='panel-body'>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>First name</span>";
        $list.="<span class='span2'><input type='text' id='group_owner_firstname'></span>";
        $list.="<span class='span2'>Last name</span>";
        $list.="<span class='span2'><input type='hidden' id='course_id' value='$group_data->courseid'></span>";
        $list.="<span class='span2'><input type='text' id='group_owner_lastname'></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>Email</span>";
        $list.="<span class='span2'><input type='text' id='group_owner_email'></span>";
        $list.="<span class='span2' style='color:red;' id='offline_group_owner_error'></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'><button class='btn btn-primary' id='send_group_invoice'>Send invoice</button></span>";
        $list.="</div>";

        $list.="</div>";
        $list.="</div>";
        return $list;
    }

    function send_group_invoice($group_owner) {
        $list = "";
        $mailer = new Mailer();
        $mailer->send_invoice($group_owner, 1);

        /*
          $list.="<div class='panel panel-default' id='invoice_detaills'>";
          $list.="<div class='panel-heading'style='text-align:left;'><h5 class='panel-title'>Payment Detailes</h5></div>";
          $list.="<div class='panel-body'>";
          $list.="<div class='container-fluid' style='text-align:left;'>";
          $list.="<span class='span6'>Thank you! Invoice has been sent to $group_owner->email.</span>";
          $list.="</div>";
          $list.="</div>";
          $list.="</div>";
         */


        $list.="Thank you! Invoice has been sent to $group_owner->email.";

        return $list;
    }

    function get_payment_section($group_data, $users, $participants, $installment = null, $from_email = null) {

        /*
          print_r($group_data);
          echo "<br/>";
          print_r($users);
          echo "<br/>";
         * 
         */

        $list = "";
        $cost_block = "";
        $card_types = $this->get_card_types_dropbox();
        $card_year = $this->get_year_drop_box();
        $card_month = $this->get_month_drop_box();

        if ($from_email != null) {
            $list.="<br/><div  class='form_div'>";
        }
        $list.="<div class='panel panel-default' id='payment_detailes'>";
        $list.="<div class='panel-heading'style='text-align:left;'><h5 class='panel-title'>Payment Detailes</h5></div>";
        $list.="<div class='panel-body'>";

        if ($installment == null) {
            if ($group_data == '') {
                $course_name = $this->get_course_name($users->courseid);
                $course_cost = $this->get_personal_course_cost($users->courseid);
                $list.= "<input type='hidden' value='' id='user_group' name='user_group' />";
                $list.= "<input type='hidden' value='$users->id' id='userid' name='userid' />";
            } // end if $group==NULL 
            else {
                $course_name = $this->get_course_name($group_data->courseid);
                $course_cost = $this->get_course_group_discount($group_data->courseid, $participants);
                $list.= "<input type='hidden' value='$group_data->group_name' id='user_group' name='user_group' />";
                $list.= "<input type='hidden' value='$users->id' id='userid' name='userid' />";
            } // end else
            if ($course_cost['discount'] == 0) {
                $cost_block.="$" . $course_cost['cost'];
            } // end if $course_cost['discount']==0
            else {
                $cost_block.="$" . $course_cost['cost'] . "&nbsp; (discount is " . $course_cost['discount'] . "%)";
            }
            $list.="<div class='container-fluid' style='text-align:left;font-weight:bold;'>";
            $list.="<span class='span2'>Selected program</span>";
            $list.="<span class='span2'>$course_name</span>";
            $list.="<span class='span2'>Sum to be charged</span>";
            $list.="<span class='span2'>$cost_block</span>";
            $list.= "<input type='hidden' value='" . $course_cost['cost'] . "' id='payment_sum' />";
            $list.="</div>";
        } // end if $installment==null
        else {
            if ($group_data == '') {
                $course_name = $this->get_course_name($users->courseid);
                $course_cost = $this->get_personal_course_cost($users->courseid);
                $list.= "<input type='hidden' value='' id='user_group' name='user_group' />";
                $list.= "<input type='hidden' value='$users->id' id='userid' name='userid' />";
            } // end if $group==NULL 
            else {
                $course_name = $this->get_course_name($group_data->courseid);
                $course_cost = $this->get_course_group_discount($group_data->courseid, $participants);
                $list.= "<input type='hidden' value='$group_data->group_name' id='user_group' name='user_group' />";
                $list.= "<input type='hidden' value='$users->id' id='userid' name='userid' />";
            } // end else

            $first_payment = round(($course_cost['cost'] / $installment['num_payments']));
            $period = $installment['period'];
            $num_payments = $installment['num_payments'];

            $list.="<div class='container-fluid' style='text-align:left;font-weight:bold;'>";
            $list.="<span class='span2'>Selected program</span>";
            $list.="<span class='span2'>$course_name</span>";
            $list.="<span class='span2'>Sum to be charged</span>";
            $list.="<span class='span2'>$$first_payment &nbsp; (discount is " . $course_cost['discount'] . "%)</span>";
            $list.= "<input type='hidden' value='" . $first_payment . "' id='payment_sum' />";
            $list.="</div>";

            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span6'>Note: this is first payment of $num_payments payment(s) to be put within $period day(s).</span>";
            $list.="</div>";
        } // end else when installment is enabled

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

        if ($from_email != null) {
            $list.="</div>"; // form div
        }

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
            $user->come_from = $group_common_section->come_from;
            $email_exists = $this->enroll->is_email_exists($group_participant->email);
            if ($email_exists == 0) {
                $this->enroll->single_signup($user);
                $userid = $this->enroll->getUserId($group_participant->email);
                $this->enroll->add_user_to_group($groupid, $userid);
                $user->id = $userid;
            } // end if $email_exists==0
        } // end foreach
        //$list = $this->get_payment_section_personal($group_common_section, 1, $tot_participants);
        $_SESSION['group_common_section'] = $group_common_section;
        $_SESSION['users'] = $users;
        $_SESSION['tot_participants'] = $tot_participants;
        $list = $this->get_payment_options($group_common_section->courseid, 1);
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
        $userid = $card->userid;

        if ($user_group == '' && $userid != '') {
            $this->confirm_user($card->email);
            $mailer->send_payment_confirmation_message($card);
        } // end if $user_group==''
        if ($user_group != '' && $userid != '') {
            $this->confirm_user($card->email);
            $mailer->send_payment_confirmation_message($card);
        } // end if $user_group!='' && $userid!=''
        if ($user_group != '' && $userid == '') {
            $group_users = $this->get_group_users($user_group);
            $mailer->send_payment_confirmation_message($card, 1);
            foreach ($group_users as $userid) {
                $user = $this->get_user_detailes($userid);
                $this->confirm_user($user->username);
                $mailer->send_group_payment_confirmation_message($user);
            } // end foreach
        } // end if $user_group!='' && $userid==''
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
