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

    public function get_programs_list() {
        $list = "";
        $list.="<select id='program' name='program' class='form-control' style='width:100%'>";
        $list.="<option value='0' selected>Program</option>";
        $query = "select * from mdl_course where cost>0 and visible=1";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $list.="<option value='$row->fullname'>$row->fullname</option>";
        }
        $list.="</select>";
        return $list;
    }

    public function get_contact_form() {
        $antispam = new Antispam();
        $configs = array(
            'img_path' => './captcha/',
            'img_url' => 'http://' . $_SERVER['SERVER_NAME'] . '/captcha/',
            'font_path' => $_SERVER['DOCUMENT_ROOT'] . '/application/fonts/',
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
        $programs_list = $this->get_programs_list();

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
        $list.="<span class='span9' style='font-weight:bold;font-size:+1;'>Send message<br><br></span>";
        $list.="</div>";

        $list.="<div class='container-fluid'>";
        $list.="<span class='span2'>First name*</span>";
        $list.="<span class='span2'><input type='text' id='firstname' class='' name='firstname'></span>";
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
        $list.="<span class='span2'>Program*</span>";
        $list.="<span class='span2'>$programs_list</span>";
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

    function get_new_contact_form() {
        $list = "";
        $captcha = $this->get_captcha();
        $programs_list = $this->get_programs_list();
        $list.="<div class='panel panel-default' id='program_section' style='margin-bottom:0px;'>";
        $list.="<div class='panel-body'>";

        $list.="<div class='form-group' style='text-align:left;'>";
        $list.=" <label for='firstname'>First Name*:</label>";
        $list.=" <input type='text' class='form-control' id='firstname' style='width:97%;'>";
        $list.="</div>";

        $list.="<div class='form-group' style='text-align:left;'>";
        $list.=" <label for='lastname'>Last Name*:</label>";
        $list.=" <input type='text' class='form-control' id='lastname' style='width:97%;'>";
        $list.="</div>";

        $list.="<div class='form-group' style='text-align:left;'>";
        $list.=" <label for='email'>Email*:</label>";
        $list.=" <input type='text' class='form-control' id='email' style='width:97%;'>";
        $list.="</div>";

        $list.="<div class='form-group' style='text-align:left;'>";
        $list.=" <label for='phone'>Phone*:</label>";
        $list.=" <input type='text' class='form-control' id='phone' placeholder='(___) ___-____' style='width:97%;'>";
        $list.="</div>";

        $list.="<div class='form-group' style='text-align:left;'>";
        $list.=" <label for='program'>Program*:</label>";
        $list.="$programs_list";
        $list.="</div>";

        $list.="<div class='form-group' style='text-align:left;'>";
        $list.="<label for='usr'>Message:</label>";
        $list.="<textarea class='form-control' rows='5' id='message' style='width:97%;'></textarea>";
        $list.="</div>";

        $list.="<div class='form-group' style='text-align:left;'>";
        $list.=" <label for='program'>Please submit the captcha*:</label>";
        $list.="" . $captcha['image'] . "&nbsp;&nbsp;&nbsp; <input type='text' class='form-control' id='captcha' style=''>&nbsp;&nbsp;&nbsp; <button id='contact_button' class='btn btn-primary'>Send</button>";
        $list.="</div>";

        $list.="<div class='row-fluid' style='text-align:left;'>";
        $list.="<span class='span9' id='contact_result'></span>";
        $list.="</div>";

        $list.="</div>";
        $list.="</div>";

        return $list;
    }

    function get_email_icon() {
        $list = "";
        $list.="<span class='fa-stack fa-2x'>
                <i class='fa fa-circle-thin fa-stack-2x'></i>
                <i class='fa fa-envelope fa-stack-1x'></i>
                </span>";
        return $list;
    }

    function get_contact_item_value($item) {
        $query = "select * from mdl_contact_page where item='$item'";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $value = $row->value;
        }
        return $value;
    }

    function get_email_address_block() {
        $list = "";
        $value = $this->get_contact_item_value('email');
        $list.="<div class='row-fluid'>";
        $list.="<span class='span9' style='font-weight:bold;'>EMAIL ADDRESS:</span>";
        $list.="</div>";
        $list.="<div class='row-fluid'>";
        $list.="<span class='span9' style=''>$value</span>";
        $list.="</div>";
        return $list;
    }

    function get_phone_icon() {
        $list = "";
        $list.="<span class='fa-stack fa-2x'>
                <i class='fa fa-circle-thin fa-stack-2x'></i>
                <i class='fa fa-phone fa-stack-1x'></i>
                </span>";
        return $list;
    }

    function get_address_icon() {
        $list = "";
        $list.="<span class='fa-stack fa-2x'>
                <i class='fa fa-circle-thin fa-stack-2x'></i>
                <i class='fa fa-flag fa-stack-1x'></i>
                </span>";
        return $list;
    }

    function get_phone_number_block() {
        $list = "";
        $value1 = $this->get_contact_item_value('phone1');
        $value2 = $this->get_contact_item_value('phone2');
        $list.="<div class='row-fluid'>";
        $list.="<span class='span9' style='font-weight:bold;'>PHONE NUMBER:</span>";
        $list.="</div>";
        $list.="<div class='row-fluid'>";
        $list.="<span class='span9' style=''>$value1</span>";
        $list.="</div>";
        $list.="<div class='row-fluid'>";
        $list.="<span class='span9' style=''>$value2</span>";
        $list.="</div>";
        return $list;
    }

    function get_fax_icon() {
        $list = "";
        $list.="<span class='fa-stack fa-2x'>
                <i class='fa fa-circle-thin fa-stack-2x'></i>
                <i class='fa fa-fax fa-stack-1x'></i>
                </span>";
        return $list;
    }

    function get_fax_number_block() {
        $list = "";
        $value = $this->get_contact_item_value('fax');
        $list.="<div class='row-fluid'>";
        $list.="<span class='span9' style='font-weight:bold;'>FAX NUMBER:</span>";
        $list.="</div>";
        $list.="<div class='row-fluid'>";
        $list.="<span class='span9' style=''>$value</span>";
        $list.="</div>";
        return $list;
    }

    function get_address_block() {
        $list = "";
        $value = $this->get_contact_item_value('address');
        $list.="<div class='row-fluid'>";
        $list.="<span class='span9' style='font-weight:bold;'>MAILING ADDRESS:</span>";
        $list.="</div>";
        $list.="<div class='row-fluid'>";
        $list.="<span class='span9' style=''>$value</span>";
        $list.="</div>";

        /*
          $list.="<div class='row-fluid'>";
          $list.="<span class='span9' style=''>1830A North Gloster St</span>";
          $list.="</div>";
          $list.="<div class='row-fluid'>";
          $list.="<span class='span9' style=''>Tupelo,MS 38804</span>";
          $list.="</div>";
         */

        return $list;
    }

    function get_captcha() {
        $antispam = new Antispam();
        $configs = array(
            'img_path' => './captcha/',
            'img_url' => 'http://' . $_SERVER['SERVER_NAME'] . '/captcha/',
            'font_path' => $_SERVER['DOCUMENT_ROOT'] . '/application/fonts/',
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
        return $cap;
    }

    function get_contact_data() {
        $list = "";

        $email = $this->get_email_address_block();
        $email_icon = $this->get_email_icon();

        $phone = $this->get_phone_number_block();
        $phone_icon = $this->get_phone_icon();

        $fax = $this->get_fax_number_block();
        $fax_icon = $this->get_fax_icon();

        $addess = $this->get_address_block();
        $address_icon = $this->get_address_icon();

        $list.="<div class='row-fluid'>";
        $list.="<span class='span2'>$email_icon</span>";
        $list.="<span class='span6'>$email</span>";
        $list.="</div>";

        $list.="<div class='row-fluid'>";
        $list.="<span class='span8'><br>&nbsp;<br></span>";
        $list.="</div>";

        $list.="<div class='row-fluid'>";
        $list.="<span class='span2'>$phone_icon</span>";
        $list.="<span class='span6'>$phone</span>";
        $list.="</div>";

        $list.="<div class='row-fluid'>";
        $list.="<span class='span8'><br>&nbsp;<br></span>";
        $list.="</div>";

        $list.="<div class='row-fluid'>";
        $list.="<span class='span2'>$fax_icon</span>";
        $list.="<span class='span6'>$fax</span>";
        $list.="</div>";

        $list.="<div class='row-fluid'>";
        $list.="<span class='span8'><br>&nbsp;<br></span>";
        $list.="</div>";

        $list.="<div class='row-fluid'>";
        $list.="<span class='span2'>$address_icon</i></span>";
        $list.="<span class='span6'>$addess</span>";
        $list.="</div>";

        return $list;
    }

    function get_contact_page() {
        $list = "";

        $form = $this->get_new_contact_form();
        $contacts = $this->get_contact_data();

        $list.="<br/><div  class='form_div'>";

        $list.="<div class='row-fluid'>";
        $list.="<span class='span6'>$form</span>";
        $list.="<span class='span6' style='text-align:left;'>$contacts</span>";
        $list.="</div>";

        $list.="<br><div class='row-fluid'>";
        $list.="<span class='span12'><iframe
            width='100%'
            height='465'
            frameborder='0' style='border:0'
            src='https://www.google.com/maps/embed/v1/place?key=AIzaSyAo9XGd-Ss75Cnfqqu41SdDvlwRu1WYKB0
            &q=Medical2+Inc, Tupelo+MS' allowfullscreen>
            </iframe></span>";
        $list.="</div>";

        $list.="</div>";


        return $list;
    }

}
