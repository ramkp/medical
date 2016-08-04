<?php

/**
 * Description of Register
 *
 * @author sirromas
 */
class Register extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('register_model');
    }

    public function index() {
        
        /*
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
        }
        */
        
        $courseid = $this->uri->segment(3);
        $slotid = $this->uri->segment(4);
        if ($courseid == null) {
            $form = $this->register_model->get_register_form();
        } // end if $courseid==null
        else {
            if ($slotid == '') {
                $slotid = 0;
            }
            $form = $this->register_model->get_register_form($courseid, $slotid);
        }

        $data = array('form' => $form);
        $this->load->view('header_view');
        $this->load->view('register_view', $data);
        $this->load->view('footer_view');
    }

}
