<?php

require_once './classes/Campus.php';
$campus = $_POST['campus'];
$c = new Campus();
$list = $c->add_new_campus(json_decode($campus));
echo $list;
