<?php

require_once ($_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php');

Class Codes extends Util {

    public $limit = 3;

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
        $query = "select * from mdl_code "
                . "order by added desc limit 0, $this->limit";
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

    function create_codes_page($codes, $toolbar = true, $search = false) {
        $list = "";
        if ($this->session->justloggedin == 1) {
            $list.="<div id='promo_page_container'>";
            if ($toolbar) {
                $list.="<div class='row-fluid' style='font-weight:bold;'>";
                //$list.="<span class='span2'><input type='text' id='promo_program' style='width:125px' placeholder='Program'></span>";
                //$list.="<span class='span2'><input type='text' id='promo_user' style='width:125px' placeholder='User'></span>";
                //$list.="<span class='span2'><input type='text' id='promo_date1' style='width:45px' placeholder='Date1'>&nbsp;<input type='text' id='promo_date2' style='width:45px' placeholder='Date2'></span>";
                //$list.="<span class='span1'><button id='promo_code_search'>Search</button></span>";
                //$list.="<span class='span1' style='padding-left:15px;'><button id='promo_reset_search'>Clear</button></span>";
                $list.="<span class='span1' style='padding-left:18px;'><button id='add_new_promo_code'>Add</button></span>";
                $list.="</div>";

                /*
                  $list.="<div class='row-fluid' style='font-weight:bold;'>";
                  $list.="<span class='span2'>&nbsp;</span>";
                  $list.="<span class='span2'>&nbsp;</span>";
                  $list.="<span class='span2'>&nbsp;</span>";
                  $list.="<span class='span3' style=''><button id='promo_send'>Send Promo Codes </button></span>";
                  $list.="</div>";
                 */

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
                $list.="<div class='row-fluid' style='font-weight:bold;'>";
                $list.="<span class='span2'>Code</span>";
                $list.="<span class='span4'>Program</span>";
                $list.="<span class='span3'>User</span>";
                $list.="<span class='span1'>Date1</span>";
                $list.="<span class='span1'>Date2</span>";
                $list.="<span class='span1'>Used</span>";
                $list.="</span>";
                foreach ($codes as $c) {
                    
                } // end foreach
            } // end if count($codes)>0
            else {
                $list.="<div class='row-fluid'>";
                $list.="<span class='span9'>There are no any promotion codes in the system</a>";
                $list.="</div>";
            } // end else
            $list.="</div>"; // end of container div

            if ($toolbar) {
                $list.="<div class='container-fluid'>";
                $list.="<span class='span9' id='pagination'></span>";
                $list.="</div>";
            } // end if toolbar
            $list.="</div>";
        } // end if $this->session->justloggedin == 1
        else {
            $list.="<p>You are not authenticated. &nbsp; <a href='https://medical2.com/login'><button class='btn btn-primary' id='relogin'>Login</button></a></p>";
        }
        return $list;
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
        $query = "select * from mdl_code "
                . "order by added desc LIMIT $offset, $rec_limit";
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

    function get_add_promo_code_page() {
        $list = "";

        $list.="<div class='row-fluid'>";
        $list.="<span class='span3'><button id='back_to_promo_page'>Back</button></span>";
        $list.="</div>";

        $list.="<div class='row-fluid'>";
        $list.="<span class='span12'><hr></span>";
        $list.="</div>";

        // ******************* First step *******************
        $list.="<div class='row-fluid' style='display:block;' id='course_promotions'>";
        $list.="<span class='span3'><input type='text' id='promo_program' style='width:175px' placeholder='Program'></span>";
        $list.="<span class='span2'><input type='text' style='width:125px;' id='program_users' placeholder='All Users'></span>";
        $list.="<span class='span1'><input type='text' id='promo_date1' style='width:45px' placeholder='Date1'></span>";
        $list.="<span class='span1'><input type='text' id='promo_date2' style='width:45px' placeholder='Date2'></span>";
        $list.="<span class='span1'>Discount</span>";
        $list.="<span class='span2'><input type='radio' name='discount' value='amount' checked>$ &nbsp;&nbsp; <input type='radio' name='discount' value='percent'>%</span>";
        $list.="<span class='span1'><input type='text' id='amount' style='width:50px;'></span>";
        $list.="<span class='span1'><button id='add_new_codes'>Add</button></span>";
        $list.="</div>";

        $list.="<div class='row-fluid'>";
        $list.="<span class='span9' id='promo_err'></span>";
        $list.="</div>";


        // ******************* Second step *******************

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

    function add_promo_codes($c) {
        $d1u = strtotime($c->date1);
        $d2u = strtotime($c->date2);
        $date = time();
        $code = $this->generateRandomString(6);
        $courseid = $this->get_program_id($c->program);
        if ($c->user != '') {
            $userid = $this->get_userid_by_fio($c->user);
        } // end if !=''
        else {
            $userid = 0;
        } // end else

        $query = "insert into mdl_code "
                . "(code, type, amount, "
                . "date1,"
                . "date2,"
                . "added) "
                . "values ('$code','$d1u','$d2u', '$c->type', '$c->amount', '$date')";
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
    }

}
