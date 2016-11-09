<?php

require_once './classes/Certificates.php';
$c = new Certificates();
$id = $_POST['id'];
$list = $c->get_ceftificate_data($id);
echo $list;
