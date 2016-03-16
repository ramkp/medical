<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Parent theme: Bootstrapbase by Bas Brands
 * Built on: Essential by Julian Ridden
 *
 * @package   theme_lambda
 * @copyright 2014 redPIthemes
 *
 */
require_once ($_SERVER['DOCUMENT_ROOT'] . '/lms/custom/nav/classes/navClass.php');

echo "<script type='text/javascript' src='http://" . $_SERVER['SERVER_NAME'] . "/lms/custom/nav/js/navigation.js'></script>";
echo "<script type='text/javascript' src='http://" . $_SERVER['SERVER_NAME'] . "/lms/custom/uploader/js/vendor/jquery.ui.widget.js'></script>";
echo "<script type='text/javascript' src='http://" . $_SERVER['SERVER_NAME'] . "/lms/custom/uploader/js/jquery.iframe-transport.js'></script>";
echo "<script type='text/javascript' src='http://" . $_SERVER['SERVER_NAME'] . "/lms/custom/uploader/js/jquery.fileupload.js'></script>";
echo "<script src='https://cdn.ckeditor.com/4.5.6/standard/ckeditor.js'></script>";
echo "<script type='text/javascript' src='http://maps.googleapis.com/maps/api/js?key=AIzaSyA_7yjXzpz9sxQw6Ut0gFa8045N_I4QGXk'></script>";
echo "<script type='text/javascript' src='http://" . $_SERVER['SERVER_NAME'] . "/assets/pagination/jquery.simplePagination.js'></script>";
echo "<link type='text/css' rel='stylesheet' href='http://" . $_SERVER['SERVER_NAME'] . "/assets/pagination/simplePagination.css'/>";
?>

<script>
    $(function () {
        $('#fileupload').fileupload({
            dataType: 'json',
            done: function (e, data) {
                $.each(data.result.files, function (index, file) {
                    $('<p/>').text(file.name).appendTo(document.body);
                });
            }
        });
    });
</script>

<?php
$haslogo = (!empty($PAGE->theme->settings->logo));

$hasheaderprofilepic = (empty($PAGE->theme->settings->headerprofilepic)) ? false : $PAGE->theme->settings->headerprofilepic;

$checkuseragent = '';
if (!empty($_SERVER['HTTP_USER_AGENT'])) {
    $checkuseragent = $_SERVER['HTTP_USER_AGENT'];
}
$username = get_string('username');
if (strpos($checkuseragent, 'MSIE 8')) {
    $username = str_replace("'", "&prime;", $username);
}
?>

<?php if ($PAGE->theme->settings->socials_position == 1) { ?>
    <div class="container-fluid socials-header"> 
    <?php require_once(dirname(__FILE__) . '/socials.php'); ?>
    </div>
    <?php }
    ?>

<header id="page-header" class="clearfix">

</header>

<?php
global $USER;
$nav = new navClass();
$top_menu = $nav->get_navigation_items($USER->id);
echo $top_menu;
?>