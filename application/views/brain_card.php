
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
                        },
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
                                var card_holder = $('#cardholder').val();
                                hostedFieldsInstance.tokenize(function (tokenizeErr, payload) {
                                    if (tokenizeErr) {
                                        $('#err').html('Please provide correct credit card data');
                                        return;
                                    }

                                    var firstname, lastname;
                                    if (card_holder == '') {
                                        $('#err').html('Please provide correct credit card data');
                                        return;
                                    }

                                    if (card_holder != '') {
                                        // Remove double spaces between words
                                        var clean_holder = card_holder.replace(/\s\s+/g, ' ');
                                        var names_arr = clean_holder.split(" ");

                                        console.log('names array length: ' + names_arr.length);

                                        if (names_arr.length == 1) {
                                            $('#err').html('Please provide correct card holder name separated by space');
                                            return;
                                        }

                                        if (names_arr.length == 2) {
                                            console.log('Two names case ....');
                                            console.log('Holder name: ' + card_holder);
                                            firstname = names_arr[0];
                                            lastname = names_arr[1];
                                            console.log('Billing firstname: ' + firstname);
                                            console.log('Billing lastname: ' + lastname);
                                            if (typeof (firstname) === "undefined" || firstname == '' || typeof (lastname) === "undefined" || lastname == '') {
                                                $('#err').html('Please provide correct card holder name separated by space');
                                                return;
                                            }
                                        } // end if names_arr.length == 2

                                        if (names_arr.length == 3) {
                                            console.log('Three names case ...');
                                            console.log('Holder name: ' + card_holder);
                                            firstname = names_arr[0] + ' ' + names_arr[1];
                                            lastname = names_arr[2];
                                            console.log('Billing firstname: ' + firstname);
                                            console.log('Billing lastname: ' + lastname);
                                            if (typeof (firstname) === "undefined" || firstname == '' || typeof (lastname) === "undefined" || lastname == '') {
                                                $('#err').html('Please provide correct card holder name separated by space');
                                                return;
                                            }
                                        } // end if names_arr.length == 3
                                    } // end if card_holder != ''

                                    console.log('Nonce: ' + payload.nonce);
                                    $('#err').html('');
                                    $('#ajax_loading_payment').show();
                                    $('#make_register_braintree_pyament').prop('disabled', true);
                                    $('#make_register_braintree_pyament').prop('value', 'Processing request');
                                    var url = '/lms/custom/paypal/create_transaction.php';
                                    var amount = $('#amount').val();
                                    var user = $('#user').val();
                                    var email = $('#email').val();
                                    var trans = {amount: amount, nonce: payload.nonce, user: user, cardholder: card_holder};
                                    $.post(url, {trans: JSON.stringify(trans)}).done(function (status) {
                                        $('#ajax_loading_payment').hide();
                                        $('#make_register_braintree_pyament').prop('disabled', false);
                                        $('#make_register_braintree_pyament').prop('value', 'I Agree, Submit');
                                        if (status) {
                                            var confirmation_url = '/lms/custom/paypal/get_reg_confirmation_msg.php';
                                            $.post(confirmation_url, {trans: JSON.stringify(trans)}).done(function (data) {
                                                // Replace payment form with response
                                                $('.form_div').html(data);
                                            });
                                            //var msg = "Congratulations! Your registration is complete. You can print your registration data <a href='https://medical2.com/lms/custom/invoices/registrations/" + email + ".pdf' target='_blank'>here.</a>";
                                            //$('#err').html("<span style='font-size:bold;'>" + msg + "</span>");
                                        } // end if status
                                        else {
                                            $('#err').html("<span style='color:red;font-size:bold;'>Credit card was declined</span>");
                                        }
                                    }); // end of post
                                });
                            }, false);
                        });
            });
        }); // end of post

        $('#card-number').css('height', null);

    }); // end of document ready ...



</script>


