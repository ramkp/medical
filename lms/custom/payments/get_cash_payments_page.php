<?php

require_once './classes/Payments.php';
$payments = new Payments(1);
$payments_type = 1;
$list = $payments->get_invoice_payments($payments_type);
$total=$payments->get_total_payments();
echo $list;
?>

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
            var url = "/lms/custom/payments/get_payment_item.php";
            $.post(url, {id: page, payments_type: <?php echo $payments_type; ?> }).done(function (data) {
                $('#payment_container').html(data);
            });
        });

    });

</script>

