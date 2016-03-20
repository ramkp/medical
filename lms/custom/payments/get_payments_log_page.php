<?php
require_once './classes/Payments.php';
$payments = new Payments();
$total = $payments->get_total_log_entries();
$list = $payments->get_payment_log_page();
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
            var url = "/lms/custom/payments/get_payment_log_item.php";
            $.post(url, {id: page}).done(function (data) {
                $('#payment_log_container').html(data);
            });
        });

    });

</script>
