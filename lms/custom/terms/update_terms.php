<?php

require_once './classes/Terms.php';
$t = new Terms();
$data = $_POST['data'];
$list = $t->save_page_changes($data);
echo $list;
