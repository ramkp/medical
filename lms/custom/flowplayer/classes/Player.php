<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php';

/**
 * Description of Player
 *
 * @author moyo
 */
class Player extends Util {

    function __construct() {
        parent::__construct();
    }

    function get_video_url($modid) {
        $query = "select * from mdl_course_modules where id=$modid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $id = $row['instance'];
        }

        $query = "select * from mdl_label where id=$id";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $intro = $row['intro'];
        }

        preg_match('/href=(["\'])([^\1]*)\1/i', $intro, $m);
        $url = $m[2];
        return $url;
    }

}
