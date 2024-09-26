<?php

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->libdir . '/externallib.php');

class block_cosy_ct_external extends external_api {
    public static function change_anchor_name_parameters() {
        return new external_function_parameters([
            'anchorid' => new external_value(PARAM_INT, 'Anchor id.'),
            'newanchorname' => new external_value(PARAM_RAW, 'The new name of the anchor.'),
        ]);
    }
    public static function change_anchor_name_returns() {
        return new external_single_structure([
            'newanchorname' => new external_value(PARAM_RAW, 'The new name of the anchor that was changed.'),
        ]);
    }
    public static function change_anchor_name(int $anchorid, string $newanchorname) {
        global $DB;
        $selected_anchor = $DB->get_record('block_cosy_ct_anchors', ['id' => $anchorid]);
        $selected_anchor->name = $newanchorname;
        $DB->update_record('block_cosy_ct_anchors', $selected_anchor);
        $selected_anchor = $DB->get_record('block_cosy_ct_anchors', ['id' => $anchorid]);
        return ['newanchorname' => $selected_anchor->name];
    }

    public static function create_rec_object_parameters() {
        return new external_function_parameters([
            'description' => new external_value(PARAM_RAW, 'The description of the recommendation object.'),
            'anchorid' => new external_value(PARAM_INT, 'The id of the anchor that belongs to the recommendation object.'),
            'categoryid' => new external_value(PARAM_INT, 'The id of the category that belongs to the recommendation object.'),
            'approachid' => new external_value(PARAM_INT, 'The id of the approach that belongs to the recommendation object.'),
            'typeid' => new external_value(PARAM_INT, 'The id of the type that belongs to the recommendation object.'),
            'mediasid' => new external_value(PARAM_RAW, 'The ids of the media that belongs to the recommendation object.'),
        ]);
    }
    public static function create_rec_object_returns() {
        return new external_single_structure([
            'id' => new external_value(PARAM_INT, 'The id of the recommendation object that was created by this operation'),
        ]);
    }
    public static function create_rec_object(string $description, int $anchorid, int $categoryid, int $approachid, int $typeid, string $mediasid) {
        global $DB;
        $rec_obj_created = new stdClass();
        $rec_obj_created->description = $description;
        $rec_obj_created->anchorid = $anchorid;
        $rec_obj_created->typerecobjid = $typeid;
        $rec_obj_created->categoryid = $categoryid;
        $rec_obj_created->approachid = $approachid;
        $rec_obj_created->comment = 'Random comment';
        $rec_obj_id = $DB->insert_record('block_cosy_ct_recobj', $rec_obj_created, true);
        // Create record in block_cosy_ct_recobj_medias
        $mediasids = explode('/', $mediasid);
        array_pop($mediasids);
        foreach ($mediasids as $key => $value) {
            $recobj_media = new stdClass();
            $recobj_media->recobjid = $rec_obj_id;
            $recobj_media->mediaid = $value;
            $aux = $DB->insert_record('block_cosy_ct_recobj_medias', $recobj_media);
        }
        return ['id' => $rec_obj_id];
    }
    
    public static function edit_rec_object_parameters() {
        return new external_function_parameters([
            'recobjid' => new external_value(PARAM_INT, VALUE_REQUIRED, 'The id of the Recommendation Object.'),
            'description' => new external_value(PARAM_RAW, VALUE_OPTIONAL,'The description of the recommendation object.'),
            'comment' => new external_value(PARAM_RAW, VALUE_OPTIONAL,'The description of the recommendation object.'),
            'categoryid' => new external_value(PARAM_INT, VALUE_OPTIONAL, 'The id of the category that belongs to the recommendation object.'),
            'approachid' => new external_value(PARAM_INT, VALUE_OPTIONAL, 'The id of the approach that belongs to the recommendation object.'),
            'typeid' => new external_value(PARAM_INT, VALUE_OPTIONAL, 'The id of the type that belongs to the recommendation object.'),
            'mediasid' => new external_value(PARAM_RAW, VALUE_OPTIONAL, 'The ids of the media that belongs to the recommendation object.'),
        ]);
    }
    public static function edit_rec_object_returns() {
        return new external_single_structure([
            'id' => new external_value(PARAM_INT, 'The id of the recommendation object that was edited by this operation'),
        ]);
    }
    public static function edit_rec_object(int $recobjid, string $description, string $comment, int $categoryid, int $approachid, int $typeid, string $mediasid) {
        global $DB;
        $current = $DB->get_record('block_cosy_ct_recobj', ['id' => $recobjid]);
        $current->description = $description;
        $current->comment = $comment;
        $current->categoryid = $categoryid;
        $current->approachid = $approachid;
        $current->typerecobjid = $typeid;
        $DB->update_record('block_cosy_ct_recobj', $current);
        // Update now medias of recobj in block_cosy_ct_recobj_medias
        $DB->delete_records('block_cosy_ct_recobj_medias', ['recobjid' => $recobjid]);
        $mediasid = explode('/', $mediasid);
        array_pop($mediasid);
        foreach ($mediasid as $key => $value) {
            $recobj_media = new stdClass();
            $recobj_media->recobjid = $recobjid;
            $recobj_media->mediaid = $value;
            $aux = $DB->insert_record('block_cosy_ct_recobj_medias', $recobj_media);
        }
        return ['id' => $recobjid];
    }

