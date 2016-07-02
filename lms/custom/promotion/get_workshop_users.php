<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php';
$util = new Util();
$id = $_POST['id'];
$list = $util->get_workshop_users($id);
echo $list;


