

<!-- Load the Client component. -->
<script src="https://js.braintreegateway.com/web/3.14.0/js/client.min.js"></script>

<!-- Load the Hosted Fields component. -->
<script src="https://js.braintreegateway.com/web/3.14.0/js/hosted-fields.min.js"></script>


<style>

    .hosted-field {
        height: 50px;
        box-sizing: border-box;
        width: 100%;
        padding: 12px;
        display: inline-block;
        box-shadow: none;
        font-weight: 600;
        font-size: 14px;
        border-radius: 6px;
        border: 1px solid #dddddd;
        line-height: 20px;
        background: #fcfcfc;
        margin-bottom: 12px;
        background: linear-gradient(to right, white 50%, #fcfcfc 50%);
        background-size: 200% 100%;
        background-position: right bottom;
        transition: all 300ms ease-in-out;
    }

    .hosted-fields--label {
        font-family: courier, monospace;
        text-transform: uppercase;
        font-size: 14px;
        display: block;
        margin-bottom: 6px;
    }

    .button-container {
        display: block;
        text-align: center;
    }

    .button {
        cursor: pointer;
        font-weight: 500;
        line-height: inherit;
        position: relative;
        text-decoration: none;
        text-align: center;
        border-style: solid;
        border-width: 1px;
        border-radius: 3px;
        -webkit-appearance: none;
        -moz-appearance: none;
        display: inline-block;
    }

    .button--small {
        padding: 10px 20px;
        font-size: 0.875rem;
    }

    .button--green {
        outline: none;
        background-color: #64d18a;
        border-color: #64d18a;
        color: white;
        transition: all 200ms ease;
    }

    .button--green:hover {
        background-color: #8bdda8;
        color: white;
    }

    .braintree-hosted-fields-focused {
        border: 1px solid #64d18a;
        border-radius: 1px;
        background-position: left bottom;
    }

    .braintree-hosted-fields-invalid {
        border: 1px solid #ed574a;
    }

    .braintree-hosted-fields-valid {
    }

    #cardForm {
        max-width: 50.75em;
        margin: 0 auto;
        padding: 1.875em;
    }


</style>

<?php
echo $form;
?>


<script type="text/javascript">
// We generated a client token for you so you can test out this code
// immediately. In a production-ready integration, you will need to
// generate a client token on your server (see section below).

    $(document).ready(function () {
        console.log("ready!");

        var form = document.querySelector('#checkout-form');
        var submit = document.querySelector('input[type="submit"]');
        var url = '/lms/custom/paypal/get_card_client_token.php';
        $.post(url, {id: 1}).done(function (token) {
            braintree.client.create({
                authorization: token
            }, function (clientErr, clientInstance) {
                if (clientErr) {
                    // Handle error in client creation
                    console.log('Initialization error ...' + clientErr);
                    return;
                }

                braintree.hostedFields.create({
                    client: clientInstance,
                    styles: {
                        'input': {
                            'font-size:': '14pt'
                        },
                        'input.invalid': {
                            'color': 'red'
                        },
                        'input.valid': {
                            'color': 'green'
                        }
                    },
                    fields: {
                        number: {
                            selector: '#card-number',
                            placeholder: '4111 1111 1111 1111'
                        },
                        cvv: {
                            selector: '#cvv',
                            placeholder: '123'
                        },
                        expirationDate: {
                            selector: '#expiration-date',
                            placeholder: '10/2019'
                        }
                    }
                },
                        function (hostedFieldsErr, hostedFieldsInstance) {
                            if (hostedFieldsErr) {
                                $('#err').html('Initizlization error:' + hostedFieldsErr);
                                return;
                            }

                            submit.removeAttribute('disabled');
                            form.addEventListener('submit', function (event) {
                                event.preventDefault();
                                hostedFieldsInstance.tokenize(function (tokenizeErr, payload) {
                                    if (tokenizeErr) {
                                        $('#err').html('Please provide correct credit card data');
                                        return;
                                    }
                                    console.log('Nonce: ' + payload.nonce);
                                    $('#err').html('');
                                    $('#ajax_loading_payment').show();
                                    $('#make_any_pay_payment').prop('disabled', true);
                                    $('#make_any_pay_payment').text('Processing request');
                                    var url = '/lms/custom/paypal/create_any_pay_transaction.php';
                                    var amount = $('#amount').val();
                                    var user = $('#user').val();
                                    var email = $('#email').val();
                                    var period = $('#period').val();
                                    var courseid = $('#courseid').val();
                                    var slotid = $('#slotid').val();
                                    var trans = {amount: amount,
                                        nonce: payload.nonce,
                                        user: user,
                                        period: period,
                                        slotid: slotid,
                                        courseid: courseid};
                                    $.post(url, {trans: JSON.stringify(trans)}).done(function (status) {
                                        $('#ajax_loading_payment').hide();
                                        $('#make_any_pay_payment').prop('disabled', false);
                                        $('#make_any_pay_payment').text('I Agree, Submit');
                                        if (status) {
                                            var msg;
                                            if (period == 0) {
                                                msg = "Payment is successful. Thank you! You can print your registration data <a href='https://medical2.com/lms/custom/invoices/registrations/" + email + ".pdf' target='_blank'>here.</a>";
                                            } // end if
                                            else {
                                                msg = "This certification has been renewed";
                                            } // end else
                                            $('#err').html("<span style='color:black'>" + msg + "</span>");
                                        } // end if status
                                        else {
                                            $('#err').html("<span style='color:black'>Credit card was declined</span>");
                                        } // end else
                                    }); // end of post
                                });
                            }, false);
                        });
            });
        }); // end of post

        $('#card-number').css('height', null);

    }); // end of document ready ...



</script>





