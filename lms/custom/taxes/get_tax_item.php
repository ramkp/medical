<?php

require_once './classes/Taxes.php';
$taxes = new Taxes();
$id = $_POST['id'];
$list = $taxes->get_tax_item($id);
echo $list;
