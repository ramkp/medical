<?php

require_once './classes/Mailer2.php';
$mailer=new Mailer2();

$user=new stdClass();
$user->userid=11677;
$user->courseid=45;
$user->slotid=74;
$user->first_name="John";
$user->last_name="Connair";
$user->email="sirromas@gmail.com";
$user->phone="380972415427";
$user->pwd='Abba1Abba2';
$user->addr="Some Address";
$user->city="Some City";
$user->state="Alabama";
$user->zip=6900;
$user->country="US";

$mailer->send_account_confirmation_message($user);


