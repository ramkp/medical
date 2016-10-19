<?php

require_once './classes/Terms.php';
$t = new Terms();
$list = $t->get_terms_page();
echo $list;
