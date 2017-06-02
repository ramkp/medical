/* -------------------- Check Browser --------------------- */

function browser() {

    var isOpera = !!(window.opera && window.opera.version);  // Opera 8.0+
    var isFirefox = testCSS('MozBoxSizing');                 // FF 0.8+
    var isSafari = Object.prototype.toString.call(window.HTMLElement).indexOf('Constructor') > 0;
    // At least Safari 3+: "[object HTMLElementConstructor]"
    var isChrome = !isSafari && testCSS('WebkitTransform');  // Chrome 1+
    // var isIE = /*@cc_on!@*/false || testCSS('msTransform'); // At least IE6

    function testCSS(prop) {
        return prop in document.documentElement.style;
    }

    if (isOpera) {

        return false;

    } else if (isSafari || isChrome) {

        return true;

    } else {

        return false;

    }

}


$(document).ready(function () {

    var codeused = 0;
    var group_users = [];

    function verify_barintree_group_registration() {
        var courseid = $('#register_courses').val();
        var slotid = $('#register_cities').val();
        var total = $('#total_group_users').val();
        var users = [];
        if (courseid == 0) {
            $('#group_err').html('Please select program');
            return false;
        } // end if courseid==0

        var addr = $('#group_addr').val();
        if (addr == '') {
            $('#group_err').html('Please provide address');
            return false;
        }

        var zip = $('#group_zip').val();
        if (zip == '') {
            $('#group_err').html('Please provide zip');
            return false;
        }

        var city = $('#group_city').val();
        if (city == '') {
            $('#group_err').html('Please provide city');
            return false;
        }

        var state = $('#state').val();
        if (state == 0) {
            $('#group_err').html('Please select state');
            return false;
        }

        var name = $('#group_name').val();
        if (name == '') {
            $('#group_err').html('Please provide group name');
            return false;
        }

        var group_check_url = "https://medical2.com/register2/is_group_exists";
        $.post(group_check_url, {name: name}).done(function (status) {
            if (status > 0) {
                $('#group_err').html('Group name already exists');
                return false;
            } // end if status>0
            else {
                if (total == 0) {
                    $('#group_err').html('Please select group participants number');
                    return false;
                }

                $('#group_err').html('');
                var groupObj = {addr: addr, zip: zip, city: city, state: state, name: name, total: total};

                var amount = $('#payment_sum').val();
                var courseObj = {courseid: courseid, slotid: slotid, total: total, amount: amount};

                for (var i = 1; i <= total; i++) {

                    var fname_elid = '#group_fname_' + i;
                    var lname_elid = '#group_lname_' + i;
                    var email_elid = '#group_email_' + i;
                    var phone_elid = '#group_phone_' + i;

                    var fname = $(fname_elid).val();
                    if (fname == '') {
                        $('#group_err').html('Please provide users first name');
                        break;
                        return false;
                    }

                    var lname = $(lname_elid).val();
                    if (lname == '') {
                        $('#group_err').html('Please provide users last name');
                        break;
                        return false;
                    }

                    var email = $(email_elid).val();
                    if (email == '') {
                        $('#group_err').html('Please provide users email');
                        break;
                        return false;
                    }

                    var email_status = is_username_exists(email);
                    if (email_status > 0) {
                        $('#group_err').html('Email ' + email + ' already in use');
                        break;
                        return false;
                    }

                    var phone = $(phone_elid).val();
                    if (phone == '') {
                        $('#group_err').html('Please provide users phone');
                        return false;
                    }
                    var user = {fname: fname, lname: lname, email: email, phone: phone};
                    users.push(user);

                } // end for

                console.log('Course Object: ' + JSON.stringify(courseObj));
                console.log('Group Object: ' + JSON.stringify(groupObj));
                console.log('Users array: ' + JSON.stringify(users));

                if (users.length == total) {
                    var reg = {course: JSON.stringify(courseObj),
                        group: JSON.stringify(groupObj),
                        users: JSON.stringify(users)};
                    var data = Base64.encode(JSON.stringify(reg));
                    var ptype = $("input:radio[name ='ptype']:checked").val();
                    if (ptype == 'card') {
                        url = 'https://medical2.com/register2/group_payment_card/' + data;
                    } // end if ptype=='card'
                    else {
                        url = 'https://medical2.com/register2/group_payment_paypal/' + data;
                    } // end else when user pays by PayPal
                    window.location.href = url;
                } // end if users.length == total
                else {
                    $('#group_err').html('Please provide users data');
                    return false;
                }
            } // end else
        }); // end of post
    }

    function  is_username_exists(email) {
        var check_url = 'https://medical2.com/register2/is_username_exists';
        $.post(check_url, {email: email}).done(function (status) {
            return status;
        });
    }

    function supports_local_storage() {
        try {
            return 'localStorage' in window && window['localStorage'] !== null;
        } // end try 
        catch (e) {
            return false;
        } // end else
    }

    var getUrlParameter = function getUrlParameter(sParam) {
        var sPageURL = decodeURIComponent(window.location.search.substring(1)),
                sURLVariables = sPageURL.split('&'),
                sParameterName,
                i;

        for (i = 0; i < sURLVariables.length; i++) {
            sParameterName = sURLVariables[i].split('=');

            if (sParameterName[0] === sParam) {
                return sParameterName[1] === undefined ? true : sParameterName[1];
            }
        }
    };

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

    $("#paypal_group_renew_submit").submit(function (event) {
        var storage = supports_local_storage();
        if (storage !== false) {
            // HTML5 storage is supported so  we could proceeed
            var courseid = $('#courseid').val();
            var groupid = $('#groupid').val();
            var psum = $('#psum').val();
            var b_fname = $('#b_fname').val();
            var b_lname = $('#b_lname').val();
            var b_phone = $('#b_phone').val();
            var b_email = $('#b_email').val();
            var b_addr = $('#b_addr').val();
            var b_city = $('#b_city').val();
            var billing_state = $('#billing_state').val();
            var b_zip = $('#b_zip').val();
            if (b_fname != '' && b_lname != '' && b_phone != '' && b_email != '' && b_addr != '' && b_city != '' && billing_state > 0 && b_zip != '') {
                $('#paypal_err').html('');
                var payment = {courseid: courseid,
                    groupid: groupid,
                    psum: psum,
                    b_fname: b_fname,
                    b_lname: b_lname,
                    b_phone: b_phone,
                    b_email: b_email,
                    b_addr: b_addr,
                    b_city: b_city,
                    b_state: billing_state,
                    b_zip: b_zip};
                var url = "/lms/custom/usrgroups/add_paypal_payer_data.php";
                $.post(url, {p: JSON.stringify(payment)}).done(function (data) {
                    var pid = data;
                    if (pid > 0) {
                        localStorage.setItem('group_renew_payer_id', pid);
                    } // end if pid>0
                    else {
                        $('#paypal_err').html('Ops, something goes wrong ...');
                    } // end else
                });
            } // end if b_fname != '' && b_lname != '' ...
            else {
                event.preventDefault();
                $('#paypal_err').html('Please provide all required fields');
            } // end else
        } // end if storage!==false
        else {
            event.preventDefault();
            $('#paypal_err').html('Your browser is not supported');
        } // end else
    });


    $("#paypal_group_payment").submit(function (event) {
        var storage = supports_local_storage();
        if (storage !== false) {
            var token = $('#token').val();
            var fname = $('#fname').val();
            var lname = $('#lname').val();
            var email = $('#email').val();
            var phone = $('#phone').val();
            if (fname != '' && lname != '' && email != '' && phone != '') {
                var regdata = $('#register_data').val();
                var buyer = {firstname: fname, lastname: lname, email: email, phone: phone}
                $('#paypal_err').html('');
                var transaction = {buyer: buyer, regdata: regdata};
                localStorage.setItem(token, JSON.stringify(transaction));
                var storedItem = localStorage.getItem(token);
                console.log('Stores item: ' + storedItem);
            } // end if name != '' && lname != '' && email != '' && phone != ''
            else {
                event.preventDefault();
                $('#paypal_err').html('Please provide user details');
            } // end else
        } // end if
        else {
            event.preventDefault();
            $('#paypal_err').html('Your browser is not supported');
        } // end else

    });

    /* ---------- Add class .active to current link ---------- */
    $('ul.main-menu li a').each(function () {

        if ($($(this))[0].href == String(window.location)) {
            $(this).parent().addClass('active');
        }
    });
    $('ul.main-menu li ul li a').each(function () {

        if ($($(this))[0].href == String(window.location)) {

            $(this).parent().addClass('active');
            $(this).parent().parent().show();
        }

    });
    /* ---------- Submenu ---------- */

    $('.dropmenu').click(function (e) {

        e.preventDefault();
        $(this).parent().find('ul').slideToggle();
    });
    /***************************************************************************
     * 
     * 
     * Service & rendeting functions
     * 
     * 
     * 
     **************************************************************************/

    $('#birth').mask("9999/99/99");
    $('#graduate_date').mask("9999");
    $('#phone1').mask("(999) 999-9999");
    $('#phone2').mask("(999) 999-9999");

    // .^\s*[A-Za-z0-9]+(?:\s+[A-Za-z0-9]+)*\s*$
    $.mask.definitions['~'] = '.^\s*[A-Za-z0-9]+(?:\s+[A-Za-z0-9]+)*\s*$';
    //$("#billing_name").mask("?aaaaaaaaaa aaaaaaaaaa");

    $('#app_date').datepicker();

    var domain = 'medical2.com';
    /***************************************************************************
     * 
     * Login form verification
     * 
     **************************************************************************/


    function check_login_form() {
        var login = $('#login_box').val();
        var password = $('#password_box').val();
        //console.log('Login: ' + login);
        //console.log('Password: ' + password);
        if (login == '' || password == '') {
            $('#login_err').html('*all fields required');
        }
        if (login != '' && password != '') {
            var url = "https://" + domain + "/functionality/php/login.php";
            $.post(url, {login: login, password: password})
                    .done(function (data) {
                        if (data > 0) {
                            $('#login_err').html('');
                            $('#login_form').submit();
                        } // end if data>0
                        else {
                            $('#login_err').html('Incorrect login or password');
                            // event.preventDefault();
                        } // end else
                    });

        } // end if login != '' && password != ''
    }


    /*
     * $( "#login_form" ).submit(function( event ) { var login =
     * $('#login_box').val(); var password = $('#passsword_box').val();
     * console.log('Login: '+login); console.log('Password: '+password); if
     * (login == '' || password == '') { $('#login_err').html('*all fields
     * required'); } if (login != '' && password != '') { var url = "https://" +
     * domain + "/functionality/php/login.php"; $.post( url, { login: login,
     * password: password }) .done(function( data ) { if (data>0) {
     * $('#login_form').submit(); } // end if data>0 else {
     * $('#login_err').html('Incorrect login or password');
     * event.preventDefault(); } // end else }); } // end if login != '' &&
     * password != '' }); // end of $( "#login_form" ).submit(function( event )
     */

    /***************************************************************************
     * 
     * Verify users upload form and start if any
     * 
     **************************************************************************/

    function verify_users_upload_form() {
        var url = "https://" + domain + "/functionality/php/upload_users_file.php";
        var file_data = $('#files').prop('files');
        console.log('File: ' + file_data);
        if (file_data == '' || file_data.length == 0) {
            $('#upload_err').html('Please select file with users data');
        } // end if file_data == ''
        else {
            var form_data = new FormData();
            $.each(file_data, function (key, value) {
                form_data.append(key, value);
            });
            $('#ajax_loading_group_file').show();
            $.ajax({
                url: url,
                data: form_data,
                processData: false,
                contentType: false,
                type: 'POST',
                success: function (data) {
                    console.log(data);
                    if (data > 0) {
                        // It means we have valid users in file >0
                        $('#upload_err').html('');
                        var selected_course = $('#register_courses').val();
                        var courseid = selected_course;
                        // $.post(course_url, request).done(function (courseid)
                        // {                        
                        var addr = $('#group_addr').val();
                        var inst = $('#group_inst').val();
                        var zip = $('#group_zip').val();
                        var city = $('#group_city').val();
                        var state = $('#group_state').val();
                        var group_name = $('#group_name').val();
                        var come_from = $('#come_from_group').text().trim();
                        var slotid = $('#register_cities').val();
                        var grpoup_data = {courseid: courseid,
                            slotid: slotid,
                            addr: addr,
                            inst: inst,
                            zip: zip,
                            city: city,
                            state: state,
                            come_from: come_from,
                            tot_participants: data,
                            group_name: group_name};
                        var group_url = "https://" + domain + "/functionality/php/group_signup_by_file.php";
                        var request = {group_common_section: JSON.stringify(grpoup_data)};
                        $.post(group_url, request).done(function (data) {
                            $('#ajax_loading_group_file').hide();
                            var el = $('#personal_payment_details').length;
                            if (el == 0) {
                                $('#group_common_section').append(data);
                            }
                        });
                        // }); // end of $.post(course_url, request)
                    } // end if data > 0
                    else {
                        $('#upload_err').html(data);
                    } // end else
                } // end of success
            }); // end of $.ajax ..
        } // end else
    }

    /***************************************************************************
     * 
     * Show Gallery page
     * 
     **************************************************************************/

    function get_gallery_page() {
        $.post("https://" + domain + "/functionality/php/gallery.php", function (data) {
            $('#instructions').hide();
            $("#page").html(data);
        });
    }

    /***************************************************************************
     * 
     * Show program items
     * 
     **************************************************************************/
    function show_program_items(cat_name) {
        $.post("https://" + domain + "/functionality/php/get_programs_list.php", {cat_name: cat_name})
                .done(function (data) {
                    $('#instructions').hide();
                    $("#page").html(data);
                });
        // console.log('Triggered click on btn-navbar');
        $(".btn-navbar").trigger("click");
    }

    /***************************************************************************
     * 
     * Show school page and Google Map
     * 
     **************************************************************************/

    function show_school_page(cat_name) {
        $.post("https://" + domain + "/functionality/php/get_school_page.php", {cat_name: cat_name})
                .done(function (data) {
                    $('#instructions').hide();
                    $("#page").html(data);
                    refresh_map();
                });
    }

    /***************************************************************************
     * 
     * Show Google Map
     * 
     **************************************************************************/

    function refresh_map() {
        var url = "/lms/custom/google_map/refresh.php";
        var category_id = 5; // Nursing school category id
        var request = {category_id: category_id};
        $.post(url, request).done(function (data) {
            var $obj_data = $.parseJSON(data);
            // Create a map object and specify the DOM element for display.
            var map = new google.maps.Map(document.getElementById('map'), {
                scrollwheel: false,
                zoom: 8
            }); // end var map
            var latLngs = [];
            var bounds = new google.maps.LatLngBounds();
            $.each($obj_data, function (i, m) {
                var myLatLng = new google.maps.LatLng(m.lat, m.lng);
                latLngs[i] = myLatLng
                var marker = new google.maps.Marker({
                    position: myLatLng,
                    map: map,
                    title: m.marker_text,
                    zIndex: i
                }); // end marker
                bounds.extend(marker.position);
                google.maps.event.addListener(marker, 'click', (function (marker, i) {
                    return function () {
                        var infowindow = new google.maps.InfoWindow();
                        var iWC = infowindow.getContent();
                        iWC = m.info;
                        infowindow.setContent(iWC);
                        infowindow.open(map, marker);
                    }
                })(marker, i));
            }) // end each
            map.fitBounds(bounds);
        }); // post(url, request).done(function (data)
    }

    /***************************************************************************
     * 
     * Show FAQ page
     * 
     **************************************************************************/
    function get_faq_page() {
        $.post("https://" + domain + "/functionality/php/get_faq_page.php", function (data) {
            $('#instructions').hide();
            $("#page").html(data);
        });
    }

    /***************************************************************************
     * 
     * Show Testimonial page
     * 
     **************************************************************************/

    function get_testimonial_page() {
        $.post("https://" + domain + "/functionality/php/get_testimonial_page.php", function (data) {
            $('#instructions').hide();
            $("#page").html(data);
        });
    }

    /***************************************************************************
     * 
     * Certificate verification form
     * 
     **************************************************************************/

    function get_certificate_verification_form() {
        $.post("https://" + domain + "/functionality/php/get_certificate_verification_form.php", function (data) {
            $('#instructions').hide();
            $("#page").html(data);
        });
    }

    /***************************************************************************
     * 
     * Get users upload form
     * 
     **************************************************************************/

    function get_users_upload_form() {
        $.post("https://" + domain + "/functionality/php/get_users_upload_form.php", function (data) {
            $('#participants_details').remove();
            $('#group_common_section').append(data);
        });
    }

    /***************************************************************************
     * 
     * Verify group general part and proceed to file upload
     * 
     **************************************************************************/

    function verify_group_general_part() {

        var courseid = $('#register_courses').val();
        if (courseid != 0) {
            $('#program_err').html('');
            $('#group_common_errors').html('');
            // var course_url = "https://" + domain +
            // "/functionality/php/get_course_id.php";
            // var request = {course_name: course_name};
            // $.post(course_url, request).done(function (courseid) {
            console.log('Course id: ' + courseid);
            var addr = $('#group_addr').val();
            var inst = $('#group_inst').val();
            var zip = $('#group_zip').val();
            var city = $('#group_city').val();
            var state = $('#group_state').val();
            var group_name = $('#group_name').val();
            var slotid = $('#register_cities').val();
            if (addr == '') {
                $('#group_common_errors').html('Please provide address');
                return false;
            }

            if (inst == '') {
                inst = '---';
            }

            if (zip == '') {
                $('#group_common_errors').html('Please provide zip code');
                return false;
            }

            if (city == '') {
                $('#group_common_errors').html('Please provide city');
                return false;
            }


            if (state == 0) {
                $('#group_common_errors').html('Please select state');
                return false;
            }

            if (group_name == '') {
                $('#group_common_errors').html('Please provide group name');
                return false;
            }

            var come_from = $('#come_from_group').val();
            if (come_from == 0) {
                $('#group_common_errors').html('');
                $('#group_common_errors').html('How did you hear about us?');
                return false;
            }

            if (addr != '' && inst != '' && zip != '' && zip != '' && city != '' && state != '' && group_name != '') {
                // Check is group name exist?
                var course_url = "https://" + domain + "/functionality/php/is_group_exist.php";
                var request = {group_name: group_name};
                $.post(course_url, request).done(function (data) {
                    if (data > 0) {
                        $('#group_common_errors').html('Group name already exists');
                    } else {
                        // Everything is fine - show participants section
                        get_users_upload_form();
                    }
                });
            } // end if addr!='' && inst!=''
            // }); // end if $.post
        } // end if course_name != 'Program' && course_name != '' ...
        else {
            $('#program_err').html('Please select program');
            $('#group_common_errors').html('Please select program');
        }
    }

    /***************************************************************************
     * 
     * Private group request form verification
     * 
     **************************************************************************/

    function submit_private_group() {

        var group_fio = $('#group_fio').val();
        var group_city = $('#group_city').val();
        var group_phone = $('#group_phone').val();
        var group_budget = $('#group_budget').val();
        var group_company = $('#group_company').val();
        var group_email = $('#group_email').val();
        var courses = $('#courses').val();
        var group_request = $('#group_request').val();
        var people_num = $('#people_num').val();
        var state = $('#group_state').val();

        if (group_fio == '') {
            $('#private_err').html('Please provide firstname and lastname');
            return false;
        }

        if (group_city == '') {
            $('#private_err').html('Please provide city ');
            return false;
        }

        if (group_phone == '') {
            $('#private_err').html('Please provide phone ');
            return false;
        }


        if (group_budget == '') {
            // $('#private_err').html('Please provide estimate budget ');
            group_budget = 0;
            // return false;
        }

        if (people_num == 0) {
            $('#private_err').html('Please provide people num in the group ');
            return false;
        }

        if (group_company == '') {
            $('#private_err').html('Please provide company ');
            return false;
        }

        if (state == 0) {
            $('#private_err').html('Please select state ');
            return false;
        }

        if (group_email == '') {
            $('#private_err').html('Please provide email ');
            return false;
        }

        if (validateEmail(group_email) != true) {
            $('#private_err').html('Please provide valid email ');
        }

        if (courses == 0) {
            $('#private_err').html('Please select program ');
            return false;
        }

        if (group_request == '') {
            group_request = 'n/a';
        }

        var url = "/functionality/php/submit_private_group_request.php";
        var group_request = {group_fio: group_fio,
            group_city: group_city,
            group_phone: group_phone,
            group_budget: group_budget,
            group_company: group_company,
            group_email: group_email,
            courses: courses,
            people_num: people_num,
            state: state,
            group_request: group_request};
        $('#private_err').html('');
        var request = {request: JSON.stringify(group_request)};
        $.post(url, request).done(function (data) {
            $(".form_div").html(data);
        });
    }

    /***************************************************************************
     * 
     * Submit verify certification form
     * 
     **************************************************************************/
    function submit_verify_cert_from() {
        var fname = $('#fname').val();
        var lname = $('#lname').val();

        if (fname != '' && lname != '') {
            var url = "https://" + domain + "/lms/custom/certificates/verify_certificate.php";
            var request = {fname: fname, lname: lname};
            $.post(url, request).done(function (data) {
                $("#cert_err").html("<span style='color:green;'>" + data + "</span>");
            });
        } // end if cert_fio!='' && cert_no!=''
        else {
            $('#cert_err').html('Please provide your First and Last name');
        } // end else
    }


    /***************************************************************************
     * 
     * Show courses inside category
     * 
     **************************************************************************/
    function get_category_course(category_id) {
        var url = "https://" + domain + "/functionality/php/get_selected_course.php";
        var request = {cat_id: category_id};
        $.post(url, request).done(function (data) {
            $("#cat_course").html(data);
        });
    }

    /***************************************************************************
     * 
     * Show Register page w/o params
     * 
     **************************************************************************/
    function get_register_page() {
        $.post("https://" + domain + "/functionality/php/get_register_page.php", function (data) {
            $('#instructions').hide();
            $("#page").html(data);
        });
    }

    function verify_personal_payment_section() {
        var sum, firstname, lastname;
        var user_group = $('#user_group').val();
        console.log('Group: ' + user_group);
        if (user_group != '') {
            sum = $('#group_payment_sum').val();
        } // end if
        else {
            sum = $('#payment_sum').val();
        }

        console.log('Sum: ' + sum);

        var dashboard = $('#dashboard').val();
        var card = $('#card_type').text();
        var card_type = card.trim();
        var email = $('#email').val();
        var card_no = $('#card_no').val();
        var card_holder = $('#card_holder').val();
        var card_year = $('#card_year').val();
        var card_month = $('#card_month').val();
        var bill_addr = $('#bill_addr').val();
        var bill_city = $('#bill_city').val();
        var bill_zip = $('#bill_zip').val();
        var bill_email = $('#bill_email').val();
        var userid = $('#userid').val();
        var courseid = $('#courseid').val();
        var participants = $('#participants').val();
        var cvv = $('#bill_cvv').val();
        var renew = $('#renew').val();
        var state = $('#bill_state').val();
        if (card_type == 'Card type') {
            $('#personal_payment_err').html('Please select card type');
            return false;
        }

        console.log('Card no: ' + card_no);

        if (card_no == '') {
            $('#personal_payment_err').html('Please provide card number');
            return false;
        }

        if (card_holder == '') {
            $('#personal_payment_err').html('Please provide card holder name');
            return false;
        }

        if (card_holder != '') {
            // Remove double spaces between words
            var clean_holder = card_holder.replace(/\s\s+/g, ' ');
            var names_arr = clean_holder.split(" ");

            console.log('names array length: ' + names_arr.length);

            if (names_arr.length == 1) {
                $('#personal_payment_err').html('Please provide correct card holder name separated by space');
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
                    $('#personal_payment_err').html('Please provide correct card holder name separated by space');
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
                    $('#personal_payment_err').html('Please provide correct card holder name separated by space');
                    return;
                }
            } // end if names_arr.length == 3

        } // end if card_holder != ''

        if (card_year == '--') {
            $('#personal_payment_err').html('Please select card expiration year');
            return false;
        }

        if (card_month == '--') {
            $('#personal_payment_err').html('Please select card expiration month');
            return false;
        }

        if (bill_addr == '') {
            $('#personal_payment_err').html('Please provide billing address');
            return false;
        }

        if (bill_city == '') {
            $('#personal_payment_err').html('Please provide billing city');
            return false;
        }

        if (bill_zip == '') {
            $('#personal_payment_err').html('Please provide billing zip code');
            return false;
        }

        if (bill_email == '') {
            $('#personal_payment_err').html('Please provide contact email');
            return false;
        }

        if (validateEmail(bill_email) != true) {
            $('#personal_payment_err').html('Please provide correct contact email');
            return false;
        }

        if (cvv == '') {
            $('#personal_payment_err').html('Please provide card cvv code');
            return false;
        }

        if (state == 0) {
            $('#personal_payment_err').html('Please select state');
            return false;
        }

        if (!$('#policy_checkbox').prop('checked')) {
            $('#personal_payment_err').html('Please Agree with Terms and Conditions');
            return false;
        }

        if (card_type != 'Card type' && card_no != '' && card_holder != '' && card_year != '--' && card_month != '--' && bill_addr != '' && bill_city != '' && bill_zip != '' && bill_email != '' && validateEmail(bill_email) == true) {
            $('#personal_payment_err').html('');
            var card = {sum: sum,
                dashboard: dashboard,
                email: email,
                userid: userid,
                courseid: courseid,
                cvv: cvv,
                participants: participants,
                card_type: card_type,
                card_no: card_no,
                card_holder: clean_holder,
                card_year: card_year,
                card_month: card_month,
                bill_addr: bill_addr,
                bill_city: bill_city,
                bill_zip: bill_zip,
                state: state,
                renew: renew,
                user_group: user_group,
                bill_email: bill_email};

            console.log('Payment object: ' + JSON.stringify(card));


            var url = "https://" + domain + "/functionality/php/make_stub_payment.php";
            var request = {card: JSON.stringify(card)};
            $('#ajax_loading_payment').show();
            $.post(url, request).done(function (data) {
                $('#ajax_loading_payment').hide();
                //console.log('Server response: '+data);
                $('.form_div').html(data);
            }); // end of post


        } // end if card_type != 'Card type' && card_no!='' ...
    }

    function make_already_registered_payment() {
        var differ, sum;
        var user_group = $('#user_group').val();
        if (user_group != '') {
            sum = $('#group_payment_sum').val();
        } // end if
        else {
            sum = $('#payment_sum').val();
        }
        var userid = $('#userid').val();
        var courseid = $('#courseid').val();
        var card_holder = $('#card_holder').val();
        var card_no = $('#card_no').val();
        var cvv = $('#bill_cvv').val();
        var card_month = $('#card_month').val();
        var card_year = $('#card_year').val();
        var renew = $('#renew').val();
        var slotid = $('#slotid').val();
        var b_fname = $('#b_fname').val();
        var b_lname = $('#b_lname').val();

        /*
         if (card_holder == '') {
         $('#personal_payment_err').html('Please provide card holder name');
         return false;
         }
         
         if (card_holder != '') {
         // Remove double spaces between words
         var clean_holder = card_holder.replace(/\s\s+/g, ' ');
         var names_arr = clean_holder.split(" ");
         
         console.log('names array length: ' + names_arr.length);
         
         if (names_arr.length == 1) {
         $('#personal_payment_err').html('Please provide correct card holder name separated by space');
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
         $('#personal_payment_err').html('Please provide correct card holder name separated by space');
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
         $('#personal_payment_err').html('Please provide correct card holder name separated by space');
         return;
         }
         } // end if names_arr.length == 3
         
         } // end if card_holder != ''
         */

        if (b_fname == '') {
            $('#personal_payment_err').html('Please provide card holder firstname');
            return false;
        }

        if (b_lname == '') {
            $('#personal_payment_err').html('Please provide card holder lastname');
            return false;
        }


        if (card_no == '') {
            $('#personal_payment_err').html('Please provide card number');
            return false;
        }

        if (cvv == '') {
            $('#personal_payment_err').html('Please provide card cvv code');
            return false;
        }

        if (card_year == '--') {
            $('#personal_payment_err').html('Please select card expiration year');
            return false;
        }

        if (card_month == '--') {
            $('#personal_payment_err').html('Please select card expiration month');
            return false;
        }

        if ($('#da').prop('checked')) {

            var addr = $('#addr2').val();
            var city = $('#city2').val();
            var state = $('#bill_state').val();
            var country = $('#pcountry').val();
            var zip = $('#zip2').val();
            var email = $('#email2').val();

            if (addr == '') {
                $('#personal_payment_err').html('Please provide billing address');
                return false;
            }

            if (city == '') {
                $('#personal_payment_err').html('Please provide city');
                return false;
            }

            if (state == 0) {
                $('#personal_payment_err').html('Please select state');
                return false;
            }

            if (zip == '') {
                $('#personal_payment_err').html('Please provide zip');
                return false;
            }

            if (email == '') {
                $('#personal_payment_err').html('Please provide contact email');
                return false;
            }

            differ = 1;

        } // end if 

        $('#personal_payment_err').html('');

        var card = {userid: userid,
            courseid: courseid,
            b_fname: b_fname,
            b_lname: b_lname,
            card_no: card_no,
            cvv: cvv,
            slotid: slotid,
            renew: renew,
            card_month: card_month,
            card_year: card_year,
            sum: sum,
            differ: differ,
            addr: addr,
            city: city,
            state: state,
            zip: zip,
            country: country,
            email: email};

        //console.log('Card Object: ' + JSON.stringify(card));

        var url = "/functionality/php/make_registered_payment.php";
        var request = {card: JSON.stringify(card)};
        $('#ajax_loading_payment').show();
        $.post(url, request).done(function (data) {
            $('#ajax_loading_payment').hide();
            $('.form_div').html(data);
        }); // end of post


    }

    function verify_group_payment_section() {
        var dashboard = $('#dashboard').val();
        var card = $('#card_type').text();
        var card_type = card.trim();
        var sum = $('#payment_sum').val();
        var email = $('#email').val();
        var card_no = $('#card_no').val();
        var card_holder = $('#card_holder').val();
        var card_year = $('#card_year').val();
        var card_month = $('#card_month').val();
        var bill_addr = $('#bill_addr').val();
        var bill_city = $('#bill_city').val();
        var bill_zip = $('#bill_zip').val();
        var bill_email = $('#bill_email').val();
        var userid = $('#userid').val();
        var courseid = $('#courseid').val();
        var participants = $('#participants').val();
        var cvv = $('#bill_cvv').val();
        var state = $('#bill_state').val();
        if (card_type == 'Card type') {
            $('#personal_payment_err').html('Please select card type');
            return false;
        }

        console.log('Card no: ' + card_no);

        if (card_no == '') {
            $('#personal_payment_err').html('Please provide card number');
            return false;
        }

        if (card_holder == '') {
            $('#personal_payment_err').html('Please provide card holder name');
            return false;
        }

        if (card_holder != '') {
            var names_arr = card_holder.split(" ");
            console.log('Billing name: ' + card_holder);
            console.log('Billing firstname: ' + names_arr[0]);
            console.log('Billing lastname: ' + names_arr[1]);
            if (typeof (names_arr[1]) === "undefined") {
                $('#personal_payment_err').html('Please provide correct card holder name separated by space');
                return;
            }
        }

        if (card_year == '--') {
            $('#personal_payment_err').html('Please select card expiration year');
            return false;
        }

        if (card_month == '--') {
            $('#personal_payment_err').html('Please select card expiration month');
            return false;
        }

        if (bill_addr == '') {
            $('#personal_payment_err').html('Please provide billing address');
            return false;
        }

        if (bill_city == '') {
            $('#personal_payment_err').html('Please provide billing city');
            return false;
        }

        if (bill_zip == '') {
            $('#personal_payment_err').html('Please provide billing zip code');
            return false;
        }

        if (bill_email == '') {
            $('#personal_payment_err').html('Please provide contact email');
            return false;
        }

        if (validateEmail(bill_email) != true) {
            $('#personal_payment_err').html('Please provide correct contact email');
            return false;
        }

        if (cvv == '') {
            $('#personal_payment_err').html('Please provide card cvv code');
            return false;
        }

        if (state == 0) {
            $('#personal_payment_err').html('Please select state');
            return false;
        }

        if (!$('#policy_checkbox').prop('checked')) {
            $('#personal_payment_err').html('Please Agree with Terms and Conditions');
            return false;
        }

        var user_group = $('#user_group').val();
        if (card_type != 'Card type' && card_no != '' && card_holder != '' && card_year != '--' && card_month != '--' && bill_addr != '' && bill_city != '' && bill_zip != '' && bill_email != '' && validateEmail(bill_email) == true) {
            $('#personal_payment_err').html('');
            var card = {sum: sum,
                dashboard: dashboard,
                email: email,
                userid: userid,
                courseid: courseid,
                cvv: cvv,
                participants: participants,
                card_type: card_type,
                card_no: card_no,
                card_holder: card_holder,
                card_year: card_year,
                card_month: card_month,
                bill_addr: bill_addr,
                bill_city: bill_city,
                bill_zip: bill_zip,
                state: state,
                user_group: user_group,
                bill_email: bill_email};
            var url = "https://" + domain + "/functionality/php/make_stub_payment.php";
            var request = {card: JSON.stringify(card)};
            $('#ajax_loading_payment').show();
            $.post(url, request).done(function (data) {
                $('#ajax_loading_payment').hide();
                console.log('Server response: ' + data);
                $('.form_div').html(data);
            }); // end of post
        } // end if card_type != 'Card type' && card_no!='' ...
    }

    /***************************************************************************
     * 
     * Group registration block
     * 
     **************************************************************************/
    var group_selected;
    var dialog_loaded;
    function get_group_registration_block() {
        var tot_participants = $('#participants').val();
        $('#personal_section').hide();
        if (tot_participants == 0) {
            $('#type_err').html('Please select number of group participants');
            group_selected = true;
        } else {
            var url = "https://" + domain + "/functionality/php/get_group_registration_form.php";
            var request = {tot_participants: tot_participants};
            $.post(url, request).done(function (data) {
                var el = $("#group_common_section").length;
                if (el == 0) {
                    $('#type_section').append(data);
                }
            });
        }
    }

    /***************************************************************************
     * 
     * Manual Group registration form
     * 
     **************************************************************************/

    function get_manual_group_registration_form(tot_participants) {
        var url = "https://" + domain + "/functionality/php/get_group_manual_registration_form.php";
        var request = {tot_participants: tot_participants};
        $.post(url, request).done(function (data) {
            $('#participants_details').remove();
            $('#group_common_section').append(data);
        });
    }

    /***************************************************************************
     * 
     * Verify Manual Group registration form
     * 
     **************************************************************************/

    function very_participants_form(tot_participants) {

        var err = 0;
        var users = new Array();
        var courseid = $('#register_courses').val();
        var addr = $('#group_addr').val();
        var inst = $('#group_inst').val();
        var zip = $('#group_zip').val();
        var city = $('#group_city').val();
        var state = $('#group_state').val();
        var group_name = $('#group_name').val();
        var come_from = $('#come_from_group').val();
        var slotid = $('#register_cities').val();
        for (i = 0; i <= tot_participants; i++) {

            var first_name_id = '#first_name_' + i;
            var last_name_id = '#last_name_' + i
            var email_id = '#email_' + i;
            var phone_id = '#phone_' + i;
            var first_name = $(first_name_id).val();
            var last_name = $(last_name_id).val();
            var email = $(email_id).val();
            var phone = $(phone_id).val();
            if (first_name == '' || last_name == '' || email == '' || validateEmail(email) == false || phone == '') {
                err++;
            } // end if first_name=='' || last_name==''
            if (first_name != '' && last_name != '' && email != '' && validateEmail(email) == true && phone != '') {
                var user = {first_name: first_name, last_name: last_name, email: email, phone: phone, come_from: come_from, slotid: slotid};
                users.push(user);
            } // end if first_name != '' && last_name != ''
        } // end for
        console.log('Errors counter: ' + err);
        if (err > 1) {
            $('#group_manual_form_err').html('Please provide all required fields and valid emails');
        } // end if err > 0
        else {
            // Everything is fine - show payment form
            $('#group_manual_form_err').html('');
            // var course_url = "https://" + domain +
            // "/functionality/php/get_course_id.php";
            // var request = {course_name: course_name};
            $('#ajax_loading_group').show();
            // $.post(course_url, request).done(function (courseid) {
            console.log('Course id: ' + courseid);
            var group_common_section = {
                courseid: courseid,
                slotid: slotid,
                addr: addr,
                inst: inst,
                zip: zip,
                city: city,
                state: state,
                come_from: come_from,
                group_name: group_name};
            var signup_url = "https://" + domain + "/functionality/php/group_signup.php";
            var signup_request = {group_common_section: JSON.stringify(group_common_section),
                users: JSON.stringify(users),
                tot_participants: tot_participants};
            $.post(signup_url, signup_request).done(function (data) {
                // console.log(data);
                // Show payment section
                $('#ajax_loading_group').hide();
                /*
                 * var el = $('#personal_payment_details').length; if (el == 0) {
                 * $('#participants_details').append(data); }
                 */
                $('#participants_details').append(data);
            }).fail(function (data) {
                console.log(data);
                $('#personal_err').html('Ops something goes wrong ...');
            }); // end of fail(function (data)
            // }); // end of $.post(course_url, request)
        } // end else
    }

    function verify_group_common_section() {
        var tot_participants = $('#participants').val();
        var selected_course = $('#register_courses').val();
        var courseid = selected_course;

        if (courseid != 0) {
            $('#program_err').html('');
            $('#group_common_errors').html('');
            console.log('Course id: ' + courseid);
            var addr = $('#group_addr').val();
            var inst = $('#group_inst').val();
            var zip = $('#group_zip').val();
            var city = $('#group_city').val();
            var state = $('#group_state').val();
            var group_name = $('#group_name').val();
            if (addr == '') {
                $('#group_common_errors').html('Please provide address');
                return false;
            }

            if (inst == '') {
                inst = '---';
            }

            if (zip == '') {
                $('#group_common_errors').html('Please provide zip code');
                return false;
            }

            if (city == '') {
                $('#group_common_errors').html('Please provide city');
                return false;
            }

            if (state == 0) {
                $('#group_common_errors').html('Please select state');
                return false;
            }

            if (group_name == '') {
                $('#group_common_errors').html('Please provide group name');
                return false;
            }

            var come_from = $('#come_from_group').val();
            if (come_from == 0) {
                $('#group_common_errors').html('How did you hear about us?');
                return false;
            }

            if (addr != '' && inst != '' && zip != '' && zip != '' && city != '' && state != '' && group_name != '') {
                // Check is group name exist?
                var course_url = "https://" + domain + "/functionality/php/is_group_exist.php";
                var request = {group_name: group_name};
                $.post(course_url, request).done(function (data) {
                    if (data > 0) {
                        $('#group_common_errors').html('Group name already exists');
                    } else {
                        // Everything is fine - show participants section
                        get_manual_group_registration_form(tot_participants, courseid);
                    }
                });
            } // end if addr!='' && inst!=''
        } // end if courseid!=0
        else {
            $('#program_err').html('Please select program');
            $('#group_common_errors').html('Please select program');
        }
    }

    /***************************************************************************
     * 
     * Get private group form
     * 
     **************************************************************************/

    function get_private_group_form() {
        $.post("https://" + domain + "/functionality/php/get_private_groups_form.php", function (data) {
            $('#instructions').hide();
            $("#page").html(data);
        });
    }

    /***************************************************************************
     * 
     * Show register form with selected program
     * 
     **************************************************************************/

    function get_selected_program_register_form(courseid) {
        console.log('Course id: ' + courseid);
        var url = "https://" + domain + "/functionality/php/get_selected_program_register_form.php";
        var request = {courseid: courseid};
        $.post(url, request).done(function (data) {
            $('#page').html(data);
        });
    }

    function get_category_items_in_state() {
        var selected_category = $('#categories').val();
        var category_name = selected_category.trim();
        var selected_state = $('#register_cities').val();
        var state_name = selected_state.trim();
        console.log('Course category: ' + category_name);
        console.log('Course state: ' + state_name);
        if (category_name != 0 && state_name != 0) {
            var url = "https://" + domain + "/functionality/php/get_state_category_items.php";
            var request = {category_name: category_name, state_name: state_name};
            $.post(url, request).done(function (data) {
                $('#cat_course').html(data);
            });
        } // end if category_name!='Program type' && state_name!='State/City'
    }

    function show_state_programs(stateid) {
        var page = window.location.href;
        if (page.indexOf("schedule") > 0) {
            var url = "https://" + domain + "/functionality/php/show_state_programs.php";
            var request = {stateid: stateid};
            $('#ajax_loading_schedule').show();
            $.post(url, request).done(function (data) {
                $('#ajax_loading_schedule').hide();
                $('#program_section').html(data);
            });
        } // end if page.indexOf("schedule") > 0
    }

    /***************************************************************************
     * 
     * Verify Manual Group registration form
     * 
     **************************************************************************/


    function validateEmail(email) {
        var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
        return re.test(email);
    }

    var personal_registration_obj;

    function verify_personal_manual_registration_form() {

        var come_from = $('#come_from').val();
        console.log('Come from: ' + come_from);
        var courseid = $('#register_courses').val();
        console.log('Courses dropdown: ' + courseid);
        if (courseid != 0) {
            $('#program_err').html('');
            $('#personal_err').html('');
            var first_name = $('#first_name').val();
            var last_name = $('#last_name').val();
            var email = $('#email').val();
            var phone = $('#phone').val();
            var addr = $('#addr').val();
            var inst = $('#inst').val();
            var zip = $('#zip').val();
            var city = $('#city').val();
            var state = $('#state').val();
            var country = $('#country').val();
            var slotid = $('#register_cities').val();

            if (first_name == '') {
                $('#personal_err').html('Please provide firstname');
                return false;
            }

            if (last_name == '') {
                $('#personal_err').html('Please provide lastname');
                return false;
            }

            if (addr == '') {
                $('#personal_err').html('Please provide address');
                return false;
            }

            if (city == '') {
                $('#personal_err').html('Please provide city');
                return false;
            }


            if (state == 0) {
                $('#personal_err').html('Please select state');
                return false;
            }

            if (country == 0) {
                $('#personal_err').html('Please select country');
                return false;
            }

            if (zip == '') {
                $('#personal_err').html('Please provide zip');
                return false;
            }

            if (inst == '') {
                inst = '---';
            }

            if (phone == '') {
                $('#personal_err').html('Please provide phone');
                return false;
            }

            if (email == '') {
                $('#personal_err').html('Please provide email');
                return false;
            }

            if (!validateEmail(email)) {
                $('#personal_err').html('Please provide valid email');
                return false;
            }

            if (come_from == 0) {
                $('#personal_err').html('How did you hear about us?');
                return false;
            }

            if (first_name != '' && last_name != '' && email != '' && phone != '' && addr != '' && inst != '' && zip != '' && city != '') {

                // Check is email exists?
                var url = "https://" + domain + "/functionality/php/is_email_exists.php";
                var request = {email: email};
                $.post(url, request).done(function (data) {
                    console.log('Server response: ' + data);
                    if (data > 0) {
                        $('#personal_err').html('Email already in use');
                    } // end if data>0
                    else {
                        // Everything is fine post data and show payment section
                        console.log('Slot ID: ' + slotid);
                        $('#personal_err').html('');
                        $('#ajax_loading_personal').show();
                        var user = {
                            courseid: courseid,
                            slotid: slotid,
                            come_from: come_from,
                            first_name: first_name,
                            last_name: last_name,
                            email: email,
                            phone: phone,
                            addr: addr,
                            inst: inst,
                            zip: zip,
                            city: city,
                            state: state,
                            country: country};
                        console.log("User: " + JSON.stringify(user));
                        personal_registration_obj = JSON.stringify(user);
                        var signup_url = "https://" + domain + "/functionality/php/single_signup.php";
                        var signup_request = {user: JSON.stringify(user)};
                        $.post(signup_url, signup_request).done(function (data) {
                            console.log(data);
                            // Show payment section
                            $('#ajax_loading_personal').hide();
                            var el = $('#personal_payment_details').length;
                            if (el == 0) {
                                $('#personal_section').append(data);
                            }
                        }).fail(function (data) {
                            console.log(data);
                            $('#personal_err').html('Ops something goes wrong ...');
                        }); // end of fail(function (data)
                    } // end else when email is not used
                }); // end if $.post(url, request))
            } // end if first_name != '' && last_name != '' ...
        } // end if courseid!=0
        else {
            $('#program_err').html('Please select program');
            // $('#personal_err').html('Please select program');
        } // end else
    }

    function verify_group_owner_detailes() {
        var firstname = $('#group_owner_firstname').val();
        var lastname = $('#group_owner_lastname').val();
        var email = $('#group_owner_email').val();
        var courseid = $('#course_id').val();
        if (firstname == '') {
            $('#offline_group_owner_error').html('Please provide firstname');
            return false;
        }

        if (lastname == '') {
            $('#offline_group_owner_error').html('Please provide lastname');
            return false;
        }

        if (email == '') {
            $('#offline_group_owner_error').html('Please provide email');
            return false;
        }

        if (validateEmail(email) != true) {
            $('#offline_group_owner_error').html('Please provide valid email');
            return false;
        }

        if (firstname != '' && lastname != '' && email != '' && validateEmail(email) == true) {
            $('#offline_group_owner_error').html('');
            var url = "https://" + domain + "/functionality/php/send_group_invoice.php";
            var request = {firstname: firstname, lastname: lastname, email: email, courseid: courseid};
            $.post(url, request).done(function (data) {
                var el = $('#payment_detailes').length;
                $('#invoice_detaills').remove();
                alert(data);
            });
        }
    }

    function submit_contact_form() {
        var firstname = $('#firstname').val();
        var lastname = $('#lastname').val();
        var email = $('#email').val();
        var phone = $('#phone').val();
        var message = $('#message').val();
        var captcha = $('#captcha').val();
        var program = $('#program').val();
        if (firstname != '' && lastname != '' && email != '' && validateEmail(email) == true && phone != '' && message != '' && captcha != '' && program != 0) {
            var url = "https://" + domain + "/functionality/php/verify_captcha.php";
            var request = {captcha: captcha};
            $.post(url, request).done(function (data) {
                if (data > 0) {
                    // Captcha is correct we can submit data
                    $('#contact_result').html('');
                    $('#firstname').val('');
                    $('#lastname').val('');
                    $('#email').val('');
                    $('#phone').val('');
                    $('#message').val('');
                    $('#captcha').val('');
                    var url = "https://" + domain + "/functionality/php/send_contact_request.php";
                    var request = {firstname: firstname, lastname: lastname, email: email, phone: phone, message: message, program: program};
                    $.post(url, request).done(function (data) {
                        $('#contact_result').html("<span style='color:red;'>" + data + "</span>");
                    });
                } // end if data>0
                else {
                    $('#contact_result').html("<span style='color:red;'>Captcha is incorrect</span>");
                }
            });
        } // end if firstname!='' && lastname!=''
        else {
            $('#contact_result').html("<span style='color:red;'>Please provide all fields and correct email address and captcha</span>");
        }
    }

    function verify_user_certificate() {
        var cert_fio = $('#cert_fio').val();
        var cert_no = $('#cert_no').val();
        if (cert_fio != '' && cert_no != '') {
            $('#verify_cert_err').html('');
            var url = "https://" + domain + "/lms/custom/certificates/verify_certificate.php";
            var request = {cert_fio: cert_fio, cert_no: cert_no};
            $.post(url, request).done(function (data) {
                $("#verify_cert_err").html("<span style='color:green;'>" + data + "</span>");
            });
        } // end if cert_fio!='' && cert_no!=''
        else {
            $('#verify_cert_err').html('Please provide your First and Last name and Certificate # as well');
        } // end else
    }

    function get_option_payment_personal() {
        var options = $('#payment_options input:radio:checked');
        console.log('Options: ' + options);
        var payment_option = options.attr('value');
        console.log('Payment option: ' + payment_option);
        var url = "https://" + domain + "/functionality/php/get_option_payment_section.php";
        var request = {option: payment_option};
        $('#ajax_loading_schedule').show();
        $.post(url, request).done(function (data) {
            $('#ajax_loading_schedule').hide();
            var el = $('#payment_detailes').length;
            if (el == 0) {
                $('#payment_options').append(data);
            } // end if el>0
            else {
                $('#payment_detailes').remove();
                $('#payment_options').append(data);
            }
            // $('#program_section').html(data);
            // console.log(data);
        });
    }

    /***************************************************************************
     * 
     * Show scheduled course
     * 
     **************************************************************************/
    function show_scheduled_course(courseid) {
        var url = "https://" + domain + "/functionality/php/show_scheduled_course.php";
        var request = {courseid: courseid};
        $('#ajax_loading_schedule').show();
        $.post(url, request).done(function (data) {
            $('#ajax_loading_schedule').hide();
            $('#program_section').html(data);
        });
    }

    function submit_search_form() {
        var search_item = $('#input_search_box').val();
        if (search_item != '') {
            var url = "https://" + domain + "/functionality/php/get_search_item.php";
            var request = {search_item: search_item};
            $.post(url, request).done(function (data) {
                $('#search_result').html(data);
            });
        } // end if search_item!=''
        else {
            $('#search_result').html("<span style='color:red;'>Please provide search criteria</span>");
        }
    }

    function  show_policy_modal_dialog() {

        console.log('Dialog loaded: ' + dialog_loaded);
        if (dialog_loaded !== true) {
            console.log('Script is not yet loaded starting loading ...');
            dialog_loaded = true;
            var js_url = "https://" + domain + "/assets/js/bootstrap.min.js";
            $.getScript(js_url)
                    .done(function () {
                        console.log('Script bootstrap.min.js is loaded ...');
                        var url = "https://" + domain + "/functionality/php/get_terms_box.php";
                        var request = {search_item: 1};
                        $.post(url, request).done(function (data) {
                            $("body").append(data);
                            $("#myModal").modal('show');
                        });
                    })
                    .fail(function () {
                        console.log('Failed to load bootstrap.min.js');
                    });
        } // dialog_loaded!=true
        else {
            console.log('Script already loaded');
            $("#myModal").modal('show');
        }
    }

    function get_schedule_course_state() {
        var stateid = $('#schedule_states').val();
        var courseid = $('#schedule_courses').val();
        var url = "https://" + domain + "/functionality/php/get_schedule_course_state.php";
        var request = {stateid: stateid, courseid: courseid};
        $('#ajax_loading_schedule').show();
        $.post(url, request).done(function (data) {
            $('#ajax_loading_schedule').hide();
            $('#course_schedule').html(data);
        });

    }

    function get_schedule_course() {
        var courseid = $('#schedule_courses').val();
        var url = "https://" + domain + "/functionality/php/get_schedule_course.php";
        var request = {courseid: courseid};
        $('#ajax_loading_schedule').show();
        $.post(url, request).done(function (data) {
            $('#ajax_loading_schedule').hide();
            $('#course_schedule').html(data);
        });
    }

    function get_register_course_states() {
        var courseid = $('#register_courses').val();
        var url = "https://" + domain + "/functionality/php/get_register_course_states.php";
        var request = {courseid: courseid};
        $.post(url, request).done(function (data) {
            $('#register_states_container').html(data);
        });

    }

    function get_register_course_cities() {
        var courseid = $('#register_courses').val();
        var slotid = $('#register_state').val();
        var url = "https://" + domain + "/functionality/php/get_register_course_cities.php";
        var request = {courseid: courseid, slotid: slotid};
        $.post(url, request).done(function (data) {
            $('#register_cities_container').html(data);
        });
    }

    function fill_billing_address() {
        // var group_status=$('#group').prop('checked');
        console.log("Group status: " + group_selected);
        if (group_selected) {
            console.log('Group registration');
            var addr = $('#group_addr').val();
            var state = $('#group_state').val();
            var city = $('#group_city').val();
            var zip = $('#group_zip').val();

            // $("#bill_state").val(state);
            $('select[name^="bill_state"] option[value=' + state + ']').attr("selected", "selected");
            $('#bill_addr').val(addr);
            $('#bill_city').val(city);
            $('#bill_zip').val(zip);
        } // end if $('#group').is(':checked')
        else {
            console.log('Personal registration');
            var addr = $('#addr').val();
            var state = $('#state').val();
            var city = $('#city').val();
            var zip = $('#zip').val();

            // $("#bill_state").val(state);
            $('select[name^="bill_state"] option[value=' + state + ']').attr("selected", "selected");
            $('#bill_addr').val(addr);
            $('#bill_city').val(city);
            $('#bill_zip').val(zip);

        } // end else
    }

    function show_gallery_pics() {
        var state = $('#state').val();
        var month = $('#month').val();
        var year = $('#year').val();
        /*
         var url = "https://" + domain + "/functionality/php/show_gallery_pics.php";
         var request = {state: state, month: month, year: year};
         $.post(url, request).done(function (data) {
         //console.log('Gallery response: '+data);
         $('#gallery_container').html(data);
         });
         */
        var url = "https://" + domain + "/index.php/gallery/matched/" + state + "/" + month + "/" + year;
        window.location = url;
    }

    /***************************************************************************
     * 
     * Individual registration block
     * 
     **************************************************************************/
    function get_individual_registration_block() {
        $('#personal_section').show();
        $('#group_common_section').hide();
        $('#participants_details').hide();
    }

    /***************************************************************************
     * 
     * 
     * Top menu items processing
     * 
     * 
     **************************************************************************/

    /***************************************************************************
     * 
     * Show workshops list after click
     * 
     **************************************************************************/

    $('#ws').click(function () {
        self.location = $('#ws').attr('href');
    });
    /***************************************************************************
     * 
     * Show courses list after click
     * 
     **************************************************************************/


    $('#cs').click(function () {
        self.location = $('#cs').attr('href');
    });
    /***************************************************************************
     * 
     * Show exams list after click
     * 
     **************************************************************************/


    $('#exam').click(function () {
        self.location = $('#exam').attr('href');
    });
    /***************************************************************************
     * 
     * Show school's list after click
     * 
     **************************************************************************/

    $('#school').click(function () {
        self.location = $('#school').attr('href');
    });

    $('#college').click(function () {
        self.location = $('#college').attr('href');
    });

    /***************************************************************************
     * 
     * Show Testimonial page after click
     * 
     **************************************************************************/

    $('#testimonial').click(function () {
        self.location = $('#testimonial').attr('href');
    });
    /***************************************************************************
     * 
     * Show priviate group page after click
     * 
     **************************************************************************/

    $('#group').click(function () {
        self.location = $('#group').attr('href');
    });
    /***************************************************************************
     * 
     * Show certificate verification form
     * 
     **************************************************************************/

    $('#cert').click(function () {
        self.location = $('#cert').attr('href');
    });
    /***************************************************************************
     * 
     * Show Gallery page
     * 
     **************************************************************************/

    $('#gallery').click(function () {
        self.location = $('#gallery').attr('href');
    });
    $('#sch').click(function () {
        self.location = $('#sch').attr('href');
    });
    $('#campus').click(function () {
        self.location = $('#campus').attr('href');
    });
    /***************************************************************************
     * 
     * 
     * Events processing block
     * 
     * 
     **************************************************************************/


    /*
     * $('#login_button').click(function (event) { event.preventDefault();
     * check_login_form(); });
     */


    /***************************************************************************
     * 
     * Section for dynamically created elements
     * 
     **************************************************************************/

    // **************** Buttons processing events ***********************
    $('.form_div').on('click', 'button', function (event) {
        // alert(event.target.id);


        if (event.target.id == 'login_button') {
            event.preventDefault();
            check_login_form();
        }



        if (event.target.id == 'make_payment_personal') {
            verify_personal_payment_section();
        }

        if (event.target.id.indexOf("program_") >= 0) {
            var courseid = event.target.id.replace("program_", "");
            get_selected_program_register_form(courseid);
            $("body").trigger("click");
        }

        if (event.target.id == 'submit_private_group') {
            event.preventDefault();
            submit_private_group();
        }

        if (event.target.id == 'verify_cert') {
            event.preventDefault();
            submit_verify_cert_from();
        }

        if (event.target.id == 'start_upload') {
            // console.log();
            verify_users_upload_form();
        }

        if (event.target.id == 'send_group_invoice') {
            verify_group_owner_detailes();
        }

        if (event.target.id == 'contact_button') {
            submit_contact_form();
        }

        if (event.target.id == 'search_button') {
            submit_search_form();
        }

        if (event.target.id == 'filter') {
            show_gallery_pics();
        }


    }); // end of $('.form_div').on('click', 'button', function (event)

    // ***********************Links processing events **********************
    $('.form_div').on('click', 'a', function (event) {
        //console.log("form_div" + event.target.id);
        if (event.target.id.indexOf("cat_") >= 0) {
            var category_id = event.target.id.replace("cat_", "");
            $(".dropdown li a").click(function () {
                $(this).parents(".dropdown").find('.dropdown-toggle').text($(this).text());
                $(this).parents(".dropdown").find('.dropdown-toggle').val($(this).text());
            });
            get_category_course(category_id);
        }

        if (event.target.id == 'categories') {
            $(".dropdown li a").click(function () {
                $(this).parents(".dropdown").find('.dropdown-toggle').text($(this).text());
                $(this).parents(".dropdown").find('.dropdown-toggle').val($(this).text());
            });
        }

        if (event.target.id.indexOf("state_") >= 0) {
            $(".dropdown li a").click(function () {
                $(this).parents(".dropdown").find('.dropdown-toggle').text($(this).text());
                $(this).parents(".dropdown").find('.dropdown-toggle').val($(this).text());
            });
            var stateid = event.target.id.replace('state_', '');
            // console.log('State ID: ' + stateid);
            show_state_programs(stateid);
        }

        if (event.target.id.indexOf('course_') >= 0) {
            $(".dropdown li a").click(function () {
                $(this).parents(".dropdown").find('.dropdown-toggle').text($(this).text());
                $(this).parents(".dropdown").find('.dropdown-toggle').val($(this).text());
            });
            var url = window.location.href;
            // console.log('Url: ' + url);
            if (url.indexOf('schedule') >= 0) {
                var courseid = event.target.id.replace('course_', '');
                // console.log('Course ID: ' + courseid);
                show_scheduled_course(courseid);
            } // end if url.indexOf('schedule') > 0
        }

        if (event.target.id == 'states') {
            $(".dropdown li a").click(function () {
                $(this).parents(".dropdown").find('.dropdown-toggle').text($(this).text());
                $(this).parents(".dropdown").find('.dropdown-toggle').val($(this).text());
            });
        }

        if (event.target.id == 'come_from') {
            $(".dropdown li a").click(function () {
                $(this).parents(".dropdown").find('.dropdown-toggle').text($(this).text());
                $(this).parents(".dropdown").find('.dropdown-toggle').val($(this).text());
                $('#program_err').html('');
            });
        }

        if (event.target.id == 'participants') {
            $(".dropdown li a").click(function () {
                $(this).parents(".dropdown").find('.dropdown-toggle').text($(this).text());
                $(this).parents(".dropdown").find('.dropdown-toggle').val($(this).text());
                $('#type_err').html('');
                // Verify is group registration selected?
                var group_status = $('#group').is(':checked');
                // console.log('Group status:' + group_status);
                if (group_status != false || group_selected == true) {
                    get_group_registration_block();
                }
            });
        }

        if (event.target.id == 'card_type') {
            $(".dropdown li a").click(function () {
                $(this).parents(".dropdown").find('.dropdown-toggle').text($(this).text());
                $(this).parents(".dropdown").find('.dropdown-toggle').val($(this).text());
            });
        }

        if (event.target.id == 'card_year') {
            $(".dropdown li a").click(function () {
                $(this).parents(".dropdown").find('.dropdown-toggle').text($(this).text());
                $(this).parents(".dropdown").find('.dropdown-toggle').val($(this).text());
            });
        }

        if (event.target.id == 'card_month') {
            $(".dropdown li a").click(function () {
                $(this).parents(".dropdown").find('.dropdown-toggle').text($(this).text());
                $(this).parents(".dropdown").find('.dropdown-toggle').val($(this).text());
            });
        }

        if (event.target.id == 'manual_group_registration') {
            // console.log('Manual registration ...');
            var tot_participants = $('#participants').val();
            $('#upload_section').hide();
            verify_group_common_section();
        }

        if (event.target.id == 'proceed_to_group_payment') {
            var tot_participants = $('#participants').val();
            very_participants_form(tot_participants);
        }

        if (event.target.id == 'proceed_to_personal_payment') {
            verify_personal_manual_registration_form();
        }

        if (event.target.id == 'p_options_p') {
            verify_personal_manual_registration_form();
        }

        if (event.target.id == 'proceed_to_payment') {
            get_option_payment_personal();
        }

        if (event.target.id.indexOf("program_") >= 0) {
            var courseid = event.target.id.replace("program_", "");
            get_selected_program_register_form(courseid);
        }

        if (event.target.id == 'upload_group_file') {
            verify_group_general_part();
        }

        if (event.target.id.indexOf("regiter_state_") >= 0) {
            get_category_items_in_state();
        }

        if (event.target.id == 'policy') {
            show_policy_modal_dialog();
        } // end if event.target.id == 'policy'


    }); // end of .form_div links processing events


    $('.form_div').on('change', 'input[type=radio][name=type]', function (event) {
        // alert(event.target.id);
        if (event.target.id == 'group') {
            get_group_registration_block();
            $('#group_common_section').show();
            $('#participants_details').show();
        } else {
            get_individual_registration_block();
        }

    }); // end if ('#page').on('change', 'input[type=radio][name=type]',
    // function (event) {

    $('.menu_items').click(function () {
        self.location = $(this).attr('href');
    });

    $('.form_div').on('change', function (event) {
        //$('body').on('change', function (event) {    
        // alert(event.target.id);
        if (event.target.id == 'categories') {
            var category_id = $('#categories').val();
            get_category_course(category_id);
        }

        if (event.target.id == 'register_cities') {
            var courseid = $('#register_courses').val();
            var slotid = $('#register_cities').val();
            var url = "/functionality/php/get_course_fee.php";
            var request = {courseid: courseid, slotid: slotid};
            $.post(url, request).done(function (data) {
                var course = jQuery.parseJSON(data);
                var coursedata = "<span id='visible_amount'>" + course.cost + "</span> " + course.box;
                $('#dyn_course_name').html(course.name);
                $('#dyn_course_fee').html(coursedata);

                $('#payment_sum').remove();
                $("#dyn_course_fee").append("<input type='hidden' id='payment_sum' value='" + course.raw_cost + "'>");

                $('#selected_course').remove();
                $("#dyn_course_fee").append("<input type='hidden' id='selected_course' value='" + courseid + "'>");

                $('#selected_slot').remove();
                $("#dyn_course_fee").append("<input type='hidden' id='selected_slot' value='" + slotid + "'>");

                var amount = $('#payment_sum').val();
                var selected_course = $('#selected_course').val();
                var selected_slot = $('#selected_slot').val();
                console.log('Selected course: ' + selected_course);
                console.log('Selected slot: ' + selected_slot);
                console.log('Program fee: ' + amount);
                $('#course_fee').show();
            });
        }

        if (event.target.id == 'participants') {
            $('#type_err').html('');
            // Verify is group registration selected?
            var group_status = $('#group').is(':checked');
            console.log('Group status:' + group_status);
            if (group_status != false || group_selected == true) {
                get_group_registration_block();
            } // end if group_status != false || group_selected == true
        } // end if event.target.id == 'participants'

        if (event.target.id == 'policy') {
            $('#policy_checkbox').prop("checked", true);
            show_policy_modal_dialog();
        } // end if event.target.id == 'policy'

        if (event.target.id == 'same_address') {
            fill_billing_address();
        } // end if event.target.i_coursed == 'policy'

        if (event.target.id == 'schedule_states') {
            get_schedule_course_state();
        } // end if event.target.id == 'policy'

        if (event.target.id == 'schedule_courses') {
            get_schedule_course();
        } // end if event.target.id == 'policy'

        if (event.target.id == 'register_courses') {
            get_register_course_states();
            $('#personal_err').html('');
            var courseid = $('#register_courses').val();
            var slotid = $('#register_cities').val();
            var request = {courseid: courseid, slotid: slotid};
            var url = '/functionality/php/get_course_data.php';
            $.post(url, request).done(function (data) {
                var course = jQuery.parseJSON(data);
                var coursedata = "<span id='visible_amount'>" + course.cost + "</span> " + course.box;
                $('#dyn_course_name').html(course.name);
                $('#dyn_course_fee').html(coursedata);

                $('#payment_sum').remove();
                $("#dyn_course_fee").append("<input type='hidden' id='payment_sum' value='" + course.raw_cost + "'>");

                $('#selected_course').remove();
                $("#dyn_course_fee").append("<input type='hidden' id='selected_course' value='" + courseid + "'>");

                $('#selected_slot').remove();
                $("#dyn_course_fee").append("<input type='hidden' id='selected_slot' value='" + slotid + "'>");

                var amount = $('#payment_sum').val();
                var selected_course = $('#selected_course').val();
                var selected_slot = $('#selected_slot').val();
                console.log('Selected course: ' + selected_course);
                console.log('Selected slot: ' + selected_slot);
                console.log('Program fee: ' + amount);
                $('#course_fee').show();

            });

        } // end if event.target.id == 'policy'

        if (event.target.id == 'register_state') {
            get_register_course_cities();
        } // end if event.target.id == 'policy'


    }); // end if ('#page').on('change', function (event) {

    $("#search_button").click(function () {
        submit_search_form();
    });
    var url = window.location.href;
    if (url.indexOf("school") >= 0) {
        // Map related code
        var url = "/lms/custom/google_map/refresh.php";
        var category_id = 5; // Nursing school category id
        var request = {category_id: category_id};
        $.post(url, request).done(function (data) {
            var $obj_data = $.parseJSON(data);
            // Create a map object and specify the DOM element for display.
            var map = new google.maps.Map(document.getElementById('map'), {
                scrollwheel: false,
                zoom: 8
            }); // end var map
            var latLngs = [];
            var bounds = new google.maps.LatLngBounds();
            $.each($obj_data, function (i, m) {
                var myLatLng = new google.maps.LatLng(m.lat, m.lng);
                latLngs[i] = myLatLng
                var marker = new google.maps.Marker({
                    position: myLatLng,
                    map: map,
                    title: m.marker_text,
                    zIndex: i
                }); // end marker
                bounds.extend(marker.position);
                google.maps.event.addListener(marker, 'click', (function (marker, i) {
                    return function () {
                        var infowindow = new google.maps.InfoWindow();
                        var iWC = infowindow.getContent();
                        iWC = m.info;
                        infowindow.setContent(iWC);
                        infowindow.open(map, marker);
                    }
                })(marker, i));
            }) // end each
            map.fitBounds(bounds);
        }); // post(url, request).done(function (data)
    } // end if url.indexOf("school") >= 0

    $("#faq_cat").change(function () {
        var id = $('#faq_cat').val();
        $('#ajax_loader').show();
        var url = "https://" + domain + "/functionality/php/get_category_faqs.php";
        var request = {id: id};
        $.post(url, request).done(function (data) {
            $('#ajax_loader').hide();
            $('#q_container').html(data)
        });
    });

    $("#total_group_users").change(function () {
        var total = $("#total_group_users").val();
        if (total > 0) {
            var url = "https://medical2.com/register2/get_group_users_block";
            var request = {total: total};
            $.post(url, request).done(function (data) {
                $('#users_div').html(data);
                var courseid = $('#register_courses').val();
                var slotid = $('#register_cities').val();
                var feeurl = "https://medical2.com/register2/get_group_course_fee";
                $.post(feeurl, {courseid: courseid, slotid: slotid, total: total}).done(function (data) {
                    console.log('Server response: ' + data);
                    var course = JSON.parse(data);

                    var coursedata = "<span id='visible_amount'>$" + course.cost + "</span> ";
                    $('#group_dyn_course_name').html(course.name);
                    $('#group_dyn_course_fee').html(coursedata);

                    $('#payment_sum').remove();
                    $("#group_dyn_course_fee").append("<input type='hidden' id='payment_sum' value='" + course.cost + "'>");

                    $('#selected_course').remove();
                    $("#group_dyn_course_fee").append("<input type='hidden' id='selected_course' value='" + courseid + "'>");

                    $('#selected_slot').remove();
                    $("#group_dyn_course_fee").append("<input type='hidden' id='selected_slot' value='" + slotid + "'>");

                    $('#group_course_fee').show();

                    $('#group_err').html('');
                });
            });
        }
    });


    $("#programs").change(function () {
        var url = "https://" + domain + "/functionality/php/get_school_programs_slots.php";
        var id = $('#programs').val();
        var request = {id: id};
        console.log('Course ID: ' + id);
        $.post(url, request).done(function (data) {
            $('#program_schedule').html(data);
        });
    });

    $("body").click(function (event) {
        console.log('Element clicked: ' + event.target.id);

        if (event.target.id == 'next_group_payment') {
            verify_barintree_group_registration();
        }


        if (event.target.id == 'make_already_registered_payment') {
            make_already_registered_payment();
        }

        if (event.target.id == 'ok') {
            $('#policy_checkbox').prop("checked", true);
        }

        if (event.target.id == 'close') {
            $("#myModal").remove();
        }

        if (event.target.id == 'prev_slide') {
            console.log('Prev is clicked ...');
        }

        if (event.target.id == 'next_slide') {
            console.log('Next is clicked ...');
        }

        if (event.target.id == 'da') {
            if ($('#da').is(':checked')) {
                $('#diff_address').show();
            } // end if
            else {
                $('#diff_address').hide();
            } // end else
        }

        if (event.target.id == 'next_register_payment') {
            var courseid = $('#selected_course').val();
            var slotid = $('#selected_slot').val();
            var amount = $('#payment_sum').val();
            var promo_code = $('#register_promo_code').val();
            if (typeof (courseid) === "undefined") {
                $('#personal_err').html('Please select program');
            } // end if
            else {
                $('#personal_err').html('');
                console.log('Course id: ' + courseid);
                console.log('Slot id: ' + slotid);
                console.log('Program fee: ' + amount);

                var firstname = $('#first_name').val();
                var lastname = $('#last_name').val();
                var addr = $('#addr').val();
                var city = $('#city').val();
                var state = $('#state').val();
                var country = $('#country').val();
                var zip = $('#zip').val();
                var phone = $('#phone').val();
                var email = $('#email').val();
                var from = $('#come_from').val();

                if (firstname == '') {
                    $('#personal_err').html('Please provide firstname');
                    return;
                }

                if (lastname == '') {
                    $('#personal_err').html('Please provide lastname');
                    return;
                }

                if (addr == '') {
                    $('#personal_err').html('Please provide mailing address');
                    return;
                }

                if (city == '') {
                    $('#personal_err').html('Please provide city');
                    return;
                }

                if (state == 0) {
                    $('#personal_err').html('Please select state');
                    return;
                }

                if (country == '') {
                    $('#personal_err').html('Please provide country');
                    return;
                }

                if (zip == '') {
                    $('#personal_err').html('Please provide zip');
                    return;
                }

                if (phone == '') {
                    $('#personal_err').html('Please provide phone');
                    return;
                }

                if (email == '') {
                    $('#personal_err').html('Please provide email');
                    return;
                }

                if (email != '') {
                    if (!validateEmail(email)) {
                        console.log('Email vetification failure ..');
                        $('#personal_err').html('Please provide valid email');
                        return;
                    } // end if 
                    else {
                        console.log('Email verification passed ...');
                    }
                }

                var url = "/functionality/php/is_email_exists.php";
                var request = {email: email};
                $.post(url, request).done(function (data) {
                    console.log('Server email exists response: ' + data);
                    if (data > 0) {
                        $('#personal_err').html("You already have an account. Please click <a href='https://medical2.com/login'  target='_blank'>here</a> to login into system using your email and password.");
                    } // end if data>0
                    else {
                        var user = {
                            first_name: firstname,
                            last_name: lastname,
                            b_fname: b_fname,
                            b_lname: b_lname,
                            addr: addr,
                            city: city,
                            state: state,
                            country: country,
                            zip: zip,
                            inst: 'n/a',
                            phone: phone,
                            email: email,
                            come_from: from,
                            renew: 0,
                            courseid: courseid,
                            promo_code: promo_code,
                            slotid: slotid,
                            amount: amount
                        };
                        console.log('User object: ' + JSON.stringify(user));
                        var url;
                        var encoded_user = Base64.encode(JSON.stringify(user));
                        var ptype = $("input:radio[name ='ptype']:checked").val();
                        if (ptype == 'card') {
                            url = 'https://medical2.com/register2/payment_card/' + encoded_user;
                        } // end if ptype=='card'
                        else {
                            url = 'https://medical2.com/register2/payment_paypal/' + encoded_user;
                        } // end else when user pays by PayPal
                        window.location.href = url;
                    } // end else when email is not exists and we can continue ...
                }); // end of post



            } // end else when program is selected
        }

        if (event.target.id == 'make_payment_personal2') {
            var b_firstname, b_lastname;
            var courseid = $('#selected_course').val();
            var slotid = $('#selected_slot').val();
            var amount = $('#payment_sum').val();
            var promo_code = $('#register_promo_code').val();
            if (typeof (courseid) === "undefined") {
                $('#personal_err').html('Please select program');
            } // end if
            else {
                $('#personal_err').html('');
                console.log('Course id: ' + courseid);
                console.log('Slot id: ' + slotid);
                console.log('Program fee: ' + amount);

                var firstname = $('#first_name').val();
                var lastname = $('#last_name').val();
                var billing_name = $('#billing_name').val();
                var addr = $('#addr').val();
                var city = $('#city').val();
                var state = $('#state').val();
                var country = $('#country').val();
                var zip = $('#zip').val();
                var phone = $('#phone').val();
                var email = $('#email').val();
                var receipt_email = 'n/a';
                var cardnumber = $('#card_no2').val();
                var cvv = $('#cvv2').val();
                var exp_month = $('#card_month2').val();
                var exp_year = $('#card_year2').val();
                var from = $('#come_from').val();
                var b_fname = $('#b_fname').val();
                var b_lname = $('#b_lname').val();

                if ($('#da').is(':checked')) {
                    addr = $('#addr2').val();
                    state = $('#state2').val();
                    city = $('#city2').val();
                    country = $('#country2').val();
                    zip = $('#zip2').val();
                    receipt_email = $('#email2').val();
                    phone = $('#phone2').val();

                    if (receipt_email == '') {
                        $('#personal_err').html('Please provide receipt email');
                        return;
                    }

                } // end if 

                if (firstname == '') {
                    $('#personal_err').html('Please provide firstname');
                    return;
                }

                if (lastname == '') {
                    $('#personal_err').html('Please provide lastname');
                    return;
                }

                if (addr == '') {
                    $('#personal_err').html('Please provide mailing address');
                    return;
                }

                if (city == '') {
                    $('#personal_err').html('Please provide city');
                    return;
                }

                if (state == 0) {
                    $('#personal_err').html('Please select state');
                    return;
                }

                if (country == '') {
                    $('#personal_err').html('Please provide country');
                    return;
                }

                if (zip == '') {
                    $('#personal_err').html('Please provide zip');
                    return;
                }

                if (zip != '') {
                    if (!$.isNumeric(zip) || zip.length < 4) {
                        $('#personal_err').html('Please provide valid zip');
                        return;
                    } // end if
                } // end if

                if (phone == '') {
                    $('#personal_err').html('Please provide phone');
                    return;
                }

                if (email == '') {
                    $('#personal_err').html('Please provide email');
                    return;
                }

                if (email != '') {
                    if (!validateEmail(email)) {
                        console.log('Email vetification failure ..');
                        $('#personal_err').html('Please provide valid email');
                        return;
                    } // end if 
                    else {
                        console.log('Email verification passed ...');
                    }

                }


                if (b_fname == '') {
                    $('#personal_err').html('Please provide card holder first name');
                    return;
                }

                if (b_lname == '') {
                    $('#personal_err').html('Please provide card holder last name');
                    return;
                }

                if (cardnumber == '') {
                    $('#personal_err').html('Please provide card number');
                    return;
                }

                if (cvv == '') {
                    $('#personal_err').html('Please provide card code number');
                    return;
                }

                if (exp_month == 0 || exp_year == 0) {
                    $('#personal_err').html('Please put expiration date');
                    return;
                }

                $('#personal_err').html('');

                var url = "/functionality/php/is_email_exists.php";
                var request = {email: email};
                $.post(url, request).done(function (data) {
                    console.log('Server email exists response: ' + data);
                    if (data > 0) {
                        $('#personal_err').html("You already have an account. Please click <a href='https://medical2.com/login'  target='_blank'>here</a> to login into system using your email and password.");
                    } // end if data>0
                    else {
                        var user = {
                            first_name: firstname,
                            last_name: lastname,
                            b_fname: b_fname,
                            b_lname: b_lname,
                            addr: addr,
                            city: city,
                            state: state,
                            country: country,
                            zip: zip,
                            inst: 'n/a',
                            phone: phone,
                            email: email,
                            receipt_email: receipt_email,
                            cardnumber: cardnumber,
                            cvv: cvv,
                            exp_month: exp_month,
                            exp_year: exp_year,
                            come_from: from,
                            courseid: courseid,
                            slotid: slotid,
                            promo_code: promo_code,
                            amount: amount
                        };
                        $('#ajax_loading_payment').show();
                        $('#make_payment_personal2').prop('disabled', true);
                        $('#make_payment_personal2').text('Processing request');
                        personal_registration_obj = JSON.stringify(user);
                        var signup_url = "/functionality/php/single_signup2.php";
                        var signup_request = {user: JSON.stringify(user)};
                        $.post(signup_url, signup_request).done(function (data) {
                            $('#ajax_loading_payment').hide();
                            $('#make_payment_personal2').text('I Agree, Submit');
                            $('#make_payment_personal2').prop('disabled', false);
                            $('#personal_err').html("<span style='color:black'>" + data + "</span>");
                        });
                    } // end else
                }); // end of post
            } // end else
        } // end if


        if (event.target.id == 'manual_group_registration') {
            // console.log('Manual registration ...');
            var tot_participants = $('#participants').val();
            $('#upload_section').hide();
            verify_group_common_section();
        }

        if (event.target.id == 'shcool_apply') {
            var courseid = $('#programs').val();
            var ssn = 'To be provided later';
            var slotid = $('#slotid').val();
            var last = $('#last').val();
            var first = $('#first').val();
            var middle = $('#middle').val();
            var maiden = $('#maiden').val();
            var street = $('#street').val();
            var city = $('#city').val();
            var state = $('#state').val();
            var zip = $('#zip').val();
            var phone1 = $('#phone1').val();
            var phone2 = $('#phone2').val();
            var email = $('#email').val();
            var birth = $('#birth').val();
            var education = $('#education').val();
            var edu_name = $('#edu_name').val();
            var graduate_date = $('#graduate_date').val();
            var work = $('#work').val();
            var pc_knoweldge = $('#pc_knoweldge').val();
            var cert_status = $('#cert_status').val();
            var cert_area = $('#cert_area').val();
            var reason = $('#reason').val();

            if (last != '' &&
                    first != '' &&
                    middle != '' &&
                    maiden != '' &&
                    street != '' &&
                    city != '' &&
                    state > 0 &&
                    zip != '' &&
                    phone1 != '' &&
                    phone2 != '' &&
                    email != '' &&
                    birth != '' &&
                    education > 0 &&
                    edu_name != '' &&
                    graduate_date != '' &&
                    work != '' &&
                    pc_knoweldge > 0 &&
                    cert_status > 0 &&
                    reason != '') {

                $('#app_err').html('');
                if (slotid == 0) {
                    $('#app_err').html('Please select class');
                    return;
                } // end if
                else {
                    $('#app_err').html('');
                    var app = {courseid: courseid,
                        ssn: ssn,
                        slotid: slotid,
                        last: last,
                        first: first,
                        middle: middle,
                        maiden: maiden,
                        street: street,
                        city: city,
                        state: state,
                        zip: zip,
                        phone1: phone1,
                        phone2: phone2,
                        email: email,
                        birth: birth,
                        education: education,
                        edu_name: edu_name,
                        graduate_date: graduate_date,
                        work: work,
                        pc_knoweldge: pc_knoweldge,
                        cert_status: cert_status,
                        cert_area: cert_area,
                        reason: reason};
                    $('#ajax_loading').show();
                    var url = "/functionality/php/send_school_app.php";
                    $.post(url, {app: JSON.stringify(app)}).done(function (data) {
                        $('#ajax_loading').hide();
                        $('.panel-body').html(data);
                    });

                }

            } // end if
            else {
                $('#app_err').html('Please provide required fields');
                return;
            } // end else


            if (event.target.id == 'make_group_renew_payment') {

            }

        }

    }); // end of $("body").click(function (event) {    

    $("body").bind('cssClassChanged', function (event) {
        console.log('Item1 has changed class ... ' + event);

    });

    $('body').on('blur', "input", function (event) {

        if (event.target.id == 'register_promo_code') {
            var c = $('#register_promo_code').val();
            var courseid = $('#selected_course').val();
            var amount = $('#payment_sum').val();
            var slotid = $('#selected_slot').val();
            var code = {courseid: courseid, slotid: slotid, amount: amount, code: c};
            console.log('Code: ' + JSON.stringify(code));
            if (c != '' && codeused == 0) {
                var url = "/lms/custom/codes/update_registration_price.php";
                $.post(url, {code: JSON.stringify(code)}).done(function (data) {
                    console.log('Server response: ' + data);
                    if (data != 0) {
                        $('#visible_amount').html('$' + data);
                        $('#payment_sum').remove();
                        $("#dyn_course_fee").append("<input type='hidden' id='payment_sum' value='" + data + "'>");
                        var newprice = $('#payment_sum').val();
                        console.log('New price:' + newprice);
                        codeused = 1;
                    } // end if data != 0
                }); // end of post
            } // end if c!=''

        } // end if $('body').on('blur', "input"

    });



}); // end of (document).ready(function ()
