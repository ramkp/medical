<?php

require_once './classes/Schedule.php';
$sch = new Schedule();
$search = $_POST['search'];
$scheduler = $_POST['scheduler'];
$list = $sch->search_slot($scheduler, $search);
echo $list;


