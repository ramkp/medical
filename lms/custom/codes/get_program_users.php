<?php

require_once './classes/Codes.php';
$c = new Codes();
$id = $_POST['id'];
$list = $c->get_promo_course_users($id);
echo $list;
