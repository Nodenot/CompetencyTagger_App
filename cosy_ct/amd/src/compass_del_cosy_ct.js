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
            $('button#id_submitbutton_del_comp_ass').on('click', t.delcompass);
            t.compid = $('input#comp_id').val();
        },
        compid: null,
    
        delcompass: function(){
            M.util.js_pending('block_cosy_ct-delete_comp_association');
            Ajax.call([{
                methodname: 'block_cosy_ct_delete_comp_association',
                args: {
                    compid : t.compid,
                }
            }])[0].done(function(response){
                alert('The competency association was deleted');
                $('button#id_cancelbutton_deletecompass').click();
                M.util.js_complete('block_cosy_ct-delete_comp_association');
            });
        },
    }
    return t;
});