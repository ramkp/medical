<?php

require_once './classes/Index.php';
$index = new Index();
$id = $_POST['id'];
$index->set_first_banner($id);
