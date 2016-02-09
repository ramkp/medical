<?php

require_once './classes/Groups.php';
$group=new Groups();
$request=$_POST['request'];
$list=$group->submit_private_group_request(json_decode($request));
echo $list;
