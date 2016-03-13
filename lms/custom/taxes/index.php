<?php
require_once './classes/Taxes.php';
$taxes = new Taxes();
$list = $taxes->get_state_taxes_list();
echo $list;
?>

<script type="text/javascript">

    $('#pagination-demo').twbsPagination({
        totalPages: 51,
        visiblePages: 2,
        onPageClick: function (event, page) {
            console.log('Event: ' + event);
            var url = "/lms/custom/taxes/get_tax_item.php";
            $.post(url, {id:page}).done(function (data) {
                $('#state_taxes').html(data);
            });

        }
    });


</script>

