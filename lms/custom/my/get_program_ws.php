<?php

require_once './classes/Dashboard.php';
$ds = new Dashboard();
$coursename = $_POST['coursename'];
$list = $ds->get_program_slots($coursename);
echo $list;
