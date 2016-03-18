<?php
require_once './classes/Invoice.php';
$invoice = new Invoices();
$total = $invoice->get_open_invoices_total();
$list = $invoice->get_open_invoices();
echo $list;
?>

<script type="text/javascript">

    $(document).ready(function () {

        $(function () {
            $('#pagination').pagination({
                items: <?php echo $total; ?>,
                itemsOnPage: 1,
                cssStyle: 'light-theme'
            });
        });

        $("#pagination").click(function () {
            var page = $('#pagination').pagination('getCurrentPage');
            console.log('Page: ' + page);
            var url = "/lms/custom/invoices/get_open_invoice_item.php";
            $.post(url, {id: page}).done(function (data) {
                $('#open_invoices_container').html(data);
            });
        });

    });

</script>
