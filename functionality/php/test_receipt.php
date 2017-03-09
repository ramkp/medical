<?php

require_once './classes/Mailer.php';
$m = new Mailer();
$user = new stdClass();
$user->id = 11773;
$user->userid = 11773;
$user->addr = 'Some billing addr';
$user->city = 'Some billing city';
$user->state = 5; // California
$user->country = 234; // USA
$user->zip = 3309;
$user->phone = 14078642400;
$user->slotid = 1305;
$user->amount = 25;
$user->payment_amount=25;
$user->courseid = 45;
$user->receipt_email = 'n/a';
$user->email='some_billing@gmail.com';

$m->get_account_confirmation_message2($user);






