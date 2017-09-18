<?php

require_once './classes/Crm.php';
$crm = new Crm();
$scase = $_POST['scase'];
$crm->add_new_support_case(json_decode($scase));
