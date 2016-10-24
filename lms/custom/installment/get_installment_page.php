<?php
require_once './classes/Installment.php';
$installment = new Installment();
$list = $installment->get_installment_page();
$total = $installment->get_subs_num();
echo $list;
?>

<script type="text/javascript">

    $(document).ready(function () {

        $(function () {
            $('#pagination').pagination({
                items: <?php echo $total; ?>,
                itemsOnPage: <?php echo $installment->limit; ?>,
                cssStyle: 'light-theme'
            });
        });

        $("#pagination").click(function () {
            var page = $('#pagination').pagination('getCurrentPage');
            console.log('Page: ' + page);
            var url = "/lms/custom/installment/get_installment_item.php";
            $.post(url, {id: page}).done(function (data) {
                $('#installment_container').html(data);
            });
        });

    });

</script>
