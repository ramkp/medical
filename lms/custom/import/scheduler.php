<?php

require_once './classes/Import.php';
$import = new Import();
$filepath = $_SERVER['DOCUMENT_ROOT'] . "/lms/custom/import/files/1/classes.csv";
$classes = $import->add_scheduler_slots($filepath);


