/* eslint-disable no-undef */
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
 * * Javascript / Datatablelibrary.
 */

// require(['core/first', 'jquery', 'jqueryui', 'core/ajax'], function(_core, $, _bootstrap, _ajax) {
// $(document).ready(function() {

    $('#mytableform').DataTable({

    responsive: true,
    dom: 'Bfrtip',
    // "pageLength": 2,
    lengthMenu: [
        [10, 25, 50, -1],
        [10, 25, 50, 'All'],
    ],


    buttons: {
        name: 'Download',
        buttons: [
            {
                extend: 'excelHtml5',
                text:  '<i class= "fa-solid fa-file-excel"></i>',
                titleAttr:  'Export to Excel',
                className:  'btn btn-light'
            }, {
                extend: 'csv',
                text:  '<i class= "fa-solid fa-file-csv"></i>',
                titleAttr:  'Export to Csv',
                className:  'btn btn-light'
            }, {
                extend: 'pdfHtml5',
                text:  '<i class= "fa-solid fa-file-pdf"></i>',
                titleAttr:  'Export to PDF',
                className:  'btn btn-light'
            }, {
                extend: 'print',
                text:  '<i class= "fa-solid fa-print"></i>',
                titleAttr:  'Print',
                className:  'btn btn-light'
            }, {
                extend: 'copy',
                text:  '<i class= "fa-solid fa-clipboard"></i>',
                titleAttr:  'copy to Clipboard',
                className:  'btn btn-light'
            }
        ]
    },

});

$('.dataTables_length').addClass('bs-select');

var table = $('#mytableform').DataTable();

table
    .buttons()
    .container()
    .insertAfter('#insertbuttonbox');

