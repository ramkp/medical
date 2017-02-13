<?php

require_once './classes/Instructors.php';
$in = new Instructors();
$instructor = $_POST['instructor'];
$in->add_instructor_to_workshop(json_decode($instructor));
