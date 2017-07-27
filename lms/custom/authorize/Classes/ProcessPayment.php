<?php

ini_set('display_errors', '1');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/lms/custom/authorize/Api/vendor/autoload.php');
require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/Mailer.php';
require_once ('/home/cnausa/public_html/lms/class.pdo.database.php');

use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

class ProcessPayment {

    private $AUTHORIZENET_LOG_FILE;
    public $SANDBOX_LOGIN_ID = '6cUTfQ5238'; // sandbox data
    public $SANDBOX_TRANSACTION_KEY = '5bN8q5WT3qa257p9'; // sandbox data
    private $LOGIN_ID = '83uKk2VcBBsC'; // production data
    private $TRANSACTION_KEY = '23P447taH34H26h5'; // production data
    public $period = 28; // 28 days of installment 
    public $log_file_path;
    public $transaction_log_path;
    public $db;

    function __construct() {
        $this->AUTHORIZENET_LOG_FILE = 'phplog';
        $this->log_file_path = $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/authorize/failed_transactions.log';
        $this->transaction_log_path = $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/authorize/transactions.log';
        $this->db = new pdo_db();
    }

    function get_failed_transaction_report($respone_object, $order_object) {
        $list = "";
        $response_arr = (array) $respone_object;
        $order_arr = (array) $order_object;

        $list.="<html>";
        $list.="<body>";
        $list.="<br><br>";

        $list.="<table align='center'>";
        foreach ($order_arr as $key => $value) {
            if ($key != 'cds_pay_type' && $key != 'group' && $key != 'cds_cc_number' && $key != 'cds_cc_exp_month' && $key != 'cds_cc_exp_year' && $key != 'cvv') {
                $field = $this->get_order_report_fields($key);
                $list.="<tr>";
                $list.="<td style='padding:15px;'>$field</td><td style='padding:15px;'>$value</td>";
                $list.="</tr>";
            }
        }
        $list.="</table>";

        $list.="<br>";

        $list.="<table align='center'>";
        foreach ($response_arr as $key => $value) {
            if ($key == 'net\authorize\api\contract\v1\TransactionResponseType responseCode') {
                $list.="<tr>";
                $list.="<td style='padding:15px;'>Transaction Response Code</td><td style='padding:15px;'>$value</td>";
                $list.="</tr>";
            }
        }
        $list.="</table>";

        $list.="</body>";
        $list.="</html>";

        return $list;
    }

    function get_order_report_fields($field) {

        switch ($field) {
            case 'cds_name':
                $name = 'Client Firstname/Lastname';
                break;
            case 'cds_address_1':
                $name = 'Client Address';
                break;
            case 'cds_city':
                $name = 'Client City';
                break;
            case 'cds_state':
                $name = 'Client State';
                break;
            case 'cds_zip':
                $name = 'Client ZIP';
                break;
            case 'cds_email':
                $name = 'Client email';
                break;
            case 'phone':
                $name = 'Client Phone';
                break;
            case 'cds_cc_number':
                $name = 'Client Card Number';
                break;
            case 'cds_cc_exp_month':
                $name = 'Client Card Expiration Month';
                break;
            case 'cds_cc_exp_year':
                $name = 'Client Card Expiration Year';
                break;
            case 'sum':
                $name = 'Program Fee ($)';
                break;
            case 'cvv':
                $name = 'Client Card CVV code';
                break;
            case 'item':
                $name = 'Program Applied';
                break;
        }

        return $name;
    }

    function save_transaction_log($data) {
        $fp = fopen($this->transaction_log_path, 'a');
        $date = date('m-d-Y h:i:s', time());
        fwrite($fp, $date . "\n");
        fwrite($fp, print_r($data, TRUE));
        fclose($fp);
    }

    function save_log($data, $order) {
        $fp = fopen($this->log_file_path, 'a');
        $date = date('m-d-Y h:i:s', time());
        fwrite($fp, $date . "\n");
        fwrite($fp, print_r($data, TRUE));
        fclose($fp);
        $report = $this->get_failed_transaction_report($data, $order);
        $subject = 'Medical2 - Failed Transaction Report';
        $mail = New Mailer();
        $mail->send_common_message($subject, $report);
    }

