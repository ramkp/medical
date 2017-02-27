<?php

require_once '../grades/classes/Grades.php';
$gr = new Grades();
$userid = $_POST['userid'];
$list = $gr->create_pdf_report($userid);
echo $list;
