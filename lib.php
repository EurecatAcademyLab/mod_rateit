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
 * The main mod_rateit configuration form.
 *
 * @package     mod_rateit
 * @author      2022 JuanCarlo Castillo <juancarlo.castillo20@gmail.com>
 * @copyright   2022 JuanCa Castillo & Eurecat.dev
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


/**
 * Return if the plugin supports $feature.
 *
 * @param string $feature Constant representing the feature.
 * @return true | null True if the feature is supported, null otherwise.
 */
function rateit_supports($feature) {
    switch ($feature) {
        case FEATURE_MOD_INTRO:
            return true;
        default:
            return null;
    }
}

/**
 * Saves a new instance of the mod_rateit into the database.
 *
 * Given an object containing all the necessary data, (defined by the form
 * in mod_form.php) this function will create a new instance and return the id
 * number of the instance.
 *
 * @param object $moduleinstance An object from the form.
 * @param mod_rateit_mod_form $mform The form.
 * @return int The id of the newly inserted record.
 */
function rateit_add_instance($moduleinstance, $mform = null) {
    global $DB;

    $moduleinstance->timecreated = time();

    $id = $DB->insert_record('rateit', $moduleinstance);

    return $id;
}

/**
 * Updates an instance of the mod_rateit in the database.
 *
 * Given an object containing all the necessary data (defined in mod_form.php),
 * this function will update an existing instance with new data.
 *
 * @param object $moduleinstance An object from the form in mod_form.php.
 * @param mod_rateit_mod_form $mform The form.
 * @return bool True if successful, false otherwise.
 */
function rateit_update_instance($moduleinstance, $mform = null) {
    global $DB;

    $moduleinstance->timemodified = time();
    $moduleinstance->id = $moduleinstance->instance;

    return $DB->update_record('rateit', $moduleinstance);
}

/**
 * Removes an instance of the mod_rateit from the database.
 *
 * @param int $id Id of the module instance.
 * @return bool True if successful, false on failure.
 */
function rateit_delete_instance($id) {
    global $DB;

    $exists = $DB->get_record('rateit', array('id' => $id));
    if (!$exists) {
        return false;
    }

    $DB->delete_records('rateit', array('id' => $id));

    return true;
}

/** To do******
 * @uses FEATURE_COMPLETION_TRACKS_VIEWS
 * @uses FEATURE_GRADE_HAS_GRADE
 * @uses FEATURE_GRADE_OUTCOMES
 * @param string $feature FEATURE_xx constant for requested feature
 * @return mixed True if module supports feature, false if not, null if doesn't know or string for the module purpose.
 */

/**
 * Get the actual course module.
 * @return Object from the current id.
 */
function getcminstance() {
    $id = optional_param('id', 0, PARAM_INT);
    if ($id) {
        $cm = get_coursemodule_from_id('rateit', $id, 0, false, MUST_EXIST);
        return $cm;
    }
}

/**
 * Get the actual module instance with all the personal values.
 * @return Object from the current course module.
 */
function getmoduleinstance() {
    global $DB;
    $cm = getcminstance();
    $moduleinstance = $DB->get_record('rateit', array('id' => $cm->instance), '*', MUST_EXIST);
    return $moduleinstance;
}

/**
 * Get course from course module.
 * @return Object from the current course module.
 */
function get_course_from_cm() {
    global $DB;
    $cm = getcminstance();
    $course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    return $course;
}

/**
 * Get module instance sequence in course from course module.
 * @return Object with the whole sequence.
 */
function getsequence() {
    global $DB;
    $cm = getcminstance();
    $sql = 'SELECT  cs.sequence FROM {course_sections} cs WHERE cs.course = ? AND cs.id = ?;';
    $course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $sql = 'SELECT  cs.sequence FROM {course_sections} cs WHERE cs.course = ? AND cs.id = ?;';
    $sequence = $DB->get_record_sql($sql, array($course->id , $cm->section));
    return $sequence;
}

/**
 * Get previous activity module instance from sequence in course having reference actual course module.
 * @return String with the previous module id.
 */
function getpreact() {
    $cm = getcminstance();
    $sequence = getsequence();
    $haystack = explode("," , $sequence->sequence);
    $actual = array_search($cm->id , $haystack);
    $previous = $actual - 1;
    $preact = $haystack[$previous];
    return $preact;
}

/**
 * To get the previous activity name and id.
 * call getpreact function to get an array.
 * @return Object of object to sql querie from modules.
 */
