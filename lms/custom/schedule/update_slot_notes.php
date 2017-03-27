<?php

require_once './classes/Schedule.php';
$sch = new Schedule();
$notes = $_POST['notes'];
$sch->update_slot_notes(json_decode($notes));
