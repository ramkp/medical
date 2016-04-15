<?php

/**
 * Description of Import
 *
 * @author sirromas
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/config.php';

require_once($CFG->dirroot . '/user/editlib.php');

$authplugin = get_auth_plugin($CFG->registerauth);

if (!$authplugin->can_signup()) {
    print_error('notlocalisederrormessage', 'error', '', 'Sorry, you may not use this page.');
}

class Import extends Util {

    function validate_email($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    function import_users($filepath) {
        $handle = @fopen($filepath, "r");
        if ($handle) {
            while (($buffer = fgets($handle, 4096)) !== false) {
                echo $buffer;
            }
            if (!feof($handle)) {
                echo "Error: unexpected fgets() fail\n";
            }
            fclose($handle);
        } // end if $handle
    }

    function check_file_data($buffer) {
        $data = split('', $buffer);
    }

}
