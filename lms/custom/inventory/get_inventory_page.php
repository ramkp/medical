<?php

require_once './classes/Inventory.php';
$in = new Inventory();
$list = $in->get_inventory_page();
$total=$in->get_total_hotels();
echo $list;
?>

<script type="text/javascript">

    $(document).ready(function () {

        $(function () {
            $('#h_pagination').pagination({
                items: <?php echo $total; ?>,
                itemsOnPage: <?php echo $in->limit; ?>,
                cssStyle: 'light-theme'
            });
        });

        $("#h_pagination").click(function () {
            var page = $('#h_pagination').pagination('getCurrentPage');
            console.log('Page: ' + page);
            var url = "/lms/custom/inventory/get_hotel_item.php";
            $.post(url, {id: page}).done(function (data) {
                $('#inventory_hotels_container').html(data);
            });
        });

    });

</script>

