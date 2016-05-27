<?php

require_once '/home/cnausa/public_html/lms/custom/installment/classes/Installment.php';
$installment = new Installment();
$list = $installment->verify_installment_users();
echo $list;
