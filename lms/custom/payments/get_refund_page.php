<?php

require_once './classes/Payments.php';
$payments = new Payments(0);
$total = $payments->get_total_refund_payments();
$list = $payments->get_refund_page();
echo $list;

?>

<!--
<script type="text/javascript">

    $(document).ready(function () {

        $(function () {
            $('#pagination').pagination({
                items: <?php echo $total; ?>,
                itemsOnPage: <?php echo $payments->limit; ?>,
                cssStyle: 'light-theme'
            });
        });

        $("#pagination").click(function () {
            var page = $('#pagination').pagination('getCurrentPage');
            console.log('Page: ' + page);
            var url = "/lms/custom/payments/get_refund_item.php";
            $.post(url, {id: page}).done(function (data) {
                $('#card_payments_container').html(data);
            });
        });
    });

</script>
-->

