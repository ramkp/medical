<?php

require_once './classes/Index.php';
$index = new Index();
$files = $_FILES;
$post = $_POST;
$index->upload_slides($files, $post);
