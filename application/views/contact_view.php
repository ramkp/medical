
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<?php

echo $form;

?>

<script type="text/javascript">

    $(document).ready(function () {

        var url = "https://medical2.com/index.php/register2/get_campus_data";
        $.post(url, {id: 1}).done(function (data) {
            var $obj_data = jQuery.parseJSON(data);

            var map = new google.maps.Map(document.getElementById('map'), {
                scrollwheel: false,
                zoom: 8
            }); // end var map            
            var latLngs = [];
            var bounds = new google.maps.LatLngBounds();
            var infowindow = new google.maps.InfoWindow();
            $.each($obj_data, function (i, m) {
                var myLatLng = new google.maps.LatLng(m.lat, m.lon);
                latLngs[i] = myLatLng;
                var marker = new google.maps.Marker({
                    position: myLatLng,
                    map: map,
                    //label: m.name,
                    title: m.name,
                    zIndex: i
                }); // end marker                
                bounds.extend(marker.position);
                google.maps.event.addListener(marker, 'click', (function (marker, i) {
                    return function () {
                        infowindow.setContent(m.campus_desc);
                        infowindow.open(map, marker);
                    }
                })(marker, i));
            }) // end each            
            map.fitBounds(bounds);
            var directionsService = new google.maps.DirectionsService;
            var directionsDisplay = new google.maps.DirectionsRenderer;
            directionsDisplay.setMap(map);

            var onChangeHandler = function () {
                calculateAndDisplayRoute(directionsService, directionsDisplay);
            };
            document.getElementById('get_driver_directions').addEventListener('click', onChangeHandler);

        }); // end if post



        function calculateAndDisplayRoute(directionsService, directionsDisplay) {
            var start = document.getElementById('start').value;
            var end = document.getElementById('end').value;

            if (start != '' && end != '') {
                $('map_err').html('');
                directionsService.route({
                    origin: start,
                    destination: end,
                    travelMode: 'DRIVING'
                }, function (response, status) {
                    if (status === 'OK') {
                        directionsDisplay.setDirections(response);
                    } else {
                        window.alert('Directions request failed due to ' + status);
                    }
                });
            } // end if
            else {
                $('map_err').html('Please provide your location and select destination');
            }
        }

    }); //end of document ready

</script>