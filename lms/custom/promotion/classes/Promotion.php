<?php

/**
 * Description of Promotion
 *
 * @author sirromas
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php';

class Promotion extends Util {

    function __construct() {
        parent::__construct();
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
        $list = "";

        return $list;
    }

    function get_promotion_page() {
        $list = "";
        $campaign = $this->get_campaigns_list();
        $list.="<div class='container-fluid'>";
        $list.="<span class='span3'>$campaign</span><span class='span3'><button class='btn btn-primary' id='create_campaign'>Create</button></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' id='campaign_container'>";

        $list.="</div>";

        $list.="<div class='container-fluid' id='new_campaign_container' style='display:none;'>";
        $list.="<span class='span3'>aaaaaa</span>";
        $list.="</div>";

        return $list;
    }

}
