<?php

require_once './classes/Captcha.php';
$cap = new Captcha();
$word = $_POST['captcha'];
$list = $cap->verify_captcha($word);
echo $list;
