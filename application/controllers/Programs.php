<?php

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
        //$cat_name = $this->uri->segment(3); 
        $catname = $this->uri->segment(3);
        switch ($catname) {
            case "certification-workshops":
                $catid = 2;
                break;
            case "online-courses":
                $catid = 3;
                break;
            case "certification-exams":
                $catid = 4;
                break;
            case "career-courses":
                $catid = 5;
                break;
        }

        //$catid = $this->uri->segment(3);
        //$program_items = $this->program_model->get_category_items($cat_name);
        $program_items = $this->program_model->get_category_items($catid);
        $data = array('items' => $program_items);
        $this->load->view('header_view');
        $this->load->view('program_view', $data);
        $this->load->view('footer_view');
    }

    public function schedule() {
        $coursename = $this->uri->segment(3);

        switch ($coursename) {
            case "certified-nurse-assistant":
                $courseid = 41;
                break;
            case "phlebotomy-workshop":
                $courseid = 44;
                break;
            case "phlebotomy-with-ekg-workshop":
                $courseid = 45;
                break;
            case "picc-line-workshop":
                $courseid = 46;
                break;
            case "iv-therapy-certification-exam":
                $courseid = 47;
                break;
            case "ob-tech-caertification-exam":
                $courseid = 49;
                break;
            case "ob-tech-certification-exam":
                $courseid = 49;
                break;
            case "ob-tech-technician-complete-program":
                $courseid = 53;
                break;
            case "iv-therapy-workshop":
                $courseid = 54;
                break;
            case "medical-assistant":
                $courseid = 55;
                break;
            case "medical-administrator-assistant":
                $courseid = 56;
                break;
            case "phlebotomy-technician-certification":
                $courseid = 57;
                break;
            case "cpr-program":
                $courseid = 58;
                break;
                
        }

        $program_items = $this->program_model->get_schedule_page($courseid);
        $data = array('items' => $program_items);
        $this->load->view('header_view');
        $this->load->view('program_view', $data);
        $this->load->view('footer_view');
    }

    public function detailes() {
        $keywords = "";
        $courseid = $this->uri->segment(3);
        if ($courseid == 44) {
            $keywords = "phlebotomy training online, phlebotomy classes online";
        }
        if ($courseid == 57) {
            $keywords = "phlebotomy certification online , phlebotomy certification exam";
        }
        $course = $this->program_model->get_item_detail_page($courseid, $keywords);
        $data = array('items' => $course);
        $this->load->view('header_view');
        $this->load->view('program_view', $data);
        $this->load->view('footer_view');
    }

    public function searh_result() {
        $item = $this->uri->segment(3);
    }

}
