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
            $('button#id_submitbutton_add_comp_ass').on('click', t.addcompass);
            t.recobjid = $('input#recobj_id').val();
        },
        recobjid: null,
    
        addcompass: function(){
            var competencyid = '';
            $("input:checkbox[name=compselected]:checked").each(function(){
                competencyid += $(this).val() + '/';
            });
            M.util.js_pending('block_cosy_ct-create_comp_ass');
            Ajax.call([{
                methodname: 'block_cosy_ct_create_comp_ass',
                args: {
                    recobjid: t.recobjid,
                    competenciesids: competencyid,
                }
            }])[0].done(function(response){
                alert('A competency was associated to the current recommendation object');
                $('button#id_cancelbutton_add_comp_ass').click();
                M.util.js_complete('block_cosy_ct-create_comp_ass');
            });
        },
    }
    return t;
});