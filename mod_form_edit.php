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

require_once($CFG->dirroot.'/lib/formslib.php');
require_once(__DIR__.'/lib.php');
require_once(__DIR__.'/view.php');

/**
 * Defines configuration form. This will have the student view form.
 */
class mod_rateit_student_form extends moodleform {
    /**
     * Defines forms elements
     */
    public function definition() {
        global $CFG, $PAGE, $DB, $USER;

        $alreadydone = 0;
        $preact = getpreact();
        $actualact = getcminstance();
        $titlename = printprevious();
        $anonim = intval( printanonymous());
        $toleft = intval(toleft());
        $user = encriptid($USER->id);

        if ($DB->record_exists('rateit_users', array('actual_id' => intval($actualact->id) ))) {
            if ($DB->record_exists('rateit_users', array('user_id' => $user))) {
                \core\notification::add(get_string('alreadysave', 'rateit'), \core\output\notification::NOTIFY_SUCCESS);

                $alreadydone = 1;
            }
        }

        $time = new DateTime("now", core_date::get_user_timezone_object());
        $timestamp = $time->getTimestamp();

        $PAGE->force_settings_menu();

        $mform = $this->_form;

        // Hidden fields id course.
        $mform->addElement('hidden', 'course', $PAGE->course->id);
        $mform->setType('course', PARAM_INT);

        // Hidden fields id previous activity.
        $mform->addElement('hidden', 'id', $actualact->id);
        $mform->setType('id', PARAM_INT);

        // To compare.
        $mform->addElement('hidden', 'actual_id', $actualact->id);
        $mform->setType('actual_id', PARAM_INT);

        // Hidden fields id actual activity.
        $mform->addElement('hidden', 'previous_id', $preact);
        $mform->setType('previous_id', PARAM_INT);

        // Hidden fields id actual activity.
        $mform->addElement('hidden', 'actual_id', $actualact->id);
        $mform->setType('actual_id', PARAM_INT);

        // Hidden fields time().
        $mform->addElement('hidden', 'timecreated', $timestamp);
        $mform->setType('timecreated', PARAM_INT);

        // Hidden fields anonymous().
        $mform->addElement('hidden', 'anonim', $anonim);
        ($anonim == 1) ? $mform->setDefault('anonim', 1) : $mform->setDefault('anonim', 0);
        $mform->setType('anonim', PARAM_INT);

        $tit1 = '<h1 id=mod_title  class = mb-4>'.$titlename.'</h1>';
        $title = '<div style="display:flex; justify-content:center; margin-bottom: 20px; margin-top: 20px;">'.$tit1.'</div>';

        // Adding the standard "name" field.
        $mform->addElement('html', $title );
        $mform->setType($title, PARAM_TEXT);

        // Anonimous.
        if ($anonim == 1) {
            \core\notification::add(get_string('anonim', 'rateit'), \core\output\notification::NOTIFY_INFO);
            $mform->addElement('hidden', 'user_id', strval($USER->id));
            $mform->setType('user_id', PARAM_INT);

        } else {
            $mform->addElement('hidden', 'user_id', strval($USER->id));
            $mform->setType('user_id', PARAM_INT);
        }

        // Options.
        ($toleft == 0) ? $options = printoptiontoright() : $options = printoptiontoleft();

        $radioarray = array();
        for ($i = 0; $i < count($options); $i++) {
            $radioarray[] =& $mform->createElement('radio', 'label', '', $options[$i] , $i);
        }

        $mform->addGroup(
            $radioarray,
            'radioar',
            get_questionname(),
            array(' '),
            false);

        $mform->addHelpButton('radioar', 'radioar', 'rateit');

        // Message.
        ($alreadydone == 0)
        ? $mform->addElement('textarea', 'message', get_string('message' , 'rateit'), [
            'rows' => '3',
            'col' => '20',
            'placeholder' => get_string('leave' , 'rateit')
            ])
        : $mform->addElement('textarea', 'message', get_string('message', 'rateit'), [
            'disabled' => 'disabled',
            'placeholder' => get_string('leave', 'rateit')
        ]);

        $mform->addHelpButton('message', 'message', 'rateit');

        $mform->setType('message', PARAM_RAW);

        // --------------------------------
        // Buttons.

        ($alreadydone == 0)
        ? $mform->addElement('submit', 'submitButton', get_string("save", 'rateit'), ['class' => 'button'])

        : $mform->addElement('submit', 'submitButton', get_string("save", 'rateit'), [
            'class' => 'button',
            'disabled' => 'disabled'
        ]);

        $mform->addElement('cancel', 'cancel', get_string("cancel", 'rateit'), ['class' => 'button']);
    }

    /**
     * To reset the button and redirect to the course view.
     */
    public function reset() {
        global $PAGE;
        redirect(new moodle_url('/course/view.php?id='.$PAGE->course->id) );
    }

    /**
     * If the user cancel the form, redirect to the course view.
     */
    public function cancel() {
        global $PAGE;
        $url = new moodle_url('/course/view.php?id='.$PAGE->course->id);
        redirect($url );
    }
}

