<?php
require_once './classes/Faq.php';
$faq = new Faq ();
$catid = $_POST ['catid'];
$q = $_POST ['q'];
$a = $_POST ['a'];
$faq->add_faq ( $catid, $q, $a );
