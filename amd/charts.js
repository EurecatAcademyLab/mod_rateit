/* eslint-disable no-unused-vars */
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
 * * Javascript / jquery / Datatablelibrary / chartsjs library
 */

$(document).ready(function() {

// Initialize Datatable library.
var table = $('#mytableform').DataTable();

// Invoke average functions and charts.
printaverage();
fullprintaverage();
chartDisplay();
averagecolumn();

/**
 * On each search table use, update the data in the chart
 * @returns {void}
 */
table.on('draw', function() {
    printaverage();
    chartDisplay();
    fullprintaverage();
});

/**
 * Average from the second colomn for each first column (title).
 * @returns {Object} the result from average.
 */
function averagecolumn() {
    let avg = {};
    let columnname = table.column(0, {search: 'applied'}).data();
    let columnvalue = table.column(2, {search: 'applied'}).data();
    for (var i = 0; i < columnname.length; i++) {
        var regex = /(\d+)/g;

        if (columnname[i]) {
            avg[printtopic(columnname[i])] += columnvalue[i];
            avg[printtopic(columnname[i])] = avg[printtopic(columnname[i])].match(regex);
            avg[printtopic(columnname[i])] = parseInt(avg[printtopic(columnname[i])]);
        } else {
            avg[printtopic(columnname[i])] = columnvalue[i];
            avg[printtopic(columnname[i])] = avg[printtopic(columnname[i])].match(regex);
            avg[printtopic(columnname[i])] = parseInt(avg[printtopic(columnname[i])]);
        }
    }

    var values = Object.values(avg);
    var avgcolumn = [];
    for (var j = 0; j < values.length; j++) {
        var y = 0;
        var x = String(values[j]);
        // eslint-disable-next-line no-loop-func, no-return-assign
        x.split('').forEach(x => y += parseInt(x));
        y = y / x.length;
        avgcolumn.push(y);
    }
    return avgcolumn;
}

/**
 * Average from the second colomn | dinamic value.
 * @returns {string} the result from average.
 */
function average() {
    let column = table.column(2, {search: 'applied'}).data();
    var count = 0;
    for (var $i = 0; $i < column.length; $i++) {
        count += parseInt(column[$i]);
    }
    count = count / column.length;
    count = count.toFixed(1);
    return count;
}

/**
 * Print the result of average function() under the table.
 * @returns {void}
 */
function printaverage() {
    let count = average();
    if (isNaN(count)) {
        $('#avgResult').html('No value');
    } else {
        $('#avgResult').html('');
        $('#avgResult').html('<strong>' + count + '</strong>');
    }
}

/**
 * Print the result of fullprintaverage function() under printaverage().
 * Estatic value result from $avg.
 * @returns {void}
 */
function fullprintaverage() {
    count = average();
    var $avg = table.column(2).data().average();
    $avg = $avg.toFixed(1);
    if (isNaN(count)) {
        $('#avgResultFull').html('No value');
    } else {
        $('#avgResultFull').html('');
        $('#avgResultFull').html('<strong>' + $avg + '</strong>');
    }
}

/**
 * Change the data from a dinamic table.
 * @returns {string} first column
 * @returns {int} rating column
 */
function chartData() {
    var counts = {};
    // Count the number of entries for each position
    table
        .column(0, {search: 'applied'})
        .data()
            .each(function(val) {
            if (counts[val]) {
                counts[val] += 1;
            } else {
                counts[val] = 1;
            }
        });
    return $.map(counts, function(val, key) {
        return {
            name: key,
            y: val,
        };
    });
}

/**
 * Create a temporal div and strip the html tag.
 * @param {string} name with a internal anchor reference.
 * @returns {Object} html object.
 */
function printtopic(name) {
    var elementhtml = [];
    var temporalDivElement = [];
    temporalDivElement = document.createElement("div");
    temporalDivElement.innerHTML = name;
    elementhtml.push(temporalDivElement.innerText);
    return elementhtml;
}

/**
 * Create an Object to display it like a value in charts.
 * @returns {Object}
 */
function topic() {
    var $topics = chartData(table);
    let max = $topics.length;
    var $topic = [];
    for (var i = 0; i < max; i++) {
        $topic[i] = printtopic($topics[i].name);
    }
    return $topic;
}

/**
 * Create an Object to display it like a value in charts.
 * @returns {Object}.
 */
function asistantpie() {
    var $topics = chartData(table);
    max = $topics.length;
    var $topic = [];
    for (var i = 0; i < max; i++) {
        $topic[i] = $topics[i].y;
    }
    return $topic;
}

/**
 * Create a loop with 6 colours / palette colour.
 * @returns {Array} color palette.
 */
function hexadecimalColors() {
    var $topics = chartData(table);
    max = $topics.length;
    var colors = [
        'rgba(255, 26, 104, 0.2)',
        'rgba(54, 162, 235, 0.2)',
        'rgba(255, 206, 86, 0.2)',
        'rgba(75, 192, 192, 0.2)',
        'rgba(153, 102, 255, 0.2)',
        'rgba(255, 159, 64, 0.2)'
        ];
        if (colors.length < max) {
            colors += colors;
        }
    return colors;
}

/**
 * Create a loop with 6 colours / palette colour.
 * @returns {Array} color palette.
 */
function hexadecimalborderColors() {
    var $topics = chartData(table);
    max = $topics.length;
    var colors = [
        'rgba(255, 26, 104, 1)',
        'rgba(54, 162, 235, 1)',
        'rgba(255, 206, 86, 1)',
        'rgba(75, 192, 192, 1)',
        'rgba(153, 102, 255, 1)',
        'rgba(255, 159, 64, 1)',
        ];
        if (colors.length < max) {
            colors += colors;
        }
    return colors;
}

/**
 * Create an Object to display like a data in const Line 1.
 * @returns {Object}.
 */
function avgrateit() {
    var $loops = chartData();
    max = $loops.length;
    var $loop = [];
    for (var i = 0; i < max; i++) {
        $loop[i] = average();
    }
    return $loop;
}

/**
 * Create and display charts belonging to the table.
 * @returns {void}
 */
function chartDisplay() {
    var ctx = document.getElementById('myChart').getContext('2d');
    var ctx2 = document.getElementById('myPie').getContext('2d');

    const line1 = {
        label: 'Rateit! average',
        data: avgrateit(),
        backgroundColor: ['rgba(0,0,0,0.2)'],
        borderColor: ['rgba(0,0,0,1)'],
        borderWidth: 1,
        pointStyle: 'dash',
    };
    const line2 = {
        label: 'Table average',
        data: averagecolumn(),
        backgroundColor: ['rgba(0,255,0,0.2)'],
        borderColor: ['rgba(0,255,0,1)'],
        borderWidth: 1,
        pointStyle: 'dash',
    };
    const score = {
        label: 'Score each activity',
        data: averagecolumn(),
        backgroundColor: hexadecimalColors(),
        borderColor: hexadecimalborderColors(),
        borderWidth: 1
    };

     // Data Bar.
    const data2 = {
        labels: topic(),
        datasets: [
            score,
            line1,
        ]
    };

    // Block horizontalDottedLine.
    // eslint-disable-next-line no-unused-vars
    const horizontalDottedLine = {
        id: 'horizontalDottedLine',
        // eslint-disable-next-line no-unused-vars
        beforeDatasetsDraw(chart, args, options) {
            // eslint-disable-next-line no-unused-vars
            const {ctx, chartArea: {top, right, bottom, left, width, height},
            scales: {x, y}} = chart;
            ctx.save();

            // Draw line.
            ctx.setLineDash([10, 5]);
            ctx.strokeStyle = 'red';
            ctx.strokeRect(700, top, 1, bottom);
            ctx.restore();
            // getPixelForTick()
        }
    };

    // Polar Area Chart.
    new Chart(ctx2, {
        type: 'polarArea',
        data: {
            labels: topic(),
            datasets: [{
                label: 'Student asistent',
                data: asistantpie(),
                backgroundColor: hexadecimalColors(),
                borderColor: hexadecimalborderColors(),
                borderWidth: 1,
                hoverOffset: 4
            }]
        }
    });

    // Horizontal Bar Chart.
    new Chart(ctx, {
        type: 'horizontalBar',
        data: data2,
        options: {
            scales: {
                xAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        },
    });
}

/**
 * Function to download a png from canvas chart horizontal
 * @returns {void}
 */
$('#downloadpng').on('click', function() {
    const imageLink = document.createElement('a');
    const canvas = document.getElementById('myChart');
    imageLink.download = 'canvas.png';
    imageLink.href = canvas.toDataURL('image/png', 1);
    imageLink.click();
});

/**
 * Function to download a png from canvas chart Pie
 * @returns {void}
 */
$('#downloadpngpie').on('click', function() {
    const imageLink = document.createElement('a');
    const canvas = document.getElementById('myPie');
    imageLink.download = 'canvas.png';
    imageLink.href = canvas.toDataURL('image/png', 1);
    imageLink.click();
});

/**
 * Function to download a pdf from canvas | unused.
 * @returns {void}
 */
$('#downloadpdf').on('click', function() {
    const pdfChart = document.getElementById('myChart');
    const canvasImage = pdfChart.toDataURL('image/jpeg', 1);
    let pdf = new JsPDF();
    pdf.setFontSize(20);
    pdf.addImage(canvasImage, 'JPEG', 15, 15, 280, 150);
    pdf.save('rateit_record.pdf');

});

});