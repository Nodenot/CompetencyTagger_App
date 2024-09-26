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
require_once($CFG->dirroot . '/blocks/cosy_ct/classes/forms/dummy_comp_form.php');

global $DB, $OUTPUT, $PAGE;

// Check for all required variables.
$cmid = required_param('id', PARAM_INT);
$courseid = required_param('courseid', PARAM_INT);
$blockid = required_param('blockid', PARAM_INT);
$anchorid = required_param('anchorid', PARAM_INT);
$recobjid = required_param('recobjid', PARAM_INT);

// Assigning url to page.
$urlpage = new moodle_url('/blocks/cosy_ct/competencies_visor.php');
$urlpage->params(array(
    'id' => $cmid,
    'courseid' => $courseid,
    'blockid' => $blockid,
    'anchorid' => $anchorid,
    'recobjid' => $recobjid,
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
$PAGE->set_heading(get_string('compvisortitle', 'block_cosy_ct') . $course->fullname);
$PAGE->set_title(get_string('breadcrumbsanchorcompvisor', 'block_cosy_ct') . $course->fullname);

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
$pagevisornode = $recobjeditnode->add(get_string('breadcrumbsanchorcompvisor', 'block_cosy_ct'), $urlpage);
$pagevisornode->make_active();

// Recobj data from DB
$recobjsrc = $DB->get_record('block_cosy_ct_recobj', ['id' => $recobjid]);
$competencycoursesrc = $DB->get_recordset('competency_coursecomp', ['courseid' => $courseid]);
$competenciessrc = array();
foreach ($competencycoursesrc as $key => $value) {
    $competenciessrc[] = $DB->get_record('competency', ['id' => $value->competencyid]);
}
$competencycoursesrc->close();
$comptodeliver = array();
foreach ($competenciessrc as $key => $value) {
    $frameworksrc = $DB->get_record('competency_framework', ['id' => $value->competencyframeworkid]);
    $selected = $DB->record_exists('block_cosy_ct_recobj_knowho', ['recobjid' => $recobjid, 'competencyid' => $value->id]);
    $aux = new stdClass();
    $aux->competencyid = $value->id;
    $aux->competencyshortname = $value->shortname;
    $aux->competencydescription = $value->description;
    $aux->frameworkname = $frameworksrc->shortname;
    $aux->frameworkdescription = $frameworksrc->description;
    $aux->frameworktaxonomies = $frameworksrc->taxonomies;
    if ($selected) {
        $aux->checked = true;
    } else {
        $aux->checked = false;
    }
    $comptodeliver[] = $aux;
}

//Create Dummy Form
$my_dummy_form = new cosy_ct_dummy_comp_form(
    $urlpage, array(
        'recobjid' => $recobjid,
    )
);

// Setting data to the template to run properly.
$data = [
    'dummy_form' => $my_dummy_form->render(),
    'anchorid' => $anchorid,
    'courseid' => $courseid,
    'coursefullname' => $course->fullname,
    'cmid' => $cmid,
    'blockid' => $blockid,
    'recobjid' => $recobjid,
    'recobjdesc' => $recobjsrc->description,
    'competencies' => $comptodeliver,
];

echo $OUTPUT->header();

echo $OUTPUT->render_from_template('block_cosy_ct/competencies_visor', $data);

echo $OUTPUT->footer();
?>