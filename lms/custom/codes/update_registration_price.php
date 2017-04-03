<?php

require_once './classes/Codes.php';
$c = new Codes();
$code = $_POST['code'];
$list = $c->update_registration_code(json_decode($code));
echo $list;
