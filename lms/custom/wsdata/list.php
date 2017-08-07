<?php

require_once './classes/Wsdata.php';
$ws = new Wsdata();
$list = $ws->get_workshop_status_page();
echo $list;
