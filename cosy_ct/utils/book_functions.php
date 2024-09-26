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
require_once($CFG->dirroot . '/blocks/cosy_ct/classes/forms/bookblockform.php');
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
function get_book_visor_content($th, $doc, $containerobj) {
    global $DB, $PAGE, $OUTPUT;
    $th->content->text .= html_writer::tag('br', '');
    $chapters = $DB->get_records('book_chapters', ['bookid' => $PAGE->cm->instance]);
    foreach ($chapters as $key => $value) {
        @$doc->loadHTML('<?xml encoding="utf-8" ?>'.$value->content);
        populateAnchors($doc, $containerobj->id.'#_#'.$value->id);
    }
    $cmid = required_param('id', PARAM_INT);
    $chapterid = optional_param('chapterid', null, PARAM_INT);
    $urlbook = new moodle_url('/mod/book/view.php');
    if ($chapterid) {
        $urlbook->params(array(
            'id' => $cmid,
            'chapterid' => $chapterid,
        ));
    }
    else {
        $urlbook->params(array(
            'id' => $cmid,
        ));
    }

    // Call the corresponding form
    $bookblock_form = new cosy_ct_bookblockform(
        $urlbook, array(
            'cmid' => $cmid,
            'courseid' => $PAGE->course->id,
            'blockid' => $th->instance->id,
            'chapterid' => $chapterid ? $chapterid : '1',
        )
    );
    $th->content->text .= $bookblock_form->render();


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
