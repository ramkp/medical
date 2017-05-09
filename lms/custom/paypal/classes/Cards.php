<?php

session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/paypal/vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/Payment.php';
require_once ('/home/cnausa/public_html/lms/class.pdo.database.php');

class Cards {

    public $db;

    function __construct() {
        $db = new pdo_db();
        $this->db = $db;
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

    function create_transaction($transObj) {
        $amount = $transObj->amount;
        $nonceFromTheClient = $transObj->nonce;
        $userObj = json_decode($transObj->user);
        $this->authorize_sandbox();
        $result = Braintree\Transaction::sale([
                    'amount' => $amount,
                    'paymentMethodNonce' => $nonceFromTheClient,
                    'customer' => [
                        'firstName' => $userObj->first_name,
                        'lastName' => $userObj->last_name,
                        'phone' => $userObj->phone,
                        'email' => $userObj->email
                    ],
                    'options' => [
                        'submitForSettlement' => True
                    ]
        ]);

        /*
         * 
          echo "------------<pre>";
          print_r($transaction);
          echo "</pre>-----------------<br>";
          echo "Transaction  id: " . $transid . "<br>";
          echo "Status: " . $status . "<br>";
          die();
         * 
         */
        $transaction = $result->transaction;
        if ($result->success) {
            $p = new Payment();
            $status = $p->enroll->single_signup($userObj);
            if ($status === TRUE) {
                $transid = $transaction->id;
                $status = $transaction->status;
                $m = new Mailer();
                $p->confirm_user($userObj->email);
                $userid = $p->get_user_id_by_email($userObj->email);
                $user_detailes = $p->get_user_detailes($userid);
                $userObj->userid = $userid;
                $userObj->sum = $userObj->amount;
                $userObj->pwd = $user_detailes->purepwd;
                $userObj->payment_amount = $userObj->amount;
                $userObj->card_holder = $userObj->first_time . " " . $userObj->last_name;
                $userObj->signup_first = $userObj->first_name;
                $userObj->signup_last = $userObj->last_name;
                $userObj->transid = $transid;
                $userObj->status = $status;
                $this->add_success_registration_payment($result, $userObj);
                $m->send_payment_confirmation_message($userObj);
                return true;
            } // end if $status 
            else {
                return FALSE;
            } // end else
        } // end if 
        else {
            $failObj = new stdClass();
            $failObj->status = $transaction->status;
            $failObj->code = $transaction->processorResponseCode;
            $failObj->msg = $transaction->processorResponseText;
            $failObj->info = $transaction->additionalProcessorResponse;
            $failObj->user = $userObj;
            $this->send_failed_transaction_info($failObj);
            return false;
        }
    }

    function send_failed_transaction_info($failObj) {
        $m = new Mailer();

        echo "<br>Failure object:------------<pre>";
        print_r($failObj);
        echo "</pre>-----------------<br>";

        $first = $failObj->user->first_name;
        $last = $failObj->user->last_name;
        $email = $failObj->user->email;
        $phone = $failObj->user->phone;
        $program = $failObj->user->program;
        $amount = $failObj->user->amount;
        $status = $failObj->status;
        $code = $failObj->code;
        $msg = $failObj->msg;
        $info = $failObj->info;

        $msg.="<html>";
        $msg.="<body>";
        $msg.="<table align='center'>";

        $msg.="<tr>";
        $msg.="<td style='padding:15px;'>User Firstname</td>";
        $msg.="<td style='padding:15px;'>$first</td>";
        $msg.="</tr>";

        $msg.="<tr>";
        $msg.="<td style='padding:15px;'>User Lastname</td>";
        $msg.="<td style='padding:15px;'>$last</td>";
        $msg.="</tr>";

        $msg.="<tr>";
        $msg.="<td style='padding:15px;'>User Email</td>";
        $msg.="<td style='padding:15px;'>$email</td>";
        $msg.="</tr>";

        $msg.="<tr>";
        $msg.="<td style='padding:15px;'>User Phone</td>";
        $msg.="<td style='padding:15px;'>$phone</td>";
        $msg.="</tr>";

        $msg.="<tr>";
        $msg.="<td style='padding:15px;'>Program applied</td>";
        $msg.="<td style='padding:15px;'>$program</td>";
        $msg.="</tr>";

        $msg.="<tr>";
        $msg.="<td style='padding:15px;'>Program cost</td>";
        $msg.="<td style='padding:15px;'>$$amount</td>";
        $msg.="</tr>";

        $msg.="<tr>";
        $msg.="<td style='padding:15px;'>Transaction status</td>";
        $msg.="<td style='padding:15px;'>$status</td>";
        $msg.="</tr>";

        $msg.="<tr>";
        $msg.="<td>Transaction code</td>";
        $msg.="<td>$code</td>";
        $msg.="</tr>";

        $msg.="<tr>";
        $msg.="<td style='padding:15px;'>Transaction message</td>";
        $msg.="<td style='padding:15px;'>$msg</td>";
        $msg.="</tr>";

        $msg.="<tr>";
        $msg.="<td style='padding:15px;'>Transaction bank info</td>";
        $msg.="<td style='padding:15px;'>$info</td>";
        $msg.="</tr>";

        $msg.="</table>";
        $msg.="</body>";
        $msg.="</html>";

        $m->send_braintree_failed_transaction_info($msg);
    }

    function add_success_registration_payment($result, $userObj) {
        $date = time();
        $query = "insert into mdl_card_payments2 "
                . "(userid,"
                . "courseid,"
                . "psum, renew, "
                . "trans_id,"
                . "auth_code,"
                . "promo_code,"
                . "pdate)"
                . "values ($userObj->userid,"
                . "$userObj->courseid,"
                . "'$userObj->amount', 0,"
                . "'$userObj->transid',"
                . "'$userObj->status',"
                . "'$userObj->promo_code',"
                . "'$date')";
        //echo "Query: " . $query . "<br>";
        $this->db->query($query);
    }

}
