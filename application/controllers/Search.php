<?php

/**
 * Description of Search
 *
 * @author sirromas
 */
class Search extends CI_Controller {

    public function index() {
        $this->load->view('header_view');
        $this->load->view('search_view');
        $this->load->view('footer_view');
    }

}
