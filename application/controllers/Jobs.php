<?php

class Jobs extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('jobs_model');
    }

    public function instructor() {
        $page = $this->jobs_model->get_jobs_instructor_page();
        $data = array('page' => $page);
        $this->load->view('header_view');
        $this->load->view('about_view', $data);
        $this->load->view('footer_view');
    }

    public function find() {
        $page = $this->jobs_model->get_jobs_student_page();
        $data = array('page' => $page);
        $this->load->view('header_view');
        $this->load->view('about_view', $data);
        $this->load->view('footer_view');
    }

}
