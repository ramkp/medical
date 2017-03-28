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

            if ($toolbar) {

                $list.="<div class='row-fluid' style='font-weight:bold;'>";
                $list.="<span class='span2'><input type='text' id='promo_program' style='width:125px' placeholder='Program'></span>";
                $list.="<span class='span2'><input type='text' id='promo_user' style='width:125px' placeholder='User'></span>";
                $list.="<span class='span2'><input type='text' id='promo_date1' style='width:45px' placeholder='Date1'>&nbsp;<input type='text' id='promo_date2' style='width:45px' placeholder='Date2'></span>";
                //$list.="<span class='span2'><input type='text' id='camp_ws' style='width:125px' placeholder='Workshop'></span>";
                $list.="<span class='span1'><button id='camp_search'>Search</button></span>";
                $list.="<span class='span1' style='padding-left:15px;'><button id='promo_reset_search'>Clear</button></span>";
                $list.="<span class='span1' style='padding-left:18px;'><button id='add_new_promo_button'>Add</button></span>";
                $list.="</div>";

                $list.="<div class='container-fluid' style='display:none;text-align:center;' id='ajax_loader'>";
                $list.="<span class='span10'><img src='https://$this->host/assets/img/ajax.gif' /></span>";
                $list.="</div>";

                $list.="<div class='row-fluid'>";
                $list.="<span class='span9' style='color:red;' id='camp_err'></span>";
                $list.="</div>";
            } // end if $toolbar

            $list.="<div id='camp_users_container'></div>";
        } // end if $this->session->justloggedin == 1
        else {
            $list.="<p>You are not authenticated. &nbsp; <a href='https://medical2.com/login'><button class='btn btn-primary' id='relogin'>Login</button></a></p>";
        }



        return $list;
    }

}
