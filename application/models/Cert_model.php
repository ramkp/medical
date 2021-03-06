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


        $list.="<div class='container-fluid' style='text-align:center;'>";

        $list.="<span class='span2'>Firstname*</span>";
        $list.="<span class='span2'><input type='text' id='fname' name='fname' ></span>";

        $list.="<span class='span2'>Lastname*</span>";
        $list.="<span class='span2'><input type='text' id='lname' name='lname' ></span>";
        $list.="</div>";

        //$list.="<span class='span4'>Certificate No*</span>";
        //$list.="<span class='span4'><input type='text' id='cert_no' name='cert_no' ></span>";
        //$list.="</div>"; // end of container-fluid

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

    function get_renew_certification_page() {
        $list = "";
        $query = "select * from mdl_renew_certificate_page where id=1";
        $result = $this->db->query($query);
        foreach ($result->result() as $row) {
            $page=$row->content;
        }
        $list.="<div class='row-fluid'>";
        $list.="<div class='form_div'><br>$page</div>";
        $list.="</div>";
        
        return $list;
    }

}
