<?php

require_once './classes/Dashboard.php';
$ds = new Dashboard();
$at = $_POST['at'];
$ds->update_student_attendance(json_decode($at));