    function authorize() {
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName($this->LOGIN_ID);
        $merchantAuthentication->setTransactionKey($this->TRANSACTION_KEY);
        return $merchantAuthentication;
    }

    function sandbox_authorize() {
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName('6cUTfQ5238');
        $merchantAuthentication->setTransactionKey('5bN8q5WT3qa257p9');
        return $merchantAuthentication;
    }

    function prepare_order($order) {
        $exp_date = $order->cds_cc_exp_year . '-' . $order->cds_cc_exp_month;
        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber($order->cds_cc_number);
        $creditCard->setCardCode($order->cvv); // added new param - cvv
        $creditCard->setExpirationDate($exp_date);
        $payment = new AnetAPI\PaymentType();
        $payment->setCreditCard($creditCard);
        return $payment;
    }

    function make_transaction($post_order) {

        // Create the payment data for credit card        
        $payment = $this->prepare_order($post_order);
        $merchantAuthentication = $this->authorize();
        //$merchantAuthentication = $this->sandbox_authorize();
        $refId = 'ref' . time();

        // Order info
        $invoiceNo = time();
        $order = new AnetAPI\OrderType();
        $order->setInvoiceNumber($invoiceNo);
        if ($order->group == 0) {
            $order->setDescription($post_order->item);
            $lineitem = new AnetAPI\LineItemType();
            $lineitem->setItemId(time());
            $lineitem->setName($post_order->item);
            $lineitem->setDescription($post_order->item);
            $lineitem->setQuantity("1");
            $lineitem->setUnitPrice($post_order->sum);
            $lineitem->setTaxable("N");
        } // end if $order==0
        else {
            $order->setDescription($post_order->item);
            $lineitem = new AnetAPI\LineItemType();
            $lineitem->setItemId(time());
            $lineitem->setName("$post_order->item");
            $lineitem->setDescription($post_order->item);
            $lineitem->setQuantity("1");
            $lineitem->setUnitPrice($post_order->sum);
            $lineitem->setTaxable("N");
        } // end else
        // Customer info 
        $custID = round(time() / 3785);
        $customer = new AnetAPI\CustomerDataType();
        $customer->setId($custID);
        $customer->setEmail($post_order->cds_email);

        $names = explode("/", $post_order->cds_name);
        $firstname = $names[0];
        $lastname = $names[1];

        //Ship To Info
        $shipto = new AnetAPI\NameAndAddressType();
        $shipto->setFirstName($firstname);
        $shipto->setLastName($lastname);
        $shipto->setCompany('Student');
        $shipto->setAddress($post_order->cds_address_1);
        $shipto->setCity($post_order->cds_city);
        $shipto->setState($post_order->cds_state);
        $shipto->setZip($post_order->cds_zip);
        $shipto->setCountry("USA");

        // Bill To
        $billto = new AnetAPI\CustomerAddressType();
        $billto->setFirstName($firstname);
        $billto->setLastName($lastname);
        $billto->setCompany("Student");
        $billto->setAddress($post_order->cds_address_1);
        $billto->setCity($post_order->cds_city);
        $billto->setState($post_order->cds_state);
        $billto->setZip($post_order->cds_zip);
        $billto->setCountry("USA");

        //create a transaction
        $transactionRequestType = new AnetAPI\TransactionRequestType();
        $transactionRequestType->setTransactionType("authCaptureTransaction");
        $transactionRequestType->setAmount($post_order->sum);
        $transactionRequestType->setPayment($payment);
        $transactionRequestType->setOrder($order);
        $transactionRequestType->addToLineItems($lineitem);
        $transactionRequestType->setCustomer($customer);
        $transactionRequestType->setBillTo($billto);
        $transactionRequestType->setShipTo($shipto);

        $request = new AnetAPI\CreateTransactionRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setTransactionRequest($transactionRequestType);
        $controller = new AnetController\CreateTransactionController($request);
        //$response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
        $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);
        //print_r($response);
        if ($response != null) {
            $tresponse = $response->getTransactionResponse();

            /*
              echo "--------Card payment response <pre>";
              print_r($tresponse);
              echo "</pre><br>";
             */


            if (($tresponse != null) && ($tresponse->getResponseCode() == "1")) {
                //echo "Charge Credit Card AUTH CODE : " . $tresponse->getAuthCode() . "\n";
                //echo "Charge Credit Card TRANS ID  : " . $tresponse->getTransId() . "\n";
                $status = array('auth_code' => $tresponse->getAuthCode(),
                    'trans_id' => $tresponse->getTransId(),
                    'auth_code' => $tresponse->getResponseCode(),
                    'sum' => $post_order->sum);
                return $status;
            } // end if ($tresponse != null) && ($tresponse->getResponseCode() == "1")
            else {
                $this->save_log($tresponse, $post_order);
                return false;
            }
        } // end if $response != null        
        else {
            //echo "Charge Credit card Null response returned";
            return false;
        }
    }

    function make_transaction2($post_order) {

        //echo "<pre>";
        //print_r($post_order);
        //echo "</pre><br>-------------------------------<br>";
        // Create the payment data for credit card        
        $payment = $this->prepare_order($post_order);
        //$merchantAuthentication = $this->sandbox_authorize();
        $merchantAuthentication = $this->authorize();
        $refId = 'ref' . time();

        // Order info
        $invoiceNo = time();
        $order = new AnetAPI\OrderType();
        $order->setInvoiceNumber($invoiceNo);
        if ($order->group == 0) {
            $order->setDescription($post_order->item);
            $lineitem = new AnetAPI\LineItemType();
            $lineitem->setItemId(time());
            $lineitem->setName($post_order->item);
            $lineitem->setDescription($post_order->item);
            $lineitem->setQuantity("1");
            $lineitem->setUnitPrice($post_order->sum);
            $lineitem->setTaxable("N");
        } // end if $order==0
        else {
            $order->setDescription($post_order->item);
            $lineitem = new AnetAPI\LineItemType();
            $lineitem->setItemId(time());
            $lineitem->setName("$post_order->item");
            $lineitem->setDescription($post_order->item);
            $lineitem->setQuantity("1");
            $lineitem->setUnitPrice($post_order->sum);
            $lineitem->setTaxable("N");
        } // end else
        // Customer info 
        $custID = round(time() / 3785);
        $customer = new AnetAPI\CustomerDataType();
        $customer->setId($custID);
        $customer->setEmail($post_order->cds_email);

        $names = explode("/", $post_order->cds_name);
        $firstname = $names[0];
        $lastname = $names[1];

        //Ship To Info
        $shipto = new AnetAPI\NameAndAddressType();
        $shipto->setFirstName($firstname);
        $shipto->setLastName($lastname);
        $shipto->setCompany('Student');
        $shipto->setAddress($post_order->cds_address_1);
        $shipto->setCity($post_order->cds_city);
        $shipto->setState($post_order->cds_state);
        $shipto->setZip($post_order->cds_zip);
        $shipto->setCountry("USA");

        // Bill To
        $billto = new AnetAPI\CustomerAddressType();
        $billto->setFirstName($firstname);
        $billto->setLastName($lastname);
        $billto->setCompany("Student");
        $billto->setAddress($post_order->cds_address_1);
        $billto->setCity($post_order->cds_city);
        $billto->setState($post_order->cds_state);
        $billto->setZip($post_order->cds_zip);
        $billto->setCountry("USA");

        //create a transaction
        $transactionRequestType = new AnetAPI\TransactionRequestType();
        $transactionRequestType->setTransactionType("authCaptureTransaction");
        $transactionRequestType->setAmount($post_order->sum);
        $transactionRequestType->setPayment($payment);
        $transactionRequestType->setOrder($order);
        $transactionRequestType->addToLineItems($lineitem);
        $transactionRequestType->setCustomer($customer);
        $transactionRequestType->setBillTo($billto);
        $transactionRequestType->setShipTo($shipto);

        $request = new AnetAPI\CreateTransactionRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setTransactionRequest($transactionRequestType);
        $controller = new AnetController\CreateTransactionController($request);
        //$response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
        $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);
        //echo "--------Card payment response1 <pre>";
        //print_r($response);
        //echo "</pre><br>";

        if ($response != null) {
            $tresponse = $response->getTransactionResponse();


            //echo "--------Card payment response2 <pre>";
            //print_r($tresponse);
            //echo "</pre><br>";
            //die();


            if (($tresponse != null) && ($tresponse->getResponseCode() == "1")) {
                //echo "Charge Credit Card AUTH CODE : " . $tresponse->getAuthCode() . "\n";
                //echo "Charge Credit Card TRANS ID  : " . $tresponse->getTransId() . "\n";
                $status = array('auth_code' => $tresponse->getAuthCode(),
                    'trans_id' => $tresponse->getTransId(),
                    'auth_code' => $tresponse->getResponseCode(),
                    'sum' => $post_order->sum);
                return $status;
            } // end if ($tresponse != null) && ($tresponse->getResponseCode() == "1")
            else {
                $this->save_log($tresponse, $post_order);
                return false;
            }
        } // end if $response != null        
        else {
            //echo "Charge Credit card Null response returned";
            return false;
        }
    }

    function createSubscription($subs) {

        //$merchantAuthentication = $this->sandbox_authorize();
        $merchantAuthentication = $this->authorize();

        $amount = round($subs->amount / $subs->payments_num);
        $names = explode(" ", $subs->holder); // card holder name
        $firstname = $names[1];
        $lastname = $names[0];
        $exp_date = $subs->card_year . "-" . $subs->card_month;

        $period_sec = strtotime($subs->end) - strtotime($subs->start);
        //  Interval Length must be a value from 7 through 365 for day based subscriptions
        $intervalLength = round($period_sec / $subs->payments_num / 86400);

        $refId = 'ref' . time();

        // Subscription Type Info
        $subscription = new AnetAPI\ARBSubscriptionType();
        $subscription->setName($subs->coursename);

        $interval = new AnetAPI\PaymentScheduleType\IntervalAType();
        $interval->setLength($intervalLength);
        $interval->setUnit("days");

        $paymentSchedule = new AnetAPI\PaymentScheduleType();
        $paymentSchedule->setInterval($interval);
        $paymentSchedule->setStartDate(new DateTime($subs->start));
        $paymentSchedule->setTotalOccurrences($subs->payments_num);

        $subscription->setPaymentSchedule($paymentSchedule);
        $subscription->setAmount($amount);

        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber($subs->card_no);
        $creditCard->setExpirationDate($exp_date);

        $payment = new AnetAPI\PaymentType();
        $payment->setCreditCard($creditCard);

        $subscription->setPayment($payment);

        $billTo = new AnetAPI\NameAndAddressType();
        $billTo->setFirstName($firstname);
        $billTo->setLastName($lastname);

        $subscription->setBillTo($billTo);

        $request = new AnetAPI\ARBCreateSubscriptionRequest();
        $request->setmerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setSubscription($subscription);
        $controller = new AnetController\ARBCreateSubscriptionController($request);

        $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);

        if (($response != null) && ($response->getMessages()->getResultCode() == "Ok")) {
            return $response->getSubscriptionId();
        } // end if  
        else {
            $errorMessages = $response->getMessages()->getMessage();
            echo "Response : " . $errorMessages[0]->getCode() . "  " . $errorMessages[0]->getText() . "\n";
            return false;
        }
    }

    function getSubscriptionStatus($subscriptionId) {
        $merchantAuthentication = $this->authorize();
        $refId = 'ref' . time();

        $request = new AnetAPI\ARBGetSubscriptionStatusRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setSubscriptionId($subscriptionId);
        $controller = new AnetController\ARBGetSubscriptionStatusController($request);

        $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);
        if (($response != null) && ($response->getMessages()->getResultCode() == "Ok")) {
            return $response->getStatus();
        } // end if  
        else {
            echo "ERROR :  Invalid response\n";
            $errorMessages = $response->getMessages()->getMessage();
            echo "Response : " . $errorMessages[0]->getCode() . "  " . $errorMessages[0]->getText() . "\n";
            return false;
        } // end else

        return $response;
    }

    function cancelSubscription($subscriptionId) {
        // Common Set Up for API Credentials
        $merchantAuthentication = $this->authorize();
        $refId = 'ref' . time();

        $request = new AnetAPI\ARBCancelSubscriptionRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setSubscriptionId($subscriptionId);

        $controller = new AnetController\ARBCancelSubscriptionController($request);
        $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);

        if (($response != null) && ($response->getMessages()->getResultCode() == "Ok")) {
            return true;
        } // end if  
        else {
            echo "ERROR :  Invalid response\n";
            $errorMessages = $response->getMessages()->getMessage();
            echo "Response : " . $errorMessages[0]->getCode() . "  " . $errorMessages[0]->getText() . "\n";
            return false;
        }
    }

    function prepareExpirationDate($exp_date) {
        // MMYY - format
        $mm = substr($exp_date, 0, 2);
        $yy = substr($exp_date, 4);
        $date = $mm . $yy;
        return $date;
    }

    function makeRefund($amount, $card_last_four, $exp_date, $trans_id) {
        $merchantAuthentication = $this->authorize();
        $refId = 'ref' . time();
        $date = $this->prepareExpirationDate($exp_date);

        // Create the payment data for a credit card
        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber(base64_decode($card_last_four));
        $creditCard->setExpirationDate($date);
        $paymentOne = new AnetAPI\PaymentType();
        $paymentOne->setCreditCard($creditCard);

        //create a transaction
        $transactionRequest = new AnetAPI\TransactionRequestType();
        $transactionRequest->setTransactionType("refundTransaction");
        $transactionRequest->setAmount($amount);
        $transactionRequest->setRefTransId($trans_id);
        $transactionRequest->setPayment($paymentOne);

        $request = new AnetAPI\CreateTransactionRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setTransactionRequest($transactionRequest);
        $controller = new AnetController\CreateTransactionController($request);
        //$response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
        $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);
        $this->save_transaction_log($response);
        if ($response != null) {
            $tresponse = $response->getTransactionResponse();
            $this->save_transaction_log($tresponse);
            if (($tresponse != null) && ($tresponse->getResponseCode() == "1" )) {
                return TRUE;
            } // end if ($tresponse != null) && ($tresponse->getResponseCode() == \SampleCode\Constants::RESPONSE_OK)            
            else {
                return FALSE;
            }
        } // end if $response != null 
        else {
            return FALSE;
        }
    }

    function getCustomerProfileIds() {
        // Common setup for API credentials
        $merchantAuthentication = $this->sandbox_authorize();
        $refId = 'ref' . time();

        // Get all existing customer profile ID's
        $request = new AnetAPI\GetCustomerProfileIdsRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $controller = new AnetController\GetCustomerProfileIdsController($request);
        $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
        if (($response != null) && ($response->getMessages()->getResultCode() == "Ok")) {
            echo "GetCustomerProfileId's SUCCESS: " . "\n";
            $profileIds[] = $response->getIds();

            echo "<pre>";
            print_r($profileIds);
            echo "</pre>";
            //echo "There are " . count($profileIds[0]) . " Customer Profile ID's for this Merchant Name and Transaction Key" . "\n";
        } // end if ($response != null) && ($response->getMessages()->getResultCode() == "Ok") 
        else {
            echo "GetCustomerProfileId's ERROR :  Invalid response\n";
            $errorMessages = $response->getMessages()->getMessage();
            echo "Response : " . $errorMessages[0]->getCode() . "  " . $errorMessages[0]->getText() . "\n";
        } // end else 
        return $response;
    }

    function makeRefund2($amount, $card_last_four, $exp_date, $trans_id) {
        $merchantAuthentication = $this->sandbox_authorize();
        $refId = 'ref' . time();
        $date = $this->prepareExpirationDate($exp_date);

        // Create the payment data for a credit card
        $creditCard = new AnetAPI\CreditCardType();
        //$creditCard->setCardNumber(base64_decode($card_last_four));
        $creditCard->setCardNumber($card_last_four);
        $creditCard->setExpirationDate($date);
        $paymentOne = new AnetAPI\PaymentType();
        $paymentOne->setCreditCard($creditCard);

        //create a transaction
        $transactionRequest = new AnetAPI\TransactionRequestType();
        $transactionRequest->setTransactionType("refundTransaction");
        $transactionRequest->setAmount($amount);
        $transactionRequest->setRefTransId($trans_id);
        $transactionRequest->setPayment($paymentOne);

        $request = new AnetAPI\CreateTransactionRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setTransactionRequest($transactionRequest);
        $controller = new AnetController\CreateTransactionController($request);
        $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
        //$response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);
        if ($response != null) {
            $tresponse = $response->getTransactionResponse();

            echo "Response: <pre>";
            print_r($tresponse);
            echo "</pre>";

            if (($tresponse != null) && ($tresponse->getResponseCode() == "1" )) {
                //echo "it is ok ....";
                return TRUE;
            } // end if ($tresponse != null) && ($tresponse->getResponseCode() == \SampleCode\Constants::RESPONSE_OK)            
            else {
                $this->save_log($tresponse, $post_order);
                return FALSE;
            }
        } // end if $response != null 
        else {
            //echo "Null resposnse .. ...";
            return FALSE;
        }
        return $response;
    }

    /*     * ************** Code related to new hosted form *************** */

    function get_country_by_id($countryid) {
        $query = "select * from mdl_countries where id=$countryid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $country = $row['name'];
        }
        return $country;
    }

    function getAnAcceptPaymentPage($userrequest) {

        $merchantAuthentication = $this->sandbox_authorize();
        //$merchantAuthentication = $this->authorize();
        $token = $userrequest->token;
        $fname = $userrequest->first_name;
        $lname = $userrequest->last_name;
        $custID = $userrequest->custid;
        $amount = $userrequest->amount;
        $addr = $userrequest->addr;
        $city = $userrequest->city;
        $stateid = $userrequest->state;  // it is id
        $state = $this->get_state_name_by_id($stateid);
        $zip = $userrequest->zip;
        $email = $userrequest->email;
        $phone = $userrequest->phone;
        $item = $userrequest->program;
        $country = $this->get_country_by_id($userrequest->country);

        //create a transaction
        $transactionRequestType = new AnetAPI\TransactionRequestType();
        $transactionRequestType->setTransactionType("authCaptureTransaction");
        $transactionRequestType->setAmount($amount);
        $refId = 'ref' . $token;
        $transactionRequestType->setRefTransId($refId);

        // Order info
        $order = new AnetAPI\OrderType();
        $order->setDescription($item);
        $transactionRequestType->setOrder($order);

        // Ship info
        $shipTo = new AnetAPI\NameAndAddressType();
        $shipTo->setFirstName($fname);
        $shipTo->setLastName($lname);
        $shipTo->setCompany('Student');
        $shipTo->setAddress($addr);
        $shipTo->setState($state);
        $shipTo->setCity($city);
        $shipTo->setZip($zip);
        $transactionRequestType->setShipTo($shipTo);

        // Customer info
        $customer = new AnetAPI\CustomerDataType();
        $customer->setEmail($email);
        $customer->setId($custID);
        $transactionRequestType->setCustomer($customer);

        // Billing info
        $billTo = new AnetAPI\CustomerAddressType();
        $billTo->setCompany('Student');
        $billTo->setEmail($email);
        $billTo->setPhoneNumber($phone);
        $billTo->setFirstName($fname);
        $billTo->setLastName($lname);
        $billTo->setState($state);
        $billTo->setCity($city);
        $billTo->setAddress($addr);
        $billTo->setCountry($country);
        $billTo->setZip($zip);
        $billTo->setFaxNumber($phone);
        $transactionRequestType->setBillTo($billTo);

        // Set Hosted Form options    
        $setting1 = new AnetAPI\SettingType();
        $setting1->setSettingName("hostedPaymentButtonOptions");
        $setting1->setSettingValue("{\"text\": \"Pay\"}");

        $setting2 = new AnetAPI\SettingType();
        $setting2->setSettingName("hostedPaymentOrderOptions");
        $setting2->setSettingValue("{\"show\": true}");

        $setting3 = new AnetAPI\SettingType();
        $setting3->setSettingName("hostedPaymentReturnOptions");
        $setting3->setSettingValue("{\"url\": \"https://medical2.com/register2/proceed_auth_card\", \"cancelUrl\": \"https://medical2.com/register2/cancel_auth_card\", \"showReceipt\": false}");

        $setting4 = new AnetAPI\SettingType();
        $setting4->setSettingName("hostedPaymentIFrameCommunicatorUrl");
        $setting4->setSettingValue("{\"url\": \"https://medical2.com/communicator.html\"}");

        $setting5 = new AnetAPI\SettingType();
        $setting5->setSettingName("hostedPaymentPaymentOptions");
        $setting5->setSettingValue("{\"cardCodeRequired\": true}");

        $setting6 = new AnetAPI\SettingType();
        $setting6->setSettingName("hostedPaymentBillingAddressOptions");
        $setting6->setSettingValue("{\"show\": true, \"required\":true}");

        $setting7 = new AnetAPI\SettingType();
        $setting7->setSettingName("hostedPaymentCustomerOptions");
        $setting7->setSettingValue("{\"showEmail\": true, \"requiredEmail\":true}");

        $setting8 = new AnetAPI\SettingType();
        $setting8->setSettingName("hostedPaymentOrderOptions");
        $setting8->setSettingValue("{\"show\": true, \"merchantName\": \"G and S Questions Inc.\"}");


        // Build transaction request    
        $request = new AnetAPI\GetHostedPaymentPageRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setTransactionRequest($transactionRequestType);
        $request->addToHostedPaymentSettings($setting1);
        $request->addToHostedPaymentSettings($setting2);
        $request->addToHostedPaymentSettings($setting3);
        $request->addToHostedPaymentSettings($setting4);
        $request->addToHostedPaymentSettings($setting5);
        $request->addToHostedPaymentSettings($setting6);
        //$request->addToHostedPaymentSettings($setting7);
        //$request->addToHostedPaymentSettings($setting8);
        //execute request
        $controller = new AnetController\GetHostedPaymentPageController($request);
        $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
        //$response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);

        if (($response != null) && ($response->getMessages()->getResultCode() == "Ok")) {
            return $response->getToken();
        } // end if  
        else {
            $errorMessages = $response->getMessages()->getMessage();
            $err_response = $errorMessages[0]->getCode() . "  " . $errorMessages[0]->getText();
            return $err_response;
        }
    }

    function getGroupAnAcceptPaymentPage($userrequest) {
        $merchantAuthentication = $this->sandbox_authorize();
        //$merchantAuthentication = $this->authorize();

        $token = time();
        $custid = rand(12, 10000);

        $fname = $userrequest->fname;
        $lname = $userrequest->lname;
        $custID = $custid;
        $amount = $userrequest->amount;
        $addr = $userrequest->addr;
        $city = $userrequest->city;
        $stateid = $userrequest->state;  // it is id
        $state = $this->get_state_name_by_id($stateid);
        $email = $userrequest->email;
        $phone = $userrequest->phone;
        $item = $userrequest->program_name;
        $country = $this->get_country_by_id($userrequest->country);

        //create a transaction
        $transactionRequestType = new AnetAPI\TransactionRequestType();
        $transactionRequestType->setTransactionType("authCaptureTransaction");
        $transactionRequestType->setAmount($amount);
        $refId = 'ref' . $token;
        $transactionRequestType->setRefTransId($refId);

        // Order info
        $order = new AnetAPI\OrderType();
        $order->setDescription($item);
        $transactionRequestType->setOrder($order);

        // Ship info
        $shipTo = new AnetAPI\NameAndAddressType();
        $shipTo->setFirstName($fname);
        $shipTo->setLastName($lname);
        $shipTo->setCompany('Student');
        $shipTo->setAddress($addr);
        $shipTo->setState($state);
        $shipTo->setCity($city);
        $transactionRequestType->setShipTo($shipTo);

        // Customer info
        $customer = new AnetAPI\CustomerDataType();
        $customer->setEmail($email);
        $customer->setId($custID);
        $transactionRequestType->setCustomer($customer);

        // Billing info
        $billTo = new AnetAPI\CustomerAddressType();
        $billTo->setCompany('Student');
        $billTo->setEmail($email);
        $billTo->setPhoneNumber($phone);
        $billTo->setFirstName($fname);
        $billTo->setLastName($lname);
        $billTo->setState($state);
        $billTo->setCity($city);
        $billTo->setAddress($addr);
        $billTo->setCountry($country);
        $billTo->setFaxNumber($phone);
        $transactionRequestType->setBillTo($billTo);

        // Set Hosted Form options    
        $setting1 = new AnetAPI\SettingType();
        $setting1->setSettingName("hostedPaymentButtonOptions");
        $setting1->setSettingValue("{\"text\": \"Pay\"}");

        $setting2 = new AnetAPI\SettingType();
        $setting2->setSettingName("hostedPaymentOrderOptions");
        $setting2->setSettingValue("{\"show\": true}");

        $setting3 = new AnetAPI\SettingType();
        $setting3->setSettingName("hostedPaymentReturnOptions");
        $setting3->setSettingValue("{\"url\": \"https://medical2.com/register2/proceed_group_auth_card\", \"cancelUrl\": \"https://medical2.com/register2/cancel_auth_card\", \"showReceipt\": false}");

        $setting4 = new AnetAPI\SettingType();
        $setting4->setSettingName("hostedPaymentIFrameCommunicatorUrl");
        $setting4->setSettingValue("{\"url\": \"https://medical2.com/communicator.html\"}");

        $setting5 = new AnetAPI\SettingType();
        $setting5->setSettingName("hostedPaymentPaymentOptions");
        $setting5->setSettingValue("{\"cardCodeRequired\": true}");

        $setting6 = new AnetAPI\SettingType();
        $setting6->setSettingName("hostedPaymentBillingAddressOptions");
        $setting6->setSettingValue("{\"show\": true, \"required\":true}");

        $setting7 = new AnetAPI\SettingType();
        $setting7->setSettingName("hostedPaymentCustomerOptions");
        $setting7->setSettingValue("{\"showEmail\": true, \"requiredEmail\":true}");

        $setting8 = new AnetAPI\SettingType();
        $setting8->setSettingName("hostedPaymentOrderOptions");
        $setting8->setSettingValue("{\"show\": true, \"merchantName\": \"G and S Questions Inc.\"}");


        // Build transaction request    
        $request = new AnetAPI\GetHostedPaymentPageRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setTransactionRequest($transactionRequestType);
        $request->addToHostedPaymentSettings($setting1);
        $request->addToHostedPaymentSettings($setting2);
        $request->addToHostedPaymentSettings($setting3);
        $request->addToHostedPaymentSettings($setting4);
        $request->addToHostedPaymentSettings($setting5);
        $request->addToHostedPaymentSettings($setting6);
        //$request->addToHostedPaymentSettings($setting7);
        //$request->addToHostedPaymentSettings($setting8);
        //execute request
        $controller = new AnetController\GetHostedPaymentPageController($request);
        $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
        //$response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);

        if (($response != null) && ($response->getMessages()->getResultCode() == "Ok")) {
            return $response->getToken();
        } // end if  
        else {
            $errorMessages = $response->getMessages()->getMessage();
            $err_response = $errorMessages[0]->getCode() . "  " . $errorMessages[0]->getText();
            return $err_response;
        }
    }

    function get_customer_payments($customerProfileId) {
        $merchantAuthentication = $this->sandbox_authorize();
        $request = new AuthorizeNetCIM();

        $response = $request->getCustomerProfile($customerProfileId);
        print_r($response);   // Will show you the structure of what comes back
    }

    function get_state_name_by_id($stateid) {
        $query = "select * from mdl_states where id=$stateid";
        $result = $this->db->query($query);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $state = $row['state'];
        }
        return $state;
    }

}
