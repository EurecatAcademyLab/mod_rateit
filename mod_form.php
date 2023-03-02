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

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/course/moodleform_mod.php');
require_once(__DIR__.'/classes/events/query.php');

/**
 * Defines configuration form.
 */
class mod_rateit_mod_form extends moodleform_mod {

    /**
     * Defines forms elements
     */
    public function definition() {
        global $CFG;

        $mform = $this->_form;

        // Adding the "general" fieldset, where all the common settings are shown.
        $mform->addElement('header', 'general', get_string('general', 'form'));

        // Adding the standard "name" field.
        $mform->addElement('text', 'name', get_string('rateitname', 'mod_rateit'), array('size' => '64'));
        $mform->setDefault('name', 'Rate the previous activity');

        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('name', PARAM_TEXT);
        } else {
            $mform->setType('name', PARAM_CLEANHTML);
        }

        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addRule('name', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');
        $mform->addHelpButton('name', 'rateitname', 'mod_rateit');

        // Option yes/no anonymous.
        $mform->addElement(
        'selectyesno',
        'anonymous_survey',
        get_string('anonymous_survey', 'rateit')
        );
        $mform->setDefault('anonymous_survey', 0);
        $mform->setType('anonymous_survey', PARAM_INT);
        $mform->addHelpButton('anonymous_survey', 'survey', 'rateit');

        // Question name.
        $mform->addElement('text', 'question_name', get_string('question_name', 'mod_rateit'), array('size' => '64'));
        $mform->setType('question_name', PARAM_TEXT);
        $mform->setDefault('question_name' , get_string('question', 'rateit'));
        $mform->addHelpButton('question_name', 'question', 'rateit');

        $instructions = get_string('instructions', 'rateit');
        $instructionsdiv = '<div class = "ml-4 w-75 mt-4"><p><small>'.$instructions.'</small></p></div>';
        $mform->addElement('html' , $instructionsdiv);

        // ---------------------
        // Option elements.

        // Option five.
        if (valueuser()) {
            $mform->addElement('text', 'optionfive', get_string('optionfive', 'rateit'));
        } else {
            $mform->addElement('text', 'optionfive', get_string('optionfive', 'rateit'), array('disabled' => 'disabled'));
        }
        $mform->addHelpButton('optionfive', 'option', 'rateit');
        $mform->setDefault('optionfive' , get_string('five', 'rateit'));
        $mform->createElement('hidden', 'optionidfive', 0);
        $mform->setType('optionidfive', PARAM_INT);

        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('optionfive', PARAM_TEXT);
        } else {
            $mform->setType('optionfive', PARAM_CLEANHTML);
        }

        // -----------------------

        // Option four.
        if (valueuser()) {
            $mform->addElement('text', 'optionfour', get_string('optionfour', 'rateit'));
        } else {
            $mform->addElement('text', 'optionfour', get_string('optionfour', 'rateit'), array('disabled' => 'disabled'));
        }
        $mform->addHelpButton('optionfour', 'option', 'rateit');
        $mform->setDefault('optionfour' , get_string('four', 'rateit'));
        $mform->createElement('hidden', 'optionidfour', 0);
        $mform->setType('optionidfour', PARAM_INT);

        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('optionfour', PARAM_TEXT);
        } else {
            $mform->setType('optionfour', PARAM_CLEANHTML);
        }

        // -----------------------

        // Option three.
        if (valueuser()) {
            $mform->addElement('text', 'optionthree', get_string('optionthree', 'rateit'));
        } else {
            $mform->addElement('text', 'optionthree', get_string('optionthree', 'rateit'), array('disabled' => 'disabled'));
        }
        $mform->addHelpButton('optionthree', 'option', 'rateit');
        $mform->setDefault('optionthree' , get_string('three', 'rateit'));
        $mform->createElement('hidden', 'optionidthree', 0);
        $mform->setType('optionidthree', PARAM_INT);

        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('optionthree', PARAM_TEXT);
        } else {
            $mform->setType('optionthree', PARAM_CLEANHTML);
        }

        // ---------------------

        // Option two.
        if (valueuser()) {
            $mform->addElement('text', 'optiontwo', get_string('optiontwo', 'rateit'));
        } else {
            $mform->addElement('text', 'optiontwo', get_string('optiontwo', 'rateit'), array('disabled' => 'disabled'));
        }
        $mform->addHelpButton('optiontwo', 'option', 'rateit');
        $mform->setDefault('optiontwo' , get_string('two', 'rateit'));
        $mform->createElement('hidden', 'optionidtwo', 0);
        $mform->setType('optionidtwo', PARAM_INT);

        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('optiontwo', PARAM_TEXT);
        } else {
            $mform->setType('optiontwo', PARAM_CLEANHTML);
        }

        // Option one.
        if (valueuser()) {
            $mform->addElement('text', 'optionone', get_string('optionone', 'rateit'));
        } else {
            $mform->addElement('text', 'optionone', get_string('optionone', 'rateit'), array('disabled' => 'disabled'));
        }
        $mform->setDefault('optionone' , get_string('one', 'rateit'));
        $mform->addHelpButton('optionone', 'option', 'rateit');
        $mform->createElement('hidden', 'optionidone', 0);
        $mform->setType('optionidone', PARAM_INT);

        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('optionone', PARAM_TEXT);
        } else {
            $mform->setType('optionone', PARAM_CLEANHTML);
        }

        // Option likert scale toleft.
        if (valueuser()) {
            $mform->addElement('selectyesno', 'toleft',  get_string('toleft', 'rateit') );
        } else {
            $mform->addElement('selectyesno', 'toleft',  get_string('toleft', 'rateit'),  array('disabled' => 'disabled') );
        }

        $mform->setDefault('toleft', 0);
        $mform->setType('toleft', PARAM_INT);
        $mform->addHelpButton('toleft', 'toleft', 'rateit');

        // Add standard elements.
        $this->standard_coursemodule_elements();

        // Add standard buttons.
        $this->add_action_buttons();
    }
}
