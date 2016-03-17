<?php

/**
 * Description of Invoice
 *
 * @author sirromas
 */
require_once ($_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php');
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/Invoice.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/Mailer.php';

class Invoices extends Util {

    function get_invoice_crednetials() {
        $list = "";
        $query = "select * from mdl_invoice_credentials";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $item = new stdClass();
            foreach ($row as $key => $value) {
                $item->$key = $value;
            } // end foreach
        } // end while
        $list.="<div class='container-fluid'>";
        $list.="<span class='span5' id='invoice_status'></span>";
        $list.="</div>";
        $list.="<div class='container-fluid'>";
        $list.="<span class='span3' style='font-weight:bold;'>Phone</span><span class='span3' style='font-weight:bold;'>Fax</span><span class='span3' style='font-weight:bold;'>Email</span><span class='span3' style='font-weight:bold;'>Site</span>";
        $list.="</div>";
        $list.="<div class='container-fluid'>";
        $list.="<span class='span3'><input type='text' id='phone' value='$item->phone' size='15'></span><span class='span3'><input type='text' id='fax' value='$item->fax' size='15'></span><span class='span3'><input type='text' id='email' value='$item->email' size='20'></span><span class='span3'><input type='text' id='site' value='$item->site' size='20'></span>";
        $list.="</div>";
        $list.="<div class='container-fluid'>";
        $list.="<span class='span2'><button type='button' id='invoice_data' class='btn btn-primary'>Save</button></span>";
        $list.="</div>";
        return $list;
    }

    function update_invoice_crednetials($phone, $fax, $email, $site) {
        $query = "update mdl_invoice_credentials set phone='$phone', "
                . "fax='$fax',"
                . "email='$email', "
                . "site='$site' where id=1";
        $this->db->query($query);
        $list = "Item successfully updated";
        return $list;
    }

    function get_send_invoice_page() {
        $list = "";
        $program_types = $this->get_course_categories();
        $list.="<div class='container-fluid'>";
        $list.="<span class='span5' id='invoice_status'></span>";
        $list.="</div>";
        $list.="<div class='container-fluid'>";
        $list.="<span class='span6'>$program_types</span>";
        $list.="</div>";
        $list.="<div class='container-fluid'>";
        $list.="<span class='span6' id='category_courses'></span>";
        $list.="</div>";
        $list.="<div class='container-fluid'>";
        $list.="<span class='span6' id='enrolled_users'></span>";
        $list.="</div>";
        $list.="<div class='container-fluid'>";
        $list.="<span class='span2'><button type='button' id='send_invoice' class='btn btn-primary'>Send</button></span>";
        $list.="</div>";
        return $list;
    }

    function send_invoice($courseid, $userid) {
        $file_invoice = new Invoice();
        $mailer = new Mailer();
        $user = $this->get_user_details($userid);
        $user->first_name = $user->firstname;
        $user->last_name = $user->lastname;
        $user->courseid = $courseid;        
        $user->invoice = $file_invoice->create_user_invoice($user, null, 1);        
        $path = $file_invoice->invoice_path . "/$user->invoice.pdf";
        $sum = $file_invoice->get_personal_course_cost($courseid);
        $query = "insert into mdl_invoice"
                . "(i_num,"
                . "userid,"
                . "courseid,"
                . "i_sum,"
                . "i_status,"
                . "i_file,"
                . "i_date) "
                . "values ('" . $user->invoice . "',"
                . "'" . $userid . "', "
                . "'" . $courseid . "',"
                . "'" . $sum['cost'] . "',"
                . "'0',"
                . "'" . $path . "',"
                . "'" . time() . "')";
        //echo "Query: ".$query."<br/>";
        $this->db->query($query);
        $mailer->send_invoice($user);
        $list = "Invoice has been sent.";
        return $list;
    }

    function get_open_invoices() {
        $query = "";
    }

}
