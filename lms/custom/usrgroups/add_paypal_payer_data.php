<?php

require_once './classes/Groups.php';
$g = new Groups();
$p = $_POST['p'];
$list = $g->add_paypal_payer_data(json_decode($p));
echo $list;
