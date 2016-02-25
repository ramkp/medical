<?php

/**
 * Description of Groups
 *
 * @author sirromas
 */
class Groups extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('groups_model');
    }

    public function index() {
        $form = $this->groups_model->get_private_group_form();
        $data = array('form' => $form);
        $this->load->view('header_view');
        $this->load->view('groups_view', $data);
        $this->load->view('footer_view');
    }

}
