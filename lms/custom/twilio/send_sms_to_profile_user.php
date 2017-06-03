<?php

require_once './vendor/autoload.php';
require_once ('/home/cnausa/public_html/lms/class.pdo.database.php');

use Twilio\Rest\Client;

$db = new pdo_db();
$id = $_REQUEST['id'];
$body = $_REQUEST['body'];
$to = $_REQUEST['to'];
if ($id == '' || $body == '' || $to == '') {
    echo "Wrong input params ...";
} // end if
else {
    $account_sid = 'AC8ac954cf1be212f36af3726fb881c177';
    $auth_token = 'd7e767e2283c2a6b8e3fae4a134fe458';
    $client = new Client($account_sid, $auth_token);
    $sms = $client->account->messages->create($to, array(
        'From' => "+14075025255",
        'Body' => $body));

    $now = time();
    $query = "insert into mdl_sms_log (userid, body, added) "
            . "values ($id, '$body', '$now')";
    $db->query($query);
} // end else





