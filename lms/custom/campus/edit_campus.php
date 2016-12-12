<?php

require_once './classes/Campus.php';
$c = new Campus();
$campus = $_POST['campus'];
$c->edit_campus(json_decode($campus));
