<?php

/**
 * Description of Invoice
 *
 * @author sirromas
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/class.pdo.database.php';

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

    function get_personal_invoice($user) {
        $invoice_path = $this->create_user_invoice($user);
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

    function create_user_invoice($user) {
        $cost = $this->get_personal_course_cost($user->courseid);
        if ($cost['discount'] > 0) {
            $amount = '$' . $cost['cost'] . '&nbsp;(discount is ' . $cost['discount'] . '%)';
        } // end if $cost['discount']>0
        else {
            $amount = '$' . $cost['cost'];
        }
        $invoice_credentials = $this->get_invoice_credentials();
        $invoice_num = $this->get_invoice_num();
        $item_name = $this->get_course_name($user->courseid);

        $list = "";
        $list.="<html>";
        $list.="<body>";
        $list.= "<p></p>";
        $list.="<br/><br/><table border='0'>";
        $list.="<tr>";
        $list.="<td colspan='2' width='75%' style=''><img src='http://cnausa.com/assets/logo/logo_site.jpg' width='300' height=90></td><td width='30%' style='padding-left:10px;padding-right:10px;border-left:1px solid;'>Phone: $invoice_credentials->phone<br/>Fax: $invoice_credentials->fax</td>";
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
        $list.="<tr valign='middle'>";
        $list.="<td  style='' colspan='3'><div style='width: 100%; height: 6px; border-bottom: 12px solid black;'>
                <span style='font-size: 20px; background-color: white; padding: 0 10px;margin-left:75px;'>
                <span style='color:#e2a500;font-weight:bold;'>INVOICE TO </span>
                </span>
                </div></td>";
        $list.="</tr>";
        $list.="<tr>";
        $list.="<td colspan='3' style='border-bottom:0px solid;padding-top:1px;height:40px;'></td>";
        $list.="</tr>";
        $list.="<tr bgcolor='black'>";
        $list.="<td style='color:white;text-align:center' width='10%'>No</td><td style='padding-left:15px;text-align:left;color:white' width='60%'>Description</td><td style='text-align:left;color:white;padding-left:15px;' width='5%'>Amount</td>";
        $list.="</tr>";
        $list.="<tr bgcolor='#FAF7F5'>";
        $list.="<td style='text-align:center;color:black;' width='10%' height='55'>1</td><td style='padding-left:15px;text-align:left;color:black' width='60%'>$item_name</td><td style='text-align:left;color:black;padding-left:15px;' width='5%'>$amount</td>";
        $list.="</tr>";
        $list.="<tr>";
        $list.="<td></td><td style='padding:10px;' align='right'>Subtotal</td><td bgcolor='black' style='color:white;padding-left:15px;'>$".$cost['cost']."</td>";
        $list.="</tr>";
        $list.="<tr>";
        $list.="<td></td><td style='padding:10px;' align='right'>Tax</td><td bgcolor='black' style='padding-left:15px;color:white;'>0</td>";
        $list.="</tr>";
        $list.="<tr>";
        $list.="<td></td><td style='padding:10px;' align='right'>Total</td><td bgcolor='black' style='padding-left:15px;color:white;'>$".$cost['cost']."</td>";
        $list.="</tr>";
        $list.="<tr>";
        $list.="<td colspan='3' style='border-bottom:0px solid;padding-top:1px;height:75px;'></td>";
        $list.="</tr>";
        $list.="<tr bgcolor='#e2a500;'>";
        $list.="<td colspan='3' height='35px' style='color:white;fonr-weight:bold;' align='center'>email: ".$invoice_credentials->email."&nbsp;&nbsp;&nbsp; ".$invoice_credentials->site." </td>";
        $list.="</tr>";
        $list.="</table>";




        $list.="</body>";
        $list.="</html>";


        $file_path = $this->invoice_path . "/$invoice_num.html";
        file_put_contents($file_path, $list);
    }

    function get_invoice_num() {
        $id = 1;
        $query = "select id from mdl_invoice order by id desc limit 0,1";
        $num = $this->db->numrows($query);
        if ($num > 0) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $id = $row['id'];
            }
        } // end if $num>0
        $invoice_num = "MDL2_$id";
        return $invoice_num;
    }

}
