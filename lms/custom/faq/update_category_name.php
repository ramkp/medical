<?php

require_once './classes/Faq.php';
$f = new Faq();
$cat = $_POST['cat'];
$f->update_category_name(json_decode($cat));
