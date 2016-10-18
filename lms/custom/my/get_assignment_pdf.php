<?php

require_once 'classes/Dashboard.php';
$ds = new Dashboard();
$moduleid = $_POST['moduleid'];
$list = $ds->create_assignment_pdf($moduleid);
echo $list;
