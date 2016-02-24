<?php

/**
 * Description of Index
 *
 * @author sirromas
 */
class Index extends CI_Controller {

    public function __construct() {
        parent::__construct();        
    }

    public function index() {
        $this->load->view('header_view');
        $this->load->view('index_view');
        $this->load->view('footer_view');
        $this->output->enable_profiler(TRUE);
    }

}
