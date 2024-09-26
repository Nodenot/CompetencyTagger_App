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
 * @package     block_cosy_ct_ct
 * @copyright      2023  J. Cornejo-Lupa
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once('../../config.php');
require_once($CFG->dirroot . '/blocks/cosy_ct/classes/forms/chganchorname.php');

global $DB, $OUTPUT, $PAGE;

// Check for all required variables.
$cmid = required_param('id', PARAM_INT);
$courseid = required_param('courseid', PARAM_INT);
$blockid = required_param('blockid', PARAM_INT);
$anchorid = required_param('anchorid', PARAM_INT);

// Assigning url to page.
$urlpage = new moodle_url('/blocks/cosy_ct/anchor_visor.php');
$urlpage->params(array(
    'id' => $cmid,
    'courseid' => $courseid,
    'blockid' => $blockid,
    'anchorid' => $anchorid,
));

// To get courseid, page object, anchor object and course object.
$cminformation = $DB->get_record('course_modules', ['id' => $cmid]);
$mode = $DB->get_record('modules', ['id' => $cminformation->module]);
$actualpage = $DB->get_record($mode->name, ['id' => $cminformation->instance]);
$course = $DB->get_record('course', ['id' => $courseid]);
$anchor = $DB->get_record('block_cosy_ct_anchors', ['id' => $anchorid]);

// Setting page with the neccesary.
$PAGE->set_url($urlpage);
$PAGE->set_context(context_course::instance($cminformation->course));
$PAGE->set_pagelayout('standard');
$PAGE->set_heading(get_string('pagevisortitle', 'block_cosy_ct'));
$PAGE->set_title($anchor->name . get_string('breadcrumbsanchorpagevisor', 'block_cosy_ct'));

// Adding navigation breadcrumbs to properly view in my anchor_visor page.
$coursenode = $PAGE->navigation->add(
    $course->fullname,
    new moodle_url('/course/view.php', ['id' => $courseid]),
    navigation_node::TYPE_CONTAINER
);
$previewnode = $coursenode->add(
    $actualpage->name,
    new moodle_url('/mod/'.$mode->name.'/view.php', ['id' => $cmid]),
    navigation_node::TYPE_CONTAINER
);
$pagevisornode = $previewnode->add($anchor->name . get_string('breadcrumbsanchorpagevisor', 'block_cosy_ct'), $urlpage);
$pagevisornode->make_active();

// Set moodle form to change anchors stuff.
$my_changeform = new cosy_ct_chganchorname(
    $urlpage, array(
        'anchor_id' => $anchor->id,
    )
);

// Get information of container but from table block_cosy_ct_containers
$container_obj = $DB->get_record('block_cosy_ct_containers', ['idin_course_modules' => $cmid]);
$course_obj = $DB->get_record('course', ['id' => $container_obj->course]);
$cm_obj = $DB->get_record('course_modules', ['id' => $container_obj->idin_course_modules]);
$module = $DB->get_record('modules', ['id' => $cm_obj->module]);
$actual_obj = $DB->get_record($module->name, ['id' => $cm_obj->instance]);

$rec_objs = $DB->get_records('block_cosy_ct_recobj', ['anchorid' => $anchorid ]);
$rec_objs_to_table = array();
foreach ($rec_objs as $key => $value) {
    $myobj = new stdClass();
    $my_approach = $DB->get_record('block_cosy_ct_approaches', ['id' => $value->approachid ]);
    $my_medias = $DB->get_records('block_cosy_ct_recobj_medias', ['recobjid' => $value->id]);
    $my_name_medias = array();
    $medianames = '';
    foreach ($my_medias as $key => $value2) {
        $aux = $DB->get_record('block_cosy_ct_medias', ['id' => $value2->mediaid]);
        $medianames .= $aux->display_name . '; ';
    }
    $myobj->id = $value->id;
    $myobj->description = $value->description;
    $myobj->approach = $my_approach->display_name;
    $myobj->media = $medianames;
    $rec_objs_to_table[] = $myobj;
}

// Setting data to the template to run properly.
$data = [
    'change_name_form' => $my_changeform->render(),
    'type_content' => $container_obj->type,
    'course_content' => $course_obj->fullname,
    'module_content' => $actual_obj->name,
    'rec_objs' => $rec_objs_to_table,
    'anchorid' => $anchorid,
    'courseid' => $courseid,
    'cmid' => $cmid,
    'blockid' => $blockid,
];

echo $OUTPUT->header();

echo $OUTPUT->render_from_template('block_cosy_ct/anchor_visor', $data);

echo $OUTPUT->footer();
?>