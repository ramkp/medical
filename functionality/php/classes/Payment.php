<?php

/**
 * Description of Signup
 *
 * @author sirromas
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/Late.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/Upload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/Invoice.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/authorize/Classes/ProcessPayment.php';

class Payment {

    public $db;
    public $enroll;
    public $invoice;
    public $user;
    public $host;

    function __construct() {
        $this->db = new pdo_db();
        $this->enroll = new Enroll();
        $this->invoice = new Invoice();
        $this->host = $_SERVER['SERVER_NAME'];
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
            //$list.="<span class='span2'><input type='radio' name='payment_option' id='offline_personal' value='offline_personal'>Offline payment</span>";
            if ($installment == 1) {
                $enroll_period = $this->get_course_enrollment_period($courseid);
                $list.="<span class='span2'><input type='radio' name='payment_option' id='online_personal_installment' value='online_personal_installment'>Installment payments</span>";
            }
            $list.="</div>"; // end of container-fluid
        } // end if $group==null
        if ($group != null) {
            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span2'><input type='radio'  name='payment_option' id='online_whole_group_payment'   value='online_whole_group_payment' checked>Online payment</span>";
            //$list.="<span class='span2'><input type='radio'  name='payment_option' id='offline_whole_group_payment'  value='offline_whole_group_payment'>Offline payment</span>";
            //$list.="<span class='span2'><input type='radio'  name='payment_option' id='online_group_members_payment' value='online_group_members_payment'>Participants payments</span>";
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
        //$list = $this->get_payment_options($user->courseid);
        $list = $this->get_payment_with_options('online_personal');

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
        $drop_down.="<select id='card_type'>";
        $drop_down.="<option value='American Express'>American Express</option>";
        $drop_down.="<option value='Discover' >Discover</option>";
        $drop_down.="<option value='Master' >Master</option>";
        $drop_down.="<option value='Visa' >Visa</option>";
        $drop_down.="</select>";
        return $drop_down;
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
        $list.="<div class='panel-heading'style='text-align:left;'><h5 class='panel-title'>Payment Details</h5></div>";
        $list.="<div class='panel-body'>";

        $list.="<div class='container-fluid' style='text-align:left;font-weight:bold;'>";
        $list.="<span class='span2'>Selected program</span>";
        $list.="<span class='span2'>$course_name</span>";
        $list.="<span class='span2'>Fee</span>";
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

        $list.="<div class='container-fluid' style='text-align:left;'>";

        $list.="</div>";

        $list.= "<div class='container-fluid' style='text-align:left;'>";
        $list.= "<span class='span2'><button class='btn btn-primary' id='make_payment_personal'>Submit</button></span>";
        $list.= "&nbsp <span style='color:red;' id='personal_payment_err'></span>";
        $list.= "</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span8' style='text-align:center;display:none;' id='ajax_loading_payment'><img src='https://$this->host/assets/img/ajax.gif' /></span>";
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
        //echo "<br>----------------<br>";
        //print_r($users);
        //echo "<br>----------------<br>";

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
            $statename = $this->get_state_name_by_id($group_common_section->state);
            $user->state = $statename;
            $user->country = 'US';
            $user->slotid = $group_common_section->slotid;
            $user->come_from = $group_common_section->come_from;
            $email_exists = $this->enroll->is_email_exists($group_participant->email);
            if ($email_exists == 0) {
                $this->enroll->single_signup($user);
                $userid = $this->enroll->getUserId($group_participant->email);
                $this->enroll->add_user_to_group($groupid, $userid);
                $user->id = $userid;
            } // end if $email_exists==0
        } // end foreach
        $group_common_section->statename = $statename;
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

    function is_course_taxable($courseid) {
        $query = "select taxes from mdl_course where id=$courseid";
        //echo "Query: ".$query."<br>";                
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $status = $row['taxes'];
        }
        return $status;
    }

    function get_user_state($userid) {
        $query = "select state from mdl_user where id=$userid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $state = $row['state'];
        }
        return $state;
    }

    function get_state_taxes($state) {
        $query = "select tax from mdl_state_taxes where state='$state'";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $tax = $row['tax'];
        }
        return $tax;
    }

    function get_cost_for_group_participants($courseid) {
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

        $final_cost = $cost - round(($cost * $discount) / 100, 2);
        $cost = array('cost' => $final_cost);
        return $cost;
    }

    function add_invoice_to_db($user, $group = false) {

        /*
         *  
          echo "<pre>----------<br>";
          print_r($user);
          echo "<pre>----------<br>";
         * 
         */

        $path = $this->invoice->invoice_path . "/$user->invoice.pdf";
        $user_installment_status = $this->invoice->is_installment_user($user->id, $user->courseid);
        //echo "Installment status: ".$user_installment_status."<br>";
        if ($group == false) {
            $cost = $this->invoice->get_personal_course_cost($user->courseid);
        } else {
            $cost = $this->get_cost_for_group_participants($user->courseid);
        }
        if ($user_installment_status == 0) {
            $installment = new stdClass();
            $sum = $cost['cost'];
            $installment->sum = $sum;
        } // end if $user_installment_status==0
        else {
            $installment = $this->invoice->get_user_installment_payments($user->id, $user->courseid);
        } // end else
        $tax_status = $this->is_course_taxable($user->courseid);
        if ($tax_status == 1) {
            $user_state = $this->get_user_state($user->id);
            $tax = $this->get_state_taxes($user_state);
        } // end if $tax_status == 1
        else {
            $tax = 0;
        } // end else
        if ($tax > 0) {
            $tax_sum = round(($installment->sum * $tax) / 100, 2);
            $installment->sum = round(($installment->sum + $tax_sum), 2);
        }
        $query = "insert into mdl_invoice"
                . "(i_num,"
                . "userid,"
                . "courseid,"
                . "i_sum,"
                . "i_status,"
                . "i_file,"
                . "i_date) "
                . "values ('" . $user->invoice . "',"
                . "'" . $user->id . "', "
                . "'" . $user->courseid . "',"
                . "'" . $installment->sum . "',"
                . "'0',"
                . "'" . $path . "',"
                . "'" . time() . "')";
        //echo "<br>Query: " . $query . "<br>";
        $this->db->query($query);
    }

    function get_payment_with_options($payment_option) {
        $list = "";
        $installment_data = array();
        $group_data = $_SESSION['group_common_section'];

        /*
         *  
          echo "<pre>----------Group data<br>";
          print_r($group_data);
          echo "<pre>--------------------<br>";
         * 
         */

        $users = $_SESSION['users'];
        //print_r($users);
        //echo "<br>";
        $participants = $_SESSION['tot_participants'];
        if ($participants == 1) {
            // Single registration
            $users->id = $this->get_user_id_by_email($users->email);
            if ($payment_option == 'online_personal') {
                $list .= $this->get_payment_section($group_data, $users, $participants);
            }
            if ($payment_option == 'offline_personal') {
                $invoice_path = $this->invoice->get_personal_invoice($users);
                $users->invoice = $invoice_path;
                $this->add_invoice_to_db($users);
                $mailer = new Mailer();
                $mailer->send_invoice($users);

                $list.="<div class='panel panel-default' id='payment_detailes'>";
                $list.="<div class='panel-heading'style='text-align:left;'><h5 class='panel-title'>Payment Details</h5></div>";
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

            // This case is not available for now
            if ($payment_option == 'offline_whole_group_payment') {
                $list.=$this->get_group_offline_payment_section($group_data, $users, $participants);
            }

            if ($payment_option == 'online_group_members_payment') {
                $mailer = new Mailer();
                //print_r($users);
                foreach ($users as $user) {
                    $user->id = $this->get_user_id_by_email($user->email);
                    $user->courseid = $group_data->courseid;
                    $user->come_from = $group_data->come_from;
                    $user->invoice = $this->invoice->get_personal_invoice($user, 1, 1);
                    $this->add_invoice_to_db($user, true);
                    $mailer->send_invoice($user);
                } // end foreach
                $list.="<div class='panel panel-default' id='payment_detailes'>";
                $list.="<div class='panel-heading'style='text-align:left;'><h5 class='panel-title'>Payment Details</h5></div>";
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
        $list.="<div class='panel-heading'style='text-align:left;'><h5 class='panel-title'>Group Owner Details</h5></div>";
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
        $participants = $_SESSION['tot_participants'];
        $invoice_path = $this->invoice->get_personal_invoice($group_owner, 1, $participants);
        $group_owner->invoice = $invoice_path;
        $mailer = new Mailer();
        $mailer->send_invoice($group_owner, 1);
        $list.="Thank you! Invoice has been sent to $group_owner->email.";
        return $list;
    }

    function get_states_list() {
        $drop_down = "";
        $drop_down.="<select id='bill_state' style='width:140px;' name='bill_state'>";
        $drop_down.="<option value='0' selected>State</option>";
        $query = "select * from mdl_states";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $drop_down.="<option value='" . $row['id'] . "'>" . $row['state'] . "</option>";
        } // end while
        $drop_down.="</select>";
        return $drop_down;
    }

    function get_renew_fee() {
        $query = "select * from mdl_renew_fee";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $fee = $row['fee_sum'];
        } // end while
        return $fee;
    }

    function get_payment_section($group_data, $users, $participants, $installment = null, $from_email = null, $sum = false) {

        /*
          echo "<br/>Users-------------<br/>";
          echo "<pre>";
          print_r($users);
          echo "<pre>";
          echo "<br/>-------------<br/>";
          echo "Group data ----------------<pre>";
          print_r($group_data);
          echo "<pre>";
          echo "<br/>-------------<br/>";
         */

        //echo "Sum inside payment section: ".$sum."<br>";

        $list = "";
        $cost_block = "";
        $card_types = $this->get_card_types_dropbox();
        $card_year = $this->get_year_drop_box();
        $card_month = $this->get_month_drop_box();
        $states = $this->get_states_list();
        $late = new Late();

        $dashboard = ($from_email == null) ? 0 : 1;
        $list.="<input type='hidden' id='dashboard' value='$dashboard'>";
        if ($from_email != null) {
            $list.="<br/><div  class='form_div'>";
        }
        $list.="<div class='panel panel-default' id='payment_detailes'>";
        $list.="<div class='panel-heading'style='text-align:left;'><h5 class='panel-title'>Payment Details</h5></div>";
        $list.="<div class='panel-body'>";

        $list.="<div class='container-fluid' style='text-align:center;'>";
        if ($from_email == null) {
            $list.="<span class='span8'><input type='checkbox' id='same_address'>Click here if billing Address is same as Contact</span>";
        }
        $list.="</div>";

        if ($installment == null) {
            if ($group_data == '') {
                $course_name = $this->get_course_name($users->courseid);
                if ($sum == false) {
                    $course_cost = $this->get_personal_course_cost($users->courseid);
                } // end if $renew == false
                else {
                    $course_cost = array('cost' => $sum, 'discount' => 0);
                }
                $list.= "<input type='hidden' value='' id='user_group' name='user_group' />";
                $list.= "<input type='hidden' value='$users->id' id='userid' name='userid' />";
                $list.= "<input type='hidden' value='$users->courseid' id='courseid' name='courseid' />";
                $tax_status = $this->is_course_taxable($users->courseid);
                //echo "Tax status: ".$tax_status."<br>";
                if ($tax_status == 1) {
                    $tax = $this->get_state_taxes($users->state);
                } // end if $tax_status == 1
                else {
                    $tax = 0;
                } // end else
                if ($users->slotid != '' && $sum == false) {
                    $apply_delay_fee = $late->is_apply_delay_fee($users->courseid, $users->slotid);
                    $late_fee = $late->get_delay_fee($users->courseid);
                }
            } // end if $group==''
            else {
                $course_name = $this->get_course_name($group_data->courseid);
                if ($sum == false) {
                    $course_cost = $this->get_course_group_discount($group_data->courseid, $participants);
                } // end if $renew == false 
                else {
                    $course_cost = array('cost' => $sum, 'discount' => 0);
                } // end else

                $list.= "<input type='hidden' value='$group_data->group_name' id='user_group' name='user_group' />";
                $list.= "<input type='hidden' value='$users->id' id='userid' name='userid' />";
                $list.= "<input type='hidden' value='$group_data->courseid' id='courseid' name='courseid' />";
                $tax_status = $this->is_course_taxable($group_data->courseid);
                //echo "Tax status: ".$tax_status."<br>";
                if ($tax_status == 1) {
                    $tax = $this->get_state_taxes($group_data->statename);
                } // end if $tax_status == 1
                else {
                    $tax = 0;
                } // end else
                if ($group_data->slotid != '' && $sum == false) {
                    $apply_delay_fee = $late->is_apply_delay_fee($group_data->courseid, $group_data->slotid);
                    $late_fee = $late->get_delay_fee($group_data->courseid);
                }
            } // end else when group_data are not null
            // Discount block
            if ($course_cost['discount'] == 0) {
                $cost_block.="$" . $course_cost['cost'];
            } // end if $course_cost['discount']==0
            else {
                $cost_block.="$" . $course_cost['cost'] . "&nbsp; (discount is " . $course_cost['discount'] . "%)";
            }

            if ($apply_delay_fee) {
                if ($group_data == '') {
                    $grand_total = $course_cost['cost'] + $late_fee;
                } // end if $group_data == ''
                else {
                    $grand_total = $course_cost['cost'] + $late_fee * $participants;
                } // end else 
            } // end if $apply_delay_fee
            else {
                $grand_total = $course_cost['cost'];
            } // end else when no delay fee applied ...
            //echo "Tax: " . $tax . "<br>";
            //echo "Group data: ";
            //print_r($group_data);
            //echo "<br>";
            //echo "Participants: ".$participants."<br>";

            if ($tax == 0) {
                $list.="<div class='container-fluid' style='text-align:left;font-weight:bold;'>";
                $list.="<span class='span2'>Selected program</span>";
                //$list.="<span class='span2' style='white-space: nowrap;overflow:hidden;'>$course_name</span>";
                $list.="<span class='span2' style=''>$course_name</span>";
                $list.="<span class='span2'>Fee</span>";
                if ($apply_delay_fee) {
                    if ($group_data == '') {
                        $list.="<span class='span2'>$cost_block+$$late_fee (late fee)<br>Total: $grand_total</span>";
                    } // end if $group_data == ''
                    else {
                        $list.="<span class='span2'>$cost_block+$" . $late_fee * $participants . " (late fee)<br>Total: $grand_total</span>";
                    } // end else                    
                } // end if $apply_delay_fee
                else {
                    $list.="<span class='span2'>$cost_block</span>";
                } // end else                 
                $list.= "<input type='hidden' value='" . $grand_total . "' id='payment_sum' />";
                $list.="</div>";
            } // end if $tax==0
            else {
                $tax_sum = round(($course_cost['cost'] * $tax) / 100, 2);
                $grand_total = round(($course_cost['cost'] + $tax_sum), 2);

                if ($apply_delay_fee) {
                    if ($group_data == '') {
                        $grand_total2 = $grand_total + $late_fee;
                    } // end if $group_data == ''
                    else {
                        $grand_total2 = $grand_total + $late_fee * $participants;
                    } // end else
                } // end if $apply_delay_fee
                else {
                    $grand_total2 = $grand_total;
                } // end else when no delay fee is applied
                //$grand_total2 = ($apply_delay_fee == true) ? $grand_total + $late_fee : $grand_total;

                $list.="<div class='container-fluid' style='text-align:left;font-weight:bold;'>";
                $list.="<span class='span2'>Selected program</span>";
                $list.="<span class='span2'>$course_name</span>";
                $list.="<span class='span2'>Subtotal</span>";
                if ($apply_delay_fee) {
                    if ($group_data == '') {
                        $list.="<span class='span2'>$cost_block+$$late_fee (late fee)</span>";
                    } // end if $group_data == ''
                    else {
                        $list.="<span class='span2'>$cost_block+$" . $late_fee * $participants . " (late fee)</span>";
                    } // end else                    
                } // end if $apply_delay_fee
                else {
                    $list.="<span class='span2'>$cost_block</span>";
                }
                $list.="</div>";

                $list.="<div class='container-fluid' style='text-align:left;font-weight:bold;'>";
                $list.="<span class='span2'></span>";
                $list.="<span class='span2'></span>";
                $list.="<span class='span2'>Tax</span>";
                $list.="<span class='span2'>$$tax_sum</span>";
                $list.="</div>";

                $list.="<div class='container-fluid' style='text-align:left;font-weight:bold;'>";
                $list.="<span class='span2'></span>";
                $list.="<span class='span2'></span>";
                $list.="<span class='span2'>Total</span>";
                $list.="<span class='span2'>$$grand_total2</span>";
                $list.= "<input type='hidden' value='" . $grand_total2 . "' id='payment_sum' />";
                $list.="</div>";
            } // end else when tax is not null
        } // end if $installment==null
        else {
            $users->slotid = 0; // There is no delay fee for installment users            
            if ($group_data == '') {
                $course_name = $this->get_course_name($users->courseid);
                if ($sum == false) {
                    $course_cost = $this->get_personal_course_cost($users->courseid);
                } // end if $renew == false
                else {
                    $course_cost = array('cost' => $sum, 'discount' => 0);
                }
                $list.= "<input type='hidden' value='' id='user_group' name='user_group' />";
                $list.= "<input type='hidden' value='$users->id' id='userid' name='userid' />";
                $list.= "<input type='hidden' value='$users->courseid' id='courseid' name='courseid' />";
                if ($users->slotid != '' && $renew == false) {
                    $apply_delay_fee = $late->is_apply_delay_fee($users->courseid, $users->slotid);
                    $late_fee = $late->get_delay_fee($group_data->courseid);
                }
            } // end if $group==NULL 
            else {
                $group_data->slotid = 0; // There is no delay fee for installment users            
                $course_name = $this->get_course_name($group_data->courseid);
                if ($sum == false) {
                    $course_cost = $this->get_course_group_discount($group_data->courseid, $participants);
                } // end if
                else {
                    $course_cost = array('cost' => $sum, 'discount' => 0);
                }
                $list.= "<input type='hidden' value='$group_data->group_name' id='user_group' name='user_group' />";
                $list.= "<input type='hidden' value='$users->id' id='userid' name='userid' />";
                $list.= "<input type='hidden' value='$group_data->courseid' id='courseid' name='courseid' />";
                if ($group_data->slotid != '' && $sum == false) {
                    $apply_delay_fee = $late->is_apply_delay_fee($users->courseid, $group_data->slotid);
                    $apply_delay_fee = $late->is_apply_delay_fee($group_data->courseid, $group_data->slotid);
                }
            } // end else when group is not null

            $first_payment = round(($course_cost['cost'] / $installment['num_payments']));
            $period = $installment['period'];
            $num_payments = $installment['num_payments'];
            $grand_total = ($apply_delay_fee == true) ? $first_payment + $late_fee : $first_payment;
            $list.="<div class='container-fluid' style='text-align:left;font-weight:bold;'>";
            $list.="<span class='span2'>Selected program</span>";
            $list.="<span class='span2'>$course_name</span>";
            $list.="<span class='span2'>Fee</span>";
            if ($apply_delay_fee) {
                $list.="<span class='span2'>$$first_payment &nbsp; (discount is " . $course_cost['discount'] . "%)+ $$late_fee (late fee)</span>";
            } // end if $apply_delay_fee
            else {
                $list.="<span class='span2'>$$first_payment &nbsp; (discount is " . $course_cost['discount'] . "%)</span>";
            } // end else 
            $list.= "<input type='hidden' value='" . $grand_total . "' id='payment_sum' />";
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
        $list.="<span class='span2'>CVV*</span>";
        $list.="<span class='span2'><input type='text' id='bill_cvv' name='bill_cvv'  ></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>Address*</span>";
        $list.="<span class='span2'><input type='text' id='bill_addr' name='bill_addr'  ></span>";
        $list.="<span class='span2'>Expiration Date*</span>";
        $list.="<span class='span2'>" . $card_month . "&nbsp;&nbsp;&nbsp;" . $card_year . "</span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>State*</span>";
        $list.="<span class='span2'>$states</span>";
        $list.="<span class='span2'>City*</span>";
        $list.="<span class='span2'><input type='text' id='bill_city' name='bill_city'  ></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>Zip code*</span>";
        $list.="<span class='span2'><input type='text' id='bill_zip' name='bill_zip'  ></span>";
        $list.="<span class='span2'>Contact email*</span>";
        $list.="<span class='span2'><input type='text' id='bill_email' name='bill_email'  ></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:center;'>";
        $list.="<span class='span2'>&nbsp;</span><span class='span4'><a href='#' onClick='return false;' id='policy'>Click Here to View Terms And Conditions</a></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>&nbsp;</span><span class='span4'><input type='checkbox' id='policy_checkbox'> I have read and agree to Terms and Conditions</span>";
        $list.="</div>";

        $list.= "<div class='container-fluid' style='text-align:left;'>";
        $list.= "<span class='span2'><button class='btn btn-primary' id='make_payment_personal'>Submit</button></span>";
        $list.= "&nbsp <span style='color:red;' id='personal_payment_err'></span>";
        $list.= "</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span8' style='text-align:center;display:none;' id='ajax_loading_payment'><img src='https://$this->host/assets/img/ajax.gif' /></span>";
        $list.="</div>";

        $list.="</div>";
        $list.="</div>";

        if ($from_email != null) {
            $list.="</div>"; // form div
        }

        return $list;
    }

    function get_state_name_by_id($stateid) {
        //echo "State ID: ".$stateid."<br>";
        if (is_numeric($stateid)) {
            $query = "select * from mdl_states where id=$stateid";
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $state = $row['state'];
            }
        } // end if
        else {
            $state = $stateid;
        }
        return $state;
    }

    function get_state_code($stateid) {
        $query = "select * from mdl_states where id=$stateid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $code = $row['code'];
        }
        return $code;
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
            $statename = $this->get_state_name_by_id($group_common_section->state);
            $user->state = $statename;
            $user->slotid = $group_common_section->slotid;
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
        $group_common_section->statename = $statename;
        $_SESSION['group_common_section'] = $group_common_section;
        $_SESSION['users'] = $users;
        $_SESSION['tot_participants'] = $tot_participants;
        //$list = $this->get_payment_options($group_common_section->courseid, 1);
        $list = $this->get_payment_with_options('online_whole_group_payment');
        //$list = "<p align='center'>Please contact site administrator for payment options help@medical2.com</p>";
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
        $query = "select * from mdl_user "
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

    function add_payment_to_db($card) {
        $card_last_four = substr($card->card_no, -4);

        //echo "<pre> add_payment_to_db";
        //print_r($card);
        //echo "</pre>--------------------<br>";


        $exp_date = $card->card_month . $card->card_year;
        $query = "insert into mdl_card_payments "
                . "(userid,"
                . "courseid, card_last_four, exp_date, "
                . "psum, "
                . "trans_id, "
                . "auth_code, "
                . "pdate) "
                . "values('" . $card->userid . "',"
                . "'" . $card->courseid . "', '" . base64_encode($card_last_four) . "', '$exp_date', "
                . "'" . $card->sum . "', "
                . "'$card->transid', "
                . "'$card->auth_code', "
                . "'" . time() . "')";
        //echo "Query: ".$query."<br>";
        $this->db->query($query);
    }

    function get_user_payment_credentials($userid) {
        if ($userid > 0) {
            $query = "select * "
                    . "from mdl_user where id=$userid";
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $user = new stdClass();
                foreach ($row as $key => $value) {
                    $user->$key = $value;
                } // end foreach
            } // end while

            $query = "select code from mdl_states where state='$user->state'";
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $code = $row['code'];
            } // end while
            $user->state_code = $code;
        } //  ebd if $userid > 0
        else {
            $user = null;
        }
        return $user;
    }

    function get_card_type($card_name) {
        $card_type = 0;
        switch ($card_name) {
            case "American Express":
                $card_type = 12;
                break;
            case "Discover":
                $card_type = 14;
                break;
            case "Master":
                $card_type = 10;
                break;
            case "Visa":
                $card_type = 11;
                break;
        }
        return $card_type;
    }

    function add_subscription($userid, $courseid, $subsid) {
        $query = "update mdl_installment_users "
                . "set subscription_id='$subsid' , "
                . "subscription_start='" . time() . "' "
                . "where userid=$userid "
                . "and courseid=$courseid";
        $this->db->query($query);
    }

    function get_user_slotid($courseid, $userid) {
        if ($userid > 0) {
            $query = "select * from mdl_slots "
                    . "where courseid=$courseid and userid=$userid";
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $slotid = $row['slotid'];
            }
        } // end if $userid>0
        else {
            $slotid = 0;
        }
        return $slotid;
    }

    function get_course_completion($courseid, $userid) {
        $query = "select * from mdl_course_completions "
                . "where course=$courseid "
                . "and userid=$userid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $timecompleted = $row['timecompleted'];
        }
        return $timecompleted;
    }

    function get_group_users_slot($userid) {
        $query = "select * from mdl_slots where userid=$userid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $slotid = $row['slotid'];
        }
        return $slotid;
    }

    function make_stub_payment($card) {

        $list = "";
        $mailer = new Mailer();
        $invoice = new Invoice();
        $user_group = $card->user_group;
        $userid = $card->userid;
        $item = substr($this->get_course_name($card->courseid), 0, 27);
        $cart_type_num = $this->get_card_type($card->card_type);
        $user_payment_data = $this->get_user_payment_credentials($card->userid); // compatible if user does not exist
        $renew_fee = $this->get_renew_fee();
        // Make card object compatible with confirmation email
        $names = explode(" ", $card->card_holder);
        $firstname = $names[0];
        $lastname = $names[1];

        $card->email = $user_payment_data->email;
        $card->slotid = $this->get_user_slotid($card->courseid, $card->userid); // compatible if user does not exist
        $card->first_name = $firstname;
        $card->signup_first = $user_payment_data->firstname;
        $card->last_name = $lastname;
        $card->signup_last = $user_payment_data->lastname;

        $card->phone = $user_payment_data->phone1;
        $card->pwd = $user_payment_data->purepwd;
        $card->addr = $user_payment_data->address;
        $card->city = $user_payment_data->city;
        //$card->state = $user_payment_data->state;
        $card->zip = $user_payment_data->zip;
        $card->country = "US";
        $card->payment_amount = $card->sum;

        $installment_status = $invoice->is_installment_user($card->userid, $card->courseid);

        if ($installment_status == 0) {
            // Personal online payment
            if ($user_group == '' && $userid > 0) {
                $state_code = $this->get_state_code($card->state);
                $user_payment_data = $this->get_user_payment_credentials($userid);
                $order = new stdClass();
                $order->cds_name = "$firstname/$lastname";
                $order->cds_address_1 = $card->bill_addr;
                $order->cds_city = $card->bill_city;
                $order->cds_state = $state_code;
                $order->cds_zip = $card->bill_zip;
                $order->cds_email = $card->email;
                $order->phone = $user_payment_data->phone1;
                $order->cds_pay_type = $cart_type_num;
                $order->cds_cc_number = $card->card_no;
                $order->cds_cc_exp_month = $card->card_month;
                $order->cds_cc_exp_year = $card->card_year;
                $order->sum = $card->sum;
                $order->cvv = $card->cvv; // add card cvv code to processor 
                $order->item = $item;
                $order->group = 0;

                $pr = new ProcessPayment();
                $status = $pr->make_transaction($order);
                if ($status === false) {
                    $list.="<div class='panel panel-default' id='personal_payment_details'>";
                    $list.="<div class='panel-heading'style='text-align:left;'><h5 class='panel-title'>Payment Details</h5></div>";
                    $list.="<div class='panel-body'>";
                    $list.= "<div class='container-fluid' style='text-align:left;'>";
                    $list.= "<span class='span8'>Transaction failed, please contact your bank for details.</span>";
                    $list.="</div>";
                    $list.="</div>";
                    $list.="</div>";
                } // end if $status === false
                else {
                    $card->transid = $status['trans_id'];
                    $card->auth_code = $status['auth_code'];
                    $this->confirm_user($card->email);
                    $this->add_payment_to_db($card); // adds payment result to DB
                    $mailer->send_payment_confirmation_message($card);
                    $list.="<div class='panel panel-default' id='personal_payment_details'>";
                    $list.="<div class='panel-heading'style='text-align:left;'><h5 class='panel-title'>Payment Details</h5></div>";
                    $list.="<div class='panel-body'>";
                    $list.= "<div class='container-fluid' style='text-align:left;'>";
                    if ($card->sum != $renew_fee) {
                        $list.= "<span class='span8'>Payment is successful. Thank you! You can print your registration data <a href='https://" . $_SERVER['SERVER_NAME'] . "/lms/custom/invoices/registrations/$user_payment_data->email.pdf' target='_blank'>here.</a></span>";
                    } // end if $card->sum != $renew_fee                    
                    else {
                        $list.= "<span class='span8'>Payment is successful. Thank you! Please use Renew Certificate option from <a href='https://" . $_SERVER['SERVER_NAME'] . "/lms/my' target='_blank'>your Dashboard</a></span>";
                    } // end else
                    $list.="</div>";
                    $list.="</div>";
                    $list.="</div>";
                    $this->enroll->add_user_to_course_schedule($card->userid, $card);
                }
            } // end if $user_group==''        
            // Installment online payment?
            if ($user_group != '' && $userid > 0) {
                //$user_payment_data = $this->get_user_payment_credentials($userid);
                $order = new stdClass();
                $names = explode(" ", $card->card_holder);
                //print_r($names);
                //echo "<br>";                
                //die ();
                $order->cds_name = "$names[0] $names[1]";
                $order->cds_address_1 = $card->bill_addr;
                $order->cds_city = $card->bill_city;
                $order->cds_state = $card->bill_state;
                $order->cds_zip = $card->bill_zip;
                $order->cds_email = $card->email;
                $order->cds_pay_type = $cart_type_num;
                $order->cds_cc_number = $card->card_no;
                $order->cvv = $card->cvv; // add card cvv code to processor 
                $order->cds_cc_exp_month = $card->card_month;
                $order->cds_cc_exp_year = $card->card_year;
                $order->sum = $card->sum;
                $order->item = $item;
                $order->group = 0;

                $pr = new ProcessPayment();
                $status = $pr->make_transaction($order);
                if ($status === false) {
                    $list.="<div class='panel panel-default' id='personal_payment_details'>";
                    $list.="<div class='panel-heading'style='text-align:left;'><h5 class='panel-title'>Payment Details</h5></div>";
                    $list.="<div class='panel-body'>";
                    $list.= "<div class='container-fluid' style='text-align:left;'>";
                    $list.= "<span class='span8'>Transaction failed, please contact your bank for details.</span>";
                    $list.="</div>";
                    $list.="</div>";
                    $list.="</div>";
                } // end if $status === false
                else {
                    $card->transid = $status['trans_id'];
                    $card->auth_code = $status['auth_code'];
                    $this->confirm_user($card->email);
                    $this->add_payment_to_db($card); // adds payment result to DB
                    $mailer->send_payment_confirmation_message($card);
                    $list.="<div class='panel panel-default' id='personal_payment_details'>";
                    $list.="<div class='panel-heading'style='text-align:left;'><h5 class='panel-title'>Payment Details</h5></div>";
                    $list.="<div class='panel-body'>";
                    $list.= "<div class='container-fluid' style='text-align:left;'>";
                    $list.= "<span class='span8'>Payment is successful. Thank you!</span>";
                    $list.="</div>";
                    $list.="</div>";
                    $list.="</div>";
                    $this->enroll->add_user_to_course_schedule($card->userid, $card);
                } // end else             
            } // end if $user_group!='' && $userid!=''
            // Group online payment
            if ($user_group != '' && $userid == '') {

                $group_users = $this->get_group_users($user_group);

                $group_sum = $card->sum;
                $state_code = $this->get_state_code($card->state);
                $order = new stdClass();
                $order->cds_name = $card->card_holder;
                $order->cds_address_1 = $card->bill_addr;
                $order->cds_city = $card->bill_city;
                $order->cds_state = $state_code;
                $order->cds_zip = $card->bill_zip;
                $order->cds_email = $card->email;
                $order->cds_pay_type = $cart_type_num;
                $order->cds_cc_number = $card->card_no;
                $order->cvv = $card->cvv; // add card cvv code to processor 
                $order->cds_cc_exp_month = $card->card_month;
                $order->cds_cc_exp_year = $card->card_year;
                $order->sum = $card->sum;
                $order->item = $item;
                $order->group = 1;

                $pr = new ProcessPayment();
                $status = $pr->make_transaction($order);
                if ($status === false) {
                    $list.="<div class='panel panel-default' id='personal_payment_details'>";
                    $list.="<div class='panel-heading'style='text-align:left;'><h5 class='panel-title'>Payment Details</h5></div>";
                    $list.="<div class='panel-body'>";
                    $list.= "<div class='container-fluid' style='text-align:left;'>";
                    $list.= "<span class='span8'>Transaction failed, please contact your bank for details.</span>";
                    $list.="</div>";
                    $list.="</div>";
                    $list.="</div>";
                } // end if $status === false
                else {
                    $card->transid = $status['trans_id'];
                    $card->auth_code = $status['auth_code'];
                    $list.="<div class='panel panel-default' id='personal_payment_details'>";
                    $list.="<div class='panel-heading'style='text-align:left;'><h5 class='panel-title'>Payment Details</h5></div>";
                    $list.="<div class='panel-body'>";
                    $list.= "<div class='container-fluid' style='text-align:left;'>";
                    $list.= "<span class='span8'>Payment is successful. Thank you!</span>";
                    $list.="</div>";
                    $list.="</div>";
                    $list.="</div>";

                    /*
                      echo "<br>-----Group users:----------------<br>";
                      print_r($group_users);
                      echo "<br>---------------------------------<br>";
                     */

                    if (count($group_users > 0)) {
                        foreach ($group_users as $userid) {
                            $slotid = $this->get_group_users_slot($userid);
                            $card->slotid = $slotid;
                            $user = $this->get_user_detailes($userid);
                            $card->userid = $userid;
                            $this->enroll->add_user_to_course_schedule($userid, $card);
                            $card->sum = round(($group_sum / count($group_users)), 2); // Sum for every group participant 
                            $this->add_payment_to_db($card); // adds payment result to DB
                            $this->confirm_user($user->username);
                            $mailer->send_group_payment_confirmation_message($user);
                        } // end foreach
                    } // end if count($group_users > 0)
                } // end else             
            } // end if $user_group!='' && $userid==''
        } // end if $installment_status==0
        else {
            // It is installment user - create subscription
            $user_payment_data = $this->get_user_payment_credentials($card->userid);
            $installmentobj = $invoice->get_user_installment_payments($card->userid, $card->courseid);
            $order = new stdClass();
            $order->cds_name = "$firstname/$lastname";
            $order->cds_address_1 = $card->bill_addr;
            $order->cds_city = $card->bill_city;
            $order->cds_state = "$user_payment_data->state_code";
            $order->cds_zip = $card->bill_zip;
            $order->cds_email = $card->email;
            $order->cds_pay_type = $cart_type_num;
            $order->cds_cc_number = $card->card_no;
            $order->cvv = $card->cvv; // add card cvv code to processor 
            $order->cd_cc_month = $card->card_month;
            $order->cds_cc_year = $card->card_year;
            $order->sum = $card->sum;
            $order->item = $item;
            $order->group = 0;
            $order->userid = $card->userid;
            $order->courseid = $card->courseid;
            $order->payments_num = $installmentobj->num;

            $pr = new ProcessPayment();
            $subscriptionID = $pr->createSubscription($order);
            //echo "Subscription  ID: ".$subscriptionID."<br>";
            //die ('Stopped ...');
            if (is_numeric($subscriptionID)) {
                $this->add_subscription($card->userid, $card->courseid, $subscriptionID);
                $card->transid = $status['trans_id'];
                $card->auth_code = $status['auth_code'];
                $this->confirm_user($card->email);
                $this->add_payment_to_db($card); // adds payment result to DB                    
                $mailer->send_payment_confirmation_message($card);
                $list.="<div class='panel panel-default' id='personal_payment_details'>";
                $list.="<div class='panel-heading'style='text-align:left;'><h5 class='panel-title'>Payment Details</h5></div>";
                $list.="<div class='panel-body'>";
                $list.= "<div class='container-fluid' style='text-align:left;'>";
                $list.= "<span class='span8'>Installment payment is successful. Thank you!.</span>";
                $list.="</div>";
                $list.="</div>";
                $list.="</div>";
                $this->enroll->add_user_to_course_schedule($card->userid, $card);
            } // end if is_numeric($subscriptionID)
            else {
                $list.="<div class='panel panel-default' id='personal_payment_details'>";
                $list.="<div class='panel-heading'style='text-align:left;'><h5 class='panel-title'>Payment Details</h5></div>";
                $list.="<div class='panel-body'>";
                $list.= "<div class='container-fluid' style='text-align:left;'>";
                $list.= "<span class='span8'>Installment payment failed, please contact your bank for details.</span>";
                $list.="</div>";
                $list.="</div>";
                $list.="</div>";
            } // end else
        } // end else

        return $list;
    }

    function get_course_data($courseid, $slotid) {
        $query = "select * from mdl_course where id=$courseid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $name = $row['fullname'];
            $cost = $row['cost'];
            $categoryid = $row['category'];
            $discount_size = $row['discount_size'];
        }

        if ($discount_size > 0) {
            $initial_amount = $cost - (($cost * $discount_size) / 100);
        } // end if $discount_size>0
        else {
            $initial_amount = $cost;
        } // end else

        if ($categoryid != 5) {
            if ($slotid > 0) {
                $week_secs = 604800;  // Registration should be one week ahead
                $query = "select * from mdl_scheduler_slots where id=$slotid";
                $result = $this->db->query($query);
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $starttime = $row['starttime'];
                } // end while

                $diff = $starttime - time();

                if ($diff <= $week_secs) {
                    $late_fee = 25;
                } // end if $diff<$week_secs
                else {
                    $late_fee = 0;
                } // end else
            } // end if $slotid > 0
            else {
                $late_fee = 0;
            } // end else
        } // end if $categoryid!=5
        else {
            $late_fee = 0;
        } // end else

        if ($late_fee > 0) {
            $full_amount = ($initial_amount + $late_fee) . " (late fee applied)";
            $raw_full_amount = $initial_amount + $late_fee;
        } // end if $late_fee>0
        else {
            $full_amount = $initial_amount;
            $raw_full_amount = $initial_amount;
        } // end else
        $course = new stdClass();
        $course->name = $name;
        $course->cost = "$" . $full_amount;
        $course->raw_cost = $raw_full_amount;

        return json_encode($course);
    }

    function enroll_user2($user) {
        $list = "";
        /*
         * 
          stdClass Object
          (
          [first_name] => Test
          [last_name] => User
          [billing_name] => John Connair
          [addr] => Some Address2
          [city] => Some city2
          [state] => 10
          [country] => 234
          [zip] => 6902
          [inst] => n/a
          [phone] => 3802
          [email] => saalax2@ambro.com
          [cardnumber] => 234123412341211
          [cvv] => 2254
          [exp_month] => 07
          [exp_year] => 2021
          [come_from] => 0
          [courseid] => 45
          [slotid] => 730
          [amount] => 450
          )
         * 
         */

        //echo "<pre>";
        //print_r($user);
        //echo "</pre>";
        //die();
        $signup_status = $this->enroll->single_signup($user);

        if ($signup_status === true) {

            $names = explode(" ", $user->billing_name);
            $fisrtname = $names[0];
            $lastname = $names[1];
            $item = substr($this->get_course_name($user->courseid), 0, 30);

            $order = new stdClass();
            $order->cds_name = "$fisrtname/$lastname";
            $order->cds_address_1 = $user->addr;
            $order->cds_city = $user->city;
            $order->cds_state = $user->state;
            $order->cds_zip = $user->zip;
            $order->cds_email = $user->email;

            $order->cds_cc_number = $user->cardnumber;
            $order->cds_cc_exp_month = $user->exp_month;
            $order->cds_cc_exp_year = $user->exp_year;
            $order->sum = $user->amount;
            $order->cvv = $user->cvv;
            $order->item = $item;
            $order->group = 0;

            $pr = new ProcessPayment();
            $status = $pr->make_transaction2($order);
            if ($status === false) {
                $list.= "<div class='container-fluid' style='text-align:center;'>";
                $list.= "<span class='span8' style='color:red;font-weight:bold;'>Transaction failed. Credit card declined.</span>";
                $list.="</div>";
            } // end if $status === false
            else {
                $mailer = new Mailer();
                $renew_fee = $this->get_renew_fee();
                // Create compatible object fields
                $userid = $this->get_user_id_by_email($user->email);
                //echo "User id: ".$userid."<br>";
                $user_detailes = $this->get_user_detailes($userid);

                //echo "<br>----------------------<br>";
                //print_r($user_detailes);
                //echo "<br>----------------------<br>";

                $user->userid = $userid;
                $user->card_no = $user->cardnumber;
                $user->sum = $user->amount;
                $user->transid = $status['trans_id'];
                $user->auth_code = $status['auth_code'];
                $user->pwd = $user_detailes->purepwd;
                $user->payment_amount = $user->amount;
                $user->card_holder = $user->billing_name;
                $user->card_month = $user->exp_month;
                $user->card_year = $user->exp_year;
                $user->signup_first = $user->first_name;
                $user->signup_last = $user->last_name;
                $this->confirm_user($user->email);
                $this->add_payment_to_db($user); // adds payment result to DB
                $mailer->send_payment_confirmation_message($user);
                $list.= "<div class='container-fluid' style='text-align:center;'>";
                if ($user->sum != $renew_fee) {
                    $list.= "<span class='span8'>Payment is successful. Thank you! You can print your registration data <a href='https://" . $_SERVER['SERVER_NAME'] . "/lms/custom/invoices/registrations/$user->email.pdf' target='_blank'>here.</a></span>";
                } // end if $card->sum != $renew_fee                    
                else {
                    $list.= "<span class='span8'>Payment is successful. Thank you! Please use Renew Certificate option from <a href='https://" . $_SERVER['SERVER_NAME'] . "/lms/my' target='_blank'>your Dashboard</a></span>";
                } // end else
                $list.="</div>";
                $this->enroll->add_user_to_course_schedule($user->userid, $user);
            } // end else
        } // end if $signup_status  === true
        else {
            $list.="<div class='container-fluid'>";
            $list.="<span class='span8'>Signup error happened </span>";
            $list.="</div>";
        }
        return $list;
    }

}
