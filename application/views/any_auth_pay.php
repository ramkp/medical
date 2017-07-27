<?php

echo $form;
?>

<script type="text/javascript">

    function resizeIframe(obj) {
        obj.style.height = 0;
        obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
    }

    $(document).ready(function () {

        var Base64 = {
            // private property
            _keyStr: "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",
            // public method for encoding
            encode: function (input) {
                var output = "";
                var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
                var i = 0;

                input = Base64._utf8_encode(input);

                while (i < input.length) {

                    chr1 = input.charCodeAt(i++);
                    chr2 = input.charCodeAt(i++);
                    chr3 = input.charCodeAt(i++);

                    enc1 = chr1 >> 2;
                    enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
                    enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
                    enc4 = chr3 & 63;

                    if (isNaN(chr2)) {
                        enc3 = enc4 = 64;
                    } else if (isNaN(chr3)) {
                        enc4 = 64;
                    }

                    output = output +
                            this._keyStr.charAt(enc1) + this._keyStr.charAt(enc2) +
                            this._keyStr.charAt(enc3) + this._keyStr.charAt(enc4);

                }

                return output;
            },
            // public method for decoding
            decode: function (input) {
                var output = "";
                var chr1, chr2, chr3;
                var enc1, enc2, enc3, enc4;
                var i = 0;

                input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");

                while (i < input.length) {

                    enc1 = this._keyStr.indexOf(input.charAt(i++));
                    enc2 = this._keyStr.indexOf(input.charAt(i++));
                    enc3 = this._keyStr.indexOf(input.charAt(i++));
                    enc4 = this._keyStr.indexOf(input.charAt(i++));

                    chr1 = (enc1 << 2) | (enc2 >> 4);
                    chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
                    chr3 = ((enc3 & 3) << 6) | enc4;

                    output = output + String.fromCharCode(chr1);

                    if (enc3 != 64) {
                        output = output + String.fromCharCode(chr2);
                    }
                    if (enc4 != 64) {
                        output = output + String.fromCharCode(chr3);
                    }

                }

                output = Base64._utf8_decode(output);

                return output;

            },
            // private method for UTF-8 encoding
            _utf8_encode: function (string) {
                string = string.replace(/\r\n/g, "\n");
                var utftext = "";

                for (var n = 0; n < string.length; n++) {

                    var c = string.charCodeAt(n);

                    if (c < 128) {
                        utftext += String.fromCharCode(c);
                    } else if ((c > 127) && (c < 2048)) {
                        utftext += String.fromCharCode((c >> 6) | 192);
                        utftext += String.fromCharCode((c & 63) | 128);
                    } else {
                        utftext += String.fromCharCode((c >> 12) | 224);
                        utftext += String.fromCharCode(((c >> 6) & 63) | 128);
                        utftext += String.fromCharCode((c & 63) | 128);
                    }

                }

                return utftext;
            },
            // private method for UTF-8 decoding
            _utf8_decode: function (utftext) {
                var string = "";
                var i = 0;
                var c = c1 = c2 = 0;

                while (i < utftext.length) {

                    c = utftext.charCodeAt(i);

                    if (c < 128) {
                        string += String.fromCharCode(c);
                        i++;
                    } else if ((c > 191) && (c < 224)) {
                        c2 = utftext.charCodeAt(i + 1);
                        string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
                        i += 2;
                    } else {
                        c2 = utftext.charCodeAt(i + 1);
                        c3 = utftext.charCodeAt(i + 2);
                        string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
                        i += 3;
                    }
                }
                return string;
            }
        };

        var token = $('#m2token').val();
        console.log('M2 token: ' + token);
        localStorage.setItem("token", token);
        $('#send_token').submit();
        $('#load_payment').show();
        var onLoad = true;

        var baseUrl = "https://test.authorize.net/customer/";
        var onLoad = true;
        tab = null;
        function returnLoaded() {
            console.log("Return Page Called ! ");
            showTab(tab);
        }
        window.CommunicationHandler = {};

        function parseQueryString(str) {
            var vars = [];
            var arr = str.split('&');
            var pair;
            for (var i = 0; i < arr.length; i++) {
                pair = arr[i].split('=');
                vars[pair[0]] = unescape(pair[1]);
            }
            return vars;
        }


        CommunicationHandler.onReceiveCommunication = function (argument) {
            params = parseQueryString(argument.qstr)
            parentFrame = argument.parent.split('/')[4];
            console.log(params);
            console.log(parentFrame);
            //alert(params['height']);
            $frame = null;
            switch (parentFrame) {
                case "payment"     :
                    $frame = $("#load_payment");
                    break;
            }
            switch (params['action']) {
                case "resizeWindow" 	:
                    if (parentFrame == "manage" && parseInt(params['height']) < 1150)
                        params['height'] = 1150;
                    if (parentFrame == "payment" && parseInt(params['height']) < 1000)
                        params['height'] = 1000;
                    if (parentFrame == "addShipping" && $(window).width() > 1021)
                        params['height'] = 350;
                    $frame.outerHeight(parseInt(params['height']));
                    break;
                case "successfulSave" 	:
                    $('#myModal').modal('hide');
                    location.reload(false);
                    break;
                case "cancel" 			:
                    var currTime = sessionStorage.getItem("lastTokenTime");
                    if (currTime === null || (Date.now() - currTime) / 60000 > 15) {
                        location.reload(true);
                        onLoad = true;
                    }
                    switch (parentFrame) {
                        case "payment"		:
                            sessionStorage.removeItem("HPTokenTime");
                            $('#HostedPayment').attr('src', 'about:blank');
                            break;
                    }
                    break;
                case "transactResponse"	:
                    console.log('Server response: ' + params['response']);
                    var data = Base64.encode(params['response']);
                    var url = 'https://medical2.com/register2/proceed_any_auth_payment/' + data;
                    window.location = url;
            } // end of switch
        } // end of receive message 

    }); // end of document ready function

</script>    