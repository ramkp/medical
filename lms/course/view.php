<?php
//  Display the course home page.

require_once('../config.php');
require_once('lib.php');
require_once($CFG->libdir . '/completionlib.php');
require_once($CFG->dirroot . '/custom/my/classes/Dashboard.php');
require_once($CFG->dirroot . '/custom/calendar/classes/Calendar.php');
require_once($CFG->dirroot . '/custom/instructors/classes/Instructors.php');
require_once($CFG->dirroot . '/custom/survey/classes/Survey.php');

$cal = new Calendar();
$inst = new Instructors();
$id = optional_param('id', 0, PARAM_INT);
$name = optional_param('name', '', PARAM_RAW);
$edit = optional_param('edit', -1, PARAM_BOOL);
$hide = optional_param('hide', 0, PARAM_INT);
$show = optional_param('show', 0, PARAM_INT);
$idnumber = optional_param('idnumber', '', PARAM_RAW);
$sectionid = optional_param('sectionid', 0, PARAM_INT);
$section = optional_param('section', 0, PARAM_INT);
$move = optional_param('move', 0, PARAM_INT);
$marker = optional_param('marker', -1, PARAM_INT);
$switchrole = optional_param('switchrole', -1, PARAM_INT); // Deprecated, use course/switchrole.php instead.
$modchooser = optional_param('modchooser', -1, PARAM_BOOL);
$return = optional_param('return', 0, PARAM_LOCALURL);

$params = array();
if (!empty($name)) {
    $params = array('shortname' => $name);
} else if (!empty($idnumber)) {
    $params = array('idnumber' => $idnumber);
} else if (!empty($id)) {
    $params = array('id' => $id);
} else {
    print_error('unspecifycourseid', 'error');
}

$course = $DB->get_record('course', $params, '*', MUST_EXIST);

$urlparams = array('id' => $course->id);

// Sectionid should get priority over section number
if ($sectionid) {
    $section = $DB->get_field('course_sections', 'section', array('id' => $sectionid, 'course' => $course->id), MUST_EXIST);
}
if ($section) {
    $urlparams['section'] = $section;
}

$PAGE->set_url('/course/view.php', $urlparams); // Defined here to avoid notices on errors etc
// Prevent caching of this page to stop confusion when changing page after making AJAX changes
$PAGE->set_cacheable(false);

context_helper::preload_course($course->id);
$context = context_course::instance($course->id, MUST_EXIST);

// Remove any switched roles before checking login
if ($switchrole == 0 && confirm_sesskey()) {
    role_switch($switchrole, $context);
}

require_login($course);

// Switchrole - sanity check in cost-order...
$reset_user_allowed_editing = false;
if ($switchrole > 0 && confirm_sesskey() &&
        has_capability('moodle/role:switchroles', $context)) {
    // is this role assignable in this context?
    // inquiring minds want to know...
    $aroles = get_switchable_roles($context);
    if (is_array($aroles) && isset($aroles[$switchrole])) {
        role_switch($switchrole, $context);
        // Double check that this role is allowed here
        require_login($course);
    }
    // reset course page state - this prevents some weird problems ;-)
    $USER->activitycopy = false;
    $USER->activitycopycourse = NULL;
    unset($USER->activitycopyname);
    unset($SESSION->modform);
    $USER->editing = 0;
    $reset_user_allowed_editing = true;
}

//If course is hosted on an external server, redirect to corresponding
//url with appropriate authentication attached as parameter
if (file_exists($CFG->dirroot . '/course/externservercourse.php')) {
    include $CFG->dirroot . '/course/externservercourse.php';
    if (function_exists('extern_server_course')) {
        if ($extern_url = extern_server_course($course)) {
            redirect($extern_url);
        }
    }
}


require_once($CFG->dirroot . '/calendar/lib.php');    /// This is after login because it needs $USER
// Must set layout before gettting section info. See MDL-47555.
$PAGE->set_pagelayout('course');

