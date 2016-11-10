<?php

require_once './classes/Installment.php';
$i = new Installment();
$subsID = $_POST['subsID'];
$list = $i->cancel_subs($subsID);
echo $list;
