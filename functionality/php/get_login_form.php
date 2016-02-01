<?php

require_once './classes/loginForm.php';
$login=new loginForm();
$form=$login->get_login_form();
echo $form;