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

// For initial values in Block.
$string['pluginname'] = 'COSY Competency Tagger';
$string['cosy_ct'] = 'COSY CT';
$string['titleblock'] = 'Manage COSY CT Tags';

// For block in course context.
$string['generalsection'] = 'General section';
$string['availablesections'] = 'Welcome to the IUT Block, here you can customize the anchors to your course.';
$string['supportedcm'] = 'Right now this are the resources that the block support.';
$string['pageblockcm'] = 'Page';
$string['revealblockcm'] = 'Page - Reveal';
$string['forumblockcm'] = 'Forum';
$string['quizblockcm'] = 'Quiz';
$string['bookblockcm'] = 'Book';

// For block in CM context.
$string['availablelabels'] = 'Welcome to the IUT Block, here you can customize the anchors that are in this resource';
$string['moodlesrc'] = 'Source moodle';
$string['extsrc'] = 'Source external';

// For page block form.
$string['selectanchorspage'] = 'Anchors:';
$string['selectrospage'] = 'Recommendation Objects attached:';

// For anchor visor IUT.
$string['pagevisortitle'] = 'IUT Visor';
$string['breadcrumbsanchorpagevisor'] = ' - IUT Anchor';
$string['returntomodpage'] = 'Returning to previous page.';
$string['successchangename'] = 'The name of the anchor was changed.';
$string['containertitle'] = 'Container Information';
$string['containertypelabel'] = 'Type:';
$string['containercourselabel'] = 'Course:';
$string['containermodulelabel'] = 'Module:';
$string['tablerowid'] = 'ID';
$string['tablerowdescription'] = 'Description';
$string['tablerowapproach'] = 'Approach';
$string['tablerowmedia'] = 'Media';
$string['recommendationtitle'] = 'Recommendation Objects';
$string['anchorvisortitle'] = 'Anchor Info.';
$string['recobjactions'] = 'Actions';


// For chganchorname form.
$string['newanchorname'] = 'Anchor name:';
$string['changename'] = 'Change name';

// For capabilities.
$string['cosy_ct:addinstance'] = 'Add an instance of block';
$string['cosy_ct:view'] = 'View the IUT block';

// For change the title of block according to context.
$string['coursetitleblock'] = 'Manage IUT Tags - Course';
$string['cmtitleblock'] = 'Manage IUT Tags - Course Module';

// For rec_obj_add PAGE
$string['recobjaddtitlepage'] = 'Add a new Recommendation Object';
$string['recobjbreadcrumbs'] = 'Rec. Obj. Info.';
$string['generalconceptsrecobj'] = 'General Characteristics';
$string['particularconceptsrecobj'] = 'Particular Characteristics';
$string['validatedata'] = 'Validate';
$string['canceldata'] = 'Cancel';
$string['competenciestabletitle'] = 'Competencies';
$string['tablerowshortname'] = 'Shortname';
$string['tablerowweight'] = 'Weight';
$string['compactions'] = 'Actions';

// For rec_obj_edit PAGE
$string['recobjedittitlepage'] = 'Edition of an object of recommendation';
$string['recobjid'] = 'Id: ';

// For rec_obj_edit PAGE
$string['deleteconfirmationbutton'] = 'Delete';
$string['deleteconfirmation'] = 'Are you sure you want to delete the recommendation oject?';

// For cosy_ct_desc_recobj_form
$string['descrecobjlabel'] = 'Description:';
$string['commrecobjlabel'] = 'Comment:';

// For cosy_ct_categories_recobj_form
$string['categoriesrecobjlabel'] = 'Category:';

// For cosy_ct_approaches_recobj_form
$string['approachesrecobjlabel'] = 'Approach:';

// For cosy_ct_types_recobj_form
$string['typesrecobjlabel'] = 'Type:';

// For cosy_ct_medias_recobj_form
$string['mediasrecobjlabel'] = 'Media:';

// For comp_ass_del PAGE
$string['compassdeltitlepage'] = 'Delete association of a competency to recommendation object';

// For comp_ass_custom_weight PAGE
$string['compassweighttitlepage'] = 'Change weight of competency association to recomendation object';
$string['selectformweight'] = 'Select the weight that want to change to this competency association.';
$string['changecustomweightbutton'] = 'Change Weight';

// For competencies_visor PAGE
$string['compvisortitle'] = 'Competencies selection';
$string['breadcrumbsanchorcompvisor'] = 'Competencies selection of ';
$string['idcompvisor'] = 'ID';
$string['shortnamecompvisor'] = 'Shortname';
$string['descriptioncompvisor'] = 'Description';
$string['frameworknamecompvisor'] = 'Framework - Name';
$string['frameworkdesccompvisor'] = 'Framework - Description';
$string['taxonomiescompvisor'] = 'Framework - Taxonomies';
$string['actionscompvisor'] = 'Actions';

// Settings
$string['blockDisplayConfiguration'] = 'Affichage des graphes';
$string['blockDisplayConfigurationDescription'] = 'Les réglages définis dans cette section concernent le rendu visuel des graphes.';
$string['urlInterfaceGraph'] = 'URL d\'affichage du graphe';
$string['urlInterfaceGraphDescription'] = 'Adresse web (URL) utilisée pour afficher les graphes représentant des cours. Par exemple : http://localhost:8080. Cette valeur doit être obligatoirement renseignée.';