<?php

require_once './classes/Import.php';
$import=new Import();
$activity=new stdClass();
$activity->uid='U201305024425000';
$activity->amount=100;
$activity->pdate='5/2/2013';
$activity->pstatus=0;
$activity->ptype='CC';
$activity->regdate=' 5/2/2013';
$activity->courseid=671;
$activity->certno='036792-PT13';
$activity->cstart='5/19/2013';
$activity->cend='5/19/2014';

$import->enroll_user_to_course($activity);
$import->create_user_certification_data($activity);
$import->add_user_payment($activity);


