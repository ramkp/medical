<?php

class Contact_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_contact_form() {
        $list = "";
        $list.="<br/><div  class='form_div'>";
        $list.="<div class='panel panel-default' id='program_section' style='margin-bottom:0px;'>";
        $list.="<div class='panel-heading' style='text-align:left;'><h5 class='panel-title'>Contact US</h5></div>";
        $list.="<div class='panel-body'>";

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
        $list.="<span class='span3' id='contact_result'></span>";
        $list.="</div>";

        $list.="</div>"; // end of panel-body
        $list.="</div>"; // end of panel panel-default
        $list.="</div>"; // end of form div

        return $list;
    }

}
