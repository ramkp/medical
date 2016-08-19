<?php

ini_set('display_errors', '1');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/lms/custom/authorize/Api/vendor/autoload.php');

//require_once $_SERVER['DOCUMENT_ROOT'] . '/functionality/php/classes/Invoice.php';
//require_once ($_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php');

use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

class ProcessPayment {

    private $AUTHORIZENET_LOG_FILE;
    //private $LOGIN_ID = '6cUTfQ5238'; // sandbox data
    //private $TRANSACTION_KEY = '5bN8q5WT3qa257p9'; // sandbox data
    private $LOGIN_ID = '83uKk2VcBBsC'; // production data
    private $TRANSACTION_KEY = '23P447taH34H26h5'; // production data
    public $period = 28; // 28 days of installment 
    public $log_file_path;

    function __construct() {
        $this->AUTHORIZENET_LOG_FILE = 'phplog';
        $this->log_file_path = $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/authorize/failed_transactions.log';
    }

    function save_log($data) {
        $fp = fopen($this->log_file_path, 'a');
        $date=date('m-d-Y h:i:s', time());
        fwrite($fp, $date ."\n");
        fwrite($fp, print_r($data, TRUE));
        fclose($fp);
    }

    function authorize() {
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName($this->LOGIN_ID);
        $merchantAuthentication->setTransactionKey($this->TRANSACTION_KEY);
        return $merchantAuthentication;
    }

    function prepare_order($order) {
        $exp_date = $order->cds_cc_exp_year . '-' . $order->cds_cc_exp_month;
        //echo "<br/>Expiration date: " . $exp_date . "<br/>";
        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber($order->cds_cc_number);
        $creditCard->setExpirationDate($exp_date);
        $payment = new AnetAPI\PaymentType();
        $payment->setCreditCard($creditCard);
        return $payment;
    }

    function make_transaction($post_order) {

        // Create the payment data for a credit card        
        $payment = $this->prepare_order($post_order);
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

        /*
         * 
          echo "<br>--------------------<br>";
          print_r($names);
          echo "<br>--------------------<br>";
          die ();
         * 
         */

        //$firstname = ($names[0] == '') ? "Loyal" : $names[0];
        //$lastname = ($names[1] == '') ? 'Client' : $names[1];

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
                $this->save_log($tresponse);
                return false;
            }
        } // end if $response != null        
        else {
            //echo "Charge Credit card Null response returned";
            return false;
        }
    }

    function createSubscription($post_order) {

        // Common Set Up for API Credentials
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName($this->LOGIN_ID);
        $merchantAuthentication->setTransactionKey($this->TRANSACTION_KEY);
        $intervalLength = round($this->period / $post_order->payments_num);
        $refId = 'ref' . time();
        $start_date_h = date('Y-m-d', time()); // first subscription payment today
        $total_occurences = $post_order->payments_num;
        $expiration = $post_order->cds_cc_year . "-" . $post_order->cd_cc_month;
        $names = explode("/", $post_order->cds_name);

        // Customer info 
        $custID = round(time() / 3785);
        $customer = new AnetAPI\CustomerDataType();
        $customer->setId($custID);
        $customer->setEmail($post_order->cds_email);

        /*
         * 
          echo "<br>--------------------<br>";
          print_r($names);
          echo "<br>--------------------<br>";

          echo "First name: ".$firstname."<br>";
          echo "Last name: ".$lastname."<br>";
         * 
         */

        $firstname = $names[0];
        $lastname = $names[1];

        //$firstname = ($names[0] == '') ? "Loyal" : $names[0];
        //$lastname = ($names[2] == '') ? 'Client' : $names[2];
        // Subscription Type Info
        $subscription = new AnetAPI\ARBSubscriptionType();
        $subscription->setName("Subscription for $post_order->item");
        $interval = new AnetAPI\PaymentScheduleType\IntervalAType();
        $interval->setLength($intervalLength);
        $interval->setUnit("days");
        $paymentSchedule = new AnetAPI\PaymentScheduleType();
        $paymentSchedule->setInterval($interval);
        $paymentSchedule->setStartDate(new DateTime($start_date_h));
        $paymentSchedule->setTotalOccurrences($total_occurences);
        $paymentSchedule->setTrialOccurrences("1");
        $subscription->setPaymentSchedule($paymentSchedule);
        $subscription->setAmount($post_order->sum);
        $subscription->setTrialAmount("0.00");

        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber($post_order->cds_cc_number);
        $creditCard->setExpirationDate($expiration);
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
        //$response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
        $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);

        /*
         * 
          echo "--------Subscription response <pre>";
          print_r($response);
          echo "<br>-------------------------<br>";
          die('Stopped ....');
         * 
         */

        if (($response != null) && ($response->getMessages()->getResultCode() == "Ok")) {
            $msg = $response->getSubscriptionId();
            //echo "Message: ".$msg."<br>";
        }  // end if ($response != null) && ($response->getMessages()->getResultCode() == "Ok")        
        else {
            $this->save_log($response);
            $errorMessages = $response->getMessages()->getMessage();
            $msg = $errorMessages[0]->getCode() . "  " . $errorMessages[0]->getText();
        } // end else
        return $msg;
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
        //$transactionRequest->setCustomField("x_ref_trans_id", $trans_id);
        $transactionRequest->setPayment($paymentOne);

        $request = new AnetAPI\CreateTransactionRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setRefId($refId);
        $request->setTransactionRequest($transactionRequest);
        $controller = new AnetController\CreateTransactionController($request);
        //$response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
        $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);
        if ($response != null) {
            $tresponse = $response->getTransactionResponse();

            //echo "Response: <pre>";
            //print_r($tresponse);
            //echo "</pre>";

            if (($tresponse != null) && ($tresponse->getResponseCode() == "1" )) {
                //echo "it is ok ....";
                return TRUE;
            } // end if ($tresponse != null) && ($tresponse->getResponseCode() == \SampleCode\Constants::RESPONSE_OK)            
            else {
                $this->save_log($tresponse);
                return FALSE;
            }
        } // end if $response != null 
        else {
            //echo "Null resposnse .. ...";
            return FALSE;
        }
        return $response;
    }

}
