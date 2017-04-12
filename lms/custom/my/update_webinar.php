<?php

require_once './classes/Dashboard.php';
$ds = new Dashboard();
$w = $_POST['w'];
$ds->update_webinar(json_decode($w));
