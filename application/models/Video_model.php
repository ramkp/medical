<?php

class Video_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    function get_video_url($modid) {
        $query = "select * from mdl_course_modules where id=$modid";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $id = $row->instance;
        }

        $query = "select * from mdl_label where id=$id";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $intro = $row->intro;
        }

        preg_match('/href=(["\'])([^\1]*)\1/i', $intro, $m);
        $url = $m[2];
        return $url;
    }

    function get_player_installation($id) {
        $list = "";
        $url = $this->get_video_url($id);
        $list.="<div class='flowplayer' data-share='false'>";
        $list.="<video>";
        $list.="<source type='video/mp4' src='$url'>";
        $list.="</video>";
        $list.="</div>";
        return $list;
    }

    function get_player_page($id) {
        $list = "";

        $player = $this->get_player_installation($id);
        $list.="<br><div class='form_div'>";
        $list.=$player;
        $list.="</div>";
        
        return $list;
    }

}
