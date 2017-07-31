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

    /*     * ******************************************************
     * 
     *             Invoice credentials
     * 
     * ******************************************************* */

    function get_invoice_crednetials() {
        $list = "";
        if ($this->session->justloggedin == 1) {
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
        } // end if $this->session->justloggedin == 1
        else {
            $list.='non_auth';
        }
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


        if ($this->session->justloggedin == 1) {
            $program_types = $this->get_course_categories();
            $invoice_program_types = $this->get_invoice_course_categories();

            // Send invoice to company or non-existing user
            $list.="<div class='container-fluid'>";
            $list.="<div class='container-fluid' style='font-weight:bold;'>";
            $list.="<span class='span6'>Send invoice to company or new user</span>";
            $list.="</div>";
            $list.="<div class='container-fluid'>";
            $list.="<span class='span9' id='any_invoice_status'></span>";
            $list.="</div>";
            $list.="<div class='container-fluid'>";
            $list.="<span class='span6'>$invoice_program_types</span>";
            $list.="</div>";

            $list.="<div class='container-fluid'>";
            $list.="<span class='span6' id='invoice_category_courses'></span>";
            $list.="</div>";

            $list.="<div class='container-fluid' id='invoice_client_row' style='display:none;'>";
            $list.="<span class='span1' style=''>Client* </span><span class='span4' style='margin-left:52px;'><input type='text' id='invoice_client' style='width:265px;'></span>";
            $list.="</div>";

            $list.="<div class='container-fluid' id='invoice_amount_row' style='display:none;'>";
            $list.="<span class='span1' style=''>Amount* </span><span class='span4' style='margin-left:52px;'><input type='text' id='invoice_amount' style='width:265px;'></span>";
            $list.="</div>";
            
            $list.="<div class='container-fluid' id='invoice_item_row' style=''>";
            $list.="<span class='span1' style=''>Item* </span><span class='span4' style='margin-left:52px;'><input type='text' id='invoice_item' style='width:265px;'></span>";
            $list.="</div>";

            $list.="<div class='container-fluid' id='invoice_email_row' style='display:none;'>";
            $list.="<span class='span1'>Email* </span><span class='span4' style='margin-left:52px;'><input type='text' id='invoice_email' style='width:265px;'></span>";
            $list.="</div>";

            $list.="<div class='container-fluid'>";
            $list.="<span class='span2'><button type='button' id='send_any_invoice' class='btn btn-primary'>Send</button></span>";
            $list.="</div>";
            $list.="</div";

            $list.="<br><br><div class='container-fluid'>";
            $list.="<span class='span6'><hr></span>";
            $list.="</div><br><br>";

            // Send invoice to existing user
            $list.="<div class='container-fluid'>";
            $list.="<div class='container-fluid' style='font-weight:bold;'>";
            $list.="<span class='span6'>Send invoice to existing user</span>";
            $list.="</div>";
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
            $list.="</div>";
        } // end if  $this->session->justloggedin == 1
        else {
            $list.="<p>You are not authenticated. &nbsp; <a href='https://medical2.com/login'><button class='btn btn-primary' id='relogin'>Login</button></a></p>";
        }

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
        $list = "";
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
        $list.= "Invoice has been sent.";
        return $list;
    }

    /*     * ******************************************************
     * 
     *             Open invoices
     * 
     * ******************************************************* */

    function get_open_invoices() {
        //echo "<br/>Current user: $this->user->id<br/>";    
        $list = "";
        if ($this->session->justloggedin == 1) {
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
            $list .= $this->create_open_invoices_page($invoices, true, false);
        } // end if if ($this->session->justloggedin == 1) 
        else {
            $list.="<p>You are not authenticated. &nbsp; <a href='https://medical2.com/login'><button class='btn btn-primary' id='relogin'>Login</button></a></p>";
        }
        return $list;
    }

    function get_any_invoice_sum($invoiceid) {
        $query = "select * from mdl_invoice where id=$invoiceid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $sum = $row['i_sum'];
        }
        return $sum;
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

    function create_open_invoices_page($invoices, $toolbar = true, $paid = false, $seacrh = false) {
        $list = "";
        $permission = $this->check_module_permission('invoice');
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
            if ($total == $this->limit && $seacrh == false) {
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

                if ($invoice->userid > 0) {
                    $user = $this->get_user_details($invoice->userid);
                    $address_block = $this->get_user_address_block($invoice->userid);
                }

                $date = date('Y-m-d', $invoice->i_date);
                $coursename = $this->get_course_name($invoice->courseid);
                $prefix = ($paid == false) ? "from " : "paid date ";
                $link = trim(str_replace($_SERVER['DOCUMENT_ROOT'], '', $invoice->i_file));

                if ($invoice->userid > 0) {
                    $list.="<div class='container-fluid'>";
                    $list.="<span class='span2'>User</span><span class='span6'><a href='https://" . $_SERVER['SERVER_NAME'] . "/lms/user/profile.php?id=$invoice->userid' target='_blank'>$user->firstname $user->lastname ($user->email)</a></span>";
                    $list.="</div>";
                } // end if $invoice->userid > 0 
                else {
                    $list.="<div class='container-fluid'>";
                    $list.="<span class='span2'>Client</span><span class='span6'>$invoice->client</span>";
                    $list.="</div>";
                }

                if ($invoice->userid > 0) {
                    $list.="<div class='container-fluid'>";
                    $list.="<span class='span2'>Address</span><span class='span6'>$address_block</span>";
                    $list.="</div>";
                }

                $list.="<div class='container-fluid'>";
                $list.="<span class='span2'>Program applied</span><span class='span6'>$coursename</span>";
                $list.="</div>";
                if ($invoice->userid > 0) {
                    $sum = $this->get_invoice_sum($invoice->courseid, $invoice->userid, $invoice->i_sum);
                } // end if $invoice->userid > 0 
                else {
                    $sum = $this->get_any_invoice_sum($invoice->id);
                }
                $list.="<div class='container-fluid'>";
                $list.="<span class='span2'>Invoice</span><span class='span6'>Invoice # $invoice->i_num for $$sum $prefix $date (<a href='$link' target='_blank'>link</a>)</span>";
                $list.="</div>";

                if ($paid == false) {

                    if ($this->user->id == 2) {
                        // It is admin                		
                        $list.="<div class='container-fluid'>";
                        $list.="<span class='span3'><a id='change_paid_$invoice->id' href='#' onClick='return false;'>Make it paid</a></span>";
                        $list.="</div>";
                    } else {
                        if ($permission == 1) {
                            $list.="<div class='container-fluid'>";
                            $list.="<span class='span3'><a id='change_paid_$invoice->id' href='#' onClick='return false;'>Make it paid</a></span>";
                            $list.="</div>";
                        } // end if $permission==1
                    }

                    $payment_types = $this->get_payment_methods($invoice->id);
                    $list.="<div id='change_payment_status_page_$invoice->id' style='display:none;'>";

                    $list.="<div class='container-fluid'>";
                    $list.=$payment_types;
                    $list.="</div>";

                    if ($invoice->userid > 0) {
                        $list.="<div class='container-fluid'>";
                        $list.="<span class='span3'><button type='button' id='make_paid_$invoice->id' class='btn btn-primary'>Make it paid</button></span><span class='span5' id='invoice_status_$invoice->id'></span>";
                        $list.="</div>";
                    } else {
                        $list.="<div class='container-fluid'>";
                        $list.="<span class='span3'><button type='button' id='make_any_paid_$invoice->id' class='btn btn-primary'>Make it paid</button></span><span class='span5' id='invoice_status_$invoice->id'></span>";
                        $list.="</div>";
                    }

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
        $list = "";

        if ($this->session->justloggedin == 1) {
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
            $list .= $this->create_open_invoices_page($invoices, true, true);
        } // end if $this->session->justloggedin == 1
        else {
            $list.="<p>You are not authenticated. &nbsp; <a href='https://medical2.com/login'><button class='btn btn-primary' id='relogin'>Login</button></a></p>";
        } // end else 

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
            if ($row['type'] != 'free') {
                $list.="<option value='" . $row['id'] . "'>" . $row['type'] . "</option>";
            }
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

    function attach_any_invoice_payment($invoice_id, $type, $users_list) {
        //$args = func_get_args();
        //var_export($args);
        // Please be aware $users_list is single user first and last name
        $invoice_userid = $this->get_userid_by_fio($users_list);
        if ($invoice_userid > 0) {
            $users_array = array();
            array_push($users_array, $invoice_userid);
            if (count($users_array) > 0) {
                // 1. Make invoice as paid
                $date = time();
                $query = "update mdl_invoice "
                        . "set i_status=1, "
                        . "i_ptype=$type, "
                        . "i_pdate='$date' "
                        . "where id=$invoice_id";
                $this->db->query($query);

                foreach ($users_array as $userid) {

                    // 2. Attach payment to users 
                    $query = "insert into mdl_any_invoice_user "
                            . "(invoiceid, userid) "
                            . "values($invoice_id, $userid)";
                    $this->db->query($query);

                    //3. Confirm user's accounts
                    $query = "update mdl_user set confirmed=1 where id=$userid";
                    $this->db->query($query);
                } // end foreach
                $list = 1;
            } // end if count($users_array)>0
            else {
                $list = 0;
            }
        } // end if $userid > 0

        return $list;
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
        $data = explode(' ', trim($item));
        /*
        echo "<br>---------<br>";
        print_r($data);
        echo "<br>---------<br>";
        */
        $total = count($data);
        //echo "Total items: " . $total . "<br>";
        if ($total == 1) {
            $query = "select id from mdl_user "
                    . "where email like '%$item%' "
                    . "or phone1 like '%$item%' "
                    . "and deleted=0";
            //echo "Query: " . $query . "<br>";
        } // end if count($data==1) 

        if ($total == 2) {
            $firstname = $data[1];
            $lastname = $data[0];
        } // end if $data == 2

        if ($total == 3) {
            $firstname = $data[2];
            $lastname = $data[0];
        } // end if count($data == 3)

        $query = "select id from mdl_user "
                . "where firstname='$firstname' "
                . "and lastname='$lastname' "
                . "and deleted=0";
        //echo "Query: " . $query . "<br>";
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

    function send_any_invoice($courseid, $amount, $email, $client, $item) {
        $file_invoice = new Invoice();
        $mailer = new Mailer();
        $invoice_data = $file_invoice->create_any_invoice($courseid, $amount, $client, $item);
        $invoice_file_name = $invoice_data['file'];
        $invoice_num = $invoice_data['num'];
        // userid is 0 because user does not exist in the system for now
        $date = time();
        $query = "insert into mdl_invoice (i_num, 
    			              userid, 
    			              client,
    			              courseid,
    			              renew,
    			              i_sum, 
    			              i_status, 
    			              i_file, 
    			              i_ptype, 
    			              i_date) 
    			values('$invoice_num', 
    	               0, 
    	               '$client',
    	               $courseid, 
    	               0,
    	               '$amount',
    	               0,
    	               '$invoice_file_name',
    	               0,
    	               '$date')";
        //echo "Query: ".$query."<br>";
        $this->db->query($query);
        $mailer->send_any_invoice($client, $email, $invoice_file_name);
        $list = "Invoice has been sent.";
        return $list;
    }

    function get_any_invoice_course_categories() {
        $list = "";
        $items = array();
        $query = "select id, name from mdl_course_categories order by name";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $item = new stdClass();
            foreach ($row as $key => $value) {
                $item->$key = $value;
            } // end foreach
            $items[] = $item;
        } // end while
        if (count($items) > 0) {
            $list.="<span class='span4'>";
            $list.="<select id='any_invoice_categories' style='width:275px;'>";
            $list.="<option value='0' selected>Program type</option>";
            foreach ($items as $item) {
                $list.="<option value='$item->id'>$item->name</option>";
            } // end foreach
            $list.="</select>";
            $list.="</span>";
        } // end if count($items)>0
        return $list;
    }

    function get_any_invoice_courses_by_category($id) {
        $list = "";
        $items = array();
        $query = "select id, fullname from mdl_course where category=$id 
    					 and cost>0 and visible=1";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $item = new stdClass();
                foreach ($row as $key => $value) {
                    $item->$key = $value;
                } // end foreach
                $items[] = $item;
            } // end while
            $list.="<span class='span4'>";
            $list.="<select id='any_invoice_courses' style='width:275px;'>";
            $list.="<option value='0' selected>Program</option>";
            foreach ($items as $item) {
                $list.="<option value='$item->id'>$item->fullname</option>";
            } // end foreach
            $list.="</select>";
            $list.="</span>";
        } // end if $num>0
        else {
            $list.="<span class='span4'>n/a</span>";
        }
        return $list;
    }

    function get_any_invoice_users($id) {
        $list = "";
        $users = array();
        //1. Get course context
        $instanceid = $this->get_course_context($id);

        //2. Get course users
        $query = "select id, roleid, contextid, userid "
                . "from mdl_role_assignments "
                . "where roleid=$this->student_role and contextid=$instanceid";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $status = $this->is_user_deleted($row['userid']);
                if ($status == 0) {
                    $user = new stdClass();
                    foreach ($row as $key => $value) {
                        $user->$key = $value;
                    } // end foreach
                    $user_detailes = $this->get_user_details($user->userid);

                    if ($user_detailes->lastname != '') {
                        $users[strtolower(trim($user_detailes->lastname)) . "." . strtolower(trim($user_detailes->firstname))] = $user;
                    } // end if $user_details->firstname != '' && $user_details->lastname != ''
                    //$users[] = $user;
                } // end if $status==0
            } // end while
        } // end if $num > 0
        ksort($users);
        if (count($users) > 0) {
            $list.="<span class='span4'>";
            $list.="<select id='users' multiple style='width:275px;'>";
            $list.="<option value='0' selected>Select user</option>";
            foreach ($users as $user) {
                $user_details = $this->get_user_details($user->userid);
                $list.="<option value='$user->userid'>" . ucfirst(strtolower(trim($user_details->lastname))) . " &nbsp;" . ucfirst(strtolower(trim($user_details->firstname))) . "</option>";
            } // end foreach            
            $list.="</select></span>";
        } // end if count($users)>0
        else {
            $list.="<span class='span3'>Enrolled users:</span><span class='span4'>n/a</span>";
        }
        return $list;
    }

    function get_any_invoice_dilog($id, $type) {
        //$args = func_get_args();
        //var_export($args);
        $programs = $this->get_any_invoice_course_categories();
        $list = "";

        $list.="<div id='myModal' class='modal fade'>
        <input type='hidden' id='invoice_id' value='$id'>
        <input type='hidden' id='invoice_payment_type' value='$type'>   
            
        <div class='modal-dialog'>
        <div class='modal-content' >
            <div class='modal-header'>                
                <h4 class='modal-title'>Attach payment</h4>
                </div> 
                
                <div class='modal-body' style='height:200px;'>     
                
                <div class='container-fluid' style='text-align:center;'>
                <span>User*</span>
                <span><input type='text' id='search_user_input'></span>    
                </div>
                
                <div class='container-fluid' style='text-align:center;'>
                <span id='any_invoice_users_err'></span>    
                </div>
                
                </div>
                
                <div class='modal-footer'>
                <span align='center'><button type='button' class='btn btn-primary' data-dismiss='modal' id='cancel'>Cancel</button></span>
                <span align='center'><button type='button' class='btn btn-primary' id='attach_invoice_payment'>Go</button></span>
                </div>
        
        </div>
        </div>";

        return $list;
    }

}
