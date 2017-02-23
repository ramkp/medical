<?php

/**
 * Description of Unsubscribe_model
 *
 * @author moyo
 */
class Unsubscribe_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_user_email_address($userid) {
        $query = "select * from mdl_user where id=$userid";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $email = $row->email;
        }
        return $email;
    }

    public function unsubscribe_user($userid) {
        $query = "insert into mdl_unsubscribe (userid) values ($userid)";
        $this->db->query($query);
    }

}
