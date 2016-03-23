<?php

require_once './classes/Groups.php';
$group=new Groups();
$page=$_POST['id'];
$list=$group->get_group_item($page);
echo $list;