if ($section and $section > 0) {
    // Get section details and check it exists.
    $modinfo = get_fast_modinfo($course);
    $coursesections = $modinfo->get_section_info($section, MUST_EXIST);

    // Check user is allowed to see it.
    if (!$coursesections->uservisible) {
        // Note: We actually already know they don't have this capability
        // or uservisible would have been true; this is just to get the
        // correct error message shown.
        require_capability('moodle/course:viewhiddensections', $context);
    }
}

// Fix course format if it is no longer installed
$course->format = course_get_format($course)->get_format();

$PAGE->set_pagetype('course-view-' . $course->format);
$PAGE->set_other_editing_capability('moodle/course:update');
$PAGE->set_other_editing_capability('moodle/course:manageactivities');
$PAGE->set_other_editing_capability('moodle/course:activityvisibility');
if (course_format_uses_sections($course->format)) {
    $PAGE->set_other_editing_capability('moodle/course:sectionvisibility');
    $PAGE->set_other_editing_capability('moodle/course:movesections');
}

// Preload course format renderer before output starts.
// This is a little hacky but necessary since
// format.php is not included until after output starts
if (file_exists($CFG->dirroot . '/course/format/' . $course->format . '/renderer.php')) {
    require_once($CFG->dirroot . '/course/format/' . $course->format . '/renderer.php');
    if (class_exists('format_' . $course->format . '_renderer')) {
        // call get_renderer only if renderer is defined in format plugin
        // otherwise an exception would be thrown
        $PAGE->get_renderer('format_' . $course->format);
    }
}

if ($reset_user_allowed_editing) {
    // ugly hack
    unset($PAGE->_user_allowed_editing);
}

if (!isset($USER->editing)) {
    $USER->editing = 0;
}
if ($PAGE->user_allowed_editing()) {
    if (($edit == 1) and confirm_sesskey()) {
        $USER->editing = 1;
        // Redirect to site root if Editing is toggled on frontpage
        if ($course->id == SITEID) {
            redirect($CFG->wwwroot . '/?redirect=0');
        } else if (!empty($return)) {
            redirect($CFG->wwwroot . $return);
        } else {
            $url = new moodle_url($PAGE->url, array('notifyeditingon' => 1));
            redirect($url);
        }
    } else if (($edit == 0) and confirm_sesskey()) {
        $USER->editing = 0;
        if (!empty($USER->activitycopy) && $USER->activitycopycourse == $course->id) {
            $USER->activitycopy = false;
            $USER->activitycopycourse = NULL;
        }
        // Redirect to site root if Editing is toggled on frontpage
        if ($course->id == SITEID) {
            redirect($CFG->wwwroot . '/?redirect=0');
        } else if (!empty($return)) {
            redirect($CFG->wwwroot . $return);
        } else {
            redirect($PAGE->url);
        }
    }
    if (($modchooser == 1) && confirm_sesskey()) {
        set_user_preference('usemodchooser', $modchooser);
    } else if (($modchooser == 0) && confirm_sesskey()) {
        set_user_preference('usemodchooser', $modchooser);
    }

    if (has_capability('moodle/course:sectionvisibility', $context)) {
        if ($hide && confirm_sesskey()) {
            set_section_visible($course->id, $hide, '0');
            redirect($PAGE->url);
        }

        if ($show && confirm_sesskey()) {
            set_section_visible($course->id, $show, '1');
            redirect($PAGE->url);
        }
    }

    if (!empty($section) && !empty($move) &&
            has_capability('moodle/course:movesections', $context) && confirm_sesskey()) {
        $destsection = $section + $move;
        if (move_section_to($course, $section, $destsection)) {
            if ($course->id == SITEID) {
                redirect($CFG->wwwroot . '/?redirect=0');
            } else {
                redirect(course_get_url($course));
            }
        } else {
            echo $OUTPUT->notification('An error occurred while moving a section');
        }
    }
} else {
    $USER->editing = 0;
}

$SESSION->fromdiscussion = $PAGE->url->out(false);


if ($course->id == SITEID) {
    // This course is not a real course.
    redirect($CFG->wwwroot . '/');
}

