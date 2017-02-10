<?php

require_once './classes/Calendar.php';
$c = new Calendar();
$userid = $_REQUEST['userid'];
$list = $c->get_user_calendar_dates($userid);
echo $list;
