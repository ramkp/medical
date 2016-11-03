<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php';

/**
 * Description of Migrate
 *
 * @author moyo
 */
class Migrate extends Util {

    function __construct() {
        parent::__construct();
    }

    function get_old_location_cartificates() {
        $certificates = array();
        $query = "select * from mdl_certificates";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $cert = new stdClass();
            foreach ($row as $key => $value) {
                $cert->$key = $value;
            } // end foreach
            $certificates[] = $cert;
        } // end while
        $this->create_certificates_list($certificates);
    }

    function create_certificates_list($certificates) {

        foreach ($certificates as $cert) {
            $user = $this->get_user_details($cert->userid);
            $cname = $this->get_course_name($cert->courseid);
            $issue = date('m-d-Y', $cert->issue_date);
            $exp = date('m-d-Y', $cert->expiration_date);
            echo "$user->firstname $user->lastname $cname $issue $exp  <br>";
            echo"<hr><br>";
        } // end foreach
    }

}
