<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Programs
 *
 * @author sirromas
 */
class Programs extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('program_model');
    }

    public function workshops() {
        $cat_name = 'workshop';
        $program_items = $this->program_model->get_category_items($cat_name);
        $data = array('items' => $program_items);
        $this->load->view('header_view');
        $this->load->view('program_view', $data);
        $this->load->view('footer_view');
    }

    public function courses() {
        $cat_name = 'course';
        $program_items = $this->program_model->get_category_items($cat_name);
        $data = array('items' => $program_items);
        $this->load->view('header_view');
        $this->load->view('program_view', $data);
        $this->load->view('footer_view');
    }

    public function exams() {
        $cat_name = 'exam';
        $program_items = $this->program_model->get_category_items($cat_name);
        $data = array('items' => $program_items);
        $this->load->view('header_view');
        $this->load->view('program_view', $data);
        $this->load->view('footer_view');
    }

    public function school() {
        $program_items = $this->program_model->get_school_page();
        $data = array('items' => $program_items);
        $this->load->view('header_view');
        $this->load->view('program_view', $data);
        $this->load->view('footer_view');
    }

}
