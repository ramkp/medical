<?php

require_once './classes/Index.php';
$index = new Index();
$list = $index->get_index_page();
echo $list;
