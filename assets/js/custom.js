/* -------------------- Check Browser --------------------- */

function browser() {

    var isOpera = !!(window.opera && window.opera.version);  // Opera 8.0+
    var isFirefox = testCSS('MozBoxSizing');                 // FF 0.8+
    var isSafari = Object.prototype.toString.call(window.HTMLElement).indexOf('Constructor') > 0;
    // At least Safari 3+: "[object HTMLElementConstructor]"
    var isChrome = !isSafari && testCSS('WebkitTransform');  // Chrome 1+
    //var isIE = /*@cc_on!@*/false || testCSS('msTransform');  // At least IE6

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


    /* ---------- Add class .active to current link  ---------- */
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

    /* ---------- Submenu  ---------- */

    $('.dropmenu').click(function (e) {

        e.preventDefault();

        $(this).parent().find('ul').slideToggle();

    });


    /**************************************************************************
     * 
     * 
     *                  Service & rendeting functions
     *      
     * 
     * 
     **************************************************************************/



    /************************************************************************
     * 
     *                Login form verification
     * 
     ************************************************************************/
    function check_login_form() {
        var login = $('#login_box').val();
        var password = $('#passsword_box').val();
        if (login == '' || password == '') {
            $('#login_err').html('*all fields required');
        }
        if (login != '' && password != '') {
            $('#login_form').submit();
        }
    }

    /************************************************************************
     * 
     *             Verify users upload form and start if any
     * 
     ************************************************************************/

    function verify_users_upload_form() {
        var url = "http://cnausa.com/functionality/php/upload_users_file.php";
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
                    if (data > 0) {
                        $('#upload_err').html('');
                        var selected_course = $('#courses').text();
                        var course_name = selected_course.trim();
                        var course_url = 'http://cnausa.com/functionality/php/get_course_id.php';
                        var request = {course_name: course_name};
                        $.post(course_url, request).done(function (courseid) {
                            var addr = $('#group_addr').val();
                            var inst = $('#group_inst').val();
                            var zip = $('#group_zip').val();
                            var city = $('#group_city').val();
                            var state = $('#group_state').val();
                            var group_name = $('#group_name').val();
                            var grpoup_data = {courseid: courseid,
                                addr: addr,
                                inst: inst,
                                zip: zip,
                                city: city,
                                state: state,
                                tot_participants: data,
                                group_name: group_name};
                            var group_url = 'http://cnausa.com/functionality/php/group_signup_by_file.php';
                            var request = {group_common_section: JSON.stringify(grpoup_data)};
                            $.post(group_url, request).done(function (data) {
                                $('#ajax_loading_group_file').hide();
                                var el = $('#personal_payment_details').length;
                                if (el == 0) {
                                    $('#group_common_section').append(data);
                                }
                            });
                        }); // end of $.post(course_url, request)
                    } // end if data > 0
                    else {
                        $('#upload_err').html(data);
                    } // end else
                } // end of success
            }); // end of $.ajax ..
        } // end else
    }

    /************************************************************************
     * 
     *                     Show Gallery page
     * 
     ************************************************************************/

    function get_gallery_page() {
        $.post("http://cnausa.com/functionality/php/gallery.php", function (data) {
            $('#instructions').hide();
            $("#page").html(data);
        });
    }

    /************************************************************************
     * 
     *                   Show program items
     * 
     ************************************************************************/
    function show_program_items(cat_name) {
        $.post("http://cnausa.com/functionality/php/get_programs_list.php", {cat_name: cat_name})
                .done(function (data) {
                    $('#instructions').hide();
                    $("#page").html(data);
                });
        console.log('Triggered click on btn-navbar');
        $(".btn-navbar").trigger("click");
    }

    /************************************************************************
     * 
     *                Show school page and Google Map
     * 
     ************************************************************************/

    function show_school_page(cat_name) {
        $.post("http://cnausa.com/functionality/php/get_school_page.php", {cat_name: cat_name})
                .done(function (data) {
                    $('#instructions').hide();
                    $("#page").html(data);
                    refresh_map();
                });
    }

    /************************************************************************
     * 
     *                   Show Google Map
     * 
     ************************************************************************/

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

    /************************************************************************
     * 
     *                        Show FAQ page
     * 
     ************************************************************************/
    function get_faq_page() {
        $.post("http://cnausa.com/functionality/php/get_faq_page.php", function (data) {
            $('#instructions').hide();
            $("#page").html(data);
        });
    }

    /************************************************************************
     * 
     *                        Show Testimonial page
     * 
     ************************************************************************/

    function get_testimonial_page() {
        $.post("http://cnausa.com/functionality/php/get_testimonial_page.php", function (data) {
            $('#instructions').hide();
            $("#page").html(data);
        });
    }

    /************************************************************************
     * 
     *                 Certificate verification form
     * 
     ************************************************************************/

    function get_certificate_verification_form() {
        $.post("http://cnausa.com/functionality/php/get_certificate_verification_form.php", function (data) {
            $('#instructions').hide();
            $("#page").html(data);
        });
    }

    /************************************************************************
     * 
     *                   Get users upload form
     * 
     ************************************************************************/

    function get_users_upload_form() {
        $.post("http://cnausa.com/functionality/php/get_users_upload_form.php", function (data) {
            $('#participants_details').remove();
            $('#group_common_section').append(data);
        });
    }

    /************************************************************************
     * 
     *      Verify group general part and proceed to file upload
     * 
     ************************************************************************/

    function verify_group_general_part() {
        var selected_course = $('#courses').text();
        var course_name = selected_course.trim();
        if (course_name != 'Program' && course_name != '' && course_name !== undefined) {
            $('#program_err').html('');
            $('#group_common_errors').html('');
            var course_url = 'http://cnausa.com/functionality/php/get_course_id.php';
            var request = {course_name: course_name};
            $.post(course_url, request).done(function (courseid) {
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
                    $('#group_common_errors').html('Please provide Business or Institution');
                    return false;
                }

                if (zip == '') {
                    $('#group_common_errors').html('Please provide zip code');
                    return false;
                }

                if (city == '') {
                    $('#group_common_errors').html('Please provide city');
                    return false;
                }

                if (state == '') {
                    $('#group_common_errors').html('Please provide state');
                    return false;
                }

                if (group_name == '') {
                    $('#group_common_errors').html('Please provide group name');
                    return false;
                }

                if (addr != '' && inst != '' && zip != '' && zip != '' && city != '' && state != '' && group_name != '') {
                    // Check is group name exist?
                    var course_url = 'http://cnausa.com/functionality/php/is_group_exist.php';
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
            }); // end if $.post
        } // end if course_name != 'Program' && course_name != '' ...
        else {
            $('#program_err').html('Please select program');
            $('#group_common_errors').html('Please select program');
        }
    }

    /************************************************************************
     * 
     *               Private group request form verification
     * 
     ************************************************************************/

    function submit_private_group() {

        var group_fio = $('#group_fio').val();
        var group_city = $('#group_city').val();
        var group_phone = $('#group_phone').val();
        var group_budget = $('#group_budget').val();
        var group_company = $('#group_company').val();
        var group_email = $('#group_email').val();
        var courses = $('#courses').val();
        var group_request = $('#group_request').val();

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
            $('#private_err').html('Please provide estimate budget ');
            return false;
        }

        if (group_company == '') {
            $('#private_err').html('Please provide company ');
            return false;
        }

        if (group_email == '') {
            $('#private_err').html('Please provide email ');
            return false;
        }

        if (validateEmail(group_email) != true) {
            $('#private_err').html('Please provide valid email ');
        }

        if (courses == '') {
            $('#private_err').html('Please select program ');
            return false;
        }

        if (group_request == '') {
            $('#private_err').html('Please provide request ');
            return false;
        }

        var url = "http://cnausa.com/functionality/php/submit_private_group_request.php";
        var group_request = {group_fio: group_fio,
            group_city: group_city,
            group_phone: group_phone,
            group_budget: group_budget,
            group_company: group_company,
            group_email: group_email,
            courses: courses,
            group_request: group_request};
        var request = {request: JSON.stringify(group_request)};
        $.post(url, request).done(function (data) {
            $("#page").html(data);
        });


    }

    /************************************************************************
     * 
     *                    Submit verify certification form
     * 
     ************************************************************************/
    function submit_verify_cert_from() {
        var cert_fio = $('#cert_fio').val();
        var cert_no = $('#cert_no').val();

        if (cert_fio == '') {
            $('#cert_err').html('Please provide Firstname and Lastname');
            return false;
        }

        if (cert_no == '') {
            $('#cert_err').html('Please provide Certificate No');
            return false;
        }

        if (cert_fio != '' && cert_no != '') {
            var url = "http://cnausa.com/functionality/php/verify_cert.php";
            var request = {user_fio: cert_fio, user_cert_no: cert_no};
            $.post(url, request).done(function (data) {
                $("#cert_err").html("<span style='color:#444'>" + data + "</span>");
            });
        }
    }

    /************************************************************************
     * 
     *                    Show courses inside category
     * 
     ************************************************************************/
    function get_category_course(category_id) {
        var url = "http://cnausa.com/functionality/php/get_selected_course.php";
        var request = {cat_id: category_id};
        $.post(url, request).done(function (data) {
            $("#cat_course").html(data);
        });

    }

    /************************************************************************
     * 
     *                    Show Register page w/o params
     * 
     ************************************************************************/
    function get_register_page() {
        $.post("http://cnausa.com/functionality/php/get_register_page.php", function (data) {
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

        var user_group = $('#user_group').val();

        if (card_type != 'Card type' && card_no != '' && card_holder != '' && card_year != '--' && card_month != '--' && bill_addr != '' && bill_city != '' && bill_zip != '' && bill_email != '' && validateEmail(bill_email) == true) {
            $('#personal_payment_err').html('');
            var card = {sum: sum,
                email: email,
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
            var url = "http://cnausa.com/functionality/php/make_stub_payment.php";
            var request = {card: JSON.stringify(card)};
            $('#ajax_loading_payment').show();
            $.post(url, request).done(function (data) {
                $('#ajax_loading_payment').hide();
                $('.form_div').html(data);
            }); // end of post
        } // end if card_type != 'Card type' && card_no!='' ...
    }

    /************************************************************************
     * 
     *                  Group registration block
     * 
     ************************************************************************/
    function get_group_registration_block() {
        var tot_participants = $('#participants').val();
        $('#personal_section').hide();
        if (tot_participants == 0) {
            $('#type_err').html('Please select number of group participants');
        }
        else {
            var url = "http://cnausa.com/functionality/php/get_group_registration_form.php";
            var request = {tot_participants: tot_participants};
            $.post(url, request).done(function (data) {
                var el = $("#group_common_section").length;
                if (el == 0) {
                    $('#type_section').append(data);
                }
            });
        }
    }

    /************************************************************************
     * 
     *                  Manual Group registration form
     * 
     ************************************************************************/

    function get_manual_group_registration_form(tot_participants) {
        var url = "http://cnausa.com/functionality/php/get_group_manual_registration_form.php";
        var request = {tot_participants: tot_participants};
        $.post(url, request).done(function (data) {
            $('#participants_details').remove();
            $('#group_common_section').append(data);

        });
    }

    /************************************************************************
     * 
     *               Verify Manual Group registration form
     * 
     ************************************************************************/

    function very_participants_form(tot_participants) {

        var err = 0;
        var users = new Array();

        var selected_course = $('#courses').text();
        var course_name = selected_course.trim();
        var addr = $('#group_addr').val();
        var inst = $('#group_inst').val();
        var zip = $('#group_zip').val();
        var city = $('#group_city').val();
        var state = $('#group_state').val();
        var group_name = $('#group_name').val();

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
                var user = {first_name: first_name, last_name: last_name, email: email, phone: phone};
                users.push(user);
            } // end if first_name != '' && last_name != ''
        } // end for
        console.log('Errors counter: ' + err);
        if (err > 1) {
            $('#group_manual_form_err').html('Please provide all required fields and valid emails');
        } // end if err > 0
        else {
            // Everything is fine  - show payment form
            $('#group_manual_form_err').html('');
            var course_url = 'http://cnausa.com/functionality/php/get_course_id.php';
            var request = {course_name: course_name};
            $('#ajax_loading_group').show();
            $.post(course_url, request).done(function (courseid) {
                console.log('Course id: ' + courseid);

                var group_common_section = {
                    courseid: courseid,
                    addr: addr,
                    inst: inst,
                    zip: zip,
                    city: city,
                    state: state,
                    group_name: group_name};

                var signup_url = 'http://cnausa.com/functionality/php/group_signup.php';
                var signup_request = {group_common_section: JSON.stringify(group_common_section),
                    users: JSON.stringify(users),
                    tot_participants: tot_participants};
                $.post(signup_url, signup_request).done(function (data) {
                    console.log(data);
                    // Show payment section
                    $('#ajax_loading_group').hide();
                    /*
                     var el = $('#personal_payment_details').length;
                     if (el == 0) {
                     $('#participants_details').append(data);
                     }
                     */
                    $('#participants_details').append(data);
                }).fail(function (data) {
                    console.log(data);
                    $('#personal_err').html('Ops something goes wrong ...');
                }); // end of fail(function (data)
            }); // end of $.post(course_url, request)            
        } // end else
    }

    function verify_group_common_section() {
        var tot_participants = $('#participants').val();
        var selected_course = $('#courses').text();
        var course_name = selected_course.trim();
        if (course_name != 'Program' && course_name != '' && course_name !== undefined) {
            $('#program_err').html('');
            $('#group_common_errors').html('');
            var course_url = 'http://cnausa.com/functionality/php/get_course_id.php';
            var request = {course_name: course_name};
            $.post(course_url, request).done(function (courseid) {
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
                    $('#group_common_errors').html('Please provide Business or Institution');
                    return false;
                }

                if (zip == '') {
                    $('#group_common_errors').html('Please provide zip code');
                    return false;
                }

                if (city == '') {
                    $('#group_common_errors').html('Please provide city');
                    return false;
                }

                if (state == '') {
                    $('#group_common_errors').html('Please provide state');
                    return false;
                }

                if (group_name == '') {
                    $('#group_common_errors').html('Please provide group name');
                    return false;
                }

                if (addr != '' && inst != '' && zip != '' && zip != '' && city != '' && state != '' && group_name != '') {
                    // Check is group name exist?
                    var course_url = 'http://cnausa.com/functionality/php/is_group_exist.php';
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
            }); // end if $.post
        } // end if course_name != 'Program' && course_name != '' ...
        else {
            $('#program_err').html('Please select program');
            $('#group_common_errors').html('Please select program');
        }
    }

    /************************************************************************
     * 
     *                  Get private group form
     * 
     ************************************************************************/

    function get_private_group_form() {
        $.post("http://cnausa.com/functionality/php/get_private_groups_form.php", function (data) {
            $('#instructions').hide();
            $("#page").html(data);
        });
    }

    /************************************************************************
     * 
     *               Show register form with selected program
     * 
     ************************************************************************/

    function get_selected_program_register_form(courseid) {
        console.log('Course id: ' + courseid);
        var url = "http://cnausa.com/functionality/php/get_selected_program_register_form.php";
        var request = {courseid: courseid};
        $.post(url, request).done(function (data) {
            $('#page').html(data);
        });
    }

    /************************************************************************
     * 
     *               Verify Manual Group registration form
     * 
     ************************************************************************/

    function validateEmail(email) {
        var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
        return re.test(email);
    }

    function verify_personal_manual_registration_form() {
        var selected_course = $('#courses').text();
        var course_name = selected_course.trim();
        console.log('Courses dropdown: ' + selected_course);
        if (course_name != 'Program' && course_name != '' && course_name !== undefined) {
            $('#program_err').html('');
            $('#personal_err').html('');
            var course_url = 'http://cnausa.com/functionality/php/get_course_id.php';
            var request = {course_name: course_name};
            $.post(course_url, request).done(function (courseid) {
                //console.log('Course id: ' + courseid);

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

                if (first_name == '') {
                    $('#personal_err').html('Please provide firstname');
                    return false;
                }
                if (last_name == '') {
                    $('#personal_err').html('Please provide lastname');
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
                if (phone == '') {
                    $('#personal_err').html('Please provide phone');
                    return false;
                }
                if (addr == '') {
                    $('#personal_err').html('Please provide address');
                    return false;
                }
                if (inst == '') {
                    $('#personal_err').html('Please provide Business or Institution');
                    return false;
                }
                if (zip == '') {
                    $('#personal_err').html('Please provide zip');
                    return false;
                }
                if (city == '') {
                    $('#personal_err').html('Please provide city');
                    return false;
                }
                if (state == '') {
                    $('#personal_err').html('Please provide state');
                    return false;
                }
                if (country == '') {
                    $('#personal_err').html('Please provide country');
                    return false;
                }
                if (first_name != '' && last_name != '' && email != '' && phone != '' && addr != '' && inst != '' && zip != '' && city != '' && state != '' && country != '') {

                    // Check is email exists?
                    var url = "http://cnausa.com/functionality/php/is_email_exists.php";
                    var request = {email: email};
                    $.post(url, request).done(function (data) {
                        console.log('Server response: ' + data);
                        if (data > 0) {
                            $('#personal_err').html('Email already in use');
                        } // end if data>0
                        else {
                            // Everything is fine post data and show payment section
                            $('#personal_err').html('');
                            $('#ajax_loading_personal').show();
                            var user = {
                                courseid: courseid,
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

                            var signup_url = 'http://cnausa.com/functionality/php/single_signup.php';
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
            }); // end of $.post(course_url, request)
        } // end if course_name !='Program'        
        else {
            $('#program_err').html('Please select program');
            $('#personal_err').html('Please select program');
        } // end else
    }

    /************************************************************************
     * 
     *                  File upload Group registration form
     * 
     ************************************************************************/

    function get_file_upload_group_registration_form() {

    }

    /************************************************************************
     * 
     *                  Individual registration block
     * 
     ************************************************************************/
    function get_individual_registration_block() {
        $('#personal_section').show();
        $('#group_common_section').hide();
        $('#participants_details').hide();
    }


    /**************************************************************************
     * 
     * 
     *                  Top menu items processing           
     * 
     * 
     **************************************************************************/

    /************************************************************************
     * 
     *                      Show workshops list after click
     * 
     ************************************************************************/

    $('#ws').click(function () {
        self.location = $('#ws').attr('href');
    });

    /************************************************************************
     * 
     *                      Show courses list after click
     * 
     ************************************************************************/


    $('#cs').click(function () {
        self.location = $('#cs').attr('href');
    });


    /************************************************************************
     * 
     *                      Show exams list after click
     * 
     ************************************************************************/


    $('#exam').click(function () {
        self.location = $('#exam').attr('href');
    });

    /************************************************************************
     * 
     *                      Show school's list after click
     * 
     ************************************************************************/

    $('#school').click(function () {
        self.location = $('#school').attr('href');
    });

    /************************************************************************
     * 
     *                      Show Testimonial page after click
     * 
     ************************************************************************/

    $('#testimonial').click(function () {
        self.location = $('#testimonial').attr('href');
    });

    /************************************************************************
     * 
     *                     Show priviate group page after click
     * 
     ************************************************************************/

    $('#group').click(function () {
        self.location = $('#group').attr('href');
    });

    /************************************************************************
     * 
     *                   Show certificate verification form
     * 
     ************************************************************************/

    $('#cert').click(function () {
        self.location = $('#cert').attr('href');
    });

    /************************************************************************
     * 
     *                   Show Gallery page
     * 
     ************************************************************************/

    $('#gallery').click(function () {
        self.location = $('#gallery').attr('href');
    });

    /************************************************************************
     * 
     *
     *                   Events processing block
     *
     *  
     ************************************************************************/

    $('#login_button').click(function (event) {
        event.preventDefault();
        check_login_form();
    });

    /********************************************************************
     * 
     *         Section for dynamically created elements
     * 
     *******************************************************************/

    // **************** Buttons processing events ***********************
    $('.form_div').on('click', 'button', function (event) {
        //alert(event.target.id);
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
            verify_users_upload_form();
        }

    }); // end of $('.form_div').on('click', 'button', function (event)

    // ***********************Links processing events **********************
    $('.form_div').on('click', 'a', function (event) {
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

        if (event.target.id == 'courses') {
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
                console.log(group_status);
                if ($('#group').is(':checked')) {
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
            console.log('Manual registration ...');
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

        if (event.target.id.indexOf("program_") >= 0) {
            var courseid = event.target.id.replace("program_", "");
            get_selected_program_register_form(courseid);
        }

        if (event.target.id == 'upload_group_file') {
            verify_group_general_part();
        }

    }); // end of .form_div links processing events

    $('.form_div').on('change', 'input[type=radio][name=type]', function (event) {
        //alert(event.target.id);
        if (event.target.id == 'group') {
            get_group_registration_block();
            $('#group_common_section').show();
            $('#participants_details').show();
        }
        else {
            get_individual_registration_block();
        }


    }); // end if ('#page').on('change', 'input[type=radio][name=type]', function (event) {

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

}); // end of (document).ready(function ()