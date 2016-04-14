<?php

require_once './classes/Contact.php';
$contact=new Contact();
$list=$contact->get_edit_page();
echo $list;