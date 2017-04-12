<?php

require_once './classes/Dashboard.php';
$ds = new Dashboard();
$courseid = $_POST['courseid'];
$list = $ds->get_add_webinar($courseid);
echo $list;
