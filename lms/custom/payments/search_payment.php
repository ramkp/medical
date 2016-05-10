<?php

require_once './classes/Payments.php';
$item = $_POST['item'];
$typeid = $_POST['typeid'];
$payment = new Payments($typeid);
$list = $payment->search_item($item, $typeid);
echo $list;
