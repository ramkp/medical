<?php

/**
 * Description of Video
 *
 * @author moyo
 */
class Video extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('video_model');
    }

    function play_video() {
        $id = $this->uri->segment(3);
        $video = $this->video_model->get_player_page($id);
        $data = array('video' => $video);
        $this->load->view('header_view');
        $this->load->view('video_view', $data);
        $this->load->view('footer_view');
    }

}
