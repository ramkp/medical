<?php
require_once './classes/Groups.php';
$groups = new Groups();
$total=$groups->get_total_group_items();
$list = $groups->get_requests_list();
echo $list;
?>

<script type="text/javascript">

    $(document).ready(function () {

        $(function () {
            $('#pagination').pagination({
                items: <?php echo $total; ?>,
                itemsOnPage: <?php echo $groups->limit; ?>,
                cssStyle: 'light-theme'
            });
        });

        $("#pagination").click(function () {
            var page = $('#pagination').pagination('getCurrentPage');
            console.log('Page: ' + page);
            var url = "/lms/custom/groups/get_group_item.php";
            $.post(url, {id: page}).done(function (data) {
                $('#group_items').html(data);
            });
        });

    });

</script>
