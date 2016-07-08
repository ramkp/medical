<?php

require_once './classes/Import.php';
$im = new Import();
$list = $im->get_non_paid_registrations();
echo $list;
