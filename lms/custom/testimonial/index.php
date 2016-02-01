<?php

require_once './classes/Testimonial.php';
$tst=new Testimonial();
$list=$tst->get_edit_page();
echo $list;
