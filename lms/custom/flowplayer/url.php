<?php

require_once './classes/Player.php';
$p = new Player();
$id = $_POST['id'];
$list = $p->get_video_url($id);
echo $list;
