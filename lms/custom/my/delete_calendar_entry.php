<?php

require_once './classes/Dashboard.php';
$ds = new Dashboard();
$at = $_POST['at'];
$ds->delete_calendar_entry(json_decode($at));
