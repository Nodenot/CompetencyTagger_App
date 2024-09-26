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
            $('select#id_anchor').on('change', t.selectAnchorChanged);
            t.lastSelAnchor = null;
            t.lastSelAnchorId = null;
            t.lastSelAnchorRef = null;
            t.chapterid = null;
            t.colourText = '#FE6847';
            t.cmid = null;
            t.courseid = null;
            t.blockid = null;
        },
        lastSelAnchor : null,
        lastSelAnchorId : null,
        lastSelAnchorRef : null,
        chapterid: null,
        colourText : null,
        cmid : null,
        courseid : null,
        blockid : null,

        selectAnchorChanged: function() {
            if ($('select#id_anchor').val() === t.lastSelAnchor) {
                return;
            }
            if ($('select#id_anchor').val() === '-') {
                return;
            }
            if (t.lastSelAnchorRef !== null) {
                var selectedAnchor = document.getElementById(t.lastSelAnchorRef);
                selectedAnchor.style = '';
            }

            // Separate ID and REFERENCE to set the values of t.
            t.lastSelAnchor = $('select#id_anchor').val();
            data = t.lastSelAnchor.split('#_#');
            t.lastSelAnchorId = data[0];
            t.lastSelAnchorRef = data[1];
            t.cmid = $('input#id_cmid').val();
            t.courseid = $('input#id_courseid').val();
            t.blockid = $('input#id_blockid').val();
            t.chapterid = $('input#id_chapterid').val();

            // We set the atributes and values of the tag a to NEWTAB functionality.
            var newTabAnchor = document.getElementById('iut_page_newtab_icon');
            if (t.chapterid) {
                newTabAnchor.href = 'view.php?id=' + t.cmid + '&chapterid=' + t.chapterid + '#' + t.lastSelAnchorRef;
            } else {
                newTabAnchor.href = 'view.php?id=' + t.cmid + '#' + t.lastSelAnchorRef;
            }
            
            newTabAnchor.target = '_blank';
            newTabAnchor.style = 'cursor:pointer;';

            var selectedAnchor = document.getElementById(t.lastSelAnchorRef);
            selectedAnchor.style = 'color:' + t.colourText + '; font-style: oblique; text-decoration: underline;' ;

            // We set the attributes and values of the tag a to EDITNAME functionality.
            var editNameElement = document.getElementById('iut_page_editname_icon');
            editNameElement.href = '/moodle/blocks/cosy_ct/anchor_visor.php?id='+t.cmid+'&courseid='+t.courseid+'&blockid='+t.blockid+'&anchorid='+t.lastSelAnchorId;
            editNameElement.style = 'cursor:pointer; color: #1177d1;';
            window.onbeforeunload = null;
        },
    };
    return t;
});