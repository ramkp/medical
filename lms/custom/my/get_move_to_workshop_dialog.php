<?php

require_once './classes/Dashboard.php';
$ds = new Dashboard();
$userid = $_POST['userid'];
$slotid = $_POST['slotid'];
$appid = $_POST['appid'];
$courseid = $_POST['courseid'];
$list = $ds->get_move_to_workshop_dialog($courseid, $slotid, $appid);
echo $list;

