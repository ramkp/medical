<?php

require_once './classes/Crm.php';
$crm_user_id = '2b08761c-59d8-bde2-48c8-59bfcb2bc48b';
$crm = new Crm();
$crm->create_moodle_account($crm_user_id);
