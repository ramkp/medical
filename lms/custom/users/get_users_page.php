<?php
require_once './classes/User.php';
$user = new User();
$list = $user->get_users_list();
$total = $user->get_users_total();
echo $list;
?>

<script type="text/javascript">

    $(document).ready(function () {

        $(function () {
                var total=document.getElementById("total").value;  
                //alert ('Total: '+total);
                $('#pagination').pagination({
                items:total,
                itemsOnPage: <?php echo $user->limit; ?>,
                cssStyle: 'light-theme'
            });
        });

        $("#pagination").click(function () {
            var page = $('#pagination').pagination('getCurrentPage');
            console.log('Page: ' + page);
            var url = "/lms/custom/users/get_user_item.php";
            $.post(url, {id: page}).done(function (data) {
                $('#users_container').html(data);
            });
        });

    });

</script>
