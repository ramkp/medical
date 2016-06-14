<?php

require_once './classes/Index.php';
$index = new Index();
$id = $_POST['id'];
$index->delete_slide($id);
