<?php
require_once './classes/Groups.php';
$groups = new Groups();
$list = $groups->get_requests_list();
echo $list;
?>

<script type="text/javascript">

    $('#pagination-demo').twbsPagination({
        totalPages: 51,
        visiblePages: 2,
        onPageClick: function (event, page) {
            console.log('Event: ' + event);
            var url = "/lms/custom/gorups/get_group_item.php";
            $.post(url, {id: page}).done(function (data) {
                $('#group_items').html(data);
            });

        }
    });


</script>
