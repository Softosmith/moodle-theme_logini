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
 * Logini theme callbacks.
 *
 * @package    theme_logini
 * @copyright  2026 Softosmith.com and Asad Ali
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
/**
 * Returns the main SCSS content for the theme.
 * Concatenates: pre.scss → preset file → post.scss
 *
 * @param theme_config $theme
 * @return string
 */
function theme_logini_get_main_scss_content($theme) {
    global $CFG;

    $scss = '';
    $filename = !empty($theme->settings->preset) ? $theme->settings->preset : null;
    $fs = get_file_storage();
    $context = context_system::instance();

    // No pre.scss in logini to keep it simple, but we could add it if needed.
    // $scss .= file_get_contents($CFG->dirroot . '/theme/logini/scss/logini/pre.scss');.

    if ($filename === 'default.scss') {
        $scss .= file_get_contents($CFG->dirroot . '/theme/boost/scss/preset/default.scss');
    } else if ($filename === 'plain.scss') {
        $scss .= file_get_contents($CFG->dirroot . '/theme/boost/scss/preset/plain.scss');
    } else if ($filename && ($presetfile = $fs->get_file($context->id, 'theme_logini', 'preset', 0, '/', $filename))) {
        $scss .= $presetfile->get_content();
    } else {
        // Safety fallback.
        $scss .= file_get_contents($CFG->dirroot . '/theme/boost/scss/preset/default.scss');
    }

    $scss .= file_get_contents($CFG->dirroot . '/theme/logini/scss/logini/post.scss');

    return $scss;
}

/**
 * Injects SCSS variables before Bootstrap compiles.
 * Handles brand colour and any raw pre-SCSS from admin settings.
 *
 * @param theme_config $theme
 * @return string
 */
function theme_logini_get_pre_scss($theme) {
    $scss = '';

    $configurable = [
        'brandcolor' => ['primary'],
    ];

    foreach ($configurable as $configkey => $targets) {
        $value = isset($theme->settings->{$configkey}) ? $theme->settings->{$configkey} : null;
        if (empty($value)) {
            continue;
        }
        array_map(function($target) use (&$scss, $value) {
            $scss .= '$' . $target . ': ' . $value . ";\n";
        }, (array) $targets);
    }

    if (defined('BEHAT_SITE_RUNNING')) {
        $scss .= "\$behatsite: true;\n";
    }

    if (!empty($theme->settings->scsspre)) {
        $scss .= $theme->settings->scsspre;
    }

    return $scss;
}

/**
 * Appends extra SCSS after compilation (e.g. raw admin SCSS textarea).
 *
 * @param theme_config $theme
 * @return string
 */
function theme_logini_get_extra_scss($theme) {
    $content = '';

    // Login left panel background.
    $loginleftbg = $theme->settings->loginleftbg ?? '';
    if (!empty($loginleftbg)) {
        $content .= "body.path-login .logini-login-left { background: {$loginleftbg} !important; }\n";
    }

    // Login right panel background (overrides brand colour).
    $loginrightbg = $theme->settings->loginrightbg ?? '';
    if (!empty($loginrightbg)) {
        $content .= "body.path-login .logini-login-right { background: {$loginrightbg} !important; }\n";
    }

    // Login button colour.
    $loginbtncolor = $theme->settings->loginbtncolor ?? '';
    if (!empty($loginbtncolor)) {
        $content .= "body.path-login .logini-login-form-wrap .btn-primary { " .
                    "background: {$loginbtncolor} !important; " .
                    "border-color: {$loginbtncolor} !important; }\n";
    }

    if (!empty($theme->settings->scss)) {
        $content .= $theme->settings->scss;
    }

    return $content;
}

/**
 * Returns the precompiled CSS fallback (used when SCSS compilation is unavailable).
 *
 * @return string
 */
function theme_logini_get_precompiled_css() {
    // If you need a fallback CSS file. For now returning empty string or default.
    return '';
}

/**
 * Serves files uploaded via theme settings (e.g. background images).
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param context  $context
 * @param string   $filearea
 * @param array    $args
 * @param bool     $forcedownload
 * @param array    $options
 * @return bool
 */
function theme_logini_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = []) {
    if ($context->contextlevel == CONTEXT_SYSTEM &&
            in_array($filearea, ['backgroundimage', 'loginslide1image', 'loginslide2image', 'loginslide3image'])) {
        $theme = theme_config::load('logini');
        if (!array_key_exists('cacheability', $options)) {
            $options['cacheability'] = 'public';
        }
        return $theme->setting_file_serve($filearea, $args, $forcedownload, $options);
    }
    send_file_not_found();
}
