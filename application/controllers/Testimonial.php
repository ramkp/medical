<?php

/**
 * Description of Testimonial
 *
 * @author sirromas
 */
class Testimonial extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('test_model');
    }

    public function index() {
        $page = $this->test_model->get_testimonial_page();
        $data = array('page' => $page);
        $this->load->view('header_view');
        $this->load->view('testimonial_view', $data);
        $this->load->view('footer_view');
    }

}
