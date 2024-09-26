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
            $('button#iut_anchor_editname_icon').on('click', t.enableinput);
            $('input#id_name_anchor').on('change', t.changeclick);
            $('input#id_name_anchor').attr('disabled', 'disabled');
            t.colourText = '#FE6847';
            t.anchorid = $('input#id_anchor').val();
        },

        colourText: null,
        anchorid: null,

        changeclick: function(){
            M.util.js_pending('block_cosy_ct-change_anchor_name');
            Ajax.call([{
                methodname: 'block_cosy_ct_change_anchor_name',
                args: {anchorid: t.anchorid, newanchorname: $('input#id_name_anchor').val()}
            }])[0].done(function(response) {
                alert('The anchor name was changed');
                location.reload();
                M.util.js_complete('block_cosy_ct-change_anchor_name');
            });
        },

        enableinput: function(){
            var inputElement = document.getElementById('id_name_anchor');
            if (inputElement.getAttribute('disabled')) {
                inputElement.removeAttribute('disabled');
            } else {
                inputElement.setAttribute('disabled', 'disabled');
            }
        },
    };
    return t;
});