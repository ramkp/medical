<?php
require_once './classes/Faq.php';
$faq = new Faq ();
$id = $_POST ['id'];
$faq->delete_faq ( $id );