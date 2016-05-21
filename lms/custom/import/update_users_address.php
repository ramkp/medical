<?php

require_once './classes/Import.php';
$import = new Import();
$list = $import->update_users_addresses();
echo $list;
