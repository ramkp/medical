<?php

require_once ($_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php');
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/Mailer.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/certificates/classes/Certificates2.php';

/**
 * Description of Groups
 *
 * @author moyo
 */
class Groups extends Util {

    public $limit = 3;

    function __construct() {
        parent::__construct();
        $this->create_groups_data();
    }

    function create_groups_data() {
        $query = "select * from mdl_groups where courseid>0 order by name";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $has_members = $this->is_has_users($row['id']);
                if ($has_members > 0) {
                    $groupnames[] = mb_convert_encoding($row['name'], 'UTF-8');
                } // end if $has_members > 0
            } // end while 
            file_put_contents('/home/cnausa/public_html/lms/custom/utils/groups.json', json_encode($groupnames));
        } // end if $num > 0
    }

    function is_has_users($groupid) {
        $query = "select * from mdl_groups_members where groupid=$groupid";
        $num = $this->db->numrows($query);
        return $num;
    }

    function get_groups_page() {
        $list = "";
        $groups = array();
        $query = "select * from mdl_groups where courseid>0 "
                . "order by name limit 0, $this->limit";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $has_members = $this->is_has_users($row['id']);
                if ($has_members > 0) {
                    $g = new stdClass();
                    foreach ($row as $key => $value) {
                        $g->$key = $value;
                    }
                    $groups[] = $g;
                } // end if $has_members > 0
            } // end while
        } // end if $num>0
        $list.=$this->create_groups_page($groups);
        return $list;
    }

    function create_groups_page($groups, $toolbar = true) {
        $list = "";

        if ($toolbar) {
            $list.="<div class='row-fluid'>";
            $list.="<span class='span1'>Search</span>";
            $list.="<span class='span3'><input type='text' id='group_search_text'></span>";
            $list.="<span class='span2'><button class='btn btn-primary' id='search_group_button'>Search</button></span>";
            $list.="<span calss='span2'><button class='btn btn-primary' id='clear_search_group_button'>Clear</button></span>";
            $list.="</div>";

            $list.="<div class='container-fluid' style='display:none;text-align:center;' id='ajax_loader'>";
            $list.="<span class='span9'><img src='https://$this->host/assets/img/ajax.gif' /></span>";
            $list.="</div>";

            $list.="<div class='row-fluid'>";
            $list.="<span class='span9'><hr/></span>";
            $list.="</div>";

            $list.="<div class='row-fluid' style='font-weight:bold;'>";
            $list.="<span class='span3'>Group Name</span>";
            $list.="<span class='span3'>Course Name</span>";
            $list.="<span class='span3'>Group Users</span>";
            $list.="</div>";
        }
        if (count($groups) > 0) {
            $list.="<div id='groups_container'>";
            foreach ($groups as $g) {
                $users = $this->get_group_users($g->id);
                $total_users = $this->get_group_total_users($g->id);
                $coursename = $this->get_course_name($g->courseid);
                $renew_button = $this->is_course_certificate_expired($g->courseid, $g->id);
                $list.="<div class='row-fluid'>";
                $list.="<input type='hidden' id='group_course_$g->id' value='$g->courseid'>";
                $list.="<span class='span3'>$g->name <br>" . $total_users . " total participants<br> <input type='checkbox' id='group_select_all_$g->id'>Select All<br><span id='group_err_$g->id'></span></span>";
                $list.="<span class='span3'>$coursename</span>";
                $list.="<span class='span3'>$users</span>";
                $list.="<span calss='span2'>$renew_button</span>";
                $list.="</div>";
                $list.="<div class='row-fluid'>";
                $list.="<span class='span9'><hr/></span>";
                $list.="</div>";
            } // end foreach
            $list.="</div>";
        } // end if count($groups)>0
        else {
            $list.="<div class='row-fluid'>";
            $list.="<span class='span9'>There are no groups at the course</span>";
            $list.="</div>";
        }
        if ($toolbar) {
            $list.="<div class='container-fluid'>";
            $list.="<span class='span9'  id='pagination'></span>";
            $list.="</div>";
        }

        return $list;
    }

    function is_course_certificate_expired($courseid, $groupid) {
        $list = "";
        $query = "select * from mdl_course where id=$courseid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $expired = $row['expired'];
        }
        if ($expired == 0) {
            $list.="<button class='btn btn-primary' disabled id='renew_group_button_$groupid'>Renew</button>";
        } // end if $expired==0
        else {
            $list.="<button class='btn btn-primary' id='renew_group_button_$groupid'>Renew</button>";
        } // end else
        return $list;
    }

    function get_states_box() {
        $list = "";
        $list.="<select id='billing_state' style='width:220px;'>";
        $list.="<otpion value='0' selected>State</option>";
        $query = "select * from mdl_states order by state";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $list.="<option value='" . $row['id'] . "'>" . $row['state'] . "</option>";
        }
        $list.="</select>";
        return $list;
    }

    function get_billing_person_info() {
        $list = "";
        $state = $this->get_states_box();
        $list.="<div class='container-fluid' style='text-align:left;'>
                        <span class='span2'>Billing name*</span>
                        <span class='span2'><input type='text' id='billing_name'></span>
                    </div>
                    
                    <div class='container-fluid' style='text-align:left;'>
                        <span class='span2'>Billing email*</span>
                        <span class='span2'><input type='text' id='billing_email'></span>
                    </div>
                    
                    <div class='container-fluid' style='text-align:left;'>
                        <span class='span2'>Billing Phone*</span>
                        <span class='span2'><input type='text' id='billing_phone'></span>
                    </div>
                    
                    <div class='container-fluid' style='text-align:left;'>
                        <span class='span2'>Billing Address*</span>
                        <span class='span2'><input type='text' id='billing_addr'></span>
                    </div>
                    
                    <div class='container-fluid' style='text-align:left;'>
                        <span class='span2'>Billing City*</span>
                        <span class='span2'><input type='text' id='billing_city'></span>
                    </div>
                    
                    <div class='container-fluid' style='text-align:left;'>
                        <span class='span2'>Billing State*</span>
                        <span class='span2'>$state</span>
                    </div>
                    
                    <div class='container-fluid' style='text-align:left;'>
                        <span class='span2'>Billing Zip*</span>
                        <span class='span2'><input type='text' id='billing_zip'></span>
                    </div>";



        return $list;
    }

    function get_renew_cert_dialog($gusers) {
        $list = "";
        $billing_info = $this->get_billing_person_info();
        $list.="<div id='myModal' class='modal fade'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h4 class='modal-title'>Renew user certificate</h4>
                </div>
                <div class='modal-body' style='text-align:center;'>
                
                <input type='hidden' id='courseid' value='$gusers->courseid'>
                <input type='hidden' id='users' value='" . base64_encode($gusers->users) . "'>  
                <input type='hidden' id='groupid' value='$gusers->groupid'>      
                
                <div class='container-fluid' style='text-align:center;'>
                
                 <!--
                 <span class='span1'>
                 <input type='radio' name='renew_payment_type' class='ptype' value='0' checked>Card
                 </span>
                 -->
                 
                  <span class='span1'>
                  <input type='radio' name='renew_payment_type' class='ptype' value='1' checked>Paypal
                  </span>
                 
                  
                  <span class='span2'>
                  <input type='radio' name='renew_payment_type' class='ptype' value='2'>Cheque
                  </span>
              
                </div>
                
                 <div class='container-fluid' style='text-align:left;'>
                 
                 <span class='span1'>
                 <input type='radio' name='period' class='period' value='1' checked>1 Year
                 </span>
                 
                  <span class='span1'>
                  <input type='radio' name='period' class='period' value='2'>2 Year
                  </span>
                 
                  <span class='span2'>
                  <input type='radio' name='period' class='period' value='3'>3 Year
                  </span>
                  
                  </div>
                  
                <div class='container-fluid' id='billing_div' style='text-align:left;display:none;'>";

        $list.=$billing_info;

        $list.="</div>

                <div class='container-fluid' style=''>
                <span class='span4' style='color:red;' id='group_err'></span>
                </div>
             
                <div class='modal-footer' style='text-align:center;'>
                    <span align='center'><button type='button' class='btn btn-primary' data-dismiss='modal' id='cancel'>Cancel</button></span>
                    <span align='center'><button type='button' class='btn btn-primary' id='renew_group_cert_manager'>OK</button></span>
                </div>
            </div>
        </div>
    </div>";

        return $list;
    }

    function calculate_group_renew_amount($courseid, $period, $total) {
        $query = "select * from mdl_renew_amount where courseid=$courseid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $amount = $row['amount'];
        }
        $group_amount = $amount * $period * $total;
        return $group_amount;
    }

    function renew_group_certificates($cert) {
        $courseid = $cert->courseid;
        $groupid = $cert->groupid;
        $period = $cert->period;
        $userslist = base64_decode($cert->users); // comma separared list
        $ptype = $cert->ptype;
        $users_arr = explode(',', $userslist);
        $total = count($users_arr);
        $psum = $this->calculate_group_renew_amount($courseid, $period, $total);
        $usersum = round($psum / $total);
        $payment = new stdClass();
        $payment->courseid = $courseid;
        $payment->groupid = $groupid;
        $payment->psum = $psum;
        $payment->billing_name = $cert->billing_name;
        $payment->billing_email = $cert->billing_email;
        $payment->billing_phone = $cert->billing_phone;
        $payment->billing_addr = $cert->billing_addr;
        $payment->billing_city = $cert->billing_city;
        $payment->billing_state = $cert->billing_state;
        $payment->billing_zip = $cert->billing_zip;
        $payment->ptype = $ptype;
        $payment->userslist = $userslist;

        if (count($users_arr) > 0) {
            $certObj = new Certificates2();
            foreach ($users_arr as $userid) {
                $p = new stdClass();
                $p->courseid = $courseid;
                $p->userid = $userid;
                $p->ptype = $ptype;
                $p->psum = $usersum;
                $p->pdate = time();
                $this->add_group_user_renew_payment($p);
                $certObj->renew_certificate($courseid, $userid, $period);
            } // end foreach
            $this->add_group_payer_data($payment);
            $list = "<span style='font-weight:bold;'>Certificate(s) has been renewed</span>";
            return $list;
        } // end if count($users_arr)>0
    }

    function add_group_payer_data($payment) {
        $m = new Mailer();
        $query = "insert into mdl_group_payments "
                . "(courseid,"
                . "groupid,"
                . "psum,"
                . "billing_name,"
                . "email,"
                . "phone,"
                . "address,"
                . "city,"
                . "state,"
                . "zip,"
                . "pdate) "
                . "values ($payment->courseid,"
                . "$payment->groupid,"
                . "'$payment->psum',"
                . "'$payment->billing_name',"
                . "'$payment->billing_email',"
                . "'$payment->billing_phone',"
                . "'$payment->billing_addr',"
                . "'$payment->billing_city',"
                . "$payment->billing_state,"
                . "'$payment->billing_zip',"
                . "'" . time() . "')";
        $this->db->query($query);
        $m->send_group_renewal_receipt($payment, $payment->ptype);
    }

    function send_paypal_group_renew_receipt($g) {
        /*
         * 
          [pid] => 194
          [userslist] => 11571,11572
         * 
         */
        $m = new Mailer();
        $query = "select * from mdl_group_payments where id=$g->pid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $courseid = $row['courseid'];
            $groupid = $row['groupid'];
            $psum = $row['psum'];
            $name = $row['billing_name'];
            $email = $row['email'];
            $phone = $row['phone'];
            $addr = $row['address'];
            $city = $row['city'];
            $state = $row['state'];
            $zip = $row['zip'];
        }

        $payment = new stdClass();
        $payment->courseid = $courseid;
        $payment->groupid = $groupid;
        $payment->psum = $psum;
        $payment->billing_name = $name;
        $payment->billing_email = $email;
        $payment->billing_phone = $phone;
        $payment->billing_addr = $addr;
        $payment->billing_city = $city;
        $payment->billing_state = $state;
        $payment->billing_zip = $zip;
        $payment->ptype = 0;
        $payment->userslist = $g->userslist;
        $m->send_group_renewal_receipt($payment, $payment->ptype);
    }

    function add_paypal_payer_data($payment) {
        $billing_name = $payment->b_fname . " " . $payment->b_lname;
        $query = "insert into mdl_group_payments "
                . "(courseid,"
                . "groupid,"
                . "psum,"
                . "billing_name,"
                . "email,"
                . "phone,"
                . "address,"
                . "city,"
                . "state,"
                . "zip,"
                . "pdate) "
                . "values ($payment->courseid,"
                . "$payment->groupid,"
                . "'$payment->psum',"
                . "'$billing_name',"
                . "'$payment->b_email',"
                . "'$payment->b_phone',"
                . "'$payment->b_addr',"
                . "'$payment->b_city',"
                . "$payment->b_state,"
                . "'$payment->b_zip',"
                . "'" . time() . "')";
        $this->db->query($query);

        $query = "select * from mdl_group_payments order by id desc limit 0,1";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $id = $row['id'];
        }
        return $id;
    }

    function add_group_user_renew_payment($p) {
        $query = "insert into mdl_partial_payments "
                . "(courseid,"
                . "userid,"
                . "psum,"
                . "ptype,"
                . "pdate) "
                . "values ($p->courseid,"
                . "$p->userid,"
                . "$p->psum,"
                . "$p->ptype,"
                . "'$p->pdate')";
        $this->db->query($query);
    }

    function get_group_users($groupid) {
        $list = "";
        $users = array();
        $query = "select * from mdl_groups_members where groupid=$groupid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $users[] = $row['userid'];
        }
        foreach ($users as $userid) {
            $user = $this->get_user_details($userid);
            $list.="<div class='row-fluid'>";
            $list.="<span class='span12'><input type='checkbox' class='user_group_$groupid' value='$userid'><a href='https://" . $_SERVER['SERVER_NAME'] . "/lms/user/profile.php?id=$userid' target='_blank' style='cursor:pointer;'>$user->firstname $user->lastname</a></span>";
            $list.="</div>";
        }

        return $list;
    }

    function get_group_total_users($groupid) {
        $query = "select * from mdl_groups_members where groupid=$groupid";
        $num = $this->db->numrows($query);
        return $num;
    }

    function get_total_groups() {
        $groups = array();
        $query = "select * from mdl_groups where courseid>0 order by name";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $has_members = $this->is_has_users($row['id']);
                if ($has_members > 0) {
                    $groups[] = $row['id'];
                } // end if $has_members > 0
            } // end while
            $total = count($groups);
        } // end if $num>0
        else {
            $total = 0;
        }
        return $total;
    }

    function get_group_item($page) {
        $groups = array();
        $rec_limit = $this->limit;
        if ($page == 1) {
            $offset = 0;
        } // end if $page==1
        else {
            $page = $page - 1;
            $offset = $rec_limit * $page;
        }
        $query = "select * from mdl_groups where courseid>0"
                . " order by name LIMIT $offset, $rec_limit";
        //echo "Query: ".$query."<br>";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $has_users = $this->is_has_users($row['id']);
            if ($has_users > 0) {
                $g = new stdClass();
                foreach ($row as $key => $value) {
                    $g->$key = $value;
                } // end foreach
                $groups[] = $g;
            }
        } // end while
        $list = $this->create_groups_page($groups, false);
        return $list;
    }

    function search_group_item($item) {
        $list = "";
        $groups = array();
        $query = "select * from mdl_groups where name like '%$item%' "
                . "order by name";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $has_users = $this->is_has_users($row['id']);
                if ($has_users > 0) {
                    $g = new stdClass();
                    foreach ($row as $key => $value) {
                        $g->$key = $value;
                    } // end foreach
                    $groups[] = $g;
                } // end if $has_users > 0
            } // end while
        } // end if $num > 0
        $list.= $this->create_groups_page($groups, false);
        return $list;
    }

}
