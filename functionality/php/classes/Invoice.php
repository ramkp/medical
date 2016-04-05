<?php

/**
 * Description of Invoice
 *
 * @author sirromas
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/class.pdo.database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/pdf/dompdf/autoload.inc.php';

use Dompdf\Dompdf;

class Invoice {

    public $db;
    public $invoice_path;

    function __construct() {
        $this->db = new pdo_db();
        $this->invoice_path = $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/invoices';
    }

    function get_invoice_credentials() {
        $query = "select * from mdl_invoice_credentials";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $data = new stdClass();
            foreach ($row as $key => $value) {
                $data->$key = $value;
            }
        }
        return $data;
    }

    function get_personal_course_cost($courseid) {
        $query = "select id, discount_size, cost from mdl_course "
                . "where id=$courseid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $cost = $row['cost'];
            $discount = $row['discount_size'];
        }
        if ($discount > 0) {
            $final_cost = $cost - round(($cost * $discount) / 100, 2);
        } // end if $discount>0
        else {
            $final_cost = $cost;
        }
        $course_cost = array('cost' => $final_cost, 'discount' => $discount);
        return $course_cost;
    }

    function get_course_group_discount($courseid, $tot_participants) {

        // 1. Get course cost
        $query = "select id, cost from mdl_course "
                . "where id=$courseid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $cost = $row['cost'];
        }

        // 2. Get course group discount
        $query = "select courseid, group_discount_size "
                . "from mdl_group_discount "
                . "where courseid=$courseid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $discount = $row['group_discount_size'];
        }

        $group_cost = $cost * $tot_participants;
        if ($discount > 0) {
            $final_cost = $group_cost - round(($group_cost * $discount) / 100, 2);
        } // end if $discount>0
        else {
            $final_cost = round($group_cost, 2);
        }
        $course_cost = array('cost' => $final_cost, 'discount' => $discount);
        return $course_cost;
    }

    function get_personal_invoice($user, $group = null, $participants = null) {
        $invoice_path = $this->create_user_invoice($user, $group, $participants);
        return $invoice_path;
    }

    function get_course_name($courseid) {
        $query = "select id, fullname from mdl_course "
                . "where id=$courseid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $name = $row['fullname'];
        }
        return $name;
    }

    function is_installment_user($userid, $courseid) {
        if ($userid != '') {
            $query = "select *  from mdl_installment_users "
                    . "where userid=$userid and courseid=$courseid";
            //echo "is_installment Query: ".$query."<br>";
            $num = $this->db->numrows($query);
        } // end if $userid!=''
        else {
            $num = 0;
        } // end else
        return $num;
    }

    function get_user_installment_payments($userid, $courseid) {
        $query = "select * from mdl_installment_users where userid=$userid and courseid=$courseid";
        //echo "get_user_installment Query: ".$query."<br>";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $installment = new stdClass();
            $installment->sum = $row['sum'];
            $installment->num = $row['num'];
        }
        return $installment;
    }

    function is_course_taxable($courseid) {
        $query = "select taxes from mdl_course where id=$courseid";
        //echo "is_course_taxable query: " .$query."<br>";
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

    function get_invoice_user_data($userid) {
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

    function get_renew_fee() {
        $query = "select * from mdl_renew_fee where id=1";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $fee = $row['fee_sum'];
        }
        return $fee;
    }

    function send_renew_invoice($courseid, $userid) {
        $item_name = $this->get_course_name($courseid);
        $user_data = $this->get_invoice_user_data($userid);
        $sum = $this->get_renew_fee();
        $invoice_credentials = $this->get_invoice_credentials();
        $invoice_num = $this->get_invoice_num();
        $amount = $sum;
        $tax = 0;
        $renew = 1;

        $list.="<html>";
        $list.="<body>";
        $list.= "<p></p>";
        $list.="<br/><br/><table border='0' align='center'>";
        $list.="<tr>";
        $list.="<td colspan='2' width='65%' style=''><img src='" . $_SERVER['DOCUMENT_ROOT'] . "/assets/logo/5.png' width='350' height=90></td><td width='35%' style='padding-left:10px;padding-right:10px;border-left:1px solid;'>Phone: $invoice_credentials->phone<br/>Fax: $invoice_credentials->fax</td>";
        $list.="</tr>";
        $list.="<tr>";
        $list.="<td colspan='3' style='border-bottom:1px solid;padding-top:1px;height:10px;'></td>";
        $list.="</tr>";
        $list.="<tr>";
        $list.="<td style='padding-top:6px;' colspan='2'>No: $invoice_num</td><td width='30%' style='padding-left:10px;'>Date: " . date('Y/m/d', time()) . "</td>";
        $list.="</tr>";
        $list.="<tr style=''>";
        $list.="<td colspan='3' style='padding-top:1px;height:35px;'></td>";
        $list.="</tr>";
        $list.="<tr bgcolor='black'>";
        $list.="<td style='text-align:center;color:black;' width='15' height='15'>&nbsp;</td><td style='padding-left:15px;text-align:left;' width='10%' bgcolor='white'><span style='color:#ff8000;font-weight:bolder;'>INVOICE TO </span></td><td style='text-align:left;color:black;padding-left:15px;' width='70%'>&nbsp;</td>";
        $list.="</tr>";

        if ($user_data->group_name == '') {
            // Single registration
            $item_block = "$user_data->firstname $user_data->lastname<br/> payment for Certificate renew ($item_name)";
            $list.="<tr>";
            $list.="<td colspan='3'>$user_data->firstname $user_data->lastname<br/>$user_data->address<br/> $user_data->state" . "/" . $user_data->city . "<br/>$user_data->zip</td>";
            $list.="</tr>";
        } // end if $user->group_name==''
        else {
            // Group registration
            $item_block = "$user_data->group_name<br/> payment for $item_name";
            $list.="<tr>";
            $list.="<td colspan='3'>$user_data->group_name<br/>$user_data->address<br/> $user_data->state" . "/" . $user_data->city . "<br/>$user_data->zip</td>";
            $list.="</tr>";
        } // end else

        $list.="<tr>";
        $list.="<td colspan='3' style='border-bottom:0px solid;padding-top:1px;height:40px;'></td>";
        $list.="</tr>";
        $list.="<tr bgcolor='black'>";
        $list.="<td style='color:white;text-align:center' width='10%'>No</td><td style='padding-left:15px;text-align:left;color:white' width='60%'>Description</td><td style='text-align:left;color:white;padding-left:15px;' width='5%'>Amount</td>";
        $list.="</tr>";

        $list.="<tr bgcolor='#FAF7F5'>";
        $list.="<td style='text-align:center;color:black;' width='10%' height='55'>1</td><td style='padding-left:15px;text-align:left;color:black' width='60%'>$item_block</td><td style='text-align:left;color:black;padding-left:15px;' width='5%'>$$amount</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td></td><td style='padding:10px;' align='right'>Subtotal</td><td bgcolor='black' style='color:white;padding-left:15px;'>$" . $amount . "</td>";
        $list.="</tr>";
        if ($tax == 0) {
            $list.="<tr>";
            $list.="<td></td><td style='padding:10px;' align='right'>Tax</td><td bgcolor='black' style='padding-left:15px;color:white;'>$0</td>";
            $list.="</tr>";
            $list.="<tr>";
            $list.="<td></td><td style='padding:10px;' align='right'>Total</td><td bgcolor='black' style='padding-left:15px;color:white;'>$" . $amount . "</td>";
            $list.="</tr>";
        } // end if $tax==0
        else {
            $tax_sum = round(($cost['cost'] * $tax) / 100, 2);
            $grand_total = round(($cost['cost'] + $tax_sum), 2);
            $list.="<tr>";
            $list.="<td></td><td style='padding:10px;' align='right'>Tax</td><td bgcolor='black' style='padding-left:15px;color:white;'>$$tax_sum</td>";
            $list.="</tr>";
            $list.="<tr>";
            $list.="<td></td><td style='padding:10px;' align='right'>Total</td><td bgcolor='black' style='padding-left:15px;color:white;'>$" . $grand_total . "</td>";
            $list.="</tr>";
        } // end else when tax is not null
        $list.="<tr>";
        $list.="<td colspan='3' style='border-bottom:0px solid;padding-top:1px;height:55px;'></td>";
        $list.="</tr>";
        $list.="<tr bgcolor='#ff8000'>";
        $list.="<td colspan='3' height='35px' style='color:white;fonr-weight:bold;' align='center'>email: " . $invoice_credentials->email . "&nbsp;&nbsp;&nbsp; " . $invoice_credentials->site . " </td>";
        $list.="</tr>";
        $list.="</table>";
        $list.="</body>";
        $list.="</html>";

        // instantiate and use the dompdf class
        $dompdf = new Dompdf();
        $dompdf->loadHtml($list);

        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();
        $output = $dompdf->output();

        $file_path = $this->invoice_path . "/$invoice_num.pdf";
        file_put_contents($file_path, $output);

        $query = "insert into mdl_invoice"
                . "(i_num,"
                . "userid,"
                . "courseid,"
                . "renew,"
                . "i_sum,"
                . "i_status,"
                . "i_file,"
                . "i_date) "
                . "values ('" . $invoice_num . "',"
                . "'" . $userid . "', "
                . "'" . $courseid . "',"
                . "'" . $renew . "',"
                . "'" . $sum . "',"
                . "'0',"
                . "'" . $file_path . "',"
                . "'" . time() . "')";
        $this->db->query($query);        
        return $invoice_num;
    }

    function create_user_invoice($user, $group, $participants) {
        $user_installment_status = $this->is_installment_user($user->id, $user->courseid);
        if ($user_installment_status == 0) {
            if ($group == null) {
                $cost = $this->get_personal_course_cost($user->courseid); // cost, discount
                $item_name = $this->get_course_name($user->courseid);
                $tax_status = $this->is_course_taxable($user->courseid);
                // Get invoice user data
                $user_data = $this->get_invoice_user_data($user->id);
                if ($tax_status == 1) {
                    $user_state = $this->get_user_state($user->id);
                    $tax = $this->get_state_taxes($user_state);
                } // end if $tax_status == 1
                else {
                    $tax = 0;
                } // end else
            } // end if $group==null
            else {
                $group_data = $_SESSION['group_common_section'];
                $participants = ($participants == null) ? $_SESSION['tot_participants'] : $participants;
                $cost = $this->get_course_group_discount($group_data->courseid, $participants); // cost, discount
                $item_name = $this->get_course_name($group_data->courseid);
                $tax_status = $this->is_course_taxable($group_data->courseid);
                if ($tax_status == 1) {
                    $user_state = $group_data->state;
                    $tax = $this->get_state_taxes($user_state);
                } // end if $tax_status == 1
                else {
                    $tax = 0;
                } // end else
                // Get invoice user data
                $user_data = new stdClass();
                $user_data->group_name = $group_data->group_name;
                $user_data->address = $group_data->addr;
                $user_data->city = $group_data->city;
                $user_data->state = $group_data->state;
                $user_data->zip = $group_data->zip;
            } // end else when it is group members
        } // end if $user_installment_status==0
        else {
            $installment = $this->get_user_installment_payments($user->id, $user->courseid);
            $cost['cost'] = $installment->sum;
            $cost['discount'] = 0;
            $item_name = $this->get_course_name($user->courseid);
            $tax_status = $this->is_course_taxable($user->courseid);
            if ($tax_status == 1) {
                $user_state = $this->get_user_state($user->id);
                $tax = $this->get_state_taxes($user_state);
            } // end if $tax_status == 1
            else {
                $tax = 0;
            } // end else
            // Get invoice user data
            $user_data = $this->get_invoice_user_data($user->id);
        } // end else when installment is active
        if ($cost['discount'] > 0) {
            $amount = '$' . $cost['cost'] . '&nbsp;(discount is ' . $cost['discount'] . '%)';
        } // end if $cost['discount']>0
        else {
            $amount = '$' . $cost['cost'];
        }
        $invoice_credentials = $this->get_invoice_credentials();
        $invoice_num = $this->get_invoice_num();

        $list = "";
        $list.="<html>";
        $list.="<body>";
        $list.= "<p></p>";
        $list.="<br/><br/><table border='0' align='center'>";
        $list.="<tr>";
        $list.="<td colspan='2' width='65%' style=''><img src='" . $_SERVER['DOCUMENT_ROOT'] . "/assets/logo/5.png' width='350' height=90></td><td width='35%' style='padding-left:10px;padding-right:10px;border-left:1px solid;'>Phone: $invoice_credentials->phone<br/>Fax: $invoice_credentials->fax</td>";
        $list.="</tr>";
        $list.="<tr>";
        $list.="<td colspan='3' style='border-bottom:1px solid;padding-top:1px;height:10px;'></td>";
        $list.="</tr>";
        $list.="<tr>";
        $list.="<td style='padding-top:6px;' colspan='2'>No: $invoice_num</td><td width='30%' style='padding-left:10px;'>Date: " . date('Y/m/d', time()) . "</td>";
        $list.="</tr>";
        $list.="<tr style=''>";
        $list.="<td colspan='3' style='padding-top:1px;height:35px;'></td>";
        $list.="</tr>";
        $list.="<tr bgcolor='black'>";
        $list.="<td style='text-align:center;color:black;' width='15' height='15'>&nbsp;</td><td style='padding-left:15px;text-align:left;' width='10%' bgcolor='white'><span style='color:#ff8000;font-weight:bolder;'>INVOICE TO </span></td><td style='text-align:left;color:black;padding-left:15px;' width='70%'>&nbsp;</td>";
        $list.="</tr>";

        if ($user_data->group_name == '') {
            // Single registration
            $item_block = "$user_data->firstname $user_data->lastname<br/> payment for $item_name";
            $list.="<tr>";
            $list.="<td colspan='3'>$user_data->firstname $user_data->lastname<br/>$user_data->address<br/> $user_data->state" . "/" . $user_data->city . "<br/>$user_data->zip</td>";
            $list.="</tr>";
        } // end if $user->group_name==''
        else {
            // Group registration
            $item_block = "$user_data->group_name<br/> payment for $item_name";
            //$item_block = "$user_data->firstname $user_data->lastname<br/> payment for $item_name";
            $list.="<tr>";
            $list.="<td colspan='3'>$user_data->group_name<br/>$user_data->address<br/> $user_data->state" . "/" . $user_data->city . "<br/>$user_data->zip</td>";
            $list.="</tr>";
        } // end else


        $list.="<tr>";
        $list.="<td colspan='3' style='border-bottom:0px solid;padding-top:1px;height:40px;'></td>";
        $list.="</tr>";
        $list.="<tr bgcolor='black'>";
        $list.="<td style='color:white;text-align:center' width='10%'>No</td><td style='padding-left:15px;text-align:left;color:white' width='60%'>Description</td><td style='text-align:left;color:white;padding-left:15px;' width='5%'>Amount</td>";
        $list.="</tr>";

        $list.="<tr bgcolor='#FAF7F5'>";
        $list.="<td style='text-align:center;color:black;' width='10%' height='55'>1</td><td style='padding-left:15px;text-align:left;color:black' width='60%'>$item_block</td><td style='text-align:left;color:black;padding-left:15px;' width='5%'>$amount</td>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<td></td><td style='padding:10px;' align='right'>Subtotal</td><td bgcolor='black' style='color:white;padding-left:15px;'>$" . $cost['cost'] . "</td>";
        $list.="</tr>";
        if ($tax == 0) {
            $list.="<tr>";
            $list.="<td></td><td style='padding:10px;' align='right'>Tax</td><td bgcolor='black' style='padding-left:15px;color:white;'>$0</td>";
            $list.="</tr>";
            $list.="<tr>";
            $list.="<td></td><td style='padding:10px;' align='right'>Total</td><td bgcolor='black' style='padding-left:15px;color:white;'>$" . $cost['cost'] . "</td>";
            $list.="</tr>";
        } // end if $tax==0
        else {
            $tax_sum = round(($cost['cost'] * $tax) / 100, 2);
            $grand_total = round(($cost['cost'] + $tax_sum), 2);
            $list.="<tr>";
            $list.="<td></td><td style='padding:10px;' align='right'>Tax</td><td bgcolor='black' style='padding-left:15px;color:white;'>$$tax_sum</td>";
            $list.="</tr>";
            $list.="<tr>";
            $list.="<td></td><td style='padding:10px;' align='right'>Total</td><td bgcolor='black' style='padding-left:15px;color:white;'>$" . $grand_total . "</td>";
            $list.="</tr>";
        } // end else when tax is not null
        $list.="<tr>";
        $list.="<td colspan='3' style='border-bottom:0px solid;padding-top:1px;height:55px;'></td>";
        $list.="</tr>";
        $list.="<tr bgcolor='#ff8000'>";
        $list.="<td colspan='3' height='35px' style='color:white;fonr-weight:bold;' align='center'>email: " . $invoice_credentials->email . "&nbsp;&nbsp;&nbsp; " . $invoice_credentials->site . " </td>";
        $list.="</tr>";
        $list.="</table>";
        $list.="</body>";
        $list.="</html>";

        // instantiate and use the dompdf class
        $dompdf = new Dompdf();
        $dompdf->loadHtml($list);

        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();
        $output = $dompdf->output();

        $file_path = $this->invoice_path . "/$invoice_num.pdf";
        file_put_contents($file_path, $output);
        return $invoice_num;
    }

    function get_invoice_num() {
        $id = 1;
        $query = "select id from mdl_invoice order by id desc limit 0,1";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            $result = $this->db->query($query);
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $id = $row['id'] + 1001;
            }
        } // end if $num>0
        $invoice_num = "M2CC_$id";
        return $invoice_num;
    }

}
