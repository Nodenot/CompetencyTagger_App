<?php

defined('MOODLE_INTERNAL') || die;

if ($hassiteconfig) {
    if ($ADMIN->fulltree) {

        $settings->add(new admin_setting_heading('cosy_ctblockdisplayconfiguration', 
            get_string('blockDisplayConfiguration', 'block_cosy_ct'), 
            get_string('blockDisplayConfigurationDescription', 'block_cosy_ct')));

        $settings->add(new admin_setting_configtext('block_cosy_ct/url',
            get_string('urlInterfaceGraph', 'block_cosy_ct'), 
            get_string('urlInterfaceGraph', 'block_cosy_ct'), 
            ''));
    }

}
