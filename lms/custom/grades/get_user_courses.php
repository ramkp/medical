<?php
/**
 * Created by PhpStorm.
 * User: moyo
 * Date: 9/27/17
 * Time: 21:09
 */

require_once './classes/Grades.php';
$gr = new Grades();
$userid = $_POST['userid'];
$list = $gr->get_user_courses($userid);
$courses = json_encode($list);
echo $courses;