<?php

require_once './classes/Groups.php';
$g = new Groups();
$gusers = $_POST['gusers'];
$list = $g->get_renew_cert_dialog(json_decode($gusers));
echo $list;

