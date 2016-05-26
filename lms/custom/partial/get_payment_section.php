<?php

require_once './classes/Partial.php';
$partial = new Partial();
$ptype = $_POST['ptype'];
$list = $partial->get_payment_section($ptype);
echo $list;