    public static function delete_rec_object_parameters() {
        return new external_function_parameters([
            'recobjid' => new external_value(PARAM_INT, VALUE_REQUIRED, 'The id of the Recommendation Object.'),
        ]);
    }
    public static function delete_rec_object_returns() {
        return new external_single_structure([
            'id' => new external_value(PARAM_INT, 'The id of the recommendation object that was deleted by this operation'),
        ]);
    }
    public static function delete_rec_object(int $recobjid) {
        global $DB;
        $DB->delete_records('block_cosy_ct_recobj', ['id' => $recobjid]);
        $DB->delete_records('block_cosy_ct_recobj_medias', ['recobjid' => $recobjid]);
        return ['id' => $recobjid];
    }

    public static function delete_comp_association_parameters() {
        return new external_function_parameters([
            'compid' => new external_value(PARAM_INT, VALUE_REQUIRED, 'The id of the Association Competency.'),
        ]);
    }
    public static function delete_comp_association_returns() {
        return new external_single_structure([
            'id' => new external_value(PARAM_INT, 'The id of the association competency that was deleted by this operation'),
        ]);
    }
    public static function delete_comp_association(int $compid) {
        global $DB;
        $DB->delete_records('block_cosy_ct_recobj_knowho', ['id' => $compid]);
        return ['id' => $compid];
    }

    public static function create_comp_ass_parameters() {
        return new external_function_parameters([
            'recobjid' => new external_value(PARAM_INT, VALUE_REQUIRED, 'The id of the recommendation object that belongs to the competency association.'),
            'competenciesids' => new external_value(PARAM_RAW, VALUE_OPTIONAL, 'The ids of the competencies that belongs to the competency association.'),
        ]);
    }
    public static function create_comp_ass_returns() {
        return new external_single_structure([
            'success' => new external_value(PARAM_BOOL, 'Return true if it was a successful operation, false otherwise'),
        ]);
    }
    public static function create_comp_ass(int $recobjid, string $competenciesids) {
        global $DB;
        $DB->delete_records('block_cosy_ct_recobj_knowho', ['recobjid' => $recobjid]);
        $competenciesids = explode('/', $competenciesids);
        array_pop($competenciesids);
        foreach ($competenciesids as $key => $value) {
            $aux = new stdClass();
            $aux->recobjid = $recobjid;
            $aux->competencyid = $value;
            $aux->weight = 100;
            $DB->insert_record('block_cosy_ct_recobj_knowho', $aux);
        }
        return ['success' => true];
    }

    public static function get_compasses_parameters() {
        return new external_function_parameters([
            'recobjid' => new external_value(PARAM_INT, VALUE_REQUIRED, 'The id of the recommendation object that belongs to the competency association.'),
        ]);
    }
    public static function get_compasses_returns() {
        return new external_multiple_structure(
            new external_single_structure([
                'compassid' => new external_value(PARAM_INT, VALUE_REQUIRED, 'The id of the compass that belongs to the recobj.'),
            ])
        );
    }
    public static function get_compasses(int $recobjid) {
        global $DB;
        $compassesids = $DB->get_records('block_cosy_ct_recobj_knowho', ['recobjid' => $recobjid]);
        $toReturnarr = array();
        foreach ($compassesids as $key => $value) {
            $aux = new stdClass();
            $aux->compassid = $value->id;
            $toReturnarr[] = $aux;
        }
        return $toReturnarr;
    }

    public static function minus_weight_compass_parameters() {
        return new external_function_parameters([
            'compassid' => new external_value(PARAM_INT, VALUE_REQUIRED, 'The id of the recommendation object that belongs to the competency association.'),
        ]);
    }
    public static function minus_weight_compass_returns() {
        return new external_single_structure([
            'success' => new external_value(PARAM_BOOL, 'Return true if it was a successful operation, false otherwise'),
        ]);
    }
    public static function minus_weight_compass(int $compassid) {
        global $DB;
        $updated = $DB->get_record('block_cosy_ct_recobj_knowho', ['id' => $compassid]);
        $updated->weight = $updated->weight - 1;
        $DB->update_record('block_cosy_ct_recobj_knowho', $updated);
        return ['success' => true];
    }

    public static function plus_weight_compass_parameters() {
        return new external_function_parameters([
            'compassid' => new external_value(PARAM_INT, VALUE_REQUIRED, 'The id of the recommendation object that belongs to the competency association.'),
        ]);
    }
    public static function plus_weight_compass_returns() {
        return new external_single_structure([
            'success' => new external_value(PARAM_BOOL, 'Return true if it was a successful operation, false otherwise'),
        ]);
    }
    public static function plus_weight_compass(int $compassid) {
        global $DB;
        $updated = $DB->get_record('block_cosy_ct_recobj_knowho', ['id' => $compassid]);
        $updated->weight = $updated->weight + 1;
        $DB->update_record('block_cosy_ct_recobj_knowho', $updated);
        return ['success' => true];
    }

    public static function change_weight_compass_parameters() {
        return new external_function_parameters([
            'compassid' => new external_value(PARAM_INT, VALUE_REQUIRED, 'The id of the recommendation object that belongs to the competency association.'),
            'customweight' => new external_value(PARAM_INT, VALUE_REQUIRED, 'Custom weight to change in compass.'),
        ]);
    }
    public static function change_weight_compass_returns() {
        return new external_single_structure([
            'success' => new external_value(PARAM_BOOL, 'Return true if it was a successful operation, false otherwise'),
        ]);
    }
    public static function change_weight_compass(int $compassid, int $customweight) {
        global $DB;
        $updated = $DB->get_record('block_cosy_ct_recobj_knowho', ['id' => $compassid]);
        $updated->weight = $customweight;
        $DB->update_record('block_cosy_ct_recobj_knowho', $updated);
        return ['success' => true];
    }
}