<?php

require_once './classes/Faq.php';
$f = new Faq();
$list = $f->get_add_cat_dialog();
echo $list;
