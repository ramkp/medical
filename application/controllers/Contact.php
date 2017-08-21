<?php

class Contact extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('contact_model');
    }

    public function index() {
        $form = $this->contact_model->get_contact_form();
        $data = array('form' => $form);
        $this->load->view('header_view');
        $this->load->view('contact_view', $data);
        $this->load->view('footer_view');
    }

    public function contact() {
        $form = $this->contact_model->get_contact_page();
        $data = array('form' => $form);
        $this->load->view('header_view');
        $this->load->view('contact_view', $data);
        $this->load->view('footer_view');
    }

}
