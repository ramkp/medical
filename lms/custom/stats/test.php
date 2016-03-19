<?php

require_once './classes/Stats.php';
$stats = new Stats();
$src_users = $stats->get_users_source_page();
$state_users = $stats->get_users_states_page();

echo "<pre>";
print_r($stats->sources);
echo "<pre>";

echo "<br>----------------------------------------------------------------<br>";

echo "<pre>";
print_r($stats->states);
echo "<pre>";
