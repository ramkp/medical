<?php

require_once './classes/Promotion.php';
$pr = new Promotion();
$id = $_POST['id'];
$slotid = $_POST['slotid'];
$list = $pr->get_user_cities($id, $slotid);
echo $list;
