<?php

require_once './classes/Faq.php';
$f = new Faq();
$name = $_POST['name'];
$list = $f->is_category_exists($name);
echo $list;

