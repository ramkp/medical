<?php

require_once './classes/Wsdata.php';
$ws = new Wsdata();
$ws->update_initial_student_statuses();
