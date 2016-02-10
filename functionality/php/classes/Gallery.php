<?php

/**
 * Description of Gallery
 *
 * @author sirromas
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/class.pdo.database.php';

class Gallery {

    public $db;

    function __construct() {
        $this->db = new pdo_db();
    }

    function get_images_list() {
        $list = "";
        $dir_path = $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/gallery/files';
        $images = scandir($dir_path);
        $list.="<div class='form_div'><div class='fotorama'>";
        foreach ($images as $image) {
            if ($image != '.' && $image != '..') {
                $file_path = 'http://' . $_SERVER['SERVER_NAME'] . '/lms/custom/gallery/files/' . $image;
                $list.="<img src='$file_path' alt='$image' width='300px;' height='200px;'>";
            }
        }
        $list.="</div></div>";
        return $list;
    }

}
