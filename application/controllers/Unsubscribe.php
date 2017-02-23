<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Unsubscribe
 *
 * @author moyo
 */
class Unsubscribe extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('unsubscribe_model');
    }

    public function index() {
        $userid = $this->uri->segment(3);
        $this->unsubscribe_model->unsubscribe_user($userid);
        $email = $this->unsubscribe_model->get_user_email_address($userid);
        $page = "<br><p align='center'>Your email address ($email) was successfully unsubscribed</p><br>";
        $data = array('page' => $page);
        $this->load->view('header_view');
        $this->load->view('about_view', $data);
        $this->load->view('footer_view');
    }

}
