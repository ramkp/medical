<?php

require_once './classes/Certificates.php';
$cert = new Certificates();
$labels=$_POST['labels'];
$cert->print_labels($labels);
