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
            $('button#id_submitbutton_edit_rec_obj').on('click', t.editrecobj);
            t.recobjid = $('input#recobj_id').val();
        },
        description: null,
        category: null,
        approach: null,
        media: null,
        type: null,
        recobjid: null,
    
        editrecobj: function(){
            var desc = document.getElementById('id_description_rec_obj').value;
            var comment = document.getElementById('id_comment_rec_obj').value;
            var category = document.getElementById('id_category_rec_obj').value;
            var approach = document.getElementById('id_approach_rec_obj').value;
            var type = document.getElementById('id_type_rec_obj').value;
            var yourArray = [];
            var mediaids = '';
            $("input:checkbox[name=type]:checked").each(function(){
                yourArray.push($(this).val());
                mediaids += $(this).val() + '/';
            });
            M.util.js_pending('block_cosy_ct-edit_rec_object');
            Ajax.call([{
                methodname: 'block_cosy_ct_edit_rec_object',
                args: {
                    recobjid : t.recobjid,
                    description : desc,
                    comment : comment,
                    categoryid: category,
                    approachid: approach,
                    typeid: type,
                    mediasid: mediaids,
                }
            }])[0].done(function(response){
                alert('The recommendation object was updated');
                window.onbeforeunload = null;
                location.reload();
                M.util.js_complete('block_cosy_ct-edit_rec_object');
            });
        },
    }
    return t;
});