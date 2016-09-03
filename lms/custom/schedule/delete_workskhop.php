<?php

require_once './classes/Schedule.php';
$sch = new Schedule();
$id = $_POST['id'];
$list = $sch->delete_workshop($id);
echo $list;
