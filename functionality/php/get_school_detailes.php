<?php

require_once './classes/Programs.php';
$pr=new Programs();
$courseid=$_POST['courseid'];
$detailes=$pr->get_nursing_info_block($courseid);
echo $detailes;