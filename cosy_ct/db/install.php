<?php

function xmldb_block_cosy_ct_install() {
    global $DB;
    $rec_obj_type = array(
        '0' => 'assessment',
        '1' => 'application',
        '2' => 'lecture',
        '3' => 'tutorial'
    );
    $approach_type = array(
        '0' => 'example',
        '1' => 'definition',
        '2' => 'description',
        '3' => 'solution',
        '4' => 'optimal_solution',
        '5' => 'solution_tip',
        '6' => 'vigilance_point',
        '7' => 'futher_information',
        '8' => 'synthesis',
        '9' => 'application',
        '10' => 'quizz'
    );
    $category_type = array(
        '0' => 'concept_apprenticeship',
        '1' => 'methodology_apprenticeship'
    );
    $media_type = array(
        '0' => 'text',
        '1' => 'code',
        '2' => 'pseudocode',
        '3' => 'image',
        '4' => 'video',
        '5' => 'animation',
        '6' => 'schema'
    );
    foreach ($rec_obj_type as $key => $value) {
        $record = new stdClass();
        $visual_name = str_replace('_', ' ', $value);
        $visual_name = ucfirst($visual_name);
        $record->type_name = $value;
        $record->display_name = $visual_name;
        $DB->insert_record('block_cosy_ct_typesrecobj', $record);
    }
    foreach ($approach_type as $key => $value) {
        $record = new stdClass();
        $visual_name = str_replace('_', ' ', $value);
        $visual_name = ucfirst($visual_name);
        $record->approach_name = $value;
        $record->display_name = $visual_name;
        $DB->insert_record('block_cosy_ct_approaches', $record);
    }
    foreach ($category_type as $key => $value) {
        $record = new stdClass();
        $visual_name = str_replace('_', ' ', $value);
        $visual_name = ucfirst($visual_name);
        $record->category_name = $value;
        $record->display_name = $visual_name;
        $DB->insert_record('block_cosy_ct_categories', $record);
    }
    foreach ($media_type as $key => $value) {
        $record = new stdClass();
        $visual_name = str_replace('_', ' ', $value);
        $visual_name = ucfirst($visual_name);
        $record->media_name = $value;
        $record->display_name = $visual_name;
        $DB->insert_record('block_cosy_ct_medias', $record);
    }
}