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
        Braintree\Configuration::environment('production');
        Braintree\Configuration::merchantId('r4fpcmgpvk9bfgwq');
        Braintree\Configuration::publicKey('5pkv399brrnwwsr7');
        Braintree\Configuration::privateKey('e89b32d1dbbe736cba6efb6d4d9f24a5');
    }

    function get_sandbox_token() {
        $this->authorize_sandbox();
        $clientToken = Braintree\ClientToken::generate();
        return $clientToken;
    }

    function get_production_token() {
        $this->autorize_production();
        $clientToken = Braintree\ClientToken::generate();
        return $clientToken;
    }

    function make_refund($trans_id, $amount) {
        $this->autorize_production();
        $result = Braintree\Transaction::refund($trans_id, $amount);
        if ($result->success) {
            return true;
        } // end if
        else {
            return false;
        } // end else
    }

    function get_course_name_by_id($courseid) {
        $query = "select * from mdl_course where id=$courseid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $name = $row['fullname'];
        }
        return $name;
    }

    function create_any_pay_transaction($transObj) {
        $amount = $transObj->amount;
        $period = $transObj->period;
        $courseid = $transObj->courseid;
        $program = $this->get_course_name_by_id($courseid);
        $slotid = $transObj->slotid;
        $nonceFromTheClient = $transObj->nonce;
        $userObj = json_decode($transObj->user); // user from mdl_user table
        $userObj->phone = $userObj->phone1;

        $cardholder = $transObj->cardholder;
        $names = explode(" ", $cardholder);
        if (count($names) == 2) {
            $billing_fisrtname = $names[0];
            $billing_lastname = $names[1];
        } // end if

        if (count($names) == 3) {
            $billing_fisrtname = $names[0] . " " . $names[1];
            $billing_lastname = $names[2];
        } // end if
        $this->autorize_production();
        $result = Braintree\Transaction::sale([
                    'amount' => $amount,
                    'paymentMethodNonce' => $nonceFromTheClient,
                    'customer' => [
                        'firstName' => $userObj->firstname,
                        'lastName' => $userObj->lastname,
                        'phone' => $userObj->phone,
                        'email' => $userObj->email
                    ],
                    'creditCard' => [
                        'cardholderName' => $cardholder
                    ],
                    'options' => [
                        'submitForSettlement' => True
                    ]
        ]);

        $transaction = $result->transaction;

        if ($result->success) {
            $m = new Mailer();
            $transid = $transaction->id;
            $status = $transaction->status;

            $userObj->userid = $userObj->id;
            $userObj->courseid = $courseid;
            $userObj->slotid = $slotid;
            $userObj->sum = $amount;
            $userObj->amount = $amount;
            $userObj->payment_amount = $amount;
            $userObj->pwd = $userObj->purepwd;
            $userObj->card_holder = $cardholder;
            $userObj->billing_name = $cardholder;
            $userObj->signup_first = $userObj->firstname;
            $userObj->signup_last = $userObj->lastname;
            $userObj->transid = $transid;
            $userObj->status = $status;
            $userObj->renew = $period;
            $userObj->period = $period;
            $userObj->promo_code = '';
            $userObj->bill_email = $userObj->email;
            $this->add_any_pay_payment($userObj);
            $m->send_payment_confirmation_message($userObj);
            return true;
        } // end if 
        else {
            $msg = $result->message;
            $failObj = new stdClass();
            $failObj->status = $transaction->status;
            $failObj->code = $transaction->processorResponseCode;
            $failObj->program = $program;
            $failObj->msg = $msg;
            $failObj->info = $transaction->additionalProcessorResponse;
            $failObj->amount = $amount;
            $failObj->firstname = $userObj->firstname;
            $failObj->lastname = $userObj->lastname;
            $failObj->email = $userObj->email;
            $failObj->phone = $userObj->phone;
            $this->send_any_pay_failed_transaction_info($failObj);
            return false;
        }
    }

    function add_any_pay_payment($userObj) {
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
                . "'$userObj->amount', $userObj->period,"
                . "'$userObj->transid',"
                . "'$userObj->status',"
                . "'$userObj->promo_code',"
                . "'$date')";
        $this->db->query($query);
        if ($userObj->period > 0) {
            $cert = new Certificates2();
            $cert->renew_certificate($userObj->courseid, $userObj->userid, $userObj->period);
        } // end if 
        else {
            if ($userObj->slotid > 0) {
                $p = new Payment();
                $p->enroll->add_user_to_course_schedule($userObj->userid, $userObj);
            } // end if $userObj->slotid>0
        } // end else
    }

    function create_transaction($transObj) {
        $amount = $transObj->amount;
        $nonceFromTheClient = $transObj->nonce;
        $userObj = json_decode($transObj->user);

        $cardholder = $transObj->cardholder;
        $names = explode(" ", $cardholder);
        if (count($names) == 2) {
            $billing_fisrtname = $names[0];
            $billing_lastname = $names[1];
        } // end if

        if (count($names) == 3) {
            $billing_fisrtname = $names[0] . " " . $names[1];
            $billing_lastname = $names[2];
        } // end if
        $this->autorize_production();
        $result = Braintree\Transaction::sale([
                    'amount' => $amount,
                    'paymentMethodNonce' => $nonceFromTheClient,
                    'customer' => [
                        'firstName' => $userObj->first_name,
                        'lastName' => $userObj->last_name,
                        'phone' => $userObj->phone,
                        'email' => $userObj->email
                    ],
                    'creditCard' => [
                        'cardholderName' => $cardholder
                    ],
                    'options' => [
                        'submitForSettlement' => True
                    ]
        ]);

        $transaction = $result->transaction;

        if ($result->success) {
            $m = new Mailer();
            $p = new Payment();
            $p->enroll->single_signup($userObj);
            $p->confirm_user($userObj->email);
            $userid = $p->get_user_id_by_email($userObj->email);
            $p->enroll->add_user_to_course_schedule($userid, $userObj);
            $transid = $transaction->id;
            $status = $transaction->status;
            $user_detailes = $p->get_user_detailes($userid);

            $userObj->userid = $userid;
            $userObj->sum = $userObj->amount;
            $userObj->pwd = $user_detailes->purepwd;
            $userObj->payment_amount = $userObj->amount;
            $userObj->card_holder = $cardholder;
            $userObj->billing_name = $cardholder;
            $userObj->signup_first = $userObj->first_name;
            $userObj->signup_last = $userObj->last_name;
            $userObj->transid = $transid;
            $userObj->status = $status;
            $this->add_success_registration_payment($userObj);
            $m->send_payment_confirmation_message($userObj);
            return true;
        } // end if 
        else {
            $msg = $result->message;
            $failObj = new stdClass();
            $failObj->status = $transaction->status;
            $failObj->code = $transaction->processorResponseCode;
            $failObj->msg = $msg;
            $failObj->info = $transaction->additionalProcessorResponse;
            $failObj->user = $userObj;
            $this->send_failed_transaction_info($failObj);
            return false;
        }
    }

    function send_any_pay_failed_transaction_info($failObj) {
        $m = new Mailer();

        $first = $failObj->firstname;
        $last = $failObj->lastname;
        $email = $failObj->email;
        $phone = $failObj->phone;
        $program = $failObj->program;
        $amount = $failObj->amount;
        $message = $failObj->msg;

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
        $msg.="<td style='padding:15px;'>Transaction message</td>";
        $msg.="<td style='padding:15px;'>$message</td>";
        $msg.="</tr>";

        $msg.="</table>";
        $msg.="</body>";
        $msg.="</html>";

        $m->send_braintree_failed_transaction_info($msg);
    }

    function send_failed_transaction_info($failObj) {
        $m = new Mailer();

        $first = $failObj->user->first_name;
        $last = $failObj->user->last_name;
        $email = $failObj->user->email;
        $phone = $failObj->user->phone;
        $program = $failObj->user->program;
        $amount = $failObj->user->amount;
        $message = $failObj->msg;

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
        $msg.="<td style='padding:15px;'>Transaction message</td>";
        $msg.="<td style='padding:15px;'>$message</td>";
        $msg.="</tr>";

        $msg.="</table>";
        $msg.="</body>";
        $msg.="</html>";

        $m->send_braintree_failed_transaction_info($msg);
    }

    function add_success_registration_payment($userObj) {
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
        $this->db->query($query);
    }

}
