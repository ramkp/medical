<?php

require_once './classes/Campus.php';
$c = new Campus();
$id = $_POST['id'];
$list = $c->get_edit_dialog($id);
echo $list;

