<?php

require_once './Classes/ProcessPayment.php';
$pr = new ProcessPayment();
$data = $_REQUEST;
$pr->save_hook_log($data);
