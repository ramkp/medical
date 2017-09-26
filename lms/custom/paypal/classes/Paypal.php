<?php


require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/utils/classes/Util.php';


/**
 * Description of Paypal
 *
 * @author moyo
 */
class Paypal extends Util
{

    /**
     * Paypal constructor.
     */
    function __construct()
    {
        parent::__construct();
    }


    /**
     * @param $transid
     * @return string
     */
    function get_refund_modal_dialog($transid)
    {
        $list = "";
        $list .= "<div id='myModal' class='modal fade'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h4 class='modal-title'>Refund PayPal Payment</h4>
                </div>
                <div class='modal-body'>
                <input type='hidden' id='transid' value='$transid'>
                   
                <div class='container-fluid'>
                <span class='span1'>Amount*</span>
                <span class='span3'><input type='text' id='refund_amount'></span>
                </div>
                
                <div class='container-fluid'>
                <span class='span4' id='refund_err' style='color:red;'></span>
                </div></div>  
              
                <div class='modal-footer' style='text-align:center;'>
                    <span align='center'><button type='button' class='btn btn-primary' data-dismiss='modal' id='cancel'>Cancel</button></span>
                    <span align='center'><button type='button' class='btn btn-primary' id='make_paypal_refund'>OK</button></span>
                </div>
            </div>
        </div>
    </div>";

        return $list;
    }

}
