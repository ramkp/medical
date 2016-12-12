<?php
echo $data;
?>

<script type="text/javascript">

    $(document).ready(function () {

        var url = "http://medical2.com/index.php/register2/get_campus_data";
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
                    label: m.name,
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
        }); // end if post

    }); //end of document ready

</script>


