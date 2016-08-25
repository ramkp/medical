<?php
require_once './classes/Faq.php';
$faq = new Faq ();
$list = $faq->faq_add ();
echo $list;