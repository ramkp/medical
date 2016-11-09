<?php

require_once './classes/Certificates.php';
$c = new Certificates();
$template = $_POST['template'];
$list = $c->create_template($template);
echo $list;
