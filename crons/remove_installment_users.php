<?php

require_once '/home/cnausa/public_html/crons/classes/Students.php';
$student = new Students();
$list = $student ->verify_installment_users();
echo $list;

