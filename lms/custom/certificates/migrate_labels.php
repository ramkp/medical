<?php

require_once './classes/Certificates.php';
$c = new Certificates();
$list = $c->migrate_labels();
echo $list;
