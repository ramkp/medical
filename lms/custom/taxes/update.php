<?php

require_once './classes/Taxes.php';
$taxes = new Taxes();
$id=$_POST['id'];
$value=$_POST['tax'];
$list=$taxes->update_state_tax($id, $value);
echo $list;
