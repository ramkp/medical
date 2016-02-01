<?php
require_once './classes/Gallery.php';
$gl = new Gallery();
$list = $gl->get_index_page();
echo $list;


