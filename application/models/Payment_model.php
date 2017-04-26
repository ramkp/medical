<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Payment_model
 *
 * @author sirromas
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/Payment.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/Mailer.php';

//require_once $_SERVER['DOCUMENT_ROOT'] .'/lms/custom/usrgroups/classes/Groups.php';

class Payment_model extends CI_Model {

    public $payment;

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->payment = new Payment();
        $this->load->model('register_model');
    }

    public function get_user_course($userid) {
        $query = "select enrolid, userid "
                . "from mdl_user_enrolments "
                . "where userid=$userid";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $enrolid = $row->enrolid;
        }

        $query = "select id, courseid from mdl_enrol where id=$enrolid";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $courseid = $row->courseid;
        }
        return $courseid;
    }

    public function get_user_data($userid) {
        $query = "select * "
                . "from mdl_user where id=$userid";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $user = new stdClass();
            foreach ($row as $key => $value) {
                $user->$key = $value;
            }
        }
        return $user;
    }

    public function get_course_payment_options($courseid, $group = null) {
        $query = "select installment, num_payments "
                . "from mdl_course "
                . "where id=$courseid";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $inst = $row->installment;
            $num_payments = $row->num_payments;
        }
        $period = $this->payment->get_course_enrollment_period($courseid);
        $data = array('id' => $courseid, 'inst' => $inst, 'num_payments' => $num_payments, 'group' => $group, 'period' => $period);
        return $data;
    }

    public function is_group_member($userid) {
        $query = "select id, userid "
                . "from mdl_groups_members "
                . "where userid=$userid";
        $result = $this->db->query($query);
        $num = $result->num_rows();
        return $num;
    }

    public function get_user_group($userid) {
        $query = "select id, groupid, userid "
                . "from mdl_groups_members "
                . "where userid=$userid";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $groupid = $row->groupid;
        }

        $query = "select id, name from mdl_groups where id=$groupid";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $name = $row->name;
        }
        return $name;
    }

    public function get_user_slot($userid) {
        $query = "select * from mdl_scheduler_appointment "
                . "where studentid=$userid";
        $result = $this->db->query($query);
        $num = $result->num_rows();
        if ($num > 0) {
            foreach ($result->result() as $row) {
                $slotid = $row->slotid;
            }
        } // end if $num>0
        else {
            $slotid = 0;
        }
        return $slotid;
    }

    public function get_payment_section($userid, $courseid, $slotid, $sum, $renew = null) {
        $list = "";
        //echo "Sum inside model: ".$sum."<br>";
        if ($userid != NULL) {
            $invoice = new Invoice();
            $user = $this->get_user_data($userid);
            $user->courseid = $courseid;
            $user->slotid = $slotid;
            $group_status = $this->is_group_member($userid);
            $installment_status = $invoice->is_installment_user($userid, $courseid);
            $installment_status = 0; // No installment in manual mode
            if ($installment_status == 0) {
                if ($group_status == 0) {
                    // Personal signup
                    $group_data = '';
                    $participants = 1;
                    $list.=$this->payment->get_payment_section($group_data, $user, $participants, null, 1, $sum, $renew);
                } // end if $group_status==0
                else {
                    // Group member signup
                    $group_name = $this->get_user_group($userid);
                    $group_data = new stdClass();
                    $group_data->group_name = $group_name;
                    $group_data->courseid = $courseid;
                    $participants = 1;
                    $list.=$this->payment->get_payment_section($group_data, $user, $participants, null, 1, $sum, $renew);
                } // end else 
            } // end if $installment_status==0
            else {
                $installment_obj = $invoice->get_user_installment_payments($userid, $courseid);
                $installment = array();
                $installment['period'] = 28; // days
                $installment['num_payments'] = $installment_obj->num;
                if ($group_status == 0) {
                    // Personal signup
                    $group_data = '';
                    $participants = 1;
                    $list.=$this->payment->get_payment_section($group_data, $user, $participants, $installment, 1);
                } // end if $group_status==0
                else {
                    // Group member signup
                    $group_name = $this->get_user_group($userid);
                    $group_data = new stdClass();
                    $group_data->group_name = $group_name;
                    $group_data->courseid = $courseid;
                    $participants = 1;
                    $list.=$this->payment->get_payment_section($group_data, $user, $participants, $installment, 1);
                } // end else            
            } // end else when it is installment user
        }  // end if $userid != NULL
        return $list;
    }

    public function get_payment_section2($userid, $courseid, $slotid, $sum, $renew = null) {
        $list = "";
        //echo "Sum inside model: ".$sum."<br>";
        if ($userid != NULL) {
            $invoice = new Invoice();
            $user = $this->get_user_data($userid);
            $user->courseid = $courseid;
            $user->slotid = $slotid;
            $group_status = $this->is_group_member($userid);
            //$installment_status = $invoice->is_installment_user($userid, $courseid);
            $installment_status = 0; // No installment in manual mode
            if ($installment_status == 0) {
                if ($group_status == 0) {
                    // Personal signup
                    $group_data = '';
                    $participants = 1;
                    $list.=$this->payment->get_payment_section2($group_data, $user, $participants, null, 1, $sum, $renew);
                } // end if $group_status==0
                else {
                    // Group member signup
                    $group_name = $this->get_user_group($userid);
                    $group_data = new stdClass();
                    $group_data->group_name = $group_name;
                    $group_data->courseid = $courseid;
                    $participants = 1;
                    $list.=$this->payment->get_payment_section2($group_data, $user, $participants, null, 1, $sum, $renew);
                } // end else 
            } // end if $installment_status==0
            else {
                $installment_obj = $invoice->get_user_installment_payments($userid, $courseid);
                $installment = array();
                $installment['period'] = 28; // days
                $installment['num_payments'] = $installment_obj->num;
                if ($group_status == 0) {
                    // Personal signup
                    $group_data = '';
                    $participants = 1;
                    $list.=$this->payment->get_payment_section($group_data, $user, $participants, $installment, 1);
                } // end if $group_status==0
                else {
                    // Group member signup
                    $group_name = $this->get_user_group($userid);
                    $group_data = new stdClass();
                    $group_data->group_name = $group_name;
                    $group_data->courseid = $courseid;
                    $participants = 1;
                    $list.=$this->payment->get_payment_section($group_data, $user, $participants, $installment, 1);
                } // end else            
            } // end else when it is installment user
        }  // end if $userid != NULL
        return $list;
    }

    public function is_user_exissts($userid) {
        $query = "select * from mdl_user where id=$userid";
        //echo $query."<br>";
        $result = $this->db->query($query);
        $num = $result->num_rows();
        return $num;
    }

    public function get_group_renewal_fee($courseid, $period, $total) {
        $query = "select * from mdl_renew_amount where courseid=$courseid";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $amount = $row->amount; // payment for every year renewal
        }
        $grand_total = $amount * $period * $total;
        return $grand_total;
    }

    public function get_states_box() {
        $list = "";
        $list.="<select id='billing_state' style='width:154px;'>";
        $list.="<otpion value='0' selected>State</option>";
        $query = "select * from mdl_states order by state";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $list.="<option value='" . $row->id . "'>" . $row->state . "</option>";
        }
        $list.="</select>";
        return $list;
    }

    public function get_user_groupid($userid) {
        $query = "select * from mdl_groups_members where userid=$userid";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $groupid = $row->groupid;
        }
        return $groupid;
    }

    public function get_paypal_button($courseid, $period, $userslist) {
        $list = "";
        $users_arr = explode(',', $userslist);
        $groupid = $this->get_user_groupid($users_arr[0]);
        $total = count($users_arr);
        $cost = $this->get_group_renewal_fee($courseid, $period, $total);

        //$list.="<form action='https://www.paypal.com/cgi-bin/webscr' method='post'>"; // production
        $list.="<form action='https://www.sandbox.paypal.com/cgi-bin/webscr' method='post' id='paypal_group_renew_submit'>"; // sandbox
        $list.="<input type='hidden' name='cmd' value='_xclick'>";
        $list.="<INPUT TYPE='hidden' name='charset' value='utf-8'>";
        //$list.="<input type='hidden' name='business' value='contato@iprovida.org.br'>"; // production
        $list.="<input type='hidden' name='business' value='sirromas-facilitator@outlook.com'>"; // sandbox
        $list.="<input type='hidden' name='item_name' value='Group certificate renewal'>
        <input type='hidden' name='amount' value='$cost'>
        <input type='hidden' name='custom' value='$courseid/$groupid/$userslist/$period'>    
        <INPUT TYPE='hidden' NAME='currency_code' value='USD'>    
        <INPUT TYPE='hidden' NAME='return' value='https://medical2.com/payments/paypal_receive/'>
        <input type='image' id='paypal_btn' src='https://www.sandbox.paypal.com/en_US/i/btn/btn_buynow_LG.gif' border='0' name='submit' alt='PayPal - The safer, easier way to pay online!'>
        <img alt='' border='0' src='https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif' width='1' height='1'>
        </form>";

        return $list;
    }

    function add_group_user_renew_payment($p) {
        $query = "insert into mdl_card_payments "
                . "(courseid,"
                . "userid, "
                . "card_last_four, "
                . "exp_date,"
                . "psum, "
                . "trans_id, "
                . "auth_code, "
                . "pdate)"
                . "values ($p->courseid, "
                . "$p->userid, "
                . "'$p->card_last_four', "
                . "'$p->exp_date',"
                . "'$p->psum', "
                . "'$p->trans_id', "
                . "'$p->auth_code',"
                . "'$p->pdate')";
        $this->db->query($query);
    }

    function renew_user_certificate($courseid, $userid, $period) {
        $url = "https://medical2.com/lms/custom/certificates/paypal_group_renew.php?courseid=$courseid&userid=$userid&period=$period";
        file_get_contents($url);
    }

    function process_paypal_response($r) {
        $list = "";
        $amount = $_REQUEST['amt'];
        $currency = $_REQUEST['cc'];
        $custom_data = $_REQUEST['cm'];
        $data_arr = explode('/', $custom_data);
        $courseid = $data_arr[0];
        //$groupid = $data_arr[1];
        $period = $data_arr[3];
        $status = $_REQUEST['st'];
        $transactionid = $_REQUEST['tx'];
        $userslist = $data_arr[2];

        if ($status == 'Completed') {
            $users_arr = explode(',', $userslist);
            $total = count($users_arr);
            if ($total > 0) {
                $usersum = round($amount / $total);
                foreach ($users_arr as $userid) {
                    $p = new stdClass();
                    $p->courseid = $courseid;
                    $p->userid = $userid;
                    $p->card_last_four = 'paypal';
                    $p->exp_date = 'paypal';
                    $p->trans_id = $transactionid;
                    $p->auth_code = '00';
                    $p->psum = $usersum;
                    $p->pdate = time();
                    $this->add_group_user_renew_payment($p);
                    $this->renew_user_certificate($courseid, $userid, $period);
                } // end foreach
            } // end if $total>0

            $list.="<br/><div  class='form_div'>";
            $list.="<div class='panel panel-default' id='program_section' style='margin-bottom:0px;'>";
            $list.="<div class='panel-heading' style='text-align:left;'><h5 class='panel-title'>Group certificate renewal</h5></div>";
            $list.="<div class='panel-body'>";

            $list.="<div class='container-fluid' style='text-align:center;'>";
            $list.="<span class='span8'>Payment is successful. Thank you! </span>";
            $list.="</div>";

            $list.="</div></div></div>";
        } // end if $status == 'Completed'
        else {
            $list.="<br/><div  class='form_div'>";
            $list.="<div class='panel panel-default' id='program_section' style='margin-bottom:0px;'>";
            $list.="<div class='panel-heading' style='text-align:left;'><h5 class='panel-title'>Group certificate renewal</h5></div>";
            $list.="<div class='panel-body'>";

            $list.="<div class='container-fluid' style='text-align:center;'>";
            $list.="<span class='span8'>Transaction failed</span>";
            $list.="</div>";

            $list.="</div></div></div>";
        } // end else

        return $list;
    }

    public function get_group_renew_form($courseid, $period, $userslist, $type) {
        // type=card/paypal
        $list = "";
        $users_arr = explode(',', $userslist);
        $groupid = $this->get_user_groupid($users_arr[0]);
        $states = $this->get_states_box();
        $total = count($users_arr);
        $cost = $this->get_group_renewal_fee($courseid, $period, $total);
        $card_year = $this->register_model->get_year_drop_box();
        $card_month = $this->register_model->get_month_drop_box();
        $paypal_button = $this->get_paypal_button($courseid, $period, $userslist);

        $list.="<br/><div  class='form_div'>";

        $list.="<div class='panel panel-default' id='program_section' style='margin-bottom:0px;'>";
        $list.="<div class='panel-heading' style='text-align:left;'><h5 class='panel-title'>Group certificate renewal</h5></div>";
        $list.="<div class='panel-body'>";

        $list.="<input type='hidden' id='courseid' value='$courseid'>";
        $list.="<input type='hidden' id='groupid' value='$groupid'>";
        $list.="<input type='hidden' id='psum' value='$cost'>";

        $list.="<div class='container-fluid' style='text-align:left;font-weight:bold;' id='course_fee'>";
        $list.="<span class='span2'>Selected program</span>";
        $list.="<span class='span2'>Group certificate renewal</span>";
        $list.="<span class='span2'>Fee</span>";
        $list.="<span class='span2'>$$cost</span>";
        $list.="</div>";

        if ($type == 'card') {
            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span2'>Card Holder First name*</span>";
            $list.="<span class='span2'><input type='text' required id='b_fname' name='b_fname' placeholder='Firstname' required></span>";
            $list.="<span class='span2'>Card Holder Last name*</span>";
            $list.="<span class='span2'><input type='text' required id='b_lname' name='b_lname' placeholder='Lastname' required></span>";
            $list.="</div>";
        } // end if 
        else {
            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span2'>First name*</span>";
            $list.="<span class='span2'><input type='text' required id='b_fname' name='b_fname' placeholder='Firstname' required></span>";
            $list.="<span class='span2'>Last name*</span>";
            $list.="<span class='span2'><input type='text' required id='b_lname' name='b_lname' placeholder='Lastname' required></span>";
            $list.="</div>";
        } // end else

        $list.="<div class='container-fluid' style='text-align:left;font-weight:bold;' id='course_fee'>";
        $list.="<span class='span2'>Billing phone*:</span>";
        $list.="<span class='span2'><input type='text' required id='b_phone' name='b_phone' placeholder='Phone' required></span>";
        $list.="<span class='span2'>Billing email*: </span>";
        $list.="<span class='span2'><input type='text'  required id='b_email' name='b_email' placeholder='Email' required></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;font-weight:bold;' id='course_fee'>";
        $list.="<span class='span2'>Billing address*:</span>";
        $list.="<span class='span2'><input type='text' required id='b_addr' name='b_addr' placeholder='Address' required></span>";
        $list.="<span class='span2'>Billing city*: </span>";
        $list.="<span class='span2'><input type='text'  required id='b_city' name='b_city' placeholder='City' required></span>";
        $list.="</div>";

        if ($type == 'card') {
            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span2'>Card number*</span>";
            $list.="<span class='span2'><input type='text' id='card_no2' name='card_no2'  required></span>";
            $list.="<span class='span2'>CVV*</span>";
            $list.="<span class='span2'><input type='text' id='cvv2' name='cvv2'  required></span>";
            $list.="</div>";

            $list.="<div class='container-fluid' style='text-align:left;'>";
            $list.="<span class='span2'>State*</span>";
            $list.="<span class='span2'>$states</span>";
            $list.="<span class='span2'>Expiration Date*</span>";
            $list.="<span class='span2'>" . $card_month . "&nbsp;&nbsp;&nbsp;" . $card_year . "</span>";
            $list.="</div>";
        } // end if
        else {
            $list.="<div class='container-fluid' style='text-align:left;font-weight:bold;' id='course_fee'>";
            $list.="<span class='span2'>State*:</span>";
            $list.="<span class='span2'>$states</span>";
            $list.="<span class='span2'>Zip*: </span>";
            $list.="<span class='span2'><input type='text'  required id='b_zip' name='b_zip' placeholder='ZIP' required></span>";
            $list.="</div>";
        }

        $list.="<div class='container-fluid' style='text-align:center;'>";
        $list.="<span class='span8' id='paypal_err' style='color:red;'></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span8' style='text-align:center;display:none;' id='ajax_loading_payment'><img src='https://" . $_SERVER['SERVER_NAME'] . "/assets/img/ajax.gif' /></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:center;'>";
        $list.="<span class='span8'>By clicking the 'Buy Now' button, you confirm you have reviewed and agree to the Pay by Computer Terms & Conditions <br><span style='text-align:center;margin-left:35px;'>(<a href='#' onClick='return false;' id='policy'>click to view)</a></span></span>";
        $list.="</div>";

        if ($type == 'card') {
            $list.="<div class='container-fluid' style='text-align:center;'>";
            $list.="<span class='span8'><button class='btn btn-primary' id='make_group_renew_payment'>I Agree, Submit</button></span>";
            $list.="</div>";
        } // end if
        else {
            $list.="<div class='container-fluid' style='text-align:center;'>";
            $list.="<span class='span8'>$paypal_button</span>";
            $list.="</div>";
        }

        $list.="</div></div></div>";

        return $list;
    }

}
