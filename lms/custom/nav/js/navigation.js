
$(document).ready(function () {

    var domain = 'medical2.com';
    var dialog_loaded;
    console.log("ready!");


    function update_navigation_status__menu(item_title) {
        $(".breadcrumb-nav").html('');
        $(".breadcrumb-nav").html("<ul class='breadcrumb'><li><a href='https://" + domain + "/lms/my/'>Dashboard</a> <span class='divider'> <span class='accesshide '><span class='arrow_text'>/</span>&nbsp;</span><span class='arrow sep'>►</span> </span></li><li><a href='#'>" + item_title + "</a></li>");
    }

    function get_price_items_from_category(id) {
        var url = "/lms/custom/prices/list.php";
        $.post(url, {id: id}).done(function (data) {
            console.log(data);
            var price_obj = $.parseJSON(data);
            update_navigation_status__menu(price_obj.item_title);
            $('#region-main').html(price_obj.item_data);
        });
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
//console.log('File data: ' + file_data);
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
        var userid = $('#users').val();
        var slotid = $('#slots').val();
        console.log('User ID: ' + userid);
        console.log('Slot ID:' + slotid);
        if (userid > 0 && slotid > 0) {
            var url = "/lms/custom/schedule/add_user_to_slot.php";
            $.post(url, {slotid: slotid, userid: userid}).done(function (data) {
                console.log('Server response: ' + data);
                document.location.reload();
            });
        } // end if userid>0 && slotid>0
        else {
            alert('Please sleect student and workshop!');
        }
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
        })

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

            $.get('/lms/custom/utils/data.json', function (data) {
                $("#search_certificate").typeahead({source: data, items: 24});
            }, 'json');


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
            $('#region-main').html(data);
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
        var taxes; // checkbox status
        var expire_num = '#expire_' + courseid;
        var expire // checkbox status

        if ($(taxes_num).is(':checked')) {
            taxes = 1;
        } else {
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

//var states_ident = $(states_id + ':selected');       
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
// Prepare and send AJAX request ...
                $('#price_err').html('');
                var url = "/lms/custom/prices/edit.php";
                var request = {
                    course_id: courseid,
                    course_cost: course_cost,
                    course_discount: course_discount,
                    course_group_discount: course_group_discount,
                    installment: installment,
                    num_payments: num_payments,
                    taxes: taxes,
                    expire: expire,
                    states: JSON.stringify(states)};
                $.post(url, request).done(function (data) {
//alert ('Server response: '+data);
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
        });
    }

    function get_course_users2(id) {
        console.log('Course ID: ' + id);
        var url = "/lms/custom/certificates/get_course_users2.php";
        $.post(url, {id: id}).done(function (data) {
            $('#send_enrolled_users').html(data);
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
        var userid = $('#users').val();
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
    }

    function get_open_invoices_page() {
        var url = "/lms/custom/invoices/open_invoices.php";
        $.post(url, {id: 1}).done(function (data) {
            $('#region-main').html(data);

            $.get('/lms/custom/utils/data.json', function (data) {
                $("#search_invoice_input").typeahead({source: data, items: 24});
            }, 'json');

        });
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
        var userid = $('#users').val();
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
    }

    function send_certicicate_to_user() {
        var courseid = $('#send_courses').val();
        var userid = $('#send_users').val();
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
            console.log('Incorrect data!');
            $('#send_cert_err').html("<span style='color:red;'>Please select program and user</span>");
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

    function renew_certificate() {
        var url = "/lms/custom/nav/renew_certificate.php";
        $.post(url, {id: 1}).done(function (data) {
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
        var userid = $('#users').val();
        var sum = $('#sum').val();
        var slotid = $('#register_cities').val();
        var ptype = $('input[name=payment_type]:checked').val();
        console.log('Course ID: ' + courseid);
        console.log('User ID: ' + userid);
        console.log('slot ID: ' + slotid);
        console.log('Sum : ' + sum);
        console.log('Ptype: ' + ptype);
        if (courseid > 0 && userid > 0 && $.isNumeric(sum) && sum > 0) {
            $('#partial_err').html('');
            if (ptype == 'cc') {
                var url = "https://medical2.com/index.php/payments/index/" + userid + "/" + courseid + "/" + slotid + "/" + sum;
                window.open(url, '_blank');
            } // end if ptype=='cc'
            else {
                if (confirm('Add partial payment for current user?')) {
                    var url = "/lms/custom/partial/add_partial_payment.php";
                    $.post(url, {courseid: courseid, userid: userid, sum: sum, source: ptype, slotid: slotid}).done(function (data) {
                        $('#partial_err').html("<span style='color:black;'>" + data + "</span>");
                    });
                } // end if confirm
            } // end else when it is not cc payment
        } // end if courseid > 0 && userid > 0 && sum > 0
        else {
            $('#partial_err').html('Please select program and user and provide paid amount');
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
            console.log('Course ID: ' + courseid);
            console.log('Students: ' + students);
            if (confirm('Change selected students course status to passed?')) {
                $('#ajax_loading').show();
                var url = "/lms/custom/schedule/compete_students.php";
                $.post(url, {courseid: courseid, students: students}).done(function () {
                    $('#ajax_loading').hide();
                    document.location.reload();
                });
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

    /**********************************************************************
     * 
     *                       Events processing block
     * 
     ***********************************************************************/

// Main region events processing function
    $('#region-main').on('click', 'button', function (event) {
        console.log("Item clicked: " + event.target.id);
        // Save price item
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

        if (event.target.id == 'invoice_data') {
            update_invoice_data();
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


        if (event.target.id == 'create_cert_button') {
            var courseid = $('#courses').val();
            var userid = $('#users').val();
            var s_m = $('#s_m').val();
            var s_d = $('#s_d').val();
            var s_y = $('#s_y').val();
            var e_m = $('#e_m').val();
            var e_d = $('#e_d').val();
            var e_y = $('#e_y').val();
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
        console.log(event.target.id);

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
            get_course_users(id);
            get_course_promotion_users(id);
            get_course_workshops(id);
        }

        if (event.target.id == 'send_courses') {
            var id = $('#send_courses').val();
            get_course_users2(id);

        }

        if (event.target.id == 'workshops') {
            var id = $('#workshops').val();
            get_workshop_users(id);
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
        });

        $.get('/lms/custom/utils/data.json', function (data) {
            $('#search_user_input').typeahead({source: data, items: 24});
        }, 'json');
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
//console.log(data);
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

    function get_refund_page() {
        //console.log('It is refund page ...');
        var url = "/lms/custom/payments/get_refund_page.php";
        $.post(url, {id: 1}).done(function (data) {
            $('#region-main').html(data);
        });
    }

    /************************************************************************
     * 
     *                   Menu processing items
     * 
     ************************************************************************/

// Show price items
    $("#prices").click(function (event) {
        get_price_items_from_category(event.target.id);
    });

    $("#index").click(function (event) {
        update_navigation_status__menu('Index page');
        get_index_page();
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
    $("#promote").click(function (event) {
        update_navigation_status__menu('Promotions');
        get_promotion_page();
    });

    $("#Testimonial").click(function (event) {
        update_navigation_status__menu('Testimonial');
        get_testimonial_page();
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
    $("#workshop_reports").click(function (event) {
        update_navigation_status__menu('Workshop reports');
        get_workshop_report();
    });
    $("#get_cert").click(function (event) {
        update_navigation_status__menu('Get Certificate');
        get_certificate();
    });
    $("#ren_cert").click(function (event) {
        update_navigation_status__menu('Renew Certificate');
        renew_certificate();
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
        }
        else {
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
            console.log('ID ' + id);
            var js_url = "https://" + domain + "/assets/js/bootstrap.min.js";
            $.getScript(js_url).done(function () {
                var url = "https://" + domain + "/lms/custom/faq/get_faq_edit_page.php";
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

        if (event.target.id == 'cancel_faq_edit') {
            $("#myModal").remove();
            dialog_loaded = false;
        }

        if (event.target.id == 'faq_add') {
            console.log('FAQ add ....');
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
            var url = "/lms/custom/faq/update_faq.php";
            var request = {id: id, q: q, a: a};
            $.post(url, request).done(function (data) {
                update_navigation_status__menu('FAQ');
                var url = "/lms/custom/faq/get_faq_page.php";
                $.post(url, {id: 1}).done(function (data) {
                    $("#myModal").remove();
                    dialog_loaded = false;
                    $('#region-main').html(data);
                });
            });

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
            }
            else {
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
            console.log('Payment ID: ' + paymentid);
            if (paymentid > 0) {
                if (confirm('Make refund for current payment?')) {
                    var url = "/lms/custom/payments/make_refund.php";
                    var request = {paymentid: paymentid};
                    $.post(url, request).done(function (data) {
                        console.log('Server response: ' + data);
                        $("[data-dismiss=modal]").trigger({type: "click"});
                    });
                } // end if confirm
            } // end if paymentid>0
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
                    //console.log('Server response: ' + data);
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


    $('body').on('change', 'select', function (event) {

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

    }); // end of body






}); // end of $(document).ready(function()

