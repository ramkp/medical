<?php

require_once './classes/Faq.php';
$f = new Faq();
$list = $f->get_del_cat_dialog();
echo $list;

