<?php

require_once './classes/Register.php';
$r = new Register();
$courseid = $_POST['id'];
$list = $r->get_school_program_slots($courseid);
echo $list;
