# Rate it!! #

Most of the rating modules refer to courses, but this proposal is intended for a rating by the activities included within each course, having more possibility to know in depth how each of them is being developed.
<p align="center"><a href="https://github.com/JCsomeShots/rateit" target="_blank"><img src="https://raw.githubusercontent.com/JCsomeShots/rateit/main/assets/rateitLabel.png" width="200"></a></p>

# More detailed description. #

The module is located within the course activities, the creation follows the same steps of any standard activity. The valuation takes a range of 5 points, having the medium or neutral value.

The module allows customization in the settings.

- The range labels can be customizable.<br>
<p align="center"><a href="https://github.com/JCsomeShots/rateit" target="_blank"><img src="https://raw.githubusercontent.com/JCsomeShots/rateit/main/assets/rateitSettings.png" width="200"></a></p>
- The scale can be displayed from right to left or vice versa, using the *Likert scale as a reference. <br>
- The rating can only be done once, with no option to change it.<br>
- Surveys can be anonymous.<br>
<p align="center"><a href="https://github.com/JCsomeShots/rateit" target="_blank"><img src="https://raw.githubusercontent.com/JCsomeShots/rateit/main/assets/rateitAnonymous.png" width="200"></a></p>
- Students can attach a comment if they wish to do so. <br><br>
<br><br>
The reception of all this information is displayed in a table, with visualizations exclusive to the director or the teacher who manages the survey, where the most relevant data appear.
Course
Activity
User (or the hashtag "anonymous" if applicable)
Rating
Comments
Creation date<br>

-- <div align="center"><a href="https://github.com/JCsomeShots/rateit" target="_blank"><img src="https://raw.githubusercontent.com/JCsomeShots/rateit/main/assets/tableAndHorizontalbarPic.png"></a><a href="https://github.com/JCsomeShots/rateit" target="_blank"><img src="https://raw.githubusercontent.com/JCsomeShots/rateit/main/assets/areaPic.png"></a></div><br>

<p align="center"><a href="https://github.com/JCsomeShots/rateit" target="_blank"><img src="https://raw.githubusercontent.com/JCsomeShots/rateit/main/assets/dinamicTable&HorizontalBar.gif"></a></p><br>

An average is generated, either by course, activity, user (except in the case of anonymous users).

In this way it is possible to reinforce those activities that generate the lowest scores, or copy methodologies from those with the highest scores.



## Installing via uploaded ZIP file ##

1. Log in to your Moodle site as an admin and go to _Site administration >
   Plugins > Install plugins_.
2. Upload the ZIP file with the plugin code. You should only be prompted to add
   extra details if your plugin type is not automatically detected.
3. Check the plugin validation report and finish the installation.

## Installing manually ##

The plugin can be also installed by putting the contents of this directory to

    {your/moodle/dirroot}/mod/rateit

Afterwards, log in to your Moodle site as an admin and go to _Site administration >
Notifications_ to complete the installation.

Alternatively, you can run

    $ php admin/cli/upgrade.php

to complete the installation from the command line.

## License ##

2022 JuanCa  <juancarlo.castillo20@gmail.com>

This program is free software: you can redistribute it and/or modify it under
the terms of the GNU General Public License as published by the Free Software
Foundation, either version 3 of the License, or (at your option) any later
version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with
this program.  If not, see <https://www.gnu.org/licenses/>.
