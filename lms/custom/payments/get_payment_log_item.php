<?php

require_once './classes/Payments.php';
$payments = new Payments();
$page = $_POST['id'];
$list = $payments->get_payment_log_item($page);
echo $list;

