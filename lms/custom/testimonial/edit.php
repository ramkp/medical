<?php

require_once './classes/Testimonial.php';
$tst=new Testimonial();
$data=$_POST['data'];
$list=$tst->save_page_changes($data);
echo $list;