$completion = new completion_info($course);
if ($completion->is_enabled()) {
    $PAGE->requires->string_for_js('completion-title-manual-y', 'completion');
    $PAGE->requires->string_for_js('completion-title-manual-n', 'completion');
    $PAGE->requires->string_for_js('completion-alt-manual-y', 'completion');
    $PAGE->requires->string_for_js('completion-alt-manual-n', 'completion');

    $PAGE->requires->js_init_call('M.core_completion.init');
}

// We are currently keeping the button here from 1.x to help new teachers figure out
// what to do, even though the link also appears in the course admin block.  It also
// means you can back out of a situation where you removed the admin block. :)
if ($PAGE->user_allowed_editing()) {
    $buttons = $OUTPUT->edit_button($PAGE->url);
    $PAGE->set_button($buttons);
}

// If viewing a section, make the title more specific
if ($section and $section > 0 and course_format_uses_sections($course->format)) {
    $sectionname = get_string('sectionname', "format_$course->format");
    $sectiontitle = get_section_name($course, $section);
    $PAGE->set_title(get_string('coursesectiontitle', 'moodle', array('course' => $course->fullname, 'sectiontitle' => $sectiontitle, 'sectionname' => $sectionname)));
} // end if  
else {
    $PAGE->set_title(get_string('coursetitle', 'moodle', array('course' => $course->fullname)));
}

$PAGE->set_heading($course->fullname);
echo $OUTPUT->header();

if ($completion->is_enabled()) {
    // This value tracks whether there has been a dynamic change to the page.
    // It is used so that if a user does this - (a) set some tickmarks, (b)
    // go to another page, (c) clicks Back button - the page will
    // automatically reload. Otherwise it would start with the wrong tick
    // values.
    echo html_writer::start_tag('form', array('action' => '.', 'method' => 'get'));
    echo html_writer::start_tag('div');
    echo html_writer::empty_tag('input', array('type' => 'hidden', 'id' => 'completion_dynamic_change', 'name' => 'completion_dynamic_change', 'value' => '0'));
    echo html_writer::end_tag('div');
    echo html_writer::end_tag('form');
}


/* Payment status */
$ds = new Dashboard();
$status = $ds->get_user_status();
if ($status == 0) {
    $message = $ds->get_user_warning_message();
    echo $message;
    die();
}

/* Payments history */
$roleid = $ds->get_user_role($USER->id);
if ($roleid == 5 && $USER->username != 'manager' && $USER->username != 'admin') {
    $payments_history = $ds->get_payments_history_block($COURSE->id, $USER->id);
    echo $payments_history;
}

/* Workshop survey */
$category = $ds->get_course_category($COURSE->id);
$completed = $ds->is_course_completed($COURSE->id, $USER->id);
$has_application = $ds->is_user_has_survey_applicagtion($USER->id);
if ($roleid == 5 && $category == 2 && $completed > 0 && $has_application > 0) {
    $status = $ds->is_ws_survey_was_completed($COURSE->id, $USER->id);
    if ($status == 0) {
        //$survey = $ds->get_workshop_survey($COURSE->id, $USER->id);
        //echo $survey;
        //die();
    }
} // end if

/* Career college demographic info */
if ($roleid == 5) {
    //if ($USER->id == 13734) {
    $category = $ds->get_course_category($COURSE->id);
    if ($category >= 5) {
        $status = $ds->demo_info_applicable($USER->id);
        if ($status == 0) {
            $list = $ds->get_college_student_demographic_questionare($COURSE->id, $USER->id);
            echo $list;
            die();
        } // end if $status == 0
    } // end if $category>=5
    //} // end if $USER->id == 13734
} // end if $roleid == 5

/* Career college survey */
if ($roleid == 5) {
    // Put survey here if student is applicable
    //if ($USER->id == 13734) {
    $s = new Survey();
    $applicable = $s->survey_applicable();
    if ($applicable) {
        $list = $s->get_career_collge_survey();
        echo $list;
        die();
    } // end if $applicable
    //} // end if $USER->id==13734
} // end if $roleid == 5

