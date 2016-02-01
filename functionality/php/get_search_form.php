<?php

require_once './classes/searchForm.php';
$sf=new searchForm();
$form=$sf->get_search_form();
echo $form;