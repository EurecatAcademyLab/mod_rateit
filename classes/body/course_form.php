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

require_once($CFG->dirroot.'/lib/formslib.php');

/**
 * Defines form to select a course / not used it.
 */
class select_course extends moodleform {

    /**
     * Add elements to form.
     */
    public function definition() {

        $mform = $this->_form; // Don't forget the underscore!

        // Add elements to your form.

        $courses = array();
        $getcourse = get_courses();

        foreach ($getcourse as $course) {
            $courses[$course->id] = $course->fullname;
        }
        $courses[0] = get_string('all_courses', 'mod_rateit');
        ksort($courses);

        $select = $mform->addElement('select', 'course', get_string('select', 'mod_rateit'), $courses);
        // This will select the colour blue.
        $select->setSelected(get_string('all_courses', 'mod_rateit'));
        $mform->setType('course', PARAM_INT);

        $this->add_action_buttons(false, get_string('submit'));

    }

}
