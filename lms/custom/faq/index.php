<?php

require_once './classes/Faq.php';
$faq=new Faq();
$list=$faq->get_edit_page();
echo $list;
