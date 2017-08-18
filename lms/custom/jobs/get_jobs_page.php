<?php

require_once './classes/Job.php';
$job = new Job();
$list = $job->get_jobs_page();
echo $list;
