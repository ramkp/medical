<?php

require_once './classes/Contact.php';
$c = new Contact();
$item = $_POST['item'];
$list = $c->update_item(json_decode($item));
echo $list;
