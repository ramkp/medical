<?php

require_once './classes/Dashboard.php';
$ds = new Dashboard();
$id = $_POST['id'];
$ds->remove_from_ws($id);

