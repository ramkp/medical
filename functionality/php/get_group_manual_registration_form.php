<?php

require_once './classes/Register.php';
$rg=new Register();
$tot_participants=$_POST['tot_participants'];
$list=$rg->get_group_manual_registration_form($tot_participants);
echo $list;