<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/Payment.php';

$card = new stdClass();
$card->cds_name = 'John Doe';
$card->cds_address_1 = 'Some address';
$card->cds_city = 'Come city';
$card->cds_state = 'AZ';
$card->cds_zip = '69002';
$card->cds_email = 'sirromas@gmail.com';
$card->cds_pay_type = '10'; // master card ..
$card->cds_cc_number = '5338555912435992';
$card->cds_cc_exp_month = '02';
$card->cds_cc_exp_year = '2018';
$card->userid = 11557;
$card->courseid = 53;
$card->sum = 256;



$invoice = new Invoice();
$payment = new Payment();
$installmentobj = $invoice->get_user_installment_payments($card->userid, $card->courseid);
$user_payment_data = $payment->get_user_payment_credentials($card->userid);

$order = new stdClass();
$order->cds_name = "$user_payment_data->firstname $user_payment_data->lastname";
$order->cds_address_1 = $card->cds_address_1;
$order->cds_city = $card->cds_city;
$order->cds_state = "$user_payment_data->cds_state";
$order->cds_zip = $card->cds_zip;
$order->cds_email = $card->cds_email;
$order->cds_pay_type = $card->cds_pay_type;
$order->cds_cc_number = $card->cds_cc_number;
$order->cd_cc_month = $card->cds_cc_exp_month;
$order->cds_cc_year = $card->cds_cc_exp_year;
$order->sum = $card->sum;
$order->item = 'Test item';
$order->group = 0;
$order->userid = $card->userid;
$order->courseid = $card->courseid;
$order->payments_num = $installmentobj->num;

echo "<br>-------------------------------------------<br>";
print_r($card);
echo "<br>-------------------------------------------<br>";

$pr = new ProcessPayment();
$pr->createSubscription($order);
