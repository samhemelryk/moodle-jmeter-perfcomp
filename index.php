<?php
/**
 * The default entry point for the Performance comparison tool.
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

require_once('lib.php');

$runs = get_runs();
$before = null;
$after = null;
$width = '300';
$height = '150';
if (!empty($_GET['before']) && array_key_exists($_GET['before'], $runs)) {
    $before = $_GET['before'];
    $beforekey = array_search($before, $runs);
}
if (!empty($_GET['after']) && array_key_exists($_GET['after'], $runs)) {
    $after = $_GET['after'];
    $afterkey = array_search($after, $runs);
}
if (!empty($_GET['w']) && preg_match('/^\d+$/', $_GET['w'])) {
    $width = (int)$_GET['w'];
}
if (!empty($_GET['h']) && preg_match('/^\d+$/', $_GET['h'])) {
    $height = (int)$_GET['h'];
}


$pages = array();
if ($before && $after) {
    $pages = build_pages_array($runs, $before, $after);
}

echo "<html>";
echo "<head>";
echo '<script type="text/javascript" src="http://yui.yahooapis.com/combo?3.3.0/build/yui/yui-min.js&3.3.0/build/oop/oop-min.js&3.3.0/build/event-custom/event-custom-base-min.js&3.3.0/build/event/event-base-min.js&3.3.0/build/dom/dom-base-min.js&3.3.0/build/dom/selector-native-min.js&3.3.0/build/dom/selector-css2-min.js&3.3.0/build/node/node-base-min.js&3.3.0/build/event/event-base-ie-min.js&3.3.0/build/event-custom/event-custom-complex-min.js&3.3.0/build/event/event-synthetic-min.js&3.3.0/build/event/event-hover-min.js&3.3.0/build/dom/dom-style-min.js&3.3.0/build/dom/dom-style-ie-min.js&3.3.0/build/node/node-style-min.js"></script>';
echo '<link rel="stylesheet" type="text/css" href="resources/jmeter.css" />';
echo "<script type='text/javascript' src='resources/jmeter.js'></script>";
echo "</head>";
echo "<body>";

display_run_selector($runs, $before, $after, array('w' => $width, 'h' => $height));

if ($before && $after) {
    $count = 0;
    echo "<div id='pagearray'>";
    $statsarray = array();
    foreach ($pages as $key=>$page) {
        $count++;
        $class = ($count%2)?'odd':'even';
        $classkey = substr($key, 0, 8);
        echo "<div class='pagecontainer $class page-$classkey'>";
        echo "<h1 class='pagetitle'>".$page['before']->name."</h1>";
        echo "<h2 class='pagesubtitle'><a href='".$page['before']->url."'>".$page['before']->url."</a></h2>";
        echo "<div class='statistical'>";

        list($output, $stats) = display_results($page['before'], $page['after']);
        echo $stats;
        echo $output;
        $statsarray[] = $stats;
        display_organised_results('filesincluded', $page['before'], $page['after']);
        
        echo "<div class='graphdiv'>";
        foreach ($PROPERTIES as $PROPERTY) {
            if (!property_exists($page['before'], $PROPERTY)) {
                continue;
            }
            echo "<a href='graph.php?before=$before&after=$after&property=$PROPERTY&page=$key' class='largegraph'>";
            echo "<img src='./cache/".produce_page_graph($PROPERTY, $beforekey, $page['before'], $afterkey, $page['after'], $width, $height)."' alt='$PROPERTY' style='width:{$width}px;height:{$height}px;' />";
            echo "</a>";
        }
        echo "</div>";
        echo "</div>";
        echo "</div>";
    }
    echo "<div class='pagecontainer statsarray even'>";
    echo "<h1 class='pagetitle'>Combined stats</h1>";
    $cstats = array_pop($statsarray);
    array_unshift($statsarray, $cstats);
    foreach ($statsarray as $stats) {
        echo $stats;
    }
    echo "</div>";
    echo "</div>";
}
echo "\n<script type='text/javascript'>YUI().use('node', collapse_pages);</script>\n";
echo "</body>";
echo "</html>";
