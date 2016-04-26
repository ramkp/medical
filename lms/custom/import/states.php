<?php

require_once './classes/Import.php';
$import=new Import();
$filepath=$_SERVER['DOCUMENT_ROOT']."/lms/custom/import/files/1/user_notes.csv";
$import->update_user_states($filepath);
