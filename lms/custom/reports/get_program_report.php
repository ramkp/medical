<?php

require_once './classes/Report.php';
$report=new Report();
$list=$report->get_program_report();
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
