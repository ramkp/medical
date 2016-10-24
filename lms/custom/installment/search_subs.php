<?php

require_once './classes/Installment.php';
$i = new Installment();
$item = $_POST['item'];
$list = $i->search_subs($item);
echo $list;
