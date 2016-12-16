<?php

require_once './classes/Faq.php';
$f = new Faq();
$id = $_POST['id'];
$list = $f->is_cat_has_items($id);
echo $list;
