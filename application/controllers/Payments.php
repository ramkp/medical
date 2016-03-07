<?php

/**
 * Description of Payment
 *
 * @author sirromas
 */
class Payments extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('payment_model');
    }

    public function index() {
        $userid = $this->uri->segment(3);
        if ($userid != null) {
            $form = $this->payment_model->get_payment_section($userid);
        } // end if $userid!=null
        else {
            $form = "<p align='center'>Invalid data provided</p>";
        }

        $data = array('form' => $form);
        $this->load->view('header_view');
        $this->load->view('payment_view', $data);
        $this->load->view('footer_view');
    }

}
