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

require_once($CFG->dirroot . '/blocks/cosy_ct/utils/reveal_functions.php');
require_once($CFG->dirroot . '/blocks/cosy_ct/utils/general_functions.php');
require_once($CFG->dirroot . '/blocks/cosy_ct/classes/forms/pageblockform.php');
require_once($CFG->dirroot . '/blocks/cosy_ct/classes/forms/revealblockform.php');
require_once($CFG->dirroot . '/lib/adminlib.php');

/**
 * The purpose of this function it is to add the forms and icons to the block
 * accordingly to the course_module in which it is displayed, and to populate
 * block_cosy_ct_anchors table with the content that will be found in the
 * content of the course_module. Ie. the content of a PAGE or a content of a book.
 *
 * @see get_visor_content()
 * @param object $th The $this var that will be used to add content to the block
 * @param object $srcobj The source object accordingly to the course_module
 * @param DOMDocument $doc The DOMDocument that will be used to view the content of the cm
 * @param object $containerobj The container object which is referring to a block_cosy_ct_containers record
 * @return void it does not return anything, the functionality happens inside function with $th var
 */
function get_page_visor_content($th, $srcobj, $doc, $containerobj) {
    global $DB, $PAGE;
    $th->content->text .= html_writer::tag('br', '');
    
    // Get content of page to see if its reveal or not.
    @$doc->loadHTML('<?xml encoding="utf-8" ?>'.$srcobj->content);
    // View if its a normal or reveal page.
    $flag = isRevealPage($doc);
    if ($flag) {
        // It is a reveal resource.
        global $PAGE, $DB, $OUTPUT;
        $th->content->text .= html_writer::tag('p', 'Hello reveal');

        // Get url from iframe.
        $reveal_src = findRevealSrc($doc);
        // Exceute curl with given url to extract html.
        $ch = curl_init($reveal_src);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTP_CONTENT_DECODING, false);
        $content = curl_exec($ch);
        curl_close($ch);
        // Load document to analyze.
        @$doc->loadHTML('<?xml encoding="utf-8" ?>'.$content);
        // Iterate trough etiquettes searching for tags ids for populate data base.
        populateRevealAnchors($doc, $containerobj->id);
        
        $cmid = required_param('id', PARAM_INT);

        $urlpage = new moodle_url('/mod/page/view.php');
        $urlpage->params(array(
            'id' => $cmid,
        ));

        // Call to the form corresponding to the IUT block whitin a reveal configuration.
        $revealblock_form = new cosy_ct_revealblockform(
            $urlpage, array(
                'cmid' => $cmid,
                'courseid' => $PAGE->course->id,
                'blockid' => $th->instance->id,
                'url_reveal' => $reveal_src,
            )
        );

        $th->content->text .= $revealblock_form->render();

        // editname icon.
        $editnameicon = $OUTPUT->pix_icon('i/edit', '');
        $atteditname = array(
            'id' => 'iut_reveal_editname_icon',
            'title' => 'Edit IUT anchor',
        );

        // newtab icon.
        $newtabicon = $OUTPUT->pix_icon('i/upload', '');
        $attnewtab = array(
            'id' => 'iut_reveal_newtab_icon',
            'title' => 'Test IUT anchor',
        );

        $data = [
            'editname' => html_writer::tag('a', $editnameicon, $atteditname),
            'newtab' => html_writer::tag('a', $newtabicon, $attnewtab),
        ];
        $th->content->text .= $OUTPUT->render_from_template('block_cosy_ct/page_block', $data);
    } else {
        // It is a normal page.
        global $PAGE, $DB, $OUTPUT;

        // Iterate trough etiquettes searching for tags ids for populate data base.
        populateAnchors($doc, $containerobj->id);

        $cmid = required_param('id', PARAM_INT);

        $urlpage = new moodle_url('/mod/page/view.php');
        $urlpage->params(array(
            'id' => $cmid,
        ));

        // Call to the form corresponding to the IUT block whitin a page configuration.
        $pageblock_form = new cosy_ct_pageblockform(
            $urlpage, array(
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
}