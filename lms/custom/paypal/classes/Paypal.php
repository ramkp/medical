<?php

session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php';
require_once '../vendor/autoload.php';

/**
 * Description of Paypal
 *
 * @author moyo
 */
class Paypal extends Util {

    function __construct() {
        parent::__construct();
    }

    function authorize_sandbox() {
        // ******* Sandbox *******
        Braintree\Configuration::environment(getenv('sandbox'));
        Braintree\Configuration::merchantId(getenv('yrfkpn2t879bqqwd'));
        Braintree\Configuration::publicKey(getenv('ngcnxdyfkc8ck7fb'));
        Braintree\Configuration::privateKey(getenv('be5c1b5fe42f8297abaea82d1e3c152e'));
    }

    function autorize_production() {
        // ******* Production *******
        Braintree\Configuration::environment(getenv('BT_ENVIRONMENT'));
        Braintree\Configuration::merchantId(getenv('BT_MERCHANT_ID'));
        Braintree\Configuration::publicKey(getenv('BT_PUBLIC_KEY'));
        Braintree\Configuration::privateKey(getenv('BT_PRIVATE_KEY'));
    }

}
