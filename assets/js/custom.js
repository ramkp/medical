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

    var domain = 'medical2.com';    
    /***************************************************************************
     * 
     * Login form verification
     * 
     **************************************************************************/


    function check_login_form() {
        var login = $('#login_box').val();
        var password = $('#password_box').val();
        console.log('Login: ' + login);
        console.log('Password: ' + password);
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
                    }
                    else {
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
            $('#private_err').html('Please provide request ');
            return false;
        }

        var url = "https://" + domain + "/functionality/php/submit_private_group_request.php";
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
        var cert_fio = $('#cert_fio').val();
        var cert_no = $('#cert_no').val();
        /*
         * if (cert_fio == '') { $('#cert_err').html('Please provide Firstname
         * and Lastname'); return false; }
         */

        if (cert_no == '') {
            $('#cert_err').html('Please provide Certificate No');
            return false;
        }

        if (cert_fio != '' && cert_no != '') {
            var url = "https://" + domain + "/lms/custom/certificates/verify_certificate.php";
            var request = {cert_fio: cert_fio, cert_no: cert_no};
            $.post(url, request).done(function (data) {
                $("#cert_err").html("<span style='color:green;'>" + data + "</span>");
            });
        } // end if cert_fio!='' && cert_no!=''
        else {
            $('#cert_err').html('Please provide your First and Last name and Certificate # as well');
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

        if (card_no == '') {
            $('#personal_payment_err').html('Please provide card number');
            return false;
        }

        if (card_holder == '') {
            $('#personal_payment_err').html('Please provide card holder name');
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
                user_group: user_group,
                bill_email: bill_email};
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
        }
        else {
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
                    }
                    else {
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
        if (firstname != '' && lastname != '' && email != '' && validateEmail(email) == true && phone != '' && message != '' && captcha != '') {
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
                    var request = {firstname: firstname, lastname: lastname, email: email, phone: phone, message: message};
                    $.post(url, request).done(function (data) {
                        $('#contact_result').html(data);
                    });
                } // end if data>0
                else {
                    $('#contact_result').html('Captcha is incorrect');
                }
            });
        } // end if firstname!='' && lastname!=''
        else {
            $('#contact_result').html("<span style='colore:red;'>Please provide all fields and correct email address and captcha</span>");
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
       var url="https://"+domain+"/index.php/gallery/matched/"+state+"/"+month+"/"+year;
       window.location=url;       
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
        console.log("form_div" + event.target.id);
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
        }
        else {
            get_individual_registration_block();
        }

    }); // end if ('#page').on('change', 'input[type=radio][name=type]',
    // function (event) {

    $('.menu_items').click(function () {
        self.location = $(this).attr('href');
    });

    $('.form_div').on('change', function (event) {
        // alert(event.target.id);
        if (event.target.id == 'categories') {
            var category_id = $('#categories').val();
            get_category_course(category_id);
        }

        // if (event.target.id == 'register_state') {
        // get_category_items_in_state();
        // }

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

    $("body").click(function (event) {
        // console.log('Element clicked: ' + event.target.id);
        if (event.target.id == 'ok') {
            $('#policy_checkbox').prop("checked", true);
        }
    });

}); // end of (document).ready(function ()
