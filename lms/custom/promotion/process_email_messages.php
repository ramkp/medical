<?php

require_once './classes/Promotion.php';
$pm = new Promotion();
$list = $pm->process_emails();
echo $list;
