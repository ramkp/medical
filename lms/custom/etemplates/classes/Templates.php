<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php';

/**
 * Description of Templates
 *
 * @author moyo
 */
class Templates extends Util {

    function __construct() {
        parent::__construct();
    }

    function get_emails_templates_page() {
        $list = "";

        $list.="<div class='row-fluid' style='font-weight:bold;'>";
        $list.="<span class='span4'>Email templates</span>";
        $list.="<span class='span2'><button class='btn btn-primary' id='add_new_template'>Add</button></span>";
        $list.="</div>";

        $query = "select * from mdl_templates order by name";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                
            } // end while
        } // end if $num > 0
        else {
            
        } // end else

        return $list;
    }

}
