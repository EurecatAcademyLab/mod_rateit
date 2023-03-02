/* eslint-disable no-unused-vars */
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
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 *  Jquery.
 */

require(['core/first', 'jquery', 'jqueryui', 'core/ajax'], function(_core, $, _bootstrap, _ajax) {

    // -----------------------------
    $(document).ready(function() {

        // Use jquery to select and add css styles.
        $('#id_namecontainer').prev().children().css({"color": "grey", "margin-bottom": "30px"});

        $('#id_namecontainer').prev().children(":first").css({"margin-left": "120px"});

        $('form').css("padding-bottom", "50px");
        $('form input[type=radio]').css({"padding-top": "120px"});

        $('#mytableform').css({'margin-top': '50px'});

    });
});