<?php

require_once './classes/Aid.php';
$aid = new Aid();
$data = $_POST['data'];
$list = $aid->save_workshop_data($data);
echo $list;
