<?php

require_once './classes/Dashboard.php';
$ds = new Dashboard();
$at = $_POST['at'];
$list = $ds->is_at_date_exists(json_decode($at));
echo $list;

