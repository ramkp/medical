<?php

require_once './classes/Dashboard.php';
$ds = new Dashboard();
$ws = $_POST['ws'];
$list = $ds->add_user_to_workshop(json_decode($ws));
echo $list;
