<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Plugin administration pages are defined here.
 *
 * @package     mod_ilsccheckmarket
 * @category    admin
 * @copyright   2024 ILSC
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    $settings = new admin_settingpage('mod_checkmarketsurvey_settings', new lang_string('pluginname', 'mod_ilsccheckmarket'));

    if ($ADMIN->fulltree) {

        $settings->add(new admin_setting_heading(
            'keyssettingssection',
            new lang_string('keyssettingssection', 'mod_ilsccheckmarket'),
            new lang_string('keyssettingssection_desc', 'mod_ilsccheckmarket')
        ));

        $settings->add(new admin_setting_configtext(
            'mod_ilsccheckmarket/xmasterkey',
            new lang_string('xmasterkey', 'mod_ilsccheckmarket'),
            new lang_string('xmasterkey_desc', 'mod_ilsccheckmarket'),
            ''
        ));

        $settings->add(new admin_setting_configtext(
            'mod_ilsccheckmarket/xkey',
            new lang_string('xkey', 'mod_ilsccheckmarket'),
            new lang_string('xkey_desc', 'mod_ilsccheckmarket'),
            ''
        ));
    }
}
