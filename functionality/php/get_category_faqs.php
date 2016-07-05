<?php

require_once './classes/FAQ.php';
$faq = new FAQ();
$id = $_POST['id'];
$list = $faq->get_faq_by_category_id($id);
echo $list;
