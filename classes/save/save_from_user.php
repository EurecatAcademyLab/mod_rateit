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

defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot.'/course/lib.php');
require_once($CFG->dirroot.'/lib/formslib.php');
require_once($CFG->dirroot.'/config.php');
require_once(__DIR__.'/../../lib.php');
require_login();

/**
 * Save record on DB.
 * @param Object $fromform get the info from user form
 */
function savecomment($fromform) {

    global $DB;

    switch ($fromform->label) {
        case 0:
            $rating = 1;
            break;
        case 1:
            $rating = 2;
            break;
        case 2:
            $rating = 3;
            break;
        case 3:
            $rating = 4;
            break;
        case 4:
            $rating = 5;
            break;
    }

    $user = encriptid($fromform->user_id);
    $user = strval($user);
    $record = new stdClass();
    $record->course_id = $fromform->course;
    $record->user_id = $user;
    $record->previous_id = $fromform->previous_id;
    $record->rating = $rating;
    $record->actual_id = $fromform->actual_id;
    $record->comments = $fromform->message;
    $record->anonim = $fromform->anonim;
    $record->timecreated = $fromform->timecreated;

    $DB->insert_record('rateit_users', $record);
}

/**
 * Save record on DB / function not used.
 * @param Object $fromform get the info from user form
 */
function savecompletion($fromform) {
    global $DB;
    $record = new stdClass();
    $record->coursemoduleid = $fromform->actual_id;
    $record->userid = $fromform->user_id;
    $record->completionstate = 1;
    $record->viewed = 1;
    $record->overrideby = null;
    $record->timemodified = $fromform->timecreated;
    $DB->insert_record('course_modules_completion', $record);
}

/**
 * Modified record on DB / function not used.
 * @param Object $fromform get the info from user form
 */
function savecoursemodule($fromform) {
    global $DB;
    $record = new stdClass();
    $record->id = $fromform->actual_id;
    $record->visible = 0;
    $record->visibleoncoursepage = 0;
    if ($DB->record_exists('course_modules', array('id' => $record->id))) {
        $DB->update_record('course_modules', $record);
    }

}

