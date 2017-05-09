
var url='lll ....';
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
                            'width': '152px',
                            'height': '30px',
                            'border-style': 'solid',
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
                                    $('#ajax_loading_payment').show();
                                    var url = '/lms/custom/paypal/create_transaction.php';
                                    var amount = $('#amount').val();
                                    var user = $('#user').val();
                                    var email = $('#email').val();
                                    var trans = {amount: amount, nonce: payload.nonce, user: user};
                                    $.post(url, {trans: JSON.stringify(trans)}).done(function (status) {
                                        $('#ajax_loading_payment').hide();
                                        if (status) {
                                            var msg = "Payment is successful. Thank you! You can print your registration data <a href='https://medical2.com/lms/custom/invoices/registrations/" + email + ".pdf' target='_blank'>here.</a>";
                                            $('#err').html("<span style='color:black'>" + msg + "</span>");
                                        } // end if status
                                        else {
                                            $('#err').html("<span style='color:black'>Credit card was declined</span>");
                                        }
                                    }); // end of post
                                });
                            }, false)
                        });
            });
            });
          