<?php

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