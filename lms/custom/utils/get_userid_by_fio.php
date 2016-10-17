<?php

require_once 'classes/Util.php';
$u = new Util();
$username = $_POST['username'];
$list = $u->get_userid_by_fio($username);
echo $list;

