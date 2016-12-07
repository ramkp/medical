<?php

require_once './classes/Register.php';
$r = new Register();
$app = $_POST['app'];
$list = $r->send_school_app(json_decode($app));
echo $list;
