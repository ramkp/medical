<?php

/**
 * Description of updatera
 *
 * @author sirromas
 */

set_time_limit(0);
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/certificates/classes/Certificates.php';

class update extends Util {

    public $cert;

    function __construct() {
        parent::__construct();
        $this->cert = new Certificates();
    }

    function get_ekg_certs() {
        $certs = array();
        $query = "select * from mdl_certificates where courseid=45";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $cert = new stdClass();
            foreach ($row as $key => $value) {
                $cert->$key = $value;
            } // end foreach
            $certs[] = $cert;
        } // end while 
        return $certs;
    }

    function process_ekg_certificates($certs) {
        $i=0;
        foreach ($certs as $cert) {
            echo "<pre>";
            print_r($cert);
            echo "</pre>";
            $userdata = $this->get_user_details($cert->userid);
            $name = $this->get_course_name($cert->courseid);
            echo "$userdata->firstname &nbsp; $userdata->lastname &nbsp; Course name: $name &nbsp;Certificate No: $cert->cert_no &nbsp; Certificate date: " . date('m-d-Y', $cert->issue_date) . "";
            $this->cert->get_certificate_template($cert->courseid, $cert->userid, $cert->issue_date, $cert->cert_no);
            $i++;
            echo "<br>--------------------------------------------------------------------------------------------------------------------------------------------<br>";
        } // end foreach
        echo "Total certificates updated: $i";
    }

}

$up = new update();
$certs = $up->get_ekg_certs();
$up->process_ekg_certificates($certs);
