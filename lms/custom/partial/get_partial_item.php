<?php

$partial=new Partial();
$page=$_POST['id'];
$list=$partial->get_partial_payment_item($page);
echo $list;