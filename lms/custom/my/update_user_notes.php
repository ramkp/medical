<?php

require_once './classes/Dashboard.php';
$ds = new Dashboard();
$notes = $_POST['notes'];
$list = $ds->update_user_notes(json_decode($notes));
echo $list;
