<?php

require_once './classes/Options.php';
$courseid=$_POST['courseid'];
$group=$_POST['group'];
$op=new Options();
$list=$op->get_payment_options($courseid);
echo $list;


