<?php

require_once './classes/Payment.php';
if ($_POST) {
    $payment = new Payment();
    $user = new stdClass();
    $user->courseid = $_POST['courseid'];
    $user->first_name = $_POST['firstname'];
    $user->last_name = $_POST['lastname'];
    $user->email = $_POST['email'];
    $list = $payment->send_group_invoice($user);
    echo $list;
} // end if $_POST
