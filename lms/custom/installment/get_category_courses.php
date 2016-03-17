<?php

require_once './classes/Installment.php';
$id = $_POST['id'];
$invoice = new Installment();
$list = $invoice->get_course_by_category($id);
echo $list;

