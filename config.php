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
 * Logini theme config.
 *
 * @package    theme_logini
 * @copyright  2026 Softosmith.com and Asad Ali
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . '/lib.php');

$THEME->name            = 'logini';
$THEME->parents         = ['boost'];
$THEME->usefallback     = true;
$THEME->sheets          = [];
$THEME->editor_sheets   = [];
$THEME->editor_scss     = ['editor'];

$THEME->scss = function($theme) {
    return theme_logini_get_main_scss_content($theme);
};

$THEME->layouts = [
    // Override the login layout.
    'login' => [
        'file'    => 'login.php',
        'regions' => [],
        'options' => ['langmenu' => true],
    ],
    // The rest of the layouts will inherit from Boost since we don't define them here.
];

$THEME->enable_dock            = false;
$THEME->prescsscallback        = 'theme_logini_get_pre_scss';
$THEME->extrascsscallback      = 'theme_logini_get_extra_scss';
$THEME->precompiledcsscallback = 'theme_logini_get_precompiled_css';
$THEME->yuicssmodules          = [];
$THEME->rendererfactory        = 'theme_overridden_renderer_factory';
$THEME->requiredblocks         = '';
$THEME->addblockposition       = BLOCK_ADDBLOCK_POSITION_FLATNAV;
$THEME->iconsystem             = \core\output\icon_system::FONTAWESOME;
$THEME->haseditswitch          = true;
$THEME->usescourseindex        = true;
$THEME->activityheaderconfig   = ['notitle' => true];
