<?php
require_once './classes/Hotel.php';
$h = new Hotel();
$list = $h->get_hotels_page();
$total = $h->get_hotels_total();
echo $list;
?>

<script type="text/javascript">

    $(document).ready(function () {

        $(function () {
            $('#pagination').pagination({
                items: <?php echo $total; ?>,
                itemsOnPage: <?php echo $h->limit; ?>,
                cssStyle: 'light-theme'
            });
        });

        $("#pagination").click(function () {
            var page = $('#pagination').pagination('getCurrentPage');
            console.log('Page: ' + page);
            var url = "/lms/custom/hotels/get_hotel_item.php";
            $.post(url, {id: page}).done(function (data) {
                $('#hotels_container').html(data);
            });
        });

    });

    $.post('/lms/custom/utils/states.json', {id: 1}, function (data) {
        $('#search_state').typeahead({source: data, items: 240});
    }, 'json');

    $.post('/lms/custom/utils/cities.json', {id: 1}, function (data) {
        $('#search_city').typeahead({source: data, items: 240});
    }, 'json');


</script>



