<?php

require_once './classes/Dashboard.php';
$ds = new Dashboard();
$profile = $_POST['profile'];
$list = $ds->update_profile_missed_fields(json_decode($profile));
echo $list;
