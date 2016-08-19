<?php
require_once './classes/Report.php';
$report = new Report();
$type = 2; // cheque
$list = $report->get_other_payments_report($type);
echo $list;
?>

<script type="text/javascript">

    $(document).ready(function () {
        $(function () {
            $('#datepicker1').datepicker();
            $('#datepicker2').datepicker();
        });
    });

</script>    