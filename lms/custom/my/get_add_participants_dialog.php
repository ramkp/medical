<?php

require_once './classes/Dashboard.php';
$ds = new Dashboard();
$join_url = $_POST['join_url'];
$list = $ds->get_add_participants_dialog($join_url);
echo $list;
