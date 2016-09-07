<?php

require_once '/home/cnausa/public_html/crons/classes/Students.php';
$student = new Students();
$host = "localhost";
$user = "cnausa_lms";
$pass = "^pH+F8*[AEdT";
$name = "cnausa_lms";
$student->backup_tables($host, $user, $pass, $name);
