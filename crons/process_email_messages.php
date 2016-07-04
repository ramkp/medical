<?php

require_once '/home/cnausa/public_html/crons/classes/Students.php';
$student = new Students();
$list = $student->process_emails();
echo $list;

