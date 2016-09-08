<?php
require_once '/home/cnausa/public_html/crons/classes/Students.php';
$student = new Students ();
$interval = 'w';
$student->get_expired_certificates ( $interval );