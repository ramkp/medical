<?php

require_once './classes/Campus.php';
$c = new Campus();
$id = $_POST['id'];
$c->del_campus($id);
