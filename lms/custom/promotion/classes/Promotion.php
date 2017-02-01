<?php

/**
 * Description of Promotion
 *
 * @author sirromas
 */
include '/home/cnausa/public_html/lms/editor/fckeditor.php';
require_once '/home/cnausa/public_html/lms/custom/utils/classes/Util.php';
require_once '/home/cnausa/public_html/lms/custom/invoices/classes/Invoice.php';
require_once '/home/cnausa/public_html/functionality/php/classes/mailer/vendor/PHPMailerAutoload.php';

class Promotion extends Util {

    public $invoice;
    public $mail_smtp_host = 'mail.medical2.com';
    public $mail_smtp_port = 25;
    public $mail_smtp_user = 'info@medical2.com';
    public $mail_smtp_pwd = 'aK6SKymc';

    function __construct() {
        parent::__construct();
        $this->invoice = new Invoices();
    }

    function get_campaigns_list() {

        $list = "";
        $list.="<select id='camapaign'>";
        $list.="<option value='0' selected>Campaign</option>";
        $query = "select * from mdl_campaign order by dated desc";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $text = substr(strip_tags($row['content']), 0, 18);
                $date = date('m-d-Y', $row['dated']);
                $list.="<option value='" . $row[id] . "'>" . $text . " - " . $date . "</option>";
            } // end while
        } // end if $num > 0
        $list.="</select>";
        return $list;
    }

    function get_add_new_campaigner_block() {
        $program_types = $this->get_course_categories();
        //$states = $this->get_user_states();
        $list = "";

        $list.="<div class='container-fluid' style='align:center;'>";
        $list.="<span class='span6'>$program_types</span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='align:center;'>";
        $list.="<span class='span6' id='category_courses'></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='align:center;'>";
        $list.="<span class='span6' id='workshop_user_states'></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='align:center;'>";
        $list.="<span class='span6' id='workshop_user_cities'></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='align:center;'>";
        $list.="<span class='span6' id='course_workshops'></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='align:center;'>";
        $list.="<span class='span6' id='workshop_users'></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:center;display:none;' id='ajax_loader'>";
        $list.="<span class='span12'><img src='/assets/img/ajax.gif'></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='align:center;'>";
        $list.="<span class='span6' id='prom_err'></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='align:left;'>";
        $list.="<span class='span3'></span>";
        $list.="<span class='span3'><button type='button' id='get_campaign_users' class='btn btn-primary'>Get Students List</button>";
        $list.="</span>";
        $list.="</div>";

        $list.="<br><div class='container-fluid' id='create_div' style='text-align:left;display:none;'>";
        $list.="<span class='span3'></span>";
        $list.="<span class='span3'><button type='button' id='create_new_campaign' class='btn btn-primary'>Create new campaign</button>";
        $list.="</span>";
        $list.="</div>";

        return $list;
    }

    function get_promotion_page() {
        $list = "";

        if ($this->session->justloggedin == 1) {
            $list.="<div class='row-fluid' style='font-weight:bold;'>";
            $list.="<span class='span2'><input type='text' id='camp_state' style='width:125px' placeholder='State'></span>";
            $list.="<span class='span2'><input type='text' id='camp_city' style='width:125px' placeholder='City'></span>";
            $list.="<span class='span2'><input type='text' id='camp_ws' style='width:125px' placeholder='Workshop'></span>";
            $list.="<span class='span1'><button id='camp_search'>Search</button></span>";
            $list.="<span class='span1' style='padding-left:15px;'><button id='camp_reset_search'>Clear</button></span>";
            $list.="<span class='span3' style='padding-left:15px;'><button id='add_new_campaign_button'>Add New Campaign</button></span>";
            $list.="</div>";

            $list.="<div class='container-fluid' style='display:none;text-align:center;' id='ajax_loader'>";
            $list.="<span class='span10'><img src='https://$this->host/assets/img/ajax.gif' /></span>";
            $list.="</div>";

            $list.="<div class='row-fluid'>";
            $list.="<span class='span9' style='color:red;' id='camp_err'></span>";
            $list.="</div>";

            $list.="<div id='camp_users_container'></div>";
        } // end if $this->session->justloggedin == 1
        else {
            $list.="<p>You are not authenticated. &nbsp; <a href='https://medical2.com/login'><button class='btn btn-primary' id='relogin'>Login</button></a></p>";
        }


        return $list;
    }

    function add_new_campaign($data, $enrolled_users, $workshop_users) {
        mysql_connect("localhost", "cnausa_lms", "^pH+F8*[AEdT") or
                die("Could not connect: " . mysql_error());
        mysql_select_db("cnausa_lms");

        $enrolled_array = explode(',', $enrolled_users);
        $workshop_arr = explode(',', $workshop_users);
        $users = array_merge($enrolled_array, $workshop_arr);

        //print_r($users);
        //die();
        $pure_data = base64_encode($data);
        $total = count($users);
        $query = "insert into mdl_campaign "
                . "(content,"
                . "total,"
                . "processed,"
                . "status,"
                . "type,"
                . "dated) "
                . "values('$pure_data',"
                . "'$total',"
                . "'0',"
                . "'pending',"
                . "'email',"
                . "'" . time() . "')";
        mysql_query($query);
        $camid = mysql_insert_id();

        if (count($users) > 0) {
            foreach ($users as $id) {
                if ($id > 0) {
                    $query = "insert into mdl_campaign_log "
                            . "(camid,"
                            . "userid,"
                            . "status,"
                            . "dated) "
                            . "values ('$camid',"
                            . "'$id',"
                            . "'pending',"
                            . "'" . time() . "')";
                    //echo "Query: " . $query . "<br>";
                    mysql_query($query);
                } // end if $id>0
            } // end foreach
        } // end if count($users)>0
        $list = "Message was added to queue and will be processed soon. ";
        return $list;
    }

    function get_campaign_stat($id) {
        $list = "";
        $query = "select * from mdl_campaign where id=$id";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $content = $row['content'];
            $total = $row['total'];
            $processed = $row['processed'];
        }

        $list.="<div class='container-fluid'  style='text-align:left;'>";
        $list.="<span class='span6'>$content</span>";
        $list.="<span class='span3'>Total users: $total <br> Processed users: $processed</span>";
        $list.="</div>";

        return $list;
    }

    function get_campaign_content($id) {
        $query = "select * from mdl_campaign where id=$id";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $content = $row['content'];
        }
        return $content;
    }

    function update_campaign_status($camid) {
        $query = "select count(id) as total "
                . "from mdl_campaign_log "
                . "where camid=$camid and status='ok'";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $processed = $row['total'];
        }

        $query = "update mdl_campaign set processed=$processed where id=$camid";
        $this->db->query($query);
    }

    function get_user_message($detailes, $content) {
        $list = "";
        $list.="<html>";
        $list.="<body>";
        $list.="<p align='center'>Dear $detailes->firstname $detailes->lastname!</p>";
        $list.="<p align='justify'>$content</p>";
        $list.="<p align='justify'>Best regards,</p>";
        $list.="<p align='justify'>Medical2 team.</p>";
        $list.="</body>";
        $list.="</html>";
        return $list;
    }

    function send_email($camid, $userid) {
        $content = $this->get_campaign_content($camid);
        $user_details = $this->get_user_details($userid);
        $user_details->email = "sirromas@gmail.com"; // temp workaroud 
        $message = $this->get_user_message($user_details, $content);
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->Host = $this->mail_smtp_host;
        $mail->SMTPAuth = true;
        $mail->Username = $this->mail_smtp_user;
        $mail->Password = $this->mail_smtp_pwd;
        $mail->SMTPSecure = 'tls';
        $mail->Port = $this->mail_smtp_port;
        $mail->setFrom($this->mail_smtp_user, 'Medical2 Career College');
        $mail->addAddress($user_details->email);
        $mail->addReplyTo($this->mail_smtp_user, 'Medical2 Career College');
        $mail->isHTML(true);
        $mail->Subject = 'Medical2 Career College';
        $mail->Body = $message;
        if (!$mail->send()) {
            $query = "update mdl_campaign_log "
                    . "set status='failed' "
                    . "where camid=$camid "
                    . "and userid=$userid";
            $this->db->query($query);
        } // end if !$mail->send()        
        else {
            $query = "update mdl_campaign_log "
                    . "set status='ok' "
                    . "where camid=$camid "
                    . "and userid=$userid";
            $this->db->query($query);
            $this->update_campaign_status($camid);
        } // end else        
    }

    function process_emails() {
        $query = "select * from mdl_campaign_log "
                . "where status='pending' "
                . "order by dated desc limit 0,1";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $camid = $row['camid'];
                $userid = $row['userid'];
            } // end while
            $this->send_email($camid, $userid);
        } // end if $num > 0
    }

    function get_user_states() {
        $list = "";

        $list.="<select id='user_states' style='width:275px;'>";
        $list.="<option value='0' selected>Select state</option>";
        $query = "select * from mdl_states order by state";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $list.="<option value='" . $row['state'] . "'>" . $row['state'] . "</option>";
        }
        $list.= "</select>";

        $box.="<span class='span3'>State:</span>";
        $box.="<span class='span3'>$list</span>";

        return $box;
    }

    function get_state_code($state_name) {
        $query = "select * from mdl_states "
                . "where upper(state)='" . strtoupper($state_name) . "'";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $code = $row['code'];
        }
        return $code;
    }

    function get_user_cities($state_name, $slotid = null) {
        $list = "";
        $box = "";
        $code = $this->get_state_code($state_name);
        $list.="<select id='user_cities' style='width:275px;'>";
        $list.="<option value='0' selected>Select city</option>";
        $query = "select * from mdl_user_cities where state_code='$code'";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $list.="<option value='" . $row['city'] . "'>" . $row['city'] . "</option>";
        } // end while
        $list.= "</select>";

        $box.="<span class='span3'>City:</span>";
        $box.="<span class='span4'>$list</span>";


        return $box;
    }

    function get_campaign_users($cr) {

        /*
          echo "<pre>";
          print_r($cr);
          echo "</pre>";
         */

        $num = 0;
        $list = "";
        $box = "";
        $list.="<select multiple id='camp_users' style='width:275px;'>";
        $list.="<option value='0' selected>Select user</option>";

        if ($cr->slotid > 0) {
            $query = "select * from mdl_scheduler_appointment "
                    . "where slotid=$cr->slotid";
            $num = $this->db->numrows($query);
            if ($num > 0) {
                $result = $this->db->query($query);
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $user = $this->get_user_details($row['studentid']);
                    $list.="<option value='" . $row['studentid'] . "'>$user->firstname $user->lastname</option>";
                } // end while
            } // end if $num > 0
        } // end if $cr->slotid>0
        else {
            if (is_numeric($cr->state_name) && is_numeric($cr->city_name)) {
                $context = $this->get_course_context($cr->courseid);
                $query = "select * from mdl_role_assignments "
                        . "where contextid=$context and roleid=5";
                //echo "Query: " . $query;
                $num = $this->db->numrows($query);
                if ($num > 0) {
                    $result = $this->db->query($query);
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        $user = $this->get_user_details($row['userid']);
                        $list.="<option value='" . $row['userid'] . "'>$user->firstname $user->lastname</option>";
                    } // end while
                } // end if $num > 0
            }

            if (!is_numeric($cr->state_name) && is_numeric($cr->city_name)) {
                $query = "select * from mdl_user "
                        . "where UPPER(state)='" . strtoupper($cr->state_name) . "'";
                //echo "Query: " . $query;
                $num = $this->db->numrows($query);
                if ($num > 0) {
                    $result = $this->db->query($query);
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        $list.="<option value='" . $row['id'] . "'>" . $row['firstname'] . " " . $row['lastname'] . "</option>";
                    } // end while
                } // end if $num > 0
            }

            if (!is_numeric($cr->state_name) && !is_numeric($cr->city_name)) {
                $query = "select * from mdl_user "
                        . "where UPPER(city)='" . strtoupper($cr->city_name) . "'";
                //echo "Query: " . $query;
                $num = $this->db->numrows($query);
                if ($num > 0) {
                    $result = $this->db->query($query);
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        $list.="<option value='" . $row['id'] . "'>" . $row['firstname'] . " " . $row['lastname'] . "</option>";
                    } // end while
                } // end if $num > 0
            }
        } // end else
        $list.="</select>";

        $box.="<span class='span3'>Students ($num):</span>";
        $box.="<span class='span4'>$list</span>";


        return $box;
    }

    function get_add_new_campaigner_dialog($users) {
        $list = "";
        $users_list = implode(',', $users);
        $list.="<div id='myModal' class='modal fade'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h4 class='modal-title'>Add New Campaign</h4>
                </div>
                <div class='modal-body'>
                <input type='hidden' id='users' value='$users_list'>
                    
                <div class='container-fluid' style='text-align:center;'>
                 <textarea id='campaign_text' rows='5' style='width:475px;'></textarea>
                </div>
                
                <div class='container-fluid' style='text-align:center;'>
                 <span class='span6' id='campaign_err'></span>
                </div>
             
                <div class='modal-footer' style='text-align:center;'>
                    <span align='center'><button type='button' class='btn btn-primary' data-dismiss='modal' id='cancel'>Cancel</button></span>
                    <span align='center'><button type='button' class='btn btn-primary' id='create_new_campaign_done'>OK</button></span>
                </div>
            </div>
        </div>
    </div>";

        return $list;
    }

    function get_add_campaign_dialog($userslist) {
        $list = "";
        $list.="<div id='myModal' class='modal fade' style='width:975px;height:575px;left:35%;'>
        <div class='modal-dialog'>
            <div class='modal-content' style='min-height:575px;'>
                <div class='modal-header'>
                    <h4 class='modal-title'>Add New Campaign</h4>
                </div>
                <div class='modal-body' style='height:970px;min-height:575px;'>
                <input type='hidden' id='users' value='$userslist'>
                    
                <div class='container-fluid' style='text-align:center;'>
                 <textarea id='campaign_text' rows='5' style='width:475px;'></textarea>
                <script>
                CKEDITOR.replace('campaign_text');
                </script>
                </div>
                
                <div class='container-fluid' style='text-align:center;'>
                 <span class='span6' id='campaign_err'></span>
                </div>
             
                <div class='modal-footer' style='text-align:center;'>
                    <span align='center'><button type='button' class='btn btn-primary' data-dismiss='modal' id='cancel'>Cancel</button></span>
                    <span align='center'><button type='button' class='btn btn-primary' id='create_new_campaign_done'>OK</button></span>
                </div>
            </div>
        </div>
    </div>";

        return $list;
    }

    function add_new_campaign2($data, $users) {
        mysql_connect("localhost", "cnausa_lms", "^pH+F8*[AEdT") or
                die("Could not connect: " . mysql_error());
        mysql_select_db("cnausa_lms");

        $users_arr = explode(',', $users);
        $pure_data = base64_encode($data);
        $total = count($users_arr);
        $query = "insert into mdl_campaign "
                . "(content,"
                . "total,"
                . "processed,"
                . "status,"
                . "type,"
                . "dated) "
                . "values('$pure_data',"
                . "'$total',"
                . "'0',"
                . "'pending',"
                . "'email',"
                . "'" . time() . "')";
        mysql_query($query);
        $camid = mysql_insert_id();

        if (count($users_arr) > 0) {
            foreach ($users_arr as $id) {
                if ($id > 0) {
                    $query = "insert into mdl_campaign_log "
                            . "(camid,"
                            . "userid,"
                            . "status,"
                            . "dated) "
                            . "values ('$camid',"
                            . "'$id',"
                            . "'pending',"
                            . "'" . time() . "')";
                    //echo "Query: " . $query . "<br>";
                    mysql_query($query);
                } // end if $id>0
            } // end foreach
        } // end if count($users)>0
        $list = "New campaign was put into queue and will be processed soon.";
        return $list;
    }

    function get_users_by_state($state) {
        $users = array();
        $query = "select * from mdl_user "
                . "where deleted=0 "
                . "and state<>'' "
                . "and state='$state'";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $users[] = $row['id'];
            } // end while
        } // end if $num > 0
        return $users;
    }

    function get_users_by_city($city) {
        $users = array();
        $query = "select * from mdl_user "
                . "where deleted=0 "
                . "and city<>'' "
                . "and city='$city'";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $users[] = $row['id'];
            } // end while
        } // end if $num > 0
        return $users;
    }

    function get_users_slot($ws) {
        $query = "select * from mdl_scheduler_slots where "
                . "FROM_UNIXTIME(starttime, '%m-%d-%Y')='$ws->date' "
                . "and appointmentlocation='$ws->location'";
        //echo "Query: " . $query . "<br>";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $slotid = $row['id'];
            } // end while
        } // end if $num > 0
        else {
            $slotid = 0;
        } // end else
        return $slotid;
    }

    function get_slot_desc($slotid) {
        $query = "select * from mdl_scheduler_slots where id=$slotid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $ws = new stdClass();
                foreach ($row as $key => $value) {
                    $ws->$key = $value;
                } // end foreach
            } // end while
        } // end if
        else {
            $ws = new stdClass();
        }
        return $ws;
    }

    function get_users_by_workshop($ws) {
        $users = array();
        $slotid = $this->get_users_slot($ws);
        if ($slotid > 0) {
            $query = "select * from mdl_scheduler_appointment "
                    . "where slotid=$slotid";
            //echo "Query: " . $query . "<br>";
            $num = $this->db->numrows($query);
            if ($num > 0) {
                $result = $this->db->query($query);
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $is_deleted = $this->is_user_deleted($row['studentid']);
                    if ($is_deleted == 0) {
                        $users[] = $row['studentid'];
                    } // end if $is_deleted==0
                } // end while
            } // end if $num > 0
        } // end if $slotid>0
        return $users;
    }

    function get_promotion_users($s) {
        $list = "";
        $state = $s->state;
        $city = $s->city;
        $ws = $s->workshop;
        $ws_data = explode('--', $ws);
        $ws_date = $ws_data[0];
        $ws_location = $ws_data[1];

        /*
          echo "User state: " . $state . "<br>";
          echo "User city: ". $city . "<br>";
          echo "Workshop date: " . $ws_date . "<br>";
          echo "Workhop location: " . $ws_location . "<br>";
         * 
         */


        if ($ws_date != '') {
            $workshop = new stdClass();
            $workshop->date = $ws_date;
            $workshop->location = $ws_location;
            $users = $this->get_users_by_workshop($workshop);
        } // end if $ws_date!=''
        else {
            if ($city != '') {
                $users = $this->get_users_by_city($city);
            } // end if $city!=''
            else {
                $users = $this->get_users_by_state($state);
            } // end else
        } // end else
        $list.=$this->create_users_page($users);
        return $list;
    }

    function get_user_workshop($userid) {
        $list = "";
        $query = "select * from mdl_scheduler_appointment "
                . "where studentid=$userid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $ws = $this->get_slot_desc($row['slotid']);
                if ($ws->starttime != '') {
                    $date = date('m-d-Y', $ws->starttime);
                    $list.="<div class='row-fluid'>";
                    $list.="<span class='span12'>$date--$ws->appointmentlocation</span>";
                    $list.="</div>";
                } // end if $ws->starttime != ''
            } // end while
        } // end if $num > 0
        else {
            $list.="<div class='row-fluid'>";
            $list.="<span class='span1'>N/A</span>";
            $list.="</div>";
        } // end else
        return $list;
    }

    function create_users_page($users) {
        $list = "";

        if (count($users) > 0) {
            $list.="<div class='row-fluid' style='font-weight:bold;padding-left:17px;'>";
            $list.="<span class='span1'><input type='checkbox' id='select_all_camp' value='select_all'></span>";
            $list.="<span class='span2'>User</span>";
            $list.="<span class='span2'>State</span>";
            $list.="<span class='span2'>City</span>";
            $list.="<span class='span5'>Workshop</span>";
            $list.="</div>";

            $list.="<div class='row-fluid' style='font-weight:bold;text-align:center;'>";
            $list.="<span class='span11'>Total found: " . count($users) . "</span>";
            $list.="</span>";

            $list.="<div class='row-fluid'>";
            $list.="<span class='span11'><hr/></span>";
            $list.="</div>";

            foreach ($users as $userid) {
                $userdata = $this->get_user_details($userid);
                $ws = $this->get_user_workshop($userid);
                $list.="<div class='row-fluid'>";
                $list.="<span class='span1'><input type='checkbox' class='camp_users' value='$userid'></span>";
                $list.="<span class='span2'><a href='https://" . $_SERVER['SERVER_NAME'] . "/lms/user/profile.php?id=$userid' target='_blank'>$userdata->firstname $userdata->lastname</a></span>";
                $list.="<span class='span2'>$userdata->state</span>";
                $list.="<span class='span2'>$userdata->city</span>";
                $list.="<span class='span5'>$ws</span>";
                $list.="</div>";

                $list.="<div class='row-fluid'>";
                $list.="<span class='span11'><hr/></span>";
                $list.="</div>";
            } // end foreach
        } // end if count($users)>0
        else {
            $list.="<div class='row-fluid'>";
            $list.="<span class='span6'>No users found</span>";
            $list.="</div>";
        }
        return $list;
    }

}
