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
class cosy_ct_dummy_comp_form extends moodleform {
    public function definition() {
        global $DB, $PAGE;
        $mform = & $this->_form;
        $mform->updateAttributes(['id' => 'dummy_comp_form']);

        $mform->setType('recobj_id', PARAM_INT);
        $mform->addElement('hidden', 'recobj_id', $this->_customdata['recobjid'], array(
            'id' => 'recobj_id',
        ));

        $PAGE->requires->js_call_amd('block_cosy_ct/compass_add_cosy_ct', 'init');
    }
}