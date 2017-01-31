<?php
require_once './classes/Instructors.php';
$in = new Instructors();
$total = $in->get_total();
$list = $in->get_instructors_page();
echo $list;
?>

<script type="text/javascript">

    $(document).ready(function () {

        $(function () {
            $('#pagination').pagination({
                items: <?php echo $total; ?>,
                itemsOnPage: <?php echo $in->limit; ?>,
                cssStyle: 'light-theme'
            });
        });

        $("#pagination").click(function () {
            var page = $('#pagination').pagination('getCurrentPage');
            console.log('Page: ' + page);
            var url = "/lms/custom/instructors/get_instructor_item.php";
            $.post(url, {id: page}).done(function (data) {
                $('#inst_container').html(data);
            });
        });

    });

</script>