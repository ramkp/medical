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

    function authorize_production() {
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
        $this->authorize_production();
        $clientToken = Braintree\ClientToken::generate();
        return $clientToken;
    }

    function make_refund($trans_id, $amount) {
        $this->authorize_production();
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
        $this->authorize_production();
        //$this->authorize_sandbox();
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
                . "psum, "
                . "renew, "
                . "trans_id,"
                . "auth_code,"
                . "promo_code,"
                . "pdate)"
                . "values ($userObj->userid,"
                . "$userObj->courseid,"
                . "'$userObj->amount', "
                . "$userObj->period,"
                . "'$userObj->transid',"
                . "'$userObj->status',"
                . "'$userObj->promo_code',"
                . "'$date')";
        $this->db->query($query);

        if ($userObj->promo_code != '') {
            $this->make_promo_code_used($userObj->promo_code);
        }

        if ($userObj->period > 0) {
            $cert = new Certificates2();
            $cert->renew_certificate($userObj->courseid, $userObj->userid, $userObj->period);
        } // end if 
        else {
            $p = new Payment();
            $p->enroll->add_user_to_course_schedule($userObj->userid, $userObj);
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
        $this->authorize_production();
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

    function send_group_certificate_renewal_message_failure_info($failObj) {
        $msg = "";
        $m = new Mailer();

        $program = $failObj->program;
        $message = $failObj->msg;
        $cardholder = $failObj->cardholder;

        $msg.="<html>";
        $msg.="<body>";

        $msg.="<table align='center'>";

        $msg.="<tr>";
        $msg.="<td style='padding:15px;'>Program</td>";
        $msg.="<td style='padding:15px;'>$program</td>";
        $msg.="</tr>";

        $msg.="<tr>";
        $msg.="<td style='padding:15px;'>Cardholder Name</td>";
        $msg.="<td style='padding:15px;'>$cardholder</td>";
        $msg.="</tr>";

        $msg.="<tr>";
        $msg.="<td style='padding:15px;'>Message</td>";
        $msg.="<td style='padding:15px;'>$message</td>";
        $msg.="</tr>";

        $msg.="</table>";

        $msg.="</body>";
        $msg.="</html>";

        $m->send_group_certificate_renewal_message_failure_info($msg);
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

        if ($userObj->promo_code != '') {
            $this->make_promo_code_used($userObj->promo_code);
        }
    }

    function make_promo_code_used($pomo_code) {
        $query = "update mdl_code set used=1 where code='$pomo_code'";
        $this->db->query($query);
    }

    function renew_group_certificates($trans) {
        $amount = $trans->amount;
        $nonceFromTheClient = $trans->nonce;
        $cardholder = $trans->cardholder;
        $courseid = $trans->courseid;
        $period = $trans->period;
        $users_arr = explode(',', $trans->users);
        $total = count($users_arr);

        $names = explode(" ", $cardholder);
        if (count($names) == 2) {
            $billing_firstname = $names[0];
            $billing_lastname = $names[1];
        } // end if

        if (count($names) == 3) {
            $billing_firstname = $names[0] . " " . $names[1];
            $billing_lastname = $names[2];
        } // end if

        $this->authorize_production();
        $result = Braintree\Transaction::sale([
                    'amount' => $amount,
                    'paymentMethodNonce' => $nonceFromTheClient,
                    'customer' => [
                        'firstName' => $billing_firstname,
                        'lastName' => $billing_lastname,
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
            $transid = $transaction->id;
            $status = $transaction->status;
            $single_renew_amount = round($amount / $total);
            foreach ($users_arr as $userid) {
                $userObj = new stdClass();
                $userObj->userid = $userid;
                $userObj->courseid = $courseid;
                $userObj->amount = $single_renew_amount;
                $userObj->period = $period;
                $userObj->transid = $transid;
                $userObj->status = $status;
                $userObj->promo_code = '';
                $this->add_user_certificate_renewal_payment($userObj);
                $this->renew_user_certificate($userObj);
            } // end foreach
        } // end if $result->success
        else {
            $program = $this->get_course_name_by_id($courseid);
            $msg = $result->message;
            $failObj = new stdClass();
            $failObj->msg = $msg;
            $failObj->program = "$program - Group certificates renewal";
            $failObj->cardholder = $cardholder;
            $this->send_group_certificate_renewal_message_failure_info($failObj);
        } // end else
    }

    function add_user_certificate_renewal_payment($userObj) {
        $date = time();
        $query = "insert into mdl_card_payments2 "
                . "(userid,"
                . "courseid,"
                . "psum, "
                . "renew, "
                . "trans_id,"
                . "auth_code,"
                . "promo_code,"
                . "pdate)"
                . "values ($userObj->userid,"
                . "$userObj->courseid,"
                . "'$userObj->amount', "
                . "$userObj->period,"
                . "'$userObj->transid',"
                . "'$userObj->status',"
                . "'$userObj->promo_code',"
                . "'$date')";
        $this->db->query($query);
    }

    function renew_user_certificate($userObj) {
        $cert = new Certificates2();
        $cert->renew_certificate($userObj->courseid, $userObj->userid, $userObj->period);
    }

    /**     * ************* Code related to group registration  ********* */
    function make_group_registration_payment($transObj) {
        $nonceFromTheClient = $transObj->nonce;
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

        $billing_email = $transObj->billing_email;
        $billing_phone = $transObj->billing_phone;

        $regdata = $transObj->data;
        $decoded_data = json_decode(base64_decode($regdata));

        $courseObj = json_decode($decoded_data->course);
        $total = $courseObj->total;
        $amount = $courseObj->amount;

        $groupObj = json_decode($decoded_data->group);
        $users = json_decode($decoded_data->users); // array of objects

        $single_user_amount = round($amount / $total);
        $program = $this->get_course_name_by_id($courseObj->courseid);

        $this->authorize_production();
        $result = Braintree\Transaction::sale([
                    'amount' => $amount,
                    'paymentMethodNonce' => $nonceFromTheClient,
                    'customer' => [
                        'firstName' => $billing_fisrtname,
                        'lastName' => $billing_lastname,
                        'phone' => $billing_phone,
                        'email' => $billing_email
                    ],
                    'creditCard' => [
                        'cardholderName' => $cardholder
                    ],
                    'options' => [
                        'submitForSettlement' => True
                    ]
        ]);

        $transaction = $result->transaction;

        $buyer = new stdClass();
        $buyer->payment_amount = $amount;
        $buyer->groupname = $groupObj->name;
        $buyer->total = $courseObj->total;
        $buyer->courseid = $courseObj->courseid;
        $buyer->slotid = $courseObj->slotid;
        $buyer->state = $groupObj->state;
        $buyer->firstname = $billing_fisrtname;
        $buyer->lastname = $billing_lastname;
        $buyer->email = $billing_email;
        $buyer->phone = $billing_phone;
        $buyer->ptype = 'card';

        if ($result->success) {

            $m = new Mailer();
            $m->send_new_group_payment_confirmation_message($buyer, $users);

            $p = new Payment();
            $groupid = $this->add_new_group($courseObj->courseid, $groupObj->name);
            $transid = $transaction->id;
            $status = $transaction->status;
            foreach ($users as $user) {
                // We need to create object compatible with signup workflow
                $original_email = $user->email;
                $username_exists = $this->is_username_exists($original_email);
                if ($username_exists > 0) {
                    $rnd_string = $this->get_random_string(4);
                    $new_email = $rnd_string . "_" . $user->email;
                    $user->email = $new_email;
                } // end if $username_exists>0

                $userObj = new stdClass();
                $userObj->firstname = $user->fname;
                $userObj->first_name = $user->fname;

                $userObj->lastname = $user->lname;
                $userObj->last_name = $user->lname;

                $userObj->email = $user->email;
                $userObj->phone = $user->phone;

                $pwd = $this->get_random_string(12);
                $userObj->pwd = $pwd;
                $userObj->courseid = $courseObj->courseid;

                $userObj->addr = $groupObj->addr;
                $userObj->inst = $groupObj->name;

                $userObj->zip = $groupObj->zip;
                $userObj->city = $groupObj->city;

                $userObj->state = $groupObj->state;
                $userObj->country = 'US';

                $userObj->slotid = $courseObj->slotid;
                $userObj->promo_code = '';

                $p->enroll->single_signup($userObj);
                $p->confirm_user($userObj->email);
                $userid = $p->get_user_id_by_email($userObj->email);
                $p->enroll->add_user_to_course_schedule($userid, $userObj);
                $this->add_user_to_group($groupid, $userid);
                $user_detailes = $p->get_user_detailes($userid);

                $userObj->userid = $userid;
                $userObj->amount = $single_user_amount;
                $userObj->sum = $single_user_amount;
                $userObj->pwd = $user_detailes->purepwd;
                $userObj->payment_amount = $single_user_amount;
                $userObj->card_holder = $cardholder;
                $userObj->billing_name = $cardholder;
                $userObj->signup_first = $userObj->first_name;
                $userObj->signup_last = $userObj->last_name;
                $userObj->transid = $transid;
                $userObj->status = $status;
                $this->add_success_registration_payment($userObj);
            } // end foreach
            $list = "Payment is successful. Thank you! Confirmation email is sent to $billing_email";
            return $list;
        } // end if $result->success
        else {
            $msg = $result->message;
            $failObj = new stdClass();
            $failObj->status = $transaction->status;
            $failObj->code = $transaction->processorResponseCode;
            $failObj->program = $program;
            $failObj->msg = $msg;
            $failObj->info = $transaction->additionalProcessorResponse;
            $failObj->amount = $amount;
            $failObj->firstname = $buyer->firstname;
            $failObj->lastname = $buyer->lastname;
            $failObj->email = $buyer->email;
            $failObj->phone = $buyer->phone;
            $this->send_any_pay_failed_transaction_info($failObj);
            $list = "Credit card declined. Please contact your bank for details";
            return $list;
        } // end else
    }

    function add_group_paypal_payment($t) {

        $data = json_decode($t);
        $billed_person = $data->buyer;
        $regdata = $data->regdata;
        $decoded_data = json_decode(base64_decode($regdata));

        $courseObj = json_decode($decoded_data->course);
        $total = $courseObj->total;
        $amount = $courseObj->amount;

        $groupObj = json_decode($decoded_data->group);
        $users = json_decode($decoded_data->users); // array of objects

        $single_user_amount = round($amount / $total);
        $transactionid = $_SESSION['group_payment_transactionid'];

        $m = new Mailer();
        $buyer = new stdClass();
        $buyer->payment_amount = $amount;
        $buyer->groupname = $groupObj->name;
        $buyer->total = $courseObj->total;
        $buyer->courseid = $courseObj->courseid;
        $buyer->slotid = $courseObj->slotid;
        $buyer->state = $groupObj->state;
        $buyer->firstname = $billed_person->firstname;
        $buyer->lastname = $billed_person->lastname;
        $buyer->email = $billed_person->email;
        $buyer->phone = $billed_person->phone;
        $buyer->ptype = 'paypal';

        $m->send_new_group_payment_confirmation_message($buyer, $users);

        $p = new Payment();
        $groupid = $this->add_new_group($courseObj->courseid, $groupObj->name);
        $transid = $transactionid;
        foreach ($users as $user) {
            // We need to create object compatible with signup workflow
            $original_email = $user->email;
            $username_exists = $this->is_username_exists($original_email);
            if ($username_exists > 0) {
                $rnd_string = $this->get_random_string(4);
                $new_email = $rnd_string . "_" . $user->email;
                $user->email = $new_email;
            } // end if $username_exists>0

            $userObj = new stdClass();
            $userObj->firstname = $user->fname;
            $userObj->first_name = $user->fname;

            $userObj->lastname = $user->lname;
            $userObj->last_name = $user->lname;

            $userObj->email = $user->email;
            $userObj->phone = $user->phone;

            $pwd = $this->get_random_string(12);
            $userObj->pwd = $pwd;
            $userObj->courseid = $courseObj->courseid;

            $userObj->addr = $groupObj->addr;
            $userObj->inst = $groupObj->name;

            $userObj->zip = $groupObj->zip;
            $userObj->city = $groupObj->city;

            $userObj->state = $groupObj->state;
            $userObj->country = 'US';

            $userObj->slotid = $courseObj->slotid;
            $userObj->promo_code = '';

            $p->enroll->single_signup($userObj);
            $p->confirm_user($userObj->email);
            $userid = $p->get_user_id_by_email($userObj->email);
            $p->enroll->add_user_to_course_schedule($userid, $userObj);
            $this->add_user_to_group($groupid, $userid);
            $user_detailes = $p->get_user_detailes($userid);

            $userObj->userid = $userid;
            $userObj->amount = $single_user_amount;
            $userObj->sum = $single_user_amount;
            $userObj->pwd = $user_detailes->purepwd;
            $userObj->transid = $transid;
            $this->add_paypal_payment($userObj);
        }
    }

    function add_paypal_payment($userObj) {
        $now = time();
        $query = "insert into mdl_paypal_payments "
                . "(courseid,"
                . "userid,"
                . "psum,"
                . "trans_id,"
                . "pdate) "
                . "values ($userObj->courseid, "
                . "$userObj->userid,"
                . "'$userObj->amount',"
                . "'$userObj->transid',"
                . "'$now')";
        $this->db->query($query);
    }

    function get_state_name_by_id($id) {
        $query = "select * from mdl_states where id=$id";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $state = $row['state'];
        }
        return $state;
    }

    function update_user_data($userid, $email, $pwd) {
        $query = "update mdl_user set email='$email' purepwd='$pwd' where id=$userid";
        $this->db->query($query);
    }

    function add_user_to_group($groupid, $userid) {
        $now = time();
        $query = "insert into mdl_groups_members "
                . "(groupid, userid, timeadded) "
                . "values ($groupid,$userid,'$now')";
        $this->db->query($query);
    }

    function is_username_exists($email) {
        $query = "select * from mdl_user where username='$email'";
        $num = $this->db->numrows($query);
        return $num;
    }

    function get_random_string($size) {
        $alpha_key = '';
        $keys = range('A', 'Z');
        for ($i = 0; $i < 2; $i++) {
            $alpha_key .= $keys[array_rand($keys)];
        }
        $length = $size - 2;
        $key = '';
        $keys = range(0, 9);
        for ($i = 0; $i < $length; $i++) {
            $key .= $keys[array_rand($keys)];
        }
        return $alpha_key . $key;
    }

    function add_new_group($courseid, $name) {
        $query = "insert into mdl_groups (courseid, name) "
                . "values($courseid, '$name')";
        $this->db->query($query);
        $query = "select * from mdl_groups where name='$name'";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $groupid = $row['id'];
        }
        return $groupid;
    }

    function get_renew_receipt($transObj) {
        $list = "";
        $list2 = "";
        $user = json_decode($transObj->user);
        $amount = $transObj->amount;
        $email = $user->email;

        $list.= "<!DOCTYPE HTML><html><head><title>Certificate Renew Confirmation</title>";
        $list.="</head>";
        $list.="<body><br/><br/>";
        $list.="<div class='datagrid'>            
            <table style='table-layout: fixed;' width='360' align='center'>
            <thead>";

        $list.="<tr>";
        $list.="<th colspan='2' align='center'><img src='https://medical2.com/assets/logo/receipt_agency.png' width='360' height='120'></th>";
        $list.="</tr>";

        $list.="<tr>";
        $list.="<th colspan='2' align='center' style='font-weight:bold;'>Renewal received</th>";
        $list.="</tr>";

        $list.="</thead>
            <tbody>
        
            <tr style='background-color:#F5F5F5;'>
            <td align='left'>First name</td><td align='left'>$user->firstname</td>
            </tr>

            <tr>
            <td align='left'>Last name</td><td align='left'>$user->lastname</td>
            </tr>

            <tr style='background-color:#F5F5F5;'>
            <td align='left'>Email</td><td align='left'>$user->email</td>
            </tr>

            <tr>
            <td align='left'>Phone</td><td align='left'>$user->phone1</td>
            </tr>

            <tr style='background-color:#F5F5F5;'>
            <td align='left'>Applied Program</td><td align='left'>Certification renewal</td>
            </tr> 

            <tr>
            <td align='left'>Amount paid</td><td align='left'>$$amount</td>
            </tr> 

            <tr>
            <td colspan='2' align='left'>&nbsp;</td>
            </tr> 

            <tr>
            <td colspan='2' align='left'>This certification have been renewed</td>
            </tr> 

            </table>";


        $list2.= "<!DOCTYPE HTML><html><head><title>Certificate Renew Confirmation</title>";
        $list2.="</head>";
        $list2.="<body><br/><br/><br/><br/>";
        $list2.="<div class='datagrid'>            
            <table style='table-layout: fixed;' width='360' align='center'>
            <thead>";

        $list2.="<tr>";
        $list2.="<th colspan='2' align='center' style='font-weight:bold;'>Renewal received</th>";
        $list2.="</tr>";

        $list2.="</thead>
            <tbody>
        
            <tr style='background-color:#F5F5F5;'>
            <td align='left'>First name</td><td align='left'>$user->firstname</td>
            </tr>

            <tr>
            <td align='left'>Last name</td><td align='left'>$user->lastname</td>
            </tr>

            <tr style='background-color:#F5F5F5;'>
            <td align='left'>Email</td><td align='left'>$user->email</td>
            </tr>

            <tr>
            <td align='left'>Phone</td><td align='left'>$user->phone1</td>
            </tr>

            <tr style='background-color:#F5F5F5;'>
            <td align='left'>Applied Program</td><td align='left'>Certification renewal</td>
            </tr> 

            <tr>
            <td align='left'>Amount paid</td><td align='left'>$$amount</td>
            </tr> 

            <tr>
            <td colspan='2' align='left'>&nbsp;</td>
            </tr> 

            <tr>
            <td colspan='2' align='left'>This certification have been renewed</td>
            </tr> 

            </table>";

        $m = new Mailer();
        $m->create_renewal_pdf_report($list2, $user->email);

        $list.="<a href='https://medical2.com/lms/custom/invoices/renewal/$email.pdf' target='_blank'><span style='font-size:bold;'>Print</span></a>";
        $list.="</body>";
        $list.="</html>";

        return $list;
    }

}
