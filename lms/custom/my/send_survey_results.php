<?php

require_once './classes/Dashboard.php';
$ds = new Dashboard();
$survey = $_POST['survey'];
$list = $ds->send_survey_results(json_decode($survey));
echo $list;
