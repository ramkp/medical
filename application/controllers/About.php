<?php

class About extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('about_model');
	}

	public function index () {
		$page = $this->about_model->get_about_page();
		$data = array('page' => $page);
		$this->load->view('header_view');
		$this->load->view('about_view', $data);
		$this->load->view('footer_view');
	}

}