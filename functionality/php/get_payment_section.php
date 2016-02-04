<?php

require_once './classes/Register.php';
$courseid = $_POST['courseid'];
$rg = new Register();
$list = $rg->get_payment_section_personal($courseid);
echo $list;

