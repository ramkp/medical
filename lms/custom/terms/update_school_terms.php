<?php

require_once './classes/Terms.php';
$t = new Terms();
$data = $_POST['data'];
$list = $t->update_school_terms($data);
echo $list;
