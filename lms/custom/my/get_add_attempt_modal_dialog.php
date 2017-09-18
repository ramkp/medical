<?php

require_once './classes/Dashboard.php';
$ds = new Dashboard();
$userid = $_POST['userid'];
$list = $ds->get_add_attempt_modal_dialog($userid);
echo $list;
