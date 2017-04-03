<?php

require_once './classes/Dashboard.php';
$ds = new Dashboard();
$list = $ds->complete_users();
echo $list;
