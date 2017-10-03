<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/config.php';

$courses = enrol_get_my_courses();
echo "<br>--------------------- Before Sort -------------------------<br>";
echo "<pre>";
print_r($courses);
echo "</pre>";

function extract_courses_names($courses) {
    foreach ($courses as $c) {
        $names[] = $c->fullname;
    }
    sort($names, SORT_NATURAL);
    return $names;
}

function sort_courses($courses) {
    $sortedcourses = array();
    $names = extract_courses_names($courses);
    foreach ($names as $name) {
        foreach ($courses as $c) {
            if ($name == $c->fullname) {
                $sortedcourses[$c->id] = $c;
            }
        } // end foreach
    } // end foreach
    return $sortedcourses;
}

echo "<br>--------------------- After Sort -------------------------<br>";
$sortedcourses = sort_courses($courses);
echo "<pre>";
print_r($sortedcourses);
echo "</pre>";



