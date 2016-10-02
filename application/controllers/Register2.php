<?php

/**
 * Description of Register
 *
 * @author sirromas
 */
class Register2 extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('register_model');
    }

    public function index() {

        $courseid = $this->uri->segment(3);
        $slotid = $this->uri->segment(4);
        if ($courseid == null) {
            $form = $this->register_model->get_register_form2();
        } // end if $courseid==null
        else {
            if ($slotid == '') {
                $slotid = 0;
            }
            $form = $this->register_model->get_register_form2($courseid, $slotid);
        }

        $data = array('form' => $form);
        $this->load->view('header_view');
        $this->load->view('register_view', $data);
        $this->load->view('footer_view');
    }

}
