<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/authorize/Classes/ProcessPayment.php';
$data1 = array('fname' => 'John', 'lname' => 'Connair', 'case' => 'Terminator');
$data2 = array('fname' => 'AAAAAAAAA', 'lname' => 'Bbbbbbbbbbbbb', 'case' => 'Josher.kopo');
$auth = new ProcessPayment();
//$auth->save_log($data1);
//$auth->save_log($data2);
//$auth->getCustomerProfileIds();

// Params of transaction to be refunded

$amount=50;
$card_last_four='0857';
$exp_date='052020';
$trans_id='60007849946';
$auth->makeRefund2($amount, $card_last_four, $exp_date, $trans_id);
        

