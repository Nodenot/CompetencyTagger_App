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

/**
 * The purpose of this function it is to get in which custom context it is the block
 * that it has been created.
 *
 * @see get_block_type()
 * @return string returns the context in which the block is in a string.
 */
function get_block_type(){
    global $PAGE, $DB;
    $course = $PAGE->course->fullname;
    $cm = $PAGE->cm;
    if (strpos($PAGE->url->out(), '/blocks/cosy_ct/')) {
        return 'cosy_ct';
    }
    if ($cm) {
        if (strpos($PAGE->url->out(), '/course/modedit.php') || strpos($PAGE->url->out(), 'edit.php')|| strpos($PAGE->url->out(), 'edit=')) {
            return 'cmsettings';
        }
        $type = $PAGE->cm->module;
        return $DB->get_record('modules', ['id' => $type])->name;    
    }
    if ($course) {
        if (strpos($PAGE->url->out(), '/course/edit.php') || strpos($PAGE->url->out(), '/course/admin.php') || strpos($PAGE->url->out(), '/course/modedit.php')) {
            return 'coursesettings';
        }
        return 'course';
    }
}

/**
 * The purpose of this function it is to delete the records from
 * block_cosy_ct_anchors that were deleted inside a content of a page.
 *
 * @see delExisting()
 * @param array $existing An array that contains all the 'reference_id's that has to be deleted
 * @param int $containerid The id of the container in which all of the elements of the $exisintg param belongs to
 * @return void It does not return nothing given that all the changes happen inside function
 */
function delExisting($existing, $containerid) {
    global $DB;
    foreach ($existing as $key => $value) {
        $compare = $DB->sql_compare_text('reference_id') .' = '.$DB->sql_compare_text(':anchorid'). ' AND ' . $DB->sql_compare_text('id_container') . ' = ' . $DB->sql_compare_text(':ctnid');
        $rec = $DB->get_record_sql("select * from mdl_block_cosy_ct_anchors where {$compare}", array('anchorid' => $value, 'ctnid' => $containerid));
        $DB->delete_records('block_cosy_ct_anchors', ['id' => $rec->id]);
    }
}

/**
 * The purpose of this function it is to insert records into the table
 * block_cosy_ct_anchors from the content of a page, and to delete those
 * that does not exist anymore in the content of the given page.
 *
 * @see populateAnchors()
 * @param DOMNode $domNode The DOMNode that has the content of the page that it is expected to look for
 * @param int $containerid The id of the container in which the content belongs to
 * @return void It does not return nothing given that all the changes happen inside function
 */
function populateAnchors(DOMNode $domNode, $containerid) {
    global $DB;
    // Search for all existing tags.
    $compare = $DB->sql_compare_text('id_container').'='.$DB->sql_compare_text(':containerid');
    $existing = $DB->get_records_sql("select * from mdl_block_cosy_ct_anchors where {$compare}",array('containerid' => $containerid));
    $ex_arr = array();
    foreach ($existing as $key => $value) {
        $ex_arr[$value->id] = $value->reference_id;
    }
    iterateTroughDOM($domNode, $containerid, $ex_arr);
    delExisting($ex_arr, $containerid);
}

/**
 * The purpose of this function it is to iterate trough the childs of DOMNode
 * to insert a new record in block_cosy_ct_anchors and review which of the existing
 * has been deleted in the content of a page.
 *
 * @see iterateTroughDOM()
 * @param DOMNode $domNode The DOMNode that has the content of the page that it is expected to look for
 * @param int $containerid The id of the container in which the content belongs to
 * @param array $del_arr The array that has all the previous records and will only left the ones that no longer are in the content
 * @return void It does not return nothing given that all the changes happen inside function
 */
function iterateTroughDOM(DOMNode $domNode, $containerid, & $del_arr) {
    global $DB;
    foreach ($domNode->childNodes as $node)
    {
        if ($node->hasAttributes()){
            foreach ($node->attributes as $key => $value) {
                if ($value->nodeName == 'id'){
                    $compare = $DB->sql_compare_text('reference_id') .' = '.$DB->sql_compare_text(':rfrid'). ' AND ' . $DB->sql_compare_text('id_container') . ' = ' . $DB->sql_compare_text(':ctnid');
                    if (!$DB->record_exists_sql("select * from mdl_block_cosy_ct_anchors where {$compare}", array('rfrid' => $value->nodeValue, 'ctnid' => $containerid))){
                        $record = new stdClass();
                        $record->name = $value->nodeValue;
                        $record->reference_id = $value->nodeValue;
                        $record->id_container = $containerid;
                        $DB->insert_record('block_cosy_ct_anchors', $record);
                    }
                    if ($del = array_search($value->nodeValue, $del_arr)) {
                        unset($del_arr[$del]);
                    }
                }
            }
        }
        if($node->hasChildNodes()) {
            iterateTroughDOM($node, $containerid, $del_arr);
        }
    }
}