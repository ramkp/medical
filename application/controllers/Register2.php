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

    function group_register() {
        $form = $this->register_model->get_group_register_form();
        $data = array('form' => $form);
        $this->load->view('header_view');
        $this->load->view('group_register_view1', $data);
        $this->load->view('footer_view');
    }

    function get_group_users_block() {
        $total = $_POST['total'];
        $users = $this->register_model->get_group_users_block($total);
        echo $users;
    }

    function get_group_course_fee() {
        $courseid = $_POST['courseid'];
        $slotid = $_POST['slotid'];
        $total = $_POST['total'];
        $fee = $this->register_model->get_group_course_fee($courseid, $slotid, $total);
        echo $fee;
    }

    function is_group_exists() {
        $name = $_POST['name'];
        $status = $this->register_model->is_group_exists($name);
        echo $status;
    }

    function is_username_exists() {
        $email = $_POST['email'];
        $status = $this->register_model->is_username_exists($email);
        echo $status;
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

    function auth_group_renew() {
        $group = new stdClass();
        $group->courseid = $this->uri->segment(3);
        $group->period = $this->uri->segment(4);
        $group->users = $this->uri->segment(5);
        $form = $this->register_model->get_group_renew_payer_form($group);
        $data = array('form' => $form);
        $this->load->view('header_view');
        $this->load->view('group_auth_view_a', $data);
        $this->load->view('footer_view');
    }

    function auth_group_renew_pay() {
        $group= $this->uri->segment(3);
        $form = $this->register_model->get_group_renew_payment_form($group);
        $data = array('form' => $form);
        $this->load->view('header_view');
        $this->load->view('auth_group_renew', $data);
        $this->load->view('footer_view');
    }
    
    function proceed_group_renew_payment () {
        $payment=$this->uri->segment(3);
        $form = $this->register_model->process_group_renew_payment($payment);
        $data = array('form' => $form);
        $this->load->view('header_view');
        $this->load->view('process_group_renew', $data);
        $this->load->view('footer_view');
       
    }

    function any_auth_pay() {
        $user = new stdClass();
        $user->userid = $this->uri->segment(3);
        $user->courseid = $this->uri->segment(4);
        $user->slotid = $this->uri->segment(5);
        $user->amount = $this->uri->segment(6);
        $user->period = $this->uri->segment(7);

        $form = $this->register_model->get_any_auth_pay_form($user);
        $data = array('form' => $form);
        $this->load->view('header_view');
        $this->load->view('any_auth_pay', $data);
        $this->load->view('footer_view');
    }

    function proceed_any_auth_payment() {
        $response = $this->uri->segment(3);
        $form = $this->register_model->process_any_auth_payment($response);
        $data = array('form' => $form);
        $this->load->view('header_view');
        $this->load->view('auth_add_any_payment', $data);
        $this->load->view('footer_view');
    }

    function group_payment_card() {
        $regdata = $this->uri->segment(3);
        $form = $this->register_model->get_braintree_group_payment_form($regdata);
        $data = array('form' => $form);
        $this->load->view('header_view');
        $this->load->view('group_register_view2', $data);
        $this->load->view('footer_view');
    }

    function group_payment_paypal() {
        $regdata = $this->uri->segment(3);
        $form = $this->register_model->get_paypal_group_payment_form($regdata);
        $data = array('form' => $form);
        $this->load->view('header_view');
        $this->load->view('group_paypal_payment', $data);
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

    function new_payment_card() {
        $user = $this->uri->segment(3);
        $form = $this->register_model->get_brain_card_form($user);
        $data = array('form' => $form);
        $this->load->view('header_view');
        $this->load->view('brain_card_test', $data);
        $this->load->view('footer_view');
    }

    function payment_auth_single() {
        $user = $this->uri->segment(3);
        $form = $this->register_model->get_auth_card_form($user);
        $data = array('form' => $form);
        $this->load->view('header_view');
        $this->load->view('auth_view', $data);
        $this->load->view('footer_view');
    }

    function proceed_auth_card() {
        $response = $this->uri->segment(3);
        $form = $this->register_model->process_auth_payment($response);
        $data = array('form' => $form);
        $this->load->view('header_view');
        $this->load->view('auth_register_individual', $data);
        $this->load->view('footer_view');
    }

    function cancel_auth_card() {
        $form = $this->register_model->get_cancel_auth_card_payment_page();
        $data = array('form' => $form);
        $this->load->view('header_view');
        $this->load->view('register_view', $data);
        $this->load->view('footer_view');
    }

    function payment_auth_group() {
        $regdata = $this->uri->segment(3);
        $form = $this->register_model->get_authorize_group_payment_form_step1($regdata);
        $data = array('form' => $form);
        $this->load->view('header_view');
        $this->load->view('auth_register_group', $data);
        $this->load->view('footer_view');
    }

    function proceed_group_auth_card() {
        $response = $this->uri->segment(3);
        $form = $this->register_model->process_group_auth_payment($response);
        $data = array('form' => $form);
        $this->load->view('header_view');
        $this->load->view('auth_register_group_complete', $data);
        $this->load->view('footer_view');
    }

    function get_auth_group_hosted_form() {
        $regdata = $this->uri->segment(3);
        $form = $this->register_model->get_authorize_group_payment_form_step2($regdata);
        $data = array('form' => $form);
        $this->load->view('header_view');
        $this->load->view('auth_register_group', $data);
        $this->load->view('footer_view');
    }

    function add_individual_registration() {
        $token = $_REQUEST['token'];
        //echo "Token: " . $token . "<br>";
        $this->register_model->add_individual_registration($token);
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

    function receive_paypal_group_register_payment() {
        $payment = $_REQUEST;
        $form = $this->register_model->process_paypal_group_payment($payment);
        $data = array('form' => $form);
        $this->load->view('header_view');
        $this->load->view('receive_paypal_group_payment', $data);
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
