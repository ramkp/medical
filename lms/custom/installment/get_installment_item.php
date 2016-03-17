<?php

require_once './classes/Installment.php';
$installment = new Installment();
$page=$_POST['id'];
$list = $installment->get_installment_item($page);
echo $list;
