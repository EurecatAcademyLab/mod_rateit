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

require_once('../../config.php');
require_login();

/**
 * To define the output result table.
 * @param object $messages get the info before print.
 * @param array $arr Constant to get the length of $messages.
 * @return Object table.
 */
function table_rateit($messages , $arr) {
    global $CFG;

    $table = new html_table();

    if (empty($arr)) {
        \core\notification::add(get_string('record_course', 'mod_rateit'), \core\output\notification::NOTIFY_WARNING);
    } else {
        $table->id = 'mytableform';
        $table->class = 'table table-striped table-bordered table-sm';
        $table->width = '100%';
        $table->align = ['justify'];
        $table->caption = get_string('titletable', 'mod_rateit');
        $table->cellspacing = 50;
        $table->colclasses = array(null, 'grade');

        $table->head = array(
            get_string('prev_act', 'mod_rateit'),
            get_string('prev_act_type', 'mod_rateit'),
            get_string('rating', 'mod_rateit'),
            get_string('comment', 'mod_rateit'),
            get_string('user', 'mod_rateit'),
            get_string('course', 'mod_rateit'),
            get_string('timecreated', 'mod_rateit'),
        );

        foreach ($messages as $item => $e) {
            $route = $CFG->wwwroot;
            $eci = $e['CourseId'];
            $ecn = $e['CourseName'];
            $eui = $e['UserId'];
            $eu = $e['User'];
            $ety = strval($e['Type']);
            $ep = $e['PreviousId'];
            $eti = $e['Title'];

            $class01 = 'class="text-info" target="_blank"';
            $course = '<a href="'.$route.'/course/view.php?id='.$eci.'" '.$class01.'><strong>'. $ecn .'</strong></a>';

            if ($e['anonim'] != 0) {
                $user = 'Anonymous';
            } else {
                $ru = $route.'/user/profile.php?id='.$eui;
                $user = '<a href="'.$ru.'" class="text-info" target="_blank"><strong>'.$eu.'</strong></a>';
            }
            $rp = $route.'/mod/'.$ety.'/view.php?id='.$ep;
            $previous = '<a href="'.$rp.'" class="text-info" target="_blank"><strong>'.$eti.'</strong></a>';

            $table->data[] = array(
                $previous,
                $e['Type'],
                $e['Rating'],
                $e['Message'],
                $user,
                $course,
                $e['Time']
            );

        }
    }

    return html_writer::table($table);
}


