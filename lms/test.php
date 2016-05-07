<?php

//require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/class.pdo.database.php';
//phpinfo();

 //Set the Content Type
  header('Content-type: image/jpeg');

  $path=$_SERVER['DOCUMENT_ROOT'].'/lms/custom/gallery/files/145509434474.jpg';
    
  // Create Image From Existing File
  $jpg_image = imagecreatefromjpeg($path);

  // Allocate A Color For The Text
  $color = imagecolorallocate($jpg_image, 255, 255, 255);

  // Set Path to Font File
  $font_path = $_SERVER['DOCUMENT_ROOT'].'/assets/fonts/font-cert/KingCityFont.ttf';

  // Set Text to Be Printed On Image
  $text = "This is a forest!";

  //$ox = imagesx($im);
  //$oy = imagesy($im);  
  
  // Print Text On Image
  imagettftext($jpg_image, 12, 0, 15, 35, $color, $font_path, $text);

  // Save image with text
  imagejpeg($jpg_image, $path);

  // Clear Memory
  imagedestroy($jpg_image);
