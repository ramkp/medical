<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php';

Class Codes extends Util {

    public $limit = 3;
    public $student_role = 5;

    function __construct() {
        parent::__construct();
    }

    function create_promotion_users() {
        $users = array();
        $query = "select * from mdl_code2course order by added desc";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $ids[] = $row['userid'];
            } // end while
            foreach ($ids as $id) {
                if ($id > 0) {
                    $query = "select * from mdl_user where id=$id";
                    $result = $this->db->query($query);
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        $users[] = mb_convert_encoding($row['lastname'], 'UTF-8') . " " . mb_convert_encoding($row['firstname'], 'UTF-8');
                    } // end while
                } // end if $id>0
            } // end foreach
        } // end if $num > 0
        file_put_contents('/home/cnausa/public_html/lms/custom/utils/promo_users.json', json_encode($users));
    }

    function get_promotion_codes_page() {
        $list = "";
        $codes = array();
        $this->create_promotion_users();
        $query = "select c.id,c.code,c.type,c.amount,c.date1,c.date2,c.used, "
                . "c.added,co.courseid,co.userid,co.codeid "
                . "from mdl_code c, mdl_code2course co where c.id=co.codeid "
                . "order by c.added desc limit 0, $this->limit";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $code = new stdClass();
                foreach ($row as $key => $value) {
                    $code->$key = $value;
                } // end foreach
                $codes[] = $code;
            } // end while
        } // end if $num > 0
        $list.=$this->create_codes_page($codes);
        return $list;
    }

    function get_code_tip($id) {
        $list = "";
        $query = "select * from mdl_code where id=$id";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $type = $row['type'];
            $amount = $row['amount'];
            $code = $row['code'];
            $date1 = date('m-d-Y', $row['date1']);
            $date2 = date('m-d-Y', $row['date2']);
        }
        $period = $date1 . "<br>" . $date2;
        $prefix = ($type == 'amount') ? 'Discount: $' . $amount . ' off' : 'Discount: ' . $amount . ' %';
        $list.=$code . "<br>" . $prefix . "<br>" . $period;
        return $list;
    }

    function create_codes_page($codes, $toolbar = true, $search = false) {
        $list = "";

        $list.="<div id='promo_page_container'>";
        if ($toolbar) {
            $list.="<div class='row-fluid' style='font-weight:bold;'>";
            $list.="<span class='span1' style='padding-left:18px;'><button id='add_new_promo_code'>Add</button></span>";
            $list.="</div>";

            $list.="<div class='container-fluid' style='display:none;text-align:center;' id='ajax_loader'>";
            $list.="<span class='span10'><img src='https://$this->host/assets/img/ajax.gif' /></span>";
            $list.="</div>";

            $list.="<div class='row-fluid'>";
            $list.="<span class='span9' style='color:red;' id='promo_err'></span>";
            $list.="</div>";
        } // end if $toolbar
        // ************** Main codes container **************
        $list.="<div id='codes_container'>";
        if (count($codes) > 0) {

            /*
              echo "<pre>";
              print_r($codes);
              echo "</pre><br>";
             */

            $list.="<div class='row-fluid' style='font-weight:bold;'>";
            $list.="<span class='span2'>Code</span>";
            $list.="<span class='span2'>Program</span>";
            $list.="<span class='span2'>User</span>";
            $list.="<span class='span2'>Date1</span>";
            $list.="<span class='span2'>Date2</span>";
            $list.="<span class='span1' style='text-align:center;'>Used</span>";
            $list.="<span class='span1' style='text-align:center;'>Ops</span>";
            $list.="</div>";
            foreach ($codes as $c) {
                if ($c->courseid == 0) {
                    $coursename = 'All programs';
                } // end if $c->courseid==0
                else {
                    $coursename = $this->get_course_name($c->courseid);
                }
                $user = ($c->userid == 0) ? 'All users' : $this->get_user_details($c->userid);
                if ($c->userid == 0) {
                    $link = 'All users';
                } // end if $c->userid == 0
                else {
                    $user = $this->get_user_details($c->userid);
                    $link = "<a href='https://medical2.com/lms/user/profile.php?id=$c->userid' target='_blank'>$user->firstname $user->lastname</a>";
                } // end else
                $tip = $this->get_code_tip($c->id);
                $date1 = date('m-d-Y', $c->date1);
                $date2 = date('m-d-Y', $c->date2);
                $used = ($c->used == 0) ? 'No' : 'Yes';
                $list.="<div class='row-fluid'>";
                $list.="<span class='span2'>$tip</span>";
                $list.="<span class='span2'>$coursename</span>";
                $list.="<span class='span2'>$link</span>";
                $list.="<span class='span2'>$date1</span>";
                $list.="<span class='span2'>$date2</span>";
                $list.="<span class='span1' style='text-align:center;'>$used</span>";
                if ($c->used == 0) {
                    $list.="<span class='span1' style='text-align:center;'><img style='cursor:pointer;' id='del_code_$c->id' data-id=$c->id src='https://medical2.com/lms/theme/image.php/lambda/core/1468523658/t/delete' title='Delete Code'></span>";
                } // end if $c->used == 0
                else {
                    $list.="<span class='span1' style='text-align:center;'>&nbsp;</span>";
                } // end else
                $list.="</div>";
                $list.="<div class='row-fluid'>";
                $list.="<span class='span12'><hr/></a>";
                $list.="</div>";
            } // end foreach
        } // end if count($codes)>0
        else {
            $list.="<div class='row-fluid'>";
            $list.="<span class='span9'>There are no any promotion codes in the system</a>";
            $list.="</div>";
        } // end else
        $list.="</div>"; // end of codes_container
        if ($toolbar) {
            $list.="<br><div class='container-fluid'>";
            $list.="<span class='span9' id='pagination'></span>";
            $list.="</div>";
        }
        $list.="</div>"; // end of promo_page_container
        return $list;
    }

    function get_course_name($courseid) {
        if ($courseid > 0) {
            $query = "select fullname from mdl_course where id=$courseid";
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $name = $row['fullname'];
            }
        } // end if $courseid>0
        else {
            $name = 'N/A';
        } // end else
        return $name;
    }

    function get_total_codes() {
        $query = "select count(id) as total from mdl_code";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $total = $row['total'];
        }
        return $total;
    }

    function get_code_item($page) {
        $codes = array();
        $rec_limit = $this->limit;
        if ($page == 1) {
            $offset = 0;
        } // end if $page==1
        else {
            $page = $page - 1;
            $offset = $rec_limit * $page;
        }
        $query = "select c.id,c.code,c.type,c.amount,c.date1,c.date2,c.used, "
                . "c.added,co.courseid,co.userid,co.codeid "
                . "from mdl_code c, mdl_code2course co where c.id=co.codeid "
                . "order by c.added desc limit $offset, $rec_limit";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $c = new stdClass();
            foreach ($row as $key => $value) {
                $c->$key = $value;
            } // end foreach
            $codes[] = $c;
        } // end while
        $list = $this->create_codes_page($codes, false, false);
        return $list;
    }

    function create_users_json_data() {
        $query = "select * from mdl_user";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $users[] = mb_convert_encoding($row['lastname'], 'UTF-8') . " " . mb_convert_encoding($row['firstname'], 'UTF-8');
        }
        file_put_contents('/home/cnausa/public_html/lms/custom/utils/users.json', json_encode($users));
    }

    function get_code_numbers_dropdown() {
        $list = "";
        $list.="<select id='code_total' style='width:55px;'>";
        $list.="<option value='1' slelected>1</option>";
        for ($i = 2; $i <= 100; $i++) {
            $list.="<option value='$i'>$i</option>";
        }
        $list.="</select>";
        return $list;
    }

    function get_add_campaign_dialog($userslist, $program) {
        $list = "";
        $list.="<div id='myModal' class='modal fade' style='width:975px;height:575px;left:35%;'>
        <div class='modal-dialog'>
            <div class='modal-content' style='min-height:575px;'>
                <div class='modal-header'>
                    <h4 class='modal-title'>Add New Campaign</h4>
                </div>
                <div class='modal-body' style='height:970px;min-height:575px;'>
                
                <input type='hidden' id='users' value='$userslist'>
                <input type='hidden' id='program' value='$program'>    
                    
                <div class='container-fluid' style='text-align:center;'>
                <span class='span6' ><input type='text' id='campaign_title' style='width:860px' placeholder='Title'></span>
                </div>
                
                <div class='container-fluid' style='text-align:center;'>
                <textarea id='campaign_text' rows='3' style='width:475px;'></textarea>
                <script>
                CKEDITOR.replace('campaign_text');
                </script>
                </div>
               
                <div class='container-fluid' style='text-align:center;'>
                 <span class='span6' id='campaign_err'></span>
                </div>
                
                <div class='row-fliud'>
                <span style='padding-left:15px;'><button type='button' class='btn btn-primary' data-dismiss='modal' id='cancel'>Cancel</button></span>
                <span><button type='button' class='btn btn-primary' id='add_new_promo_code_campaign'>OK</button></span>
                </div>
             </div>
        </div>
    </div>";

        return $list;
    }

    function get_add_promo_code_page() {
        $list = "";
        //$this->create_users_json_data();
        $total = $this->get_code_numbers_dropdown();
        $list.="<div class='row-fluid'>";
        $list.="<span class='span3'><button id='back_to_promo_page'>Back</button></span>";
        $list.="</div>";

        $list.="<div class='row-fluid'>";
        $list.="<span class='span12'><hr></span>";
        $list.="</div>";

        // ******************* Toolbar *******************

        $list.="<div class='row-fluid' style='display:block;' id='course_promotions'>";
        //$list.="<span class='span3'><input type='text' id='promo_program' style='width:200px' placeholder='All Programs'></span>";
        $list.="<span class='span1'><input type='text' id='promo_date1' style='width:45px' placeholder='Date1'></span>";
        $list.="<span class='span1'><input type='text' id='promo_date2' style='width:45px' placeholder='Date2'></span>";
        $list.="<span class='span2' style='text-align:left;'>Discount: &nbsp;";
        $list.="<input type='radio' name='discount' value='amount' checked>$ &nbsp;<input type='radio' name='discount' value='percent'>% </span>";
        $list.="<span class='span2'><input type='text' id='amount' style='width:50px;'>&nbsp;&nbsp; $total</span>";
        $list.="<span class='span6'>When creating message please do not forget to include {code} snipplet</span>";
        //$list.="<span class='span1'><button id='add_new_codes'>Add</button></span>";
        $list.="</div>";

        $list.="<div class='row-fluid' style='font-weight:bold;'>";
        $list.="<span class='span2'><input type='text' id='camp_program' style='width:125px' placeholder='Program'></span>";
        $list.="<span class='span2'><input type='text' id='camp_state' style='width:125px' placeholder='State'></span>";
        $list.="<span class='span2'><input type='text' id='camp_city' style='width:125px' placeholder='City'></span>";
        $list.="<span class='span1'><button id='camp_search'>Search</button></span>";
        $list.="<span class='span1' style='padding-left:15px;'><button id='clear_code_search'>Clear</button></span>";
        $list.="<span class='span1' style='padding-left:18px;'><button id='add_new_codes'>Add</button></span>";
        $list.="<span class='span1' style='padding-left:18px;'><button id='send_new_codes'>Send</button></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='display:none;text-align:center;' id='ajax_loader'>";
        $list.="<span class='span10'><img src='https://$this->host/assets/img/ajax.gif' /></span>";
        $list.="</div>";

        $list.="<div class='row-fluid'>";
        $list.="<span class='span9' id='promo_err'></span>";
        $list.="</div>";

        $list.="<div id='camp_users_container'></div>";

        $list.="<div class='container-fluid' style='display:none;text-align:center;' id='ajax_loader'>";
        $list.="<span class='span9'><img src='https://$this->host/assets/img/ajax.gif' /></span>";
        $list.="</div>";

        // ******************* Users container *******************
        $list.="<div class='row-fluid'>";
        $list.="<span class='span9' id='promo_course_users'></span>";
        $list.="</div>";

        return $list;
    }

    function get_program_id($name) {
        $query = "select * from mdl_course where fullname='$name'";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $id = $row['id'];
        }
        return $id;
    }

    function add_new_campaign($title, $text) {
        $date = time();
        $clear_title = base64_encode($title);
        $clear_text = base64_encode($text);
        $query = "insert into mdl_code_campaign "
                . "(title,content,added) "
                . "values ('$clear_title','$clear_text','$date') ";
        $this->db->query($query);
        $stmt = $this->db->query("SELECT LAST_INSERT_ID()");
        $lastid_arr = $stmt->fetch(PDO::FETCH_NUM);
        $campid = $lastid_arr[0];
        return $campid;
    }

    function add_promo_codes_for_send($c) {
        $courseid = $this->get_program_id($c->program);
        if ($courseid > 0) {
            $users = explode(',', $c->users);
            if (count($users) > 0) {
                $campid = $this->add_new_campaign($c->title, $c->text);
                foreach ($users as $userid) {
                    if ($userid != 'select_all') {
                        $date = time();
                        $d1u = strtotime($c->date1);
                        $d2u = strtotime($c->date2);
                        $code = $this->generateRandomString(6);
                        $query = "insert into mdl_code "
                                . "(code,"
                                . "type,"
                                . "amount,"
                                . "date1,"
                                . "date2,"
                                . "campid,"
                                . "added) "
                                . "values ('$code',"
                                . "'$c->type',"
                                . "'$c->amount',"
                                . "'$d1u',"
                                . "'$d2u',"
                                . "'$campid',"
                                . "'$date')";
                        $this->db->query($query);
                        $stmt = $this->db->query("SELECT LAST_INSERT_ID()");
                        $lastid_arr = $stmt->fetch(PDO::FETCH_NUM);
                        $codeid = $lastid_arr[0];

                        $query = "insert into mdl_code2course "
                                . "(courseid,"
                                . "slotid,"
                                . "userid,"
                                . "codeid,"
                                . "added) values ($courseid,'0',$userid,$codeid,'$date')";
                        $this->db->query($query);
                    } // end if $userid!='select_all'
                } // end foreach
            } // end if count($users)>0
        } // end if $courseid>0
    }

    function generateRandomString($length = 25) {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
    }

    function get_program_users($courseid) {
        $users = array();
        $instanceid = $this->get_course_context($courseid);
        $query = "select id, roleid, contextid, userid "
                . "from mdl_role_assignments "
                . "where roleid=$this->student_role and contextid=$instanceid";
        //echo "Query: ".$query."<br>";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $status = $this->is_user_deleted($row['userid']);
                if ($status == 0) {
                    $users[] = $row['userid'];
                } // end if $status == 0
            } // end while
        } // end if $num > 0
        return $users;
    }

    function add_promo_codes($c) {

        /*
          echo "<pre>";
          print_r($c);
          echo "</pre>";
          die();
         */

        /*
         * 
          [program] =>
          [type] => percent
          [amount] => 1
          [date1] => 03/05/2017
          [date2] => 03/31/2017
          [total] => 1
          [users] => n/a
          ---------------------------------------
          [program] => Medical Assistant
          [type] => percent
          [amount] => 1
          [date1] => 03/05/2017
          [date2] => 03/31/2017
          [total] => 1
          [users] => 11681,11720,11694
         * 
         */

        $d1u = strtotime($c->date1);
        $d2u = strtotime($c->date2);
        $date = time();
        $total = $c->total;

        if ($c->program == '') {
            $courseid = 0;
        } // end if
        else {
            $courseid = $this->get_program_id($c->program);
        }
        $userid = 0;
        for ($i = 1; $i <= $total; $i++) {
            $code = $this->generateRandomString(6);
            $query = "insert into mdl_code "
                    . "(code, type, amount, "
                    . "date1,"
                    . "date2,"
                    . "added) "
                    . "values ('$code','$c->type', '$c->amount','$d1u','$d2u', '$date')";
            $this->db->query($query);
            $stmt = $this->db->query("SELECT LAST_INSERT_ID()");
            $lastid_arr = $stmt->fetch(PDO::FETCH_NUM);
            $codeid = $lastid_arr[0];
            $query = "insert into mdl_code2course "
                    . "(courseid,"
                    . "slotid,"
                    . "userid,"
                    . "codeid,"
                    . "added) values ($courseid,'0',$userid,$codeid,'$date')";
            $this->db->query($query);
        } // end for
    }

    function get_user_details($id) {
        $query = "select * from mdl_user where id=$id";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $user = new stdClass();
            foreach ($row as $key => $value) {
                $user->$key = $value;
            } // end if $row['firstname'] != '' && $row['lastname'] != ''
        } // end while
        return $user;
    }

    function get_promo_course_users($id) {
        $list = "";
        $users = $this->get_program_users($id);
        if (count($users > 0)) {
            foreach ($users as $userid) {
                $user = $this->get_user_details($userid);
                if ($user->firstname != '' && $user->lastname != '') {
                    $list.="<div class='row-fluid'>";
                    $list.="<span class='span1'><input type='checkbox' class='promo_user' data-userid='$userid'></span>";
                    $list.="<span class='span3'><a href='https://medical2.com/lms/user/profile.php?id=$userid' target='_blank'>$user->firstname $user->lastname</a></span>";
                    $list.="</div>";
                } // end if 
            } // end foreach
        } // end if count($users>0)
        return $list;
    }

    function del_code($id) {
        $query = "delete from mdl_code2course where codeid=$id";
        $this->db->query($query);

        $query = "delete from mdl_code where id=$id";
        $this->db->query($query);
    }

    function get_code_courseid($codeid) {
        $query = "select * from mdl_code2course where codeid=$codeid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $courseid = $row['courseid'];
        }
        return $courseid;
    }

    function verify_used_code($code) {
        $query = "select * from mdl_code "
                . "where code='$code' "
                . "and used=1";
        $num = $this->db->numrows($query);
        return $num;
    }

    function is_code_exists($courseid, $code, $used = false) {
        if ($used) {
            $status = $this->verify_used_code($code);
        } // end if $used
        else {
            $query = "select * from mdl_code "
                    . "where code='$code' "
                    . "and used=0";
            $num = $this->db->numrows($query);
            if ($num > 0) {
                $now = time();
                $codedata = $this->get_code_details($code);
                $date1 = $codedata->date1;
                $date2 = $codedata->date2 + 86400; // we add date ahead ...
                $codecourseid = $this->get_code_courseid($codedata->id);
                if ($codecourseid == 0) {
                    $status = 1;
                } // end if $codecourseid==0
                else {
                    if ($now >= $date1 and $now <= $date2) {
                        $status = 1;
                    } // end if $now >= $date1 and $now <= $date2
                    else {
                        $status = 0;
                    } // end else
                } // end else
            } // end if $num>0
            else {
                $status = 0;
            } // end else
        } // end else
        return $status;
    }

    function get_code_details($code) {
        $query = "select * from mdl_code where code='$code'";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $item = new stdClass();
            foreach ($row as $key => $value) {
                $item->$key = $value;
            } // end foreach
        } // end while
        return $item;
    }

    function update_registration_code($c) {

        /*
          echo "<pre>";
          print_r($c);
          echo "</pre>";
         */

        $newprice = 0;
        $code = trim($c->code);
        $status = $this->is_code_exists($c->courseid, $code);
        //echo "Status: " . $status . "<br>";
        if ($status > 0) {
            $codedata = $this->get_code_details($code);
            if ($codedata->type == 'amount') {
                $newprice = (int) $c->amount - (int) $codedata->amount;
            } // end if
            else {
                $newprice = (int) $c->amount - (int) ($c->amount * $codedata->amount) / 100;
            } // end else
        } // end if $status>0
        //echo "New price: " . $newprice;

        return round($newprice);
    }

}
