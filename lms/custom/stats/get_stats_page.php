<?php
require_once './classes/Stats.php';
$stats = new Stats();
$src_users = $stats->get_users_source_page();
$state_users = $stats->get_users_states_page();
$src_list = $stats->get_users_source_page();
$states_list=$stats->get_users_states_page();
echo $src_list;
echo $states_list;
?>

<script type="text/javascript">

    $(document).ready(function () {

    
    }); // end of document.ready ...

</script>
