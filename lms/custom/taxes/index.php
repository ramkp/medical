<?php
require_once './classes/Taxes.php';
$taxes = new Taxes();
$list = $taxes->get_state_taxes_list();
echo $list;
?>

<script type="text/javascript">

    $(document).ready(function () {

        $(function () {
            $('#pagination').pagination({
                items: 51,
                itemsOnPage: <?php echo $taxes->limit; ?>,
                cssStyle: 'light-theme'
            });
        });

        $("#pagination").click(function () {
            var page = $('#pagination').pagination('getCurrentPage');
            console.log('Page: ' + page);
            var url = "/lms/custom/taxes/get_tax_item.php";
            $.post(url, {id: page}).done(function (data) {
                $('#state_taxes').html(data);
            });
        });

    });

</script>

