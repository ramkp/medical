<?php

require_once ($_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php');
require_once $_SERVER['DOCUMENT_ROOT'] . "/lms/custom/twilio/vendor/autoloader.php";

use Twilio\Rest\Client;

class Sender extends Util {

    public $account_sid = 'AC8ac954cf1be212f36af3726fb881c177';
    public $auth_token = 'd7e767e2283c2a6b8e3fae4a134fe458';

    function __construct() {
        parent::__construct();
    }

    function sendItem($userid, $body, $to) {
        $client = new Client($this->account_sid, $this->auth_token);
        $client->account->messages->create($to, array(
            'From' => "+14075025255",
            'Body' => $body));
        $this->add_sms_record($userid, $body, $to);
    }

    function add_sms_record($userid, $body, $to) {
        $now = time();
        $query = "insert into mdl_sms_log (userid, body, added) "
                . "values ($userid, '$body', '$now')";
        $this->db->query($query);
    }

}
