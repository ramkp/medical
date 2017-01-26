<?php
require_once './classes/Groups.php';
$g = new Groups();
$list = $g->get_groups_page();
$total = $g->get_total_groups();
echo $list;
?>

<script type="text/javascript">

    $(document).ready(function () {

        $(function () {
            $('#pagination').pagination({
                items: <?php echo $total; ?>,
                itemsOnPage: <?php echo $g->limit; ?>,
                cssStyle: 'light-theme'
            });
        });

        $("#pagination").click(function () {
            var page = $('#pagination').pagination('getCurrentPage');
            console.log('Page: ' + page);
            var url = "/lms/custom/usrgroups/get_group_item.php";
            $.post(url, {id: page}).done(function (data) {
                $('#groups_container').html(data);
            });
        });

    });

</script>