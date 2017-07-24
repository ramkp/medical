<?php

require_once './classes/Dashboard.php';
$ds = new Dashboard();
$data = $_POST['data'];
$ds->add_college_student_basic_data(json_decode($data));

