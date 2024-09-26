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

define(['jquery', 'core/ajax'], function($, Ajax) {
    var t = {
        init: function() {
            t.recobjid = $('input#recobj_id').val();
            t.compassid = $('input#compass_id').val();
            $('button#id_submitbutton_change_custom_weight').on('click', t.changeweight);
        },
        recobjid: null,
        compassid: null,
        changeweight: function(){
            var customweight = document.getElementById('id_custom_weight').value;
            M.util.js_pending('block_cosy_ct-change_weight_compass');
            Ajax.call([{
                methodname: 'block_cosy_ct_change_weight_compass',
                args: { 
                    compassid: t.compassid,
                    customweight: customweight,
                 }
            }])[0].done(function(response){
                alert('Change updated');
                window.onbeforeunload = null;
                $('button#id_cancelbutton_change_custom_weight').click();
                M.util.js_complete('block_cosy_ct-change_weight_compass');
            });
        }
    }
    return t;
});