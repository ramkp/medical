<?php

require_once './classes/Groups.php';
$groups = new Groups();
$id = $_POST['id'];
$list = $groups->get_request_detailed_view($id);
echo $list;
