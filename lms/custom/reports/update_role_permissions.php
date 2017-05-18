<?php

require_once './classes/Report.php';
$r = new Report();
$items = $_POST['items'];
$list = $r->update_role_permissions(json_decode($items));
echo $list;
