<?php

/**
 * Description of Certificate
 *
 * @author sirromas
 */
set_time_limit(0);
error_reporting('all');
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/pdf/mpdf/mpdf.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/PDF_Label.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/Mailer.php';
require_once ($_SERVER['DOCUMENT_ROOT'] . '/lms/custom/invoices/classes/Invoice.php');
include $_SERVER['DOCUMENT_ROOT'] . "/lms/editor/fckeditor.php";

class Certificates extends Util {

    public $cert_path;
    public $limit = 3;
    public $host;

    function __construct() {
        parent::__construct();
        $this->cert_path = $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/certificates';
        //$this->cert_path = $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/my_dir/certificates';
        $this->host = $_SERVER['SERVER_NAME'];
    }

    function get_course_name($id) {
        $query = "select fullname from mdl_course where id=$id";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $name = $row['fullname'];
        }
        return $name;
    }

    function get_total_certificates() {
        $query = "select * from mdl_certificates order by issue_date";
        $num = $this->db->numrows($query);
        return $num;
    }

    function get_certificates_list() {
        $list = "";

        if ($this->session->justloggedin == 1) {
            $certificates = array();
            $query = "select * from mdl_certificates "
                    . "where userid<>3002 and userid<>3784 "
                    . "order by issue_date desc limit 0, $this->limit";

            $num = $this->db->numrows($query);
            if ($num > 0) {
                $result = $this->db->query($query);
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $certificate = new stdClass();
                    foreach ($row as $key => $value) {
                        $certificate->$key = $value;
                    } // end foreach
                    $certificates[] = $certificate;
                } // end while
            } // end if $num > 0
            $list = $this->create_certificates_list($certificates);
            $_SESSION['total'] = $this->get_total_certificates();
        } // end if
        else {
            $list.="<p>You are not authenticated. &nbsp; <a href='https://medical2.com/login'><button class='btn btn-primary' id='relogin'>Login</button></a></p>";
        } // end else

        return $list;
    }

    function get_pagination_bar() {
        $list = "";
        $query = "select * from mdl_certificates order by issue_date";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $list.="<ul class='pagination'>";
            $result = $this->db->query($query);
            $i = 1;
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $list.="<li style='display:inline;margin-right:10px;'><a href='#' id='cert_page_" . $row['id'] . "' onClick='return false;'>$i</a></li>";
                $i++;
            } // end while
            $list.="</ul>";
        } // end if $num > 0
        return $list;
    }

    function prepare_certificate_tab_content($certificates, $toolbar = true, $search = false) {
        $list = "";

        if (count($certificates) > 0) {
            if ($toolbar == true) {
                $create_cert_block = $this->get_create_box();
                $list.="<div class='container-fluid' style='text-align:center;'>";
                $list.="<span class='span2'>Search</span>";
                $list.="<span class='span2'><input type='text' id='search_certificate' class='typeahead' style='width:125px;' autocomplete='off' spellcheck='false' /></span>";
                $list.="<span class='span3'><button class='btn btn-primary' id='search_certificate_button'>Search</button></span>";
                $list.="<span class='span2'><button class='btn btn-primary' id='clear_certificate_button'>Clear filter</button></span>";
                $list.="</div>";
                $list.="<div class='container-fluid' style='text-align:center;'>";
                $list.="<span class='span8' style='color:red;' id='cert_err'></span>";
                $list.="</div>";
                $list.="<div class='container-fluid' style='text-align:center;'>";
                $list.="<span class='span2'><a href='#' onclick='return false;' id='select_all'>Select all</a></span>";
                $list.="<span class='span2'><a href='#' onclick='return false;' id='deselect_all'>Deselect all</a></span>";
                $list.="<span class='span2'><a href='#' onclick='return false;' id='print_certs'>Print Certificate</a></span>";
                $list.="<span class='span2'><a href='#' onclick='return false;' id='print_labels'>Print Label</a></span>";
                if ($this->user->id == 2) {
                    $list.="<span class='span2'><a href='#' onclick='return false;' id='create_cert'>Create Certificate</a></span>";
                }
                if ($this->user->id == 2) {
                    $list.="<span class='span2'><a href='#' onclick='return false;' id='recertificate'>Re-Certificate</a></span>";
                } // end if $this->user->id==2
                else {
                    $list.="<span class='span2'><a href='#' onclick='return false;' id='renew_cert'>Renew Certificate</a></span>";
                }

                $list.="</div>";

                $list.="<div class='container-fluid' id='cert_container' style='text-align:center;display:none;'>";
                $list.=$create_cert_block;
                $list.="</div>";

                $list.="<div class='container-fluid' style='text-align:center;'>";
                $list.="<span class='span10' id='print_err'></span>";
                $list.="</div>";

                $list.="<div class='container-fluid' style='display:none;text-align:center;' id='ajax_loader'>";
                $list.="<span class='span10'><img src='https://$this->host/assets/img/ajax.gif' /></span>";
                $list.="</div>";
            } // end if $toolbar==true            
            $list.="<div id='certificates_container'>";
            $total = count($certificates);
            if ($total <= $this->limit && $search == false) {
                $total = $this->get_total_certificates();
            } // end if $total<=$this->limit && $search==false
            $list.="<div class='container-fluid' style='text-align:center;font-weight:bold;'>";
            $list.="<span class='span10'>Total certificates: $total</span>";
            $list.="</div>";
            foreach ($certificates as $certificate) {
                $user = $this->get_user_details($certificate->userid);
                $coursename = $this->get_course_name($certificate->courseid);
                $date = date('m-d-Y', $certificate->issue_date);
                $exp_date = date('m-d-Y', $certificate->expiration_date);
                $_exp_date = ($exp_date == '') ? "n/a" : $exp_date;
                //$cert_link = trim(str_replace($_SERVER['DOCUMENT_ROOT'], '', $certificate->path));
                $cert_link = "/lms/custom/certificates/$certificate->userid/$certificate->courseid/certificate.pdf";
                $addr_link = "/lms/custom/certificates/$certificate->userid/label.pdf";
                //$address_block=$this->get_user_address_block($user->id);                
                //echo "Address block: ".$address_block."<br>";
                //if ($date != '1999-12-31') {
                $list.="<div class='container-fluid'>";
                $list.="<span class='span2'>Firstname</span><span class='span2'>$user->firstname</span>";
                $list.="</div>";
                $list.="<div class='container-fluid'>";
                $list.="<span class='span2'>Lastname</span><span class='span2'>$user->lastname</span>";
                $list.="</div>";
                $list.="<div class='container-fluid'>";
                $list.="<span class='span2'>Email</span><span class='span2'>$user->email</span>";
                $list.="</div>";
                /*
                 * 
                  $list.="<div class='container-fluid'>";
                  $list.="<span class='span2'>Address</span><span class='span2'>$address_block</span>";
                  $list.="</div>";
                 * 
                 */

                $list.="<div class='container-fluid'>";
                $list.="<span class='span2'>Program name</span><span class='span6'>$coursename</span>";
                $list.="</div>";

                $list.="<div class='container-fluid'>";
                $list.="<span class='span2'>Certificate</span><span class='span6'><a href='$cert_link' target='_blank'>Print Certificate</a></span>";
                $list.="</div>";

                $list.="<div class='container-fluid'>";
                $list.="<span class='span2'>Address Label</span><span class='span6'><a href='$addr_link' target='_blank'>Print Address Label</a></span>";
                $list.="</div>";

                $list.="<div class='container-fluid'>";
                $list.="<span class='span2'>Issue date</span><span class='span2'>$date</span>";
                $list.="</div>";
                $expired = $this->is_certificate_expired($certificate->courseid);
                if ($expired > 0) {
                    $list.="<div class='container-fluid'>";
                    $list.="<span class='span2'>Expiration date</span><span class='span2'>$_exp_date</span>";
                    $list.="</div>";
                } // end if $expired
                else {
                    $list.="<div class='container-fluid'>";
                    $list.="<span class='span2'>Expiration date</span><span class='span2'>N/A</span>";
                    $list.="</div>";
                }

                $list.="<div class='container-fluid'>";
                $list.="<span class='span2'><input class='cert' type='checkbox' id='$certificate->id' value='$certificate->id'></span><span class='span2'>Select</span>";
                $list.="</div>";

                $list.="<div class='container-fluid'>";
                $list.="<span class='span6'><hr/></span>";
                $list.="</div>";
                //} // end if $date != '1999-12-31'
            } // end foreach
            $list.="</div>";
            if ($toolbar == true) {
                $list.="<div class='container-fluid'>";
                $list.="<span class='span9'  id='pagination'></span>";
                $list.="</div>";
                $list.="<div class='container-fluid'>";
                $list.="<span class='span6'><a href='#' onClick='return false;' id='cert_send_page'>Send Certificate</a></span>";
                $list.="</div>";
                $list.=$this->get_send_certificate_page();
            } // end if $toolbar==true
        } // end if count($certificates)>0
        else {
            $list.="<div class='container-fluid'>";
            $list.="<span class='span6'>There are no certificates issued</span>";
            $list.="</div>";
            $list.="<div class='container-fluid'>";
            $list.="<span class='span6'><a href='#' onClick='return false;' id='cert_send_page'>Send Certificate</a></span>";
            $list.="</div>";
            $list.=$this->get_send_certificate_page();
        } // end else

        return $list;
    }

    function get_certificates_templates_tab() {
        $list = '';
        $courses_list = $this->get_courses_list();
        $list.="<div class='container-fluid'>";
        $list.="<span class='span2'>Program: </span><span class='span6'>$courses_list</span>";
        $list.="</div>";


        $list.="<div class='container-fluid'>";
        $list.="<span class='span2'>Certificate issuer</span>";
        $list.="<span class='span9'><input type='text' id='cert_issuer' style='width:640px;'></span>";
        $list.="</div>";

        $list.="<div class='container-fluid'>";
        $list.="<span class='span2'>Certificate title</span>";
        $list.="<span class='span9'><input type='text' id='cert_title' style='width:640px;'></span>";
        $list.="</div>";

        $list.="<div class='container-fluid'>";
        $list.="<span class='span12'><textarea name='editor1' id='editor1' rows='10' cols='80'>
                Please select program to edit/view certificate template
            </textarea>
            <script>
                // Replace the <textarea id='editor1'> with a CKEditor
                // instance, using default configuration.
                CKEDITOR.replace( 'editor1' );
            </script></span>";
        $list.="</div>";

        $list.="<div class='container-fluid'>";
        $list.="<span class='span12'><br/></span>";
        $list.="</div>";

        $list.="<div class='container-fluid'>";
        $list.="<span class='span2'>Signature person #1</span>";
        $list.="<span class='span3'><input type='text' id='person_name1'></span>";
        $list.="<span class='span3'><input type='text' id='person_title1'></span>";
        $list.="</div>";

        $list.="<div class='container-fluid'>";
        $list.="<span class='span2'>Signature person #2</span>";
        $list.="<span class='span3'><input type='text' id='person_name2'></span>";
        $list.="<span class='span3'><input type='text' id='person_title2'></span>";
        $list.="</div>";

        $list.="<div class='container-fluid'>";
        $list.="<span class='span2'>Signature person #3</span>";
        $list.="<span class='span3'><input type='text' id='person_name3'></span>";
        $list.="<span class='span3'><input type='text' id='person_title3'></span>";
        $list.="</div>";

        $list.="<div class='container-fluid'>";
        $list.="<span class='span12' id='template_err' style='color:red;'></span>";
        $list.="</div>";

        $list.="<div class='container-fluid'>";
        $list.="<span class='span2'></span>";
        $list.="<span class='span3'><button type='button' id='preview_btn' class='btn btn-primary'>Preview</button></span>";
        $list.="<span class='span3'><button type='button' id='save_btn' class='btn btn-primary'>Save</button></span>";
        $list.="</div>";

        $list.="<div class='container-fluid' style='text-align:left;'>";
        $list.="<br/><br/><span class='span12' id='preview_container'></span>";
        $list.="</div>";

        return $list;
    }

    function get_courses_list() {
        $list = "";
        $list.="<select id='courses_list' style='width:650px;'>";
        $list.="<option value='0' selected>Program</option>";
        $query = "select * from mdl_course where cost>0 and visible=1";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $list.="<option value='" . $row[id] . "'>" . $row['fullname'] . "</option>";
        }
        $list.="</select>";
        return $list;
    }

    function is_late_fee_exists($type, $length, $courseid) {
        $query = "select * from mdl_renew_late_fees "
                . "where courseid=$courseid and type=$type and length=$length";
        $num = $this->db->numrows($query);
        return $num;
    }

    function insert_course_renew_late_fee_data($courseid, $type, $length) {
        $query = "insert into mdl_renew_late_fees "
                . "(courseid,type,length) "
                . "values ($courseid,$type,$length)";
        $this->db->query($query);
    }

    function update_course_renew_late_fee() {
        $query = "select * from mdl_course where cost>0 and visible=1 and expired=1";
        $result = $this->db->query($query);
        $type = 4;
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $length = 30;
            $status = $this->is_late_fee_exists($type, $length, $row['id']);
            if ($status == 0) {
                $this->insert_course_renew_late_fee_data($row['id'], $type, $length);
            } // end if

            $length = 60;
            $status = $this->is_late_fee_exists($type, $length, $row['id']);
            if ($status == 0) {
                $this->insert_course_renew_late_fee_data($row['id'], $type, $length);
            } // end if

            $length = 90;
            $status = $this->is_late_fee_exists($type, $length, $row['id']);
            if ($status == 0) {
                $this->insert_course_renew_late_fee_data($row['id'], $type, $length);
            } // end if
        } // end while
    }

    function get_period_box($type, $id = null) {
        $list = "";
        if ($id > 0) {
            $list.="<select id='period_types_$id'>";
        } // end if 
        else {
            $list.="<select id='period_types'>";
        } // end else
        $query = "select * from mdl_period_type";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            if ($row['id'] == $type) {
                $list.="<option value='" . $row['id'] . "' selected>" . $row['type'] . "</option>";
            } // end if
            else {
                $list.="<option value='" . $row['id'] . "'>" . $row['type'] . "</option>";
            } // end else
        }

        $list.="</select>";

        return $list;
    }

    function get_length_box($index, $id = null) {
        $list = "";
        if ($id > 0) {
            $list.="<select id='period_length_$id'>";
        } // end if
        else {
            $list.="<select id='period_length'>";
        } // end else
        for ($i = 1; $i <= 120; $i++) {
            if ($i == $index) {
                $list.="<option value='$i' selected>$i</option>";
            } // end if
            else {
                $list.="<option value='$i'>$i</option>";
            } // end else
        }

        $list.="</select>";

        return $list;
    }

    function update_renew_late_amount($fee) {
        $query = "update mdl_renew_late_fees "
                . "set type=$fee->type, "
                . "length=$fee->length, "
                . "amount='$fee->amount' "
                . "where id=$fee->id";
        //echo "Query: " . $query . "<br>";
        $this->db->query($query);
    }

    function get_lates_tab() {
        $list = "";
        //$this->update_course_renew_late_fee();

        $list.="<div class='container-fluid' style='font-weight:bold;'>";
        $list.="<span class='span5'>Program</span>";
        $list.="<span class='span2' style='text-align:center;'>Period</span>";

        $list.="<span class='span2' style='padding-left:35px;text-align:center;'>Amount</span>";
        $list.="<span class='span1'></span>";
        $list.="</div>";

        $list.="<div class='container-fluid'>";
        $list.="<span class='span10' style='color:red;' id='ren_err2'></span>";
        $list.="</div>";

        $query = "select * from mdl_renew_late_fees";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $id = $row['id'];
            $period = $this->get_period_box($row['type'], $id);
            $length = $this->get_length_box($row['length'], $id);
            $coursename = $this->get_course_name($row['courseid']);
            $list.="<div class='container-fluid'>";
            $list.="<span class='span5'>$coursename</span>";
            $list.="<span class='span1'>$length</span>";
            $list.="<span class='span1'>$period</span>";
            $list.="<span class='span2' style='padding-left:35px;text-align:center;' id='fee_container_$id'><input type='text' id='late_amount_$id' value='" . $row['amount'] . "' style='width:25px;'></span>";
            $list.="<span class='span1'><button id='renew_late_amount_$id' class='btn btn-primary' data-id='$id'>Update</button></span>";
            $list.="</div>";
            $list.="<div class='container-fluid'>";
            $list.="<span class='span10'><hr/></span>";
            $list.="</div>";
        }
        return $list;
    }

    function is_course_renew_data_exists($courseid) {
        $query = "select * from mdl_renew_amount where courseid=$courseid";
        $num = $this->db->numrows($query);
        return $num;
    }

    function update_renew_courses_data() {
        $query = "select * from mdl_course where cost>0 and visible=1 and expired=1";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $status = $this->is_course_renew_data_exists($row['id']);
            if ($status == 0) {
                $query2 = "insert into mdl_renew_amount "
                        . "(courseid,amount) "
                        . "values(" . $row['id'] . ", '50')";
                $this->db->query($query2);
            }
        }
    }

    function get_renew_tab() {
        $list = "";

        $list.="<div class='container-fluid' style='font-weight:bold;'>";
        $list.="<span class='span8'>Program</span><span class='span1'>Fee ($)</span>";
        $list.="</div>";
        $list.="<div class='container-fluid'>";
        $list.="<span class='span10' style='color:red;' id='ren_err'></span>";
        $list.="</div>";

        $this->update_renew_courses_data();
        $query = "select * from mdl_renew_amount";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $id = $row['id'];
            $coursename = $this->get_course_name($row['courseid']);
            $list.="<div class='container-fluid'>";
            $list.="<span class='span8'>$coursename</span><span class='span1' id='amount_container_$id'><input style='width:45px;' type='text' id='amount_$id'  value='" . $row['amount'] . "'></span>";
            $list.="<span class='span1'><button id='renew_amount_$id' class='btn btn-primary' data-id='$id'>Update</button></span>";
            $list.="</div>";
            $list.="<div class='container-fluid'>";
            $list.="<span class='span11'><hr></span>";
            $list.="</div>";
        }
        return $list;
    }

    function update_renew_amount($item) {
        $query = "update mdl_renew_amount "
                . "set amount='$item->amount' where id=$item->id";
        $this->db->query($query);
        return $item->amount;
    }

    function create_certificates_list($certificates, $toolbar = true, $search = false, $tabs = true) {
        $list = "";
        $certificates_tab = $this->prepare_certificate_tab_content($certificates, $toolbar, $search);
        $templates_tab = $this->get_certificates_templates_tab();
        $renew_tab = $this->get_renew_tab();
        $lates_tab = $this->get_lates_tab();
        $list.="<ul class='nav nav-tabs'>
                    <li class='active'><a data-toggle='tab' href='#certs_tab'>Certificates</a></li>
                    <li><a data-toggle='tab' href='#templates_tab'>Templates</a></li>
                    <li><a data-toggle='tab' href='#fee_tab'>Renew fees</a></li>   
                    <li><a data-toggle='tab' href='#lates_tab'>Late fees</a></li>
                 </ul>";

        $list.="<div class='tab-content'>
        
        <div id='certs_tab' class='tab-pane fade in active'>
        <h3>Certificates</h3>
              <p>$certificates_tab</p>
        </div>

        <div id='templates_tab' class='tab-pane fade'>
        <h3>Templates</h3>
             <p>$templates_tab</p>
        </div>

        <div id='fee_tab' class='tab-pane fade'>
        <h3>Renew fees</h3>
             <p>$renew_tab</p>
        </div>

        <div id='lates_tab' class='tab-pane fade'>
        <h3>Late fees&nbsp; <button class='btn btn-primary' id='add_course_late_fee'>Add</button></h3>
             <p>$lates_tab</p>
        </div>
  
        </div>";


        if ($tabs) {
            return $list;
        } // end if
        else {
            return $certificates_tab;
        } // end else
    }

    function get_add_late_fee_dialog() {
        $list = "";
        $type = $this->get_period_box(4);
        $length = $this->get_length_box(30);
        $list.="<div id='myModal' class='modal fade'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h4 class='modal-title'>Add renew late fee</h4>
                </div>
                <div class='modal-body'>
                
                   
                <div class='container-fluid'>
                <span class='span1'>Program</span>
                <span class='span3'><input type='text' id='coursename' style='width:275px;'></span>
                <br><br>
                </div>
                
                <div class='container-fluid'>
                <span class='span1'>Period</span>
                <span class='span3'>$length &nbsp; $type</span>
                </div>
                
                <div class='container-fluid'>
                <span class='span1'>Amount ($)</span>
                <span class='span3'><input type='text' id='amount' style='width:45px;'></span>
                </div>
                
                <div class='container-fluid' style=''>
                <span class='span6' style='color:red;' id='add_renew_err'></span>
                </div>
             
                <div class='modal-footer' style='text-align:center;'>
                    <span align='center'><button type='button' class='btn btn-primary' data-dismiss='modal' id='cancel'>Cancel</button></span>
                    <span align='center'><button type='button' class='btn btn-primary' id='add_renew_late_fee_button'>OK</button></span>
                </div>
            </div>
        </div>
    </div>";

        return $list;
    }

    function get_courseid_by_name($name) {
        $query = "select * from mdl_course where fullname='$name'";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $id = $row['id'];
        }
        return $id;
    }

    function add_renew_late_fee($fee) {
        $courseid = $this->get_courseid_by_name($fee->coursename);
        $query = "insert into mdl_renew_late_fees "
                . "(courseid,type,length,amount) "
                . "values ($courseid,$fee->type,$fee->length,'$fee->amount')";
        $this->db->query($query);
    }

    function is_certificate_expired($courseid) {
        $query = "select * from mdl_course where id=$courseid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $expired = $row['expired'];
        }
        return $expired;
    }

    function get_send_certificate_page() {
        $list = "";
        $programs_category = $this->get_course_categories(true);
        $list.="<div id='send_cert_container' style='display:none;'>";
        $list.="<div class='container-fluid'>";
        $list.="<span class='span6' id='send_cert_err'></span>";
        $list.="</div>";
        $list.="<div class='container-fluid'>";
        $list.="<span class='span8'>$programs_category</span>";
        $list.="</div>";
        $list.="<div class='container-fluid'>";
        $list.="<span class='span8' id='send_category_courses'></span>";
        $list.="</div>";
        $list.="<div class='container-fluid'>";
        $list.="<span class='span8' id='send_enrolled_users'></span>";
        $list.="</div>";
        $list.="<div class='container-fluid'>";
        $list.="<span class='span2'><button type='button' id='send_cert' class='btn btn-primary'>Send</button></span>";
        //$list.="<span class='span2'><button type='button' id='print_label' class='btn btn-primary'>Address Label</button></span>";
        //$list.="<span class='span2'><button type='button' id='print_cert' class='btn btn-primary'>Print Certificate</button></span>";
        $list.="</div></div>";
        return $list;
    }

    function get_course_completion($courseid, $userid, $raw = false) {
        $date = 0;
        $query = "select * from mdl_course_completions where "
                . "course=$courseid and userid=$userid";
        //echo "Query: ".$query."<br>";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                if ($raw == FALSE) {
                    $date = date('Y-m-d', $row['reaggregate']);
                } // end if $raw==FALSE
                else {
                    $date = $row['reaggregate'];
                }
            } // end while
        } // end if $num > 0)
        return $date;
    }

    function get_user_details($id) {
        $query = "select * from mdl_user where id=$id";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $user = new stdClass();
            if ($row['firstname'] != '' && $row['lastname'] != '') {
                $user->firstname = $row['firstname'];
                $user->lastname = $row['lastname'];
                $user->email = $row['email'];
                $user->id = $row['id'];
            } // end if $row['firstname'] != '' && $row['lastname'] != ''
        } // end while
        return $user;
    }

    function get_course_renew_status($courseid) {
        $query = "select expired from mdl_course where id=$courseid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $expire = $row['expired'];
        }
        return $expire;
    }

    function get_course_code($courseid, $userid) {
        $query = "select fullname from mdl_course where id=$courseid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $name = $row['fullname'];
        }
        //$course_name=trim(str_replace('Workshop', '', $name));
        $course_name = $name;
        $date = time();
        $day = date('d', $date);
        $month = date('m', $date);
        $year = date('y', $date);

        $expr = '/(?<=\s|^)[a-z]/i';
        preg_match_all($expr, $course_name, $matches);
        $result = implode('', $matches[0]);
        $result = strtoupper($result);
        $code = "$month$day$year-$result$userid";
        return $code;
    }

    function get_certificate_title($courseid) {
        $query = "select category from mdl_course where id=$courseid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $category = $row['category'];
        } // end while
        if ($category == 2 || $category == 4) {
            $title = "Medical2 Certification Agency";
        } // end if $category == 2 || $category == 4        
        else {
            $title = "Medical2 Career College";
        }
        return $title;
    }

    function get_certificate_issue_date($courseid, $userid) {
        $exp_date = 0;
        $query = "select * from mdl_certificates "
                . "where courseid=$courseid and userid=$userid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $exp_date = $row['issue_date'];
            } // end while
        } // end if $num > 0
        return $exp_date;
    }

    function update_certificate_data2($courseid, $userid, $expiration) {
        //echo "Expiration date: ". date('m-d-Y', $expiration)."<br>";
        $query = "update mdl_certificates "
                . "set expiration_date='$expiration' "
                . "where courseid=$courseid and userid=$userid";
        //echo "Query: ".$query."<br>";
        $this->db->query($query);
        echo "Certificate has been renewed";
    }

    function get_certificate_template($courseid, $userid, $date = 0, $code = '', $renew = false) {
        $list = "";
        $coursename = $this->get_course_name($courseid); // string
        $userdetails = $this->get_user_details($userid); // object
        $firstname = strtoupper($userdetails->firstname);
        $lastname = strtoupper($userdetails->lastname);
        // This is expiration date
        if ($date == '') {
            $date = time();
        }
        $cert_issue_date = $this->get_certificate_issue_date($courseid, $userid);
        $issue_date = ($cert_issue_date == 0) ? time() : $cert_issue_date;
        $day = date('dS', $issue_date);
        $month = date('F', $issue_date);
        $year = date('Y', $issue_date);
        $renew_status = $this->get_course_renew_status($courseid);
        $title = $this->get_certificate_title($courseid);
        if ($code == '') {
            $code = $this->get_course_code($courseid, $userid);
        } // end if $code==''
        if ($renew_status == 1) {
            if ($renew == true) {
                //  renew existing certificate
                $expiration_date_sec = $issue_date + 31536000;
                $this->update_certificate_data2($courseid, $userid, $expiration_date_sec);
            } // end if $renew==true
            else {
                // create new one certificate 
                $expiration_date_sec = $issue_date + 31536000;
            }
        } // end if $renew_status == 1
        $expiration_date = strtoupper(date('m-d-Y', $expiration_date_sec));
        //echo "Expiration date: ".$expiration_date."<br>";
        switch ($courseid) {

            case 41:

                $list.="<!DOCTYPE HTML SYSTEM>";
                $list.="<head>";
                $list.="<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                $list.="</head>";
                $list.="<body>";
                $list.="<div>";
                $list.="<p style='text-align:center'><span style='text-align:center'><img src='/assets/logo/5.png' width='55%' height='15%'></span><br>";
                $list.="<span style='align:center;font-weight:bold;font-size:8pt;'>Licensed by the Mississippi Commission on Proprietary School and College Registration, License No. C675</span>";
                $list.="<span style='align:center;font-size:16pt;'><br><br>Presents in recognition on this the $day of $month, $year</span><br>";
                $list.="<br><span style='align:center;font-size:16pt;'>this <span style='align:center;font-weight:bold;font-size:30pt;'>Certificate</span> to</span><br>";
                $list.="<span style='align:center;font-size:35pt;'>$firstname $lastname</span>";
                $list.="<span style='align:center;font-size:16pt;'><br>for successfully completion of the requirements in the program of</span><br>";
                $list.="<span style='align:center;font-size:40pt;font-weight:bold;'>Certified Nurse  Assistant</span><br>";
                $list.="<span style='align:center;text-decoration:underline;font-size:15pt;font-weight:normal;'><br>CERTIFICATE # $code</span><br>";
                if ($renew_status == true) {
                    $list.="EXPIRATION DATE $expiration_date</p>";
                } // end if $renew_status == true                                 
                // President signature
                $list.="<br><br><p align='center'><table border='0' width='675px;'><tr><td align='right' style='font-family:king;text-decoration:underline;border-bottom:thick;font-size:10pt;'> Mrs. Donna Steele<br/><span style='float:left;font-size:12pt;font-family: Geneva, Arial, Helvetica, sans-serif;text-decoration:none; '>Director</span></td><td align='right' style='font-family:king;text-decoration:underline;border-bottom:thick;font-size:10pt;'>Mrs. Vicky Gunter<br/><span style='float:left;font-size:12pt;font-family: Geneva, Arial, Helvetica, sans-serif;text-decoration:none; '>Program Instructional Director</span></td></tr></table></p";
                $list.="</div>";

                $list.="</body>";
                $list.="</html>";

                break;


            case 44:
                $list.="<!DOCTYPE HTML SYSTEM>";
                $list.="<head>";
                $list.="<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                $list.="<link rel='stylesheet' type='text/css' href='cert.css'>";
                $list.="</head>";
                $list.="<body>";
                $list.="<div class='cert'>";
                $list.="<p><span style='align:center;font-family:king;font-weight:bolder;font-size:25pt;'>$title</span><br>";
                $list.="<span style='align:center;font-weight:bold;font-size:15pt;'>Presents this Phlebotomy Workshop Certification the $day of $month $year To:</span>";
                $list.="<br><br><span style='align:center;font-weight:bold;font-size:20pt;'>$firstname $lastname</span><br>";
                $list.="<br><span style='align:center;font-weight:bold;font-size:15pt;'>For the successful completion of hands-on clinical exam and written certification exam.
                        Medical2 Career College hands-on workshop covered topics; basic anatomy of arm, safety techniques with routine venipuncture, 
                        patient bill of rights, order of draws, needle/syringe, winged infusion (butterfly), 
                        lancet, evacuated tube system, and corrective techniques.
                        </span>";
                $list.="<br><br><br><br><p style='align:center;text-decoration:underline;font-size:15pt;font-weight:normal;'>CERTIFICATION # $code<br>";
                if ($renew_status == true) {
                    $list.="EXPIRATION DATE $expiration_date</p>";
                } // end if $renew_status == true                                 
                // President signature
                $list.="<br><br><div align='left'><table border='0' width='675px;'><tr><td align='center' style='font-family:king;text-decoration:underline;border-bottom:thick;font-size:10pt;'>Shahid Malik<br/><span style='float:left;font-size:12pt;font-family: Geneva, Arial, Helvetica, sans-serif;text-decoration:none; '>President</td></tr></table></div>";
                $list.="</div>";

                $list.="</body>";
                $list.="</html>";

                break;

            case 45:
                $list.="<!DOCTYPE HTML SYSTEM>";
                $list.="<head>";
                $list.="<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                $list.="<link rel='stylesheet' type='text/css' href='cert.css'>";
                $list.="</head>";
                $list.="<body>";
                $list.="<div class='cert'>";
                $list.="<p><span style='align:center;font-family:king;font-weight:bolder;font-size:25pt;'>$title</span><br>";
                $list.="<span style='align:center;font-weight:bold;font-size:15pt;'>Presents this Phlebotomy with EKG certification the $day of $month $year To:</span>";
                $list.="<br><br><span style='align:center;font-weight:bold;font-size:20pt;'>$firstname $lastname</span><br>";
                $list.="<br><span style='align:center;font-weight:bold;font-size:15pt;'>For the successful completion of hands-on clinical exam and written certification exam.<br>"
                        . "Medical2 Career College hands-on workshop covered topics; basic anatomy of arm, safety techniques with routine venipuncture,<br> "
                        . "Patient bill of rights, order of draws, needle/syringe, winged infusion (butterfly), lancet, evacuated tube system, and corrective techniques.<br><br>"
                        . "The workshop also discusses procedures to perform an Electrocardiogram.</span>";
                $list.="<br><br><br><br><p style='align:center;text-decoration:underline;font-size:15pt;font-weight:normal;'>CERTIFICATION # $code<br>";
                if ($renew_status == true) {
                    $list.="EXPIRATION DATE $expiration_date</p>";
                } // end if $renew_status == true                                 
                // President signature
                $list.="<br><br><div align='left'><table border='0' width='675px;'><tr><td align='center' style='font-family:king;text-decoration:underline;border-bottom:thick;font-size:10pt;'>Shahid Malik<br/><span style='float:left;font-size:12pt;font-family: Geneva, Arial, Helvetica, sans-serif;text-decoration:none; '>President</td></tr></table></div>";
                $list.="</div>";

                $list.="</body>";
                $list.="</html>";

                break;

            case 47:
                $list.="<!DOCTYPE HTML SYSTEM>";
                $list.="<head>";
                $list.="<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                $list.="<link rel='stylesheet' type='text/css' href='cert.css'>";
                $list.="</head>";
                $list.="<body>";
                $list.="<div class='cert'>";
                $list.="<p style='align:center;font-family:king;font-weight:bolder;font-size:25pt;'>$title ";
                $list.="<br><span style='align:center;font-weight:bold;font-size:35pt;font-family: Geneva, Arial, Helvetica, sans-serif;'>$coursename<br>";
                $list.="<span style='align:center;font-weight:bold;font-size:15pt;'>Presents this certificate this the $day of $month $year To:</span>";
                $list.="<br><span style='align:center;font-weight:bold;font-size:35pt;'>$firstname $lastname</span>";
                $list.="<br><span style='align:center;font-weight:bold;font-size:15pt;'>For successfully meeting all requirements to hold this certification.</span>";
                $list.="<br><br><br><br><p style='align:center;text-decoration:underline;font-size:15pt;font-weight:normal;'>CERTIFICATION # $code<br>";
                if ($renew_status == true) {
                    $list.="EXPIRATION DATE $expiration_date</p>";
                }
                $list.="<div align='left'><table border='0' width='675px;'><tr><td align='center' style='font-family:king;text-decoration:underline;border-bottom:thick;font-size:10pt;'>Shahid Malik<br/><span style='float:left;font-size:12pt;font-family: Geneva, Arial, Helvetica, sans-serif;text-decoration:none; '>President</span></td></tr></table></div>";
                $list.="</div>";
                $list.="</body>";
                $list.="</html>";

                break;

            case 48:
                $coursename = "IV Therapy Exam";
                $list.="<!DOCTYPE HTML SYSTEM>";
                $list.="<head>";
                $list.="<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                $list.="<link rel='stylesheet' type='text/css' href='cert.css'>";
                $list.="</head>";
                $list.="<body>";
                $list.="<div class='cert'>";
                $list.="<p style='align:center;font-family:king;font-weight:bolder;font-size:25pt;'>$title ";
                $list.="<br><span style='align:center;font-weight:bold;font-size:35pt;font-family: Geneva, Arial, Helvetica, sans-serif;'>$coursename<br>";
                $list.="<span style='align:center;font-weight:bold;font-size:15pt;'>Presents this certificate this the $day of $month $year To:</span>";
                $list.="<br><span style='align:center;font-weight:bold;font-size:35pt;'>$firstname $lastname</span>";
                $list.="<br><span style='align:center;font-weight:bold;font-size:15pt;'>For successfully meeting all requirements to hold this certification.</span>";
                $list.="<br><br><br><br><p style='align:center;text-decoration:underline;font-size:15pt;font-weight:normal;'>CERTIFICATION # $code<br>";
                if ($renew_status == true) {
                    $list.="EXPIRATION DATE $expiration_date</p>";
                }
                $list.="<div align='left'><table border='0' width='675px;'><tr><td align='center' style='font-family:king;text-decoration:underline;border-bottom:thick;font-size:10pt;'>Shahid Malik<br/><span style='float:left;font-size:12pt;font-family: Geneva, Arial, Helvetica, sans-serif;text-decoration:none; '>President</span></td></tr></table></div>";
                $list.="</div>";
                $list.="</body>";
                $list.="</html>";

                break;

            case 49:
                $list.="<!DOCTYPE HTML SYSTEM>";
                $list.="<head>";
                $list.="<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                $list.="<link rel='stylesheet' type='text/css' href='cert.css'>";
                $list.="</head>";
                $list.="<body>";
                $list.="<div class='cert'>";
                $list.="<p><span style='align:center;font-family:king;font-weight:bolder;font-size:25pt;'>$title</span><br>";
                $list.="<span style='align:center;font-weight:bold;font-size:25pt;'>Obstetrics Technician Certificate</span>";
                $list.="<br><br><span style='align:center;font-weight:bold;font-size:15pt;'>Presented this the $day of $month $year To:</span>";
                $list.="<br><br><span style='align:center;font-weight:bold;font-size:20pt;'>$firstname $lastname</span><br>";
                $list.="<br><span style='align:center;font-weight:bold;font-size:15pt;'>for the successful completion of the Obstetrics Technician Written Exam. </span>";
                $list.="<br><br><br><br><p style='align:center;text-decoration:underline;font-size:15pt;font-weight:normal;'>CERTIFICATION # $code<br>";
                if ($renew_status == true) {
                    $list.="EXPIRATION DATE $expiration_date</p>";
                } // end if $renew_status == true                                 
                // President signature
                $list.="<br><br><br><br><br><div align='left'><table border='0' width='675px;'><tr><td align='center' style='font-family:king;text-decoration:underline;border-bottom:thick;font-size:10pt;'>Shahid Malik<br/><span style='float:left;font-size:12pt;font-family: Geneva, Arial, Helvetica, sans-serif;text-decoration:none; '>President</td></tr></table></div>";
                $list.="</div>";

                $list.="</body>";
                $list.="</html>";

                break;

            case 51:
                $coursename = "Phlebotomy Technician Exam";
                $list.="<!DOCTYPE HTML SYSTEM>";
                $list.="<head>";
                $list.="<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                $list.="<link rel='stylesheet' type='text/css' href='cert.css'>";
                $list.="</head>";
                $list.="<body>";
                $list.="<div class='cert'>";
                $list.="<p style='align:center;font-family:king;font-weight:bolder;font-size:25pt;'>$title ";
                $list.="<br><span style='align:center;font-weight:bold;font-size:35pt;font-family: Geneva, Arial, Helvetica, sans-serif;'>$coursename<br>";
                $list.="<span style='align:center;font-weight:bold;font-size:15pt;'>Presents this certificate this the $day of $month $year To:</span>";
                $list.="<br><span style='align:center;font-weight:bold;font-size:35pt;'>$firstname $lastname</span>";
                $list.="<br><span style='align:center;font-weight:bold;font-size:15pt;'>For successfully completing the Phlebotomy Technician Certification Exam.</span>";
                $list.="<br><br><br><br><p style='align:center;text-decoration:underline;font-size:15pt;font-weight:normal;'>CERTIFICATION # $code<br>";
                if ($renew_status == true) {
                    $list.="EXPIRATION DATE $expiration_date</p>";
                }
                $list.="<div align='left'><table border='0' width='675px;'><tr><td align='center' style='font-family:king;text-decoration:underline;border-bottom:thick;font-size:10pt;'>Shahid Malik<br/><span style='float:left;font-size:12pt;font-family: Geneva, Arial, Helvetica, sans-serif;text-decoration:none; '>President</span></td></tr></table></div>";
                $list.="</div>";
                $list.="</body>";
                $list.="</html>";

                break;

            case 55:

                $list.="<!DOCTYPE HTML SYSTEM>";
                $list.="<head>";
                $list.="<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                $list.="</head>";
                $list.="<body>";
                $list.="<div>";
                $list.="<p style='text-align:center'><span style='text-align:center'><img src='/assets/logo/5.png' width='55%' height='15%'></span><br>";
                $list.="<span style='align:center;font-weight:bold;font-size:8pt;'>Licensed by the Mississippi Commission on Proprietary School and College Registration, License No. C675</span>";
                $list.="<span style='align:center;font-size:16pt;'><br><br>Presents in recognition on this the $day of $month, $year</span><br>";
                $list.="<br><span style='align:center;font-size:16pt;'>this <span style='align:center;font-weight:bold;font-size:30pt;'>Diploma</span> to</span><br>";
                $list.="<span style='align:center;font-size:35pt;'>$firstname $lastname</span>";
                $list.="<span style='align:center;font-size:16pt;'><br>for successfully completion of the requirements in the program of</span><br>";
                $list.="<span style='align:center;font-size:40pt;font-weight:bold;'>Medical Assistant</span><br>";
                $list.="<span style='align:center;text-decoration:underline;font-size:15pt;font-weight:normal;'><br>DIPLOMA # $code</span><br>";
                if ($renew_status == true) {
                    $list.="EXPIRATION DATE $expiration_date</p>";
                } // end if $renew_status == true                                 
                // President signature
                $list.="<br><br><p align='center'><table border='0' width='675px;'><tr><td align='right' style='font-family:king;text-decoration:underline;border-bottom:thick;font-size:10pt;'> Mrs. Donna Steele<br/><span style='float:left;font-size:12pt;font-family: Geneva, Arial, Helvetica, sans-serif;text-decoration:none; '>Director</span></td><td align='right' style='font-family:king;text-decoration:underline;border-bottom:thick;font-size:10pt;'>Mrs. Vicky Gunter<br/><span style='float:left;font-size:12pt;font-family: Geneva, Arial, Helvetica, sans-serif;text-decoration:none; '>Program Instructional Director</span></td></tr></table></p";
                $list.="</div>";

                $list.="</body>";
                $list.="</html>";

                break;

            case 56:

                $list.="<!DOCTYPE HTML SYSTEM>";
                $list.="<head>";
                $list.="<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                $list.="</head>";
                $list.="<body>";
                $list.="<div>";
                $list.="<p style='text-align:center'><span style='text-align:center'><img src='/assets/logo/5.png' width='55%' height='15%'></span><br>";
                $list.="<span style='align:center;font-weight:bold;font-size:8pt;'>Licensed by the Mississippi Commission on Proprietary School and College Registration, License No. C675</span>";
                $list.="<span style='align:center;font-size:16pt;'><br><br>Presents in recognition on this the $day of $month, $year</span><br>";
                $list.="<br><span style='align:center;font-size:16pt;'>this <span style='align:center;font-weight:bold;font-size:30pt;'>Certificate</span> to</span><br>";
                $list.="<span style='align:center;font-size:35pt;'>$firstname $lastname</span>";
                $list.="<span style='align:center;font-size:16pt;'><br>for successfully completion of the requirements in the program of</span><br>";
                $list.="<span style='align:center;font-size:40pt;font-weight:bold;'>Medical Administrative Assistant</span><br>";
                $list.="<span style='align:center;text-decoration:underline;font-size:15pt;font-weight:normal;'><br>CERTIFICATE # $code</span><br>";
                if ($renew_status == true) {
                    $list.="EXPIRATION DATE $expiration_date</p>";
                } // end if $renew_status == true                                 
                // President signature
                $list.="<br><br><p align='center'><table border='0' width='675px;'><tr><td align='right' style='font-family:king;text-decoration:underline;border-bottom:thick;font-size:10pt;'> Mrs. Donna Steele<br/><span style='float:left;font-size:12pt;font-family: Geneva, Arial, Helvetica, sans-serif;text-decoration:none; '>Director</span></td><td align='right' style='font-family:king;text-decoration:underline;border-bottom:thick;font-size:10pt;'>Mrs. Vicky Gunter<br/><span style='float:left;font-size:12pt;font-family: Geneva, Arial, Helvetica, sans-serif;text-decoration:none; '>Program Instructional Director</span></td></tr></table></p";
                $list.="</div>";

                $list.="</body>";
                $list.="</html>";

                break;

            case 57:
                $list.="<!DOCTYPE HTML SYSTEM>";
                $list.="<head>";
                $list.="<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                $list.="<link rel='stylesheet' type='text/css' href='cert.css'>";
                $list.="</head>";
                $list.="<body>";
                $list.="<div class='cert'>";
                $list.="<p style='align:center;font-family:king;font-weight:bolder;font-size:25pt;'>$title ";
                $list.="<br><span style='align:center;font-weight:bold;font-size:25pt;font-family: Geneva, Arial, Helvetica, sans-serif;'>Phlebotomy Technician Certificate<br><br><br>";
                $list.="<span style='align:center;font-weight:bold;font-size:15pt;'>Presented this the $day of $month $year To:</span>";
                $list.="<br><br><br><span style='align:center;font-weight:bold;font-size:35pt;'>$firstname $lastname</span>";
                $list.="<br><span style='align:center;font-weight:bold;font-size:15pt;'>For successfully completing the Phlebotomy Technician Certification Exam. </span>";
                $list.="<br><br><br><p style='align:center;text-decoration:underline;font-size:15pt;font-weight:normal;'>CERTIFICATION # $code<br>";
                if ($renew_status == true) {
                    $list.="EXPIRATION DATE $expiration_date</p>";
                }
                $list.="<br><br><div align='left'><table border='0' width='675px;'><tr><td align='center' style='font-family:king;text-decoration:underline;border-bottom:thick;font-size:10pt;'>Shahid Malik<br/><span style='float:left;font-size:12pt;font-family: Geneva, Arial, Helvetica, sans-serif;text-decoration:none; '>President</td></tr></table></div>";
                $list.="</div>";
                $list.="</body>";
                $list.="</html>";

                break;

            default:
                $list.="<!DOCTYPE HTML SYSTEM>";
                $list.="<head>";
                $list.="<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                $list.="<link rel='stylesheet' type='text/css' href='cert.css'>";
                $list.="</head>";
                $list.="<body>";
                $list.="<div class='cert'>";
                $list.="<p style='align:center;font-family:king;font-weight:bolder;font-size:25pt;'>$title ";
                $list.="<br><span style='align:center;font-weight:bold;font-size:35pt;font-family: Geneva, Arial, Helvetica, sans-serif;'>$coursename<br>";
                $list.="<span style='align:center;font-weight:bold;font-size:15pt;'>Presents this certificate this the $day of $month $year To:</span>";
                $list.="<br><span style='align:center;font-weight:bold;font-size:35pt;'>$firstname $lastname</span>";
                $list.="<br><span style='align:center;font-weight:bold;font-size:15pt;'>For successfully meeting all requirements to hold this certification.</span>";
                $list.="<br><br><br><br><p style='align:center;text-decoration:underline;font-size:15pt;font-weight:normal;'>CERTIFICATION # $code<br>";
                if ($renew_status == true) {
                    $list.="EXPIRATION DATE $expiration_date</p>";
                }
                $list.="<div align='left'><table border='0' width='675px;'><tr><td align='center' style='font-family:king;text-decoration:underline;border-bottom:thick;font-size:10pt;'>Shahid Malik<br/><span style='float:left;font-size:12pt;font-family: Geneva, Arial, Helvetica, sans-serif;text-decoration:none; '>President</td><td align='left' style='font-family:king;text-decoration:underline;border-bottom:thick;font-size:10pt;margin-left:40px;'>Donna Steele<br/><span style='float:right;font-size:12pt;font-family: Geneva, Arial, Helvetica, sans-serif;text-decoration:none;'>Director</span></td></tr></table></div>";
                $list.="</div>";
                $list.="</body>";
                $list.="</html>";

                break;
        } // end switch

        $pdf = new mPDF('utf-8', 'A4-L');
        $stylesheet = file_get_contents($this->cert_path . '/cert.css');

        if ($courseid != 41 && $courseid != 55 && $courseid != 56) {
            $pdf->WriteHTML($stylesheet, 1);
        }

        $pdf->WriteHTML($list, 2);
        $dir_path = $this->cert_path . "/$userid/$courseid";
        //echo "Dir path: " . $dir_path . "<br>";
        if (!is_dir($dir_path)) {
            //echo "Dir does not exist ...<br>";
            if (!mkdir($dir_path, 0777, true)) {
                print_r(error_get_last());
                //die('Could not write to disk');
            } // end if !mkdir($dir_path)
        } // end if !is_dir($dir_path)
        $path = $dir_path . "/certificate.pdf";
        //echo "File path: " . $path . "<br>";
        $pdf->Output($path, 'F');

        return $list;
    }

    function prepare_ceriticate($courseid, $userid, $date = 0, $code = '', $renew = false) {
        $list = "";
        $list.=$this->get_certificate_template($courseid, $userid, $date, $code, $renew);
        $dir_path = $this->cert_path . "/$userid/$courseid";
        $path = $dir_path . "/certificate.pdf";
        return $path;
    }

    function get_user_course_entollment_date($courseid, $userid) {
        $contextid = $this->get_course_context($courseid);
        $query = "select * from mdl_role_assignments "
                . "where roleid=$this->student_role "
                . "and contextid=$contextid "
                . "and userid=$userid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $date = $row['timemodified'];
        } // end while
        return $date;
    }

    function get_course_category($id, $code = false) {
        $query = "select category from mdl_course where id=$id";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $categoryid = $row['category'];
        } // end while
        if ($code == false) {
            if ($categoryid == 2 || $categoryid == 4) {
                return true;
            } // end if $categoryid==2 || $categoryid==4
            else {
                return false;
            } // end else
        } // end if
        else {
            return $categoryid;
        } // end else
    }

    function is_certificate_exists($courseid, $userid) {
        $query = "select * from mdl_certificates "
                . "where courseid=$courseid and userid=$userid";
        $num = $this->db->numrows($query);
        return $num;
    }

    function send_certificate($courseid, $userid, $date = 0, $send = true, $code = '', $renew = false) {
        //echo "Date: ".$date."<br>";
        if ($date == 0) { // course is not yet completed
            $date = $this->get_user_course_entollment_date($courseid, $userid);
        } // end if $date == 0
        $renew_status = $this->get_course_category($courseid);
        if ($renew_status) {
            $expiration_date_sec = $date + 31536000;
        } // end if $renew_status
        else {
            $expiration_date_sec = 'n/a';
        } // end else
        $path = $this->prepare_ceriticate($courseid, $userid, $date, $code, $renew);
        $this->create_label($courseid, $userid);
        $user = $this->get_user_details($userid);
        $user->path = $path;
        if ($code == '') {
            $code = $this->get_course_code($courseid, $userid);
        } // end if $code == ''
        $certificate_exists = $this->is_certificate_exists($courseid, $userid);
        if ($certificate_exists == 0) {
            $query = "insert into mdl_certificates (courseid,"
                    . "userid,"
                    . "path, cert_no, "
                    . "issue_date,"
                    . "expiration_date) "
                    . "values('$courseid',"
                    . " '$userid',"
                    . " '$path', '$code', "
                    . " '$date',"
                    . " '$expiration_date_sec')";
            //echo "Query: ".$query."<br>";
            $this->db->query($query);
        } // end if $certificate_exists==0
        if ($send == true) {
            $mailer = new Mailer();
            $mailer->send_certificate($user);
        }
        $list = "Certificate has been sent";
        return $list;
    }

    function get_certificate_item($page) {
        $certificates = array();
        $rec_limit = $this->limit;
        if ($page == 1) {
            $offset = 0;
        } // end if $page==1
        else {
            $page = $page - 1;
            $offset = $rec_limit * $page;
        }
        $query = "select * from mdl_certificates "
                . "where userid<>3002 "
                . "and userid<>3784 "
                . "order by issue_date desc LIMIT $offset, $rec_limit";
        //echo "Query: ".$query."<br>";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $certificate = new stdClass();
            foreach ($row as $key => $value) {
                $certificate->$key = $value;
            } // end foreach
            $certificates[] = $certificate;
        } // end while
        $list = $this->create_certificates_list($certificates, false, false, false);
        return $list;
    }

    function verify_certificate($fname, $lname) {
        $certs = array();
        $list = "";
        //echo "Certificate no: ".$cert_no."<br>";
        //$cert_arr = explode('-', $cert_no);

        $query = "SELECT * FROM `mdl_user` 
            WHERE firstname = '" . trim($fname) . "'
            AND lastname = '" . trim($lname) . "'";
        //echo "Query: ".$query."<br>";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $id = $row['id'];
            } // end while
            //echo "User id: ".$id."<br>";
            $query = "select * from mdl_certificates where userid=$id";
            //echo "Query: ".$query."<br>";
            $num = $this->db->numrows($query);
            if ($num > 0) {
                $result = $this->db->query($query);
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $cert = new stdClass();
                    $cert->no = $row['cert_no'];
                    $cert->expiration = date('m-d-Y', $row['expiration_date']);
                    $certs[] = $cert;
                } //end while
                foreach ($certs as $cert) {
                    $list.="<p>Certificate No: $cert->no &nbsp; Expiration date: $cert->expiration</p>";
                }
            } // end if $num > 0
            else {
                $list.="Certificate is not found";
            }
        } // end if $num > 0
        else {
            $list.="Certificate is not found";
        }


        return $list;
    }

    function get_user_address_data($userid) {
        $query = "SELECT firstname, lastname, email, phone1, address, city, state, zip
                FROM  `mdl_user` where id=$userid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $user = new stdClass();
            foreach ($row as $key => $value) {
                $user->$key = $value;
            }
            $user->group_name = '';
        } // end while
        return $user;
    }

    function create_label($courseid, $userid) {
        $user_address = $this->get_user_address_data($userid);
        $pdf = new PDF_Label('5162');
        $pdf->AddPage();
        $text = sprintf("%s\n%s\n%s\n%s\n%s\n%s\n%s", "From:", "Medical2 Inc", "1830A North Gloster St \nTupelo, MS 38804", "--------------------------------------------------------","                       $user_address->firstname $user_address->lastname","                       $user_address->address", "                       $user_address->city, " . $user_address->state . " $user_address->zip");
        $pdf->Add_Label($text);
        
        $dir_path = $this->cert_path . "/$userid";
        if (!is_dir($dir_path)) {
            if (!mkdir($dir_path)) {
                die('Could not write to disk');
            } // end if !mkdir($dir_path)
        } // end if !is_dir($dir_path)
        $path = $dir_path . "/label.pdf";
        $pdf->Output($path, 'F');
    }

    function get_certificate_classes($item) {
        $schedulers = array();
        $courses = array();
        $query = "select * from mdl_scheduler_slots where notes like '%$item%'";
        //echo "Query: ".$query."<br>";
        $num = $this->db->numrows($query);
        //echo "Slots num: ".$num."<br>";
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $schedulers[] = $row['schedulerid'];
            } // end while
            //echo "<br>----------Schedulers--------------------<br>";
            //print_r($schedulers);
            //echo "<br>";
            foreach ($schedulers as $scheduler) {
                $query = "select * from mdl_scheduler where id=$scheduler";
                //echo "Scheduler Query: ".$query."<br>";
                $result = $this->db->query($query);
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $courses[] = $row['course'];
                } // end while
            } // end foreach
        } // end if $num > 0
        return $courses;
    }

    function search_certificate($item) {
        $list = "";
        $certificates = array();
        $invoice = new Invoices();
        $users_array = array_unique($invoice->search_invoice_users($item));
        $courses_array = array_unique($invoice->search_invoice_courses($item));
        $classes_array = array_unique($this->get_certificate_classes($item));
        $programs_array = array_unique(array_merge($courses_array, $classes_array));
        $users_list = implode(",", $users_array);
        $courses_list = implode(",", $programs_array);

        /*
         * 
          echo "<br>--------------Users array--------------<br>";
          print_r($users_array);
          echo "<br>--------------Classes array--------------<br>";
          print_r($classes_array);
          echo "<br>--------------Courses array--------------<br>";
          print_r($courses_array);
          echo "<br>--------------Programs array--------------<br>";
          print_r($programs_array);
          echo "<br>";
         * 
         */

        if ($users_list != '') {
            $query = "select * from mdl_certificates "
                    . "where userid in ($users_list) order by issue_date desc ";
        } // end if $users_list != ''
        if ($courses_list != '') {
            $query = "select * from mdl_certificates "
                    . "where courseid in ($courses_list) order by issue_date desc ";
        } // end if $courses_list != ''
        if ($users_list != '' && $courses_list != '') {
            $query = "select * from mdl_certificates "
                    . "where  (courseid in ($courses_list) "
                    . "or userid in ($users_list)) order by issue_date desc ";
        } // end if $users_list != '' && $courses_list != ''
        //echo "Certificates query: " . $query . "<br>";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $certificate = new stdClass();
                foreach ($row as $key => $value) {
                    $certificate->$key = $value;
                }
                $certificates[] = $certificate;
            } // end while
            $list.=$this->create_certificates_list($certificates, false, true, false);
        } // end if num>0
        else {
            $list.="<div class='container-fluid' style='text-align:center;'>";
            $list.="<span class='span6'>No Certificates found</span>";
            $list.="</div>";
        }
        return $list;
    }

    function get_user_id($id) {
        $query = "select * from mdl_certificates where id=$id";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $userid = $row['userid'];
        }
        return $userid;
    }

    function print_certificates($certificates) {
        $certs = array();
        $certificates_arr = explode(",", $certificates);
        if (count($certificates_arr) > 0) {
            foreach ($certificates_arr as $id) {
                $studentid = $this->get_user_id($id);
                $pdf_file = $_SERVER['DOCUMENT_ROOT'] . "/lms/custom/certificates/$studentid/certificate.pdf";
                $certs[] = $pdf_file;
            } // end foreach
            $datadir = $_SERVER['DOCUMENT_ROOT'] . "/print/";
            $outputName = $datadir . "merged.pdf";
            $cmd = "gs -q -dNOPAUSE -dBATCH -sDEVICE=pdfwrite -sOutputFile=$outputName ";
            foreach ($certs as $certificate) {
                $cmd .= $certificate . " ";
            } // end foreach
            shell_exec($cmd);
        } // end f count($students_arr) > 0
    }

    function print_labels($certificates) {
        $lb = array();
        $certificates_arr = explode(",", $certificates);
        if (count($certificates_arr) > 0) {
            foreach ($certificates_arr as $id) {
                $studentid = $this->get_user_id($id);
                $pdf_file = $_SERVER['DOCUMENT_ROOT'] . "/lms/custom/certificates/$studentid/label.pdf";
                $lb[] = $pdf_file;
            } // end foreach
            $datadir = $_SERVER['DOCUMENT_ROOT'] . "/print/";
            $outputName = $datadir . "merged.pdf";
            $cmd = "gs -q -dNOPAUSE -dBATCH -sDEVICE=pdfwrite -sOutputFile=$outputName ";
            foreach ($lb as $label) {
                $cmd .= $label . " ";
            } // end foreach
            shell_exec($cmd);
        } // end f count($students_arr) > 0
    }

    function get_certificate_detailes($id) {
        $query = "select * from mdl_certificates where id=$id";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $cert = new stdClass();
            $cert->id = $row['id'];
            $cert->courseid = $row['courseid'];
            $cert->userid = $row['userid'];
            $cert->code = $row['cert_no'];
            $cert->date = $row['expiration_date'];
        }
        return $cert;
    }

    function update_certificate_data($id, $exp_date) {
        $query = "update mdl_certificates "
                . "set expiration_date='$exp_date' where id=$id";
        //echo "Query:".$query."<br>";
        $this->db->query($query);
    }

    function renew_certificates($certs) {
        $list = "";
        $certs_array = explode(",", $certs);
        if (count($certs_array) > 0) {
            foreach ($certs_array as $id) {
                $certificate = $this->get_certificate_detailes($id);
                $this->get_certificate_template($certificate->courseid, $certificate->userid, $certificate->date, $certificate->code, true);
                //$this->update_certificate_data($id, $certificate->date);
            } // end foreach
        } // end if count($certs_array)>0
        // $list.="Selected Certificate(s) were renewed";
        return $list;
    }

    function get_start_unputs($modal = TRUE) {
        $list = "";
        // **************** Month drop-box *********************
        $m_list = "";
        if ($modal == true) {
            $m_list.="<select id='s_m_c'>";
        } else {
            $m_list.="<select id='s_m'>";
        }
        $m_list.="<option value='0' selected>Month</option>";
        for ($i = 1; $i <= 12; $i++) {
            $m_list.="<option value='$i'>$i</option>";
        }
        $m_list.="</select>";
        // **************** Day drop-box *********************
        $d_list = "";
        if ($modal == true) {
            $d_list.="<select id='s_d_c'>";
        } else {
            $d_list.="<select id='s_d'>";
        }
        $d_list.="<option value='0' selected>Day</option>";
        for ($i = 1; $i <= 31; $i++) {
            $d_list.="<option value='$i'>$i</option>";
        }
        $d_list.="</select>";
        // **************** Year drop-box *********************
        $y_list = "";
        if ($modal == true) {
            $y_list .= "<select id='s_y_c'>";
        } else {
            $y_list .= "<select id='s_y'>";
        }
        $y_list.="<option value='0' selected>Year</option>";
        for ($i = 2000; $i <= 2075; $i++) {
            $y_list.="<option value='$i'>$i</option>";
        }
        $y_list.="</select>";
        if ($modal == true) {
            $list.="<div class='container-fluid' style='text-align:center;'>";
            $list.="<span class='span1'>Issue</span>";
            $list.="<span class='span1'>$m_list</span>";
            $list.="<span class='span1'>$d_list</span>";
            $list.="<span class='span1'>$y_list</span>";
            $list.="</div>";
        } // end if $modal == true
        else {
            $list.="<div class='container-fluid' style='text-align:center;'>";
            $list.="<span class='span3'>Issue</span>";
            $list.="<span class='span3'>$m_list</span>";
            $list.="<span class='span3'>$d_list</span>";
            $list.="<span class='span3'>$y_list</span>";
            $list.="</div>";
        }
        return $list;
    }

    function get_end_inputs($modal = TRUE) {
        $list = "";
        // **************** Month drop-box *********************
        $m_list = "";
        if ($modal == true) {
            $m_list.="<select id='e_m_c'>";
        } else {
            $m_list.="<select id='e_m'>";
        }
        $m_list.="<option value='0' selected>Month</option>";
        for ($i = 1; $i <= 12; $i++) {
            $m_list.="<option value='$i'>$i</option>";
        }
        $m_list.="</select>";
        // **************** Day drop-box *********************
        $d_list = "";
        if ($modal == true) {
            $d_list.="<select id='e_d_c'>";
        } else {
            $d_list.="<select id='e_d'>";
        }
        $d_list.="<option value='0' selected>Day</option>";
        for ($i = 1; $i <= 31; $i++) {
            $d_list.="<option value='$i'>$i</option>";
        }
        $d_list.="</select>";
        // **************** Year drop-box *********************
        $y_list = "";
        if ($modal == true) {
            $y_list .= "<select id='e_y_c'>";
        } else {
            $y_list .= "<select id='e_y'>";
        }
        $y_list.="<option value='0' selected>Year</option>";
        for ($i = 2000; $i <= 2075; $i++) {
            $y_list.="<option value='$i'>$i</option>";
        }
        $y_list.="</select>";
        if ($modal == true) {
            $list.="<div class='container-fluid' style='text-align:center;'>";
            $list.="<span class='span1'>Expire</span>";
            $list.="<span class='span1'>$m_list</span>";
            $list.="<span class='span1'>$d_list</span>";
            $list.="<span class='span1'>$y_list</span>";
            $list.="</div>";
        } // end if $modal == true
        else {
            $list.="<div class='container-fluid' style='text-align:center;'>";
            $list.="<span class='span3'>Expire</span>";
            $list.="<span class='span3'>$m_list</span>";
            $list.="<span class='span3'>$d_list</span>";
            $list.="<span class='span3'>$y_list</span>";
            $list.="</div>";
        }
        return $list;
    }

    function get_dates_box($certs) {
        $list = "";
        $issue = $this->get_start_unputs();
        $expire = $this->get_end_inputs();
        $list.="<div id='myModal' class='modal fade'>
        <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-header'>                
                <h4 class='modal-title'>Certificate dates</h4>
                </div>                
                <div class='modal-body'>                                
                <div class='container-fluid' style='text-align:left;'>
                <span class='span5'>$issue</span>    
                </div>            
                <div class='container-fluid' style='text-align:left;'>
                <span class='span5'>$expire</span>    
                <input type='hidden' id='certs' value='$certs'>    
                </div>                
                </div>
                
                <div class='modal-footer'>
                <span align='center'><button type='button' class='btn btn-primary' data-dismiss='modal' id='cancel'>Cancel</button></span>
                <span align='center'><button type='button' class='btn btn-primary' data-dismiss='modal' id='recreate'>Go</button></span>
                </div>
        </div>
        </div>
        </div>";
        return $list;
    }

    function update_certificate_dates($id, $issue, $expire) {
        $query = "update mdl_certificates "
                . "set issue_date='$issue' ,"
                . "expiration_date='$expire' "
                . "where id=$id";
        //echo "Query: " . $query . "<br>";
        $this->db->query($query);
    }

    function recertificate($certs, $start, $end) {
        //echo "Start date: " . $start . "<br>";
        //echo "End date: " . $end . "<br>";
        $list = "";
        $certs_arr = explode(",", $certs);
        if (count($certs_arr) > 0) {
            $issue = strtotime($start);
            $expire = strtotime($end);
            //$timezone = 'America/New_York';
            foreach ($certs_arr as $certid) {
                $detailes = $this->get_certificate_detailes($certid);
                $this->create_new_certificate($detailes, $issue, $expire);
                $this->update_certificate_dates($certid, $issue, $expire);
            } // end foeach             
        } // end if count($certs_arr
        $list.="Selected Certificate(s) are updated, please reload the page.";
        return $list;
    }

    function create_new_certificate($cert, $issue, $expire) {

        $coursename = $this->get_course_name($cert->courseid); // string
        $userdetails = $this->get_user_details($cert->userid); // object
        $courseid = $cert->courseid;
        $userid = $cert->userid;
        $this->create_label($courseid, $userid);
        $firstname = strtoupper($userdetails->firstname);
        $lastname = strtoupper($userdetails->lastname);
        $day = date('dS', $issue);
        $month = date('F', $issue);
        $year = date('Y', $issue);
        $renew_status = $this->get_course_renew_status($cert->courseid);
        $title = $this->get_certificate_title($cert->courseid);
        $expiration_date = strtoupper(date('m-d-Y', $expire));
        $code = $this->get_course_code($cert->courseid, $cert->userid);


        switch ($cert->courseid) {

            case 41:

                $list.="<!DOCTYPE HTML SYSTEM>";
                $list.="<head>";
                $list.="<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                $list.="</head>";
                $list.="<body>";
                $list.="<div>";
                $list.="<p style='text-align:center'><span style='text-align:center'><img src='/assets/logo/5.png' width='55%' height='15%'></span><br>";
                $list.="<span style='align:center;font-weight:bold;font-size:8pt;'>Licensed by the Mississippi Commission on Proprietary School and College Registration, License No. C675</span>";
                $list.="<span style='align:center;font-size:16pt;'><br><br>Presents in recognition on this the $day of $month, $year</span><br>";
                $list.="<br><span style='align:center;font-size:16pt;'>this <span style='align:center;font-weight:bold;font-size:30pt;'>Certificate</span> to</span><br>";
                $list.="<span style='align:center;font-size:35pt;'>$firstname $lastname</span>";
                $list.="<span style='align:center;font-size:16pt;'><br>for successfully completion of the requirements in the program of</span><br>";
                $list.="<span style='align:center;font-size:40pt;font-weight:bold;'>Certified Nurse  Assistant</span><br>";
                $list.="<span style='align:center;text-decoration:underline;font-size:15pt;font-weight:normal;'><br>CERTIFICATE # $code</span><br>";
                if ($renew_status == true) {
                    $list.="EXPIRATION DATE $expiration_date</p>";
                } // end if $renew_status == true                                 
                // President signature
                $list.="<br><br><p align='center'><table border='0' width='675px;'><tr><td align='right' style='font-family:king;text-decoration:underline;border-bottom:thick;font-size:10pt;'> Mrs. Donna Steele<br/><span style='float:left;font-size:12pt;font-family: Geneva, Arial, Helvetica, sans-serif;text-decoration:none; '>Director</span></td><td align='right' style='font-family:king;text-decoration:underline;border-bottom:thick;font-size:10pt;'>Mrs. Vicky Gunter<br/><span style='float:left;font-size:12pt;font-family: Geneva, Arial, Helvetica, sans-serif;text-decoration:none; '>Program Instructional Director</span></td></tr></table></p";
                $list.="</div>";

                $list.="</body>";
                $list.="</html>";

                break;

            case 44:
                $list.="<!DOCTYPE HTML SYSTEM>";
                $list.="<head>";
                $list.="<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                $list.="<link rel='stylesheet' type='text/css' href='cert.css'>";
                $list.="</head>";
                $list.="<body>";
                $list.="<div class='cert'>";
                $list.="<p><span style='align:center;font-family:king;font-weight:bolder;font-size:25pt;'>$title</span><br>";
                $list.="<span style='align:center;font-weight:bold;font-size:15pt;'>Presents this Phlebotomy Workshop Certification the $day of $month $year To:</span>";
                $list.="<br><br><span style='align:center;font-weight:bold;font-size:20pt;'>$firstname $lastname</span><br>";
                $list.="<br><span style='align:center;font-weight:bold;font-size:15pt;'>For the successful completion of hands-on clinical exam and written certification exam.
                        Medical2 Career College hands-on workshop covered topics; basic anatomy of arm, safety techniques with routine venipuncture, 
                        patient bill of rights, order of draws, needle/syringe, winged infusion (butterfly), 
                        lancet, evacuated tube system, and corrective techniques.
                        </span>";
                $list.="<br><br><br><br><p style='align:center;text-decoration:underline;font-size:15pt;font-weight:normal;'>CERTIFICATION # $code<br>";
                if ($renew_status == true) {
                    $list.="EXPIRATION DATE $expiration_date</p>";
                } // end if $renew_status == true                                 
                // President signature
                $list.="<br><br><div align='left'><table border='0' width='675px;'><tr><td align='center' style='font-family:king;text-decoration:underline;border-bottom:thick;font-size:10pt;'>Shahid Malik<br/><span style='float:left;font-size:12pt;font-family: Geneva, Arial, Helvetica, sans-serif;text-decoration:none; '>President</span></td></tr></table></div>";
                $list.="</div>";

                $list.="</body>";
                $list.="</html>";

                break;

            case 45:
                $list.="<!DOCTYPE HTML SYSTEM>";
                $list.="<head>";
                $list.="<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                $list.="<link rel='stylesheet' type='text/css' href='cert.css'>";
                $list.="</head>";
                $list.="<body>";
                $list.="<div class='cert'>";
                $list.="<p><span style='align:center;font-family:king;font-weight:bolder;font-size:25pt;'>$title</span><br>";
                $list.="<span style='align:center;font-weight:bold;font-size:15pt;'>Presents this Phlebotomy with EKG certification the $day of $month $year To:</span>";
                $list.="<br><br><span style='align:center;font-weight:bold;font-size:20pt;'>$firstname $lastname</span><br>";
                $list.="<br><span style='align:center;font-weight:bold;font-size:15pt;'>For the successful completion of hands-on clinical exam and written certification exam.<br>"
                        . "Medical2 Career College hands-on workshop covered topics; basic anatomy of arm, safety techniques with routine venipuncture,<br> "
                        . "Patient bill of rights, order of draws, needle/syringe, winged infusion (butterfly), lancet, evacuated tube system, and corrective techniques.<br><br>"
                        . "The workshop also discusses procedures to perform an Electrocardiogram.</span>";
                $list.="<br><br><br><br><p style='align:center;text-decoration:underline;font-size:15pt;font-weight:normal;'>CERTIFICATION # $code<br>";
                if ($renew_status == true) {
                    $list.="EXPIRATION DATE $expiration_date</p>";
                } // end if $renew_status == true                                 
                // President signature
                $list.="<br><br><div align='left'><table border='0' width='675px;'><tr><td align='center' style='font-family:king;text-decoration:underline;border-bottom:thick;font-size:10pt;'>Shahid Malik<br/><span style='float:left;font-size:12pt;font-family: Geneva, Arial, Helvetica, sans-serif;text-decoration:none; '>President</span></td></tr></table></div>";
                $list.="</div>";

                $list.="</body>";
                $list.="</html>";

                break;

            case 47:
                $list.="<!DOCTYPE HTML SYSTEM>";
                $list.="<head>";
                $list.="<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                $list.="<link rel='stylesheet' type='text/css' href='cert.css'>";
                $list.="</head>";
                $list.="<body>";
                $list.="<div class='cert'>";
                $list.="<p style='align:center;font-family:king;font-weight:bolder;font-size:25pt;'>$title ";
                $list.="<br><span style='align:center;font-weight:bold;font-size:35pt;font-family: Geneva, Arial, Helvetica, sans-serif;'>$coursename<br>";
                $list.="<span style='align:center;font-weight:bold;font-size:15pt;'>Presents this certificate this the $day of $month $year To:</span>";
                $list.="<br><span style='align:center;font-weight:bold;font-size:35pt;'>$firstname $lastname</span>";
                $list.="<br><span style='align:center;font-weight:bold;font-size:15pt;'>For successfully meeting all requirements to hold this certification.</span>";
                $list.="<br><br><br><br><p style='align:center;text-decoration:underline;font-size:15pt;font-weight:normal;'>CERTIFICATION # $code<br>";
                if ($renew_status == true) {
                    $list.="EXPIRATION DATE $expiration_date</p>";
                }
                $list.="<div align='left'><table border='0' width='675px;'><tr><td align='center' style='font-family:king;text-decoration:underline;border-bottom:thick;font-size:10pt;'>Shahid Malik<br/><span style='float:left;font-size:12pt;font-family: Geneva, Arial, Helvetica, sans-serif;text-decoration:none; '>President</span></td></tr></table></div>";
                $list.="</div>";
                $list.="</body>";
                $list.="</html>";

                break;

            case 48:
                $coursename = "IV Therapy Exam";
                $list.="<!DOCTYPE HTML SYSTEM>";
                $list.="<head>";
                $list.="<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                $list.="<link rel='stylesheet' type='text/css' href='cert.css'>";
                $list.="</head>";
                $list.="<body>";
                $list.="<div class='cert'>";
                $list.="<p style='align:center;font-family:king;font-weight:bolder;font-size:25pt;'>$title ";
                $list.="<br><span style='align:center;font-weight:bold;font-size:35pt;font-family: Geneva, Arial, Helvetica, sans-serif;'>$coursename<br>";
                $list.="<span style='align:center;font-weight:bold;font-size:15pt;'>Presents this certificate this the $day of $month $year To:</span>";
                $list.="<br><span style='align:center;font-weight:bold;font-size:35pt;'>$firstname $lastname</span>";
                $list.="<br><span style='align:center;font-weight:bold;font-size:15pt;'>For successfully meeting all requirements to hold this certification.</span>";
                $list.="<br><br><br><br><p style='align:center;text-decoration:underline;font-size:15pt;font-weight:normal;'>CERTIFICATION # $code<br>";
                if ($renew_status == true) {
                    $list.="EXPIRATION DATE $expiration_date</p>";
                }
                $list.="<div align='left'><table border='0' width='675px;'><tr><td align='center' style='font-family:king;text-decoration:underline;border-bottom:thick;font-size:10pt;'>Shahid Malik<br/><span style='float:left;font-size:12pt;font-family: Geneva, Arial, Helvetica, sans-serif;text-decoration:none; '>President</span></td></tr></table></div>";
                $list.="</div>";
                $list.="</body>";
                $list.="</html>";

                break;

            case 49:
                $list.="<!DOCTYPE HTML SYSTEM>";
                $list.="<head>";
                $list.="<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                $list.="<link rel='stylesheet' type='text/css' href='cert.css'>";
                $list.="</head>";
                $list.="<body>";
                $list.="<div class='cert'>";
                $list.="<p><span style='align:center;font-family:king;font-weight:bolder;font-size:25pt;'>$title</span><br>";
                $list.="<span style='align:center;font-weight:bold;font-size:25pt;'>Obstetrics Technician Certificate</span>";
                $list.="<br><br><span style='align:center;font-weight:bold;font-size:15pt;'>Presented this the $day of $month $year To:</span>";
                $list.="<br><br><span style='align:center;font-weight:bold;font-size:20pt;'>$firstname $lastname</span><br>";
                $list.="<br><span style='align:center;font-weight:bold;font-size:15pt;'>for the successful completion of the Obstetrics Technician Written Exam. </span>";
                $list.="<br><br><br><br><p style='align:center;text-decoration:underline;font-size:15pt;font-weight:normal;'>CERTIFICATION # $code<br>";
                if ($renew_status == true) {
                    $list.="EXPIRATION DATE $expiration_date</p>";
                } // end if $renew_status == true                                 
                // President signature
                $list.="<br><br><br><br><br><div align='left'><table border='0' width='675px;'><tr><td align='center' style='font-family:king;text-decoration:underline;border-bottom:thick;font-size:10pt;'>Shahid Malik<br/><span style='float:left;font-size:12pt;font-family: Geneva, Arial, Helvetica, sans-serif;text-decoration:none; '>President</span></td></tr></table></div>";
                $list.="</div>";

                $list.="</body>";
                $list.="</html>";

                break;

            case 51:
                $coursename = "Phlebotomy Technician Exam";
                $list.="<!DOCTYPE HTML SYSTEM>";
                $list.="<head>";
                $list.="<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                $list.="<link rel='stylesheet' type='text/css' href='cert.css'>";
                $list.="</head>";
                $list.="<body>";
                $list.="<div class='cert'>";
                $list.="<p style='align:center;font-family:king;font-weight:bolder;font-size:25pt;'>$title ";
                $list.="<br><span style='align:center;font-weight:bold;font-size:35pt;font-family: Geneva, Arial, Helvetica, sans-serif;'>$coursename<br>";
                $list.="<span style='align:center;font-weight:bold;font-size:15pt;'>Presents this certificate this the $day of $month $year To:</span>";
                $list.="<br><span style='align:center;font-weight:bold;font-size:35pt;'>$firstname $lastname</span>";
                $list.="<br><span style='align:center;font-weight:bold;font-size:15pt;'>For successfully completing the Phlebotomy Technician Certification Exam.</span>";
                $list.="<br><br><br><br><p style='align:center;text-decoration:underline;font-size:15pt;font-weight:normal;'>CERTIFICATION # $code<br>";
                if ($renew_status == true) {
                    $list.="EXPIRATION DATE $expiration_date</p>";
                }
                $list.="<div align='left'><table border='0' width='675px;'><tr><td align='center' style='font-family:king;text-decoration:underline;border-bottom:thick;font-size:10pt;'>Shahid Malik<br/><span style='float:left;font-size:12pt;font-family: Geneva, Arial, Helvetica, sans-serif;text-decoration:none; '>President</span></td></tr></table></div>";
                $list.="</div>";
                $list.="</body>";
                $list.="</html>";

                break;

            case 55:

                $list.="<!DOCTYPE HTML SYSTEM>";
                $list.="<head>";
                $list.="<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                $list.="</head>";
                $list.="<body>";
                $list.="<div>";
                $list.="<p style='text-align:center'><span style='text-align:center'><img src='/assets/logo/5.png' width='55%' height='15%'></span><br>";
                $list.="<span style='align:center;font-weight:bold;font-size:8pt;'>Licensed by the Mississippi Commission on Proprietary School and College Registration, License No. C675</span>";
                $list.="<span style='align:center;font-size:16pt;'><br><br>Presents in recognition on this the $day of $month, $year</span><br>";
                $list.="<br><span style='align:center;font-size:16pt;'>this <span style='align:center;font-weight:bold;font-size:30pt;'>Diploma</span> to</span><br>";
                $list.="<span style='align:center;font-size:35pt;'>$firstname $lastname</span>";
                $list.="<span style='align:center;font-size:16pt;'><br>for successfully completion of the requirements in the program of</span><br>";
                $list.="<span style='align:center;font-size:40pt;font-weight:bold;'>Medical Assistant</span><br>";
                $list.="<span style='align:center;text-decoration:underline;font-size:15pt;font-weight:normal;'><br>DIPLOMA # $code</span><br>";
                if ($renew_status == true) {
                    $list.="EXPIRATION DATE $expiration_date</p>";
                } // end if $renew_status == true                                 
                // President signature
                $list.="<br><br><p align='center'><table border='0' width='675px;'><tr><td align='right' style='font-family:king;text-decoration:underline;border-bottom:thick;font-size:10pt;'> Mrs. Donna Steele<br/><span style='float:left;font-size:12pt;font-family: Geneva, Arial, Helvetica, sans-serif;text-decoration:none; '>Director</span></td><td align='right' style='font-family:king;text-decoration:underline;border-bottom:thick;font-size:10pt;'>Mrs. Vicky Gunter<br/><span style='float:left;font-size:12pt;font-family: Geneva, Arial, Helvetica, sans-serif;text-decoration:none; '>Program Instructional Director</span></td></tr></table></p";
                $list.="</div>";

                $list.="</body>";
                $list.="</html>";

                break;

            case 56:

                $list.="<!DOCTYPE HTML SYSTEM>";
                $list.="<head>";
                $list.="<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                $list.="</head>";
                $list.="<body>";
                $list.="<div>";
                $list.="<p style='text-align:center'><span style='text-align:center'><img src='/assets/logo/5.png' width='55%' height='15%'></span><br>";
                $list.="<span style='align:center;font-weight:bold;font-size:8pt;'>Licensed by the Mississippi Commission on Proprietary School and College Registration, License No. C675</span>";
                $list.="<span style='align:center;font-size:16pt;'><br><br>Presents in recognition on this the $day of $month, $year</span><br>";
                $list.="<br><span style='align:center;font-size:16pt;'>this <span style='align:center;font-weight:bold;font-size:30pt;'>Certificate</span> to</span><br>";
                $list.="<span style='align:center;font-size:35pt;'>$firstname $lastname</span>";
                $list.="<span style='align:center;font-size:16pt;'><br>for successfully completion of the requirements in the program of</span><br>";
                $list.="<span style='align:center;font-size:40pt;font-weight:bold;'>Medical Administrative Assistant</span><br>";
                $list.="<span style='align:center;text-decoration:underline;font-size:15pt;font-weight:normal;'><br>CERTIFICATE # $code</span><br>";
                if ($renew_status == true) {
                    $list.="EXPIRATION DATE $expiration_date</p>";
                } // end if $renew_status == true                                 
                // President signature
                $list.="<br><br><p align='center'><table border='0' width='675px;'><tr><td align='right' style='font-family:king;text-decoration:underline;border-bottom:thick;font-size:10pt;'> Mrs. Donna Steele<br/><span style='float:left;font-size:12pt;font-family: Geneva, Arial, Helvetica, sans-serif;text-decoration:none; '>Director</span></td><td align='right' style='font-family:king;text-decoration:underline;border-bottom:thick;font-size:10pt;'>Mrs. Vicky Gunter<br/><span style='float:left;font-size:12pt;font-family: Geneva, Arial, Helvetica, sans-serif;text-decoration:none; '>Program Instructional Director</span></td></tr></table></p";
                $list.="</div>";

                $list.="</body>";
                $list.="</html>";

                break;

            case 57:
                $list.="<!DOCTYPE HTML SYSTEM>";
                $list.="<head>";
                $list.="<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                $list.="<link rel='stylesheet' type='text/css' href='cert.css'>";
                $list.="</head>";
                $list.="<body>";
                $list.="<div class='cert'>";
                $list.="<p style='align:center;font-family:king;font-weight:bolder;font-size:25pt;'>$title ";
                $list.="<br><span style='align:center;font-weight:bold;font-size:25pt;font-family: Geneva, Arial, Helvetica, sans-serif;'>Phlebotomy Technician Certificate<br><br><br>";
                $list.="<span style='align:center;font-weight:bold;font-size:15pt;'>Presented this the $day of $month $year To:</span>";
                $list.="<br><br><br><span style='align:center;font-weight:bold;font-size:35pt;'>$firstname $lastname</span>";
                $list.="<br><span style='align:center;font-weight:bold;font-size:15pt;'>For successfully completing the Phlebotomy Technician Certification Exam. </span>";
                $list.="<br><br><br><p style='align:center;text-decoration:underline;font-size:15pt;font-weight:normal;'>CERTIFICATION # $code<br>";
                if ($renew_status == true) {
                    $list.="EXPIRATION DATE $expiration_date</p>";
                }
                $list.="<br><br><div align='left'><table border='0' width='675px;'><tr><td align='center' style='font-family:king;text-decoration:underline;border-bottom:thick;font-size:10pt;'>Shahid Malik<br/><span style='float:left;font-size:12pt;font-family: Geneva, Arial, Helvetica, sans-serif;text-decoration:none; '>President</span></td></tr></table></div>";
                $list.="</div>";
                $list.="</body>";
                $list.="</html>";

                break;

            default:
                $list.="<!DOCTYPE HTML SYSTEM>";
                $list.="<head>";
                $list.="<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
                $list.="<link rel='stylesheet' type='text/css' href='cert.css'>";
                $list.="</head>";
                $list.="<body>";
                $list.="<div class='cert'>";
                $list.="<p style='align:center;font-family:king;font-weight:bolder;font-size:25pt;'>$title ";
                $list.="<br><span style='align:center;font-weight:bold;font-size:35pt;font-family: Geneva, Arial, Helvetica, sans-serif;'>$coursename<br>";
                $list.="<span style='align:center;font-weight:bold;font-size:15pt;'>Presents this certificate this the $day of $month $year To:</span>";
                $list.="<br><span style='align:center;font-weight:bold;font-size:35pt;'>$firstname $lastname</span>";
                $list.="<br><span style='align:center;font-weight:bold;font-size:15pt;'>For successfully meeting all requirements to hold this certification.</span>";
                $list.="<br><br><br><br><p style='align:center;text-decoration:underline;font-size:15pt;font-weight:normal;'>CERTIFICATION # $code<br>";
                if ($renew_status == true) {
                    $list.="EXPIRATION DATE $expiration_date</p>";
                }
                $list.="<div align='left'><table border='0' width='675px;'><tr><td align='center' style='font-family:king;text-decoration:underline;border-bottom:thick;font-size:10pt;'>Shahid Malik<br/><span style='float:left;font-size:12pt;font-family: Geneva, Arial, Helvetica, sans-serif;text-decoration:none; '>President</td><td align='left' style='font-family:king;text-decoration:underline;border-bottom:thick;font-size:10pt;margin-left:40px;'>Donna Steele<br/><span style='float:right;font-size:12pt;font-family: Geneva, Arial, Helvetica, sans-serif;text-decoration:none;'>Director</span></td></tr></table></div>";
                $list.="</div>";
                $list.="</body>";
                $list.="</html>";

                break;
        } // end switch

        $pdf = new mPDF('utf-8', 'A4-L');
        $stylesheet = file_get_contents($this->cert_path . '/cert.css');

        if ($courseid != 41 && $courseid != 55 && $courseid != 56) {
            $pdf->WriteHTML($stylesheet, 1);
        }
        $pdf->WriteHTML($list, 2);

        $dir_path = $this->cert_path . "/$userid/$courseid";
        if (!is_dir($dir_path)) {
            if (!mkdir($dir_path)) {
                die('Could not write to disk');
            } // end if !mkdir($dir_path)
        } // end if !is_dir($dir_path)
        $path = $dir_path . "/certificate.pdf";
        $pdf->Output($path, 'F');
    }

    function get_create_box() {
        $list = "";
        $program_types = $this->get_course_categories();
        $issue = $this->get_start_unputs(FALSE);
        $expire = $this->get_end_inputs(FALSE);
        $list.="<div class='container-fluid'>
                <span class='span6'>$program_types</span>
                </div>
                
                <div class='container-fluid'>
                <span class='span6' id='category_courses'></span>
                </div>
        
                <div class='container-fluid'>
                <span class='span6' id='enrolled_users'></span>
                </div>                
                
                <div class='container-fluid' style='text-align:left;'>
                <span class='span9'>$issue</span>    
                </div>
                
                <div class='container-fluid' style='text-align:left;'>
                <span class='span9'>$expire</span>                  
                </div>
        
                <div class='container-fluid' style='text-align:center;'>
                <span class='span9'><button class='btn btn-primary' id='create_cert_button'>Create</button></span>                  
                </div>";

        return $list;
    }

    function add_certificate_data($cert, $issue, $expire) {
        $path = $this->cert_path . "/$cert->userid/certificate.pdf";
        $query = "insert into mdl_certificates "
                . "(courseid,"
                . "userid,"
                . "cert_no,"
                . "path,"
                . "issue_date,"
                . "expiration_date) "
                . "values($cert->courseid,"
                . "$cert->userid,"
                . "'$cert->code',"
                . "'$path',"
                . "'$issue',"
                . "'$expire')";
        $this->db->query($query);
    }

    function create_certificate($courseid, $userid, $start, $end) {
        $list = "";
        $issue = strtotime($start);
        $expire = strtotime($end);
        $code = $this->get_course_code($courseid, $userid);
        $cert = new stdClass();
        $cert->courseid = $courseid;
        $cert->userid = $userid;
        $cert->code = $code;
        $this->create_new_certificate($cert, $issue, $expire);
        $this->add_certificate_data($cert, $issue, $expire);
        $list.="Certificate has been created";
        return $list;
    }

    function create_certificate2($courseid, $userid, $start, $end) {
        $list = "";
        $issue = $start;
        $expire = $end;
        $code = $this->get_course_code($courseid, $userid);
        $cert = new stdClass();
        $cert->courseid = $courseid;
        $cert->userid = $userid;
        $cert->code = $code;
        $this->create_new_certificate($cert, $issue, $expire);
        $this->add_certificate_data($cert, $issue, $expire);
        $list.="Certificate has been created";
        return $list;
    }

    // ***************** Migration code *********************

    function get_old_location_cartificates($courseid = null) {
        $certificates = array();
        if ($courseid == null) {
            $query = "select * from mdl_certificates";
        } // end if 
        else {
            $query = "select * from mdl_certificates where courseid=$courseid";
        } // end else
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $cert = new stdClass();
            foreach ($row as $key => $value) {
                $cert->$key = $value;
            } // end foreach
            $certificates[] = $cert;
        } // end while
        $this->create_new_location_certificates_list($certificates);
    }

    function create_new_location_certificates_list($certificates) {
        $total = count($certificates);
        $i = 0;
        foreach ($certificates as $cert) {
            $user = $this->get_user_details($cert->userid);
            $cname = $this->get_course_name($cert->courseid);
            $issue = date('m-d-Y', $cert->issue_date);
            $exp = date('m-d-Y', $cert->expiration_date);
            echo "$user->firstname $user->lastname $cname $issue $exp  <br>";
            $this->create_new_certificate($cert, $cert->issue_date, $cert->expiration_date);
            echo "New location certificate has been created ... Item No $i of $total<br>";
            echo"<hr><br>";
            $i++;
        } // end foreach
        echo "Total certificates processed: " . count($certificates) . "<br>";
    }

    // ************************* Certificates Editor **************************

    function get_ceftificate_data($id) {
        $query = "select * from mdl_certificate_templates where courseid=$id";
        $num = $this->db->numrows($query);
        $template = new stdClass();
        if ($num > 0) {
            $result = $this->db->query($query);
            $template->status = 1;
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $template->issuer = $row['issuer'];
                $template->title = $row['title'];
                $template->content = $row['content'];
                $template->person1 = $row['person1'];
                $template->person1_title = $row['person1_title'];
                $template->person2 = $row['person2'];
                $template->person2_title = $row['person2_title'];
                $template->person3 = $row['person3'];
                $template->person3_title = $row['person3_title'];
            } // end while
        } // end if $num > 0
        else {
            $template->status = 0;
        }
        return json_encode($template);
    }

    function peview_template($template) {
        $list = "";
        $tempObject = json_decode($template);
        $tempObject->userid = 11772;

        //$coursename = $this->get_course_name($tempObject->courseid); // string
        $categoryid = $this->get_course_category($tempObject->courseid, true);
        $userdetails = $this->get_user_details($tempObject->userid); // object
        $firstname = strtoupper($userdetails->firstname);
        $lastname = strtoupper($userdetails->lastname);

        $issue_date = time();
        $day = date('dS', $issue_date);
        $month = date('F', $issue_date);
        $year = date('Y', $issue_date);
        $renew_status = $this->get_course_renew_status($tempObject->courseid);
        $code = $this->get_course_code($tempObject->courseid, $tempObject->userid);

        if ($renew_status == 1) {
            $expiration_date_sec = $issue_date + 31536000;
        } // end if $renew_status == 1
        //echo "Category ID: ".$categoryid."<br>";
        $expiration_date = strtoupper(date('m-d-Y', $expiration_date_sec));
        if ($categoryid == 2) {
            // workshops
            $list.="<!DOCTYPE HTML SYSTEM>";
            $list.="<head>";
            $list.="<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
            $list.="<link rel='stylesheet' type='text/css' href='/lms/custom/certificates/cert2.css'>";
            $list.="</head>";
            $list.="<body>";
            $list.="<div class='cert'>";
            $list.="<p><span style='align:center;font-family:king;font-weight:bolder;font-size:25pt;'>$tempObject->issuer</span><br>";
            $list.="<br><br><span style='align:center;font-weight:bold;font-size:15pt;'>$tempObject->title the $day of $month $year To:</span>";
            $list.="<br><br><span style='align:center;font-weight:bold;font-size:20pt;'>$firstname $lastname</span><br>";
            $list.="<br><span style='align:center;font-weight:bold;font-size:15pt;'>$tempObject->content</span>";
            $list.="<br><br><p style='align:center;text-decoration:underline;font-size:15pt;font-weight:normal;'>CERTIFICATION # $code<br>";
            if ($renew_status == true) {
                $list.="EXPIRATION DATE $expiration_date</p>";
            } // end if $renew_status == true                                 

            $list.="<br><br><div align='left'><table border='0' align='left' width='675px;'><tr>";

            if ($tempObject->person1 != '' && $tempObject->person1_title != '') {
                $list.="<td align='left' style='padding-left:35px;font-family:king;text-decoration:underline;border-bottom:thick;font-size:10pt;'>$tempObject->person1<br/><span style='float:cebter;font-size:12pt;font-family: Geneva, Arial, Helvetica, sans-serif;text-decoration:none; '>$tempObject->person1_title</span></td>";
            }

            if ($tempObject->person2 != '' && $tempObject->person2_title != '') {
                $list.="<td align='center' style='font-family:king;text-decoration:underline;border-bottom:thick;font-size:10pt;'>$tempObject->person2<br/><span style='float:center;font-size:12pt;font-family: Geneva, Arial, Helvetica, sans-serif;text-decoration:none; '>$tempObject->person2_title</span></td>";
            }

            if ($tempObject->person3 != '' && $tempObject->person3_title != '') {
                $list.="<td align='center' style='font-family:king;text-decoration:underline;border-bottom:thick;font-size:10pt;'>$tempObject->person3<br/><span style='float:center;font-size:12pt;font-family: Geneva, Arial, Helvetica, sans-serif;text-decoration:none; '>$tempObject->person3_title</span></td>";
            }

            $list.="</tr></table></div>";
            $list.="</div>";

            $list.="</body>";
            $list.="</html>";
        } // end $categoryid==2
        if ($categoryid == 5) {
            // health care courses
            $list.="<!DOCTYPE HTML SYSTEM>";
            $list.="<head>";
            $list.="<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
            $list.="<link rel='stylesheet' type='text/css' href='/lms/custom/certificates/cert2.css'>";
            $list.="</head>";
            $list.="<body>";
            $list.="<div style='text-align:center;'>";
            $list.="<p style='text-align:center'><span style='text-align:center'><img src='/assets/logo/5.png' width='55%' height='15%'></span><br>";
            $list.="<span style='align:center;font-weight:bold;font-size:8pt;'>$tempObject->issuer</span>";
            $list.="<br><br><span style='align:center;font-weight:bold;font-size:35pt;'>Diploma of Completion</span><br>";
            $list.="<br><span style='align:center;font-size:16pt;'>Presents and declares on this the $day of $month, $year</span><br>";
            $list.="<br><span style='align:center;font-size:40pt;'>$firstname $lastname</span><br>";
            $list.="<span style='text-align:center;font-size:16pt;'>$tempObject->content </span><br>";
            $list.="<span style='text-align:center;font-size:26pt;'>$tempObject->title</span><br>";
            $list.="<br><br><span style='text-align:center;text-decoration:underline;font-size:15pt;font-weight:normal;'>DIPLOMA # $code</span><br>";
            if ($renew_status == true) {
                $list.="EXPIRATION DATE $expiration_date</p>";
            } // end if $renew_status == true                                 
            // President signature

            $list.="<br><br><div align='left'><table border='0' align='center' width='675px;'><tr>";

            if ($tempObject->person1 != '' && $tempObject->person1_title != '') {
                $list.="<td align='left' style='padding-left:35px;font-family:king;text-decoration:underline;border-bottom:thick;font-size:10pt;'>$tempObject->person1<br/><span style='float:cebter;font-size:12pt;font-family: Geneva, Arial, Helvetica, sans-serif;text-decoration:none; '>$tempObject->person1_title</span></td>";
            }

            if ($tempObject->person2 != '' && $tempObject->person2_title != '') {
                $list.="<td align='center' style='font-family:king;text-decoration:underline;border-bottom:thick;font-size:10pt;'>$tempObject->person2<br/><span style='float:center;font-size:12pt;font-family: Geneva, Arial, Helvetica, sans-serif;text-decoration:none; '>$tempObject->person2_title</span></td>";
            }

            if ($tempObject->person3 != '' && $tempObject->person3_title != '') {
                $list.="<td align='center' style='font-family:king;text-decoration:underline;border-bottom:thick;font-size:10pt;'>$tempObject->person3<br/><span style='float:center;font-size:12pt;font-family: Geneva, Arial, Helvetica, sans-serif;text-decoration:none; '>$tempObject->person3_title</span></td>";
            }

            $list.="</tr></table></div>";
            $list.="</div>";

            $list.="</body>";
            $list.="</html>";
        } // end if $categoryid==5

        if ($categoryid == 3 || $categoryid == 4) {
            // exams
            $list.="<!DOCTYPE HTML SYSTEM>";
            $list.="<head>";
            $list.="<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
            $list.="<link rel='stylesheet' type='text/css' href='/lms/custom/certificates/cert2.css'>";
            $list.="</head>";
            $list.="<body>";
            $list.="<div class='cert'>";
            $list.="<p><span style='align:center;font-family:king;font-weight:bolder;font-size:25pt;'>$tempObject->issuer</span><br>";
            $list.="<br><br><span style='align:center;font-weight:bold;font-size:25pt;'>$tempObject->title </span>";
            $list.="<br><br><span style='align:center;font-weight:bold;font-size:15pt;'>Presents this certificate this the $day of $month $year To:</span>";
            $list.="<br><br><span style='align:center;font-weight:bold;font-size:20pt;'>$firstname $lastname</span><br>";
            $list.="<br><span style='align:center;font-weight:bold;font-size:15pt;'>$tempObject->content</span>";
            $list.="<br><br><p style='align:center;text-decoration:underline;font-size:15pt;font-weight:normal;'>CERTIFICATION # $code<br>";
            if ($renew_status == true) {
                $list.="EXPIRATION DATE $expiration_date</p>";
            } // end if $renew_status == true                                 

            $list.="<br><br><div align='left'><table border='0' align='left' width='675px;'><tr>";

            if ($tempObject->person1 != '' && $tempObject->person1_title != '') {
                $list.="<td align='left' style='padding-left:35px;font-family:king;text-decoration:underline;border-bottom:thick;font-size:10pt;'>$tempObject->person1<br/><span style='float:cebter;font-size:12pt;font-family: Geneva, Arial, Helvetica, sans-serif;text-decoration:none; '>$tempObject->person1_title</span></td>";
            }

            if ($tempObject->person2 != '' && $tempObject->person2_title != '') {
                $list.="<td align='center' style='font-family:king;text-decoration:underline;border-bottom:thick;font-size:10pt;'>$tempObject->person2<br/><span style='float:center;font-size:12pt;font-family: Geneva, Arial, Helvetica, sans-serif;text-decoration:none; '>$tempObject->person2_title</span></td>";
            }

            if ($tempObject->person3 != '' && $tempObject->person3_title != '') {
                $list.="<td align='center' style='font-family:king;text-decoration:underline;border-bottom:thick;font-size:10pt;'>$tempObject->person3<br/><span style='float:center;font-size:12pt;font-family: Geneva, Arial, Helvetica, sans-serif;text-decoration:none; '>$tempObject->person3_title</span></td>";
            }

            $list.="</tr></table></div>";
            $list.="</div>";

            $list.="</body>";
            $list.="</html>";
        }

        return $list;
    }

    function create_template($template) {
        $tempObject = json_decode($template);
        $query = "select * from mdl_certificate_templates "
                . "where courseid=$tempObject->courseid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $query2 = "update mdl_certificate_templates "
                    . "set issuer='$tempObject->issuer', "
                    . "title='$tempObject->title', "
                    . "content='$tempObject->content', "
                    . "person1='$tempObject->person1',  "
                    . "person1_title='$tempObject->person1_title', "
                    . "person2='$tempObject->person2', "
                    . "person2_title='$tempObject->person2_title', "
                    . "person3='$tempObject->person3', "
                    . "person3_title='$tempObject->person3_title' "
                    . "where courseid=$tempObject->courseid";
        } // end if $num>0
        else {
            $query2 = "insert into mdl_certificate_templates "
                    . "(courseid,"
                    . "issuer,"
                    . "title,"
                    . "content,"
                    . "person1,"
                    . "person1_title,"
                    . "person2,"
                    . "person2_title,"
                    . "person3,"
                    . "person3_title) "
                    . "values('$tempObject->courseid',"
                    . "'$tempObject->issuer',"
                    . "'$tempObject->title',"
                    . "'$tempObject->content',"
                    . "'$tempObject->person1',"
                    . "'$tempObject->person1_title',"
                    . "'$tempObject->person2',"
                    . "'$tempObject->person2_title',"
                    . "'$tempObject->person3',"
                    . "'$tempObject->person3_title')";
        } // end else
        $this->db->query($query2);
        $list = "Certificate template was added/updated";
        return $list;
    }

    // ************************ Late fees *****************************
}
