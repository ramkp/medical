<?php

require_once './classes/Dashboard.php';
$ds = new Dashboard();
$demo = $_POST['demo'];
$list = $ds->insert_demographic_data(json_decode($demo));
echo $list;
