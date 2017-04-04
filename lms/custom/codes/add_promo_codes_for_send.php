<?php

require_once './classes/Codes.php';
$c = new Codes();
$camp = $_POST['camp'];
$c->add_promo_codes_for_send(json_decode($camp));

