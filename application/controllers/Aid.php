<?php

class Aid extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('aid_model');
    }

    public function index() {

        $form = $this->aid_model->get_financial_aid_page();
        $data = array('form' => $form);
        $this->load->view('header_view');
        $this->load->view('cert_view', $data);
        $this->load->view('footer_view');
    }
    
    public function workshop () {
        $form = $this->aid_model->get_financial_aid_workshop_page();
        $data = array('form' => $form);
        $this->load->view('header_view');
        $this->load->view('cert_view', $data);
        $this->load->view('footer_view');
    }
    
    public function college () {
         $form = $this->aid_model->get_financial_aid_college_page();
        $data = array('form' => $form);
        $this->load->view('header_view');
        $this->load->view('cert_view', $data);
        $this->load->view('footer_view');
    }

}
