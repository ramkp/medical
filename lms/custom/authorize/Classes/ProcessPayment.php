<?php

ini_set('display_errors', '1');
require_once ($_SERVER['DOCUMENT_ROOT'] . '/lms/custom/authorize/Api/vendor/autoload.php');

use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

class ProcessPayment {

    private $AUTHORIZENET_LOG_FILE;
    private $LOGIN_ID = '6cUTfQ5238'; // sandbox data
    private $TRANSACTION_KEY = '5bN8q5WT3qa257p9'; // sandbox data

    function __construct() {
        $this->AUTHORIZENET_LOG_FILE = 'phplog';
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
            $order->setDescription("Payment for tuition $post_order->item");
            $lineitem = new AnetAPI\LineItemType();
            $lineitem->setItemId(time());
            $lineitem->setName("$post_order->item");
            $lineitem->setDescription("Payment for tuition $post_order->item");
            $lineitem->setQuantity("1");
            $lineitem->setUnitPrice($post_order->sum);
            $lineitem->setTaxable("N");
        } // end if $order==0
        else {
            $order->setDescription("Payment for group tuition $post_order->item");
            $lineitem = new AnetAPI\LineItemType();
            $lineitem->setItemId(time());
            $lineitem->setName("$post_order->item");
            $lineitem->setDescription("Payment for group tuition $post_order->item");
            $lineitem->setQuantity("1");
            $lineitem->setUnitPrice($post_order->sum);
            $lineitem->setTaxable("N");
        } // end else
        // Customer info 
        $custID = round(time() / 3785);
        $customer = new AnetAPI\CustomerDataType();
        $customer->setId($custID);
        $customer->setEmail($post_order->cds_email);

        //Ship To Info
        $names = explode(" ", $post_order->cds_name);
        $shipto = new AnetAPI\NameAndAddressType();
        $shipto->setFirstName($names[0]);
        $shipto->setLastName($names[1]);
        $shipto->setCompany('Student');
        $shipto->setAddress($post_order->cds_address_1);
        $shipto->setCity($post_order->cds_city);
        $shipto->setState($post_order->cds_state);
        $shipto->setZip($post_order->cds_zip);
        $shipto->setCountry("USA");

        // Bill To
        $billto = new AnetAPI\CustomerAddressType();
        $billto->setFirstName($names[0]);
        $billto->setLastName($names[1]);
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
        $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
        if ($response != null) {
            $tresponse = $response->getTransactionResponse();

              /*
               * 
              echo "<pre>";
              print_r($tresponse);
              echo "</pre>";
               * 
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
                //echo "Charge Credit Card ERROR :  Invalid response\n";
                return false;
            }
        } // end if $response != null        
        else {
            //echo "Charge Credit card Null response returned";
            return false;
        }
    }

}
