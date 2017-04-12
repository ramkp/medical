<?php

require_once './classes/Dashboard.php';
$ds = new Dashboard();
$w = $_POST['w'];
$ds->add_new_webinar(json_decode($w));
