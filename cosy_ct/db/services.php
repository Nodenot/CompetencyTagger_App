<?php
defined('MOODLE_INTERNAL') || die();

$functions = [
    'block_cosy_ct_change_anchor_name' => [
        'classname' => 'block_cosy_ct_external',
        'methodname' => 'change_anchor_name',
        'classpath' => 'blocks/cosy_ct/classes/external.php',
        'description' => 'Use to change an anchor name in DB.',
        'type' => 'write',
        'ajax' => true,
    ],
    'block_cosy_ct_create_rec_object' => [
        'classname' => 'block_cosy_ct_external',
        'methodname' => 'create_rec_object',
        'classpath' => 'blocks/cosy_ct/classes/external.php',
        'description' => 'Use to create a recommendation object in DB.',
        'type' => 'write',
        'ajax' => true,
    ],
    'block_cosy_ct_create_comp_ass' => [
        'classname' => 'block_cosy_ct_external',
        'methodname' => 'create_comp_ass',
        'classpath' => 'blocks/cosy_ct/classes/external.php',
        'description' => 'Use to create an association between competency and a rec_obj in DB.',
        'type' => 'write',
        'ajax' => true,
    ],
    'block_cosy_ct_edit_rec_object' => [
        'classname' => 'block_cosy_ct_external',
        'methodname' => 'edit_rec_object',
        'classpath' => 'blocks/cosy_ct/classes/external.php',
        'description' => 'Use to edit a recommendation object in DB.',
        'type' => 'write',
        'ajax' => true,
    ],
    'block_cosy_ct_delete_rec_object' => [
        'classname' => 'block_cosy_ct_external',
        'methodname' => 'delete_rec_object',
        'classpath' => 'blocks/cosy_ct/classes/external.php',
        'description' => 'Use to delete a recommendation object in DB.',
        'type' => 'write',
        'ajax' => true,
    ],
    'block_cosy_ct_delete_comp_association' => [
        'classname' => 'block_cosy_ct_external',
        'methodname' => 'delete_comp_association',
        'classpath' => 'blocks/cosy_ct/classes/external.php',
        'description' => 'Use to delete an association between competency and a rec_obj in DB.',
        'type' => 'write',
        'ajax' => true,
    ],
    'block_cosy_ct_get_compasses' => [
        'classname' => 'block_cosy_ct_external',
        'methodname' => 'get_compasses',
        'classpath' => 'blocks/cosy_ct/classes/external.php',
        'description' => 'Use to list all of the compassociations according to recobj id in DB.',
        'type' => 'read',
        'ajax' => true,
    ],
    'block_cosy_ct_minus_weight_compass' => [
        'classname' => 'block_cosy_ct_external',
        'methodname' => 'minus_weight_compass',
        'classpath' => 'blocks/cosy_ct/classes/external.php',
        'description' => 'Use to diminish the weight of the competence association to recobj in DB.',
        'type' => 'write',
        'ajax' => true,
    ],
    'block_cosy_ct_plus_weight_compass' => [
        'classname' => 'block_cosy_ct_external',
        'methodname' => 'plus_weight_compass',
        'classpath' => 'blocks/cosy_ct/classes/external.php',
        'description' => 'Use to increase the weight of the competence association to recobj in DB.',
        'type' => 'write',
        'ajax' => true,
    ],
    'block_cosy_ct_change_weight_compass' => [
        'classname' => 'block_cosy_ct_external',
        'methodname' => 'change_weight_compass',
        'classpath' => 'blocks/cosy_ct/classes/external.php',
        'description' => 'Use to change the weight in custom way of the competence association to recobj in DB.',
        'type' => 'write',
        'ajax' => true,
    ],
];

$services = array(
    'Custom Services cosy_ct' => array(
        'functions' => array(
            'block_cosy_ct_change_anchor_name',
            'block_cosy_ct_create_rec_object',
            'block_cosy_ct_edit_rec_object',
            'block_cosy_ct_delete_rec_object',
            'block_cosy_ct_delete_comp_association',
            'block_cosy_ct_get_compasses'
        ),
        'restrictedusers' => 0,
        'enabled' => 1,
    )
);