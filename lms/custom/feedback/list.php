<?php

require_once "./classes/Feedback.php";
$feedback = new Feedback();
$list = $feedback->get_feedback_list();
$total = $feedback->get_total_feedbacks();
//echo "Total items: ".$total."<br>";
echo $list;
?>

<script type="text/javascript">

    $(document).ready(function () {

        $(function () {
            $('#pagination').pagination({
                items: <?php echo $total; ?>,
                itemsOnPage: <?php echo $feedback->limit; ?>,
                cssStyle: 'light-theme'
            });
        });

        $("#pagination").click(function () {
            var page = $('#pagination').pagination('getCurrentPage');
            console.log('Page: ' + page);
            var url = "/lms/custom/feedback/get_feedback_item.php";
            $.post(url, {id: page}).done(function (data) {
                $('#feedback_container').html(data);
            });
        });

    });

</script>