<?php

require_once './classes/Schedule.php';
$sch = new Schedule();
$userslist = $_POST['userslist'];
$list = $sch->get_send_text_message_dialog($userslist);
echo $list;
