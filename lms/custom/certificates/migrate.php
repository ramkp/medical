<?php

require_once './classes/Certificates.php';

$c = new Certificates();
$courseid = 51;
$c->get_old_location_cartificates($courseid);


