<?php

require('../config.php');
require_once($CFG->dirroot . '/user/editlib.php');
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/Enroll.php';

$authplugin = get_auth_plugin($CFG->registerauth);
$enroll=new Enroll();
$pwd=$enroll->get_password();

if (!$authplugin->can_signup()) {
    print_error('notlocalisederrormessage', 'error', '', 'Sorry, you may not use this page.');
}

if ($_POST) {

    $user_data = $_POST['user'];
    $posted_user = json_decode(base64_decode($user_data));

    $user = new stdClass();

    $user->confirmed = 0;
    $user->username = $posted_user->email;
    $user->password = $pwd;
    $user->purepassword = $pwd;
    $CFG->purepassword = $pwd;
    $user->email = $posted_user->email;
    $user->email1 = $posted_user->email;
    $user->email2 = $posted_user->email;
    $user->firstname = $posted_user->first_name;
    $user->lastname = $posted_user->last_name;
    $user->courseid = $posted_user->courseid;    
    $user->address = $posted_user->addr;
    $user->inst=$posted_user->inst;
    $user->zip=$posted_user->zip;
    $user->city=$posted_user->city;
    $user->state=$posted_user->state;
    $user->country=$posted_user->country;
    $user->lang = current_language();    
    $user->firstaccess = 0;    
    $user->timecreated = time();
    $user->mnethostid = $CFG->mnet_localhost_id;
    $user->secret = random_string(15);
    $user->auth = $CFG->registerauth;

    echo "<br><pre>";
    print_r($user);
    echo "<br><pre>";

    die('Stopped');

    // Initialize alternate name fields to empty strings.
    $namefields = array_diff(get_all_user_name_fields(), useredit_get_required_name_fields());
    foreach ($namefields as $namefield) {
        $user->$namefield = '';
    }

    // Perform signup process
    $authplugin->user_signup($user, false);
} // end if $_POST

