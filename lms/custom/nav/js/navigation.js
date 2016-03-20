
$(document).ready(function () {
    console.log("ready!");
    var courseid;
    function update_navigation_status__menu(item_title) {
        $(".breadcrumb-nav").html('');
        $(".breadcrumb-nav").html("<ul class='breadcrumb'><li><a href='http://cnausa.com/lms/my/'>Dashboard</a> <span class='divider'> <span class='accesshide '><span class='arrow_text'>/</span>&nbsp;</span><span class='arrow sep'>►</span> </span></li><li><a href='#'>" + item_title + "</a></li>");
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
        if (file_data == '' || file_data.length == 0) {
            $('#gallery_err').html('Please select files to be upload ...');
        }
        else {
            console.log('File data: ' + file_data);
            $('#gallery_err').html('');
            var form_data = new FormData();
            $.each(file_data, function (key, value) {
                form_data.append(key, value);
            });
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
        }
    }

    function refresh_gallery_thumbs() {
        var url = "/lms/custom/gallery/refresh.php";
        $.post(url, {id: 1}).done(function (data) {
            $('#thumb_list').html(data);
        });
    }

    function delete_gallery_img() {
        console.log('Inside delete_gallery_img ...');
        $('#gallery_err').html('');
        var items = new Array();
        //$( "input:checked" ).val()
        $("input:checked").each(function () {
            items.push($(this).val());
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
        }
        else {
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
        }
        else {
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
        var taxes;

        if ($(taxes_num).is(':checked')) {
            taxes = 1;
        }
        else {
            taxes = 0;
        }

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
        });
    }

    function get_paid_invoice_page() {
        var url = "/lms/custom/invoices/paid_invoices.php";
        $.post(url, {id: 1}).done(function (data) {
            $('#region-main').html(data);
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
    }

    function get_check_payments_page() {
        var url = "/lms/custom/payments/get_cheque_payments.php";
        $.post(url, {id: 1}).done(function (data) {
            $('#region-main').html(data);
        });
    }

    function get_free_payments() {
        var url = "/lms/custom/payments/get_free_payments.php";
        $.post(url, {id: 1}).done(function (data) {
            $('#region-main').html(data);
        });
    }

    function make_invoice_paid(id) {
        var status_id = '#invoice_status_' + id;
        var payment_type_id='#payment_type_'+id;
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
        var courseid = $('#courses').val();
        var userid = $('#users').val();
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

    function show_private_group_request_detailes(id) {
        var container_id = '#det_' + id;
        var status = $(container_id).is(":visible");
        console.log(status);
        if (status == false) {
            $(container_id).show();
        }
        else {
            $(container_id).hide();
        }
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
            var data = CKEDITOR.instances.editor1.getData();
            update_faq_page(data);
        }

        if (event.target.id.indexOf("_test") >= 0) {
            var data = CKEDITOR.instances.editor1.getData();
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

        if (event.target.id == 'send_cert') {
            send_certicicate_to_user();
        }

        if (event.target.id == 'send_invoice') {
            send_invoice();
        }

        if (event.target.id == 'add_installment_user') {
            add_installment_user();
        }



    }); // end of #region-main click', 'button',

    $('#region-main').on('click', 'a', function (event) {
        if (event.target.id.indexOf("group_") >= 0) {
            var id = event.target.id.replace("group_", "");
            show_private_group_request_detailes(id);
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





    }); // end of $('#region-main').on('click', 'a'

    $(document).on('change', '[type=checkbox]', function (event) {
        console.log('Event id: ' + event.target.id);
        var courseid = event.target.id.replace('installment_', '');
        var installment_el = '#installment_' + courseid;
        console.log('Installment: ' + installment_el);
        var num_payments_el = '#num_payments_' + courseid;
        console.log('Num payments: ' + num_payments_el);
        var installment_status = $(installment_el).is(':checked');
        console.log('Installment status: ' + installment_status);
        if (installment_status == true) {
            $(num_payments_el).prop("disabled", false);
        }
        else {
            $(num_payments_el).prop("disabled", true);
        }

    });

    $('#region-main').on('change', 'select', function (event) {
        console.log(event.target.id);
        if (event.target.id == 'course_categories') {
            var id = $('#course_categories').val();
            get_category_courses(id);
        }

        if (event.target.id == 'courses') {
            var id = $('#courses').val();
            get_course_users(id);
        }

        if (event.target.id == 'users') {
            //var page = document.cookie;
            //console.log('Page cookie: ' + page);
            //if (page == 'installment_users') {
            $('#installment_params').show();
            //}
        }



    }); // end of $('#region-main').on('change', 'select',

    /************************************************************************
     * 
     *                   Menu processing items
     * 
     ************************************************************************/

    // Show price items
    $("#prices").click(function (event) {
        get_price_items_from_category(event.target.id);
    });

    $("#FAQ").click(function (event) {
        update_navigation_status__menu('FAQ');
        get_faq_edit_page();
    });

    $("#Google_Map").click(function (event) {
        update_navigation_status__menu('Google Map');
        get_google_map_page();
    });

    $("#Certificates").click(function (event) {
        update_navigation_status__menu('Certificates');
        get_certificates_page();
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

    });

    $("#free").click(function (event) {
        update_navigation_status__menu('Free');
        get_free_payments();
    });

    $("#refund").click(function (event) {
        update_navigation_status__menu('Refund');

    });

}); // end of $(document).ready(function()

