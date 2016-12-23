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

</script>



