

<?php
require_once './classes/Demographic.php';
$dm = new Demographic();
$list = $dm->get_demographic_page();
echo $list;
?>

<script type="text/javascript">

    $(document).ready(function () {
        console.log("ready!");

        $('#start_d').datepicker();
        $('#end_d').datepicker();

    });

</script>