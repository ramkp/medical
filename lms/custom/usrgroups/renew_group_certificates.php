<?php

require_once './classes/Groups.php';
$g = new Groups();
$cert = $_POST['cert'];
$list = $g->renew_group_certificates(json_decode($cert));
echo $list;
