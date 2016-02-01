
$(document).ready(function () {
    console.log("ready!");

    function update_navigation_status__menu(item_title) {
        $(".breadcrumb-nav").html('');
        $(".breadcrumb-nav").html("<ul class='breadcrumb'><li><a href='http://cnausa.com/lms/my/'>Dashboard</a> <span class='divider'> <span class='accesshide '><span class='arrow_text'>/</span>&nbsp;</span><span class='arrow sep'>►</span> </span></li><li><a href='#'>" + item_title + "</a></li>");
    }

    function get_price_items_from_category(id) {
        var url = "/lms/custom/prices/list.php";
        $.post(url, {id: id}).done(function (data) {
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











            /*
             var map = new google.maps.Map(document.getElementById('map'), {
             center: new google.maps.LatLng(3.171368,101.653404),
             scrollwheel: false,
             zoom: 8
             }); // end var map
             var marker;
             $.each($obj_data, function (obj) {
             marker = new google.maps.Marker({
             position: new google.maps.LatLng(obj.lat, obj.lng),
             map: map
             }); // end marker
             }); // end each
             */
        }); // post(url, request).done(function (data)
    }

    function get_certificates_page() {

    }

    function validateNum(str) {
        var patForReqdFld = /^(\-)?([\d]+(?:\.\d{1,2})?)$/;
        return patForReqdFld.test(str);
    }

    function update_item_price(id) {
        var courseid = id.replace("price_", "");
        var course_cost_id = '#cost_' + courseid;
        var course_discount_id = '#item_' + courseid;
        var course_group_discount_id = '#group_' + courseid;

        var course_cost = $(course_cost_id).val();
        var course_discount = $(course_discount_id).val();
        var course_group_discount = $(course_group_discount_id).val();
        if (course_cost == '' || course_cost == 0) {
            $('#price_err').html('Please provide item cost');
        }
        else {
            if (validateNum(course_cost)) {
                // Prepare and send AJAX request ...
                $('#price_err').html('');
                var url = "/lms/custom/prices/edit.php";
                var request = {
                    course_id: courseid,
                    course_cost: course_cost,
                    course_discount: course_discount,
                    course_group_discount: course_group_discount};
                $.post(url, request).done(function (data) {
                    $('#region-main').html(data);
                });
            } // end if validateNum(course_cost
            else {
                $('#price_err').html('Invalid item cost');
            }
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

    }); // end of #region-main

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
    });

    $("#Testimonial").click(function (event) {
        update_navigation_status__menu('Testimonial');
        get_testimonial_page();
    });

    $("#Photo_Gallery").click(function (event) {
        update_navigation_status__menu('Photo Gallery');
        get_gallery_index_page();
    });




}); // end of $(document).ready(function()
