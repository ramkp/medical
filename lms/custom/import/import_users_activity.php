<?php

require_once './classes/Import.php';
$import=new Import();
$filepath=$_SERVER['DOCUMENT_ROOT']."/lms/custom/import/files/1/users_activity.csv";
$import->process_user_activities($filepath);
