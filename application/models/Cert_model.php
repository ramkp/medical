<?php

/**
 * Description of Cert_model
 *
 * @author sirromas
 */
class Cert_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_cert_form() {
        $list = '';
        $list.="<br/><div  class='form_div'>";
        $list.="<div class='panel panel-default' id='program_section' style='margin-bottom:0px;'>";
        $list.="<div class='panel-heading' style='text-align:left;'><h5 class='panel-title'>Verify Certification</h5></div>";
        $list.="<div class='panel-body'>";


        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'>Firstname Lastname*</span>";
        $list.="<span class='span2'><input type='text' id='cert_fio' name='cert_fio' ></span>";
        $list.="<span class='span2'>Certificate No*</span>";
        $list.="<span class='span2'><input type='text' id='cert_no' name='cert_no' ></span>";
        $list.="</div>"; // end of container-fluid

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span2'><button class='btn btn-primary' id='verify_cert'>Verify Certificate</button></span>";
        $list.="<span class='span6'><span id='cert_err' style='color:red;'></span></span>";
        $list.="</div>"; // end of container-fluid
        
        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<span class='span6' style='color:red;' id='verify_cert_err'></span>";
        $list.="</div>";
        
        $list.="</div>"; // end of panel-body
        $list.="</div></div>"; // end of panel panel-default

        return $list;
    }

}
