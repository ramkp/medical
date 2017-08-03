<?php

require_once './Classes/ProcessPayment.php';
$pr = new ProcessPayment();
$amount = 2;
$card_last_four = 'NzkwNA==';
$trans_id = '60027509793';
$pr->test_refund($amount, $card_last_four, $trans_id);
