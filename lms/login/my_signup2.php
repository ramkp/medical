<?php

header("Access-Control-Allow-Origin: *");
require('../config.php');
require_once($CFG->dirroot . '/user/editlib.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/crmMailer.php');

$authplugin = get_auth_plugin($CFG->registerauth);

if (!$authplugin->can_signup()) {
    print_error('notlocalisederrormessage', 'error', '', 'Sorry, you may not use this page.');
}

if ($_REQUEST) {

    $user_data = $_REQUEST['user'];
    $posted_user = json_decode(base64_decode($user_data));

    $user = new stdClass();
    $user->confirmed = 1;
    $user->username = strtolower($posted_user->email);
    $user->password = $posted_user->pwd;
    $user->email = strtolower($posted_user->email);
    $user->email1 = strtolower($posted_user->email);
    $user->email2 = strtolower($posted_user->email);
    $user->phone1 = $posted_user->phone1;
    $user->firstname = $posted_user->first_name;
    $user->lastname = $posted_user->last_name;
    $user->address = $posted_user->addr;
    $user->zip = $posted_user->zip;
    $user->city = $posted_user->city;
    $user->state = $posted_user->state;
    $user->country = $posted_user->country;
    $user->lang = current_language();
    $user->firstaccess = 0;
    $user->timecreated = time();
    $user->mnethostid = $CFG->mnet_localhost_id;
    $user->secret = random_string(15);
    $user->auth = $CFG->registerauth;

    // Initialize alternate name fields to empty strings.
    $namefields = array_diff(get_all_user_name_fields(), useredit_get_required_name_fields());
    foreach ($namefields as $namefield) {
        $user->$namefield = '';
    }

    // Perform signup process
    $authplugin->user_signup($user, false);
    $m = new crmMailer();
    $m->send_moodle_account_info($posted_user);
} // end if $_POST
else {
    echo "There is no post ...";
}

