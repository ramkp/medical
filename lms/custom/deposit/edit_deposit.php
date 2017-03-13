<?php

require_once './classes/Deposit.php';
$d = new Deposit();
$id = $_POST['id'];
$list = $d->edit_deposit($id);
echo $list;
