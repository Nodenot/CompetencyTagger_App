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

require_once("$CFG->libdir/formslib.php");

/**
 * Page block class form.
 * 
 * This class is a form that let interact the user with the content of the page in which is displayed
 * using the form_cosy_ct.js amd file (amd/src/form_cosy_ct.js). It should be displayed only in a
 * PAGE course_module context.
 *
 * @package    block_cosy_ct
 * @copyright  2023 J. Cornejo-Lupa
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class cosy_ct_pageblockform extends moodleform {
    public function definition() {
        global $CFG, $DB, $PAGE;
        // Dont forget the underscore!
        $mform = & $this->_form;

        $mform->updateAttributes(['id' => 'pageblockform']);
        
        // Setting id of course module, this way we can access trough js code.
        $mform->setType('cmid', PARAM_INT);
        $mform->addElement('hidden','cmid', $this->_customdata['cmid'], array(
            'id' => 'id_cmid',
        ));

        // Setting id of course, this way we can access trough js code.
        $mform->setType('courseid', PARAM_INT);
        $mform->addElement('hidden','courseid', $this->_customdata['courseid'], array(
            'id' => 'id_courseid',
        ));

        // Setting id of blockid, this way we can access trough js code.
        $mform->setType('blockid', PARAM_INT);
        $mform->addElement('hidden','blockid', $this->_customdata['blockid'], array(
            'id' => 'id_blockid',
        ));

        // Call DB for retrieving all the anchors inside this page.
        $container = $DB->get_record('block_cosy_ct_containers', ['idin_course_modules' => $this->_customdata['cmid']]);
        $compare = $DB->sql_compare_text('id_container').'='.$DB->sql_compare_text(':containerid');
        $anchors = $DB->get_records_sql("select * from mdl_block_cosy_ct_anchors where {$compare}",array('containerid' => $container->id));
	
        // call DB for retrieving all the ROs inside this page
        $compare2 = $DB->sql_compare_text('a.id_container').'='.$DB->sql_compare_text(':containerid');
        $ros = $DB->get_records_sql("select r.id, r.description, r.anchorid, r.comment from mdl_block_cosy_ct_recobj r, mdl_block_cosy_ct_anchors a where {$compare2} and r.anchorid = a.id",array('containerid' => $container->id)); // here try to add ROs list linked to the anchor

	    // Iterate and save anchors in array to select element.
        $anchorsidarr = array();
        $anchorsidarr['-'] = '-';

        // Concatenate id and reference anchor with: #_# to skip process to call DB inside JS code.
        foreach ($anchors as $key => $value) {
            $new_id = $value->id . '#_#' . $value->reference_id;
            $anchorsidarr[$new_id] = $value->name;
        }
	
        // Setting select element with all the anchors in the previous array.
        $mform->setType('anchor', PARAM_RAW);
        $select_reference = $mform->addElement('select', 'anchor', get_string('selectanchorspage', 'block_cosy_ct'), $anchorsidarr);
        $select_reference->setSelected('-');

        // Adding to the DOM the list of ROs 
        $mform->addElement('html', '<ul id="ros_ul">');		
        foreach ($ros as $key => $value) {
            $mform->addElement('html', '<li hidden=true id="'.$value->id.'#_#'.$value->anchorid.'">'. $value->description .'</li>'); // RoId#_#AnchorsLinkedId
        }		
        $mform->addElement('html', '</ul>');

        
        // Set the JS that will get called when this form is rendered.
        $PAGE->requires->js_call_amd('block_cosy_ct/form_cosy_ct', 'init', [get_config('block_cosy_ct', 'url')]);
    }
}
