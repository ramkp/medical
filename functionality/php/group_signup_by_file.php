<?php

require_once './classes/Payment.php';
$payment = new Payment();
$section=$_POST['group_common_section'];
$list=$payment->get_group_payment_section_file(json_decode($section));
echo $list;

