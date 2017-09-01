<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/unirest/vendor/autoload.php';

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
        $this->load->view('jobs_view', $data);
        $this->load->view('footer_view');
    }

    public function find2() {
        $page = $this->jobs_model->get_jobs_student_page2();
        $data = array('page' => $page);
        $this->load->view('header_view');
        $this->load->view('jobs_view', $data);
        $this->load->view('footer_view');
    }

    function getHtml($url, $post = null) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        if (!empty($post)) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    public function proces_job_search_results() {
        $jobs = json_decode($_POST['result']);
        $page = $this->jobs_model->proces_job_search_results($jobs);
        echo $page;
        
        /*
         * 
        $data = array('page' => $page);
        $this->load->view('header_view');
        $this->load->view('jobs_view', $data);
        $this->load->view('footer_view');
         * 
         */
    }

}
