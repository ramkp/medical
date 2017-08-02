<?php

require_once './classes/Demographic.php';
$dm = new Demographic();
$list = $dm->create_demographic_pdf_report();
echo $list;
