<?php

require_once './classes/Invoice.php';
$invoice=new Invoices();
$list=$invoice->get_paid_invoices();
$total=$invoice->get_paid_invoices_total();
echo $list;

?>

<script type="text/javascript">

    $(document).ready(function () {

        $(function () {
            $('#pagination').pagination({
                items: <?php echo $total; ?>,
                itemsOnPage: <?php echo $invoice->limit; ?>,
                cssStyle: 'light-theme'
            });
        });

        $("#pagination").click(function () {
            var page = $('#pagination').pagination('getCurrentPage');
            console.log('Page: ' + page);
            var url = "/lms/custom/invoices/get_paid_invoice_item.php";
            $.post(url, {id: page}).done(function (data) {
                $('#open_invoices_container').html(data);
            });
        });

    });

</script>
