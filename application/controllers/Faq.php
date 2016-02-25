<?php

/**
 * Description of Faq
 *
 * @author sirromas
 */
class Faq extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('faq_model');
    }

    public function index() {
        $page = $this->faq_model->get_faq_page();
        $data = array('page' => $page);
        $this->load->view('header_view');
        $this->load->view('faq_view', $data);
        $this->load->view('footer_view');
    }

}
