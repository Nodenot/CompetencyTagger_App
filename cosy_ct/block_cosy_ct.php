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
 * @package     block_cosy_ct
 * @copyright      2023  J. Cornejo-Lupa
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once($CFG->dirroot . '/blocks/cosy_ct/utils/page_functions.php');
require_once($CFG->dirroot . '/blocks/cosy_ct/utils/book_functions.php');
require_once($CFG->dirroot . '/blocks/cosy_ct/utils/quiz_functions.php');
require_once($CFG->dirroot . '/blocks/cosy_ct/utils/forum_functions.php');
require_once($CFG->dirroot . '/blocks/cosy_ct/utils/general_functions.php');

/**
 * Base class of the block plugin.
 *
 * @package    block_cosy_ct
 * @category   backup
 * @copyright  2023 J. Cornejo-Lupa
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_cosy_ct extends block_base {
    public function init() {
        $this->title = get_string('titleblock', 'block_cosy_ct');
    }
     public function has_config() {
        return true;
    }
    public function get_content(){
        global $PAGE;
        $addrServer = get_config('block_cosy_ct', 'url');
        
        if (!$PAGE->user_is_editing()){
            return null;
        }
        if ($this->content !== null) {
            return $this->content;
        }
        $this->content = new stdclass;
        $this->content->footer = "";
        if (has_capability('block/cosy_ct:view', context_block::instance($this->instance->id))) {
            switch (get_block_type()) {
                case 'course':
                    $this->title = get_string('coursetitleblock', 'block_cosy_ct');

                    global $DB, $COURSE;
                    $courseid = optional_param('id', NULL, PARAM_INT);
                    $coursemodules = $DB->get_records('course_modules', ['course' => strval($courseid)]);

		    	// Create an iframe in the course page
			echo "<script type='text/javascript'>
				document.addEventListener('DOMContentLoaded', (e) => {
					let ifrm = document.createElement('iframe');
					ifrm.setAttribute('src', '$addrServer/index.html?gid=$courseid');
     					ifrm.setAttribute('allow', 'fullscreen');
					ifrm.style.width = '100%';
					ifrm.style.height = '500px';
					ifrm.frameBorder = 1;
					ifrm.id = 'graph_course_$courseid';
					document.getElementById('topofscroll').appendChild(ifrm); //topofscroll is a common id to every Moodle page delimiting the content
				});
			</script>";
                    
                    // Populating the table block_cosy_ct_containers with the information of current course.
                    $arrcms = array();
                    foreach ($coursemodules as $key => $value) {
                        if (! $DB->record_exists('block_cosy_ct_containers', ['idin_course_modules' => strval($value->id)])) {
                            $module = $DB->get_record('modules', ['id' => strval($value->module)]);
                            $record1 = new stdClass();
                            $record1->type = $module->name;
                            $record1->course = $courseid;
                            $record1->section = $value->section;
                            $record1->idin_course_modules = $value->id;
                            $DB->insert_record('block_cosy_ct_containers', $record1);
                        }
                    }
    
                    $this->content->text .= html_writer::tag('p', get_string('availablesections','block_cosy_ct'));
                    $this->content->text .= html_writer::tag('p', get_string('supportedcm','block_cosy_ct'));
                    $this->content->text .= html_writer::start_tag('ul');
                    $this->content->text .= html_writer::start_tag('li');
                    $this->content->text .= html_writer::tag('p', get_string('pageblockcm','block_cosy_ct'));
                    $this->content->text .= html_writer::end_tag('li');
                    $this->content->text .= html_writer::start_tag('li');
                    $this->content->text .= html_writer::tag('p', get_string('revealblockcm','block_cosy_ct'));
                    $this->content->text .= html_writer::end_tag('li');
                    $this->content->text .= html_writer::start_tag('li');
                    $this->content->text .= html_writer::tag('p', get_string('forumblockcm','block_cosy_ct'));
                    $this->content->text .= html_writer::end_tag('li');
                    $this->content->text .= html_writer::start_tag('li');
                    $this->content->text .= html_writer::tag('p', get_string('quizblockcm','block_cosy_ct'));
                    $this->content->text .= html_writer::end_tag('li');
                    $this->content->text .= html_writer::start_tag('li');
                    $this->content->text .= html_writer::tag('p', get_string('bookblockcm','block_cosy_ct'));
                    $this->content->text .= html_writer::end_tag('li');
                    $this->content->text .= html_writer::end_tag('ul');
                    break;

                case 'coursesettings':
                    return null;
                case 'cosy_ct':
                    return null;
                case 'cmsettings':
                    return null;

                case 'page':
                    $this->title = get_string('cmtitleblock', 'block_cosy_ct');
                    // Case it is a course module page.
                    global $DB, $PAGE;
    
                    $this->content->text = get_string('availablelabels', 'block_cosy_ct');
    
                    $cmid = optional_param('id', null, PARAM_INT);
                    if ($cmid){
                        // Getting content:
                        // A. Information about the type of module (i.e. page, book, etc).
                        $module = $DB->get_record('modules', ['id' => $PAGE->cm->module]);
                        // B. Specific information about the course module in respective table.
                        $srcobj = $DB->get_record($module->name, ['id' => $PAGE->cm->instance]);
    
                        // Getting id of container that corresponds to current cm.
                        $containerobj = $DB->get_record('block_cosy_ct_containers', ['idin_course_modules' => $PAGE->cm->id]);
    
                        // Loading content to dom.
                        $doc = new DOMDocument;
                        get_page_visor_content($this, $srcobj, $doc, $containerobj);
                    }
                    break;
                case 'book':
                    $this->title = get_string('cmtitleblock', 'block_cosy_ct');
                    // Case it is a course module book.
                    global $DB, $PAGE;
                    $this->content->text = get_string('availablelabels', 'block_cosy_ct');
                    $cmid = optional_param('id', null, PARAM_INT);
                    $chapterid = optional_param('chapterid', null, PARAM_INT);
                    if ($cmid) {
                        // Getting content:
                        // A. Information about the type of module (i.e. page, book, etc).
                        $module = $DB->get_record('modules', ['id' => $PAGE->cm->module]);
                        // B. Specific information about the course module in respective table.
                        $srcobj = $DB->get_record($module->name, ['id' => $PAGE->cm->instance]);
                        // Getting id of container that corresponds to current cm.
                        $containerobj = $DB->get_record('block_cosy_ct_containers', ['idin_course_modules' => $PAGE->cm->id]);
                        $doc = new DOMDocument;
                        get_book_visor_content($this, $doc, $containerobj);
                    }
                    break;
                case 'quiz':
                    $this->title = get_string('cmtitleblock', 'block_cosy_ct');
                    // Case it is a course module quiz.
                    global $DB, $PAGE;
                    $this->content->text = get_string('availablelabels', 'block_cosy_ct');
                    $cmid = optional_param('id', null, PARAM_INT);
                    if ($cmid) {
                        // Getting content:
                        // A. Information about the type of module (i.e. page, book, etc).
                        $module = $DB->get_record('modules', ['id' => $PAGE->cm->module]);
                        // B. Specific information about the course module in respective table.
                        $srcobj = $DB->get_record($module->name, ['id' => $PAGE->cm->instance]);
                        // Getting id of container that corresponds to current cm.
                        $containerobj = $DB->get_record('block_cosy_ct_containers', ['idin_course_modules' => $PAGE->cm->id]);
                        $doc = new DOMDocument;
                        get_quiz_visor_content($this, $doc, $containerobj);
                    }
                    break;
                case 'forum':
                    $this->title = get_string('cmtitleblock', 'block_cosy_ct');
                    // Case it is a course module forum.
                    global $DB, $PAGE;
                    $this->content->text = get_string('availablelabels', 'block_cosy_ct');
                    $cmid = optional_param('id', null, PARAM_INT);
                    $did = optional_param('d', null, PARAM_INT);
                    $forum_obj = $DB->get_record('forum', ['id' => $PAGE->cm->instance]);
                    $module = $DB->get_record('modules', ['id' => $PAGE->cm->module]);
                    $srcobj = $DB->get_record($module->name, ['id' => $PAGE->cm->instance]);
                    $containerobj = $DB->get_record('block_cosy_ct_containers', ['idin_course_modules' => $PAGE->cm->id]);
                    if ($forum_obj->type == 'news') {
                        $doc = new DOMDocument;
                        get_forum_only_desc_visor_content($this, $doc, $containerobj);
                    }
                    if ($forum_obj->type == 'eachuser' || $forum_obj->type == 'general'){
                        $doc = new DOMDocument;
                        if ($did) {
                            get_forum_only_disc_visor_content($this, $doc, $containerobj, $did);
                        } else {
                            get_forum_only_desc_visor_content($this, $doc, $containerobj);
                        }
                    }
                    break;
                default:
                    return null;
            }
        } else {
            return null;
        }
        
        return $this->content;
    }
    public function applicable_formats() {
        return array(
            'course-view' => true,
        );
    }
}
