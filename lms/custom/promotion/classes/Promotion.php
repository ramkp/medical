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
            $new_campaign = $this->get_add_new_campaigner_block();
            //$campagin_list = $this->get_campaigns_list();

            /*
              $list.="<div class='container-fluid'  style='text-align:center;'>";
              $list.="<span class='span8'>";
              $oFCKeditor = new FCKeditor('editor');
              $oFCKeditor->BasePath = $this->editor_path;
              $oFCKeditor->Value = '';
              $oFCKeditor->Create(false);
              $list.="</span>";
              $list.="</div>";
             */


            $list.="<div class='container-fluid' id='new_campaign_container' style='text-align:center;'>";
            $list.=$new_campaign;
            $list.="</div>";

            $list.="<div class='container-fluid'  style='text-align:center;'>";
            $list.="<span class='span12'><hr/></span>";
            $list.="</div>";

            /*
              $list.="<div class='container-fluid'  style='text-align:left;'>";
              $list.="<span class='span6'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$campagin_list</span>";
              $list.="</div>";

              $list.="<div class='container-fluid' id='campaign_container' style='text-align:left;'>";
              $list.="";
              $list.="</div>";
             */

            $list.="</div>";
        } // end if
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

}
