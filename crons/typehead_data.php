<?php

require_once '/home/cnausa/public_html/crons/classes/Students.php';
$student = new Students();
$student->create_typehead_data();

