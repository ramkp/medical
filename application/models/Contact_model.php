<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/application/libraries/antispam.php';

class Contact_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('captcha');
    }

    public function get_contact_page_data() {
        $list = "";

        $query = "select * from mdl_contact_page where id=1";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $list.="<div class='container-fluid' style='text-align:center;>";
            $list.="<span class='span9'>$row->content</span>";
            $list.="</div>";
        } // end foreach
        return $list;
    }

    public function get_contact_form() {   
        $antispam = new Antispam();
        $configs = array(
            'img_path' => './captcha/',
            'img_url' => 'http://'.$_SERVER['SERVER_NAME'] . '/captcha/',            
            'font_path' => $_SERVER['DOCUMENT_ROOT'].'/application/fonts/',
            'img_height' => '50',
            'font_size' => 16
        );
        $cap = $antispam->get_antispam_image($configs);

        $data = array(
            'captcha_time' => $cap['time'],
            'ip_address' => $this->input->ip_address(),
            'word' => $cap['word']
        );

        $query = $this->db->insert_string('mdl_captcha', $data);
        $this->db->query($query);

        $list = "";
        $list.="<br/><div  class='form_div'>";

        $list.="<div class='panel panel-default' id='program_section' style='margin-bottom:0px;'>";
        $list.="<div class='panel-heading' style='text-align:left;'><h5 class='panel-title'>Contact US</h5></div>";
        $list.="<div class='panel-body'>";

        $list.=$this->get_contact_page_data();

        $list.="<div class='container-fluid'>";
        $list.="<span class='span9'>&nbsp;</span>";
        $list.="</div>";

        $list.="<div>";
        $list.="<div class='container-fluid' style='text-align:center;'>";
        $list.="<span class='span9'>&nbsp;</span>";
        $list.="</div>";

        $list.="<div class='container-fluid'>";
        $list.="<span class='span2'>First name*</span>";
        $list.="<span class='span2'><input type='text' id='firstname' name='firstname'></span>";
        $list.="<span class='span2' >Last name*</span>";
        $list.="<span class='span2'><input type='text' id='lastname' name='lastname'></span>";
        $list.="</div>";

        $list.="<div class='container-fluid'>";
        $list.="<span class='span2' >Email*</span>";
        $list.="<span class='span2'><input type='text' id='email' name='email'></span>";
        $list.="<span class='span2' >Phone*</span>";
        $list.="<span class='span2'><input type='text' id='phone' name='phone'></span>";
        $list.="</div>";

        $list.="<div class='container-fluid'>";
        $list.="<span class='span2'>Message*</span>";
        $list.="<span  class='span2'><textarea id='message' name='message' rows='4' ></textarea></span>";
        $list.="</div>";

        $list.="<div class='container-fluid'>";
        $list.="<span class='span2'>Please submit the captcha*:</span>";
        $list.="<span class='span2'>" . $cap['image'] . "</span>";
        $list.="<span class='span2'><input type='text' id='captcha' name='captcha' value=''></span>";
        $list.="</div>";

        $list.="<div class='container-fluid'>";
        $list.="<span class='span2'><button class='btn btn-primary' id='contact_button'>Submit</button></span>";
        $list.="<span class='span6' id='contact_result'></span>";
        $list.="</div>";

        $list.="</div>"; // end of panel-body
        $list.="</div>"; // end of panel panel-default
        $list.="</div></div>"; // end of form div

        return $list;
    }

}
