<?php

require_once './classes/Grades.php';
$gr = new Grades();
$userid = 13361;
$gr->create_pdf_report($userid);
