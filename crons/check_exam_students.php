<?php

require_once '/home/cnausa/public_html/crons/classes/Students.php';
$student = new Students();
$list = $student->check_exam_students();
echo $list;

