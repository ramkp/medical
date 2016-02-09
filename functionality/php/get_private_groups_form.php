<?php

require_once './classes/Groups.php';
$group=new Groups();
$list=$group->get_private_group_form();
echo $list;
