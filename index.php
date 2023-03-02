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

$id = required_param('id', PARAM_INT);

$course = $DB->get_record('course', array('id' => $id), '*', MUST_EXIST);
require_course_login($course);

$coursecontext = context_course::instance($course->id);


$PAGE->set_url('/mod/rateit/index.php', array('id' => $id));
$PAGE->set_title(format_string($course->fullname));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($coursecontext);
$PAGE->set_pagelayout('standard');


echo $OUTPUT->header();

$modulenameplural = get_string('modulenameplural', 'mod_rateit');
echo $OUTPUT->heading($modulenameplural);

$rateits = get_all_instances_in_course('rateit', $course);

if (empty($rateits)) {
    notice(get_string('no$rateitinstances', 'mod_rateit'), new moodle_url('/course/view.php', array('id' => $course->id)));
}


foreach ($rateits as $rateit) {
    if (!$rateit->visible) {
        $link = html_writer::link(
            new moodle_url('/mod/rateit/view.php', array('id' => $rateit->coursemodule)),
            format_string($rateit->name, true),
            array('class' => 'dimmed'));
    } else {
        $link = html_writer::link(
            new moodle_url('/mod/rateit/view.php', array('id' => $rateit->coursemodule)),
            format_string($rateit->name, true));
    }
}

echo $OUTPUT->footer();
