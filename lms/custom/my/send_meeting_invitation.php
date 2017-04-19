<?php

require_once './classes/Dashboard.php';
$ds = new Dashboard();
$inv = $_POST['inv'];
$list = $ds->send_meeting_invitation(json_decode($inv));
echo $list;
