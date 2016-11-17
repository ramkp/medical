<?php

require_once './classes/Dashboard.php';
$ds = new Dashboard();
$userid = $_POST['userid'];
$list = $ds->get_add_create_cert_dialog($userid);
echo $list;
