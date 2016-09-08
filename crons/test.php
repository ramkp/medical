<?php


require_once '/home/cnausa/public_html/crons/classes/Students.php';
$student = new Students();

echo date_default_timezone_get();
echo "<br>";

echo "Date: ".date('m-d-Y h:i:s');


$reminder=$student->get_certificate_reminder_message ();
echo $reminder;

