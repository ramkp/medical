<?php


/**
 * Description of Login
 *
 * @author sirromas
 */
class Login extends CI_Controller {
    
    public function index () {
        $this->load->view('header_view');
        $this->load->view('login_view');
        $this->load->view('footer_view');
    }
    
}
