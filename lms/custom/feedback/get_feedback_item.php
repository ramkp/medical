<?php

require_once './classes/Feedback.php';
$feedback = new Feedback();
$page = $_POST['id'];
$list = $feedback->get_feedback_item($page);
echo $list;