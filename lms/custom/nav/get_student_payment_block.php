<?php

require_once './classes/navClass.php';
$nav = new NavClass();
$userid = $_POST['userid'];
$list = $nav->get_ekg_payment_block($userid);
echo $list;
