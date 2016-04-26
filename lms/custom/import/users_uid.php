<?php

require_once './classes/Import.php';
$import=new Import();
$filepath=$_SERVER['DOCUMENT_ROOT']."/lms/custom/import/files/1/users_uid.csv";
$import->update_users_uid($filepath);