/* * **********************************************************

 * Code related to instructor's calendar and students attendance

 * ********************************************************** */
$userid = $cal->user->id;
$instructor_status = $cal->is_instructor($userid);
if ($instructor_status) {
    $calendar = $cal->create_user_calendar();
    $attendance = $inst->get_instructor_students_attendance_block();
    echo $calendar;
    if ($userid == 234) {
        echo $attendance;
    }
} // end if $status
// Course wrapper start.
echo html_writer::start_tag('div', array('class' => 'course-content'));

// make sure that section 0 exists (this function will create one if it is missing)
course_create_sections_if_missing($course, 0);

// get information about course modules and existing module types
// format.php in course formats may rely on presence of these variables
$modinfo = get_fast_modinfo($course);
$modnames = get_module_types_names();
$modnamesplural = get_module_types_names(true);
$modnamesused = $modinfo->get_used_module_names();
$mods = $modinfo->get_cms();
$sections = $modinfo->get_section_info_all();

// CAUTION, hacky fundamental variable defintion to follow!
// Note that because of the way course fromats are constructed though
// inclusion we pass parameters around this way..
$displaysection = $section;

// Include the actual course format.
require($CFG->dirroot . '/course/format/' . $course->format . '/format.php');
// Content wrapper end.

echo html_writer::end_tag('div');

// Trigger course viewed event.
// We don't trust $context here. Course format inclusion above executes in the global space. We can't assume
// anything after that point.
course_view(context_course::instance($course->id), $section);

// Include course AJAX
include_course_ajax($course, $modnamesused);
?>


<script type="text/javascript">

    $(document).ready(function () {

        var courseid =<?php echo $COURSE->id; ?>;
        console.log('Course id: ' + courseid);
        // Replace original Moodle player with flowplayer for labels
        var labelurl = '/lms/custom/flowplayer/url.php';
        $(".activity.modtype_label.label").each(function () {
            var id = $(this).attr('id').replace('module-', '');
            if (courseid != 71) {
                $.post(labelurl, {id: id}).done(function (url) {
                    var containerid = '#module-' + id + '> div > div > div:nth-child(2) > div > div > div > p > span';
                    console.log('Container id: ' + containerid);
                    var container = $(containerid);
                    container.empty();
                    
                    /*
                    container.bind("contextmenu", function (e) {
                        e.preventDefault();
                    });
                    */
                    $(containerid).html("<button class='btn btn-primary' id='play_video_"+id+"'>Play Video</button>");
                    /*
                    flowplayer(container, {
                        share: false,
                        key: "$599424236128582",
                        clip: {
                            sources: [{type: "video/mp4", src: url, engine: "html5"}]
                        } // end of clip
                    }); // end of player ...
                    */
                    
                }); // end of post 
            } // end if courseid!=71
            else {
                // It is test course where we perform development
                $.post(labelurl, {id: id}).done(function (url) {
                    var containerid = "#module-" + id + " > div > div > div:nth-child(2) > div > div > div > span ";
                    console.log('Container id: ' + containerid);
                    var container = $(containerid);
                    container.empty();
                    $(containerid).html("<br><button class='btn btn-primary' id='play_video_"+id+"'>Play Video</button>");
                    /*
                     container.bind("contextmenu", function (e) {
                     e.preventDefault();
                     });
                     
                     flowplayer(container, {
                     share: false,
                     key: "$599424236128582",
                     clip: {
                     sources: [{type: "video/mp4", src: url, engine: "html5"}]
                     } // end of clip
                     }); // end of player ...
                     */
                }); // end of post
            } // end else
        }); // end of each

        var calendarurl = '/lms/custom/calendar/get_user_calendar.php';
        $.post(calendarurl, {userid: <?php echo $userid; ?>}).done(function (dates) {
            console.log('Returned dates: ' + dates);
            $('#user_calendar').datepicker({
                dateFormat: "mm/dd/yy"
            });
        }); // end of post

        $('#graduate_date').datepicker();
    }); // end of document ready

</script>

<?php
echo $OUTPUT->footer();

