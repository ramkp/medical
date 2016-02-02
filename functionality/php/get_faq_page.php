<?php

require_once './classes/FAQ.php';
$faq = new FAQ();
$list = $faq->get_faq_page();
echo $list;
