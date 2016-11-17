<?php

require_once './classes/Dashboard.php';
$ds = new Dashboard();
$userid = $_POST['userid'];
$courseid = $_POST['courseid'];
$list = $ds->get_payment_move_dialog($courseid, $userid);
echo $list;

