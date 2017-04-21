<?php

// This file is part of Moodle - http://moodle.org/
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
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * myprofile renderer.
 *
 * @package    core_user
 * @copyright  2015 onwards Ankit Agarwal <ankit.agrr@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace core_user\output\myprofile;

defined('MOODLE_INTERNAL') || die;

/**
 * Report log renderer's for printing reports.
 *
 * @since      Moodle 2.9
 * @package    core_user
 * @copyright  2015 onwards Ankit Agarwal <ankit.agrr@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/lms/custom/my/classes/Dashboard.php';

class renderer extends \plugin_renderer_base {

    /**
     * Render the whole tree.
     *
     * @param tree $tree
     *
     * @return string
     */
    public function render_tree(tree $tree) {

        global $USER;

        $return = \html_writer::start_tag('div', array('class' => 'profile_tree'));
        $categories = $tree->categories;


        foreach ($categories as $category) {
            $return .= $this->render($category);
        }
        $return .= \html_writer::end_tag('div');

        $userid = $USER->id;

        //if ($userid == 2 || $userid == 234) {

            parse_str($_SERVER['QUERY_STRING']);
            $ds = new \Dashboard();

            $return .= \html_writer::start_tag('section', array('class' => 'node_category', 'id' => 'custom_section'));
            $return .=$ds->get_user_profile_custom_sections($id);
            $return .= \html_writer::end_tag('section');
        //}

        return $return;
    }

    /**
     * Render a category.
     *
     * @param category $category
     *
     * @return string
     */
    public function render_category(category $category) {
        $classes = $category->classes;

        if (empty($classes)) {
            $return = \html_writer::start_tag('section', array('class' => 'node_category'));
            //echo "Inside if ....<br>";
        } // end if 
        else {
            $return = \html_writer::start_tag('section', array('class' => 'node_category ' . $classes));
            //echo "Inside else ... <br>";
        } // end else
        $return .= \html_writer::tag('h3', $category->title);
        $nodes = $category->nodes;
        if (empty($nodes)) {
            // No nodes, nothing to render.
            return '';
        }
        $return .= \html_writer::start_tag('ul');
        foreach ($nodes as $node) {
            $return .= $this->render($node);
        }
        $return .= \html_writer::end_tag('ul');
        $return .= \html_writer::end_tag('section');
        return $return;
    }

    /**
     * Render a node.
     *
     * @param node $node
     *
     * @return string
     */
    public function render_node(node $node) {
        $return = '';
        if (is_object($node->url)) {
            $header = \html_writer::link($node->url, $node->title);
        } else {
            $header = $node->title;
        }
        $icon = $node->icon;
        if (!empty($icon)) {
            $header .= $this->render($icon);
        }
        $content = $node->content;
        $classes = $node->classes;
        if (!empty($content)) {
            // There is some content to display below this make this a header.
            $return = \html_writer::tag('dt', $header);
            $return .= \html_writer::tag('dd', $content);

            $return = \html_writer::tag('dl', $return);
            if ($classes) {
                $return = \html_writer::tag('li', $return, array('class' => 'contentnode ' . $classes));
            } else {
                $return = \html_writer::tag('li', $return, array('class' => 'contentnode'));
            }
        } else {
            $return = \html_writer::span($header);
            $return = \html_writer::tag('li', $return, array('class' => $classes));
        }

        return $return;
    }

}
