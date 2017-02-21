<?php

require_once './classes/Survey.php';
$s = new Survey();
$list = $s->get_survey_page();
echo $list;
