<?php

require_once './classes/Installment.php';
$installment = new Installment();
$num = $_POST['num'];
$userid = $_POST['userid'];
$courseid = $_POST['courseid'];
$list = $installment->add_installment_user($courseid, $userid, $num);
echo $list;
