<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/crons/classes/Index.php';
$index = new Index();
$list = $index->get_random_banner();
echo $list;
