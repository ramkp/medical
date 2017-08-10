<?php

require_once './classes/Wsdata.php';
$ws = new Wsdata();
$request = $_POST['request'];
$list = $ws->get_pdf_report(json_decode($request));
echo $list;
?>

<script type="text/javascript">
    
    $('#myTable').DataTable();
    
    </script>
