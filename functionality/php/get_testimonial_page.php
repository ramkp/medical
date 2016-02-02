<?php

require_once './classes/Testimonial.php';
$tst = new Testimonial();
$list = $tst->get_testimonial_page();
echo $list;
