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

require_once($CFG->dirroot . '/lib/adminlib.php');
require_once($CFG->dirroot . '/blocks/cosy_ct/classes/forms/pageblockform.php');
require_once($CFG->dirroot . '/blocks/cosy_ct/classes/forms/discussionblockform.php');
require_once($CFG->dirroot . '/blocks/cosy_ct/utils/general_functions.php');

/**
 * The purpose of this function it is to add the forms and icons to the block
 * accordingly to the course_module in which it is displayed, and to populate
 * block_cosy_ct_anchors table with the content that will be found in the
 * content of the course_module. Ie. the content of a PAGE or a content of a book.
 *
 * @see get_visor_content()
 * @param object $th The $this var that will be used to add content to the block
 * @param DOMDocument $doc The DOMDocument that will be used to view the content of the cm
 * @param object $containerobj The container object which is referring to a block_cosy_ct_containers record
 * @return void it does not return anything, the functionality happens inside function with $th var
 */
function get_forum_only_desc_visor_content($th, $doc, $containerobj) {
    global $DB, $PAGE, $OUTPUT;
    $th->content->text .= html_writer::tag('br', '');
    $quizobj = $DB->get_record('forum', ['id' => $PAGE->cm->instance]);
    @$doc->loadHTML('<?xml encoding="utf-8" ?>'.$quizobj->intro);
    populateAnchors($doc, $containerobj->id);

    $cmid = required_param('id', PARAM_INT);

    $urlquiz = new moodle_url('/mod/forum/view.php');
    $urlquiz->params(array(
        'id' => $cmid,
    ));

    // Call the corresponding form
    $pageblock_form = new cosy_ct_pageblockform(
        $urlquiz, array(
            'cmid' => $cmid,
            'courseid' => $PAGE->course->id,
            'blockid' => $th->instance->id,
        )
    );
    $th->content->text .= $pageblock_form->render();


    // Editname icon.
    $editnameicon = $OUTPUT->pix_icon('i/edit', '');
    $atteditname = array(
        'id' => 'iut_page_editname_icon',
        'title' => 'Edit IUT anchor',
    );

    // Newtab icon.
    $newtabicon = $OUTPUT->pix_icon('i/upload', '');
    $attnewtab = array(
        'id' => 'iut_page_newtab_icon',
        'title' => 'Test IUT anchor',
    );

    $data = [
        'editname' => html_writer::tag('a', $editnameicon, $atteditname),
        'newtab' => html_writer::tag('a', $newtabicon, $attnewtab),
    ];

    $th->content->text .= $OUTPUT->render_from_template('block_cosy_ct/page_block', $data);
}

function get_forum_only_disc_visor_content($th, $doc, $containerobj, $did) {
    global $DB, $PAGE, $OUTPUT;
    $th->content->text .= html_writer::tag('br', '');
    $posts = $DB->get_records('forum_posts', ['discussion' => $did]);
    foreach ($posts as $key => $value) {
        @$doc->loadHTML('<?xml encoding="utf-8" ?>'.$value->message);
        populateAnchors($doc, $containerobj->id.'#_#'.$did);
    }
    $quizobj = $DB->get_record('forum', ['id' => $PAGE->cm->instance]);
    @$doc->loadHTML('<?xml encoding="utf-8" ?>'.$quizobj->intro);

    $urldiscussion =  new moodle_url('/mod/forum/discuss.php');
    $urldiscussion->params(array(
        'd' => $did,
    ));
    $discussion_form = new cosy_ct_discussionblockform(
        $urldiscussion, array(
            'cmid' => $PAGE->cm->id,
            'courseid' => $PAGE->course->id,
            'blockid' => $th->instance->id,
            'did' => $did
        )
    );
    $th->content->text .= $discussion_form->render();
}
