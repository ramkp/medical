<?php

require_once './classes/Contact.php';
$contact=new Contact();
$data=$_POST['data'];
$list=$contact->save_page_changes($data);
echo $list;

