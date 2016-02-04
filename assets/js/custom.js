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
     *                   Show program items
     * 
     ************************************************************************/
    function show_program_items(cat_name) {
        $.post("functionality/php/get_programs_list.php", {cat_name: cat_name})
                .done(function (data) {
                    $('#instructions').hide();
                    $("#page").html(data);
                });

    }

    /************************************************************************
     * 
     *                Show school page and Google Map
     * 
     ************************************************************************/

    function show_school_page(cat_name) {
        $.post("functionality/php/get_school_page.php", {cat_name: cat_name})
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
        $.post("functionality/php/get_faq_page.php", function (data) {
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
        $.post("functionality/php/get_testimonial_page.php", function (data) {
            $('#instructions').hide();
            $("#page").html(data);
        });
    }

    /************************************************************************
     * 
     *                    Show courses inside category
     * 
     ************************************************************************/
    function get_category_course(category_id) {
        var url = "functionality/php/get_selected_course.php";
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
        $.post("functionality/php/get_register_page.php", function (data) {
            $('#instructions').hide();
            $("#page").html(data);
        });
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
            var url = "functionality/php/get_group_registration_form.php";
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
        var url = "functionality/php/get_group_manual_registration_form.php";
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

    function verify_group_manual_registration_form() {
        var tot_participants = $('#participants').val();
        var courseid = $('#cat_course li a').attr('id');
        if (courseid !== undefined) {
            console.log('Course id: ' + courseid);
            $('#group_manual_form_err').html('');
        }
        else {
            $('#group_manual_form_err').html('Please select program');

        }
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
            var course_url = 'functionality/php/get_course_id.php';
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
                    var url = "functionality/php/is_email_exists.php";
                    var request = {email: email};
                    $.post(url, request).done(function (data) {
                        console.log('Server response: ' + data);
                        if (data > 0) {
                            $('#personal_err').html('Email already in use');
                        } // end if data>0
                        else {
                            // Everything is fine post data and show payment section
                            $('#personal_err').html('');
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

                            var signup_url = 'functionality/php/single_signup.php';
                            var signup_request = {user: JSON.stringify(user)};
                            $.post(signup_url, signup_request).done(function (data) {
                                console.log(data);
                                // Show payment section



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
     *                Show login form after click
     * 
     ************************************************************************/

    $("#login_link").click(function () {
        console.log('Login link clicked ...');
        $.post("functionality/php/get_login_form.php", function (data) {
            $('#instructions').hide();
            $("#page").html(data);
        });
    });

    /************************************************************************
     * 
     *                Show register form after click
     * 
     ************************************************************************/

    $('#register_item').click(function () {
        console.log('Register clicked ...');
        get_register_page();
    });


    /************************************************************************
     * 
     *                      Show search form
     * 
     ************************************************************************/

    $('#search_item').click(function () {
        console.log('Search clicked ...');
        $.post("functionality/php/get_search_form.php", function (data) {
            $('#instructions').hide();
            $("#page").html(data);
        });
    });

    /************************************************************************
     * 
     *                      Show workshops list after click
     * 
     ************************************************************************/
    $('#ws').click(function () {
        console.log('Workshops clicked ...');
        show_program_items('Workshops');
    });

    /************************************************************************
     * 
     *                      Show courses list after click
     * 
     ************************************************************************/

    $('#cs').click(function () {
        console.log('Courses clicked ...');
        show_program_items('Courses');

    });

    /************************************************************************
     * 
     *                      Show exams list after click
     * 
     ************************************************************************/

    $('#exam').click(function () {
        console.log('Exams clicked ...');
        show_program_items('Exams');

    });

    /************************************************************************
     * 
     *                      Show school's list after click
     * 
     ************************************************************************/

    $('#school').click(function () {
        console.log('Schools clicked ...');
        show_school_page('School');

    });

    /************************************************************************
     * 
     *                      Show FAQ page after click
     * 
     ************************************************************************/
    $('#faq_item').click(function () {
        console.log('FAQ clicked ...');
        get_faq_page();
    });

    /************************************************************************
     * 
     *                      Show Testimonial page after click
     * 
     ************************************************************************/
    $('#testimonial').click(function () {
        console.log('Testimonial clicked ...');
        get_testimonial_page();
    });


    /************************************************************************
     * 
     *
     *                   Events processing block
     *
     *  
     ************************************************************************/

    // Buttons processing events
    $('#page').on('click', 'button', function (event) {
        //alert(event.target.id);
        if (event.target.id == 'login_button') {
            event.preventDefault();
            check_login_form();
        }




    });

    // Links processing events
    $('#page').on('click', 'a', function (event) {
        //alert(event.target.id);
        if (event.target.id.indexOf("cat_") >= 0) {
            var category_id = event.target.id.replace("cat_", "");
            $(".dropdown li a").click(function () {
                $(this).parents(".dropdown").find('.dropdown-toggle').text($(this).text());
                $(this).parents(".dropdown").find('.dropdown-toggle').val($(this).text());
            });
            get_category_course(category_id);
        }

        // Remember Boostrap3 dropdon values
        if (event.target.id == 'categories') {
            $(".dropdown li a").click(function () {
                $(this).parents(".dropdown").find('.dropdown-toggle').text($(this).text());
                $(this).parents(".dropdown").find('.dropdown-toggle').val($(this).text());
            });
        }

        // Remember Boostrap3 dropdon values
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
                get_group_registration_block();
            });
        }

        if (event.target.id == 'manual_group_registration') {
            console.log('Manual registration ...');
            var tot_participants = $('#participants').val();
            get_manual_group_registration_form(tot_participants);
        }

        if (event.target.id == 'upload_group_file') {
            console.log('File upload registration ...');
            get_file_upload_group_registration_form();
        }

        if (event.target.id == 'proceed_to_group_payment') {
            verify_group_manual_registration_form();
        }

        if (event.target.id == 'proceed_to_personal_payment') {
            verify_personal_manual_registration_form();
        }



    }); // end of $('#page').on('click', 'a', function (event)

    $('#page').on('change', 'input[type=radio][name=type]', function (event) {
        //alert(event.target.id);
        if (event.target.id == 'group') {
            get_group_registration_block();
            $('#group_common_section').show();
            $('#participants_details').show();
        }
        else {
            get_individual_registration_block();
        }


    }) // end if ('#page').on('change', 'input[type=radio][name=type]', function (event) {




}); // end of (document).ready(function ()