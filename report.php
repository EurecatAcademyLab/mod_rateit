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

    require_once("../../config.php");
    require_once("lib.php");
    require_once($CFG->dirroot. '/mod/rateit/classes/body/table.php');
    require_once($CFG->dirroot. '/mod/rateit/classes/db/query.php');
    require_once($CFG->dirroot. '/mod/rateit/classes/body/course_form.php');
    require_once($CFG->dirroot. '/mod/rateit/lib.php');

    // Course module id.
    $id = optional_param('id', 0, PARAM_INT);

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
}

    $modulecontext = context_module::instance($cm->id);

    $url = new moodle_url('/mod/rateit/report.php', array('id' => $id));
    $PAGE->set_url($url);
    require_login($course, true, $cm);
    $PAGE->set_heading(format_string($course->fullname));
    $PAGE->set_title(get_string('pluginnametitle' , 'rateit'));
    $PAGE->set_context($modulecontext);
    $PAGE->set_pagelayout('admin');

    // Requires.
    $PAGE->requires->jquery();
    $PAGE->requires->js(new \moodle_url('https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js'), true);
    $PAGE->requires->js(new \moodle_url('https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js'), true);
    $PAGE->requires->js(new \moodle_url('https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js'), true);
    $PAGE->requires->js(new \moodle_url('https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js'), true);
    $PAGE->requires->js(new \moodle_url('https://cdn.datatables.net/buttons/2.3.3/js/dataTables.buttons.min.js'), true);
    $PAGE->requires->js(new \moodle_url('https://cdn.datatables.net/buttons/2.3.3/js/buttons.bootstrap4.min.js'), true);
    $PAGE->requires->js(new \moodle_url('https://cdn.datatables.net/buttons/2.3.2/js/buttons.colVis.min.js'), true);
    $PAGE->requires->js(new \moodle_url('https://cdn.datatables.net/buttons/2.3.2/js/buttons.html5.min.js'), true);
    $PAGE->requires->js(new \moodle_url('https://cdn.datatables.net/buttons/2.3.2/js/buttons.print.min.js'), true);
    $PAGE->requires->js(new \moodle_url('https://cdn.datatables.net/searchpanes/2.1.0/js/dataTables.searchPanes.min.js'), true);
    $PAGE->requires->js(new \moodle_url('https://cdn.datatables.net/select/1.5.0/js/dataTables.select.min.js'), true);
    $PAGE->requires->js(new \moodle_url('https://cdn.datatables.net/plug-ins/1.13.1/api/average().js'), true);
    $PAGE->requires->css(new \moodle_url('https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css'));
    $PAGE->requires->css(new \moodle_url('https://cdn.datatables.net/buttons/2.3.3/css/buttons.bootstrap4.min.css'));
    $PAGE->requires->css(new \moodle_url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css'));
    $PAGE->requires->css(new \moodle_url('https://cdn.datatables.net/select/1.5.0/css/select.dataTables.min.css'));

    // Chartsjs.
    $PAGE->requires->js(new \moodle_url('https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.min.js'), true);

    // From modul.
    $PAGE->requires->js('/mod/rateit/amd/jquery.js');
    $PAGE->requires->js('/mod/rateit/amd/module.js');
    $PAGE->requires->js('/mod/rateit/amd/charts.js');

    require_login();
if (isguestuser()) {
    throw new moodle_exception('noguest');
}

    $canmanage = has_capability('mod/rateit:addinstance', $modulecontext);
    $canview = has_capability('mod/rateit:view', $modulecontext);
    $canread = has_capability('mod/rateit:readresponses', $modulecontext);

    echo $OUTPUT->header();
if ($canread) {
    $ru = ranking_user();

    if ($ru == null) {
        \core\notification::add(get_string('record', 'mod_rateit'), \core\output\notification::NOTIFY_WARNING);
    } else {
        $ru = ranking_user();
        if ($ru == null) {
            \core\notification::add(get_string('record', 'mod_rateit'), \core\output\notification::NOTIFY_WARNING);
        } else {
            $courseselected = 0;
            $messages = get_message_from_result($ru);
            $arr = (array)$messages;
            $output = "";
            $output .= table_rateit($messages, $arr);
            $output .= html_writer::start_tag('div', [
                'id' => 'buttonbox',
                'class' => 'mx-2 d-flex justify-content-center align-items-center'
            ]);
            $output .= html_writer::tag('label', get_string('exportTo', 'rateit'), ['class' => 'mb-0 mr-4']);
            $output .= html_writer::start_tag('div', ['id' => 'insertbuttonbox']);
            $output .= html_writer::end_tag('div');
            $output .= html_writer::end_tag('div');

            // -----------------------------------------
            $output .= html_writer::start_tag('div', ['id' => 'fullAvg', 'class' => 'mt-6 d-flex align-items-center']);
            $output .= html_writer::tag('label', get_string('avgDisplayfull', 'rateit'), ['class' => 'mb-0']);
            $output .= html_writer::start_tag('div', ['id' => 'avgResultFull', 'class' => 'mx-2']);
            $output .= html_writer::end_tag('div');
            $output .= html_writer::tag('small', get_string('estatic', 'rateit'), ['class' => 'text-secondary']);
            $output .= html_writer::end_tag('div');

            // -----------------------------------------
            $output .= html_writer::start_tag('div', ['id' => 'myAvg', 'class' => 'd-flex align-items-center']);
            $output .= html_writer::tag('label', get_string('avgDisplay', 'rateit'), ['class' => 'mb-0']);
            $output .= html_writer::start_tag('div', ['id' => 'avgResult', 'class' => 'mx-2 ']);
            $output .= html_writer::end_tag('div');
            $output .= html_writer::tag('small', get_string('dinamic', 'rateit'), ['class' => 'text-secondary']);
            $output .= html_writer::end_tag('div');

            // -----------------------------------------
            $output .= html_writer::start_tag('div', ['height' => '200px', 'class' => 'rounded']);
            $output .= html_writer::tag('h4', get_string('titleBar', 'rateit'), [
                'class' => 'titol my-4 d-flex justify-content-center'
            ]);
            $output .= html_writer::start_tag(
                'canvas',
                ['id' => 'myChart',
                'class' => 'mt-3 ',
                'width' => "400",
                "height" => "100"]
            );
            $output .= html_writer::end_tag('canvas');

            $output .= html_writer::start_tag('div', ['id' => 'download', 'class' => 'd-flex  align-items-center']);
            $output .= html_writer::tag('label', get_string('exportTo', 'rateit'), ['id' => 'exportimgs']);
            $output .= html_writer::tag('i', '', [
                'class' => 'fa-solid fa-file-image text-dark mx-2',
                'id' => 'downloadpng',
                'alt' => 'png_pie'
            ]);
            $output .= html_writer::end_tag('div');
            $output .= html_writer::end_tag('div');

            // -----------------------------------------
            $output .= html_writer::start_tag('div', ['height' => '200px', 'class' => 'mt-6']);
            $output .= html_writer::tag('h4', get_string('titlePie', 'rateit'), [
                'class' => 'titol my-4 d-flex justify-content-center'
            ]);

            $output .= html_writer::start_tag('canvas', ['id' => 'myPie', 'class' => 'mt-3', 'width' => "400", "height" => "100"]);
            $output .= html_writer::end_tag('canvas');

            $output .= html_writer::start_tag('div', ['id' => 'downloadpie', 'class' => 'd-flex  align-items-center']);
            $output .= html_writer::tag('label', get_string('exportTo', 'rateit'), ['id' => 'exportimgs2']);
            $output .= html_writer::tag('i', '', [
                'class' => 'fa-solid fa-file-image text-dark mx-2',
                'id' => 'downloadpngpie',
                'alt' => 'pngPie'
            ]);
            $output .= html_writer::end_tag('div');
            $output .= html_writer::end_tag('div');

            echo $output;
        }
    }
}

    echo $OUTPUT->footer();

