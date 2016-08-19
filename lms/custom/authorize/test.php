<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/authorize/Classes/ProcessPayment.php';
$data1 = array('fname' => 'John', 'lname' => 'Connair', 'case' => 'Terminator');
$data2 = array('fname' => 'AAAAAAAAA', 'lname' => 'Bbbbbbbbbbbbb', 'case' => 'Josher.kopo');
$auth = new ProcessPayment();
$auth->save_log($data1);
$auth->save_log($data2);


