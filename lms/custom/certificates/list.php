<?php
require_once './classes/Certificates.php';
$certificate = new Certificates();
$list = $certificate->get_certificates_list();
$total = $_SESSION['total'];
echo $list;
?>

<script type="text/javascript">

    $(document).ready(function () {

        $(function () {
            $('#pagination').pagination({
                items: <?php echo $total; ?>,
                itemsOnPage: <?php echo $certificate->limit; ?>,
                cssStyle: 'light-theme'
            });
        });

        $("#pagination").click(function () {
            var page = $('#pagination').pagination('getCurrentPage');
            console.log('Page: ' + page);
            var url = "/lms/custom/certificates/get_certificate_item.php";
            $.post(url, {id: page}).done(function (data) {
                $('#certificates_container').html(data);
            });
        });

    });

</script>