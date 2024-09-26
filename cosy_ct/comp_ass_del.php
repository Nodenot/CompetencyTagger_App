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
require_once('../../config.php');
require_once($CFG->dirroot . '/blocks/cosy_ct/classes/forms/dummy_comp_del_form.php');

global $DB, $OUTPUT, $PAGE;

// Check for all required variables.
$cmid = required_param('id', PARAM_INT);
$courseid = required_param('courseid', PARAM_INT);
$blockid = required_param('blockid', PARAM_INT);
$anchorid = required_param('anchorid', PARAM_INT);
$recobjid = required_param('recobjid', PARAM_INT);
$compid = required_param('compid', PARAM_INT);

// Assigning url to page.
$urlpage = new moodle_url('/blocks/cosy_ct/comp_ass_del.php');
$urlpage->params(array(
    'id' => $cmid,
    'courseid' => $courseid,
    'blockid' => $blockid,
    'anchorid' => $anchorid,
    'recobjid' => $recobjid,
    'compid' => $compid,
));

$cminformation = $DB->get_record('course_modules', ['id' => $cmid]);
$mode = $DB->get_record('modules', ['id' => $cminformation->module]);
$actualpage = $DB->get_record($mode->name, ['id' => $cminformation->instance]);
$course = $DB->get_record('course', ['id' => $courseid]);
$anchor = $DB->get_record('block_cosy_ct_anchors', ['id' => $anchorid]);

// Setting page with the neccesary.
$PAGE->set_url($urlpage);
$PAGE->set_context(context_course::instance($cminformation->course));
$PAGE->set_pagelayout('standard');
$PAGE->set_heading(get_string('compassdeltitlepage', 'block_cosy_ct'));
$PAGE->set_title(get_string('compassdeltitlepage', 'block_cosy_ct'));

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
$anchornode = $previewnode->add(
    get_string('anchorvisortitle', 'block_cosy_ct'),
    new moodle_url('/blocks/cosy_ct/anchor_visor.php', ['id' => $cmid, 'courseid' => $courseid, 'blockid' => $blockid, 'anchorid' => $anchorid]),
    navigation_node::TYPE_CONTAINER
);
$recobjeditnode = $previewnode->add(
    get_string('recobjbreadcrumbs', 'block_cosy_ct'),
    new moodle_url('/blocks/cosy_ct/rec_obj_edit.php', ['id' => $cmid, 'courseid' => $courseid, 'blockid' => $blockid, 'anchorid' => $anchorid, 'recobjid' => $recobjid,]),
    navigation_node::TYPE_CONTAINER
);
$pagevisornode = $recobjeditnode->add(get_string('recobjbreadcrumbs', 'block_cosy_ct'), $urlpage);
$pagevisornode->make_active();

$dummy_form = new cosy_ct_dummy_comp_del_form(
    $urlpage, array(
        'anchor_id' => $anchor->id,
        'recobj_id' => $recobjid,
        'comp_id' => $compid,
    )
);

$data = [
    'dummy_form' => $dummy_form->render(),
    'cmid' => $cmid,
    'courseid' => $courseid,
    'blockid' => $blockid,
    'anchorid' => $anchorid,
    'recobjid' => $recobjid,
];

echo $OUTPUT->header();

echo $OUTPUT->render_from_template('block_cosy_ct/comp_ass_del', $data);

echo $OUTPUT->footer();