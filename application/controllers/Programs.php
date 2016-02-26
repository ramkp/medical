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

    public function program() {
        $cat_name = $this->uri->segment(3);
        if ($cat_name != 'school') {
            $program_items = $this->program_model->get_category_items($cat_name);
        } // end if $cat_name !='school'
        else {
            $program_items = $this->program_model->get_school_page($cat_name);
        } // end else        
        $data = array('items' => $program_items);
        $this->load->view('header_view');
        $this->load->view('program_view', $data);
        $this->load->view('footer_view');
    }

}
