<?php

require_once './classes/Groups.php';
$groups=new Groups();
$list=$groups->get_requests_list();
echo $list;
