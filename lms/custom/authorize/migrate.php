<?php

require_once './Classes/ProcessPayment.php';
$pr = new ProcessPayment();
$pr->migrate_authorize();
$pr->migrate_braintree();
