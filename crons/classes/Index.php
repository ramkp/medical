<?php

require_once ('/home/cnausa/public_html/class.pdo.database.php');

class Index {

    public $db;

    function __construct() {
        $db = new pdo_db();
        $this->db = $db;
    }

    function get_random_banner() {
        $banners = array();
        $query = "select * from mdl_slides";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $banner = new stdClass();
            $banner->title = $row['title'];
            $banner->slogans=$row['slogan1']."<br>".$row['slogan2']."<br>".$row['slogan3'];
            $banners[] = $banner;
        }
        $rand_keys = array_rand($banners, 1);
        $banner = $banners[$rand_keys];
        return json_encode($banner);
    }

}
