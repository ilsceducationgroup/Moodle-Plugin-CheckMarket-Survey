<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Prints an instance of mod_ilsccheckmarket.
 *
 * @package     mod_ilsccheckmarket
 * @copyright   2024 ILSC
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/lib.php');

use mod_ilsccheckmarket\event\course_module_viewed;
use mod_ilsccheckmarket\services\panel_surveys as service_panel_surveys;
use mod_ilsccheckmarket\views\surveys\view;


// Course module id.
$courseid = optional_param('id', 0, PARAM_INT);

// Activity instance id.
$s = optional_param('s', 0, PARAM_INT);

if ($courseid) {
    $cm = get_coursemodule_from_id('ilsccheckmarket', $courseid, 0, false, MUST_EXIST);
    $course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $moduleinstance = $DB->get_record('ilsccheckmarket', array('id' => $cm->instance), '*', MUST_EXIST);
} else {
    $moduleinstance = $DB->get_record('ilsccheckmarket', array('id' => $s), '*', MUST_EXIST);
    $course = $DB->get_record('course', array('id' => $moduleinstance->course), '*', MUST_EXIST);
    $cm = get_coursemodule_from_instance('ilsccheckmarket', $moduleinstance->id, $course->id, false, MUST_EXIST);
}

require_login($course, true, $cm);

$modulecontext = context_module::instance($cm->id);

$event = course_module_viewed::create(array(
    'objectid' => $moduleinstance->id,
    'context' => $modulecontext
));
$event->add_record_snapshot('course', $course);
$event->add_record_snapshot('ilsccheckmarket', $moduleinstance);
$event->trigger();

$PAGE->set_context($modulecontext);
$PAGE->set_url('/mod/ilsccheckmarket/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($moduleinstance->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($modulecontext);
$PAGE->requires->js(new \moodle_url('/mod/ilsccheckmarket/assets/js/bootstrap.bundle.min.js'), true);

echo $OUTPUT->header();

$masterkey = get_config('mod_ilsccheckmarket', 'xmasterkey');
$xkey = get_config('mod_ilsccheckmarket', 'xkey');

if (!$masterkey || !$xkey) {
    echo $OUTPUT->notification('Please configure the plugin first.', 'error');
    echo $OUTPUT->footer();
    die();
}

$user_is_admin = user_is_admin($USER);

if (!$user_is_admin) {
    $email = null;
    $ilsc_number = $USER->idnumber;
} else {
    $email = trim(optional_param('email', '', PARAM_TEXT));
    $ilsc_number = trim(optional_param('ilscnumber', '', PARAM_TEXT));
}


$view = new view($courseid, $user_is_admin);
$view->set_page($PAGE);

$service_panel_surveys = new service_panel_surveys($masterkey, $xkey);
if ($email) {
    $view->email = $email;
    $service_panel_surveys->set_email($email);
}

if ($ilsc_number) {
    $view->ilsc_number = $ilsc_number;
    $service_panel_surveys->set_ilscnumber($ilsc_number);
}

$panel_data = null;

try {
    $panel_data = $service_panel_surveys->get_panel_data();
} catch (Exception $e) {
    echo $OUTPUT->notification($e->getMessage(), 'error');
    echo $OUTPUT->footer();
    die();
}

$respondedCompletelyId = 5;
$currentTime = time();

// Three categories of surveys
$respondedSurveyData = [];
$nonRespondedSurveyData = [];
$expiredSurveyData = [];

foreach ($panel_data as $row) {
    // Check if the survey has ended (using EndDate)
    $endTime = strtotime($row->EndDate);
    $startTime = strtotime($row->StartDate);

    // Already responded survey
    if ($row->ContactStatusId === $respondedCompletelyId) {
        $respondedSurveyData[] = $row;
    }
    // Non-responded but expired survey
    else if ($endTime < $currentTime || ($row->DateInvited && strtotime($row->DateInvited) + (14 * 24 * 60 * 60) < $currentTime)) {
        // Survey is expired either because:
        // 1. The survey end date has passed, or
        // 2. More than 14 days have passed since the invitation (assuming links expire after 14 days)
        $expiredSurveyData[] = $row;
    }
    // Non-responded and still active survey
    else if ($startTime <= $currentTime && $currentTime <= $endTime) {
        $nonRespondedSurveyData[] = $row;
    }
}

$view->respondedSurveyData = $respondedSurveyData;
$view->nonRespondedSurveyData = $nonRespondedSurveyData;
$view->expiredSurveyData = $expiredSurveyData;

$content = $view->get_content();

echo $content->text;

echo $OUTPUT->footer();

function user_is_admin($user)
{
    $admins = get_admins();
    foreach ($admins as $admin) {
        if ($user->id == $admin->id) {
            return true;
        }
    }
    return false;
}
