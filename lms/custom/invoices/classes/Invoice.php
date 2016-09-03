<?php

/**
 * Description of Invoice
 *
 * @author sirromas
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/Invoice.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/Mailer.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/Late.php';

class Invoices extends Util {

    public $limit = 3;

    /* *******************************************************
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
    
    

    /* *******************************************************
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

    function is_course_taxable($courseid) {
        $query = "select taxes from mdl_course where id=$courseid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $status = $row['taxes'];
        }
        return $status;
    }

    function get_user_state($userid) {
        $query = "select state from mdl_user where id=$userid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $state = $row['state'];
        }
        return $state;
    }

    function get_state_taxes($state) {
        $query = "select tax from mdl_state_taxes where state='$state'";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $tax = $row['tax'];
        }
        return $tax;
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
        // State taxes section
        $tax_status = $this->is_course_taxable($courseid);
        if ($tax_status == 1) {
            $user_state = $this->get_user_state($userid);
            $tax = $this->get_state_taxes($user_state);
        } // end if $tax_status == 1
        else {
            $tax = 0;
        } // end else
        if ($tax > 0) {
            $tax_sum = round(($installment->sum * $tax) / 100, 2);
            $installment->sum = round(($installment->sum + $tax_sum), 2);
        }
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
        //echo "<br/>Current user: $this->user->id<br/>";        
        $invoices = array();
        $query = "select * from mdl_invoice "
                . "where i_status=0 and i_ptype=0 order by i_date desc limit 0,$this->limit";
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

    function get_invoice_sum($courseid, $userid, $i_sum) {
        $late = new Late();
        $late_fee = $late->get_delay_fee($courseid);
        $query = "select * from mdl_scheduler_appointment where studentid=$userid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $slotid = $row['slotid'];
            }
        } // end if $num > 0
        else {
            $slotid = 0;
        } // end else        

        if ($slotid > 0) {
            $is_apply_late_fee = $late->is_apply_delay_fee($courseid, $slotid);
        } // end if $slotid>0
        else {
            $is_apply_late_fee = false;
        } // end else 

        if ($is_apply_late_fee) {
            $sum = $i_sum + $late_fee;
        } // end if $is_apply_late_fee
        else {
            $sum = $i_sum;
        } // end else         
        return $sum;
    }

    function create_open_invoices_page($invoices, $toolbar = true, $paid = false, $seacrh=false) {
        $list = "";
        //print_r($invoices);
        if ($toolbar == TRUE) {
            $list.="<div class='container-fluid' style='text-align:center;'>";
            $list.="<span class='span2'>Search</span>";
            $list.="<span class='span2'><input type='text' id='search_invoice_input' class='typeahead’ autocomplete='off' spellcheck='false' style='width:125px;' /></span>";
            if ($paid == false) {
                $list.="<span class='span3'><button class='btn btn-primary' id='search_open_invoice_user'>Search</button></span>";
                $list.="<span class='span2'><button class='btn btn-primary' id='clear_open_invoice'>Clear filter</button></span>";
            } // end if $paid==false
            else {
                $list.="<span class='span3'><button class='btn btn-primary' id='search_paid_invoice_user'>Search</button></span>";
                $list.="<span class='span2'><button class='btn btn-primary' id='clear_paid_invoice'>Clear filter</button></span>";
            } // end else             
            $list.="</div>";
            $list.="<div class='container-fluid' style='text-align:center;'>";
            $list.="<span class='span8' style='color:red;' id='invoice_err'></span>";
            $list.="</div>";
            $list.="<div class='container-fluid' style='display:none;text-align:center;' id='ajax_loader'>";
            $list.="<span class='span8'><img src='http://$this->host/assets/img/ajax.gif' /></span>";
            $list.="</div>";
        } // end if $toolbar == TRUE

        if (count($invoices) > 0) {
            $list.="<div id='open_invoices_container'>";
            $total = count($invoices);
            if ($total == $this->limit && $seacrh==false) {
                if ($paid == false) {
                    $total = $this->get_open_invoices_total();
                } // end if $paid==false
                else {
                    $total = $this->get_paid_invoices_total();
                } // end else 
            }
            $list.="<div class='container-fluid' style='text-align:center;font-weight:bold;'>";
            $list.="<span class='span8'>Total invoices: $total</span>";
            $list.="</div>";
            foreach ($invoices as $invoice) {
                $user = $this->get_user_details($invoice->userid);
                $date = date('Y-m-d', time());
                $coursename = $this->get_course_name($invoice->courseid);
                $prefix = ($paid == false) ? "from " : "paid date ";
                $link = trim(str_replace($_SERVER['DOCUMENT_ROOT'], '', $invoice->i_file));
                $address_block=$this->get_user_address_block($invoice->userid);
                $list.="<div class='container-fluid'>";
                $list.="<span class='span2'>User</span><span class='span6'><a href='https://" . $_SERVER['SERVER_NAME'] . "/lms/user/profile.php?id=$invoice->userid' target='_blank'>$user->firstname $user->lastname ($user->email)</a></span>";
                $list.="</div>";
                
                $list.="<div class='container-fluid'>";
                $list.="<span class='span2'>Address</span><span class='span6'>$address_block</span>";
                $list.="</div>";

                $list.="<div class='container-fluid'>";
                $list.="<span class='span2'>Program applied</span><span class='span6'>$coursename</span>";
                $list.="</div>";
                $sum = $this->get_invoice_sum($invoice->courseid, $invoice->userid, $invoice->i_sum);
                $list.="<div class='container-fluid'>";
                $list.="<span class='span2'>Invoice</span><span class='span6'>Invoice # $invoice->i_num for $$sum $prefix $date (<a href='$link' target='_blank'>link</a>)</span>";
                $list.="</div>";

                if ($paid == false) {
                    $list.="<div class='container-fluid'>";
                    $list.="<span class='span3'><a id='change_paid_$invoice->id' href='#' onClick='return false;'>Make it paid</a></span>";
                    $list.="</div>";
                    $payment_types = $this->get_payment_methods($invoice->id);
                    $list.="<div id='change_payment_status_page_$invoice->id' style='display:none;'>";
                    $list.="<div class='container-fluid'>";
                    $list.=$payment_types;
                    $list.="</div>";
                    $list.="<div class='container-fluid'>";
                    $list.="<span class='span3'><button type='button' id='make_paid_$invoice->id' class='btn btn-primary'>Make it paid</button></span><span class='span5' id='invoice_status_$invoice->id'></span>";
                    $list.="</div>";
                    $list.="</div>";
                } // end if $paid==false

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
        $rec_limit = $this->limit;
        if ($page == 1) {
            $offset = 0;
        } // end if $page==1
        else {
            $page = $page - 1;
            $offset = $rec_limit * $page;
        }
        $query = "select * from mdl_invoice where i_status=0 and i_ptype=0 "
                . "order by i_date desc "
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
                . "where i_status=1 order by i_date desc limit 0, $this->limit";
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
        $rec_limit = $this->limit;
        if ($page == 1) {
            //echo "inside if ...";
            $offset = 0;
        } // end if $page==1
        else {
            $page = $page - 1;
            $offset = $rec_limit * $page;
        }
        $query = "select * from mdl_invoice where i_status=1 "
                . "order by  i_date desc "
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

    /*     * ******************************************************
     * 
     *                Make invoice paid
     * 
     * ******************************************************* */

    function get_payment_methods($id) {
        $list = "";
        $query = "select * from mdl_payments_type order by type";
        $list.="<span class='span3'>Payment type:</span><span class='span4'><select id='payment_type_$id' >";
        $list.="<option value='0' selected>Payment type</option>";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $list.="<option value='" . $row['id'] . "'>" . $row['type'] . "</option>";
        }
        $list.="</select></span>";
        return $list;
    }

    function get_invoice_detailes($id) {
        $query = "select * from mdl_invoice where id=$id";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $invoice = new stdClass();
            foreach ($row as $key => $value) {
                $invoice->$key = $value;
            } // end foreach 
        } // end while
        return $invoice;
    }

    function make_invoice_paid($id, $payment_type) {
        $modifierid = $this->user->id;
        $payment_date = time();

        //1. Get invoice detailes
        $invoice = $this->get_invoice_detailes($id);

        //2. Get user detailes
        $user = $this->get_user_details($invoice->userid);

        // 3. Confirm user
        $query = "update mdl_user set confirmed=1 "
                . "where id=$invoice->userid";
        $this->db->query($query);

        // 4. Make invoice as paid
        $query = "update mdl_invoice "
                . "set i_status=1, "
                . "i_ptype=$payment_type, "
                . "i_pdate='$payment_date' "
                . "where id=$id";
        $this->db->query($query);

        if ($invoice->renew == 1) {
            $query = "select * from mdl_user_balance "
                    . "where courseid=$invoice->courseid "
                    . "and userid=$invoice->userid";
            $num = $this->db->numrows($query);
            if ($num > 0) {
                $query = "update mdl_user_balance "
                        . "set balance_sum='$invoice->i_sum' "
                        . "where courseid=$invoice->courseid "
                        . "and userid=$invoice->userid";
                $this->db->query($query);
            } // end if $num>0
            else {
                $query = "insert into mdl_user_balance "
                        . "(userid,"
                        . "courseid,"
                        . "is_certified,"
                        . "cert_no,"
                        . "cert_exp,"
                        . "balance_sum) "
                        . "values ('$invoice->userid',"
                        . "'$invoice->courseid',"
                        . "1,"
                        . "'/$invoice->courseid/certificate.pdf',"
                        . "1,"
                        . "'$invoice->i_sum')";
                $this->db->query($query);
            } // end else 
        } // end if $invoice->renew==1
        //5. Add log entry
        $sum = $this->get_invoice_sum($invoice->courseid, $invoice->userid, $invoice->i_sum);
        $query = "insert into "
                . "mdl_payments_log "
                . "(userid,"
                . "courseid,"
                . "modifierid,"
                . "sum,"
                . "payment_type,"
                . "date_added) "
                . "values('" . $invoice->userid . "', "
                . "'" . $invoice->courseid . "',"
                . "'" . $modifierid . "',"
                . "'" . $sum . "',"
                . "'" . $payment_type . "',"
                . "'" . $payment_date . "')";
        $this->db->query($query);

        //6. Send payment confirmation email        
        $mailer = new Mailer();
        $user->bill_email = $user->email;
        $user->card_holder = $user->firstname . "&nbsp;" . $user->lastname;
        $user->sum = $sum;
        if ($payment_type != 3) {
            $mailer->send_payment_confirmation_message($user, null, null);
        } // end if $payment_type!=3
        else {
            $mailer->send_payment_confirmation_message($user, null, true);
        } // end else

        $list = "Invoice made as paid. Please reload the page";
        return $list;
    }

    function search_invoice_users($item) {
        $users = array();
        $data=explode(' ', $item);
        $firstname=$data[1];
        $lastname=$data[0];
        $query = "select id from mdl_user "
                . "where (firstname='$firstname' and lastname='$lastname') "
                . "or email like '%$item%' and deleted=0";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $users[] = $row['id'];
            } // end while
        } // end if $num > 0
        return $users;
    }

    function search_invoice_courses($item) {
        $courses = array();
        $query = "select id from mdl_course where fullname like '%$item%'";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $courses[] = $row['id'];
            } // end while
        } // end if $num > 0
        return $courses;
    }

    function search_invoice($item, $paid = false) {
        $list = "";
        $invoices = array();
        $users_list = implode(",", $this->search_invoice_users($item));
        $courses_list = implode(",", $this->search_invoice_courses($item));
        $status = ($paid == false) ? 0 : 1;
        if ($users_list != '') {
            $query = "select * from mdl_invoice "
                    . "where i_status=$status "
                    . "and userid in ($users_list) order by i_date desc ";
        } // end if $users_list != ''
        if ($courses_list != '') {
            $query = "select * from mdl_invoice "
                    . "where i_status=$status "
                    . "and courseid in ($courses_list) order by i_date desc ";
        } // end if $courses_list != ''
        if ($users_list != '' && $courses_list != '') {
            $query = "select * from mdl_invoice "
                    . "where i_status=$status "
                    . "and (courseid in ($courses_list) "
                    . "or userid in ($users_list)) order by i_date desc ";
        } // end if $users_list != '' && $courses_list != ''
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
            if ($paid == false) {
                $list.= $this->create_open_invoices_page($invoices, false, false, true);
            } // end if $paid==false
            else {
                $list.= $this->create_open_invoices_page($invoices, false, true, true);
            } // end else 
        } // end if $num > 0
        else {
            $list.="<div class='container-fluid' style='text-align:center;'>";
            $list.="<span class='span6'>No invoices found</span>";
            $list.="</div>";
        } // end else 
        return $list;
    }

}
