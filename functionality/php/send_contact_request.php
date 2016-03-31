<?php

require_once 'classes/Contact.php';
$contact=new Contact();
$firstname=$_POST['firstname'];
$lastname=$_POST['lastname'];
$email=$_POST['email'];
$phone=$_POST['phone'];
$message=$_POST['message'];
$list=$contact->post_contact_form($firstname, $lastname, $email, $phone, $message);
echo $list;