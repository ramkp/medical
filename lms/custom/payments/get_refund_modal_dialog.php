<?php

require_once './classes/Payment.php';
$payments = new Payments(0);
$list = $payments->get_refund_modal_dialog();
echo $list;
