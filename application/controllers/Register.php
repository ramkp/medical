<?php

/**
 * Description of Register
 *
 * @author sirromas
 */
class Register extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('register_model');
    }

    public function index() {
        $courseid = $this->uri->segment(3);
        $slotid=$this->uri->segment(4);
        if ($courseid == null) {
            $form = $this->register_model->get_register_form();
        } // end if $courseid==null
        else {
            $form = $this->register_model->get_register_form($courseid,$slotid);
        }

        $data = array('form' => $form);
        $this->load->view('header_view');
        $this->load->view('register_view', $data);
        $this->load->view('footer_view');
    }

}
