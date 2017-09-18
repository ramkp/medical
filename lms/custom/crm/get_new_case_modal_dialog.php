<?php

require_once './classes/Crm.php';
$crm = new Crm();
$userid = $_POST['userid'];
$list = $crm->get_new_case_modal_dialog($userid);
echo $list;
