<?php

session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php';
require_once '../vendor/autoload.php';

/**
 * Description of Paypal
 *
 * @author moyo
 */
class Cards extends Util {

    function __construct() {
        parent::__construct();
    }

    function authorize_sandbox() {
        // ******* Sandbox *******
        Braintree\Configuration::environment('sandbox');
        Braintree\Configuration::merchantId('yrfkpn2t879bqqwd');
        Braintree\Configuration::publicKey('ngcnxdyfkc8ck7fb');
        Braintree\Configuration::privateKey('be5c1b5fe42f8297abaea82d1e3c152e');
    }

    function autorize_production() {
        // ******* Production *******
        Braintree\Configuration::environment('BT_ENVIRONMENT');
        Braintree\Configuration::merchantId('BT_MERCHANT_ID');
        Braintree\Configuration::publicKey('BT_PUBLIC_KEY');
        Braintree\Configuration::privateKey('BT_PRIVATE_KEY');
    }

    function get_sandbox_token() {
        $this->authorize_sandbox();
        $clientToken = Braintree\ClientToken::generate();
        return $clientToken;
    }

}
