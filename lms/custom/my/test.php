<?php

require_once './classes/Dashboard.php';
$ds = new Dashboard();
$courseid = 41;
$userid = 14224;
$grades = $ds->get_student_course_average_grades($courseid, $userid);
echo "Grades: " . $grades . "<br>";
