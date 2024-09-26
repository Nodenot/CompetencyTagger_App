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
 * @var functions
 */

/**
 * The purpose of this function it is to search in a DOMNode the tag iframe,
 * and return that DOMNode.
 *
 * @see findRevealSrc()
 * @param DOMNode $domNode The DOMNode in which will be search the tag iframe
 * @return DOMNode The DOMNode in which the tag iframe is located
 */
function findRevealSrc(DOMNode $domNode) {
    return $domNode->getElementsByTagName('iframe')[0]->attributes->getNamedItem('src')->value;
}

/**
 * The purpose of this function it is to search in a DOMNode some element
 * with the 'revealsrc' id.
 *
 * @see isRevealPage()
 * @param DOMNode $domNode The DOMNode in which will be search the id 'revealsrc'
 * @return bool true if the 'revealsrc' id it is found false if not.
 */
function isRevealPage(DOMNode $domNode) {
    if ($domNode->getElementById('revealsrc'))
        return true;
    return false;
}

/**
 * The purpose of this function it is to delete the records from
 * block_cosy_ct_anchors that were deleted inside a content of a reveal_page.
 *
 * @see delExisting()
 * @param array $existing An array that contains all the 'reference_id's that has to be deleted
 * @param int $containerid The id of the container in which all of the elements of the $exisintg param belongs to
 * @return void It does not return nothing given that all the changes happen inside function
 */
function delExistingReveal($existing, $containerid) {
    global $DB;
    foreach ($existing as $key => $value) {
        $compare = $DB->sql_compare_text('reference_id') .' = '.$DB->sql_compare_text(':anchorid'). ' AND ' . $DB->sql_compare_text('id_container') . ' = ' . $DB->sql_compare_text(':ctnid');
        $rec = $DB->get_record_sql("select * from mdl_block_cosy_ct_anchors where {$compare}", array('anchorid' => $value, 'ctnid' => $containerid));
        $DB->delete_records('block_cosy_ct_anchors', ['id' => $rec->id]);
    }
}

/**
 * The purpose of this function it is to insert records into the table
 * block_cosy_ct_anchors from the content of a reveal_page, and to delete those
 * that does not exist anymore in the content of the given reveal_page.
 *
 * @see populateAnchors()
 * @param DOMNode $domNode The DOMNode that has the content of the reveal_page that it is expected to look for
 * @param int $containerid The id of the container in which the content belongs to
 * @return void It does not return nothing given that all the changes happen inside function
 */
function populateRevealAnchors(DOMNode $domNode, $containerid) {
    global $DB;
    // Search for all existing tags
    $compare = $DB->sql_compare_text('id_container').'='.$DB->sql_compare_text(':containerid');
    $existing = $DB->get_records_sql("select * from mdl_block_cosy_ct_anchors where {$compare}",array('containerid' => $containerid));
    $ex_arr = array();
    foreach ($existing as $key => $value) {
        $ex_arr[$value->id] = $value->reference_id;
    }
    iterateRevealDOM($domNode, $containerid, $ex_arr);
    delExistingReveal($ex_arr, $containerid);
}

/**
 * The purpose of this function it is to iterate trough the childs of DOMNode
 * to insert a new record in block_cosy_ct_anchors and review which of the existing
 * has been deleted in the content of a reveal_page.
 *
 * @see iterateTroughDOM()
 * @param DOMNode $domNode The DOMNode that has the content of the reveal_page that it is expected to look for
 * @param int $containerid The id of the container in which the content belongs to
 * @param array $del_arr The array that has all the previous records and will only left the ones that no longer are in the content
 * @return void It does not return nothing given that all the changes happen inside function
 */
function iterateRevealDOM(DOMNode $domNode, $containerid, & $del_arr) {
    global $DB;
    foreach ($domNode->childNodes as $node)
    {
        if ($node->hasAttributes()){
            foreach ($node->attributes as $key => $value) {
                if ($value->nodeName == 'id') {
                    $compare = $DB->sql_compare_text('reference_id') .' = '.$DB->sql_compare_text(':anchid'). ' AND ' . $DB->sql_compare_text('id_container') . ' = ' . $DB->sql_compare_text(':ctnid');
                    if (!$DB->record_exists_sql("select * from mdl_block_cosy_ct_anchors where {$compare}", array('anchid' => $value->nodeValue, 'ctnid' => $containerid))){
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
            iterateRevealDOM($node, $containerid, $del_arr);
        }
    }
}