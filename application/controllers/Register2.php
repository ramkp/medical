<?php

/**
 * Description of Register
 *
 * @author sirromas
 */
class Register2 extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('register_model');
    }

    public function index() {

        $courseid = $this->uri->segment(3);
        $slotid = $this->uri->segment(4);
        if ($courseid == null) {
            $form = $this->register_model->get_register_form2();
        } // end if $courseid==null
        else {
            if ($slotid == '') {
                $slotid = 0;
            }
            $form = $this->register_model->get_register_form2($courseid, $slotid);
        }

        $data = array('form' => $form);
        $this->load->view('header_view');
        $this->load->view('register_view', $data);
        $this->load->view('footer_view');
    }

    function school_app() {
        $form = $this->register_model->get_scholl_app_form();
        $data = array('form' => $form);
        $this->load->view('header_view');
        $this->load->view('register_view', $data);
        $this->load->view('footer_view');
    }

    function get_campus_data() {
        $data = $this->register_model->get_campus_locations();
        echo $data;
    }

    function campus() {
        $page = $this->register_model->get_campus_page();
        $data = array('data' => $page);
        $this->load->view('header_view');
        $this->load->view('campus_view', $data);
        $this->load->view('footer_view');
    }

    function brain_register() {
        $courseid = $this->uri->segment(3);
        $slotid = $this->uri->segment(4);
        if ($courseid == null) {
            $form = $this->register_model->get_brain_register_form();
        } // end if $courseid==null
        else {
            if ($slotid == '') {
                $slotid = 0;
            }
            $form = $this->register_model->get_brain_register_form($courseid, $slotid);
        } // end if 

        $data = array('form' => $form);
        $this->load->view('header_view');
        $this->load->view('register_view', $data);
        $this->load->view('footer_view');
    }

    function any_pay() {
        $user = new stdClass();
        $user->userid = $this->uri->segment(3);
        $user->courseid = $this->uri->segment(4);
        $user->slotid = $this->uri->segment(5);
        $user->amount = $this->uri->segment(6);
        $user->period = $this->uri->segment(7);

        $form = $this->register_model->get_any_pay_payment_form($user);
        $data = array('form' => $form);
        $this->load->view('header_view');
        $this->load->view('any_pay_view', $data);
        $this->load->view('footer_view');
    }

    function payment_card() {
        $user = $this->uri->segment(3);
        $form = $this->register_model->get_brain_card_form($user);
        $data = array('form' => $form);
        $this->load->view('header_view');
        $this->load->view('brain_card', $data);
        $this->load->view('footer_view');
    }

    function payment_paypal() {
        $user = $this->uri->segment(3);
        $form = $this->register_model->get_brain_paypal_form($user);
        $data = array('form' => $form);
        $this->load->view('header_view');
        $this->load->view('register_view', $data);
        $this->load->view('footer_view');
    }

    function receive_paypal_register_payment() {
        $payment = $_REQUEST;
        $form = $this->register_model->process_paypal_payment($payment);
        $data = array('form' => $form);
        $this->load->view('header_view');
        $this->load->view('register_view', $data);
        $this->load->view('footer_view');
    }

    function group_renew() {
        $cert = new stdClass();
        $cert->courseid = $this->uri->segment(3);
        $cert->period = $this->uri->segment(4);
        $cert->users = base64_decode($this->uri->segment(5));
        $form = $this->register_model->get_group_renew_form($cert);
        $data = array('form' => $form);
        $this->load->view('header_view');
        $this->load->view('group_renew_view', $data);
        $this->load->view('footer_view');
    }

}
