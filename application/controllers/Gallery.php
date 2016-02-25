<?php

/**
 * Description of Gallery
 *
 * @author sirromas
 */
class Gallery extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('gallery_model');
    }

    public function index () {
        $images=$this->gallery_model->get_images_list() ;
        $data=array('images'=>$images);
        $this->load->view('header_view');
        $this->load->view('gallery_view', $data);
        $this->load->view('footer_view');
    }

}
