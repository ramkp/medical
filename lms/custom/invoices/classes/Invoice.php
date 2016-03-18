<?php

/**
 * Description of Invoice
 *
 * @author sirromas
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/Invoice.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/Mailer.php';

class Invoices extends Util {
    /*     * ******************************************************
     * 
     *             Invoice credentials
     * 
     * ******************************************************* */

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

    /*     * ******************************************************
     * 
     *             Send invoice
     * 
     * ******************************************************* */

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
        $user->id = $userid;
        $user->first_name = $user->firstname;
        $user->last_name = $user->lastname;
        $user->courseid = $courseid;
        $user->invoice = $file_invoice->create_user_invoice($user, null, 1);
        $path = $file_invoice->invoice_path . "/$user->invoice.pdf";
        $user_installment_status = $file_invoice->is_installment_user($userid, $courseid);
        if ($user_installment_status == 0) {
            $installment = new stdClass();
            $cost = $file_invoice->get_personal_course_cost($courseid);
            $sum = $cost['cost'];
            $installment->sum = $sum;
        } // end if $user_installment_status==0
        else {
            $installment = $file_invoice->get_user_installment_payments($userid, $courseid);
        } // end else 
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
                . "'" . $installment->sum . "',"
                . "'0',"
                . "'" . $path . "',"
                . "'" . time() . "')";
        $this->db->query($query);
        $mailer->send_invoice($user);
        $list = "Invoice has been sent.";
        return $list;
    }

    /*     * ******************************************************
     * 
     *             Open invoices
     * 
     * ******************************************************* */

    function get_open_invoices() {
        $invoices = array();
        $query = "select * from mdl_invoice "
                . "where i_status=0 and i_ptype=0 order by id asc limit 0,1";
        //echo $query."<br/>";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $invoice = new stdClass();
                foreach ($row as $key => $value) {
                    $invoice->$key = $value;
                }
                $invoices[] = $invoice;
            } // end while
        } // end if $num>0
        $list = $this->create_open_invoices_page($invoices, true, false);
        return $list;
    }

    function create_open_invoices_page($invoices, $toolbar = true, $paid = false) {
        $list = "";
        //print_r($invoices);
        if (count($invoices) > 0) {
            $list.="<div id='open_invoices_container'>";
            foreach ($invoices as $invoice) {
                $user = $this->get_user_details($invoice->userid);
                $date = date('Y-m-d', time());
                $coursename = $this->get_course_name($invoice->courseid);
                $link = trim(str_replace($_SERVER['DOCUMENT_ROOT'], '', $invoice->i_file));
                $list.="<div class='container-fluid'>";
                $list.="<span class='span2'>User</span><span class='span6'>$user->firstname $user->lastname</span>";
                $list.="</div>";

                $list.="<div class='container-fluid'>";
                $list.="<span class='span2'>Program applied</span><span class='span6'>$coursename</span>";
                $list.="</div>";

                $list.="<div class='container-fluid'>";
                $list.="<span class='span2'>Invoice</span><span class='span6'>Invoice # $invoice->i_num for $$invoice->i_sum from $date (<a href='$link' target='_blank'>link</a>)</span>";
                $list.="</div>";
                $list.="<div class='container-fluid'>";
                $list.="<span class='span8'><hr/></span>";
                $list.="</div>";
            } // end foreach
            $list.="</div>";
            if ($toolbar) {
                $list.="<div class='container-fluid'>";
                $list.="<span class='span6'  id='pagination'></span>";
                $list.="</div>";
            } // end if $toolbar
        } // end if count($invoices)>0
        else {
            if ($paid == false) {
                $list.="<div class='container-fluid'>";
                $list.="<span class='span3'>There are no open invoices</span>";
                $list.="</div>";
            } // end if $paid==false
            else {
                $list.="<div class='container-fluid'>";
                $list.="<span class='span3'>There are no paid invoices</span>";
                $list.="</div>";
            } // end else
        }
        return $list;
    }

    function get_open_invoice_item($page) {
        //echo "Function page: " . $page . "<br>";
        $invoices = array();
        $rec_limit = 1;
        if ($page == 1) {
            $offset = 0;
        } // end if $page==1
        else {
            $page = $page - 1;
            $offset = $rec_limit * $page;
        }
        $query = "select * from mdl_invoice where i_status=0 and i_ptype=0 "
                . "LIMIT $offset, $rec_limit";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $invoice = new stdClass();
            foreach ($row as $key => $value) {
                $invoice->$key = $value;
            } // end foreach      
            $invoices[] = $invoice;
        } // end while
        $list = $this->create_open_invoices_page($invoices, false, false);
        return $list;
    }

    function get_open_invoices_total() {
        $query = "select * from mdl_invoice where i_status=0 and i_ptype=0 "
                . "order by id asc";
        $num = $this->db->numrows($query);
        return $num;
    }

    /*     * ******************************************************
     * 
     *             Paid invoices
     * 
     * ******************************************************* */

    function get_paid_invoices() {
        $invoices = array();
        $query = "select * from mdl_invoice "
                . "where i_status=1 order by id asc limit 0,1";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $invoice = new stdClass();
                foreach ($row as $key => $value) {
                    $invoice->$key = $value;
                }
                $invoices[] = $invoice;
            } // end while
        } // end if $num>0
        $list = $this->create_open_invoices_page($invoices, true, true);
        return $list;
    }

    function get_paid_invoices_total() {
        $query = "select * from mdl_invoice where i_status=1  "
                . "order by id asc";
        $num = $this->db->numrows($query);
        return $num;
    }

    function get_paid_invoice_item($page) {
        //echo "Function page: ".$page."<br>";
        $invoices = array();
        $rec_limit = 1;
        if ($page == 1) {
            //echo "inside if ...";
            $offset = 0;
        } // end if $page==1
        else {
            $page = $page - 1;
            $offset = $rec_limit * $page;
        }
        $query = "select * from mdl_invoice where i_status=1  "
                . "LIMIT $offset, $rec_limit";
        //echo $query;
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $invoice = new stdClass();
            foreach ($row as $key => $value) {
                $invoice->$key = $value;
            } // end foreach      
            $invoices[] = $invoice;
        } // end while
        $list = $this->create_open_invoices_page($invoices, false, true);
        return $list;
    }

}
