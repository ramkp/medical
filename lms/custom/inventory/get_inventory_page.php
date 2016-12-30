<?php
require_once './classes/Inventory.php';
$in = new Inventory();
$list = $in->get_inventory_page();
$btotal = $in->get_books_hotels();
echo $list;
?>

<script type="text/javascript">

    $(document).ready(function () {

        $(function () {
            $('#b_pagination').pagination({
                items: <?php echo $btotal; ?>,
                itemsOnPage: <?php echo $in->limit; ?>,
                cssStyle: 'light-theme'
            });
        });

        $("#b_pagination").click(function () {
            var page = $('#b_pagination').pagination('getCurrentPage');
            console.log('Page: ' + page);
            var url = "/lms/custom/inventory/get_book_item.php";
            $.post(url, {id: page}).done(function (data) {
                $('#b_container').html(data);
            });
        });

    });

</script>

