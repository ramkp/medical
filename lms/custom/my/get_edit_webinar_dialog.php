<?php

require_once './classes/Dashboard.php';
$ds = new Dashboard();
$id = $_POST['id'];
$list = $ds->get_edit_webinar_dialog($id);
echo $list;
