<?php

require_once './classes/Installment.php';
$installment = new Installment();
$list = $installment->verify_installment_users();
echo $list;
