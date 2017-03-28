<?php
require_once './classes/Codes.php';
$c = new Codes();
$list = $c->get_promotion_codes_page();
$total = $c->get_total_codes();
echo $list;
?>

<script type="text/javascript">

    $(document).ready(function () {
        $(function () {
            $('#pagination').pagination({
                items: <?php echo $total; ?>,
                itemsOnPage: <?php echo $c->limit; ?>,
                cssStyle: 'light-theme'
            });
        });

        $("#pagination").click(function () {
            var page = $('#pagination').pagination('getCurrentPage');
            var url = "/lms/custom/codes/get_code_item.php";
            $.post(url, {id: page}).done(function (data) {
                $('#codes_container').html(data);
            });
        });
    });

</script>