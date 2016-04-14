<?php

class Contact_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
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
        $list = "";
        $list.="<br/><div  class='form_div'>";

        $list.="<div class='panel panel-default' id='program_section' style='margin-bottom:0px;'>";
        $list.="<div class='panel-heading' style='text-align:left;'><h5 class='panel-title'>Contact US</h5></div>";
        $list.="<div class='panel-body'>";

        $list.=$this->get_contact_page_data();

        $list.="<div class='container-fluid'>";
        $list.="<span class='span9'>&nbsp;</span>";
        $list.="</div>";

        
        /*
         * 
          $list.="<div>";
          $list.="<div class='container-fluid' style='text-align:center;'>";
          $list.="<span class='span2'>Email: </span><span class='span2'>info@medical2.com</span><span class='span2'>International and Local:</span><span class='span2'>Toll Free:1-877-741-1996  Phone: 1-662--844-9400</span>";
          $list.="</div>";

          $list.="<div>";
          $list.="<div class='container-fluid' style='text-align:center;'>";
          $list.="<span class='span2'>Fax: </span><span class='span2'>1 407 233-1192</span><span class='span2'> Mailing Address:</span><span class='span2'>Medical2 Inc 1830 North Gloster St. Suite-A.  Tupelo, MS 38804 </span>";
          $list.="</div>";

          $list.="<div class='container-fluid' style='text-align:center;'>";
          $list.="<span class='span2'>Office Hours: </span><span class='span7' style='text-align:left;'> Mon-Fri  9 am To 5 pm CST</span>";
          $list.="</div>";
         * 
         */

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
        //$list.="<span class='span6' style='text-align:left;'>&nbsp;&nbsp;<textarea id='message' name='message' rows='4' cols='75'></textarea></span>";
        $list.="<span  class='span2'><textarea id='message' name='message' rows='4' ></textarea></span>";
        $list.="</div>";

        $list.="<div class='container-fluid'>";
        $list.="<span class='span2'><button class='btn btn-primary' id='contact_button'>Submit</button></span>";
        $list.="<span class='span6' id='contact_result'></span>";
        $list.="</div>";

        $list.="</div>"; // end of panel-body
        $list.="</div>"; // end of panel panel-default
        $list.="</div>"; // end of form div

        return $list;
    }

}
