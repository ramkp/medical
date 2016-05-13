<?php

/**
 * Description of Payment
 *
 * @author sirromas
 */
class Payments extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('payment_model');
    }

    public function index() {
        $form = "";
        $userid = $this->uri->segment(3);
        $courseid=$this->uri->segment(4);        
        //die('It is payment controller :)');        
        if ($userid != null) {
            if (is_numeric($userid)) {
                $user_exists = $this->payment_model->is_user_exissts($userid);                
                if ($user_exists > 0) {
                    $form = $this->payment_model->get_payment_section($userid,$courseid);
                } // end if $user_exists>0
                else {
                    $form.="<br><div class='container-fluid' style='text-align:center;'>";
                    $form.= "<span class='span12'>Invalid data provided</span>";
                    $form.="</div><br>";
                }
            } // end if is_int($userid)
            else {
                $form.="<br><div class='container-fluid' style='text-align:center;'>";
                $form.= "<span class='span12'>Invalid data provided</span>";
                $form.="</div><br>";
            }
        } // end if $userid!=null
        else {
            $form.="<br><div class='container-fluid' style='text-align:center;'>";
            $form.= "<span class='span12'>Invalid data provided</span>";
            $form.="</div><br>";
        }

        $data = array('form' => $form);
        $this->load->view('header_view');
        $this->load->view('payment_view', $data);
        $this->load->view('footer_view');
    }

}
