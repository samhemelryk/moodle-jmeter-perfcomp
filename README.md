Moodle performance comparison tool.
===================================

This is a development tool, created for the sole purpose of helping me investigate performance issues
and prove the performance impact of significant changes in code.
It is provided in the hope that it will be useful to others but is provided without any warranty,
without even the implied warranty of merchantability or fitness for a particular purpose.
This code is provided under GPLv3 or at your discretion any later version.

About this tool
---------------
This tool is really four tools packaged together for convenience.

* A JMeter projet that can be opened within JMeter and run from there, or preferably run from the command line. When run it generates a "run" file that contains the performance information.
* A PHP tool that compares two of the "run" files displaying the information both summarised and as graphs.
* A Moodle theme to install within Moodle that then embeds the git branch in the bottom of the page to give more descriptive "run" file names.
* An initialisation script to be run within Moodle that generates the structure and content required by the JMeter project (creates users, course, enrollments).

Obviously the JMeter project requries JMeter to be installed. You can read more about this here: http://jmeter.apache.org/usermanual/get-started.html
The PHP tool requires that PHP be installed and this tool accessible by the webserver.
The theme and initialisation script require a Moodle installation (that has not been otherwise used).

This tool has been used with _Moodle 2_ (2.0, 2.1, 2.2, 2.3). It may work with other versions if you are lucky.
Future versions: It has a high chance of working without anything requiring updating.
Previous versions: It will not work without huge modifications.

To use:
-------
1.  Download this tool and extract it to a web accessible directory
2.  Copy the file moodle/perfcomp.init.php to the root of a moodle directory that has not been installed.
3.  Copy the directory moodle/perfcomp to the theme directory for that same site.
4.  Install a fresh version of Moodle. As soon as installation finishes and you've set up the admin and site name stop using it.
5.  Copy moodle/perfcomp.init.php to your Moodle directory.
6.  Browse to your site and make sure you are logged in as an admin
7.  Browse to /perfcomp.init.php. This will create users, cohorts, courses, and a forum.
8.  Change the theme for the site to perfcomp.
9.  Turn on perfdebug in the admin settings (just search it)
10. Log out.
11. Log in as teacher01 / teacher01 (username / password)
12. Browse to Performance comparison course and into Performance test forum
13. Add a new discussion with any old subject and content.
14. Log out.
15. Setup is now complete

To run:
-------
1.  In a terminal cd to tools directory and execute the following command (it will take some time).
2.  jmeter -n -t testplan-simple.jmx -Jhost=localhost -Jusers=1 -Jloops=1 -Jdesc="Default master"
    * _host=s_ The URL to run the test script against _required_
    * _users=n_ is the number of users to simulate simultaneously (20 max, 10 by default)
    * _loops=n_ The number of times to rinse and repeat (no max, 30 by default)
    * _desc=s_ A short description for the run (string)
    * _rampup=n_ The ramp up period in seconds (3 by default)
    * _throughput=n_ The target throughput (samples per second)
    * _repeats=n_ The number of times a user repeats the sequence in a single loop
    * _runfile=n_ The name to use for the run file. Defaults to the branch and a timestamp
3.  Change your Moodle setup however you want and run the command again.
4.  In your browser pull up the perf tool.
5.  Select the two branches to compare and go for gold.

Bugs, issues, and other such guff
---------------------------------
Please report any bugs within the project on github. https://github.com/samhemelryk/moodle-jmeter-perfcomp/issues
I will of course endeavour to look into any reported bugs however I don't have much personal time to spare these days so it may take a while for me to get to things.
Alternatively feel free to produce the fix yourself, if you do please share it back so that I can merge it into the main project and others can benefit.

Before reporting any bugs please check the settings on your Moodle site.
They can play a big role in how things work, and of course are an easy fix.
The following table illustrates the settings and their expected values:

    // Required.
    $CFG->themedesignermode = 0; // Can be turned on but not indicative of a production server.
    $CFG->cachejs = 1; // Better representation of client timing with this on.
    $CFG->langstringcache = 1; // Can be turned off but again not indicative of a production server.
    $CFG->perfdebug = 15; // Absolutely required. This is how we get performance readings from the server.
    $CFG->passwordpolicy = false; // Passwords created by init script are weak an designed to be predictable.

    // Set by the init scripts but optional.
    $CFG->enableoutcomes = 1;
    $CFG->enableportfolios = 1;
    $CFG->enablewebservices = 1;
    $CFG->enablestats = 1;
    $CFG->enablerssfeeds = 1;
    $CFG->enablecompletion = 1;
    $CFG->enableavailability = 1;
    $CFG->enableplagiarism = 1;
    $CFG->enablecssoptimiser = 1;
    $CFG->allowthemechangeonurl = 1;
    $CFG->debugpageinfo = 1;


Things to know
--------------
1. This tool can take an exceptionally long time to run.

2. The user log ins within the JMeter project are pretty average. User details are stored in a CSV file and used consecutively.
   I haven't worked out how to randomise the data coming from a CSV, although I haven't really put any time into it either.
   If you're passionate about it and come up with a solution please share it as I would like to have randomised users as an option.

