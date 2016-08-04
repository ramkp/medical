<?php

/**
 * Description of Cert
 *
 * @author sirromas
 */
class Certs extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('cert_model');
    }

    public function index() {
        $form = $this->cert_model->get_cert_form();
        $data = array('form' => $form);
        $this->load->view('header_view');
        $this->load->view('cert_view', $data);
        $this->load->view('footer_view');
    }

    public function verify_certification() {
        $form = $this->cert_model->get_cert_form();
        $data = array('form' => $form);
        $this->load->view('header_view');
        $this->load->view('cert_view', $data);
        $this->load->view('footer_view');
    }

}
