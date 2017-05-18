<?php

require_once './classes/Report.php';
$r = new Report();
$roleid = $_POST['roleid'];
$list = $r->get_role_based_permissions($roleid);
echo $list;
