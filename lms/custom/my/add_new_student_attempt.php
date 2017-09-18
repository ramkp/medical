<?php

require_once './classes/Dashboard.php';
$ds = new Dashboard();
$item = $_POST['item'];
$ds->add_new_student_attempt(json_decode($item));
