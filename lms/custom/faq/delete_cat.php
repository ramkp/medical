<?php

require_once './classes/Faq.php';
$f = new Faq();
$id = $_POST['id'];
$list = $f->delete_cat($id);
echo $list;
