<?php

/**
 * Description of Promotion
 *
 * @author sirromas
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/invoices/classes/Invoice.php';
include $_SERVER['DOCUMENT_ROOT'] . "/lms/editor/fckeditor.php";

class Promotion extends Util {

    public $invoice;

    function __construct() {
        parent::__construct();
        $this->invoice = new Invoices();
    }

    function get_campaigns_list() {
        date_default_timezone_set('Pacific/Wallis');
        $list = "";
        $list.="<select id='camapaign'>";
        $list.="<option value='0' selected>Campaign</option>";
        $query = "select * from mdl_campaign order by dated desc";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $date = date('m-d-Y', $row['dated']);
                $list.="<option value='" . $row[id] . "'>" . $row['subject'] . " - " . $date . "</option>";
            } // end while
        } // end if $num > 0
        $list.="</select>";
        return $list;
    }

    function get_add_new_campaigner_block() {
        $program_types = $this->get_course_categories();
        $list = "";

        $list.="<div class='container-fluid' style='align:center;'>";
        $list.="<span class='span6'>$program_types</span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='align:center;'>";
        $list.="<span class='span6' id='category_courses'></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='align:center;'>";
        $list.="<span class='span6' id='promotion_users'></span>";
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

        $list.="<div class='container-fluid' style='align:center;'>";
        $list.="<span class='span3'>";
        $list.="<button type='button' id='create_campaign' class='btn btn-primary'>Create</button>";
        $list.="</span>";
        $list.="</div>";
        return $list;
    }

    function get_promotion_page() {
        $list = "";
        $new_campaign = $this->get_add_new_campaigner_block();

        $list.="<div class='container-fluid'  style='text-align:center;'>";
        $list.="<span class='span8'>";
        $oFCKeditor = new FCKeditor('editor');
        $oFCKeditor->BasePath = $this->editor_path;
        $oFCKeditor->Value = '';
        $oFCKeditor->Create(false);
        $list.="</div>";
        $list.="</div>";

        $list.="<div class='container-fluid' id='new_campaign_container' style='text-align:center;'>";
        $list.=$new_campaign;
        $list.="</div>";


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

        $total = count($users);
        $query = "insert into mdl_campaign "
                . "(content,"
                . "total,"
                . "processed,"
                . "status,"
                . "type,"
                . "dated) "
                . "values('$data',"
                . "'$total',"
                . "'0',"
                . "'pending',"
                . "'email',"
                . "'" . time() . "')";
        mysql_query($query);
        $camid = mysql_insert_id();

        if (count($users) > 0) {
            foreach ($users as $id) {
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
            } // end foreach
        } // end if count($users)>0
        $list = "Message was added to queue and will be processed soon. ";
        return $list;
    }

}
