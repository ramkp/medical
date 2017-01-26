<?php
require_once './classes/Deposit.php';
$d = new Deposit();
$total = $d->get_deposit_total();
$list = $d->get_deposit_page();
echo $list;
?>

<script type="text/javascript">

    $(document).ready(function () {

        $(function () {
            $('#pagination').pagination({
                items: <?php echo $total; ?>,
                itemsOnPage: <?php echo $d->limit; ?>,
                cssStyle: 'light-theme'
            });
        });

        $("#pagination").click(function () {
            var page = $('#pagination').pagination('getCurrentPage');
            console.log('Page: ' + page);
            var url = "/lms/custom/deposit/get_deposit_item.php";
            $.post(url, {id: page}).done(function (data) {
                $('#deposit_container').html(data);
            });
        });

    });

</script>
