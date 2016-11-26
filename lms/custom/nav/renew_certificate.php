<?php

require_once './classes/navClass.php';
$nav = new navClass();
$cert = $_POST['cert'];
$list = $nav->renew_certificate(json_decode($cert));
echo $list;

