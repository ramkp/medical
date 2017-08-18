<?php

require_once './classes/Page.php';
$cert = new Page();
$data = $_POST['data'];
$list = $cert->save_page_changes($data);
echo $list;
