<?php

require_once './classes/Aid.php';
$aid = new Aid();
$list = $aid->get_aid_page();
echo $list;
