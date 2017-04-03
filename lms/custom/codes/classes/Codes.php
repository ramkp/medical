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
        }
        $prefix = ($type == 'amount') ? 'Discount: $' . $amount . ' off' : 'Discount: ' . $amount . ' %';
        $list.=$code . "<br>" . $prefix;
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
        $list.="<select id='code_total'>";
        $list.="<option value='1' slelected>1</option>";
        for ($i = 2; $i <= 100; $i++) {
            $list.="<option value='$i'>$i</option>";
        }
        $list.="</select>";
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
        $list.="<span class='span3'><input type='text' id='promo_program' style='width:200px' placeholder='All Programs'></span>";
        $list.="<span class='span1'><input type='text' id='promo_date1' style='width:45px' placeholder='Date1'></span>";
        $list.="<span class='span1'><input type='text' id='promo_date2' style='width:45px' placeholder='Date2'></span>";
        $list.="<span class='span3'>Discount: &nbsp;";
        $list.="<input type='radio' name='discount' value='amount' checked>$ &nbsp;&nbsp; <input type='radio' name='discount' value='percent'>% &nbsp;";
        $list.="<input type='text' id='amount' style='width:50px;'></span>";
        $list.="<span class='span1'>$total</span>";
        $list.="<span class='span1'><button id='add_new_codes'>Add</button></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='display:none;text-align:center;' id='ajax_loader'>";
        $list.="<span class='span9'><img src='https://$this->host/assets/img/ajax.gif' /></span>";
        $list.="</div>";

        $list.="<div class='row-fluid'>";
        $list.="<span class='span9' id='promo_err'></span>";
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
         *  
          echo "<pre>";
          print_r($c);
          echo "</pre>";
          //die();
         * 
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
            //echo "Inside when no users selected ...";
            for ($i = 1; $i <= $c->total; $i++) {
                $code = $this->generateRandomString(6);
                $courseid = 0;
                $userid = 0;
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
        } // end if $c->program==''
        else {
            //echo "Inside when program selected ...";
            $courseid = $this->get_program_id($c->program);
            $users = $c->users;
            if ($users != '') {
                //echo "Inside when users selected ....";
                $users_arr = explode(',', $users);
                foreach ($users_arr as $userid) {
                    $code = $this->generateRandomString(6);
                    $query = "insert into mdl_code "
                            . "(code, type, amount, "
                            . "date1,"
                            . "date2,"
                            . "added) "
                            . "values ('$code','$c->type', '$c->amount','$d1u','$d2u', '$date')";
                    //echo "Query: ".$query."<br>";
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
                    //echo "Query: ".$query."<br>";
                    $this->db->query($query);
                } // end foreach
            } // end if $users!=''
            else {
                //echo "Inside when no users selected ...";
                for ($i = 1; $i <= $c->total; $i++) {
                    $code = $this->generateRandomString(6);
                    $userid = 0;
                    $query = "insert into mdl_code "
                            . "(code, type, amount, "
                            . "date1,"
                            . "date2,"
                            . "added) "
                            . "values ('$code','$c->type', '$c->amount','$d1u','$d2u', '$date')";
                    //echo "Query: ".$query."<br>";
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
                    //echo "Query: ".$query."<br>";
                    $this->db->query($query);
                } // end for
            } // end else when no users selected
        } // end else when name is not empty
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
            $now = time();
            $query = "select * from mdl_code "
                    . "where code='$code' "
                    . "and used=0 and $now between date1 and date2";
            $num = $this->db->numrows($query);
            //echo "Num: " . $num . "<br>";
            if ($num > 0) {
                $result = $this->db->query($query);
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $id = $row['id'];
                } // end while
                $codecourseid = $this->get_code_courseid($id);
                if ($codecourseid == 0) {
                    $status = 1;
                } // end if $codecourseid==0
                else {
                    $query = "select * from mdl_code2course "
                            . "where courseid=$courseid and codeid=$id";
                    //echo "Query: " . $query . "<br>";
                    $coursenum = $this->db->numrows($query);
                    $status = ($coursenum > 0) ? 1 : 0;
                }
            } // end if $num>0
            else {
                $status = 0;
            }
        } // end else
        return $status;
    }

    function get_code_details($code) {
        $query = "select * from mdl_code where code='$code'";
        //echo "Query: ".$query."<br>";
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
        $newprice = 0;
        $code = trim($c->code);
        $status = $this->is_code_exists($c->courseid, $code);

        /*
          echo "<pre>";
          print_r($c);
          echo "</pre>";
         */

        if ($status > 0) {
            $codedata = $this->get_code_details($code);
            if ($codedata->type == 'amount') {
                $newprice = (int) $c->amount - (int) $codedata->amount;
            } // end if
            else {
                $newprice = (int) $c->amount - (int) ($c->amount * $codedata->amount) / 100;
            } // end else
        } // end if $status>0
        //echo "New price: ".$newprice;

        return round($newprice);
    }

}
