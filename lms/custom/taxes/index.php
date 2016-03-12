<?php

require_once './classes/Taxes.php';
$taxes = new Taxes();
$list = $taxes->get_state_taxes_list();
echo $list;
