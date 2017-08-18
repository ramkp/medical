<?php

require_once './classes/Page.php';
$cert = new Page();
$list = $cert->get_renew_certification_page();
echo $list;
