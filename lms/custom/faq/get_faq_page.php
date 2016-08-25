<?php

require_once './classes/Faq.php';
$faq=new Faq();
$list=$faq->get_faq_page();
echo $list;

