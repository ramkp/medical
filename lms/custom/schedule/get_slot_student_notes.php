<?php

require_once './classes/Schedule.php';
$sch = new Schedule();
$slotid = $_POST['slotid'];
$list = $sch->get_slot_student_notes($slotid);
echo $list;
