<?php

require_once './classes/Faq.php';
$f = new FAQ();
$list = $f->get_edit_cat_dialog();
echo $list;
