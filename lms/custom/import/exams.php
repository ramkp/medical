<?php

require_once './classes/Import.php';
$import = new Import();
$filepath = $_SERVER['DOCUMENT_ROOT'] . "/lms/custom/import/files/1/exams";
$classes = $import->process_exam_questions($filepath);
