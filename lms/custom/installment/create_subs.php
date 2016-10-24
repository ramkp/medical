<?php

require_once './classes/Installment.php';
$inst = new Installment();
$subs = $_POST['subs'];
$list = $inst->create_subscription(json_decode($subs));
echo $list;
