<?php

require_once './classes/Installment.php';
$installment = new Installment();
$list = $installment->get_installment_page();
echo $list;
?>


