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

    public function index() {
        $images = $this->gallery_model->get_images_list();
        $data = array('images' => $images);
        $this->load->view('header_view');
        $this->load->view('gallery_view', $data);
        $this->load->view('footer_view');
    }

    public function photo_gallery() {
        $images = $this->gallery_model->get_images_list();
        $data = array('images' => $images);
        $this->load->view('header_view');
        $this->load->view('gallery_view', $data);
        $this->load->view('footer_view');
    }

    public function matched() {
        $state = $this->uri->segment(3);
        $month = $this->uri->segment(4);
        $year = $this->uri->segment(5);

        $state = ($state == 0) ? null : $state;
        $month = ($month == 0) ? null : $month;
        $year = ($year == 0) ? null : $year;

        $images = $this->gallery_model->get_images_list($state, $month, $year);
        $data = array('images' => $images);
        $this->load->view('header_view');
        $this->load->view('gallery_view', $data);
        $this->load->view('footer_view');
    }

}
