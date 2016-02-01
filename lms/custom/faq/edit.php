<?php

require_once './classes/Faq.php';
$faq=new Faq();
$data=$_POST['data'];
$list=$faq->save_page_changes($data);
echo $list;

