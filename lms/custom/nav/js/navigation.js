
$(document).ready(function () {

    $('#mpermission').click(function () {
        var url = 'https://medical2.com/lms/admin/roles/manage.php';
        //window.open = url;
        var win = window.open(url, '_blank');
        win.focus();
    });

    // Monitor all events
    /*
     $('body').on("click mousedown mouseup focus blur keydown change mouseup click dblclick mousemove mouseover mouseout mousewheel keydown keyup keypress textInput touchstart touchmove touchend touchcancel resize scroll zoom focus blur select change submit reset", function (e) {
     console.log(e);
     });
     */
    // Monitor only few events ...
    /*
     $('body').on("click change click  blur select change submit reset", function (e) {
     console.log(e);
     });
     */


    var domain = 'medical2.com';
    var dialog_loaded;
    console.log("ready!");
    function update_navigation_status__menu(item_title) {
        $(".breadcrumb-nav").html('');
        $(".breadcrumb-nav").html("<ul class='breadcrumb'><li><a href='https://" + domain + "/lms/my/'>Dashboard</a> <span class='divider'> <span class='accesshide '><span class='arrow_text'>/</span>&nbsp;</span><span class='arrow sep'>►</span> </span></li><li><a href='#'>" + item_title + "</a></li>");
    }

    function get_price_items_from_category(id) {
        var url = "/lms/custom/prices/list.php";
        if (id != 'sch' && id != 'course_management') {
            $.post(url, {id: id}).done(function (data) {
                console.log(data);
                try {
                    var price_obj = $.parseJSON(data);
                    update_navigation_status__menu(price_obj.item_title);
                    $('#region-main').html(price_obj.item_data);
                } // end try 
                catch (e) {
                    window.location = "https://medical2.com/login";
                }
            }); // end of post
        } // end if
    }

    function get_faq_edit_page() {
        var url = "/lms/custom/faq/index.php";
        $.post(url, {id: 1}).done(function (data) {
            $('#region-main').html(data);
        });
    }

    function update_faq_page(data) {
        var url = "/lms/custom/faq/edit.php";
        $.post(url, {data: data}).done(function () {
            $('#region-main').html("<p align='center'>Data successfully saved. </p>");
        });
    }

    function get_about_edit_page() {
        var url = "/lms/custom/about/index.php";
        $.post(url, {id: 1}).done(function (data) {
            $('#region-main').html(data);
        });
    }

    function update_about_page(data) {
        var url = "/lms/custom/about/edit.php";
        $.post(url, {data: data}).done(function () {
            $('#region-main').html("<p align='center'>Data successfully saved. </p>");
        });
    }

    function get_testimonial_page() {
        var url = "/lms/custom/testimonial/index.php";
        $.post(url, {id: 1}).done(function (data) {
            $('#region-main').html(data);
        });
    }

    function update_terms_page(data) {
        var url = "/lms/custom/terms/update_terms.php";
        $.post(url, {data: data}).done(function () {
            $('#region-main').html("<p align='center'>Data successfully saved. </p>");
        });
    }

    function get_terms_page() {
        var url = "/lms/custom/terms/get_page.php";
        $.post(url, {id: 1}).done(function (data) {
            $('#region-main').html(data);
        });
    }

    function update_testimonial_page(data) {
        var url = "/lms/custom/testimonial/edit.php";
        $.post(url, {data: data}).done(function () {
            $('#region-main').html("<p align='center'>Data successfully saved. </p>");
        });
    }

    function get_gallery_index_page() {
        var url = "/lms/custom/gallery/index.php";
        $.post(url, {id: 1}).done(function (data) {
            $('#region-main').html(data);
        });
    }

    function upload_files() {
        var url = "/lms/custom/gallery/upload.php";
        var file_data = $('#files').prop('files');
        var state = $('#state').val();
        var month = $('#month').val();
        var year = $('#year').val();
        var comment = $('#comment').val();
        if (file_data == '' || file_data.length == 0) {
            $('#gallery_err').html('Please select files to be upload ...');
            return false;
        }

        if (state == 0 || month == 0 || year == 0) {
            $('#gallery_err').html('Please select state, month and year');
            return false;
        } // end if state==0 || month==0 || year==0

        if (file_data != '' && file_data.length != 0 && state > 0 && month > 0 && year > 0) {

            $('#gallery_err').html('');
            $('#comment').val('');
            var form_data = new FormData();
            $.each(file_data, function (key, value) {
                form_data.append(key, value);
            });
            form_data.append('state', state);
            form_data.append('month', month);
            form_data.append('year', year);
            form_data.append('comment', comment);
            $('#loader').show();
            $.ajax({
                url: url,
                data: form_data,
                processData: false,
                contentType: false,
                type: 'POST',
                success: function () {
                    $('#loader').hide();
                    refresh_gallery_thumbs();
                }
            });
        } // end if file_data != '' && file_data.length != 0 && state > 0 && month > 0 && year > 0
    }

    function add_user_to_slot() {
        var slotid = $('#slots').val();
        var username = $('#users').val();
        var schedulerid = $('#schedulerid').val();
        if (username == '') {
            alert('Please slelect student and workshop!');
            return;
        } // end if
        else {
            var url = "/lms/custom/utils/get_userid_by_fio.php";
            $.post(url, {username: username}).done(function (userid) {
                console.log('User ID: ' + userid);
                console.log('Slot ID:' + slotid);
                if (userid > 0 && slotid != '') {
                    var url = "/lms/custom/schedule/add_user_to_slot.php";
                    $.post(url, {slotid: slotid, userid: userid, schedulerid: schedulerid}).done(function (data) {
                        console.log('Server response: ' + data);
                        document.location.reload();
                    });
                } // end if userid>0 && slotid>0
                else {
                    alert('Please select student and workshop!');
                }
            });
        } // end else
    }

    function refresh_gallery_thumbs() {
        var url = "/lms/custom/gallery/refresh.php";
        $.post(url, {id: 1}).done(function (data) {
            $('#thumb_list').html(data);
        });
    }

    function filter() {
        var state = $('#state').val();
        var month = $('#month').val();
        var year = $('#year').val();
        var url = "/lms/custom/gallery/filter.php";
        $.post(url, {state: state, month: month, year: year}).done(function (data) {
            $('#thumb_list').html(data);
        });
    }

    function delete_gallery_img() {
        console.log('Inside delete_gallery_img ...');
        $('#gallery_err').html('');
        var items = new Array();
        //$( "input:checked" ).val()
        $("input:checked").each(function () {
            if ($(this).val() != '') {
                items.push($(this).val());
            }
        });
        console.log('Items array: ' + items);
        if (items.length > 0) {
            $('#gallery_err').html('');
            if (confirm('Are you sure want to delete selected items?')) {
                var url = "/lms/custom/gallery/delete.php";
                $.post(url, {items: items}).done(function () {
                    refresh_gallery_thumbs();
                });
            }
        } else {
            $('#gallery_err').html('Please select items to be deleted');
        }
    }

    function get_google_map_page() {
        var id = 5; // This is Nursing school category id, but be carefull
        var url = "/lms/custom/google_map/index.php";
        $.post(url, {category_id: id}).done(function (data) {
            $('#region-main').html(data);
            refresh_map();
        });
    }

    function update_tax_item(item) {
        var id = item.replace("tax_", "");
        var taxid = "#tax_val" + id;
        var status_box = "#tax_status_" + id;
        var tax = $(taxid).val();
        var url = "/lms/custom/taxes/update.php";
        $.post(url, {id: id, tax: tax}).done(function (data) {
            $(status_box).html(data);
        });
    }

    function get_tax_item(id) {
        var url = "/lms/custom/taxes/get_tax_item.php";
        $.post(url, {id: id}).done(function (data) {
            $('#state_taxes').html(data);
        });
    }

    function update_invoice_data() {
        var phone = $('#phone').val();
        var fax = $('#fax').val();
        var email = $('#email').val();
        var site = $('#site').val();
        if (phone != '' && fax != '' && email != '' && site != '') {
            $('#invoice_status').html('');
            var url = "/lms/custom/invoices/update.php";
            $.post(url, {phone: phone, fax: fax, email: email, site: site}).done(function (data) {
                $('#invoice_status').html(data);
            });
        } // end if phone!='' && fax!='' && email!='' && site!=''
        else {
            $('#invoice_status').html("<span style='color:red'>Please provide all data</span>");
        } // end else 
    }

    function  update_map_item(item) {
        var category_id = 5; // Nursing school category id
        var courseid = item.replace("map_", "");
        var item_lat_id = '#lat_' + courseid;
        var item_lng_id = '#lng_' + courseid;
        var item_marker_id = '#marker_' + courseid;
        var item_lat = $(item_lat_id).val();
        var item_lng = $(item_lng_id).val();
        var item_marker = $(item_marker_id).val();
        console.log(item_lat_id);
        console.log('Lat: ' + item_lat);
        console.log(item_lng_id);
        console.log('Lng: ' + item_lng);
        console.log(item_marker_id);
        console.log('Marker: ' + item_marker);
        if (item_lat == 0 || item_lat == '' || item_lng == '0' || item_lng == '' || item_marker == '') {
            $("#map_err").html('Please provide coordinates and marker text');
        } else {
            if (validateNum(item_lat) && validateNum(item_lng)) {
// Prepare and send AJAX request...
                $("#map_err").html('');
                var url = "/lms/custom/google_map/edit.php";
                var request = {
                    category_id: category_id,
                    courseid: courseid,
                    lat: item_lat,
                    lng: item_lng,
                    marker: item_marker};
                $.post(url, request).done(function (data) {
                    refresh_map();
                });
            } // end else
            else {
                $("#map_err").html('Please provide correct item coordinates!');
            }
        }
    }

    function get_promotion_page() {
        var url = "/lms/custom/promotion/get_promotion_page.php";
        $.post(url, {id: 1}).done(function (data) {
            $('#region-main').html(data);

            $.post('/lms/custom/utils/workshops.json', {id: 1}, function (data) {
                $('#camp_ws').typeahead({source: data, items: 240});
            }, 'json');

            $.post('/lms/custom/utils/states.json', {id: 1}, function (data) {
                $('#camp_state').typeahead({source: data, items: 240});
            }, 'json');

            $.post('/lms/custom/utils/cities.json', {id: 1}, function (data) {
                $('#camp_city').typeahead({source: data, items: 52000});
            }, 'json');

            $.post('/lms/custom/utils/programs.json', {id: 1}, function (data) {
                $('#camp_program').typeahead({source: data, items: 52000});
            }, 'json');

        });
    }

    function refresh_map() {
        var url = "/lms/custom/google_map/refresh.php";
        var category_id = 5; // Nursing school category id
        var request = {category_id: category_id};
        $.post(url, request).done(function (data) {
            var $obj_data = $.parseJSON(data);
            // Create a map object and specify the DOM element for display.
            var map = new google.maps.Map(document.getElementById('map'), {
//               //center: new google.maps.LatLng(3.171368, 101.653404),
                scrollwheel: false,
                zoom: 8
            }); // end var map            
            var latLngs = [];
            var bounds = new google.maps.LatLngBounds();
            var infowindow = new google.maps.InfoWindow();
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
                        infowindow.setContent(m.marker_text);
                        infowindow.open(map, marker);
                    }
                })(marker, i));
            }) // end each            
            map.fitBounds(bounds);
        }); // post(url, request).done(function (data)
    }

    function get_certificates_page() {
        var url = "/lms/custom/certificates/list.php";
        $.post(url, {id: 1}).done(function (data) {
            $('#region-main').html(data);
        });
    }

    function get_hotels_page() {
        var url = "/lms/custom/hotels/list.php";
        $.post(url, {id: 1}).done(function (data) {
            $('#region-main').html(data);

        });
    }


    function get_inventory_page() {
        var url = "/lms/custom/inventory/get_inventory_page.php";
        $.post(url, {id: 1}).done(function (data) {
            $('#region-main').html(data);

            $.post('/lms/custom/utils/books.json', {id: 1}, function (data) {
                $('#b_search').typeahead({source: data, items: 2400});
            }, 'json');

            $('#b_start').datepicker();
            $('#b_end').datepicker();
        });
    }

    function get_hotel_expenses_page() {
        var url = "/lms/custom/inventory/get_hotel_expenses_page.php";
        $.post(url, {id: 1}).done(function (data) {
            $('#region-main').html(data);

            $.post('/lms/custom/utils/hotels.json', {id: 1}, function (data) {
                $('#hotel_search').typeahead({source: data, items: 2400});
            }, 'json');

            $('#h_start').datepicker();
            $('#h_end').datepicker();
        });
    }

    function get_state_taxes_list() {
        var url = "/lms/custom/taxes/index.php";
        $.post(url, {id: 1}).done(function (data) {
            $('#region-main').html(data);
        });
    }

    function get_invoice_spec_page() {
        var url = "/lms/custom/invoices/index.php";
        $.post(url, {id: 1}).done(function (data) {
            if (data != 'non_auth') {
                $('#region-main').html(data);
            } // end if data != 'non_auth' 
            else {
                window.location = "https://medical2.com/login";
            } // end else 
        });
    }

    function validateNum(str) {
        var patForReqdFld = /^(\-)?([\d]+(?:\.\d{1,2})?)$/;
        return patForReqdFld.test(str);
    }


    function update_item_price(id) {
        var installment;
        var states = [];
        var courseid = id.replace("price_", "");
        var course_cost_id = '#cost_' + courseid;
        var course_discount_id = '#item_' + courseid;
        var course_group_discount_id = '#group_' + courseid;
        var price_id_err = '#price_err_' + courseid;
        var states_id = '#states_' + courseid;
        var installment_id = '#installment_' + courseid;
        var num_payments_id = '#num_payments_' + courseid;
        var num_payments = $(num_payments_id).val();
        var taxes_num = '#taxes_' + courseid;
        var taxes; // checkbox
        var expire_num = '#expire_' + courseid;
        var expire, pass; // checkboxes
        var pass_num = 'pass_' + courseid;
        if ($(pass_num).is(':checked')) {
            pass = 1;
        } // end if 
        else {
            pass = 0;
        }

        if ($(taxes_num).is(':checked')) {
            taxes = 1;
        } // end if 
        else {
            taxes = 0;
        }
        console.log('Taxes status: ' + taxes);
        if ($(expire_num).is(':checked')) {
            expire = 1;
        } else {
            expire = 0;
        }
        console.log('Expiration status: ' + expire);
        if ($(installment_id).is(':checked')) {
            installment = 1;
            if (num_payments < 2) {
                $(price_id_err).html('Please select num of installment payments');
                return false;
            }
        } // end if $('#installment').is(':checked')
        else {
            installment = 0;
        }
        console.log(states_id);
        $(states_id).each(function (i, selected) {
            states[i] = $(selected).val();
        });
        console.log(states);
        if (states.length == 0 || states[0] == null) {
            $(price_id_err).html('Please select item states');
            return false;
        }

        var course_cost = $(course_cost_id).val();
        var course_discount = $(course_discount_id).val();
        var course_group_discount = $(course_group_discount_id).val();
        if (course_cost == '' || course_cost == 0) {
            $(price_id_err).html('Please provide item cost');
            return false;
        }

        if (course_cost != 0 && states.length > 0) {
            $(price_id_err).html('');
            if (validateNum(course_cost)) {
                $('#price_err').html('');
                var url = "/lms/custom/prices/edit.php";
                var request = {
                    course_id: courseid,
                    course_cost: course_cost,
                    pass: pass,
                    course_discount: course_discount,
                    course_group_discount: course_group_discount,
                    installment: installment,
                    num_payments: num_payments,
                    taxes: taxes,
                    expire: expire,
                    states: JSON.stringify(states)};
                $.post(url, request).done(function (data) {
                    $(price_id_err).html("<span style='color:green;'>" + data + "</span>");
                });
            } // end if validateNum(course_cost
            else {
                $(price_id_err).html('Invalid item cost');
            }
        } // end if course_cost != 0 && states.length > 0        
    }

    function get_private_groups_requests_list() {
        var url = "/lms/custom/groups/index.php";
        $.post(url, function (data) {
            $('#region-main').html(data);
        });
    }

    function get_category_courses(id) {
        var url = "/lms/custom/certificates/get_category_courses.php";
        $.post(url, {id: id}).done(function (data) {
            $('#category_courses').html(data);
        });
    }

    function get_invoice_category_courses(id) {
        var url = "/lms/custom/invoices/get_invoice_category_courses.php";
        $.post(url, {id: id}).done(function (data) {
            $('#invoice_category_courses').html(data);
            $('#invoice_client_row').show();
            $('#invoice_amount_row').show();
            $('#invoice_email_row').show();
        });
    }

    function get_category_courses2(id) {
        var url = "/lms/custom/certificates/get_category_courses2.php";
        $.post(url, {id: id}).done(function (data) {
            $('#send_category_courses').html(data);
        });
    }

    function get_certificate_item(id) {
        console.log('Page: ' + id);
        var url = "/lms/custom/certificates/get_certificate_item.php";
        $.post(url, {id: id}).done(function (data) {
            $('#certificates_container').html(data);
        });
    }

    function get_course_users(id) {
        var url = "/lms/custom/certificates/get_course_users.php";
        $.post(url, {id: id}).done(function (data) {
            $('#enrolled_users').html(data);
            var json_path = id + ".json";
            $.get('/lms/custom/utils/' + json_path, function (data) {
                $("#users").typeahead({source: data, items: 27});
            }, 'json');
        });
    }

    function get_course_users2(id) {
        console.log('Course ID: ' + id);
        var url = "/lms/custom/certificates/get_course_users2.php";
        $.post(url, {id: id}).done(function (data) {
            $('#send_enrolled_users').html(data);
            var json_path = id + ".json";
            $.get('/lms/custom/utils/' + json_path, function (data) {
                $("#send_users").typeahead({source: data, items: 27});
            }, 'json');
        });
    }

    function get_course_promotion_users(id) {
        var url = "/lms/custom/promotion/get_course_promotion_users.php";
        $.post(url, {id: id}).done(function (data) {
            $('#promotion_users').html(data);
        });
    }

    function send_invoice_to_user() {
        var url = "/lms/custom/invoices/send_invoice.php";
        $.post(url, {id: 1}).done(function (data) {
            $('#region-main').html(data);
        });
    }

    function send_invoice() {
        var courseid = $('#courses').val();
        var username = $('#users').val();
        if (username == '') {
            $('#invoice_status').html('Please provide username');
            return;
        } // end if
        else {
            var url = "/lms/custom/utils/get_userid_by_fio.php";
            $.post(url, {username: username}).done(function (userid) {
                if (userid > 0 && courseid > 0) {
                    if (confirm('Send invoice to user?')) {
                        $('#invoice_status').html('');
                        var url = "/lms/custom/invoices/send_invoice_send.php";
                        $.post(url, {userid: userid, courseid: courseid}).done(function (data) {
                            $('#invoice_status').html(data);
                        });
                    } // end if confirm('Send invoice to user?')
                } // end if userid > 0 && courseid > 0
                else {
                    $('#invoice_status').html("<span style='color:red;'>Please select program and user</span>");
                } // end else
            });
        } // end else



    }

    function get_open_invoices_page() {
        var url = "/lms/custom/invoices/open_invoices.php";
        $.post(url, {id: 1}).done(function (data) {
            $('#region-main').html(data);
            $.get('/lms/custom/utils/data.json', function (data) {
                $("#search_invoice_input").typeahead({source: data, items: 24});
            }, 'json');
        }); // end of post
    }

    function get_paid_invoice_page() {
        var url = "/lms/custom/invoices/paid_invoices.php";
        $.post(url, {id: 1}).done(function (data) {
            $('#region-main').html(data);
            $.get('/lms/custom/utils/data.json', function (data) {
                $("#search_invoice_input").typeahead({source: data, items: 24});
            }, 'json');
        });
    }

    function get_installment_page() {
        var url = "/lms/custom/installment/get_installment_page.php";
        $.post(url, {id: 1}).done(function (data) {
            $('#region-main').html(data);
            $.get('/lms/custom/utils/courses.json', function (data) {
                $("#installment_program").typeahead({source: data, items: 24});
            }, 'json');
            $.get('/lms/custom/utils/data.json', function (data) {
                $("#installment_user ").typeahead({source: data, items: 24});
            }, 'json');
            $.get('/lms/custom/utils/installment_users.json', function (data) {
                $("#installment_search").typeahead({source: data, items: 24});
            }, 'json');
            $('#subs_start').datepicker();
            $('#subs_exp').datepicker();
        });
    }

    function get_users_stats_page() {
        var url = "/lms/custom/stats/get_stats_page.php";
        $.post(url, {id: 1}).done(function (data) {
            $('#region-main').html(data);
        });
    }

    function get_cash_payments_page() {
        var url = "/lms/custom/payments/get_cash_payments_page.php";
        $.post(url, {id: 1}).done(function (data) {
            $('#region-main').html(data);
        });
        $.get('/lms/custom/utils/data.json', function (data) {
            $("#search_payment").typeahead({source: data, items: 24});
        }, 'json');
    }

    function get_check_payments_page() {
        var url = "/lms/custom/payments/get_cheque_payments.php";
        $.post(url, {id: 1}).done(function (data) {
            $('#region-main').html(data);
            $.get('/lms/custom/utils/data.json', function (data) {
                $("#search_payment").typeahead({source: data, items: 24});
            }, 'json');
        });
    }

    function get_course_workshops(id) {
        console.log('Course ID: ' + id);
        var url = "/lms/custom/promotion/get_course_workshops.php";
        $.post(url, {id: id}).done(function (data) {
            $('#course_workshops').html(data);
        });
    }

    function get_credit_card_payments_page() {
        var url = "/lms/custom/payments/get_card_payments.php";
        $.post(url, {id: 1}).done(function (data) {
            $('#region-main').html(data);
            $.get('/lms/custom/utils/data.json', function (data) {
                $("#search_payment").typeahead({source: data, items: 24});
            }, 'json');
        });
    }

    function get_free_payments() {
        var url = "/lms/custom/payments/get_free_payments.php";
        $.post(url, {id: 1}).done(function (data) {
            $('#region-main').html(data);
            $.get('/lms/custom/utils/data.json', function (data) {
                $("#search_payment").typeahead({source: data, items: 24});
            }, 'json');
        });
    }

    function get_payment_log_page() {
        var url = "/lms/custom/payments/get_payments_log_page.php";
        $.post(url, {id: 1}).done(function (data) {
            $('#region-main').html(data);
        });
    }

    function make_invoice_paid(id) {
        var status_id = '#invoice_status_' + id;
        var payment_type_id = '#payment_type_' + id;
        var payment_type = $(payment_type_id).val();
        if (payment_type > 0) {
            $(status_id).html('');
            if (confirm('Make current invoice as paid?')) {
                var url = "/lms/custom/invoices/make_invoice_paid.php";
                $.post(url, {id: id, payment_type: payment_type}).done(function (data) {
                    $(status_id).html(data);
                });
            } // end if confirm            
        } // end if payment_type>0
        else {
            $(status_id).html("<span style='color:red;'>Please select payment type</span>");
        } // end else
    }

    function get_students_modal_box() {
        console.log('Dialog loaded: ' + dialog_loaded);
        var courseid = $('#courseid').val();
        var scheduler = $('#scheduler').val();
        if (dialog_loaded !== true) {
            console.log('Script is not yet loaded starting loading ...');
            dialog_loaded = true;
            var js_url = "https://" + domain + "/assets/js/bootstrap.min.js";
            $.getScript(js_url)
                    .done(function () {
                        console.log('Script bootstrap.min.js is loaded ...');
                        var url = "https://" + domain + "/lms/custom/schedule/get_students_box.php";
                        var request = {courseid: courseid, scheduler: scheduler};
                        $.post(url, request).done(function (data) {
                            $("body").append(data);
                            $("#myModal").modal('show');
                            $.get('/lms/custom/utils/data.json', function (data) {
                                $('#users').typeahead({source: data, items: 24});
                            }, 'json');
                            $.get('/lms/custom/utils/workshops.json', function (data) {
                                $('#slots').typeahead({source: data, items: 24});
                            }, 'json');
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

    function search_payment(typeid) {
        var item = $('#search_payment').val();
        var url = "/lms/custom/payments/search_payment.php";
        if (item == '') {
            $('#payment_err').html('Please provide search criteria');
        } // end if item==''
        else {
            $('#payment_err').html('');
            $('#ajax_loader').show();
            $.post(url, {item: item, typeid: typeid}).done(function (data) {
                $('#ajax_loader').hide();
                $('#pagination').hide();
                $('#payment_container').html(data);
            });
        } // end else        
    }

    function add_installment_user() {
        var username = $('#users').val();
        if (username == '') {
            $('#add_inst_user_status').html("<span style='color:red;'>Please provide user</span>");
            return;
        } // end if username==''
        else {
            var url = "/lms/custom/utils/get_userid_by_fio.php";
            $.post(url, {username: username}).done(function (userid) {
                console.log('User id: ' + userid);
                var courseid = $('#courses').val();
                console.log('Course id: ' + courseid);
                var num = $('#inst_num').val();
                console.log('Payments num:' + num);
                if (userid > 0 && courseid > 0 && num > 0) {
                    if (confirm('Add installment user?')) {
                        $('#add_inst_user_status').html('');
                        var url = "/lms/custom/installment/add_installment_user.php";
                        $.post(url, {userid: userid, num: num, courseid: courseid}).done(function (data) {
                            $('#add_inst_user_status').html(data);
                        });
                    } // end if confirm
                } // end if userid>0 && num>0 && sum>0
                else {
                    $('#add_inst_user_status').html("<span style='color:red;'>Please select user and provide installment params</span>");
                } // end else
            });
        } // end else
    }

    function send_certicicate_to_user() {
        var courseid = $('#send_courses').val();
        var username = $('#send_users').val();
        console.log('User name: ' + username);
        if (username == '') {
            $('#send_cert_err').html("<span style='color:red;'>Please provide user</span>");
            return;
        } // end if username == ''
        else {
            var url = "/lms/custom/utils/get_userid_by_fio.php";
            $.post(url, {username: username}).done(function (userid) {
                console.log('Course ID: ' + courseid);
                console.log('User ID: ' + userid);
                if (userid > 0 && courseid > 0) {
                    var url = "/lms/custom/certificates/get_course_completion.php";
                    $.post(url, {userid: userid, courseid: courseid}).done(function (completion_date) {
                        if (completion_date == 0) {
                            if (confirm('User did not complete the course, send certificate anyway?')) {
                                var url2 = "/lms/custom/certificates/send_certificate.php";
                                $.post(url2, {courseid: courseid, userid: userid, completion_date: completion_date}).done(function (data) {
                                    $('#send_cert_err').html(data);
                                });
                            } // end if confirm
                        } // end if data==0 
                        else {
                            if (confirm('Send certificate to user?')) {
                                $('#send_cert_err').html('');
                                $.post(url2, {courseid: courseid, userid: userid, completion_date: completion_date}).done(function (data) {
                                    $('#send_cert_err').html(data);
                                });
                            } // end if conform
                        } // end else
                    }); // end of $.post get_course_completion.php
                } // end if userid>0 && courseid>0
                else {
                    $('#send_cert_err').html("<span style='color:red;'>Please select program and user</span>");
                } // end else
            });
        } // end else
    }

    function print_certificate_address_label() {
        var courseid = $('#courses').val();
        var userid = $('#users').val();
        if (userid > 0 && courseid > 0) {
            if (confirm('Print address label?')) {
                $('#send_cert_err').html('');
                var url = "/lms/custom/certificates/print_label.php";
                $.post(url, {courseid: courseid, userid: userid}).done(function (data) {
                    $('#send_cert_err').html(data);
                });
            } // end if confirm
        } // end if userid>0 && courseid>0
        else {
            console.log('Incorrect data!');
            $('#send_cert_err').html("<span style='color:red;'>Please select program and user</span>");
        } // end else    	
    }

    function print_certificate() {
        var courseid = $('#courses').val();
        var userid = $('#users').val();
        if (userid > 0 && courseid > 0) {
            if (confirm('Print Certificate?')) {
                $('#send_cert_err').html('');
                var url = "/lms/custom/certificates/print_certificate.php";
                $.post(url, {courseid: courseid, userid: userid}).done(function (data) {
                    $('#send_cert_err').html(data);
                });
            } // end if confirm
        } // end if userid>0 && courseid>0
        else {
            console.log('Incorrect data!');
            $('#send_cert_err').html("<span style='color:red;'>Please select program and user</span>");
        }
    }

    function search_partial_payments() {
        var item = $('#search_partial').val();
        if (item != '') {
            var url = "/lms/custom/partial/search_partial.php";
            $('#ajax_loader').show();
            $.post(url, {item: item}).done(function (data) {
                $('#ajax_loader').hide();
                $('#partial_container').html(data);
            });
        } // end if item!=''
    }

    function show_private_group_request_detailes(id) {
        var container_id = '#det_' + id;
        var status = $(container_id).is(":visible");
        console.log(status);
        if (status == false) {
            $(container_id).show();
        } else {
            $(container_id).hide();
        }
    }

    function get_revenue_report() {
        var url = "/lms/custom/reports/get_revenue_report.php";
        $.post(url, {id: 1}).done(function (data) {
            $('#region-main').html(data);
        });
    }

    function get_revenue_report_data() {
        var courseid = $('#courses').val();
        var from = $('#datepicker1').val();
        var to = $('#datepicker2').val();
        if (from != '' && to != '') {
            $('#revenue_report_err').html('');
            $('#ajax_loading').show();
            var url = "/lms/custom/reports/get_revenue_report_data.php";
            $.post(url, {courseid: courseid, from: from, to: to}).done(function (data) {
                $('#ajax_loading').hide();
                $('#revenue_report_container').html(data);
                $('#stat_start').datepicker();
                $('#stat_end').datepicker();
            });
        } // end if courseid>0 && from!='' && to!=''
        else {
            $('#revenue_report_err').html("<span style='color:red;'>Please select program and dates</span>");
        }
    }

    function get_program_report() {
        var url = "/lms/custom/reports/get_program_report.php";
        $.post(url, {id: 1}).done(function (data) {
            $('#region-main').html(data);
        });
    }

    function get_feedback_page() {
        var url = "/lms/custom/feedback/list.php";
        $.post(url, {id: 1}).done(function (data) {
            $('#region-main').html(data);
        });
    }

    function get_program_report_data() {
        var courseid = $('#courses').val();
        var from = $('#datepicker1').val();
        var to = $('#datepicker2').val();
        if (courseid > 0 && from != '' && to != '') {
            $('#program_report_err').html('');
            $('#ajax_loading').show();
            var url = "/lms/custom/reports/get_program_report_data.php";
            $.post(url, {courseid: courseid, from: from, to: to}).done(function (data) {
                $('#ajax_loading').hide();
                $('#program_report_container').html(data);
            });
        } // end if courseid>0 && from!='' && to!=''
        else {
            $('#program_report_err').html("<span style='color:red;'>Please select program and dates</span>");
        }
    }

    function export_program_report() {
        if (confirm('Export data to CSV?')) {
            var courseid = $('#courses').val();
            var from = $('#datepicker1').val();
            var to = $('#datepicker2').val();
            if (courseid > 0 && from != '' && to != '') {
                var url = "/lms/custom/reports/program_report_export.php";
                $.post(url, {courseid: courseid, from: from, to: to}).done(function (data) {
                    $('#ajax_loading').hide();
                    //$('#program_report_container').html(data);
                });
            } // end if courseid > 0 && from != ''
            else {
                alert('Incorrect program data!');
            }
        } // end if confirm

    }

    function get_workshop_report() {
        var url = "/lms/custom/reports/get_workshop_report.php";
        $.post(url, {id: 1}).done(function (data) {
            $('#region-main').html(data);
        });
    }

    function get_state_workshops() {
        var stateid = $('#states').val();
        //if (stateid>0) {
        var url = "/lms/custom/reports/get_state_workshops.php";
        $.post(url, {stateid: stateid}).done(function (data) {
            $('#workshops_dropdown').html(data);
        });
        //} // end if stateid>0
    }

    function get_workshop_report_data() {
        var courseid = $('#workshops').val();
        var from = $('#datepicker1').val();
        var to = $('#datepicker2').val();
        if (courseid > 0 && from != '' && to != '') {
            $('#workshop_report_err').html('');
            $('#ajax_loading').show();
            var url = "/lms/custom/reports/get_workshops_report_data.php";
            $.post(url, {courseid: courseid, from: from, to: to}).done(function (data) {
                $('#ajax_loading').hide();
                $('#workshops_report_container').html(data);
            });
        } // end if courseid > 0 && from != '' && to != ''
        else {
            $('#workshop_report_err').html("<span style='color:red;'>Please select workshop and dates</span>");
        }
    }

    function get_certificate() {
        var url = "/lms/custom/nav/get_certificate.php";
        $.post(url, {id: 1}).done(function (data) {
            $('#region-main').html(data);
        });
    }

    function renew_certificate(userid, courseid) {
        var url = "/lms/custom/nav/renew_certificate.php";
        var cert = {userid: userid, courseid: courseid};
        $.post(url, {cert: JSON.stringify(cert)}).done(function (data) {
            $('#region-main').html(data);
        });
    }

    function send_invoice_renew() {
        if (confirm('Send invoice?')) {
            var url = "/lms/custom/nav/send_renew_certificate.php";
            $.post(url, {id: 1}).done(function (data) {
                $('#region-main').html(data);
            });
        } // end if confirm
    }

    function get_renew_fee_page() {
        var url = "/lms/custom/payments/get_renew_fee_page.php";
        $.post(url, {id: 1}).done(function (data) {
            $('#region-main').html(data);
        });
    }

    function update_renew_fee() {
        var fee = $('#renew_fee2').val();
        //var renew_fee=fee.trim();
        var renew_fee = fee;
        console.log('Fee: ' + renew_fee);
        if (renew_fee > 0) {
            var url = "/lms/custom/payments/update_renew_fee.php";
            $.post(url, {fee: renew_fee}).done(function (data) {
                $('#fee_err').html(data);
            });
        } // end if fee>0
        else {
            $('#fee_err').html('Please provide correct renew fee');
        }
    }

    function search_open_invoice_user() {
        var item = $('#search_invoice_input').val();
        var url = "/lms/custom/invoices/search_open_invoice.php";
        if (item == '') {
            $('#invoice_err').html('Please provide search criteria');
        } // end if item==''
        else {
            $('#invoice_err').html('');
            $('#ajax_loader').show();
            $.post(url, {item: item}).done(function (data) {
                $('#ajax_loader').hide();
                $('#open_invoices_container').html(data);
                $('#pagination').hide();
            });
        } // end else 
    }

    function search_paid_invoice_user() {
        var item = $('#search_invoice_input').val();
        var url = "/lms/custom/invoices/search_paid_invoice.php";
        if (item == '') {
            $('#invoice_err').html('Please provide search criteria');
        } // end if item==''
        else {
            $('#invoice_err').html('');
            $('#ajax_loader').show();
            $.post(url, {item: item}).done(function (data) {
                $('#ajax_loader').hide();
                $('#open_invoices_container').html(data);
                $('#pagination').hide();
            });
        } // end else 
    }

    function search_credit_card_payment() {
        var item = $('#search_payment').val();
        if (item == '') {
            $('#payment_err').html('Please provide search criteria');
        } else {
            $('#payment_err').html('');
            $('#ajax_loader').show();
            var url = "/lms/custom/payments/search_credit_card_payment.php";
            $.post(url, {item: item}).done(function (data) {
                $('#ajax_loader').hide();
                $('#card_payments_container').html(data);
                $('#pagination').hide();
            });
        }
    }

    function search_refund_page() {
        var item = $('#search_payment').val();
        if (item == '') {
            $('#payment_err').html('Please provide search criteria');
        } else {
            $('#payment_err').html('');
            $('#ajax_loader').show();
            var url = "/lms/custom/payments/search_refund_payment.php";
            $.post(url, {item: item}).done(function (data) {
                $('#ajax_loader').hide();
                $('#card_payments_container').html(data);
                $('#pagination').hide();
            });
        }
    }

    function search_certificate() {
        var item = $('#search_certificate').val();
        if (item == '') {
            $('#cert_err').html('Please provide search criteria');
        } // end if item==''
        else {
            $('#cert_err').html('');
            $('#ajax_loader').show();
            var url = "/lms/custom/certificates/search_certificate.php";
            $.post(url, {item: item}).done(function (data) {
                $('#ajax_loader').hide();
                $('#certificates_container').html(data);
                $('#pagination').hide();
            });
        } // end else 
    }

    function select_all() {
// console.log('Select all function ....');
        $('.cert').each(function () { //loop through each checkbox
            this.checked = true; //select all checkboxes with class "cert"              
        });
    }

    function deselect_all() {
//console.log('Deselect all function ....');
        $('.cert').each(function () { //loop through each checkbox
            this.checked = false; //select all checkboxes with class "cert"              
        });
    }

    function add_partial_payment(source) {
        var courseid = $('#register_courses').val();
        var userid = $('#users').val();
        var sum = $('#sum').val();
        var slotid = $('#register_cities').val();
        if (courseid > 0 && userid > 0 && sum != '') {
            $('#partial_err').html('');
            var url = "/lms/custom/partial/add_partial_payment.php";
            $.post(url, {courseid: courseid, userid: userid, sum: sum, source: source, slotid: slotid}).done(function (data) {
                $('#partial_err').html("<span style='color:black;'>" + data + "</span>");
            });
        } // end if courseid>0 && userid>0 && sum!=''
        else {
            $('#partial_err').html('Please select program and user and provide paid amount');
        } // end else
    }

    function print_certs() {
        var selected = new Array();
        $(".cert").each(function () {
            if ($(this).is(':checked')) {
                selected.push($(this).val());
            }
        });
        if (selected.length > 0) {
            $('#print_err').html('');
            if (confirm('Print selected certificates?')) {
                $('#ajax_loader').show();
                var selected_certs = selected.join();
                var url = "/lms/custom/certificates/print_certificates.php";
                $.post(url, {certs: selected_certs}).done(function () {
                    $('#ajax_loader').hide();
                    var url = "http://medical2.com/print/merged.pdf";
                    window.open(url, "print");
                });
            } // end if confirm
        } // end if selected.length>0
        else {
            $('#print_err').html('Please select at least one certificate to be printed');
        } // end else
    }

    function print_labels() {
        var selected = new Array();
        $(".cert").each(function () {
            if ($(this).is(':checked')) {
                selected.push($(this).val());
            }
        });
        if (selected.length > 0) {
            $('#print_err').html('');
            if (confirm('Print selected labels?')) {
                $('#ajax_loader').show();
                var selected_labels = selected.join();
                var url = "/lms/custom/certificates/print_labels.php";
                $.post(url, {labels: selected_labels}).done(function () {
                    $('#ajax_loader').hide();
                    var url = "http://medical2.com/print/merged.pdf";
                    window.open(url, "print");
                });
            } // end if confirm
        } // end if selected.length>0
        else {
            $('#print_err').html('Please select at least one addresss label to be printed');
        } // end else

    }

    function renew_certificates() {
        var selected = new Array();
        $(".cert").each(function () {
            if ($(this).is(':checked')) {
                selected.push($(this).val());
            }
        });
        if (selected.length > 0) {
            $('#print_err').html('');
            if (confirm('Renew selected certificate(s)?')) {
                $('#ajax_loader').show();
                var selected_certs = selected.join();
                var url = "/lms/custom/certificates/renew_certs.php";
                $.post(url, {certs: selected_certs}).done(function (data) {
                    $('#ajax_loader').hide();
                    $('#print_err').html(data);
                });
            } // end if confirm
        } // end if selected.length > 0
        else {
            $('#print_err').html('Please select at least one certificate');
        } // end else
    }

    function create_cert() {
        if ($('#cert_container').is(":visible")) {
            $('#cert_container').hide();
        } else {
            $('#cert_container').show();
        }
    }

    function recertificate() {
        var selected = new Array();
        $(".cert").each(function () {
            if ($(this).is(':checked')) {
                selected.push($(this).val());
            }
        });
        if (selected.length > 0) {
            $('#print_err').html('');
            var certs = selected.join();
            console.log('Selected certificates: ' + certs);
            if (dialog_loaded !== true) {
                console.log('Script is not yet loaded starting loading ...');
                dialog_loaded = true;
                var js_url = "https://" + domain + "/assets/js/bootstrap.min.js";
                $.getScript(js_url)
                        .done(function () {
                            console.log('Script bootstrap.min.js is loaded ...');
                            var url = "/lms/custom/certificates/get_dates_box.php";
                            $.post(url, {certs: certs}).done(function (data) {
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
        } // end if selected.length>0
        else {
            $('#print_err').html('Please select at least one certificate');
        } // end else
    }

    function get_workshop_users(id) {
        var url = "/lms/custom/promotion/get_workshop_users.php";
        $.post(url, {id: id}).done(function (data) {
            $('#workshop_users').html(data);
        });
    }


    function recertificate_done() {
        var certs = $('#certs').val();
        var s_m = $('#s_m_c').val();
        var s_d = $('#s_d_c').val();
        var s_y = $('#s_y_c').val();
        var e_m = $('#e_m_c').val();
        var e_d = $('#e_d_c').val();
        var e_y = $('#e_y_c').val();
        var start = s_y + '-' + s_m + '-' + s_d;
        console.log('Start: ' + start);
        var end = e_y + '-' + e_m + '-' + e_d;
        console.log('End: ' + end);
        console.log('Certs: ' + certs);
        if (s_m > 0 && s_d > 0 && s_y > 0 && e_m > 0 && e_d > 0 && e_y > 0) {

            $('#print_err').html('');
            $('#ajax_loader').show();
            console.log('Issue date: ' + start);
            console.log('Expire date: ' + end);
            console.log('Certificates list:' + certs);
            var url = "/lms/custom/certificates/recertificate.php";
            $.post(url, {certs: certs, start: start, end: end}).done(function (data) {
                $('#ajax_loader').hide();
                //$('#print_err').html(data);
                //document.location.reload();
                get_certificates_page();
            });
        } // end if selected.length > 0
        else {
            $('#print_err').html('Please select at least one certificate and dates');
        } // end else
    }

    function get_partial_payments_section() {
        var courseid = $('#register_courses').val();
        var username = $('#users').val();
        if (username == '') {
            $('#partial_err').html('Please provide user');
            return;
        } // end if username==''
        else {
            var url = "/lms/custom/utils/get_userid_by_fio.php";
            $.post(url, {username: username}).done(function (userid) {
                var sum = $('#sum').val();
                var slotid = $('#register_cities').val();
                var ptype = $('input[name=payment_type]:checked').val();
                var period = 0;
                if ($('#renew').prop('checked')) {
                    period = $('#renew_period').val();
                    if (period == 0) {
                        $('#partial_err').html('Please select renew period');
                        return;
                    } // end if 
                    else {
                        console.log('Renew period: ' + period);
                    } // end else
                } // end if 

                console.log('Course ID: ' + courseid);
                console.log('User ID: ' + userid);
                console.log('slot ID: ' + slotid);
                console.log('Sum : ' + sum);
                console.log('Ptype: ' + ptype);

                if (courseid > 0 && userid > 0 && $.isNumeric(sum) && sum > 0) {
                    $('#partial_err').html('');
                    if (ptype == 'cc') {
                        if (period == 0) {
                            var url = "https://medical2.com/index.php/payments/payment/" + userid + "/" + courseid + "/" + slotid + "/" + sum;
                        } // end if
                        else {
                            var url = "https://medical2.com/index.php/payments/payment/" + userid + "/" + courseid + "/" + slotid + "/" + sum + "/" + period;
                        }
                        window.open(url, '_blank');
                    } // end if ptype=='cc'
                    else {
                        if (confirm('Add partial payment for current user?')) {
                            var url = "/lms/custom/partial/add_partial_payment.php";
                            $.post(url, {courseid: courseid, userid: userid, sum: sum, source: ptype, slotid: slotid, period: period}).done(function (data) {
                                $('#partial_err').html("<span style='color:black;'>" + data + "</span>");
                            });
                        } // end if confirm
                    } // end else when it is not cc payment
                } // end if courseid > 0 && userid > 0 && sum > 0
                else {
                    $('#partial_err').html('Please select program and user and provide paid amount');
                } // end else


            });



        } // end else

    }

    function search_slots_by_date() {
        var start = $('#start').val();
        var end = $('#end').val();
        var sesskey = $('#sesskey').val();
        var scheduler = $('#scheduler').val();
        var url = "/lms/custom/schedule/get_slots_by_date.php";
        $('#ajax_loading').show();
        $.post(url, {start: start, end: end, scheduler: scheduler, sesskey: sesskey}).done(function (data) {
            $('#ajax_loading').hide();
            //console.log(data);
            $('#schedule_container').html(data);
        });
    }

    function search_slots() {
        var search = $('#search').val();
        var scheduler = $('#scheduler').val();
        var url = "/lms/custom/schedule/search_slot.php";
        $('#ajax_loading').show();
        $.post(url, {search: search, scheduler: scheduler}).done(function (data) {
            $('#ajax_loading').hide();
            $('#schedule_container').html(data);
        });
    }

    function change_students_course_status() {
        var selected = new Array();
        $("input:checked").each(function () {
            if ($(this).val() != '') {
                selected.push($(this).val());
            }
        });
        if (selected.length > 0) {
            $('#sch_err').html('');
            var students = selected.join();
            var courseid = $('#courseid').val();
            if (confirm('Change selected students course status to passed?')) {
                var check_url = "/lms/custom/schedule/check_student_balance.php";
                $.post(check_url, {courseid: courseid, students: students}).done(function (data) {
                    console.log('Balance status: ' + data);
                    if (data == 0) {
                        $('#ajax_loading').show();
                        var url = "/lms/custom/schedule/compete_students.php";
                        $.post(url, {courseid: courseid, students: students}).done(function () {
                            $('#ajax_loading').hide();
                            document.location.reload();
                        });
                    } // end if data == 0
                    else {
                        $('#sch_err').html('Some student(s) do not have zero balance due. ');
                    } // end else
                }); // end of $.post(check_url
            } // end if confirm
        } // selected.length>0
        else {
            $('#sch_err').html('Please select at least one student');
        }
    }

    function send_certificates() {
        var selected = new Array();
        $("input:checked").each(function () {
            selected.push($(this).val());
        });
        if (selected.length > 0) {
            $('#sch_err').html('');
            var students = selected.join();
            var courseid = $('#courseid').val();
            console.log('Course ID: ' + courseid);
            console.log('Students: ' + students);
            if (confirm('Send certificates for selected users?')) {
                $('#ajax_loading').show();
                var url = "/lms/custom/schedule/send_certificates.php";
                $.post(url, {courseid: courseid, students: students}).done(function (data) {
                    $('#ajax_loading').hide();
                    $('#sch_err').html("<span style='color:black'>" + data + "</span>");
                });
            } // end if confirm
        } // end if selected.length > 0
        else {
            $('#sch_err').html('Please select at least one student');
        }
    }

    function print_certificates() {
        var selected = new Array();
        $("input:checked").each(function () {
            if ($(this).val() != '') {
                selected.push($(this).val());
            }
        });
        if (selected.length > 0) {
            $('#sch_err').html('');
            var students = selected.join();
            var courseid = $('#courseid').val();
            if (confirm('Print certificates for selected users?')) {
                $('#ajax_loading').show();
                var url = "/lms/custom/schedule/print_certificates.php";
                $.post(url, {courseid: courseid, students: students}).done(function (filename) {
                    $('#ajax_loading').hide();
                    //console.log('Server response: ' + filename);
                    //var url = "http://medical2.com/print/merged.pdf";
                    var url = "http://medical2.com/print/" + filename;
                    var oWindow = window.open(url, "print");
                });
            } // end if confirm
        } // end if selected.length > 0
        else {
            $('#sch_err').html('Please select at least one student');
        }

    }

    function refresh_slide_tumbs() {
        get_index_page();
    }

    function upload_slide() {
        $list = "";
        var url = "/lms/custom/index/upload.php";
        var file_data = $('#files').prop('files');
        var title = $('#title').val();
        var slogan1 = $('#slogan1').val();
        var slogan2 = $('#slogan2').val();
        var slogan3 = $('#slogan3').val();
        var active;
        if ($('#active').is(":checked")) {
            active = 1;
        } else {
            active = 0;
        }

        if (file_data == '' || file_data.length == 0) {
            $('#slide_err').html('Please select files to be upload ...');
            return false;
        }

        if (title == '' || slogan1 == '' || slogan2 == '' || slogan3 == '') {
            $('#slide_err').html('Please provide banner title and slogan items');
            return false;
        } // end if state==0 || month==0 || year==0

        if (file_data != '' && file_data.length != 0 && title != '' && slogan1 != '' && slogan2 != '' && slogan3 != '') {
            $('#slide_err').html('');
            var form_data = new FormData();
            $.each(file_data, function (key, value) {
                form_data.append(key, value);
            });
            form_data.append('title', title);
            form_data.append('slogan1', slogan1);
            form_data.append('slogan2', slogan2);
            form_data.append('slogan3', slogan3);
            form_data.append('active', active);
            $('#ajax_loader').show();
            $.ajax({
                url: url,
                data: form_data,
                processData: false,
                contentType: false,
                type: 'POST',
                success: function () {
                    $('#ajax_loader').hide();
                    refresh_slide_tumbs();
                }
            });
        } // end if file_data != '' && file_data.length != 0 && state > 0 && month > 0 && year > 0        
    }

    function get_users_states() {
        var url = "/lms/custom/promotion/get_user_states.php";
        $.post(url, {id: 1}).done(function (data) {
            $('#workshop_user_states').html(data);
        });
    }

    function get_user_cities_by_state(id, slotid) {
        var url = "/lms/custom/promotion/get_user_cities.php";
        $.post(url, {id: id, slotid: slotid}).done(function (data) {
            $('#workshop_user_cities').html(data);
        });
    }

    /**********************************************************************
     * 
     *                       Events processing block
     * 
     ***********************************************************************/

// Main region events processing function
    $('#region-main').on('click', 'button', function (event) {
        console.log("Item clicked: " + event.target.id);
        if (event.target.id == 'print_assignment') {
            var moduleid = $('#print_assignment').data('moduleid');
            var url = "/lms/custom/my/get_assignment_pdf.php";
            $.post(url, {moduleid: moduleid}).done(function (url) {
                var oWindow = window.open(url, "print");
            });
        }

        if (event.target.id == 'add_hotel') {
            if (dialog_loaded !== true) {
                console.log('Script is not yet loaded starting loading ...');
                dialog_loaded = true;
                var js_url = "https://" + domain + "/assets/js/bootstrap.min.js";
                $.getScript(js_url)
                        .done(function () {
                            console.log('Script bootstrap.min.js is loaded ...');
                            var url = "/lms/custom/hotels/get_add_hotel_dialog.php";
                            var cert = {userid: userid, courseid: courserid};
                            $.post(url, {cert: JSON.stringify(cert)}).done(function (data) {
                                //console.log(data);
                                $("body").append(data);
                                $("#myModal").modal('show');

                                $.post('/lms/custom/utils/states.json', {id: 1}, function (data) {
                                    $('#state').typeahead({source: data, items: 240});
                                }, 'json');

                                $.post('/lms/custom/utils/cities.json', {id: 1}, function (data) {
                                    $('#city').typeahead({source: data, items: 52000});
                                }, 'json');

                            });
                        })
                        .fail(function () {
                            console.log('Failed to load bootstrap.min.js');
                        });
            } // dialog_loaded!=true
            else {
                console.log('Script already loaded');
                $("body").append(data);
                $("#myModal").modal('show');
            }

        }

        if (event.target.id == 'get_campaign_users') {
            var courseid = $('#courses').val();
            var slotid = $('#workshops').val();
            var state_code = $('#user_states').val();
            var cityid = $('#user_cities').val();
            if (courseid > 0) {
                $('#prom_err').html('');
                var url = "/lms/custom/promotion/get_campaign_users.php";
                var user_criteria = {courseid: courseid, slotid: slotid, state_name: state_code, city_name: cityid};
                $.post(url, {user_criteria: JSON.stringify(user_criteria)}).done(function (data) {
                    $('#workshop_users').html(data);
                    $('#create_div').show();
                });
            } // end if
            else {
                $('#prom_err').html('Please select program');
            } // end else
        }



        if (event.target.id == 'add_campus') {
            if (dialog_loaded !== true) {
                console.log('Script is not yet loaded starting loading ...');
                dialog_loaded = true;
                var js_url = "https://" + domain + "/assets/js/bootstrap.min.js";
                $.getScript(js_url)
                        .done(function () {
                            console.log('Script bootstrap.min.js is loaded ...');
                            var url = "/lms/custom/campus/get_add_dialog.php";
                            var request = {id: id, type: type};
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
            } // end else
        }

        if (event.target.id == 'submit_survey') {
            var courseid = $('#courseid').val();
            var userid = $('#userid').val();
            // Select input
            var attend = 1;

            // Text input
            var city = $('#s_city').val();

            // Select inputs
            var reg_exp = 1;
            var reg_online = 1;
            var in_prof = $('#s_in_prof').val();
            var in_know = 1;
            var ws_content = 1;
            var ws_thro = 1;
            var ws_pace = 1;
            var hands_exp = 1;
            var ws_use = 1;
            var qu_answer = $('#s_qu_answer').val();
            var co_org = 1;
            var in_org = 1;
            var in_clear = $('#s_in_clear').val();
            var training_met = $('#s_training_met').val();
            var draw_blood = $('#s_draw_blood').val();
            var brochure = $('#s_brochure').val();
            var recommend = $('#s_recommend').val();

            // Text inputs
            var ws_more = $('#s_more_interesting').val();
            var improve = $('#s_improve').val();
            var comments = $('#s_comments').val();

            if (attend != '--' &&
                    city != '' &&
                    reg_exp != '--' &&
                    reg_online != '--' &&
                    in_prof != '--' &&
                    in_know != '--' &&
                    ws_content != '--' &&
                    ws_thro != '--' &&
                    ws_pace != '--' &&
                    hands_exp != '--' &&
                    ws_use != '--' &&
                    qu_answer != '--' &&
                    co_org != '--' &&
                    in_org != '--' &&
                    in_clear != '--' &&
                    training_met != '--' &&
                    draw_blood != '--' &&
                    brochure != '--' &&
                    recommend != '--') {

                $('#survey_err').html('');
                var survey = {courseid: courseid,
                    userid: userid,
                    attend: attend,
                    city: city,
                    reg_exp: reg_exp,
                    reg_online: reg_online,
                    in_prof: in_prof,
                    in_know: in_know,
                    ws_content: ws_content,
                    ws_thro: ws_thro,
                    ws_pace: ws_pace,
                    hands_exp: hands_exp,
                    ws_use: ws_use,
                    qu_answer: qu_answer,
                    co_org: co_org,
                    in_org: in_org,
                    in_clear: in_clear,
                    training_met: training_met,
                    draw_blood: draw_blood,
                    brochure: brochure,
                    recommend: recommend,
                    ws_more: ws_more,
                    improve: improve,
                    comments: comments};

                console.log(survey);
                var url = '/lms/custom/my/send_survey_results.php';
                $('#ajax_loader').show();
                $('#submit_survey').prop('disabled', true);
                $.post(url, {survey: JSON.stringify(survey)}).done(function (data) {
                    $('#ajax_loader').hide();
                    alert(data);
                    document.location.reload();
                });

            } // end if
            else {
                $('#survey_err').html('Please provide all required fields');
            } // end else

        }

        if (event.target.id == 'year_stat') {
            var year1 = $('#stat_year1').val();
            var year2 = $('#stat_year2').val();
            console.log('Year1: ' + year1);
            console.log('Year2' + year2);
            if (year1 == 0 || year2 == 0) {
                $('#report_year_data').html("<span style='color:red;'>Please select years to be compared</span>");
            } // end if 
            else {
                $('#report_year_data').html('');
                $('#ajax_loader').show();
                var url = "/lms/custom/reports/get_year_stat.php";
                $.ajax({
                    url: url,
                    data: {year1: year1, year2: year2},
                    type: 'POST',
                    success: function (data) {
                        $('#ajax_loader').hide();
                        $('#report_year_data').html(data);
                    }
                });
            } // end else

        }

        if (event.target.id == 'month_stat') {
            var year = $('#stat_month_year').val();
            var month1 = $('#stat_month1').val();
            var month2 = $('#stat_month2').val();
            console.log('Year: ' + year);
            console.log('Month1: ' + month1);
            console.log('Month1: ' + month2);
            if (year == 0 || month1 == 0 || month2 == 0) {
                $('#report_month_data').html("<span style='color:red;'>Please select year and months to be compared</span>");
            } // end if
            else {
                $('#report_month_data').html('');
                $('#ajax_loader').show();
                var url = "/lms/custom/reports/get_month_stat.php";
                $.ajax({
                    url: url,
                    data: {year: year, month1: month1, month2: month2},
                    type: 'POST',
                    success: function (data) {
                        $('#ajax_loader').hide();
                        $('#report_month_data').html(data);
                    }
                });
            } // end else
        }


        if (event.target.id.indexOf("price_") >= 0) {
            update_item_price(event.target.id);
        }

        if (event.target.id.indexOf("_faq") >= 0) {
            var oEditor = FCKeditorAPI.GetInstance('editor');
            var data = oEditor.GetHTML();
            update_faq_page(data);
        }

        if (event.target.id.indexOf("_about") >= 0) {
            var oEditor = FCKeditorAPI.GetInstance('editor');
            var data = oEditor.GetHTML();
            update_about_page(data);
        }

        if (event.target.id.indexOf("_terms") >= 0) {
            var oEditor = FCKeditorAPI.GetInstance('editor');
            var data = oEditor.GetHTML();
            update_terms_page(data);
        }

        if (event.target.id.indexOf("_test") >= 0) {
            var oEditor = FCKeditorAPI.GetInstance('editor');
            var data = oEditor.GetHTML();
            update_testimonial_page(data);
        }

        if (event.target.id.indexOf("_upload") >= 0) {
            upload_files();
        }

        if (event.target.id.indexOf("_img") >= 0) {
            delete_gallery_img();
        }

        if (event.target.id.indexOf("map_") >= 0) {
            update_map_item(event.target.id);
        }

        if (event.target.id.indexOf("tax_") >= 0) {
            update_tax_item(event.target.id);
        }

        if (event.target.id.indexOf("make_paid_") >= 0) {
            var id = event.target.id.replace("make_paid_", "");
            make_invoice_paid(id);
        }

        if (event.target.id.indexOf("make_any_paid_") >= 0) {
            var id = event.target.id.replace("make_any_paid_", "");
            var typeid = '#payment_type_' + id;
            var errid = '#invoice_status_' + id;
            var type = $(typeid).val();
            if (type > 0) {
                $(errid).html("");
                if (dialog_loaded !== true) {
                    console.log('Script is not yet loaded starting loading ...');
                    dialog_loaded = true;
                    var js_url = "https://" + domain + "/assets/js/bootstrap.min.js";
                    $.getScript(js_url)
                            .done(function () {
                                console.log('Script bootstrap.min.js is loaded ...');
                                var url = "/lms/custom/invoices/get_any_invoice_modal.php";
                                var request = {id: id, type: type};
                                $.post(url, request).done(function (data) {
                                    $("body").append(data);
                                    $("#myModal").modal('show');
                                    $.get('/lms/custom/utils/data.json', function (data) {
                                        $('#search_user_input').typeahead({source: data, items: 24});
                                    }, 'json');

                                });
                            })
                            .fail(function () {
                                console.log('Failed to load bootstrap.min.js');
                            });
                } // dialog_loaded!=true
                else {
                    console.log('Script already loaded');
                    $("#myModal").modal('show');
                } // end else
            } // end if typeid
            else {
                $(errid).html("<span style='color:red;'>Please select payment type</span>");
            }
        }


        if (event.target.id == 'invoice_data') {
            update_invoice_data();
        }

        if (event.target.id == 'send_any_invoice') {
            var courseid = $('#invoice_courses').val();
            var client = $('#invoice_client').val();
            var amount = $('#invoice_amount').val();
            var email = $('#invoice_email').val();
            if (courseid > 0 && amount != '' && email != '' && client != '') {
                $('#any_invoice_status').html('');
                if (confirm('Send invoice?')) {
                    var url = "/lms/custom/invoices/send_any_invoice.php";
                    $.post(url, {courseid: courseid, amount: amount, email: email, client: client}).done(function (data) {
                        $('#any_invoice_status').html(data);
                    });
                }
            } else {
                $('#any_invoice_status').html("<span style='color:red;'>Please select program and provide invoice amount with client email</span>");
            }
        }


        if (event.target.id == 'make_refund_button') {
            get_refund_modal_dialog();
        }

        if (event.target.id == 'create_campaign') {
            console.log('Enrolled users: ' + $('select#users').val());
            if (typeof $('select#users').val() !== 'undefined') {
                var enrolled = $('select#users').val();
                var enrolled_users = enrolled.join();
                console.log('Enrolled users: ' + enrolled_users);
            } // end if typeof enr != 'undefined'
            else {
                $('#prom_err').html('Please select users to be messaged');
            }
            console.log('WS users: ' + $('select#ws_users').val());
            if (typeof $('select#ws_users').val() !== 'undefined') {
                var ws = $('select#ws_users').val();
                var workshop_users = ws.join();
                console.log('Workshop users: ' + workshop_users);
            } // end if typeof wsr != 'undefined'
            else {
                $('#prom_err').html('Please select users to be messaged');
            }

            var oEditor = FCKeditorAPI.GetInstance('editor');
            var data = oEditor.GetHTML();
            console.log('Editor data: ' + data);
            if (data == '') {
                $('#prom_err').html('Please provide message text');
            } else {
                $('#prom_err').html('');
                if ((typeof $('select#users').val() !== 'undefined' && enrolled_users != 0) || (typeof $('select#ws_users').val() !== 'undefined' && workshop_users != 0)) {
                    $('#prom_err').html('');
                    if (confirm('Send message to selected users?')) {
                        $('#ajax_loader').show();
                        var url = "/lms/custom/promotion/add_new_campaign.php";
                        $.post(url, {data: data, enrolled_users: enrolled_users, workshop_users: workshop_users}).done(function (data) {
                            $('#ajax_loader').hide();
                            $('#prom_err').html(data);
                        });
                    } // end if confirm
                } // end if enrolled_users != 0 || workshop_users != 0
                else {
                    $('#prom_err').html('Please select users to be messaged');
                }
            } // end else
        }

        if (event.target.id == 'other_go') {
            console.log('It is me ...');
            var courseid = $('#courses').val();
            var from = $('#datepicker1').val();
            var to = $('#datepicker2').val();
            var type = $('#type').val();
            if (from == '' || to == '') {
                $('#other_report_container').html('Please select dates');
            } // end if 
            else {
                $('#other_report_container').html('');
                $('#ajax_loading').show();
                var url = "/lms/custom/reports/get_other_payments_report_data.php";
                $.post(url, {courseid: courseid, from: from, to: to, type: type}).done(function (data) {
                    $('#ajax_loading').hide();
                    $('#other_report_container').html(data);
                });
            } // end else 
        }

        if (event.target.id == 'update_slide') {
            var id = $('#slide_id').val();
            var title = $('#title').val();
            var slogan1 = $('#slogan1').val();
            var slogan2 = $('#slogan2').val();
            var slogan3 = $('#slogan3').val();
            if (id > 0 && title != '' && slogan1 != '' && slogan2 != '' && slogan3 != '') {
                $('#slide_err').html('');
                $('#ajax_loader').show();
                var url = "/lms/custom/index/update_slide.php";
                $.post(url, {id: id, title: title, slogan1: slogan1, slogan2: slogan2, slogan3: slogan3}).done(function (data) {
                    console.log(data);
                    $('#ajax_loader').hide();
                    get_index_page();
                });
            } // end if id>0 && title!='' && slogan1!='' && slogan2!='' && slogan3!=''
            else {
                $('#slide_err').html('Please provide banner title and slogans');
            } // end else


        }


        if (event.target.id == 'create_new_campaign') {
            var users = [];
            var id;
            $("#camp_users :selected").map(function (i, el) {
                id = $(el).val();
                if (id > 0) {
                    users.push(id);
                }
            });
            var ul = users.length;
            console.log('Users length: ' + ul);
            if (ul > 0) {
                $('#prom_err').html('');
                if (dialog_loaded !== true) {
                    console.log('Script is not yet loaded starting loading ...');
                    dialog_loaded = true;
                    var js_url = "https://" + domain + "/assets/js/bootstrap.min.js";
                    $.getScript(js_url)
                            .done(function () {
                                console.log('Script bootstrap.min.js is loaded ...');
                                var url = "/lms/custom/promotion/get_create_campaign_dialog.php";
                                $.post(url, {users: JSON.stringify(users)}).done(function (data) {
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
                    $("body").append(data);
                    $("#myModal").modal('show');
                }
            }// end if ul>0
            else {
                $('#prom_err').html('Please select students');
            }  // end else
        }

        if (event.target.id == 'create_cert_button') {
            var courseid = $('#courses').val();
            var username = $('#users').val();
            if (username == '') {
                $('#print_err').html('Please select user');
            } // end if username == '' 
            else {
                var url = "/lms/custom/utils/get_userid_by_fio.php";
                $.post(url, {username: username}).done(function (userid) {

                    var s_m = $('#s_m').val();
                    var s_d = $('#s_d').val();
                    var s_y = $('#s_y').val();
                    var e_m = $('#e_m').val();
                    var e_d = $('#e_d').val();
                    var e_y = $('#e_y').val();
                    console.log('Course ID: ' + courseid);
                    console.log('User ID: ' + userid);
                    if (s_m > 0 && s_d > 0 && s_y > 0 && e_m > 0 && e_d > 0 && e_y > 0 && courseid > 0 && userid > 0) {
                        $('#print_err').html('');
                        var start = s_y + '-' + s_m + '-' + s_d;
                        var end = e_y + '-' + e_m + '-' + e_d;
                        $('#print_err').html('');
                        $('#ajax_loader').show();
                        console.log('Issue date: ' + start);
                        console.log('Expire date: ' + end);
                        var url = "/lms/custom/certificates/create_certificate.php";
                        $.post(url, {courseid: courseid, userid: userid, start: start, end: end}).done(function (data) {
                            $('#ajax_loader').hide();
                            get_certificates_page();
                        });
                    } // end if
                    else {
                        $('#print_err').html('Please select program, user and certificate dates');
                    }

                });
            } // end else
        }

        if (event.target.id == 'send_cert') {
            send_certicicate_to_user();
        }

        if (event.target.id == 'send_invoice') {
            send_invoice();
        }

        if (event.target.id == 'add_installment_user') {
            add_installment_user();
        }

        if (event.target.id == 'rev_go') {
            get_revenue_report_data();
        }

        if (event.target.id == 'program_go') {
            get_program_report_data();
        }

        if (event.target.id == 'workshops_go') {
            get_workshop_report_data();
        }

        if (event.target.id == 'print_label') {
            print_certificate_address_label();
        }

        if (event.target.id == 'print_cert') {
            print_certificate();
        }

        if (event.target.id == 'update_renew_fee') {
            update_renew_fee();
        }

        if (event.target.id.indexOf("_contact") >= 0) {
            var oEditor = FCKeditorAPI.GetInstance('editor');
            var data = oEditor.GetHTML();
            update_contact_page(data);
        }

        if (event.target.id.indexOf('update_late') >= 0) {
            update_late_fee(event.target.id);
        }

        if (event.target.id == 'search_user') {
            search_user_by_email();
        }

        if (event.target.id == 'clear_user') {
            clear_user_filter();
        }

        if (event.target.id == 'filter') {
            filter();
        }

        if (event.target.id == 'search_open_invoice_user') {
            search_open_invoice_user();
        }

        if (event.target.id == 'search_paid_invoice_user') {
            search_paid_invoice_user();
        }

        if (event.target.id == 'clear_open_invoice') {
            get_open_invoices_page();
        }

        if (event.target.id == 'clear_paid_invoice') {
            get_paid_invoice_page();
        }

        if (event.target.id == 'search_payment_button') {
            var typeid = $('#ptype').val();
            search_payment(typeid);
        }

        if (event.target.id == 'clear_payment_button') {
            var typeid = $('#ptype').val();
            console.log('Payment type: ' + typeid);
            switch (typeid) {
                case "1":
                    get_cash_payments_page();
                    break;
                case "2":
                    get_check_payments_page();
                    break;
                case "3":
                    get_free_payments();
                    break;
            } // end switch
        }  // end of event.target.id == 'clear_payment_button'

        if (event.target.id == 'search_card_payment_button') {
            search_credit_card_payment();
        }

        if (event.target.id == 'clear_card_payment_button') {
            get_credit_card_payments_page();
        }

        if (event.target.id == 'search_refund_payment_button') {
            search_refund_page();
        }

        if (event.target.id == 'clear_refund_payment_button') {
            get_refund_page();
        }

        if (event.target.id == 'search_certificate_button') {
            search_certificate();
        }

        if (event.target.id == 'clear_certificate_button') {
            get_certificates_page();
        }


        if (event.target.id == 'add_payment') {
            add_partial_payment();
        }

        if (event.target.id == 'add_cash' || event.target.id == 'add_cheque') {
            add_partial_payment(event.target.id);
        }

        if (event.target.id == 'date_btn') {
            search_slots_by_date();
        }

        if (event.target.id == 'search_btn') {
            search_slots();
        }

        if (event.target.id == 'get_partial_payment_section') {
            get_partial_payments_section();
        }

        if (event.target.id == 'search_partial_button') {
            search_partial_payments();
        }

        if (event.target.id == 'clear_partial_button') {
            get_partial_payments_page();
        }

        if (event.target.id == 'add_user_to_slot') {
            add_user_to_slot();
        }

        if (event.target.id == 'upload_slide') {
            upload_slide();
        }

        if (event.target.id.indexOf('del_slide_') >= 0) {
            var id = event.target.id.replace("del_slide_", "");
            if (id > 0) {
                if (confirm('Delete current slide?')) {
                    var url = "/lms/custom/index/del_slide.php";
                    $.post(url, {id: id}).done(function () {
                        get_index_page();
                    });
                }  // end if confirm
            } // end if id>0
        } // end if event.target.id.indexOf('del_slide_') >= 0

        /********************************************************************
         * 
         *                     Class based listeners
         *
         ********************************************************************/

        console.log('Class element clicked: ' + $(event.target).attr('class'));


        if ($(event.target).attr('class') == 'profile_user_suspend') {
            var userid = $(this).data('userid');
            var state = $(this).data('status');
            var msg;
            if (state == 0) {
                msg = 'Suspend current user?';
            } // end if 
            else {
                msg = 'Activate current user?';
            }
            if (confirm(msg)) {
                var url = "/lms/custom/my/suspend_user.php";
                $.post(url, {userid: userid, state: state}).done(function () {
                    document.location.reload();
                });
            }
        }

        if ($(event.target).attr('class') == 'edit_campus') {
            var id = $(this).data('id');
            if (dialog_loaded !== true) {
                console.log('Script is not yet loaded starting loading ...');
                dialog_loaded = true;
                var js_url = "https://" + domain + "/assets/js/bootstrap.min.js";
                $.getScript(js_url)
                        .done(function () {
                            console.log('Script bootstrap.min.js is loaded ...');
                            var url = "/lms/custom/campus/get_edit_dialog.php";
                            $.post(url, {id: id}).done(function (data) {
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
                $("body").append(data);
                $("#myModal").modal('show');
            }
        }

        if ($(event.target).attr('class') == 'del_campus') {
            var id = $(this).data('id');
            console.log('ID: ' + id);
            if (confirm('Are you sure?')) {
                var url = "/lms/custom/campus/del_campus.php";
                $.post(url, {id: id}).done(function () {
                    get_campus_page();
                });
            }
        }

        if ($(event.target).attr('class') == 'profile_add_payment') {
            var userid = $(this).data('userid');
            if (dialog_loaded !== true) {
                console.log('Script is not yet loaded starting loading ...');
                dialog_loaded = true;
                var js_url = "https://" + domain + "/assets/js/bootstrap.min.js";
                $.getScript(js_url)
                        .done(function () {
                            console.log('Script bootstrap.min.js is loaded ...');
                            var url = "/lms/custom/my/get_add_payment_dialog.php";
                            var request = {userid: userid};
                            $.post(url, request).done(function (data) {
                                $("body").append(data);
                                $("#myModal").modal('show');
                                $.get('/lms/custom/utils/programs.json', function (data) {
                                    $('#coursename').typeahead({source: data, items: 24});
                                }, 'json');

                                /*
                                 $.get('/lms/custom/utils/workshops.json', function (data) {
                                 $('#wsname').typeahead({source: data, items: 240});
                                 }, 'json');
                                 */
                            });
                        })
                        .fail(function () {
                            console.log('Failed to load bootstrap.min.js');
                        });
            } // dialog_loaded!=true
            else {
                console.log('Script already loaded');
                $("body").append(data);
                $("#myModal").modal('show');
            }

        }

        if ($(event.target).attr('class') == 'profile_move_payment') {
            var userid = $(this).data('userid');
            var courseid = $(this).data('courseid');
            var paymentid = $(this).data('paymentid');
            console.log('User id: ' + userid);
            console.log('Course id: ' + courseid);
            if (dialog_loaded !== true) {
                console.log('Script is not yet loaded starting loading ...');
                dialog_loaded = true;
                var js_url = "https://" + domain + "/assets/js/bootstrap.min.js";
                $.getScript(js_url)
                        .done(function () {
                            console.log('Script bootstrap.min.js is loaded ...');
                            var url = "/lms/custom/my/get_payment_move_dialog.php";
                            var request = {userid: userid, courseid: courseid, paymentid: paymentid};
                            $.post(url, request).done(function (data) {
                                $("body").append(data);
                                $("#myModal").modal('show');
                                $.get('/lms/custom/utils/programs.json', function (data) {
                                    $('#coursename').typeahead({source: data, items: 24});
                                }, 'json');
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
            $.get('/lms/custom/utils/programs.json', function (data) {
                $('#coursename').typeahead({source: data, items: 24});
            }, 'json');
        }

        if ($(event.target).attr('class') == 'profile_refund_payment') {
            var userid = $(this).data('userid');
            var courseid = $(this).data('courseid');
            var id = $(this).data('paymentid');
            console.log('Payment id: ' + id);
            if (confirm('Refund current payment?')) {
                var url = "/lms/custom/my/refund.php";
                var payment = {userid: userid, courseid: courseid, id: id};
                $.post(url, {payment: JSON.stringify(payment)}).done(function (data) {
                    console.log(data);
                    $("[data-dismiss=modal]").trigger({type: "click"});
                    document.location.reload();
                });
            } // end if confirm
        }

        if ($(event.target).attr('class') == 'profile_add_to_workshop') {
            var userid = $(this).data('userid');
            console.log('User id: ' + userid);
            if (dialog_loaded !== true) {
                console.log('Script is not yet loaded starting loading ...');
                dialog_loaded = true;
                var js_url = "https://" + domain + "/assets/js/bootstrap.min.js";
                $.getScript(js_url)
                        .done(function () {
                            console.log('Script bootstrap.min.js is loaded ...');
                            var url = "/lms/custom/my/add_to_workshop.php";
                            var request = {userid: userid};
                            $.post(url, request).done(function (data) {
                                $("body").append(data);
                                $("#myModal").modal('show');

                                /*
                                 
                                 $.get('/lms/custom/utils/workshops.json', function (data) {
                                 console.log(data);
                                 $('#wsname').typeahead({source: data, items: 240});
                                 }, 'json');
                                 
                                 $.post('/lms/custom/utils/workshops.json', {id: 1}, function (data) {
                                 console.log(data);
                                 $('#wsname').typeahead({source: data, items: 240});
                                 }, 'json');
                                 
                                 $.get('/lms/custom/utils/programs.json', function (data) {
                                 $('#coursename').typeahead({source: data, items: 24});
                                 }, 'json');
                                 
                                 */

                            }); // end of post
                        })
                        .fail(function () {
                            console.log('Failed to load bootstrap.min.js');
                        });
            } // dialog_loaded!=true
            else {
                console.log('Script already loaded');
                $("body").append(data);
                $("#myModal").modal('show');
            }
        }

        if ($(event.target).attr('class') == 'profile_move_to_workshop') {
            var userid = $(this).data('userid');
            var slotid = $(this).data('slotid');
            var appid = $(this).data('appid');
            var courseid = $(this).data('courseid');
            console.log('Course id: ' + courseid);
            if (dialog_loaded !== true) {
                console.log('Script is not yet loaded starting loading ...');
                dialog_loaded = true;
                var js_url = "https://" + domain + "/assets/js/bootstrap.min.js";
                $.getScript(js_url)
                        .done(function () {
                            console.log('Script bootstrap.min.js is loaded ...');
                            var url = "/lms/custom/my/get_move_to_workshop_dialog.php";
                            var request = {userid: userid, slotid: slotid, appid: appid, courseid: courseid};
                            $.post(url, request).done(function (data) {
                                $("body").append(data);
                                $("#myModal").modal('show');
                                $.get('/lms/custom/utils/workshops.json', function (data) {
                                    $('#wsname').typeahead({source: data, items: 24});
                                }, 'json');
                            });
                        })
                        .fail(function () {
                            console.log('Failed to load bootstrap.min.js');
                        });
            } // dialog_loaded!=true
            else {
                console.log('Script already loaded');
                $("body").append(data);
                $("#myModal").modal('show');
            }

            $.get('/lms/custom/utils/workshops.json', function (data) {
                $('#wsname').typeahead({source: data, items: 24});
            }, 'json');
        }

        if ($(event.target).attr('class') == 'profile_cancel_workshop') {
            var appid = $(this).data('appid');
            if (confirm('Remove current student from selected workshop?')) {
                var url = "/lms/custom/my/remove_from_workshop.php";
                $.post(url, {id: appid}).done(function () {
                    document.location.reload();
                });
            }

        }

        if ($(event.target).attr('class') == 'profile_create_cert') {
            var userid = $(this).data('userid');
            console.log('User id: ' + userid);
            if (dialog_loaded !== true) {
                console.log('Script is not yet loaded starting loading ...');
                dialog_loaded = true;
                var js_url = "https://" + domain + "/assets/js/bootstrap.min.js";
                $.getScript(js_url)
                        .done(function () {
                            console.log('Script bootstrap.min.js is loaded ...');
                            var url = "/lms/custom/my/get_create_cert_dialog.php";
                            var request = {userid: userid};
                            $.post(url, request).done(function (data) {
                                $("body").append(data);
                                $("#myModal").modal('show');
                                $.get('/lms/custom/utils/programs.json', function (data) {
                                    $('#coursename').typeahead({source: data, items: 24});
                                }, 'json');
                                $("#date1").datepicker();
                                $("#date2").datepicker();
                            });
                        })
                        .fail(function () {
                            console.log('Failed to load bootstrap.min.js');
                        });
            } // dialog_loaded!=true
            else {
                console.log('Script already loaded');
                $("body").append(data);
                $("#myModal").modal('show');
            }

            $.get('/lms/custom/utils/programs.json', function (data) {
                $('#coursename').typeahead({source: data, items: 24});
            }, 'json');
        }

        if ($(event.target).attr('class') == 'delete_profile_user') {
            var userid = $(this).data('userid');
            if (dialog_loaded !== true) {
                console.log('Script is not yet loaded starting loading ...');
                dialog_loaded = true;
                var js_url = "https://" + domain + "/assets/js/bootstrap.min.js";
                $.getScript(js_url)
                        .done(function () {
                            console.log('Script bootstrap.min.js is loaded ...');
                            var url = "/lms/custom/my/get_delete_user_dialog.php";
                            var request = {id: userid};
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
                $("body").append(data);
                $("#myModal").modal('show');
            }
        }

        if ($(event.target).attr('class') == 'profile_renew_cert') {
            var id = $(this).data('id');
            if (dialog_loaded !== true) {
                console.log('Script is not yet loaded starting loading ...');
                dialog_loaded = true;
                var js_url = "https://" + domain + "/assets/js/bootstrap.min.js";
                $.getScript(js_url)
                        .done(function () {
                            console.log('Script bootstrap.min.js is loaded ...');
                            var url = "/lms/custom/my/get_renew_cert_dialog.php";
                            var cert = {id: id};
                            $.post(url, {cert: JSON.stringify(cert)}).done(function (data) {
                                $("body").append(data);
                                $("#myModal").modal('show');
                                $("#date1").datepicker();
                                $("#date2").datepicker();
                            });
                        })
                        .fail(function () {
                            console.log('Failed to load bootstrap.min.js');
                        });
            } // dialog_loaded!=true
            else {
                console.log('Script already loaded');
                $("body").append(data);
                $("#myModal").modal('show');
            }
        }

        if ($(event.target).attr('class') == 'add_instructor') {
            var userid = $(this).data('inst_userid');
            var elid = '#cws_' + userid;
            var slot = $(elid).val();

            console.log('User id: ' + userid);
            console.log('Slot id: ' + slot);

            if (slot > 0) {
                if (confirm('Add instructor to selected workshop?')) {
                    var instructor = {userid: userid, slot: slot};
                    var url = "/lms/custom/instructors/add_instructor_to_workshop.php";
                    $.post(url, {instructor: JSON.stringify(instructor)}).done(function (data) {
                        $('#inst_err').html(data);
                        //$('#inst_err').html('Instructor has been added to selected workshop');
                    });
                } // end if confirm
            } // end if slot>0
        }

        if ($(event.target).attr('class') == 'profile_send_cert') {
            var userid = $(this).data('userid');
            var courserid = $(this).data('courseid');
            if (dialog_loaded !== true) {
                console.log('Script is not yet loaded starting loading ...');
                dialog_loaded = true;
                var js_url = "https://" + domain + "/assets/js/bootstrap.min.js";
                $.getScript(js_url)
                        .done(function () {
                            console.log('Script bootstrap.min.js is loaded ...');
                            var url = "/lms/custom/my/get_send_cert_dialog.php";
                            var cert = {userid: userid, courseid: courserid};
                            $.post(url, {cert: JSON.stringify(cert)}).done(function (data) {
                                //console.log(data);
                                $("body").append(data);
                                $("#myModal").modal('show');
                                $("#date1").datepicker();
                                $("#date2").datepicker();
                            });
                        })
                        .fail(function () {
                            console.log('Failed to load bootstrap.min.js');
                        });
            } // dialog_loaded!=true
            else {
                console.log('Script already loaded');
                $("body").append(data);
                $("#myModal").modal('show');
            }

        }


    }); // end of #region-main click', 'button',

    $('#region-main').on('click', 'checkbox', function (event) {
        console.log('Event: ' + event);
    });
    function get_refund_modal_dialog() {
        //console.log('Refund modal dialog ...');
        if (dialog_loaded !== true) {
            console.log('Script is not yet loaded starting loading ...');
            dialog_loaded = true;
            var js_url = "https://" + domain + "/assets/js/bootstrap.min.js";
            $.getScript(js_url)
                    .done(function () {
                        console.log('Script bootstrap.min.js is loaded ...');
                        var url = "/lms/custom/payments/get_refund_modal_dialog.php";
                        var request = {item: 1};
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
        } // end else
    }

    $('#region-main').on('click', 'a', function (event) {
        if (event.target.id.indexOf("group_") >= 0) {
            var id = event.target.id.replace("group_", "");
            show_private_group_request_detailes(id);
        }

        if (event.target.id.indexOf("edit_slide_") >= 0) {
            var id = event.target.id.replace("edit_slide_", "");
            var url = "/lms/custom/index/edit_slide.php";
            $.post(url, {id: id}).done(function (data) {
                slide = $.parseJSON(data);
                $('#slide_id').val(id);
                $('#title').val(slide.title);
                $('#slogan1').val(slide.slogan1);
                $('#slogan2').val(slide.slogan2);
                $('#slogan3').val(slide.slogan3);
                //get_index_page();
            });
        }

        if (event.target.id == 'cert_send_page') {
            $('#send_cert_container').show();
        }

        if (event.target.id == 'send_cert') {
            send_certicicate_to_user();
        }

        if (event.target.id.indexOf("cert_page_") >= 0) {
            var id = event.target.id.replace("cert_page_", "");
            get_certificate_item(id);
        }

        if (event.target.id.indexOf("tax_page_") >= 0) {
            var id = event.target.id.replace("tax_page_", "");
            get_tax_item(id);
        }

        if (event.target.id.indexOf("change_paid_") >= 0) {
            var id = event.target.id.replace("change_paid_", "");
            var page_id = '#change_payment_status_page_' + id;
            $(page_id).show();
        }

        if (event.target.id == 'add_installment_user') {
            $('#add_installment_user_container').show();
        }

        if (event.target.id == 'program_report_export') {
            export_program_report();
        }

        if (event.target.id == 'send_invoice_renew') {
            send_invoice_renew();
        }

        if (event.target.id == 'select_all') {
            select_all();
        }

        if (event.target.id == 'deselect_all') {
            deselect_all();
        }

        if (event.target.id == 'print_certs') {
            print_certs();
        }

        if (event.target.id == 'print_labels') {
            print_labels();
        }

        if (event.target.id == 'labels') {
//console.log('Print labels from Workshop Schedule page ...');
            var selected = new Array();
            $("input:checked").each(function () {
                if ($(this).val() != '') {
                    selected.push($(this).val());
                }
            });
            if (selected.length > 0) {
                $('#sch_err').html('');
                var students = selected.join();
                var courseid = $('#courseid').val();
                if (confirm('Print labels for selected users?')) {
                    $('#ajax_loading').show();
                    var url = "/lms/custom/schedule/print_workshop_labels.php";
                    $.post(url, {courseid: courseid, students: students}).done(function (filename) {
                        $('#ajax_loading').hide();
                        //var url = "http://medical2.com/print/merged.pdf";
                        var url = "http://medical2.com/print/" + filename;
                        var oWindow = window.open(url, "print");
                    });
                } // end if confirm
            } // end if selected.length > 0
            else {
                $('#sch_err').html('Please select at least one student');
            }

        }

        if (event.target.id == 'renew_cert') {
            renew_certificates();
        }

        if (event.target.id == 'recertificate') {
            recertificate();
        }

        if (event.target.id == 'create_cert') {
            create_cert();
        }

        if (event.target.id == 'add_partial') {
            if ($('#add_payment_container').is(':visible')) {
                $('#add_payment_container').hide();
            } // end if 
            else {
                $('#add_payment_container').show();
            } // end else
        }

        if (event.target.id == 'students_all') {
            $('.students').each(function () { //loop through each checkbox
                this.checked = true; //select all checkboxes with class "cert"              
            });
        }

        if (event.target.id == 'complete') {
            change_students_course_status();
        }

        if (event.target.id == 'pending') {
            var selected = new Array();
            $("input:checked").each(function () {
                if ($(this).val() != '') {
                    selected.push($(this).val());
                }
            });
            if (selected.length > 0) {
                $('#sch_err').html('');
                var students = selected.join();
                var courseid = $('#courseid').val();
                console.log('Course ID: ' + courseid);
                console.log('Students: ' + students);
                if (confirm('Change selected students to pending status?')) {
                    var url = "/lms/custom/schedule/pending.php";
                    $.post(url, {students: students, courseid: courseid}).done(function () {
                        document.location.reload();
                    });
                } // end if condifrm
            } // end if selected.length > 0
            else {
                $('#sch_err').html('Please select at least one student');
            }
        }

        if (event.target.id == 'delete') {
            var scheduler = $('#scheduler').val();
            var selected = new Array();
            $("input:checked").each(function () {
                selected.push($(this).val());
            });
            if (selected.length > 0) {
                $('#sch_err').html('');
                var students = selected.join();
                if (confirm('Remove selected students from this class/workshop?')) {
                    var url = "/lms/custom/schedule/remove.php";
                    $.post(url, {students: students, schedulerid: scheduler}).done(function () {
                        document.location.reload();
                    });
                } // end if condifrm
            } // end if // end if selected.length > 0
            else {
                $('#sch_err').html('Please select at least one student');
            }
        }

        if (event.target.id == 'move') {
            var scheduler = $('#scheduler').val();
            var selected = new Array();
            $("input:checked").each(function () {
                if ($(this).val() != '') {
                    selected.push($(this).val());
                }
            });
            if (selected.length > 0) {
                $('#sch_err').html('');
                var students = selected.join();
                if (dialog_loaded !== true) {
                    console.log('Script is not yet loaded starting loading ...');
                    dialog_loaded = true;
                    var js_url = "https://" + domain + "/assets/js/bootstrap.min.js";
                    $.getScript(js_url)
                            .done(function () {
                                console.log('Script bootstrap.min.js is loaded ...');
                                var url = "/lms/custom/schedule/get_workshops_list.php";
                                var request = {students: students, scheduler: scheduler};
                                $.post(url, request).done(function (data) {
                                    $("body").append(data);
                                    $("#myModal").modal('show');
                                    $.get('/lms/custom/utils/workshops.json', function (data) {
                                        $('#slots').typeahead({source: data, items: 24});
                                    }, 'json');
                                });
                            })
                            .fail(function () {
                                console.log('Failed to load bootstrap.min.js');
                            });
                } // dialog_loaded!=true
                else {
                    console.log('Script already loaded');
                    $("#myModal").modal('show');
                } // end else

            } // end if selected.length > 0
            else {
                $('#sch_err').html('Please select at least one student');
            }
        }


        if (event.target.id == 'print') {
            print_certificates();
        }

        if (event.target.id == 'send') {
            send_certificates();
        }

        if (event.target.id == 'students_none') {
            $('.students').each(function () { //loop through each checkbox
                this.checked = false; //select all checkboxes with class "cert"              
            });
        }

        if (event.target.id == 'add_students') {
            get_students_modal_box();
        }


        if (event.target.id.indexOf("upd_slogan_") >= 0) {
            var list = event.target.id.replace('upd_slogan_', "");
            var ids = list.split('_');
            var sloganid = ids[0];
            var bannerid = ids[1];
            var slogan_elid = '#input_slogan_' + sloganid + '_' + bannerid;
            var text = $(slogan_elid).val();
            if (text != '') {
                var url = "/lms/custom/index/update_slogan.php";
                $.post(url, {sloganid: sloganid, bannerid: bannerid, text: text}).done(function (data) {
                    console.log('Server response: ' + data);
                    get_index_page();
                });
            } // end if text != ''
        } // end if event.target.id.indexOf("upd_slogan_") >= 0

        if (event.target.id.indexOf("first_") >= 0) {
            var id = event.target.id.replace('first_', "");
            if (confirm('Set current banner as first one?')) {
                var url = "/lms/custom/index/set_first_banner.php";
                $.post(url, {id: id}).done(function (data) {
                    console.log('Server response: ' + data);
                    get_index_page();
                }); // end if $.post
            } // end if confirm
        } // end if event.target.id.indexOf("first_") >= 0

    }); // end of $('#region-main').on('click', 'a'

    $(document).on('change', '[type=checkbox]', function (event) {
//console.log('Event id: ' + event.target.id);
        var courseid = event.target.id.replace('installment_', '');
        var installment_el = '#installment_' + courseid;
        //console.log('Installment: ' + installment_el);
        var num_payments_el = '#num_payments_' + courseid;
        //console.log('Num payments: ' + num_payments_el);
        var installment_status = $(installment_el).is(':checked');
        //console.log('Installment status: ' + installment_status);
        if (installment_status == true) {
            $(num_payments_el).prop("disabled", false);
        } else {
            $(num_payments_el).prop("disabled", true);
        }

        if (event.target.id.indexOf("slot_students_") >= 0) {
            var id = event.target.id.replace('slot_students_', "");
            var divid = '#' + id;
            var main_checkbox_id = '#slot_students_' + id;
            var status = $(main_checkbox_id).prop("checked");
            console.log('Main checkbox status:' + status);
            var checkboxes = $(divid).find("input[type='checkbox']");
            $.each(checkboxes, function (i, item) {
                if (status == true) {
                    $(item).prop("checked", true);
                    //item.attr("checked", true);
                } // end if $(main_checkbox_id).attr("checked") != 'checked'
                else {
                    $(item).prop("checked", false);
                } // end else
            }); // end each
        }

    });
    $('#region-main').on('change', 'select', function (event) {

//console.log(event.target.id);

        if (event.target.id == 'invoice_categories') {
            var id = $('#invoice_categories').val();
            console.log('Category id: ' + id);
            get_invoice_category_courses(id);
        }

        if (event.target.id == 'course_categories') {
            var id = $('#course_categories').val();
            console.log('Category id: ' + id);
            get_category_courses(id);
        }


        if (event.target.id == 'send_course_categories') {
            var id = $('#send_course_categories').val();
            console.log('Category id: ' + id);
            get_category_courses2(id);
        }

        if (event.target.id == 'courses') {
            var id = $('#courses').val();
            var slotid = 0;
            get_course_users(id);
            get_users_states();
            get_user_cities_by_state(id, slotid);
            get_course_workshops(id);

        }

        if (event.target.id == 'send_courses') {
            var id = $('#send_courses').val();
            get_course_users2(id);
        }

        if (event.target.id == 'workshops') {
            //var id = $('#workshops').val();
            //get_workshop_users(id);
            //get_users_states();
        }

        if (event.target.id == 'user_states') {
            var id = $('#user_states').val();
            var slotid = $('#workshops').val();
            get_user_cities_by_state(id, slotid);
        }

        if (event.target.id == 'users') {
            $('#installment_params').show();
            $('#payment_options').show();
        }

        if (event.target.id == 'states') {
            get_state_workshops();
        }

        if (event.target.id == 'categories') {
            var category_id = $('#categories').val();
            get_category_course(category_id);
        }

        if (event.target.id == 'register_courses') {
            get_register_course_states();
            var id = $('#register_courses').val();
            get_course_users(id);
        }

        if (event.target.id == 'register_state') {
            get_register_course_cities();
        }



    }); // end of $('#region-main').on('change', 'select',

    $('body').on('change', 'select', function (event) {

        if (event.target.id == 'categories') {
            var category_id = $('#categories').val();
            get_category_course(category_id);
        }

        if (event.target.id == 'register_courses') {
            get_register_course_states();
            var id = $('#register_courses').val();
            get_course_users(id);
        }

        if (event.target.id == 'register_state') {
            get_register_course_cities();
        }


    }); // end of $('#body').on('change', 'select', 

    function get_contact_page() {
        var url = "/lms/custom/contact/index.php";
        $.post(url, {id: 1}).done(function (data) {
            $('#region-main').html(data);
        });
    }

    function update_contact_page(data) {
        var url = "/lms/custom/contact/edit.php";
        $.post(url, {data: data}).done(function () {
            $('#region-main').html("<p align='center'>Data successfully updated. </p>");
        });
    }

    function get_late_fee_page() {
        var url = "/lms/custom/late/index.php";
        $.post(url, {id: 1}).done(function (data) {
            $('#region-main').html(data);
        });
    }

    function update_late_fee(item) {

        var courseid = item.replace("update_late_", "");
        var delay_id = "#fee_delay_" + courseid;
        var amount_id = "#fee_amount_" + courseid;
        var err_id = '#late_err_' + courseid;
        var url = "/lms/custom/late/edit.php";
        var fee_delay = $(delay_id).val();
        var fee_amount = $(amount_id).val();
        //console.log('Course id: '+courseid);
        //console.log('Fee delay: '+fee_delay);
        //console.log('Fee amount: '+fee_amount);        

        if (fee_delay > 0 && fee_amount > 0) {
            $.post(url, {period: fee_delay, amount: fee_amount, courseid: courseid}).done(function (data) {
                $(err_id).html("<span style='color:black;'>" + data + "</span>");
            });
        } // end if fee_delay>0 && fee_amount>0
        else {
            $(err_id).html('Please provide values for amount and delay period');
        } // end else 


    }

    function get_user_credentials_page() {

        var url = "/lms/custom/users/get_users_page.php";
        $.post(url, {id: 1}).done(function (data) {
            $('#region-main').html(data);
            $.get('/lms/custom/utils/data.json', function (data) {
                $('#search_user_input').typeahead({source: data, items: 24});
            }, 'json');
        });
    }

    function search_user_by_email() {
        var email = $('#search_user_input').val();
        if (email != '') {
            $('#user_search_err').html('');
            $('#ajax_loader').show();
            var url = "/lms/custom/users/search_user.php";
            $.post(url, {email: email}).done(function (data) {
                $('#ajax_loader').hide();
                $('#users_container').html(data);
            });
            $('#pagination').hide();
        } // end if email != ''
        else {
            $('#user_search_err').html('Please provide search criteria');
        }

    }

    function clear_user_filter() {
        var url = "/lms/custom/users/get_users_page.php";
        $.post(url, {id: 1}).done(function (data) {
            console.log('Server response: ' + data);
            $('#region-main').html(data);
        });
    }

    function get_partial_payments_page() {
        var url = "/lms/custom/partial/get_partial_payments_page.php";
        $.post(url, {id: 1}).done(function (data) {
            $('#region-main').html(data);
            $.get('/lms/custom/utils/data.json', function (data) {
                $("#search_partial").typeahead({source: data, items: 24});
            }, 'json');
        });
    }

    function get_index_page() {
        var url = "/lms/custom/index/get_index_page.php";
        $.post(url, {id: 1}).done(function (data) {
            $('#region-main').html(data);
        });
    }

    function get_campus_page() {
        var url = "/lms/custom/campus/get_campus_page.php";
        $.post(url, {id: 1}).done(function (data) {
            $('#region-main').html(data);
        });
    }

    function get_schedule_page() {
        var url = "/lms/custom/schedule/get_schedule_page.php";
        $.post(url, {id: 1}).done(function (data) {
            $('#region-main').html(data);
        });
    }

    function get_refund_page() {
//console.log('It is refund page ...');
        var url = "/lms/custom/payments/get_refund_page.php";
        $.post(url, {id: 1}).done(function (data) {
            $('#region-main').html(data);
        });
    }

    function get_instructors_page() {
        var url = "/lms/custom/instructors/get_instructors_page.php";
        $.post(url, {id: 1}).done(function (data) {
            $('#region-main').html(data);

            $.post('/lms/custom/utils/states.json', {id: 1}, function (data) {
                $('#instructor_state').typeahead({source: data, items: 240});
            }, 'json');

            $.post('/lms/custom/utils/cities.json', {id: 1}, function (data) {
                $('#instructor_city').typeahead({source: data, items: 52000});
            }, 'json');
        });
    }

    function get_groups_page() {
        var url = "/lms/custom/usrgroups/get_groups_page.php";
        $.post(url, {id: 1}).done(function (data) {
            $('#region-main').html(data);
            $.get('/lms/custom/utils/groups.json', function (data) {
                $('#group_search_text').typeahead({source: data, items: 240});
            }, 'json');
        });
    }

    function get_deposit_page() {
        var url = "/lms/custom/deposit/get_deposit_page.php";
        $.post(url, {id: 1}).done(function (data) {
            $('#region-main').html(data);
            $("#dep_date1").datepicker();
            $("#dep_date2").datepicker();
        });
    }

    function get_survey_report() {
        var url = "/lms/custom/survey/get_survey_page.php";
        $.post(url, {id: 1}).done(function (data) {
            $('#region-main').html(data);
        });
    }

    /************************************************************************
     * 
     *                   Menu processing items
     * 
     ************************************************************************/


    $("#prices").click(function (event) {
        get_price_items_from_category(event.target.id);
    });
    $("#campus").click(function (event) {
        update_navigation_status__menu('Campus Locations');
        get_campus_page(event.target.id);
    });
    $("#sch").click(function (event) {
        update_navigation_status__menu('Workshops schedule');
        get_schedule_page();
    });
    $("#groups").click(function (event) {
        update_navigation_status__menu('Groups');
        get_groups_page();
    });
    $("#deposit").click(function (event) {
        update_navigation_status__menu('Deposit');
        get_deposit_page();
    });

    $("#index").click(function (event) {
        update_navigation_status__menu('Index page');
        get_index_page();
    });
    $("#instructors").click(function (event) {
        update_navigation_status__menu('Instructors');
        get_instructors_page();
    });
    $("#about").click(function (event) {
        update_navigation_status__menu('About');
        get_about_edit_page();
    });
    $("#feedback").click(function (event) {
        update_navigation_status__menu('Feedback');
        get_feedback_page();
    });
    $("#Google_Map").click(function (event) {
        update_navigation_status__menu('Google Map');
        get_google_map_page();
    });
    $("#Certificates").click(function (event) {
        update_navigation_status__menu('Certificates');
        get_certificates_page();
    });
    $("#hotels").click(function (event) {
        update_navigation_status__menu('Hotels');
        get_hotels_page();
    });
    $("#inventory").click(function (event) {
        update_navigation_status__menu('Inventory');
        get_inventory_page();
    });
    $("#hotel_expenses").click(function (event) {
        update_navigation_status__menu('Hotel Expenses');
        get_hotel_expenses_page()
    });

    $("#promote").click(function (event) {
        update_navigation_status__menu('Promotions');
        get_promotion_page();
    });
    $("#Testimonial").click(function (event) {
        update_navigation_status__menu('Testimonial');
        get_testimonial_page();
    });
    $("#terms").click(function (event) {
        update_navigation_status__menu('Terms and Conditions');
        get_terms_page();
    });
    $("#Photo_Gallery").click(function (event) {
        update_navigation_status__menu('Photo Gallery');
        get_gallery_index_page();
    });
    $("#Groups").click(function (event) {
        update_navigation_status__menu('Private Groups');
        get_private_groups_requests_list();
    });
    $("#taxes").click(function (event) {
        update_navigation_status__menu('State taxes');
        get_state_taxes_list();
    });
    $("#data_inv").click(function (event) {
        update_navigation_status__menu('Invoice');
        get_invoice_spec_page();
    });
    $("#send_inv").click(function (event) {
        update_navigation_status__menu('Send invoice');
    });
    $("#send_inv").click(function (event) {
        update_navigation_status__menu('Send invoice');
        send_invoice_to_user();
    });
    $("#opn_inv").click(function (event) {
        update_navigation_status__menu('Open invoices');
        get_open_invoices_page();
    });
    $("#paid_inv").click(function (event) {
        update_navigation_status__menu('Paid invoices');
        get_paid_invoice_page();
    });
    $("#installment").click(function (event) {
        update_navigation_status__menu('Installment users');
        get_installment_page();
    });
    $("#user_report").click(function (event) {
        update_navigation_status__menu('Users stats');
        get_users_stats_page();
    });
    $("#payments_report").click(function (event) {
        update_navigation_status__menu('Payments log');
        get_payment_log_page();
    });
    $("#cash").click(function (event) {
        update_navigation_status__menu('Cash payments');
        get_cash_payments_page()
    });
    $("#cheque").click(function (event) {
        update_navigation_status__menu('Cheque payments');
        get_check_payments_page();
    });
    $("#cards").click(function (event) {
        update_navigation_status__menu('Credit cards payments');
        get_credit_card_payments_page();
    });
    $("#refund").click(function (event) {
        update_navigation_status__menu('Refund payments');
        get_refund_page();
    });
    $("#free").click(function (event) {
        update_navigation_status__menu('Free');
        get_free_payments();
    });
    $("#program_reports").click(function (event) {
        update_navigation_status__menu('Program reports');
        get_program_report();
    });
    $("#revenue_reports").click(function (event) {
        update_navigation_status__menu('Revenue reports');
        get_revenue_report();
    });

    $("#survey_reports").click(function (event) {
        update_navigation_status__menu('Survey reports');
        get_survey_report();
    });
    $("#workshop_reports").click(function (event) {
        update_navigation_status__menu('Workshop reports');
        get_workshop_report();
    });
    $("#get_cert").click(function (event) {
        update_navigation_status__menu('Get Certificate');
        get_certificate();
    });
    $(".ren_cert").click(function (event) {
        update_navigation_status__menu('Renew Certificate');
        var userid = $(this).data('userid');
        var courseid = $(this).data('courseid');
        renew_certificate(userid, courseid);
    });
    $("#renew_fee").click(function (event) {
        update_navigation_status__menu('Renew Fee');
        get_renew_fee_page();
    });
    $("#contact_page").click(function (event) {
        update_navigation_status__menu('Contact page');
        get_contact_page();
    });
    $("#late_fee").click(function (event) {
        update_navigation_status__menu('Late Fee Settings');
        get_late_fee_page();
    });
    $("#user_cred").click(function (event) {
        update_navigation_status__menu('User credentials');
        get_user_credentials_page();
    });
    $("#partial").click(function (event) {
        update_navigation_status__menu('Partial payments');
        get_partial_payments_page();
    });
    $("#cash_report").click(function () {
        update_navigation_status__menu('Cash report');
        var url = "/lms/custom/reports/get_cash_report.php";
        $.post(url, {id: 1}).done(function (data) {
            $('#region-main').html(data);
        });
    });
    $("#cheque_report").click(function () {
        update_navigation_status__menu('Cheque report');
        var url = "/lms/custom/reports/get_cheque_report.php";
        $.post(url, {id: 1}).done(function (data) {
            $('#region-main').html(data);
        });
    });
    $("#search_partial_button").click(function () {
        var item = $('#search_partial').val();
        if (item != '') {
            $('#partial_err').html('');
            var url = "/lms/custom/partial/search_partial.php";
            $.post(url, {item: item}).done(function (data) {
                $('#partial_container').html(data);
            });
        } else {
            console.log('Inside else ...');
            $('#partial_err').html('Please provide search criteria');
        }
    });
    $("#permissions").click(function () {
        update_navigation_status__menu('Permissions');
        var url = "/lms/custom/reports/get_permissions_page.php";
        $.post(url, {id: 1}).done(function (data) {
            $('#region-main').html(data);
        });
    });
    $("#faq").click(function () {
        console.log('FAQ function ...');
        update_navigation_status__menu('FAQ');
        var url = "/lms/custom/faq/get_faq_page.php";
        $.post(url, {
            id: 1
        }).done(function (data) {
            $('#region-main').html(data);
        });
    });
    /************************************************************************
     * 
     *      Code related to courses selection by logged students
     * 
     ***********************************************************************/

    function get_category_course(category_id) {
        var url = "https://" + domain + "/functionality/php/get_selected_course.php";
        var request = {cat_id: category_id};
        $.post(url, request).done(function (data) {
            $("#cat_course").html(data);
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

    $('#program_section').on('change', function (event) {

        if (event.target.id == 'categories') {
            var category_id = $('#categories').val();
            get_category_course(category_id);
        }

        if (event.target.id == 'register_courses') {
            get_register_course_states();
        } // end if event.target.id == 'policy'

        if (event.target.id == 'register_state') {
            get_register_course_cities();
        } // end if event.target.id == 'policy'

    });
    function assign_user_to_course() {
        var courseid = $('#register_courses').val();
        var slotid = $('#register_cities').val();
        var userid = $('#userid').val();
        console.log('Selected course: ' + courseid);
        if (courseid > 0) {
            $('#program_err').html('');
            var url = "/lms/custom/my/get_course_schedule.php";
            var request = {courseid: courseid};
            $.post(url, request).done(function (data) {
                if (data > 0) {
                    if (slotid == 0) {
                        $('#program_err').html('Please select state and city');
                    } // end if slotid==0
                    else {
                        var url = "/lms/custom/my/enrol_user_to_course.php";
                        var request = {courseid: courseid, slotid: slotid, userid: userid};
                        $.post(url, request).done(function (data) {
                            $('#program_err').html("<span style='color:black;'>" + data + "</span>");
                            //window.location.reload();
                        });
                    } // end else 
                } // end if data>0
                else {
                    var url = "/lms/custom/my/enrol_user_to_course.php";
                    var request = {courseid: courseid, slotid: slotid, userid: userid};
                    $.post(url, request).done(function (data) {
                        $('#program_err').html("<span style='color:black;'>" + data + "</span>");
                        //window.location.reload();
                    });
                } // end else when there is no course schedule
            });
        } // end if courseid>0
        else {
            $('#program_err').html('Please select program');
        }
    }

    $('#program_section').on('click', function (event) {
        //console.log('Item clicked: ' + event.target.id);
        if (event.target.id == 'internal_apply') {
            assign_user_to_course();
        }
    });
    $('#make_college_strudent_partial_payment').on('click', function () {
        var courseid = $('#courseid').val();
        var userid = $('#userid').val();
        var slotid = $('#slotid').val();
        var amount = $('#amount').val();
        if (amount != '' && $.isNumeric(amount)) {
            $('#partial_err').html('');
            var url = "http://medical2.com/index.php/payments/index/" + userid + "/" + courseid + "/" + slotid + "/" + amount;
            window.open(url, "Payment");
        } // end if amount!='' &&  $.isNumeric(amount)
        else {
            $('#partial_err').html('Please provide amount to be charged');
        }

    });
    $("body").click(function (event) {

        //console.log('Element clicked: ' + event.target.id);

        if (event.target.id.indexOf("faq_edit_") >= 0) {
            var id = event.target.id.replace("faq_edit_", "");
            //console.log('ID ' + id);
            var js_url = "https://" + domain + "/assets/js/bootstrap.min.js";
            $.getScript(js_url).done(function () {
                var url = "https://" + domain + "/lms/custom/faq/get_faq_edit_page.php";
                var request = {id: id};
                $.post(url, request).done(function (data) {
                    $("body").append(data);
                    $("#myModal").modal('show');
                });
            }).fail(function () {
                console.log('Failed to load bootstrap.min.js');
            });
        }



        if (event.target.id == 'cancel_faq_edit') {
            $("#myModal").remove();
            $("[data-dismiss=modal]").trigger({type: "click"});
            dialog_loaded = false;
        }

        if (event.target.id == 'faq_add') {
            var js_url = "https://" + domain + "/assets/js/bootstrap.min.js";
            $.getScript(js_url).done(function () {
                var url = "https://" + domain + "/lms/custom/faq/faq_add.php";
                var request = {id: id};
                $.post(url, request).done(function (data) {
                    //console.log('Server data ...' + data);
                    $("body").append(data);
                    $("#myModal").modal('show');
                });
            }).fail(function () {
                console.log('Failed to load bootstrap.min.js');
            });
        }

        if (event.target.id == 'faq_add_cat') {
            var js_url = "https://" + domain + "/assets/js/bootstrap.min.js";
            $.getScript(js_url).done(function () {
                var url = "https://" + domain + "/lms/custom/faq/get_add_cat_dialog.php";
                var request = {id: id};
                $.post(url, request).done(function (data) {
                    $("body").append(data);
                    $("#myModal").modal('show');
                });
            }).fail(function () {
                console.log('Failed to load bootstrap.min.js');
            });
        }

        if (event.target.id == 'del_cat') {
            var js_url = "https://" + domain + "/assets/js/bootstrap.min.js";
            $.getScript(js_url).done(function () {
                var url = "https://" + domain + "/lms/custom/faq/get_del_cat_dialog.php";
                var request = {id: id};
                $.post(url, request).done(function (data) {
                    $("body").append(data);
                    $("#myModal").modal('show');
                });
            }).fail(function () {
                console.log('Failed to load bootstrap.min.js');
            });
        }


        if (event.target.id == 'del_cat_button') {
            var catid = $('#faq_categories2').val();
            console.log('Category ID: ' + catid);
            if (catid > 0) {
                $('#faq_err').html('');
                var url1 = "https://" + domain + "/lms/custom/faq/cat_has_items.php";
                $.post(url1, {id: catid}).done(function (data) {
                    if (data == 0) {
                        if (confirm('Delete current category?')) {
                            $('#faq_err').html('');
                            var url2 = "https://" + domain + "/lms/custom/faq/delete_cat.php";
                            $.post(url2, {id: catid}).done(function () {
                                $("[data-dismiss=modal]").trigger({type: "click"});
                                $('#faq').trigger({type: "click"});
                            });
                        } // end if confirm
                    } // end if data == 0
                    else {
                        $('#faq_err').html('Selected category contains questions. Deletion is impossible');
                    } // end else
                }); // end of post

            } // end if
            else {
                $('#faq_err').html('Please select category');
            } // end else
        }

        if (event.target.id == 'add_cat') {
            var name = $('#cat_name').val();
            if (name != '') {
                $('#faq_err').html('');
                var url1 = "/lms/custom/faq/exists.php";
                $.post(url1, {name: name}).done(function (data) {
                    if (data == 0) {
                        $('#faq_err').html('');
                        var url2 = "/lms/custom/faq/add_category.php";
                        $("[data-dismiss=modal]").trigger({type: "click"});
                        $.post(url2, {name: name}).done(function () {
                            $('#faq').trigger({type: "click"});
                        });
                    } // end if
                    else {
                        $('#faq_err').html('Category name already exists');
                    } // end else 
                }) // end of post
            } // end if
            else {
                $('#faq_err').html('Please provide category name');
            } // end else 
        }

        if (event.target.id == 'add_faq') {
            var q = $('#q').val();
            var a = $('#a').val();
            var catid = $('#faq_categories').val();
            console.log('CATID: ' + catid);
            if (catid > 0) {
                if (q != '' && a != '') {
                    var url = "/lms/custom/faq/add_faq.php";
                    $("[data-dismiss=modal]").trigger({type: "click"});
                    var request = {q: q, a: a, catid: catid};
                    $.post(url, request).done(function (data) {
                        console.log('Server response ...' + data);
                        var url = "/lms/custom/faq/get_faq_page.php";
                        $.post(url, {id: 1}).done(function (data) {
                            $('#region-main').html(data);
                        });
                    });
                } // end if q!='' && a!=''
                else {
                    $('#faq_err').html('Please provide FAQ question and answer');
                }
            } // end if catid>0
            else {
                $('#faq_err').html('Please select category');
            }


        }

        if (event.target.id == 'update_faq') {
            var id = $('#id').val();
            var q = $('#q').val();
            var a = $('#a').val();
            var catid = $('#faq_categories2').val();
            if (catid > 0 && q != '' && a != '') {
                $('#faq_err').html('');
                var url = "/lms/custom/faq/update_faq.php";
                var request = {id: id, q: q, a: a, catid: catid};
                $.post(url, request).done(function () {
                    $("[data-dismiss=modal]").trigger({type: "click"});
                    $('#faq').trigger({type: "click"});
                }); // end of post
            } // end if
            else {
                $('#faq_err').html('Please provide required data in required fields');
            }
        }

        if (event.target.id.indexOf("del_slot_") >= 0) {
            var id = event.target.id.replace("del_slot_", "");
            console.log('Slot id: ' + id);
            if (confirm('Delete current workshop?')) {
                var url = "/lms/custom/schedule/delete_workskhop.php";
                $.post(url, {id: id}).done(function (data) {
                    console.log('Server response: ' + data);
                    document.location.reload();
                });
            }
        }


        if (event.target.id.indexOf("faq_del_") >= 0) {
            var id = event.target.id.replace("faq_del_", "");
            if (confirm('Delete this item?')) {
                var url = "/lms/custom/faq/delete_faq.php";
                var request = {id: id};
                $.post(url, request).done(function (data) {
                    var url = "/lms/custom/faq/get_faq_page.php";
                    $.post(url, {id: 1}).done(function (data) {
                        $('#region-main').html(data);
                    });
                });
            }
        }

        if (event.target.id.indexOf("permission_") >= 0) {
            var moduleid = event.target.id.replace("permission_", "");
            var status;
            if ($('#' + event.target.id).is(':checked')) {
                status = 1;
            } else {
                status = 0;
            }
            if (confirm('Change permissions for current module?')) {
                var url = "/lms/custom/reports/update_permission.php";
                var request = {moduleid: moduleid, status: status};
                $.post(url, request).done(function (data) {
                    $("#status").html(data);
                });
            } // end if confirm
        }

        if (event.target.id == 'cancel') {
            $("#myModal").remove();
            dialog_loaded = false;
        }

        if (event.target.id == 'make_new_refund') {
            var paymentid = $('#course_payments').val();
            var amount = $('#refund_amount').val();
            console.log('Payment ID: ' + paymentid);
            if (paymentid > 0 && amount != '' && $.isNumeric(amount)) {
                $('#refund_err').html('');
                if (confirm('Make refund for current payment?')) {
                    var url = "/lms/custom/payments/make_refund.php";
                    var request = {paymentid: paymentid, amount: amount};
                    $.post(url, request).done(function (data) {
                        console.log('Server response: ' + data);
                        $("[data-dismiss=modal]").trigger({type: "click"});
                    });
                } // end if confirm
            } // end if paymentid>0
            else {
                $('#refund_err').html('Please select user payment and amount');
            }
        }


        if (event.target.id == 'add_user_to_slot') {
            add_user_to_slot();
        }

        if (event.target.id == 'move_user_to_slot') {
            var users = $('#students').val();
            var slotid = $('#slots').val();
            var scheduler = $('#scheduler').val();
            console.log('Students list: ' + users);
            console.log('Slot id: ' + slotid);
            if (confirm('Move selected students to another workshop?')) {
                var url = "/lms/custom/schedule/move_students.php";
                var request = {users: users, slotid: slotid, schedulerid: scheduler};
                $.post(url, request).done(function (data) {
                    $('#program_err').html(data);
                    console.log('Server response: ' + data);
                    document.location.reload();
                });
            } // end if confirm
        }

        if (event.target.id == 'recreate') {
            recertificate_done();
        }

        if (event.target.id == 'show_refund_page') {
            var pwd = $('#refund_pwd').val();
            if (pwd == '') {
                $('#refund_pwd_err').html('Password field is required');
            } else {
                var url = "/lms/custom/payments/get_old_refund_pwd.php";
                var request = {id: 1};
                $.post(url, request).done(function (data) {
                    if (pwd == data) {
                        $('#refund_pwd_err').html('');
                        $('#pwd_container').hide();
                        $('#refund_container').show('slow');
                    } else {
                        $('#refund_pwd_err').html('Wrong password');
                    }
                });
            }
        }

        if (event.target.id == 'refund_pwd_link') {
            update_navigation_status__menu('Refund password');
            var url = "/lms/custom/payments/get_refund_pwd.php";
            var request = {id: 1};
            $.post(url, request).done(function (data) {
                $('#region-main').html(data);
            });
        }

        if (event.target.id == 'register_user') {
            update_navigation_status__menu('Register User');
            var url = "/lms/custom/register/get_register_form.php";
            var request = {id: 1};
            $.post(url, request).done(function (data) {
                $('#region-main').html(data);
            });
        }

        if ($('#da').prop('checked')) {
            $('#diff_address').show();
        } // end if
        else {
            $('#diff_address').hide();
        } // end else

        if (event.target.id == 'update_refund_pwd') {
            var url = "/lms/custom/payments/get_old_refund_pwd.php";
            var request = {id: 1};
            $.post(url, request).done(function (data) {
                var db_old_pwd = data;
                var old_pwd = $('#old_pwd').val();
                var new_pwd1 = $('#new_pwd1').val();
                var new_pwd2 = $('#new_pwd2').val();
                console.log('New pwd1: ' + new_pwd1);
                console.log('New pwd2:' + new_pwd2);
                if (old_pwd == '' || new_pwd1 == '' || new_pwd2 == '') {
                    $('#pwd_err').html('All fields are required');
                } // end if old_pwd == '' || new_pwd1 == '' || new_pwd2 == ''
                else {
                    if (old_pwd != db_old_pwd) {
                        $('#pwd_err').html('Wrong old password');
                    } else {
                        if (new_pwd1 != new_pwd2) {
                            $('#pwd_err').html('Passwords do not match');
                        } else {
                            if (old_pwd == db_old_pwd && new_pwd2 != '' && new_pwd1 == new_pwd2) {
                                $('#pwd_err').html('');
                                var url = "/lms/custom/payments/update_refund_pwd.php";
                                var request = {pwd: new_pwd1};
                                $.post(url, request).done(function (data) {
                                    $('#pwd_err').html("<span style='color:black;'>" + data + "</span>");
                                });
                            }
                        } // end else
                    } //end else 
                } // end else
            });
        }

    }); // end of body click function


    $('body').on('change', function (event) {

        //console.log('Body change event: ' + event);
        var elClass = $(event.target).attr('class');
        var elID = event.target.id;

        //console.log('Event id: ' + elID);
        console.log('Event class: ' + elClass);


        if (event.target.id == 'user_calendar') {
            var date = new Date($('#user_calendar').datepicker("getDate"));
            var userid = $('#user_calendar').data('userid');
            var month = date.getMonth() + 1;
            var day = date.getDate();
            var year = date.getFullYear();
            var humanDate = [month, day, year].join('/');
            if (confirm('Update calendar?')) {
                var calendar = {userid: userid, date: humanDate};
                var url = "/lms/custom/calendar/update_user_calendar.php";
                $.post(url, {calendar: JSON.stringify(calendar)}).done(function (data) {
                    console.log(data);
                    document.location.reload();
                });
            } // end if confirm
        }

        if ($(event.target).attr('class') == 'at_calendar hasDatepicker') {
            console.log('Attendance calendar clicked ...');
            var courseid = $('.at_calendar').data('courseid');
            var userid = $('.at_calendar').data('userid');
            var date = new Date($('.at_calendar').datepicker("getDate"));
            var month = date.getMonth() + 1;
            var day = date.getDate();
            var year = date.getFullYear();
            var hdate = [month, day, year].join('/');
            var at = {courseid: courseid, userid: userid, date: hdate};
            var exurl = "/lms/custom/my/is_calendar_date_exists.php";
            $.post(exurl, {at: JSON.stringify(at)}).done(function (num) {
                if (num == 0) {
                    if (dialog_loaded !== true) {
                        console.log('Script is not yet loaded starting loading ...');
                        dialog_loaded = true;
                        var js_url = "https://" + domain + "/assets/js/bootstrap.min.js";
                        $.getScript(js_url)
                                .done(function () {
                                    console.log('Script bootstrap.min.js is loaded ...');
                                    var url = "/lms/custom/my/get_presence_modal_dialog.php";
                                    $.post(url, {at: JSON.stringify(at)}).done(function (data) {
                                        $("body").append(data);
                                        $("#myModal").modal('show');
                                    });
                                }) // end of done
                                .fail(function () {
                                    console.log('Failed to load bootstrap.min.js');
                                }); // end of fail
                    } // dialog_loaded!=true
                    else {
                        console.log('Script already loaded');
                        $("body").append(data);
                        $("#myModal").modal('show');
                    }
                } // end if num==0
                else {
                    if (confirm('Delete current calendar entry?')) {
                        var url = "/lms/custom/my/delete_calendar_entry.php"
                        $.post(url, {at: JSON.stringify(at)}).done(function () {
                            document.location.reload();
                        });
                    } // end if confirm
                } // end else
            }); // end of post
        }


    }); // end of body change event ...

    $('body').on('change', 'select', function (event) {

        if (event.target.id == 'courses_list') {
            $('#preview_container').html('');
            var courseid = $('#courses_list').val();
            if (courseid > 0) {
                var url = "/lms/custom/certificates/get_certificate_data.php";
                $.post(url, {id: courseid}).done(function (data) {
                    var template = JSON.parse(data);
                    console.log(template);
                    if (template.status == 0) {
                        $('#template_err').html('');
                        $('#cert_issuer').val('');
                        $('#cert_title').val('');
                        CKEDITOR.instances.editor1.setData('');
                        $('#person_name1').val('');
                        $('#person_title1').val('');
                        $('#person_name2').val('');
                        $('#person_title2').val('');
                        $('#person_name3').val('');
                        $('#person_title3').val('');
                        $('#template_err').html('Selected program does not contain certificate template. Please create it');
                    } // end if template.status==0
                    else {
                        $('#template_err').html('');
                        $('#cert_issuer').val(template.issuer);
                        $('#cert_title').val(template.title);
                        CKEDITOR.instances.editor1.setData(template.content);
                        $('#person_name1').val(template.person1);
                        $('#person_title1').val(template.person1_title);
                        $('#person_name2').val(template.person2);
                        $('#person_title2').val(template.person2_title);
                        $('#person_name3').val(template.person3);
                        $('#person_title3').val(template.person3_title);
                    } // end else
                }); // end if $.post
            } // end if
        }

        if (event.target.id == 'any_invoice_categories') {
            var id = $('#any_invoice_categories').val();
            console.log('Category id: ' + id);
            var url = "/lms/custom/invoices/get_any_invoice_course_by_category.php";
            $.post(url, {id: id}).done(function (data) {
                $('#invoice_category_courses').html(data);
            }); // end if $.post
        }

        if (event.target.id == 'any_invoice_courses') {
            var id = $('#any_invoice_courses').val();
            console.log('Courseid id: ' + id);
            var url = "/lms/custom/invoices/get_any_invoice_users.php";
            $.post(url, {id: id}).done(function (data) {
                $('#invoice_courses_users').html(data);
            }); // end if $.post
        }

        if (event.target.id == 'faq_categories') {
            var id = $('#faq_categories').val();
            if (id > 0) {
                var url = "/lms/custom/faq/get_faq_by_category.php";
                var request = {id: id};
                $.post(url, request).done(function (data) {
                    $('#faq_container').html(data);
                });
            } // end if id>0
        }

        if (event.target.id == 'camapaign') {
            var id = $('#camapaign').val();
            console.log('Campaign id: ' + id);
            var url = "/lms/custom/promotion/get_campaign_stat.php";
            var request = {id: id};
            $.post(url, request).done(function (data) {
                $('#campaign_container').html(data);
            });
        }

        if (event.target.id == 'refund_courses') {
            var courseid = $('#refund_courses').val();
            console.log('Course id: ' + courseid);
            if (courseid > 0) {
                var url = "/lms/custom/payments/get_course_payments.php";
                $.post(url, {id: courseid}).done(function (data) {
                    console.log('Server response: ' + data);
                    $('#course_payments_span').html(data);
                }); // end if $.post

            } // end if course_payment_id>0
        }

    }); // end of body change select even 


    $('body').on('click', function (event) {

        if (event.target.id == 'attach_invoice_payment') {

            var invoice_id = $('#invoice_id').val();
            var type = $('#invoice_payment_type').val();
            if (invoice_id > 0 && type != '') {
                $('#any_invoice_users_err').html("");
                var user = $('#search_user_input').val();
                if (user != '') {
                    $('#any_invoice_users_err').html("");
                    var url = "/lms/custom/invoices/attach_any_invoice_payment.php";
                    $.post(url, {invoice_id: invoice_id, type: type, users_list: user}).done(function (data) {
                        console.log(data);

                        $("[data-dismiss=modal]").trigger({type: "click"});
                        //get_open_invoices_page();
                    }); // end if $.post

                } // end if users_list != '' && users_list != 0 
                else {
                    $('#any_invoice_users_err').html("<span style='color:red;'>Please select users</span>");
                }
            } // end if invoice_id != '' && type != '' && users.length > 0 
            else {
                $('#any_invoice_users_err').html("<span style='color:red;'>Please select users</span>");
            }
        }

        if (event.target.id == 'internal_register_submit') {

            var courseid = $('#register_courses').val();
            var slotid = $('#register_cities').val();
            var firstname = $('#first_name').val();
            var lastname = $('#last_name').val();
            var addr = $('#addr').val();
            var city = $('#city').val();
            var state = $('#state').val();
            var country = $('#country').val();
            var zip = $('#zip').val();
            var phone = $('#phone').val();
            var email = $('#email').val();
            var card_holder = $('#billing_name').val();
            var sum = $('#sum').val();

            if (courseid == 0) {
                $('#personal_err').html('Please select program');
                return;
            }

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

            if (sum == '') {
                $('#personal_err').html('Please provide payment amount');
                return false;
            }

            if (card_holder == '') {
                $('#personal_err').html('Please provide card holder name');
                return false;
            }

            if (card_holder != '') {
                // Remove double spaces between words
                var clean_holder = card_holder.replace(/\s\s+/g, ' ');
                var names_arr = clean_holder.split(" ");

                console.log('names array length: ' + names_arr.length);

                if (names_arr.length == 1) {
                    $('#personal_err').html('Please provide correct card holder name separated by space');
                    return;
                }

                if (names_arr.length == 2) {
                    console.log('Two names case ....');
                    console.log('Holder name: ' + card_holder);
                    var h_firstname = names_arr[0];
                    var h_lastname = names_arr[1];
                    console.log('Billing firstname: ' + h_firstname);
                    console.log('Billing lastname: ' + h_lastname);
                    if (typeof (h_firstname) === "undefined" || h_firstname == '' || typeof (h_lastname) === "undefined" || h_lastname == '') {
                        $('#personal_err').html('Please provide correct card holder name separated by space');
                        return;
                    }
                } // end if names_arr.length == 2

                if (names_arr.length == 3) {
                    console.log('Three names case ...');
                    console.log('Holder name: ' + card_holder);
                    h_firstname = names_arr[0] + ' ' + names_arr[1];
                    h_lastname = names_arr[2];
                    console.log('Billing firstname: ' + h_firstname);
                    console.log('Billing lastname: ' + h_lastname);
                    if (typeof (h_firstname) === "undefined" || h_firstname == '' || typeof (h_lastname) === "undefined" || h_lastname == '') {
                        $('#personal_err').html('Please provide correct card holder name separated by space');
                        return;
                    }
                } // end if names_arr.length == 3

            } // end if card_holder != ''

            var cvv = $('#cvv2').val();
            if (cvv == '') {
                $('#personal_err').html('Please provide CVV code');
                return;
            }

            var card_no = $('#card_no2').val();
            if (card_no == '') {
                $('#personal_err').html('Please provide card number');
                return;
            }

            var card_month = $('#card_m').val();
            if (card_month == 0) {
                $('#personal_err').html('Please select card expiration month');
                return;
            }

            var card_year = $('#card_y').val();
            if (card_year == 0) {
                $('#personal_err').html('Please select card expiration year');
                return;
            }

            if ($('#da').prop('checked')) {
                var bill_addr = $('#addr2').val();
                if (bill_addr == '') {
                    $('#personal_err').html('Please provide billing address');
                    return;
                }


                var bill_city = $('#city2').val();
                if (bill_city == '') {
                    $('#personal_err').html('Please provide city');
                    return;
                }

                var bill_state = $('#state2').val();
                if (bill_state == 0) {
                    $('#personal_err').html('Please select state');
                    return;
                }

                var bill_zip = $('#zip2').val();
                if (bill_zip == '') {
                    $('#personal_err').html('Please provide zip');
                    return;
                }

                var bill_email = $('#email2').val();
                if (bill_email == '') {
                    $('#personal_err').html('Please provide email');
                    return;
                }
                var bill_phone = $('#phone2').val();
                if (bill_phone == '') {
                    $('#personal_err').html('Please provide phone');
                    return;
                }
            }

            $('#personal_err').html('');
            var url = "/functionality/php/is_email_exists.php";
            var request = {email: email};
            $.post(url, request).done(function (data) {
                console.log('Server email exists response: ' + data);
                if (data > 0) {
                    $('#personal_err').html('Email is already in use');
                } // end if data>0
                else {
                    var user = {
                        first_name: firstname,
                        last_name: lastname,
                        addr: addr,
                        city: city,
                        state: state,
                        country: country,
                        zip: zip,
                        inst: 'n/a',
                        phone: phone,
                        email: email,
                        courseid: courseid,
                        slotid: slotid,
                        sum: sum,
                        card_holder: card_holder,
                        cvv: cvv,
                        card_no: card_no,
                        card_month: card_month,
                        card_year: card_year,
                        bill_addr: bill_addr,
                        bill_city: bill_city,
                        bill_state: bill_state,
                        bill_zip: bill_zip,
                        bill_email: bill_email,
                        bill_phone: bill_phone
                    };
                    $('#ajax_loading_payment').show();
                    personal_registration_obj = JSON.stringify(user);
                    //console.log('User object: ' + personal_registration_obj);
                    var signup_url = "/functionality/php/internal_signup.php";
                    var signup_request = {user: JSON.stringify(user)};
                    $.post(signup_url, signup_request).done(function (data) {
                        $('#ajax_loading_payment').hide();
                        $('#personal_err').html("<span style='color:black'>" + data + "</span>");
                    });

                } // end else
            }); // end of post
        }


        if (event.target.id == 'add_subs_button') {
            var coursename = $('#installment_program').val();
            var user = $('#installment_user').val();
            var amount = $('#amount').val();
            var start = $('#subs_start').val();
            var end = $('#subs_exp').val();
            var holder = $('#card_holder').val();
            var card_no = $('#card_no').val();
            var cvv = $('#cvv').val();
            var card_year = $('#card_year').val();
            var card_month = $('#card_month').val();
            var payments_num = $('#payments_num').val();

            if (coursename == '') {
                $('#subs_err').html('Please select program');
                return;
            }

            if (user == '') {
                $('#subs_err').html('Please select user');
                return;
            }

            if (amount == '') {
                $('#subs_err').html('Please provide program fee');
                return;
            }

            if (start == '') {
                $('#subs_err').html('Please select subscription start');
                return;
            }

            if (end == '') {
                $('#subs_err').html('Please select subscription expiration');
                return;
            }

            if (holder == '') {
                $('#subs_err').html('Please provide cardholder name');
                return;
            }

            if (card_no == '') {
                $('#subs_err').html('Please provide card number');
                return;
            }

            if (cvv == '') {
                $('#subs_err').html('Please provide cvv code');
                return;
            }

            if (card_year == '--' || card_month == '--') {
                $('#subs_err').html('Please put expiration');
                return;
            }

            $('#subs_err').html('');
            var subs = {
                coursename: coursename,
                user: user,
                amount: amount,
                start: start,
                end: end,
                payments_num: payments_num,
                holder: holder,
                card_no: card_no,
                cvv: cvv,
                card_year: card_year,
                card_month: card_month
            };

            console.log('Subs object: ' + JSON.stringify(subs));
            $('#ajax_loader2').show();
            var url = "/lms/custom/installment/create_subs.php";
            var request = {subs: JSON.stringify(subs)};
            $.post(url, request).done(function (data) {
                $('#ajax_loader2').hide();
                $('#subs_err').html("<span style='color:black'>" + data + "</span>");
            });

        }

        if (event.target.id == 'installment_search_button') {
            var item = $('#installment_search').val();
            if (item == '') {
                $('#installment_err').html('Please provide search criteria');
            } // end if
            else {
                $('#installment_err').html('');
                $('#ajax_loader').show();
                var url = "/lms/custom/installment/search_subs.php";
                var request = {item: item};
                $.post(url, request).done(function (data) {
                    $('#ajax_loader').hide();
                    $('#installment_container').html(data);
                });
            } // end else

        }

        if (event.target.id == 'installment_clear_button') {
            get_installment_page();
        }

        if (event.target.id == 'get_stat') {

            var interval = null;
            var courseid = $('#courses').val();
            if ($('#byyear').prop("checked")) {
                interval = 'year';
            }

            if ($('#bymonth').prop("checked")) {
                interval = 'month';
            }

            if ($('#byweek').prop("checked")) {
                interval = 'week';
            }

            if (interval == null) {
                $('#stat_err').html('Please select interval');
                return false;
            }

            var start = $('#stat_start').val();
            var end = $('#stat_end').val();
            if (start == '' || end == '') {
                $('#stat_err').html('Please select period');
                return false;
            }

            var stat = {
                start: start,
                end: end,
                interval: interval,
                courseid: courseid};
            $('#stat_err').html('');
            $('#stat_data').html('');
            $('#stat_ajax_loader').show();
            var url = "/lms/custom/reports/get_stat_data.php";
            var request = {stat: JSON.stringify(stat)};
            $.post(url, request).done(function (data) {
                $('#stat_ajax_loader').hide();
                $('#stat_data').html(data);
            });
        }


        if (event.target.id == 'preview_btn') {
            var courseid = $('#courses_list').val();
            var issuer = $('#cert_issuer').val();
            var title = $('#cert_title').val();
            var content = CKEDITOR.instances.editor1.getData();
            var person1 = $('#person_name1').val();
            var person1_title = $('#person_title1').val();
            var person2 = $('#person_name2').val();
            var person2_title = $('#person_title2').val();
            var person3 = $('#person_name3').val();
            var person3_title = $('#person_title3').val();
            var template = {courseid: courseid,
                issuer: issuer,
                title: title,
                content: content,
                person1: person1,
                person1_title: person1_title,
                person2: person2,
                person2_title: person2_title,
                person3: person3,
                person3_title: person3_title};
            //console.log('Template params: ' + JSON.stringify(template));
            //console.log('Content: ' + content);

            if (courseid > 0 && issuer != '' && title != '' && content != '' && person1 != '' && person1_title != '') {
                $('#template_err').html('');
                var url = "/lms/custom/certificates/preview_template.php";
                var request = {template: JSON.stringify(template)};
                $.post(url, request).done(function (data) {
                    //console.log(data);
                    $('#preview_container').html(data);
                });
            } // end if 
            else {
                $('#template_err').html('Please select program and provide template params');
            } // end else

        }

        if (event.target.id == 'save_btn') {
            var courseid = $('#courses_list').val();
            var issuer = $('#cert_issuer').val();
            var title = $('#cert_title').val();
            var content = CKEDITOR.instances.editor1.getData();
            var person1 = $('#person_name1').val();
            var person1_title = $('#person_title1').val();
            var person2 = $('#person_name2').val();
            var person2_title = $('#person_title2').val();
            var person3 = $('#person_name3').val();
            var person3_title = $('#person_title3').val();
            var template = {courseid: courseid,
                issuer: issuer,
                title: title,
                content: content,
                person1: person1,
                person1_title: person1_title,
                person2: person2,
                person2_title: person2_title,
                person3: person3,
                person3_title: person3_title};
            //console.log('Template params: ' + JSON.stringify(template));

            if (courseid > 0 && issuer != '' && title != '' && content != '' && person1 != '' && person1_title != '') {
                $('#template_err').html('');
                if (confirm('Create/Update Certificate template for current program?')) {
                    var url = "/lms/custom/certificates/create_template.php";
                    var request = {template: JSON.stringify(template)};
                    $.post(url, request).done(function (data) {
                        $('#preview_container').html(data);
                    });
                } // end if confirm
            } // end if 
            else {
                $('#template_err').html('Please select program and provide template params');
            } // end else
        }

        if (event.target.id.indexOf("subs_") >= 0) {
            var elID = '#' + event.target.id;
            var subsID = $(elID).data('subsid');
            console.log('Subscritption ID: ' + subsID);
            if (subsID > 0) {
                if (confirm('Cancel current subscription?')) {
                    var url = "/lms/custom/installment/cancel_subscription.php";
                    var request = {subsID: subsID};
                    $.post(url, request).done(function (data) {
                        $('#subs_err').html(data);
                    });
                } // end if
            } // end if
        }

        if (event.target.id == 'add_profile_payment') {
            var userid = $('#userid').val();
            var coursename = $('#coursename').val();
            var ptype = $("input[name='ptype']:checked").val();
            var amount = $('#amount').val();
            var wsname = $('#wsname').val();
            if (amount > 0 && coursename != '') {
                $('#payment_err').html('');
                var url = "/lms/custom/my/get_course_id.php";
                var request = {coursename: coursename, wsname: wsname};
                $.post(url, request).done(function (data) {
                    console.log('Server response: ' + data);
                    var program = JSON.parse(data);
                    if (program.courseid > 0) {
                        if (ptype == 'card') {
                            var url = "/lms/custom/my/enroll_user.php";
                            $.post(url, {userid: userid, courseid: program.courseid}).done(function () {
                                var url = "https://medical2.com/index.php/payments/payment/" + userid + "/" + program.courseid + "/" + program.slotid + "/" + amount;
                                window.open(url, '_blank');
                            });
                        } // end if ptype == 'card'
                        else {
                            if (confirm('Add payment for current user?')) {
                                var url2 = "/lms/custom/my/add_other_payment.php";
                                var payment = {courseid: program.courseid, userid: userid, ptype: ptype, amount: amount, slotid: program.slotid};
                                $.post(url2, {payment: JSON.stringify(payment)}).done(function (data) {
                                    console.log(data);
                                    $("[data-dismiss=modal]").trigger({type: "click"});
                                    document.location.reload();
                                });
                            } // end if
                        }  // end else
                    } // end if courseid>0
                    else {
                        $('#payment_err').html('Error happened...');
                    }
                }); // end of post

            } // end if amount > 0 && coursename != ''
            else {
                $('#payment_err').html('Please select program and provide amount');
            } // end else 
        }


        if (event.target.id == 'move_profile_payment') {
            var userid = $('#userid').val();
            var coursename = $('#coursename').val();
            var oldcourseid = $('#oldcourseid').val();
            var id = $('#d_paymentid').val();
            console.log('Payment data: ' + id);
            var wsname = '';
            if (coursename != '') {
                $('#payment_err').html('');
                var url = "/lms/custom/my/get_course_id.php";
                var request = {coursename: coursename, wsname: wsname};
                $.post(url, request).done(function (data) {
                    var program = JSON.parse(data);
                    if (program.courseid > 0) {
                        if (confirm('Move payment for current user?')) {
                            var url2 = "/lms/custom/my/move_payment.php";
                            var payment = {courseid: program.courseid, userid: userid, oldcourseid: oldcourseid, id: id};
                            $.post(url2, {payment: JSON.stringify(payment)}).done(function (data) {
                                console.log(data);
                                $("[data-dismiss=modal]").trigger({type: "click"});
                                document.location.reload();
                            });
                        } // end if
                    } // end if
                    else {
                        $('#payment_err').html('Error happened...');
                    } // end else
                });
            } // end if
            else {
                $('#payment_err').html('Please select program');
            } // end else
        }

        if (event.target.id == 'add_to_ws') {
            var userid = $('#userid').val();
            var courseid = $('#register_courses').val();
            var slotid = $('#register_cities').val();
            if (courseid > 0) {
                $('#ws_err').html('');
                if (confirm('Add current user to new program?')) {
                    var url = "/lms/custom/my/add_user_to_workshop.php";
                    var ws = {courseid: courseid, userid: userid, slotid: slotid};
                    $.post(url, {ws: JSON.stringify(ws)}).done(function (data) {
                        console.log(data);
                        $("[data-dismiss=modal]").trigger({type: "click"});
                        document.location.reload();
                    });
                } // end if
            } // end if courseid>0
            else {
                $('#ws_err').html('Please select program');
            } // end else 
        }

        if (event.target.id == 'move_to_ws') {
            var appid = $('#appid').val();
            var wsname = $('#wsname').val();
            var courseid = $('#courseid').val();
            if (wsname != '') {
                $('#ws_error').html('');
                if (confirm('Move user to another workshop?')) {
                    var url = "/lms/custom/my/move_workshop.php";
                    var ws = {wsname: wsname, appid: appid, courseid: courseid};
                    $.post(url, {ws: JSON.stringify(ws)}).done(function (data) {
                        console.log(data);
                        $("[data-dismiss=modal]").trigger({type: "click"});
                        document.location.reload();
                    });
                } // end if
            } // end if
            else {
                $('#ws_error').html('Please select workshop');
            } // end else 
        }

        if (event.target.id == 'create_user_cert') {
            var userid = $('#userid').val();
            var coursename = $('#coursename').val();
            var date1 = $('#date1').val();
            var date2 = $('#date2').val();
            var wsname = '';
            if (coursename != '' && date1 != '' && date2 != '') {
                $('#program_err').html('');
                if (confirm('Create certificate for current user?')) {
                    var url = "/lms/custom/my/get_course_id.php";
                    var request = {coursename: coursename, wsname: wsname};
                    $.post(url, request).done(function (data) {
                        var program = JSON.parse(data);
                        if (program.courseid > 0) {
                            var url = "/lms/custom/my/create_user_cert.php";
                            var cert = {userid: userid, courseid: program.courseid, date1: date1, date2: date2};
                            $.post(url, {cert: JSON.stringify(cert)}).done(function (data) {
                                console.log(data);
                                $("[data-dismiss=modal]").trigger({type: "click"});
                                document.location.reload();
                            });
                        } // end if
                        else {
                            $('#program_err').html('Error happened ...');
                        } // end else
                    }); // end of post
                } // end if confirm
            } // end if coursename != '' && date1 != '' && date2 != ''
            else {
                $('#program_err').html('Please select program and certificate dates');
            }

        }

        if (event.target.id == 'renew_user_cert') {
            var id = $('#id').val();
            var date1 = $('#date1').val();
            var date2 = $('#date2').val();
            if (date1 != '' && date2 != '') {
                $('#program_err').html('');
                if (confirm('Update user cetificate?')) {
                    var url = "/lms/custom/my/renew_user_certificate.php";
                    var cert = {id: id, date1: date1, date2: date2};
                    $.post(url, {cert: JSON.stringify(cert)}).done(function (data) {
                        console.log(data);
                        $("[data-dismiss=modal]").trigger({type: "click"});
                        document.location.reload();
                    });
                } // end if confirm
            } // end if
            else {
                $('#program_err').html('Please select certificate dates');
            } // end else 

        }

        if (event.target.id == 'default_email') {
            if ($('#default_email').prop("checked")) {
                $("#user_email").prop("disabled", true);
            } // end if
            else {
                $("#user_email").prop("disabled", false);
            } // end else 
        }

        if (event.target.id == 'send_user_cert') {
            var userid = $('#userid').val();
            var courseid = $('#courseid').val();
            var email = $('#user_email').val();
            if (email != '') {
                $('#cert_err').html('');
                var cert = {userid: userid, courseid: courseid, email: email};
                if (confirm('Send cetificate?')) {
                    var url = "/lms/custom/my/send_user_certificate.php";
                    $.post(url, {cert: JSON.stringify(cert)}).done(function (data) {
                        console.log(data);
                        $("[data-dismiss=modal]").trigger({type: "click"});
                        document.location.reload();
                    });
                } // end if confirm
            } // end if
            else {
                $('#cert_err').html('Please provide email');
            } // end else 

        }

        if (event.target.id == 'renew_user_cert_manager') {
            console.log('Clicked ... ');
            var id = $('#id').val();
            var period = $("input[name='period']:checked").val();
            var url = "/lms/custom/my/renew_user_certificate_manager.php";
            var cert = {id: id, period: period};
            $.post(url, {cert: JSON.stringify(cert)}).done(function (data) {
                var fullcert = $.parseJSON(data);
                $("[data-dismiss=modal]").trigger({type: "click"});
                var url = "http://medical2.com/index.php/payments/index/" + fullcert.userid + "/" + fullcert.courseid + "/0/" + fullcert.amount + "/" + fullcert.period;
                var oWindow = window.open(url, "renew");
            });

        }

        if (event.target.id.indexOf("renew_amount_") >= 0) {
            var id = event.target.id.replace("renew_amount_", "");
            var amount_el = '#amount_' + id;
            var amount_container = '#amount_container_' + id;
            var amount = $(amount_el).val();
            console.log('ID: ' + id);
            console.log('Amount: ' + amount);
            if (amount != '') {
                $('#ren_err').html('');
                var url = "/lms/custom/certificates/update_renew_amount.php";
                var cert = {id: id, amount: amount};
                $.post(url, {cert: JSON.stringify(cert)}).done(function (data) {
                    $(amount_container).html(data);
                });

            } // end if
            else {
                $('#ren_err').html('Please provide amount fee');
            } // end else
        }

        if (event.target.id.indexOf("renew_late_amount_") >= 0) {
            var id = event.target.id.replace("renew_late_amount_", "");
            //var container_el = '#fee_container_' + id;

            var length_el = '#period_length_' + id;
            var length = $(length_el).val();

            var type_el = '#period_types_' + id;
            var type = $(type_el).val()

            var amount_el = '#late_amount_' + id
            var amount = $(amount_el).val();
            if (amount != '') {
                $('#ren_err2').html('');
                var fee = {id: id, length: length, type: type, amount: amount};
                var url = "/lms/custom/certificates/update_renew_late_amount.php";
                $.post(url, {fee: JSON.stringify(fee)}).done(function (data) {
                    console.log(data);
                    $('#ren_err2').html("<span style='color:black'>Item has been updated</span>");
                });
            } // end if 
            else {
                $('#ren_err2').html('Please provide amount');
            } // end else

        }

        if (event.target.id == 'add_course_late_fee') {
            if (dialog_loaded !== true) {
                console.log('Script is not yet loaded starting loading ...');
                dialog_loaded = true;
                var js_url = "https://" + domain + "/assets/js/bootstrap.min.js";
                $.getScript(js_url)
                        .done(function () {
                            console.log('Script bootstrap.min.js is loaded ...');
                            var url = "/lms/custom/certificates/get_add_late_fee_dialog.php";
                            var request = {userid: userid};
                            $.post(url, request).done(function (data) {
                                $("body").append(data);
                                $("#myModal").modal('show');
                                $.get('/lms/custom/utils/programs.json', function (data) {
                                    $('#coursename').typeahead({source: data, items: 24});
                                }, 'json');
                            });
                        })
                        .fail(function () {
                            console.log('Failed to load bootstrap.min.js');
                        });
            } // dialog_loaded!=true
            else {
                console.log('Script already loaded');
                $("body").append(data);
                $("#myModal").modal('show');
            }
        }

        if (event.target.id == 'add_renew_late_fee_button') {
            var coursename = $('#coursename').val();
            var type = $('#period_types').val();
            var length = $('#period_length').val();
            var amount = $('#amount').val();
            if (coursename != '' && amount != '') {
                $('#add_renew_err').html('');
                var fee = {coursename: coursename, type: type, length: length, amount: amount};
                var url = "/lms/custom/certificates/add_renew_late_amount.php";
                $.post(url, {fee: JSON.stringify(fee)}).done(function (data) {
                    console.log(data);
                    $("[data-dismiss=modal]").trigger({type: "click"});
                    document.location.reload();
                });

            } // end if
            else {
                $('#add_renew_err').html('Please select program and provide amount');
            } // end else

        }

        if (event.target.id == 'delete_user_profile') {
            var userid = $('#userid').val();
            console.log('User id: ' + userid);
            var dbpwd = $('#dbpwd').val();
            var pwd = $('#pwd').val();
            if (pwd != '') {
                $('#user_err').html('');
                if (pwd == dbpwd) {
                    $('#user_err').html('');
                    if (confirm('Are you sure?')) {
                        var url = "/lms/custom/my/delete_profile_user.php";
                        $.post(url, {userid: userid}).done(function () {
                            var redirect_url = 'https://medical2.com/lms/my/';
                            $("[data-dismiss=modal]").trigger({type: "click"});
                            document.location.reload();
                        });
                    } // end if
                } // end if
                else {
                    $('#user_err').html('Wrong password');
                }
            } // end if
            else {
                $('#user_err').html('Please provide password to complete this operation');
            } // end else
        }

        if (event.target.id == 'add_campus_location') {
            var lat = $('#lat').val();
            var lon = $('#lon').val();
            var name = $('#name').val();
            var desc = $('#desc').val();
            if (lat != '' && lon != '' && name != '' && desc != '') {
                $('#campus_err').html('');
                var url = "/lms/custom/campus/add_new_campus.php";
                var campus = {lat: lat, lon: lon, name: name, desc: desc};
                console.log(campus);
                $.post(url, {campus: JSON.stringify(campus)}).done(function (data) {
                    console.log('Server response: ' + data);
                    $("[data-dismiss=modal]").trigger({type: "click"});
                    get_campus_page();
                });

            } // end if
            else {
                $('#campus_err').html('Please provide all required fields');
            } // end else
        }

        if (event.target.id == 'edit_campus_location') {
            var lat = $('#lat').val();
            var lon = $('#lon').val();
            var name = $('#name').val();
            var desc = $('#desc').val();
            var id = $('#id').val();
            if (lat != '' && lon != '' && name != '' && desc != '') {
                $('#campus_err').html('');
                var url = "/lms/custom/campus/edit_campus.php";
                var campus = {lat: lat, lon: lon, name: name, desc: desc, id: id};
                console.log(campus);
                $.post(url, {campus: JSON.stringify(campus)}).done(function (data) {
                    console.log('Server response: ' + data);
                    $("[data-dismiss=modal]").trigger({type: "click"});
                    get_campus_page();
                });

            } // end if
            else {
                $('#campus_err').html('Please provide all required fields');
            } // end else
        }

        if (event.target.id == 'create_new_campaign_done') {
            var users = $('#users').val();
            console.log('Users list: ' + users);
            var text = CKEDITOR.instances.campaign_text.getData();
            if (text != '') {
                $('#campaign_err').html('');
                if (confirm('Send this message to selected users?')) {
                    var url = "/lms/custom/promotion/add_new_campaign2.php";
                    $.post(url, {text: text, users: users}).done(function (data) {
                        $("[data-dismiss=modal]").trigger({type: "click"});
                        $('#camp_err').html(data);
                    });
                } // end if
            } // end if
            else {
                $('#campaign_err').html('Please provide campaign text');
            } // end else
        }

        if (event.target.id == 'add_hotel_button') {
            console.log('Clicked ...');
            var state = $('#state').val();
            var city = $('#city').val();
            var addr = $('#addr').val();
            var phone = $('#phone').val();
            var email = $('#email').val();
            var contact = $('#contact').val();
            var charge = $('#charge').val();
            var room = $('#room').val();

            if (email == '') {
                email = 'N/A';
            }

            if (state != '' &&
                    city != '' &&
                    addr != '' &&
                    phone != '' &&
                    email != '' &&
                    contact != '' &&
                    charge != '' && room != '') {
                var hotel = {state: state,
                    city: city,
                    addr: addr,
                    phone: phone,
                    email: email,
                    contact: contact,
                    charge: charge,
                    room: room};

                $('#hotel_err').html('');
                var url = "/lms/custom/hotels/add_new_hotel.php";
                $.post(url, {hotel: JSON.stringify(hotel)}).done(function () {
                    $("[data-dismiss=modal]").trigger({type: "click"});
                    get_hotels_page();
                });
            } // end if
            else {
                $('#hotel_err').html('Please provide all require fields');
            } // end else 
        }

        //console.log('Class element clicked: ' + $(event.target).attr('class'));


        if (event.target.id.indexOf("hotel_edit_") >= 0) {
            var id = event.target.id.replace("hotel_edit_", "");
            if (dialog_loaded !== true) {
                console.log('Script is not yet loaded starting loading ...');
                dialog_loaded = true;
                var js_url = "https://" + domain + "/assets/js/bootstrap.min.js";
                $.getScript(js_url)
                        .done(function () {
                            console.log('Script bootstrap.min.js is loaded ...');
                            var url = "/lms/custom/hotels/get_edit_hotel_dialog.php";
                            var request = {id: id};
                            $.post(url, request).done(function (data) {
                                $("body").append(data);
                                $("#myModal").modal('show');

                                $.post('/lms/custom/utils/states.json', {id: 1}, function (data) {
                                    $('#state').typeahead({source: data, items: 240});
                                }, 'json');

                                $.post('/lms/custom/utils/cities.json', {id: 1}, function (data) {
                                    $('#city').typeahead({source: data, items: 52000});
                                }, 'json');
                            });
                        })
                        .fail(function () {
                            console.log('Failed to load bootstrap.min.js');
                        });
            } // dialog_loaded!=true
            else {
                console.log('Script already loaded');
                $("body").append(data);
                $("#myModal").modal('show');
            }
        }

        if (event.target.id == 'search_instuctor') {
            var state = $('#instructor_state').val();
            var city = $('#instructor_city').val();
            if (state != '' || city != '') {
                $('#ajax_loader').show();
                var location = {state: state, city: city};
                var url = "/lms/custom/instructors/search_item.php";
                $.post(url, {item: JSON.stringify(location)}).done(function (data) {
                    $('#ajax_loader').hide();
                    $('#inst_container').html(data);
                    $('#pagination').hide();
                });
            } // end if state!='' || city!=''
        }

        if (event.target.id == 'reset_instuctor') {
            get_instructors_page();
        }


        if (event.target.id == 'update_hotel_button') {
            var id = $('#id').val();
            var state = $('#state').val();
            var city = $('#city').val();
            var addr = $('#addr').val();
            var phone = $('#phone').val();
            var email = $('#email').val();
            var contact = $('#contact').val();
            var charge = $('#charge').val();
            var room = $('#room').val();

            if (email == '') {
                email = 'N/A';
            }

            if (state != '' &&
                    city != '' &&
                    addr != '' &&
                    phone != '' &&
                    email != '' &&
                    contact != '' &&
                    charge != '' && room != '') {
                var hotel = {state: state,
                    city: city,
                    id: id,
                    addr: addr,
                    phone: phone,
                    email: email,
                    contact: contact,
                    charge: charge,
                    room: room};

                $('#hotel_err').html('');
                var url = "/lms/custom/hotels/update_hotel.php";
                $.post(url, {hotel: JSON.stringify(hotel)}).done(function () {
                    $("[data-dismiss=modal]").trigger({type: "click"});
                    get_hotels_page();
                });
            } // end if
            else {
                $('#hotel_err').html('Please provide all require fields');
            } // end else 

        }

        if (event.target.id.indexOf("hotel_del_") >= 0) {
            var id = event.target.id.replace("hotel_del_", "");
            if (confirm('Delete current hotel?')) {
                var url = "/lms/custom/hotels/del_hotel.php";
                $.post(url, {id: id}).done(function () {
                    $("[data-dismiss=modal]").trigger({type: "click"});
                    get_hotels_page();
                });
            }
        }

        if (event.target.id == 'hotel_search') {
            var state = $('#search_state').val();
            var city = $('#search_city').val();
            if (state != '' || city != '') {
                $('#ajax_loader').show();
                var url = "/lms/custom/hotels/search_hotel.php";
                $.post(url, {state: state, city: city}).done(function (data) {
                    $('#ajax_loader').hide();
                    $('#pagination').hide();
                    $('#hotels_container').html(data);
                });
            } // end if
        }

        if (event.target.id == 'reset_search_hotel') {
            get_hotels_page();
        }

        if (event.target.id == 'inventory_add_hotel_button') {
            if (dialog_loaded !== true) {
                console.log('Script is not yet loaded starting loading ...');
                dialog_loaded = true;
                var js_url = "https://" + domain + "/assets/js/bootstrap.min.js";
                $.getScript(js_url)
                        .done(function () {
                            console.log('Script bootstrap.min.js is loaded ...');
                            var url = "/lms/custom/inventory/get_add_hotel_dialog.php";
                            var request = {id: id};
                            $.post(url, request).done(function (data) {
                                $("body").append(data);
                                $("#myModal").modal('show');

                                $.post('/lms/custom/utils/hotels.json', {id: 1}, function (data) {
                                    $('#inventory_hotels_list').typeahead({source: data, items: 2400});
                                }, 'json');

                                $('#pdate').datepicker();

                            });
                        })
                        .fail(function () {
                            console.log('Failed to load bootstrap.min.js');
                        });
            } // dialog_loaded!=true
            else {
                console.log('Script already loaded');
                $("body").append(data);
                $("#myModal").modal('show');
            }
        }

        if (event.target.id == 'inventory_add_b_button') {
            if (dialog_loaded !== true) {
                console.log('Script is not yet loaded starting loading ...');
                dialog_loaded = true;
                var js_url = "https://" + domain + "/assets/js/bootstrap.min.js";
                $.getScript(js_url)
                        .done(function () {
                            console.log('Script bootstrap.min.js is loaded ...');
                            var url = "/lms/custom/inventory/get_add_book_dialog.php";
                            var request = {id: id};
                            $.post(url, request).done(function (data) {
                                $("body").append(data);
                                $("#myModal").modal('show');

                                $.post('/lms/custom/utils/courses.json', {id: 1}, function (data) {
                                    $('#courses_list').typeahead({source: data, items: 2400});
                                }, 'json');

                                $('#pdate').datepicker();

                            });
                        })
                        .fail(function () {
                            console.log('Failed to load bootstrap.min.js');
                        });
            } // dialog_loaded!=true
            else {
                console.log('Script already loaded');
                $("body").append(data);
                $("#myModal").modal('show');
            }
        }


        if (event.target.id == 'add_hotel_to_inventory') {
            var hotel = $('#inventory_hotels_list').val();
            var amount = $('#amount').val();
            var pdate = $('#pdate').val();

            if (hotel != '' && amount != '' && pdate != '') {
                $('#inv_err').html('');
                var hotelObj = {hotel: hotel, amount: amount, pdate: pdate};
                var url = "/lms/custom/inventory/add_hotel.php";
                $.post(url, {hotel: JSON.stringify(hotelObj)}).done(function () {
                    $("[data-dismiss=modal]").trigger({type: "click"});
                    get_hotel_expenses_page();
                });
            } // end if
            else {
                $('#inv_err').html('Please provide all required fields');
            } // end else
        }

        console.log('Event ID: ' + event.target.id);

        if (event.target.id.indexOf("instructor_dialog") >= 0) {
            var userid = event.target.id.replace("instructor_dialog_", "");
            if (dialog_loaded !== true) {
                console.log('Script is not yet loaded starting loading ...');
                dialog_loaded = true;
                var js_url = "https://" + domain + "/assets/js/bootstrap.min.js";
                $.getScript(js_url)
                        .done(function () {
                            console.log('Script bootstrap.min.js is loaded ...');
                            var url = "/lms/custom/instructors/get_settings_dialog.php";
                            var request = {userid: userid};
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
                $("body").append(data);
                $("#myModal").modal('show');
            }
        }


        if (event.target.id.indexOf("inv_hotel2_edit_") >= 0) {

            if (dialog_loaded !== true) {
                console.log('Script is not yet loaded starting loading ...');
                dialog_loaded = true;
                var js_url = "https://" + domain + "/assets/js/bootstrap.min.js";
                $.getScript(js_url)
                        .done(function () {
                            console.log('Script bootstrap.min.js is loaded ...');
                            var url = "/lms/custom/inventory/get_edit_hotel_dialog.php";
                            var request = {id: id};
                            $.post(url, request).done(function (data) {
                                $("body").append(data);
                                $("#myModal").modal('show');

                                $.post('/lms/custom/utils/hotels.json', {id: 1}, function (data) {
                                    $('#inventory_hotels_list').typeahead({source: data, items: 2400});
                                }, 'json');

                                $('#pdate').datepicker();

                            });
                        })
                        .fail(function () {
                            console.log('Failed to load bootstrap.min.js');
                        });
            } // dialog_loaded!=true
            else {
                console.log('Script already loaded');
                $("body").append(data);
                $("#myModal").modal('show');
            }
        }

        if (event.target.id.indexOf("inv_book_edit_") >= 0) {
            var id = event.target.id.replace("inv_book_edit_", "");
            if (dialog_loaded !== true) {
                console.log('Script is not yet loaded starting loading ...');
                dialog_loaded = true;
                var js_url = "https://" + domain + "/assets/js/bootstrap.min.js";
                $.getScript(js_url)
                        .done(function () {
                            console.log('Script bootstrap.min.js is loaded ...');
                            var url = "/lms/custom/inventory/get_edit_book_dialog.php";
                            var request = {id: id};
                            $.post(url, request).done(function (data) {
                                $("body").append(data);
                                $("#myModal").modal('show');

                                $.post('/lms/custom/utils/courses.json', {id: 1}, function (data) {
                                    $('#courses_list').typeahead({source: data, items: 2400});
                                }, 'json');

                                $.post('/lms/custom/utils/data.json', {id: 1}, function (data) {
                                    $('#student').typeahead({source: data, items: 240000});
                                }, 'json');

                                $('#pdate').datepicker();

                            });
                        })
                        .fail(function () {
                            console.log('Failed to load bootstrap.min.js');
                        });
            } // dialog_loaded!=true
            else {
                console.log('Script already loaded');
                $("body").append(data);
                $("#myModal").modal('show');
            }
        }

        if (event.target.id == 'update_hotel_inventory') {
            var hotel = $('#inventory_hotels_list').val();
            var amount = $('#amount').val();
            var pdate = $('#pdate').val();
            var id = $('#id').val();

            if (hotel != '' && amount != '' && pdate != '') {
                $('#inv_err').html('');
                var hotelObj = {hotel: hotel, amount: amount, pdate: pdate, id: id};
                var url = "/lms/custom/inventory/update_hotel.php";
                $.post(url, {hotel: JSON.stringify(hotelObj)}).done(function () {
                    $("[data-dismiss=modal]").trigger({type: "click"});
                    get_inventory_page();
                    $("a[data-toggle='tab']").trigger({type: "click"});
                });
            } // end if
            else {
                $('#inv_err').html('Please provide all required fields');
            } // end else
        }

        if (event.target.id == 'update_book_to_inventory') {
            var coursename = $('#courses_list').val();
            var pub = $('#pub').val();
            var title = $('#title').val();
            var cost = $('#cost').val();
            var pdate = $('#pdate').val();
            var student = $('#student').val();
            var id = $('#id').val();
            if (coursename != '' && pub != '' && title != '' && cost != '' && pdate != '') {
                $('#inv_err').html('');
                var book = {
                    coursename: coursename,
                    pub: pub,
                    title: title,
                    cost: cost,
                    pdate: pdate,
                    student: student,
                    id: id
                };
                var url = "/lms/custom/inventory/update_book.php";
                $.post(url, {book: JSON.stringify(book)}).done(function () {
                    $("[data-dismiss=modal]").trigger({type: "click"});
                    get_inventory_page();
                });
            } // end if
            else {
                $('#inv_err').html('Please provide all required fields');
            } // end else
        }

        if (event.target.id.indexOf("inv_hotel2_del_") >= 0) {
            var id = event.target.id.replace("inv_hotel2_del_", "");
            if (confirm('Delete current hotel payment?')) {
                var url = "/lms/custom/inventory/del_hotel.php";
                $.post(url, {id: id}).done(function () {
                    get_hotel_expenses_page()
                });
            }
        }

        if (event.target.id.indexOf("inv_book_del_") >= 0) {
            var id = event.target.id.replace("inv_book_del_", "");
            if (confirm('Delete current book?')) {
                var url = "/lms/custom/inventory/del_book.php";
                $.post(url, {id: id}).done(function () {
                    get_inventory_page();
                });
            }
        }

        if (event.target.id == 'inventory_hotel_search_button') {
            var hotel = $('#hotel_search').val();
            var start = $('#h_start').val();
            var end = $('#h_end').val();

            if (hotel != '' || start != '' || end != '') {
                $('#hotel_ajax_loader').show();
                var hotelObj = {addr: hotel, start: start, end: end};
                var url = "/lms/custom/inventory/search_hotel_items.php";
                $.post(url, {hotel: JSON.stringify(hotelObj)}).done(function (data) {
                    $('#hotel_ajax_loader').hide();
                    $('#h_pagination').hide();
                    $('#inventory_hotels_container').html(data);
                });
            }
        }

        if (event.target.id == 'inventory_b_search_button') {
            var title = $('#b_search').val();
            var start = $('#b_start').val();
            var end = $('#b_end').val();

            if (title != '' || start != '' || end != '') {
                $('#book_ajax_loader').show();
                var bookObj = {title: title, start: start, end: end};
                var url = "/lms/custom/inventory/search_book_items.php";
                $.post(url, {book: JSON.stringify(bookObj)}).done(function (data) {
                    $('#book_ajax_loader').hide();
                    $('#b_pagination').hide();
                    $('#b_container').html(data);
                });
            }
        }

        if (event.target.id == 'inventory_b_cancel_search_button') {
            get_inventory_page();
        }

        if (event.target.id == 'inventory_hotel_cancel_search_button') {
            get_hotel_expenses_page();
        }

        if (event.target.id == 'add_book_to_inventory') {
            var coursename = $('#courses_list').val();
            var pub = $('#pub').val();
            var title = $('#title').val();
            var cost = $('#cost').val();
            var pdate = $('#pdate').val();
            var total = $('#total_books').val();

            if (coursename != '' && pub != '' && title != '' && cost != '' && pdate != '') {
                $('#inv_err').html('');
                var book = {
                    coursename: coursename,
                    pub: pub,
                    title: title,
                    cost: cost,
                    pdate: pdate,
                    total: total
                };
                var url = "/lms/custom/inventory/add_book.php";
                $.post(url, {book: JSON.stringify(book)}).done(function () {
                    $("[data-dismiss=modal]").trigger({type: "click"});
                    get_inventory_page();
                });

            } // end if
            else {
                $('#inv_err').html('Please provide all required fields');
            } // end else
        }

        if (event.target.id == 'search_group_button') {
            var search = $('#group_search_text').val();
            if (search != '') {
                $('#ajax_loader').show();
                var url = "/lms/custom/usrgroups/search_group_item.php";
                $.post(url, {item: search}).done(function (data) {
                    $('#ajax_loader').hide();
                    $('#groups_container').html(data);
                    $('#pagination').hide();
                });
            }
        }

        if (event.target.id == 'clear_search_group_button') {
            get_groups_page();
        }

        if (event.target.id == 'add_deposit_btn') {
            if (dialog_loaded !== true) {
                console.log('Script is not yet loaded starting loading ...');
                dialog_loaded = true;
                var js_url = "https://" + domain + "/assets/js/bootstrap.min.js";
                $.getScript(js_url)
                        .done(function () {
                            console.log('Script bootstrap.min.js is loaded ...');
                            var url = "/lms/custom/deposit/get_add_deposit_dialog.php";
                            var request = {id: id};
                            $.post(url, request).done(function (data) {
                                $("body").append(data);
                                $("#myModal").modal('show');
                                $('#pdate').datepicker();
                            });
                        })
                        .fail(function () {
                            console.log('Failed to load bootstrap.min.js');
                        });
            } // dialog_loaded!=true
            else {
                console.log('Script already loaded');
                $("body").append(data);
                $("#myModal").modal('show');
            }
        }

        if (event.target.id == 'search_dep_btn') {
            var date1 = $('#dep_date1').val();
            var date2 = $('#dep_date2').val();

            if (date1 != '' && date1 != '') {
                $('#ajax_loader').show();
                var url = "/lms/custom/deposit/search_deposit.php";
                var request = {date1: date1, date2: date2};
                $.post(url, {date: JSON.stringify(request)}).done(function (data) {
                    $('#ajax_loader').hide();
                    $('#deposit_container').html(data);
                    $('#pagination').hide();
                });
            }
        }

        if (event.target.id == 'clear_search_btn') {
            get_deposit_page();
        }

        if (event.target.id == 'add_new_deposit_done') {
            var banknum = $('#banknum').val();
            var amount = $('#amount').val();
            var name = $('#name').val();
            var date = $('#pdate').val();
            var userid = $('#userid').val();
            if (amount != '' && date != '') {
                $('#dep_err').html('');
                if (banknum == '') {
                    banknum = 'N/A';
                }
                var dep = {banknum: banknum,
                    amount: amount,
                    name: name,
                    date: date, userid: userid};
                var url = "/lms/custom/deposit/add_deposit.php";
                $.post(url, {dep: JSON.stringify(dep)}).done(function () {
                    $("[data-dismiss=modal]").trigger({type: "click"});
                    get_deposit_page();
                });

            } // end if
            else {
                $('#dep_err').html('Please provide all required fields');
            }

        }

        if (event.target.id == 'camp_reset_search') {
            get_promotion_page();
        }



        if (event.target.id == 'select_all_camp') {
            if ($('#select_all_camp').prop('checked')) {
                $('.camp_users').prop('checked', true);
            } // end if
            else {
                $('.camp_users').prop('checked', false);
            } //else  
        }

        if (event.target.id == 'add_new_campaign_button') {
            var users = [];
            $('input:checkbox.camp_users:checked').each(function () {
                users.push($(this).val());
            }); // end of each function
            if (users.length > 0) {
                var userslist = users.join(',');
                $('#camp_err').html('');
                if (dialog_loaded !== true) {
                    console.log('Script is not yet loaded starting loading ...');
                    dialog_loaded = true;
                    var js_url = "https://" + domain + "/assets/js/bootstrap.min.js";
                    $.getScript(js_url)
                            .done(function () {
                                console.log('Script bootstrap.min.js is loaded ...');
                                var url = "/lms/custom/promotion/get_add_campaign_dialog.php";
                                var request = {userslist: userslist};
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
                    $("body").append(data);
                    $("#myModal").modal('show');
                }
            } // end if users.length>0
            else {
                $('#camp_err').html('Please select users');
            } // end else

        }

        if (event.target.id == 'camp_search') {
            var coursename = $('#camp_program').val();
            var state = $('#camp_state').val();
            var city = $('#camp_city').val();
            //var workshop = $('#camp_ws').val();


            if (coursename == '') {
                if (state == '' && city != '') {
                    $('#camp_err').html('Please select state');
                    return;
                }

                if (state != '') {
                    $('#camp_err').html('');
                    $('#ajax_loader').show();
                    var search = {state: state, city: city, workshop: '', coursename: coursename};
                    var url = "/lms/custom/promotion/search_camp_users.php";
                    $.post(url, {search: JSON.stringify(search)}).done(function (data) {
                        $('#ajax_loader').hide();
                        $('#camp_users_container').html(data);
                    });
                } // end if state!='' || city!='' && workshop!=''
                else {
                    $('#camp_err').html('Please select state or city');
                }
            } // end if coursename==''
            else {
                $('#camp_err').html('');
                $('#ajax_loader').show();
                var search = {state: state, city: city, workshop: '', coursename: coursename};
                var url = "/lms/custom/promotion/search_camp_users.php";
                $.post(url, {search: JSON.stringify(search)}).done(function (data) {
                    $('#ajax_loader').hide();
                    $('#camp_users_container').html(data);
                });
            } // end else
        }

        if (event.target.id == 'update_student_attendance') {
            var courseid = $('#at_courseid').val();
            var userid = $('#at_userid').val();
            var date = $('#at_date').val();
            var status = $('#students_status').val();
            var notes = $('#at_notes').val();
            var at = {courseid: courseid, userid: userid, date: date, status: status, notes: notes};
            if (confirm('Update current student attendance?')) {
                var url = "/lms/custom/my/update_stdudent_attendance.php";
                $.post(url, {at: JSON.stringify(at)}).done(function () {
                    $("[data-dismiss=modal]").trigger({type: "click"});
                    document.location.reload();
                });
            } // end if confirm
        }

        if (event.target.id == 'print_grades') {
            var userid = $('#userid').val();
            var url = "/lms/custom/my/print_grades.php";
            $.post(url, {userid: userid}).done(function (filename) {
                var url2 = "http://medical2.com/lms/custom/grades/" + userid + '/' + filename;
                var oWindow = window.open(url2, "print");
            });
        }

        if (event.target.id == 'print_att') {
            var userid = $('#userid').val();
            var url = "/lms/custom/my/print_att.php";
            $.post(url, {userid: userid}).done(function (filename) {
                var url2 = "http://medical2.com/lms/custom/my/" + filename;
                var oWindow = window.open(url2, "print");
            });
        }

        if (event.target.id == 'print_payment') {
            var userid = $('#userid').val();
            var url = "/lms/custom/my/print_payment.php";
            $.post(url, {userid: userid}).done(function (filename) {
                var url2 = "http://medical2.com/lms/custom/my/" + filename;
                var oWindow = window.open(url2, "print");
            });
        }



    }); // end of body click event

    $('body').on('typeahead:select', function (event, suggestion) {
        console.log('Selected item: ' + suggestion);

    });

    $('body').on('blur', "input#coursename", function () {

        var coursename = $('#coursename').val();
        if (coursename != '') {
            var url = "/lms/custom/my/get_program_ws.php";
            $.post(url, {coursename: coursename}).done(function (res) {
                if (res > 0) {
                    $.get('/lms/custom/utils/slots.json', function (data) {
                        $('#wsname').typeahead({source: data, items: 240});
                    }, 'json');
                } // end if res>0
            });
        } // end if coursename!=''
    }); // end of $('body').on('blur',



}); // end of $(document).ready(function()

