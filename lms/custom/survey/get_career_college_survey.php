<?php

require_once './classes/Survey.php';
$s = new Survey();
$list = $s->get_career_collge_survey();
echo $list;
