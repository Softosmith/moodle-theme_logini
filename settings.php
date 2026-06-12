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
 * Logini theme admin settings.
 *
 * @package    theme_logini
 * @copyright  2026 Softosmith.com and Asad Ali
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {
    $settings = new theme_boost_admin_settingspage_tabs('themesettinglogini', get_string('pluginname', 'theme_logini'));

    // Tab: General.
    $page = new admin_settingpage('theme_logini_general', get_string('generalsettings', 'theme_boost'));

    // Unaddable blocks.
    $setting = new admin_setting_configtext('theme_logini/unaddableblocks',
        get_string('unaddableblocks', 'theme_boost'), get_string('unaddableblocks_desc', 'theme_boost'),
        'navigation,settings,course_list', PARAM_TEXT);
    $page->add($setting);

    // Preset.
    $context = context_system::instance();
    $fs = get_file_storage();
    $files = $fs->get_area_files($context->id, 'theme_logini', 'preset', 0, 'itemid, filepath, filename', false);
    $choices = [];
    foreach ($files as $file) {
        $choices[$file->get_filename()] = $file->get_filename();
    }
    $choices['default.scss'] = 'default.scss';
    $choices['plain.scss']   = 'plain.scss';
    $setting = new admin_setting_configthemepreset('theme_logini/preset',
        get_string('preset', 'theme_boost'), get_string('preset_desc', 'theme_boost'),
        'default.scss', $choices, 'boost');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Preset files.
    $setting = new admin_setting_configstoredfile('theme_logini/presetfiles',
        get_string('presetfiles', 'theme_boost'), get_string('presetfiles_desc', 'theme_boost'),
        'preset', 0, ['maxfiles' => 20, 'accepted_types' => ['.scss']]);
    $page->add($setting);

    // Brand colour.
    $setting = new admin_setting_configcolourpicker('theme_logini/brandcolor',
        get_string('brandcolor', 'theme_logini'), get_string('brandcolor_desc', 'theme_logini'), '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Advanced SCSS.
    $setting = new admin_setting_scsscode('theme_logini/scsspre',
        get_string('rawscsspre', 'theme_logini'), get_string('rawscsspre_desc', 'theme_logini'), '', PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $setting = new admin_setting_scsscode('theme_logini/scss',
        get_string('rawscss', 'theme_logini'), get_string('rawscss_desc', 'theme_logini'), '', PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $settings->add($page);

    // Tab: Login.
    $page = new admin_settingpage('theme_logini_login', get_string('loginsettings', 'theme_logini'));

    // Content.

    $setting = new admin_setting_configtext('theme_logini/loginheadingtext',
        get_string('loginheadingtext', 'theme_logini'),
        get_string('loginheadingtext_desc', 'theme_logini'),
        '', PARAM_TEXT);
    $page->add($setting);

    $setting = new admin_setting_configtext('theme_logini/loginsubtitletext',
        get_string('loginsubtitletext', 'theme_logini'),
        get_string('loginsubtitletext_desc', 'theme_logini'),
        '', PARAM_TEXT);
    $page->add($setting);

    $setting = new admin_setting_configtext('theme_logini/signupheadingtext',
        get_string('signupheadingtext', 'theme_logini'),
        get_string('signupheadingtext_desc', 'theme_logini'),
        '', PARAM_TEXT);
    $page->add($setting);

    $setting = new admin_setting_configtext('theme_logini/signupsubtitletext',
        get_string('signupsubtitletext', 'theme_logini'),
        get_string('signupsubtitletext_desc', 'theme_logini'),
        '', PARAM_TEXT);
    $page->add($setting);

    $setting = new admin_setting_configtext('theme_logini/forgotpasswordheadingtext',
        get_string('forgotpasswordheadingtext', 'theme_logini'),
        get_string('forgotpasswordheadingtext_desc', 'theme_logini'),
        '', PARAM_TEXT);
    $page->add($setting);

    $setting = new admin_setting_configtext('theme_logini/forgotpasswordsubtitletext',
        get_string('forgotpasswordsubtitletext', 'theme_logini'),
        get_string('forgotpasswordsubtitletext_desc', 'theme_logini'),
        '', PARAM_TEXT);
    $page->add($setting);

    $setting = new admin_setting_configtext('theme_logini/loginfootertext',
        get_string('loginfootertext', 'theme_logini'),
        get_string('loginfootertext_desc', 'theme_logini'),
        '', PARAM_TEXT);
    $page->add($setting);

    $setting = new admin_setting_configselect('theme_logini/loginlogotype',
        get_string('loginlogotype', 'theme_logini'),
        get_string('loginlogotype_desc', 'theme_logini'),
        'icon_name',
        [
            'icon_name' => get_string('loginlogotype_iconname', 'theme_logini'),
            'icon_only' => get_string('loginlogotype_icononly', 'theme_logini'),
            'text_only' => get_string('loginlogotype_textonly', 'theme_logini'),
            'none'      => get_string('loginlogotype_none', 'theme_logini'),
        ]);
    $page->add($setting);

    $setting = new admin_setting_configtext('theme_logini/loginlogoiconclass',
        get_string('loginlogoiconclass', 'theme_logini'),
        get_string('loginlogoiconclass_desc', 'theme_logini'),
        'fa-graduation-cap', PARAM_TEXT);
    $page->add($setting);

    // Right Panel.

    $setting = new admin_setting_configcheckbox('theme_logini/loginhidecards',
        get_string('loginhidecards', 'theme_logini'),
        get_string('loginhidecards_desc', 'theme_logini'), 0);
    $page->add($setting);

    $setting = new admin_setting_configtext('theme_logini/logincardcourse',
        get_string('logincardcourse', 'theme_logini'),
        get_string('logincardcourse_desc', 'theme_logini'),
        '', PARAM_TEXT);
    $page->add($setting);
    $page->hide_if('theme_logini/logincardcourse', 'theme_logini/loginhidecards', 'checked');

    $setting = new admin_setting_configtextarea('theme_logini/logincarddesc',
        get_string('logincarddesc', 'theme_logini'),
        get_string('logincarddesc_desc', 'theme_logini'),
        '', PARAM_TEXT);
    $page->add($setting);
    $page->hide_if('theme_logini/logincarddesc', 'theme_logini/loginhidecards', 'checked');

    $setting = new admin_setting_configtext('theme_logini/logincardlevel',
        get_string('logincardlevel', 'theme_logini'),
        get_string('logincardlevel_desc', 'theme_logini'),
        '', PARAM_TEXT);
    $page->add($setting);
    $page->hide_if('theme_logini/logincardlevel', 'theme_logini/loginhidecards', 'checked');

    $setting = new admin_setting_configtext('theme_logini/logincardduration',
        get_string('logincardduration', 'theme_logini'),
        get_string('logincardduration_desc', 'theme_logini'),
        '', PARAM_TEXT);
    $page->add($setting);
    $page->hide_if('theme_logini/logincardduration', 'theme_logini/loginhidecards', 'checked');

    $setting = new admin_setting_configtext('theme_logini/logincardrating',
        get_string('logincardrating', 'theme_logini'),
        get_string('logincardrating_desc', 'theme_logini'),
        '', PARAM_TEXT);
    $page->add($setting);
    $page->hide_if('theme_logini/logincardrating', 'theme_logini/loginhidecards', 'checked');

    $setting = new admin_setting_configtext('theme_logini/logincardratingcount',
        get_string('logincardratingcount', 'theme_logini'),
        get_string('logincardratingcount_desc', 'theme_logini'),
        '', PARAM_TEXT);
    $page->add($setting);
    $page->hide_if('theme_logini/logincardratingcount', 'theme_logini/loginhidecards', 'checked');

    $setting = new admin_setting_configcheckbox('theme_logini/loginhideslider',
        get_string('loginhideslider', 'theme_logini'),
        get_string('loginhideslider_desc', 'theme_logini'), 0);
    $page->add($setting);

    $setting = new admin_setting_configtext('theme_logini/loginslide1heading',
        get_string('loginslide1heading', 'theme_logini'),
        get_string('loginslide1heading_desc', 'theme_logini'),
        '', PARAM_TEXT);
    $page->add($setting);

    $setting = new admin_setting_configtextarea('theme_logini/loginslide1body',
        get_string('loginslide1body', 'theme_logini'),
        get_string('loginslide1body_desc', 'theme_logini'),
        '', PARAM_TEXT);
    $page->add($setting);

    $setting = new admin_setting_configstoredfile('theme_logini/loginslide1image',
        get_string('loginslide1image', 'theme_logini'),
        get_string('loginslide1image_desc', 'theme_logini'),
        'loginslide1image', 0, ['maxfiles' => 1, 'accepted_types' => 'web_image']);
    $page->add($setting);

    $setting = new admin_setting_configtext('theme_logini/loginslide2heading',
        get_string('loginslide2heading', 'theme_logini'),
        get_string('loginslide2heading_desc', 'theme_logini'),
        '', PARAM_TEXT);
    $page->add($setting);

    $setting = new admin_setting_configtextarea('theme_logini/loginslide2body',
        get_string('loginslide2body', 'theme_logini'),
        get_string('loginslide2body_desc', 'theme_logini'),
        '', PARAM_TEXT);
    $page->add($setting);

    $setting = new admin_setting_configstoredfile('theme_logini/loginslide2image',
        get_string('loginslide2image', 'theme_logini'),
        get_string('loginslide2image_desc', 'theme_logini'),
        'loginslide2image', 0, ['maxfiles' => 1, 'accepted_types' => 'web_image']);
    $page->add($setting);

    $setting = new admin_setting_configtext('theme_logini/loginslide3heading',
        get_string('loginslide3heading', 'theme_logini'),
        get_string('loginslide3heading_desc', 'theme_logini'),
        '', PARAM_TEXT);
    $page->add($setting);

    $setting = new admin_setting_configtextarea('theme_logini/loginslide3body',
        get_string('loginslide3body', 'theme_logini'),
        get_string('loginslide3body_desc', 'theme_logini'),
        '', PARAM_TEXT);
    $page->add($setting);

    $setting = new admin_setting_configstoredfile('theme_logini/loginslide3image',
        get_string('loginslide3image', 'theme_logini'),
        get_string('loginslide3image_desc', 'theme_logini'),
        'loginslide3image', 0, ['maxfiles' => 1, 'accepted_types' => 'web_image']);
    $page->add($setting);

    // Colors.

    $setting = new admin_setting_configcolourpicker('theme_logini/loginleftbg',
        get_string('loginleftbg', 'theme_logini'),
        get_string('loginleftbg_desc', 'theme_logini'), '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $setting = new admin_setting_configcolourpicker('theme_logini/loginrightbg',
        get_string('loginrightbg', 'theme_logini'),
        get_string('loginrightbg_desc', 'theme_logini'), '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $setting = new admin_setting_configcolourpicker('theme_logini/loginbtncolor',
        get_string('loginbtncolor', 'theme_logini'),
        get_string('loginbtncolor_desc', 'theme_logini'), '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $settings->add($page);
}
