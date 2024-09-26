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
 * @copyright   J. Cornejo-Lupa
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once("$CFG->libdir/formslib.php");

/**
 * Change anchor name class form.
 * 
 * This class is a form with the pourpose to change the name of an element in block_cosy_ct_anchors table.
 *
 * @package    block_cosy_ct
 * @copyright  2023 J. Cornejo-Lupa
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class cosy_ct_weight_comp_form extends moodleform {
    public function definition() {
        global $DB, $PAGE;
        $mform = & $this->_form;
        $mform->updateAttributes(['id' => 'weight_comp_form']);

        $mform->setType('recobj_id', PARAM_INT);
        $mform->addElement('hidden', 'recobj_id', $this->_customdata['recobj_id'], array(
            'id' => 'recobj_id',
        ));
        $mform->setType('compass_id', PARAM_INT);
        $mform->addElement('hidden', 'compass_id', $this->_customdata['compass_id'], array(
            'id' => 'compass_id',
        ));

        $current_weight = $DB->get_record('block_cosy_ct_recobj_knowho', ['id' => $this->_customdata['compass_id']]);

        $custom_weights = array(
            '0' => '0',
            '10' => '10',
            '20' => '20',
            '30' => '30',
            '40' => '40',
            '50' => '50',
            '60' => '60',
            '70' => '70',
            '80' => '80',
            '90' => '90',
            '100' => '100',
        );

        $select = $mform->addElement('select', 'custom_weight', get_string('selectformweight', 'block_cosy_ct'), $custom_weights);
        $select->setSelected($current_weight->weight);

        $PAGE->requires->js_call_amd('block_cosy_ct/weightcompform_cosy_ct', 'init');
    }
}