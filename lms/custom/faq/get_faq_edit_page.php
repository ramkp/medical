<?php
require_once './classes/Faq.php';
$faq = new Faq ();
$id = $_POST ['id'];
$list = $faq->get_faq_eit_page ( $id );
echo $list;