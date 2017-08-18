<?php

require_once './classes/Job.php';
$job = new Job();
$data = $_POST['data'];
$list = $job->update_jobs_instructor($data);
echo $list;



