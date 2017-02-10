<?php

require_once './classes/Calendar.php';
$cal = new Calendar();
$data = $_POST['calendar'];
$list = $cal->update_user_calendar(json_decode($data));
echo $list;

