<?php
require_once './classes/Partial.php';
$partial = new Partial();
$total = $partial->get_partial_payments_total();
echo "Total: ".$total."<br>";
$list = $partial->get_partial_payments_list();
echo $list;
?>


<script type="text/javascript">

    $(document).ready(function () {

        $(function () {
            $('#pagination').pagination({
                items: <?php echo $total; ?>,
                itemsOnPage: <?php echo $partial->limit; ?>,
                cssStyle: 'light-theme'
            });
        });

        $("#pagination").click(function () {
            var page = $('#pagination').pagination('getCurrentPage');
            console.log('Page: ' + page);
            var url = "/lms/custom/partial/get_partial_item.php";
            $.post(url, {id: page}).done(function (data) {
                $('#partial_container').html(data);
            });
        });

    });

</script>

