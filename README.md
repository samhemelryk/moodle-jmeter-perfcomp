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
* An initialisation script to be run within Moodle that generates the structure and content required by the JMeter project (creates users, course, enrolments).

Obviously the JMeter project requries JMeter to be installed. You can read more about this here: http://jmeter.apache.org/usermanual/get-started.html
The PHP tool requries that PHP be installed and this tool accessible by the webserver.
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
    * _users=n_ is the number of users to simulate simulaneously (20 max, 10 by default)
    * _loops=n_ The number of times to rinse and repeat (no max, 30 by default)
    * _desc=s_ A short description for the run (string)
    * _rampup=n_ The ramp up period in seconds (3 by default)
    * _throughput=n_ The target throughput (samples per second)
    * _repeats=n_ The number of times a user repeats the sequence in a single loop
3.  Change your Moodle setup however you want and run the command again.
4.  In your browser pull up the perf tool.
5.  Select the two branches to compare and go for gold.

Things to know
--------------
1. This tool can take an exceptionally long time to run.

2. When running the JMeter tool you may notice that you get two run files each time you run the project. One names properly, and one named unknown.
   This occurs because the page that displays the confirmation message after the user posts to a forum does not use the standard footer, and thus
   the branch name cannot be found in the page and the default file name "unknown" is used.

3. The user log ins within the JMeter project are pretty average. User details are stored in a CSV file and used consecutively.
