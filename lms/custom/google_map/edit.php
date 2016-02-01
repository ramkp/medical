<?php

require_once './classes/Map.php';
$category_id=$_POST['category_id'];
$courseid=$_POST['courseid'];
$lat=$_POST['lat'];
$lng=$_POST['lng'];
$marker_text=$_POST['marker'];
$map= new Map($category_id);
$map->update_map($lat, $lng, $courseid, $marker_text);
