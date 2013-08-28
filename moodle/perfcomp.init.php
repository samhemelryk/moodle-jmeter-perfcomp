<?php
/**
 * Moodle data initialisation script for the performance comparison tool.
 *
 * This is a development tool, created for the sole purpose of helping me investigate performance issues
 * and prove the performance impact of significant changes in code.
 * It is provided in the hope that it will be useful to others but is provided without any warranty,
 * without even the implied warranty of merchantability or fitness for a particular purpose.
 * This code is provided under GPLv3 or at your discretion any later version.
 *
 * @package moodle-jmeter-perfcomp
 * @copyright 2012 Sam Hemelryk (blackbirdcreative.co.nz)
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define('_MANAGERS_', 5);
define('_TEACHERS_', 32);
define('_STUDENTS_', 500);
define('_TEACHERCOHORTS_', 8);
define('_STUDENTCOHORTS_', 20);
define('_CATEGROIES_', 8);
define('_SUBCATEGROIES_', 5);
define('_SUBSUBCATEGROIES_', 3);
define('_COURSES_', 150);

require_once('config.php');
require_once($CFG->dirroot.'/course/lib.php');
require_once($CFG->dirroot.'/cohort/lib.php');
require_once($CFG->dirroot.'/user/lib.php');
require_once($CFG->dirroot.'/lib/enrollib.php');
require_once($CFG->dirroot.'/enrol/locallib.php');
require_once($CFG->dirroot.'/enrol/cohort/locallib.php');
require_once($CFG->dirroot.'/group/lib.php');
require_once($CFG->dirroot.'/mod/forum/lib.php');
require_once($CFG->libdir.'/filelib.php');
require_once($CFG->libdir.'/gradelib.php');
require_once($CFG->libdir.'/completionlib.php');
require_once($CFG->libdir.'/conditionlib.php');
require_once($CFG->libdir.'/plagiarismlib.php');

$release = 2.5;
if (preg_match('#^(\d+\.\d+)(\.(\d+))?#', $CFG->release, $matches)) {
    $release = floatval($matches[1].$matches[3]);
}

$PAGE->set_context(get_system_context());
$PAGE->set_url('/init.php');

if (!empty($CFG->custominitexecuted)) {
    notice('Custom initialisation has already been executed');
}

echo $OUTPUT->header();

echo sprintf('<h1>Generating for Moodle %f</h1>', $release);
echo sprintf('<p>Creating %d managers</p>', _MANAGERS_);
echo sprintf('<p>Creating %d teachers</p>', _TEACHERS_);
echo sprintf('<p>Creating %d students</p>', _STUDENTS_);
echo sprintf('<p>Creating %d teacher cohorts</p>', _TEACHERCOHORTS_);
echo sprintf('<p>Creating %d student cohorts</p>', _STUDENTCOHORTS_);
echo sprintf('<p>Creating %d categories</p>', _CATEGROIES_);
echo sprintf('<p>Creating %d sub categories</p>', _SUBCATEGROIES_);
echo sprintf('<p>Creating %d sub sub categories</p>', _SUBSUBCATEGROIES_);
echo sprintf('<p>Creating %d courses</p>', _COURSES_);
echo '<hr />';
@flush();

$managercohort = new stdClass;
$managercohort->contextid = SYSCONTEXTID;
$managercohort->name = 'Managers';
$managercohort->idnumber = 'managers';
$managercohort->id = cohort_add_cohort($managercohort);
echo '<p>Management cohort created</p>';@flush();

$teachercohorts = array();
for ($i = 0; $i < _TEACHERCOHORTS_; $i++) {
    $teachercohorts[$i] = new stdClass;
    $teachercohorts[$i]->contextid = SYSCONTEXTID;
    $teachercohorts[$i]->name = 'Teachers '.($i+1);
    $teachercohorts[$i]->idnumber = 'teachers'.($i+1);
    $teachercohorts[$i]->id = cohort_add_cohort($teachercohorts[$i]);
    echo '<p>Teacher cohort created ('.($i+1).')</p>';@flush();
}

$studentcohorts = array();
for ($i = 0; $i < _STUDENTCOHORTS_; $i++) {
    $studentcohorts[$i] = new stdClass;
    $studentcohorts[$i]->contextid = SYSCONTEXTID;
    $studentcohorts[$i]->name = 'Students '.($i+1);
    $studentcohorts[$i]->idnumber = 'students'.($i+1);
    $studentcohorts[$i]->id = cohort_add_cohort($studentcohorts[$i]);
    echo '<p>Student cohort created ('.($i+1).')</p>';@flush();
}

for ($i = 0; $i < _MANAGERS_; $i++) {
    $username = 'manager';
    if ($i < 10) {
        $username .= '0';
    }
    $username .= $i;
    $manager = create_user_record($username, $username);
    cohort_add_member($managercohort->id, $manager->id);

    $manager->firstname = 'Manager ('.$i.')';
    $manager->lastname = 'User';
    $manager->idnumber = $i;
    $manager->city = 'Nelson';
    $manager->email = 'manager.'.$i.'@local.host';
    $manager->country = 'NZ';
    $DB->update_record('user', $manager);
    echo '<p>Manager '.($i+1).' created</p>';@flush();
}

for ($i = 0; $i < _TEACHERS_; $i++) {
    $username = 'teacher';
    if ($i < 100) {
        $username .= '0';
    } else if ($i < 10) {
        $username .= '00';
    }
    $username .= $i;
    $teacher = create_user_record($username, $username);
    cohort_add_member($teachercohorts[array_rand($teachercohorts)]->id, $teacher->id);

    $teacher->firstname = 'Teacher ('.$i.')';
    $teacher->lastname = 'User';
    $teacher->idnumber = $i;
    $teacher->city = 'Nelson';
    $teacher->email = 'teacher.'.$i.'@local.host';
    $teacher->country = 'NZ';
    $DB->update_record('user', $teacher);
    
    echo '<p>Teacher '.($i+1).' created</p>';@flush();
}

for ($i = 0; $i < _STUDENTS_; $i++) {
    $username = 'student';
    if ($i < 100) {
        $username .= '0';
    } else if ($i < 10) {
        $username .= '00';
    }
    $username .= $i;
    $student = create_user_record($username, $username);
    cohort_add_member($studentcohorts[array_rand($studentcohorts)]->id, $student->id);

    $student->firstname = 'Student ('.$i.')';
    $student->lastname = 'User';
    $student->idnumber = $i;
    $student->city = 'Nelson';
    $student->email = 'student.'.$i.'@local.host';
    $student->country = 'NZ';
    $DB->update_record('user', $student);
    
    echo '<p>Student '.($i+1).' created</p>';@flush();
}

$menu = 'Moodle community|http://moodle.org
-Moodle free support|http://moodle.org/support
-Moodle development|http://moodle.org/development
--Moodle Tracker|http://tracker.moodle.org
--Moodle Docs|http://docs.moodle.org
-Moodle News|http://moodle.org/news
Moodle company
-Moodle commercial hosting|http://moodle.com/hosting
-Moodle commercial support|http://moodle.com/support';

// Required settings
set_config('perfdebug', 15);            // Uber essential. This is how we get perf information from the server.
set_config('themedesignermode', '0');   // Ensured to be off for best representation of production system.
set_config('cachejs', '1');             // Ensured to be on for best representation of production system.
set_config('langstringcache', '1');     // Ensured to be on for best representation of production system.
set_config('passwordpolicy', false);    // Init script creates users with weak + predictable passwords.

// Optional settings.
// These settings arn't strictly required for the operation of the tool.
// They are however set because if we set them routinely we can add tests for things and be sure everything is
// already enabled and ready to go.
set_config('enableoutcomes', '1');
set_config('enableportfolios', '1');
set_config('enablewebservices', '1');
set_config('enablestats', '1');
set_config('enablerssfeeds', '1');
set_config('enablecompletion', '1');
set_config('enableavailability', '1');
set_config('enableplagiarism', '1');
set_config('enablecssoptimiser', '1');
set_config('allowthemechangeonurl', 1);
set_config('debugpageinfo', 1);
set_config('custommenuitems', $menu);   // Not require but there so we can be sure a constant display.

set_config('custominitexecuted', '1');

echo '<p>Settings updated</p>';@flush();

$categoryids = array();

for ($i = 0; $i < _CATEGROIES_; $i++) {
    $category = new stdClass;
    $category->name = 'Category '.($i+1);
    $category->description = 'This is a root category';
    $category->parent = 0;
    $category->sortorder = 999;
    $iid = $DB->insert_record('course_categories', $category);
    $categorycontext = get_context_instance(CONTEXT_COURSECAT, $iid);
    mark_context_dirty($categorycontext->path);
    $categoryids[] = $iid;
    
    echo '<p>Category '.($i+1).' created</p>';@flush();
    
    for ($j = 0; $j < _SUBCATEGROIES_; $j++) {
        $category = new stdClass;
        $category->name = 'Sub category '.($j+1).' of '.($i+1);
        $category->description = 'This is a sub category of category '.($i+1);
        $category->parent = $iid;
        $category->sortorder = 999;
        $jid = $DB->insert_record('course_categories', $category);
        $categorycontext = get_context_instance(CONTEXT_COURSECAT, $jid);
        mark_context_dirty($categorycontext->path);
        $categoryids[] = $jid;
        
        echo '<p>Sub category '.($j+1).' created</p>';@flush();
        
        for ($k = 0; $k < _SUBSUBCATEGROIES_; $k++) {
            $category = new stdClass;
            $category->name = 'Sub category '.($k+1).' of '.($j+1).'/'.($i+1);
            $category->description = 'This is a sub category of category '.($j+1);
            $category->parent = $jid;
            $category->sortorder = 999;
            $kid = $DB->insert_record('course_categories', $category);
            $categorycontext = get_context_instance(CONTEXT_COURSECAT, $kid);
            mark_context_dirty($categorycontext->path);
            $categoryids[] = $kid;
            
            echo '<p>Sub sub category '.($i+1).' created</p>';@flush();
        }
    }
}
fix_course_sortorder();
unset($category);
$trace = new null_progress_trace();
for ($i = 0 ; $i < _COURSES_; $i++) {
    $course = new stdClass;
    $course->category = $categoryids[array_rand($categoryids)]; // Should be the misc category
    $course->shortname = 'Test course '.($i+1);
    $course->fullname = 'Automatically generated test course '.($i+1);
    $course->summary = 'This course is used for performance comparison testing. Please refrain from making any changes in this course or from changing any enrollments to this course as you\'ll muck up the performance comparison tool.';
    $course->format = 'topics';
    $course->numsections = 5;
    $course = create_course($course);
    
    echo '<p>Course '.($i+1).' created</p>';@flush();

    $errors = array();

    $manager = new course_enrolment_manager($PAGE, $course);
    $roles = $manager->get_assignable_roles();
    $cohorts = enrol_cohort_get_cohorts($manager);

    $studentroleid = null;
    $teacherroleid = null;
    $managerroleid = null;

    foreach ($roles as $roleid => $rolename) {
        if ($rolename === 'Student') {
            $studentroleid = $roleid;
        } else if ($rolename === 'Teacher') {
            $teacherroleid = $roleid;
        } else if ($rolename === 'Manager') {
            $managerroleid = $roleid;
        }
    }

    $teachercohort = $teachercohorts[array_rand($teachercohorts)];
    $studentcohort = $studentcohorts[array_rand($studentcohorts)];
    
    if (array_key_exists($studentcohort->id, $cohorts) && !is_null($studentroleid)) {
        $enrol = enrol_get_plugin('cohort');
        $enrol->add_instance($manager->get_course(), array('customint1' => $studentcohort->id, 'roleid' => $studentroleid));
    } else {
        $errors[] = 'Could not enrol student cohort to the performance course. Please do that manually.';
    }

    if (array_key_exists($teachercohort->id, $cohorts) && !is_null($teacherroleid)) {
        $enrol = enrol_get_plugin('cohort');
        $enrol->add_instance($manager->get_course(), array('customint1' => $teachercohort->id, 'roleid' => $teacherroleid));
    } else {
        $errors[] = 'Could not enrol teacher cohort to the performance course. Please do that manually.';
    }

    if (array_key_exists($managercohort->id, $cohorts) && !is_null($managerroleid)) {
        $enrol = enrol_get_plugin('cohort');
        $enrol->add_instance($manager->get_course(), array('customint1' => $managercohort->id, 'roleid' => $managerroleid));
    } else {
        $errors[] = 'Could not enrol manager cohort to the performance course. Please do that manually.';
    }
    if ($release >= 2.5) {
        enrol_cohort_sync($trace, $manager->get_course()->id);
    } else {
        enrol_cohort_sync($manager->get_course()->id);
    }



    $course = $DB->get_record('course', array('id'=>$course->id), '*', MUST_EXIST);
    $module = $DB->get_record('modules', array('name'=>'forum'), '*', MUST_EXIST);

    $forum = new stdClass;
    $forum->course = $course->id;
    $forum->name = 'Performance test forum';
    $forum->intro = 'This forum will be used by the Moodle JMeter performance comparison tool.';
    $forum->introformat = 0;

    $forum->section = 2;
    $forum->type = 'general';
    $forum->modulename = 'forum';
    $forum->groupingid = 0;
    $forum->groupmembersonly = 0;
    $forum->completion = 0;
    $forum->completionview = 0;
    $forum->completiongradeitemnumber = null;
    $forum->instance     = '';
    $forum->coursemodule = '';
    $forum->groupmode = 0;
    $forum->availablefrom = 0;
    $forum->availableuntil = 0;
    $forum->showavailability = 0;
    $forum->visible = 1;
    $forum->forcesubscribe = 0;
    $forum->conditiongradegroup = array();
    $forum->conditionfieldgroup = array();
    $forum->conditioncompletiongroup = array();

    // first add course_module record because we need the context
    $newcm = new stdClass;
    $newcm->course = $course->id;
    $newcm->module = $module->id;
    $newcm->instance = 0; // not known yet, will be updated later (this is similar to restore code)
    $newcm->visible  = 1;
    $newcm->groupmode = 0;
    $newcm->groupingid = 0;
    $newcm->groupmembersonly = 0;
    $newcm->completion = 0;
    $newcm->completiongradeitemnumber = null;
    $newcm->completionview = 0;
    $newcm->completionexpected = 0;
    $newcm->availablefrom = 0;
    $newcm->availableuntil = 0;
    $newcm->showavailability = 0;

    $forum->coursemodule = add_course_module($newcm);
    $forum->cmidnumber = $forum->coursemodule;
    $forum->id = forum_add_instance($forum, false);

    if (!$forum->id or !is_number($forum->id)) {
        // undo everything we can
        $modcontext = get_context_instance(CONTEXT_MODULE, $fromform->coursemodule);
        delete_context(CONTEXT_MODULE, $fromform->coursemodule);
        $DB->delete_records('course_modules', array('id'=>$fromform->coursemodule));
    }
    $forum->instance = $forum->id;

    $DB->set_field('course_modules', 'instance', $forum->id, array('id'=>$forum->coursemodule));

    // update embedded links and save files
    $modcontext = get_context_instance(CONTEXT_MODULE, $forum->coursemodule);

    // course_modules and course_sections each contain a reference
    // to each other, so we have to update one of them twice.
    if ($release < 2.4) {
        $sectionid = add_mod_to_section($forum);
    } else {
        $sectionid = course_add_cm_to_section($forum->course, $forum->coursemodule, $forum->section, null);
    }


    $DB->set_field('course_modules', 'section', $sectionid, array('id'=>$forum->coursemodule));

    // make sure visibility is set correctly (in particular in calendar)
    set_coursemodule_visible($forum->coursemodule, $forum->visible);

    // Set up conditions
    if ($CFG->enableavailability) {
        condition_info::update_cm_from_form((object)array('id'=>$forum->coursemodule), $forum, false);
    }

    add_to_log($course->id, "course", "add mod", "../mod/$forum->modulename/view.php?id=$forum->coursemodule", "$forum->modulename $forum->instance");
    add_to_log($course->id, $forum->modulename, "add", "view.php?id=$forum->coursemodule", "$forum->instance", $forum->coursemodule);

    // Trigger mod_created/mod_updated event with information about this module.
    $eventdata = new stdClass;
    $eventdata->modulename = $forum->modulename;
    $eventdata->name       = $forum->name;
    $eventdata->cmid       = $forum->coursemodule;
    $eventdata->courseid   = $course->id;
    $eventdata->userid     = 2;
    events_trigger('mod_created', $eventdata);

    // add outcomes if requested
    if ($outcomes = grade_outcome::fetch_all_available($course->id)) {
        // Outcome grade_item.itemnumber start at 1000, there is nothing above outcomes
        $max_itemnumber = 999;
        foreach($outcomes as $outcome) {
            $elname = 'outcome_'.$outcome->id;
            if (property_exists($fromform, $elname) and $fromform->$elname) {
                // so we have a request for new outcome grade item?
                $max_itemnumber++;
                $outcome_item = new grade_item();
                $outcome_item->courseid     = $course->id;
                $outcome_item->itemtype     = 'mod';
                $outcome_item->itemmodule   = $forum->modulename;
                $outcome_item->iteminstance = $forum->instance;
                $outcome_item->itemnumber   = $max_itemnumber;
                $outcome_item->itemname     = $outcome->fullname;
                $outcome_item->outcomeid    = $outcome->id;
                $outcome_item->gradetype    = GRADE_TYPE_SCALE;
                $outcome_item->scaleid      = $outcome->scaleid;
                $outcome_item->insert();

                // move the new outcome into correct category and fix sortorder if needed
                if (!empty($grade_item)) {
                    $outcome_item->set_parent($grade_item->categoryid);
                    $outcome_item->move_after_sortorder($grade_item->sortorder);

                } else if (isset($fromform->gradecat)) {
                    $outcome_item->set_parent($fromform->gradecat);
                }
            }
        }
    }
    rebuild_course_cache($course->id);
    grade_regrade_final_grades($course->id);
    plagiarism_save_form_elements($forum); //save plagiarism settings
}


if (count($errors)) {
    echo $OUTPUT->notification('Initialised to performance test state with errors');
    foreach ($errors as $error) {
        echo "<p>{$error}</p>";
    }
} else {
    echo $OUTPUT->notification('Successfully initialised to performance test state', 'notifysuccess');
}
echo $OUTPUT->footer();
