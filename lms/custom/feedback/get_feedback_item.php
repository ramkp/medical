<?php

require_once './classes/Feedback.php';
$feedback = new Feedback();
$page = $_POST['id'];
$list = $cert->get_feedback_item($page);
echo $list;