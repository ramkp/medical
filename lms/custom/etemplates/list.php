<?php

require_once './classes/Templates.php';
$t = new Templates();
$list = $t->get_emails_templates_page();
echo $list;

