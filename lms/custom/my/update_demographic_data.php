<?php

require_once './classes/Dashboard.php';
$ds = new Dashboard();
$demo = $_POST['demo'];
$ds->update_demographic_data(json_decode($demo));
