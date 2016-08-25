<?php
require_once './classes/Faq.php';
$faq = new Faq ();
$id = $_POST ['id'];
$list = $faq->get_questions_by_category ( $id );
echo $list; 