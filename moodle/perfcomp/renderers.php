<?php
/**
 * Perfcomp theme: Required by the performance comparison tool.
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

class theme_perfcomp_core_renderer extends core_renderer {
    public function standard_footer_html() {
        try {
            $output = `git branch`;
            if (preg_match('#^\s*\*\s*([a-zA-Z0-9\-_]+)\s*$#m', $output, $matches)) {
                $gitbranch = $matches[1];
            } else {
                $gitbranch = 'Unknown';
            }
        } catch (Exception $e) {
            $gitbranch = 'Failed';
        }
        return parent::standard_footer_html().html_writer::tag('div', $gitbranch, array('class' => 'currentgitbranch'));
    }
    
}