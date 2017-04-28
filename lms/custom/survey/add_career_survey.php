<?php

require_once './classes/Survey.php';
$s = new Survey();
$data = $_POST['data'];
$list = $s->add_career_survey(json_decode($data));
echo $list;
