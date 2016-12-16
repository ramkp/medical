<?php

require_once './classes/Faq.php';
$faq = new Faq ();
$id = $_POST['id'];
$q = $_POST['q'];
$a = $_POST['a'];
$catid = $_POST['catid'];
$list = $faq->update_qa($id, $q, $a, $catid);