function get_previousact() {
    global $DB;
    $sql = 'SELECT m.name , m.id from {course_modules} cm JOIN  {modules} m ON cm.module = m.id WHERE cm.id = ?;';
    $preact = getpreact();
    $sql = 'SELECT m.name , m.id from {course_modules} cm JOIN  {modules} m ON cm.module = m.id WHERE cm.id = ?;';

    $previousact = $DB->get_record_sql($sql, array ($preact));
    return $previousact;
}

/**
 * To get the previous activity name and id.
 * call getpreact function to get an array.
 * @return String of object to sql querie from modules.
 */
function getpreviousacttype() {
    $previousact = get_previousact();
    return strval($previousact->name);
}

/**
 * To get the previous activity name.
 * call getpreact function to get an array.
 * @return String of object to sql querie from modules.
 */
function getpreviousactname() {
    $previousact = get_previousact();
    $preact = getpreact();
    $cmpre = get_coursemodule_from_id(strval($previousact->name), intval($preact), 0, false, MUST_EXIST);
    return strval($cmpre->name);
}

/**
 * To get the Object of the module instance, in especify question name.
 * call getmoduleinstance function.
 * @return String of module instance , question name.
 */
function get_questionname() {
    $moduleinstance = getmoduleinstance();
    return $moduleinstance->question_name;
}

/**
 * To get the Object of the module instance, in especify how to display: to right and print it.
 * call getmoduleinstance function.
 * @return Array to display it to right.
 */
function printoptiontoright() {
    $moduleinstance = getmoduleinstance();

    return array (
        html_writer::tag('label', $moduleinstance->optionone, array('class' => 'optionone')),
        html_writer::tag('label', $moduleinstance->optiontwo, array('class' => 'optiontwo')),
        html_writer::tag('label', $moduleinstance->optionthree, array('class' => 'optionthree')),
        html_writer::tag('label', $moduleinstance->optionfour, array('class' => 'optionfour')),
        html_writer::tag('label', $moduleinstance->optionfive, array('class' => 'optionfive')),
    );
}

/**
 * To get the Object of the module instance, in especify how to display: to left and print it .
 * call getmoduleinstance function.
 * @return Array to display it to left.
 */
function printoptiontoleft() {
    $moduleinstance = getmoduleinstance();

    return array (
        html_writer::tag('label', $moduleinstance->optionfive, array('class' => 'optionfive')),
        html_writer::tag('label', $moduleinstance->optionfour, array('class' => 'optionfour')),
        html_writer::tag('label', $moduleinstance->optionthree, array('class' => 'optionthree')),
        html_writer::tag('label', $moduleinstance->optiontwo, array('class' => 'optiontwo')),
        html_writer::tag('label', $moduleinstance->optionone, array('class' => 'optionone')),
    );
}

/**
 * To get the name type & message  of the previous module instance.
 * @return String with the title.
 */
function printprevious() {
    $define = get_string('get_message', 'rateit');

    if (getpreviousactname() == 'lti') {
        $previuostype = "External tool";
    } else {
        $previuostype = getpreviousacttype();
    }
    $titlename = $define . $previuostype . " / " . getpreviousactname();
    return $titlename;
}

/**
 * To print is que survey is anonymous or not.
 * @return String with the anonymous.
 */
function printanonymous() {
    $moduleinstance = getmoduleinstance();
    return $moduleinstance->anonymous_survey;

}

/**
 * To get the info is have to display it to left.
 * @return String with a number.
 */
function toleft() {
    $moduleinstance = getmoduleinstance();
    return $moduleinstance->toleft;
}

/**
 * To get the value encript for a ID.
 * @param Int $data with the user id.
 * @return String.
 */
function encriptid($data) {
    $ciphering = "AES-256-CTR";
    $option = 0;
    $encryptioniv = '1234567890123456';
    $encryptionkey = 'rateit';
    $encryption = openssl_encrypt($data, $ciphering, $encryptionkey, $option, $encryptioniv);
    return $encryption;
}

/**
 * Adds module specific settings to the settings block
 *
 * @param settings_navigation $settings The settings navigation object
 * @param navigation_node $rateitnode The node to add module settings to
 */
function rateit_extend_settings_navigation(settings_navigation $settings, navigation_node $rateitnode) {

        $rateitnode->add(
            get_string('responses', 'rateit'),
            new moodle_url('/mod/rateit/report.php', array('id' => $settings->get_page()->cm->id))
        );
}

