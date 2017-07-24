

<?php
require_once './classes/Demographic.php';
$dm = new Demographic();
$list = $dm->get_demographic_page();
echo $list;
?>

