<?php
/**
 * Created by PhpStorm.
 * User: moyo
 * Date: 9/22/17
 * Time: 19:12
 */

require_once 'classes/Paypal.php';
$p = new Paypal();
$transid = $_POST['transid'];
$list = $p->get_refund_modal_dialog($transid);
echo $list;


