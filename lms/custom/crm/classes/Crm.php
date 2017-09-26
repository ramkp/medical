<?php

require_once ($_SERVER['DOCUMENT_ROOT'] . '/lms/class.pdo.database.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/lms/class.pdo.database2.php');

class Crm {

    public $moodle_db;
    public $crm_db;
    public $signup_url;

    function __construct() {
        $this->moodle_db = new pdo_db();
        $this->crm_db = new pdo_db2();
        $this->signup_url = 'https://' . $_SERVER['SERVER_NAME'] . '/lms/login/my_signup2.php';
    }

    function is_email_exists($email) {
        $query = "select id, username, deleted "
                . "from mdl_user where username='$email' and deleted=0";
        return $this->moodle_db->numrows($query);
    }

    function create_guid() {
        $microTime = microtime();
        list($a_dec, $a_sec) = explode(' ', $microTime);

        $dec_hex = dechex($a_dec * 1000000);
        $sec_hex = dechex($a_sec);

        $this->ensure_length($dec_hex, 5);
        $this->ensure_length($sec_hex, 6);

        $guid = '';
        $guid .= $dec_hex;
        $guid .= $this->create_guid_section(3);
        $guid .= '-';
        $guid .= $this->create_guid_section(4);
        $guid .= '-';
        $guid .= $this->create_guid_section(4);
        $guid .= '-';
        $guid .= $this->create_guid_section(4);
        $guid .= '-';
        $guid .= $sec_hex;
        $guid .= $this->create_guid_section(6);

        return $guid;
    }

    function create_guid_section($characters) {
        $return = '';
        for ($i = 0; $i < $characters; ++$i) {
            $return .= dechex(mt_rand(0, 15));
        }

        return $return;
    }

    function ensure_length(&$string, $length) {
        $strlen = strlen($string);
        if ($strlen < $length) {
            $string = str_pad($string, $length, '0');
        } elseif ($strlen > $length) {
            $string = substr($string, 0, $length);
        }
    }

    function microtime_diff($a, $b) {
        list($a_dec, $a_sec) = explode(' ', $a);
        list($b_dec, $b_sec) = explode(' ', $b);

        return $b_sec - $a_sec + $b_dec - $a_dec;
    }

    function get_payment_schedule_dashboard() {
        $list = "";



        return $list;
    }

