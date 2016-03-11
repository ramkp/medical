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
        $invoice_path=$this->create_user_invoice($user);
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
        $invoice_credentials = $this->get_invoice_credentials();
        $invoice_num = $this->get_invoice_num();

        $list = "";
        $list.="<html>";
        $list.="<body>";
        $list.= "<p></p>";
        $list.="<table border='1'>";
        $list.="<tr style=''>";
        $list.="<td colspan='2' width='80%' style=''><img src='http://cnausa.com/assets/logo/logo_site.jpg' width='300' height=90></td><td width='30%' style='padding-left:10px;padding-right:10px;border-left:1px solid;'>Phone: $invoice_credentials->phone<br/>Fax: $invoice_credentials->fax</td>";
        $list.="</tr>";
        $list.="<tr style=''>";
        $list.="<td colspan='3' style='border-bottom:1px solid;padding-top:1px;height:10px;'></td>";
        $list.="</tr>";         
        $list.="<tr>";
        $list.="<td style='padding-top:6px;' colspan='2'>No: $invoice_num</td><td width='30%' style='padding-left:10px;'>Date: ".date('Y/m/d', time())."</td>";
        $list.="</tr>"; 
        $list.="<tr style=''>";
        $list.="<td colspan='2' style='padding-top:1px;height:85px;'></td>";
        $list.="</tr>"; 
        $list.="<tr style=''>";
        $list.="<td  style='' colspan='3'> <hr style='height:10px;height:10px;color:black;background-color:black;width:35px;'/> &nbsp;INVOICE TO &nbsp; <hr style='height:10px;height:10px;color:black;background-color:black;'/></td>";
        $list.="</tr>"; 
        $list.="</table>";
        $list.="<html>";
        $list.="<body>";
        
        $file_path=$this->invoice_path."/$invoice_num.html";
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
