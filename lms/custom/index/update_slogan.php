<?php

require_once './classes/Index.php';
$index = new Index();
$sloganid = $_POST['sloganid'];
$bannerid = $_POST['bannerid'];
$text = $_POST['text'];
$index->update_slogan($sloganid, $bannerid, $text);
