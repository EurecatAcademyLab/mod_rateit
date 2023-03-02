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
 * Display information about all the mod_rateit modules in the requested course.
 *
 * @package     mod_rateit
 * @author      2022 JuanCarlo Castillo <juancarlo.castillo20@gmail.com>
 * @copyright   2022 JuanCa Castillo & Eurecat.dev
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__.'/../../config.php');
require_once(__DIR__.'/lib.php');
require_once(__DIR__.'/classes/save/save_from_user.php');
require_once('mod_form.php');
require_once('mod_form_edit.php');

$PAGE->requires->jquery();
$PAGE->requires->js('/mod/rateit/amd/jquery.js');

// Course module id.
$id = optional_param('id', 0, PARAM_INT);

// Activity instance id.
$r = optional_param('r', 0, PARAM_INT);


if ($id) {
    $cm = get_coursemodule_from_id('rateit', $id, 0, false, MUST_EXIST);
    $course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $moduleinstance = $DB->get_record('rateit', array('id' => $cm->instance), '*', MUST_EXIST);
    $sql = 'SELECT  cs.sequence FROM {course_sections} cs WHERE cs.course = ? AND cs.id = ?;';
    $sequence = $DB->get_record_sql($sql, array($course->id , $cm->section));

    $haystack = explode("," , $sequence->sequence);
    $actual = array_search($cm->id , $haystack);
    if ($actual == 0) {
        \core\notification::add(get_string('no_previous', 'rateit'), \core\output\notification::NOTIFY_WARNING);
        $urlcourse = new moodle_url('/course/view.php', array('id' => $course->id));
        redirect($urlcourse);
        exit();
    }
} else {
    $moduleinstance = $DB->get_record('rateit', array('id' => $r), '*', MUST_EXIST);
    $course = $DB->get_record('course', array('id' => $moduleinstance->course), '*', MUST_EXIST);
    $cm = get_coursemodule_from_instance('rateit', $moduleinstance->id, $course->id, false, MUST_EXIST);
    if ($actual == 0) {
        \core\notification::add(get_string('no_previous', 'rateit'), \core\output\notification::NOTIFY_WARNING);
        $urlcourse = new moodle_url('/course/view.php', array('id' => $course->id));
        redirect($urlcourse);
        exit();
    }
}

$modulecontext = context_module::instance($cm->id);
require_login($course, true, $cm);
require_course_login($course, false, $cm);


$PAGE->set_url('/mod/rateit/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($moduleinstance->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($modulecontext);
$PAGE->set_pagelayout('standard');

$mform = new mod_rateit_student_form();

echo $OUTPUT->header();

if ($mform->is_cancelled()) {
    redirect(new moodle_url('/course/view.php?id='.$PAGE->course->id.'"') );
} else if ($fromform = $mform->get_data()) {
    require_sesskey();
    savecomment($fromform);
    redirect(new moodle_url('/course/view.php?id='.$PAGE->course->id.'"') );
} else {
    $mform->display();
}

echo $OUTPUT->footer();

