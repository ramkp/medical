<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Payment_model
 *
 * @author sirromas
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/Payment.php';


class Payment_model extends CI_Model {

    public $payment;

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->payment = new Payment();
    }

    public function get_user_course($userid) {
        $query = "select enrolid, userid "
                . "from mdl_user_enrolments "
                . "where userid=$userid";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $enrolid = $row->enrolid;
        }

        $query = "select id, courseid from mdl_enrol where id=$enrolid";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $courseid = $row->courseid;
        }
        return $courseid;
    }

    public function get_user_data($userid) {
        $query = "select * "
                . "from mdl_user where id=$userid";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $user = new stdClass();
            foreach ($row as $key => $value) {
                $user->$key = $value;
            }
        }
        return $user;
    }

    public function get_course_payment_options($courseid, $group = null) {
        $query = "select installment, num_payments "
                . "from mdl_course "
                . "where id=$courseid";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $inst = $row->installment;
            $num_payments = $row->num_payments;
        }
        $period = $this->payment->get_course_enrollment_period($courseid);
        $data = array('id' => $courseid, 'inst' => $inst, 'num_payments' => $num_payments, 'group' => $group, 'period' => $period);
        return $data;
    }

    public function is_group_member($userid) {
        $query = "select id, userid "
                . "from mdl_groups_members "
                . "where userid=$userid";
        $result = $this->db->query($query);
        $num = $result->num_rows();
        return $num;
    }

    public function get_user_group($userid) {
        $query = "select id, groupid, userid "
                . "from mdl_groups_members "
                . "where userid=$userid";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $groupid = $row->groupid;
        }

        $query = "select id, name from mdl_groups where id=$groupid";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $name = $row->name;
        }
        return $name;
    }

    public function get_user_slot($userid) {
        $query = "select * from mdl_scheduler_appointment "
                . "where studentid=$userid";
        $result = $this->db->query($query);
        $num = $result->num_rows();
        if ($num > 0) {
            foreach ($result->result() as $row) {
                $slotid = $row->slotid;
            }
        } // end if $num>0
        else {
            $slotid = 0;
        }
        return $slotid;
    }

    public function get_payment_section($userid, $courseid, $slotid, $sum) {
        $list = "";                
        //echo "Sum inside model: ".$sum."<br>";
        if ($userid != NULL) {
            $invoice = new Invoice();
            $user = $this->get_user_data($userid);            
            $user->courseid = $courseid;
            $user->slotid = $slotid;
            $group_status = $this->is_group_member($userid);
            $installment_status = $invoice->is_installment_user($userid, $courseid);            
            if ($installment_status == 0) {
                if ($group_status == 0) {
                    // Personal signup
                    $group_data = '';
                    $participants = 1;
                    $list.=$this->payment->get_payment_section($group_data, $user, $participants, null, 1,$sum);
                } // end if $group_status==0
                else {
                    // Group member signup
                    $group_name = $this->get_user_group($userid);
                    $group_data = new stdClass();
                    $group_data->group_name = $group_name;
                    $group_data->courseid = $courseid;
                    $participants = 1;
                    $list.=$this->payment->get_payment_section($group_data, $user, $participants, null, 1, $sum);
                } // end else 
            } // end if $installment_status==0
            else {
                $installment_obj = $invoice->get_user_installment_payments($userid, $courseid);
                $installment = array();
                $installment['period'] = 28; // days
                $installment['num_payments'] = $installment_obj->num;
                if ($group_status == 0) {
                    // Personal signup
                    $group_data = '';
                    $participants = 1;
                    $list.=$this->payment->get_payment_section($group_data, $user, $participants, $installment, 1);
                } // end if $group_status==0
                else {
                    // Group member signup
                    $group_name = $this->get_user_group($userid);
                    $group_data = new stdClass();
                    $group_data->group_name = $group_name;
                    $group_data->courseid = $courseid;
                    $participants = 1;
                    $list.=$this->payment->get_payment_section($group_data, $user, $participants, $installment, 1);
                } // end else            
            } // end else when it is installment user
        }  // end if $userid != NULL
        return $list;
    }

    public function is_user_exissts($userid) {
        $query = "select * from mdl_user where id=$userid";
        //echo $query."<br>";
        $result = $this->db->query($query);
        $num = $result->num_rows();
        return $num;
    }

}
