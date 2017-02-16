<?php

require_once './classes/Dashboard.php';
$ds = new Dashboard();
$at = $_POST['at'];
$list = $ds->get_presence_modal_dialog(json_decode($at));
echo $list;
