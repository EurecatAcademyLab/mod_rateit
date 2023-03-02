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
 * Display auxiliar function and DB conections.
 */


defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot. '/config.php');
require_login();

/**
 * To get the whole record from rateit_users.
 * @return Array of Objects.
 */
function ranking_user() {
    global $DB;
    $result = $DB->get_records_sql("SELECT * FROM {rateit_users} ru");
    return $result;
}

/**
 * To know is the rateit_users is empty or how many entries have.
 * @return Int from count.
 */
function count_rankinguser() {
    global $DB;
    $result = $DB->count_records_sql("SELECT count(id) FROM {rateit_users} ru");
    $result = intval($result);
    return $result;
}

/**
 * To get message from rate it users/ unused.
 * @param Array $courseid with the course id.
 * call count ranking function to know if is empty.
 * call update db function to know if is empty.
 * @return Array from rateit_user table.
 */
function get_message($courseid) {
    global $DB;
    updatedb();

    $limit = count_rankinguser();

    if (is_null($courseid) || $courseid == 0) {
        $result = $DB->get_records_sql("SELECT * FROM {rateit_users} ru ORDER BY ru.rating LIMIT $limit");
    } else {
        $sql = "SELECT * FROM {rateit_users} ru WHERE ru.course_id = ? ORDER BY ru.rating LIMIT '.$limit.';";
        $result = $DB->get_records_sql($sql , array($courseid));
    }

    return $result;
}

/**
 * To get the name and id of a module.
 * @param String $previousid with the previous activity id.
 * @return Object from module table.
 */
function type($previousid) {
    // Global variable $DB.
    global $DB;
    $sql = 'SELECT md.name, md.id FROM {course_modules} cm JOIN {modules} md ON md.id = cm.module WHERE cm.id = ?';
    $type = $DB->get_record_sql($sql, array($previousid));
    return $type;
}

/**
 * To get the officialname of a user.
 * @param String $userid with the user id.
 * @return Object from user table.
 */
function username($userid) {
    global $DB;
    $user = decriptid($userid);

    $username = $DB->get_record_sql('SELECT concat(u.firstname," ",u.lastname) as officialname FROM {user} u WHERE u.id = '.$user);
    return $username;
}

/**
 * To get the name of the actual module from rate it.
 * @param Int $aid with the actual module id.
 * @return Object from rateit table.
 */
function actual($aid) {
    global $DB;
    $sql = "SELECT r.name FROM {course_modules} c
    JOIN {modules} md ON md.id = c.module
    JOIN {rateit} r ON r.id = c.instance WHERE c.id = ?";
    $actual = $DB->get_record_sql($sql, array($aid));
    return $actual;
}

/**
 * To get the data before send it to the table.
 * @param Array $result with users which have made the rate.
 * @return Object with data defore datatable.
 */
function get_message_from_result($result) {
    $tabletoprint = new stdClass;
    foreach ($result as $m) {

        $id = $m->id;
        if ($m->anonim == 0) {
            $username = username($m->user_id);
            $userprint = $username->officialname;
        } else {
            $username = 'Anonymous user';
            $userprint = $username;
        }

        $type = type($m->previous_id);

        $previousact = get_coursemodule_from_id(strval($type->name), $m->previous_id, 0, false, MUST_EXIST);

        $actual = actual($m->actual_id);

        $course = get_course($m->course_id);
        $time = date ("j.m.Y G:i:s", $m->timecreated);

        $tabletoprint->$id = [
            "id" => $m->id,
            "PreviousId" => $m->previous_id,
            "Title" => $previousact->name,
            "Type" => $type->name,
            "Rating" => $m->rating,
            "Message" => $m->comments,
            "UserId" => $m->user_id,
            "User" => $userprint,
            "CourseId" => $m->course_id,
            "CourseName" => $course->fullname,
            "RateitId" => $m->actual_id,
            "Rateit" => $actual->name,
            "anonim" => $m->anonim,
            "Time" => $time,
            "Timecreated" => $m->timecreated
        ];
    }
    return $tabletoprint;
}

/**
 * To get the name of users after decript it.
 * @param String $data with encript data.
 * @return String with data after decrip.
 */
function decriptid($data) {
    $ciphering = "AES-256-CTR";
    $option = 0;
    $encryptioniv = '1234567890123456';
    $encryptionkey = 'rateit';
    $decryption = openssl_decrypt($data, $ciphering, $encryptionkey, $option, $encryptioniv);
    return $decryption;
}

/**
 * To update rateit users table
 * @return Void.
 */
function updatedb() {
    global $DB;
    $update = $DB->execute_sql("UPDATE {rateit_users}");
}