    function get_user_moodle_email($userid) {
        $query = "select * from mdl_user where id=$userid";
        $result = $this->moodle_db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $email = $row['email'];
        }
        return $email;
    }

    function get_crm_email_id($email) {
        $query = "select * from email_addresses where email_address='$email'";
        $num = $this->crm_db->numrows($query);
        if ($num > 0) {
            $result = $this->crm_db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $email_id = $row['id'];
            } // end while
        } // end if $num > 0
        else {
            $email_id = '';
        } // end else
        return $email_id;
    }

    function is_user_has_crm_account($userid) {
        $email = $this->get_user_moodle_email($userid);
        $email_id = $this->get_crm_email_id($email);
        if ($email_id != '') {
            $query = "select * from email_addr_bean_rel where email_address_id='$email_id'";
            $result = $this->crm_db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $crm_account_id = $row['bean_id'];
            }
        } // end if
        else {
            $crm_account_id = '';
        } // end else
        return $crm_account_id;
    }

    function get_support_button($userid) {
        $list = "";
        $crm_account_id = $this->is_user_has_crm_account($userid);
        if ($crm_account_id != '') {
            $list.="<div class='row-fluid'>";
            $list.="<span class='span4'><button class='btn btn-primary' id='new_case_$userid' data-crmid='$crm_account_id'>Support Request</button></span>";
            $list.="</div>";
        }
        return $list;
    }

    function get_new_case_modal_dialog($userid) {
        $list = "";
        $list.="<div id='myModal' class='modal fade' style='width:675px;'>
        <div class='modal-dialog'>
        
            <div class='modal-content'>
                <div class='modal-header'>
                    <h4 class='modal-title'>Add Support Case</h4>
                </div>
                <div class='modal-body'>
                <input type='hidden' id='crm_userid' value='$userid'>
                <div class='container-fluid'>
                <span class='span1'>Subject*:</span>
                <span class='span3'><input type='text' id='case_subject' style='width:475px;'></span>
                <br><br>
                </div>
                
                <div class='container-fluid'>
                <span class='span1'>Description*:</span>
                <span class='span3'><textarea style='width:475px;' id='case_desc'></textarea></span>
                </div>
                
                <div class='container-fluid' style=''>
                <span class='span1'>&nbsp;</span>
                <span class='span3' style='color:red;' id='case_err'></span>
                </div>
             
                <div class='modal-footer' style='text-align:center;'>
                    <span align='center'><button type='button' class='btn btn-primary' data-dismiss='modal' id='cancel'>Cancel</button></span>
                    <span align='center'><button type='button' class='btn btn-primary' id='add_new_support_case'>OK</button></span>
                </div>
            </div>
        </div>
    </div>";

        return $list;
    }

    function get_crm_user_id($userid) {
        $query = "select * from mdl_user where id=$userid";
        $result = $this->moodle_db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $crm_uid = $row['crm_uid'];
        }
        return $crm_uid;
    }

    function add_new_support_case($item) {
        $user_crm_id = $item->userid;
        $guid = $this->create_guid();
        $name = $item->subject;
        $message = $item->message;
        $stamp = date('Y-m-d H:i:s');
        $status = 'Open_New';
        $priority = 'P2';
        $state = 'Open';
        $query1 = "insert into cases (id,"
                . "name, "
                . "date_entered, "
                . "date_modified, "
                . "modified_user_id, "
                . "created_by,"
                . "description,"
                . "assigned_user_id,"
                . "status,"
                . "priority,"
                . "account_id,"
                . "state) values ('$guid',"
                . "'$name',"
                . "'$stamp',"
                . "'$stamp',"
                . "'1',"
                . "'1',"
                . "'$message',"
                . "'1',"
                . "'$status',"
                . "'$priority',"
                . "'$user_crm_id',"
                . "'$state')";
        //echo "Query1: " . $query1 . "<br>";
        $this->crm_db->query($query1);

        $query2 = "insert into cases_cstm (id_c) values ('$guid')";
        //echo "Query2: " . $query2 . "<br>";
        $this->crm_db->query($query2);
    }

    function get_email_address_id($crm_user_id) {
        $query = "select * from email_addr_bean_rel where bean_id='$crm_user_id'";
        $result = $this->crm_db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $email_id = $row['email_address_id'];
        }
        return $email_id;
    }

    function get_user_data($crm_user_id) {
        $query = "select * from accounts where id='$crm_user_id'";
        $result = $this->crm_db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $user = new stdClass();
            foreach ($row as $key => $value) {
                $user->$key = $value;
            }
        }
        return $user;
    }

    function get_user_email($crm_user_id) {
        $email_id = $this->get_email_address_id($crm_user_id);
        $query = "select * from email_addresses where id='$email_id'";
        $result = $this->crm_db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $email = $row['email_address'];
        }
        return $email;
    }

    function add_user_crm_id($crm_user_id, $email, $pwd) {
        $query = "update mdl_user set crm_uid='$crm_user_id', purepwd='$pwd' "
                . "where username='$email'";
        $this->moodle_db->query($query);
    }

    function update_new_moodle_user_pwd($email, $pwd) {
        $query = "update mdl_user set purepwd='$pwd' "
                . "where username='$email'";
        $this->moodle_db->query($query);
    }

    function generateRandomString($length = 25) {
        return substr(str_shuffle("0123456789abcde*fghijk*lmnopqrstuvwxyzABCDEF*GHIJKLMNOPQRSTUVWXYZ*"), 0, $length);
    }

    function create_moodle_account($crm_user_id) {

        $email = $this->get_user_email($crm_user_id);
        $status = $this->is_email_exists($email);
        if ($status == 0) {
            $userdata = $this->get_user_data($crm_user_id);
            $names_arr = explode(' ', $userdata->name);
            $fname = $names_arr[0];
            $lname = $names_arr[1];
            $pwd = $this->generateRandomString(8);

            $user = new stdClass();
            $user->username = $email;
            $user->password = $pwd;
            $user->pwd = $pwd;
            $user->first_name = $fname;
            $user->last_name = $lname;
            $user->email = $email;
            $user->email1 = $email;
            $user->email2 = $email;
            $user->addr = $userdata->billing_address_street;
            $user->city = $userdata->billing_address_city;
            $user->state = $userdata->billing_address_state;
            $user->zip = $userdata->billing_address_postalcode;
            $user->country = $userdata->billing_address_country;
            $user->phone1 = $userdata->phone_fax;

            $encoded_user = base64_encode(json_encode($user));
            $data = array('user' => $encoded_user);

            // 1. Signup user into moodle    
            $options = array(
                'http' => array(
                    'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method' => 'POST',
                    'content' => http_build_query($data),
                ),
            );

            $context = stream_context_create($options);
            $response = file_get_contents($this->signup_url, false, $context);
            if ($response !== false) {
                $this->update_new_moodle_user_pwd($email, $pwd);
            }  // end if $response !== false        
            else {
                return false;
            }
        } // end if status==0
        else {
            return false;
        }
    }

}
