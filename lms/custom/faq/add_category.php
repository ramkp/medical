<?php

require_once './classes/Faq.php';
$f = new Faq();
$name = $_POST['name'];
$list = $f->add_category($name);
echo $list;
