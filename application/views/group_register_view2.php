

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

    $(document).ready(function () {
        console.log("ready!");

        var form = document.querySelector('#checkout-form');
        var submit = document.querySelector('input[type="submit"]');
        var url = '/lms/custom/paypal/get_card_client_token.php';
        //var url = '/lms/custom/paypal/get_card_client_sandbox_token.php';
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
                                var card_holder = $('#cardholder').val();
                                var billing_email = $('#billing_email').val();
                                var billing_phone = $('#billing_phone').val();
                                hostedFieldsInstance.tokenize(function (tokenizeErr, payload) {
                                    if (tokenizeErr) {
                                        $('#err').html('Please provide correct credit card data');
                                        return;
                                    }

                                    var firstname, lastname;
                                    if (card_holder == '') {
                                        $('#err').html('Please provide card holder name');
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

                                    if (billing_email == '') {
                                        $('#err').html('Please provide billing email');
                                        return;
                                    }

                                    if (billing_phone == '') {
                                        $('#err').html('Please provide contact phone');
                                        return;
                                    }

                                    console.log('Nonce: ' + payload.nonce);

                                    $('#err').html('');
                                    $('#ajax_loading_payment').show();
                                    $('#make_group_registration_payment').prop('disabled', true);
                                    $('#make_group_registration_payment').prop('value', 'Processing request');
                                    var url = '/lms/custom/paypal/make_group_registration_payment.php';
                                    var data = $('#register_data').val();
                                    var courseid = $('#courseid').val();
                                    var slotid = $('#slotid').val();
                                    var trans = {data: data,
                                        nonce: payload.nonce,
                                        slotid: slotid,
                                        cardholder: card_holder,
                                        billing_email: billing_email,
                                        billing_phone: billing_phone,
                                        courseid: courseid};
                                    $.post(url, {trans: JSON.stringify(trans)}).done(function (status) {
                                        $('#ajax_loading_payment').hide();
                                        $('#make_group_registration_payment').prop('disabled', false);
                                        $('#make_group_registration_payment').prop('value', 'I Agree, Submit');
                                        $('#err').html("<span style='color:red;font-size:bold;'>" + status + "</span>");
                                    }); // end of post
                                });
                            }, false);
                        });
            });
        }); // end of post

        $('#card-number').css('height', null);

    }); // end of document ready ...



</script>





