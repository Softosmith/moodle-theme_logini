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
 * Custom login page layout for the Logini theme.
 *
 * @package   theme_logini
 * @copyright 2026 Softosmith.com and Asad Ali
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$PAGE->requires->css(new moodle_url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap'));

$bodyattributes = $OUTPUT->body_attributes();

$languagemenu = new \core\output\language_menu($PAGE);
$langmenu = $languagemenu->export_for_template($OUTPUT);

$loginitheme = theme_config::load('logini');
$s = $loginitheme->settings ?? new stdClass();

$logotype = $s->loginlogotype ?? 'icon_name';

// Build social login buttons from real OAuth 2 issuers.
$socialbuttons = [];
$authsequence = get_enabled_auth_plugins();
if (in_array('oauth2', $authsequence)) {
    $authplugin = get_auth_plugin('oauth2');
    $wantsurl = isset($SESSION->wantsurl) ? $SESSION->wantsurl : '';
    $idplist = $authplugin->loginpage_idp_list($wantsurl);

    // Build a map of issuer ID → servicetype so we can identify each provider.
    $issuers = \core\oauth2\api::get_all_issuers(true);
    $issuermap = [];
    foreach ($issuers as $issuer) {
        $issuermap[$issuer->get('id')] = $issuer->get('servicetype');
    }

    $supportedtypes = ['google', 'microsoft', 'facebook', 'linkedin'];
    foreach ($idplist as $idp) {
        // Extract issuer ID from the login URL parameters.
        $url = new moodle_url($idp['url']);
        $issuerid = $url->get_param('id');
        $type = isset($issuermap[$issuerid]) ? $issuermap[$issuerid] : '';

        if (in_array($type, $supportedtypes)) {
            $socialbuttons[] = [
                'url'          => $idp['url'] instanceof moodle_url ? $idp['url']->out(false) : $idp['url'],
                'name'         => $idp['name'],
                'type'         => $type,
                'is_google'    => ($type === 'google'),
                'is_microsoft' => ($type === 'microsoft'),
                'is_facebook'  => ($type === 'facebook'),
                'is_linkedin'  => ($type === 'linkedin'),
            ];
        }
    }
}

$isloginpage = ($PAGE->pagetype === 'login-index');

if ($PAGE->pagetype === 'login-signup') {
    $headingtext = !empty($s->signupheadingtext)
        ? format_string($s->signupheadingtext)
        : get_string('signupheadingtext_default', 'theme_logini');
    $subtitletext = !empty($s->signupsubtitletext)
        ? format_string($s->signupsubtitletext)
        : get_string('signupsubtitletext_default', 'theme_logini');
} else if ($PAGE->pagetype === 'login-forgot_password') {
    $headingtext = !empty($s->forgotpasswordheadingtext)
        ? format_string($s->forgotpasswordheadingtext)
        : get_string('forgotpasswordheadingtext_default', 'theme_logini');
    $subtitletext = !empty($s->forgotpasswordsubtitletext)
        ? format_string($s->forgotpasswordsubtitletext)
        : get_string('forgotpasswordsubtitletext_default', 'theme_logini');
} else {
    $headingtext = !empty($s->loginheadingtext)
        ? format_string($s->loginheadingtext)
        : get_string('loginheadingtext_default', 'theme_logini');
    $subtitletext = !empty($s->loginsubtitletext)
        ? format_string($s->loginsubtitletext)
        : get_string('loginsubtitletext_default', 'theme_logini');
}

// Evaluate if each slide has any text.
$loginslide1heading = !empty($s->loginslide1heading) ? format_string($s->loginslide1heading) : '';
$loginslide1body    = !empty($s->loginslide1body) ? format_string($s->loginslide1body) : '';
$loginslide1image   = $loginitheme->setting_file_url('loginslide1image', 'loginslide1image');
$hasslide1          = ($loginslide1heading !== '' || $loginslide1body !== '' || $loginslide1image !== null);

$loginslide2heading = !empty($s->loginslide2heading) ? format_string($s->loginslide2heading) : '';
$loginslide2body    = !empty($s->loginslide2body) ? format_string($s->loginslide2body) : '';
$loginslide2image   = $loginitheme->setting_file_url('loginslide2image', 'loginslide2image');
$hasslide2          = ($loginslide2heading !== '' || $loginslide2body !== '' || $loginslide2image !== null);

$loginslide3heading = !empty($s->loginslide3heading) ? format_string($s->loginslide3heading) : '';
$loginslide3body    = !empty($s->loginslide3body) ? format_string($s->loginslide3body) : '';
$loginslide3image   = $loginitheme->setting_file_url('loginslide3image', 'loginslide3image');
$hasslide3          = ($loginslide3heading !== '' || $loginslide3body !== '' || $loginslide3image !== null);

$hasanyslides = $hasslide1 || $hasslide2 || $hasslide3;

// Count slides to determine if we need dots.
$slidecount = ($hasslide1 ? 1 : 0) + ($hasslide2 ? 1 : 0) + ($hasslide3 ? 1 : 0);
$showdots = ($slidecount > 1);

// Determine which slide gets the active class initially.
$slide1active = '';
$slide2active = '';
$slide3active = '';
$dot1active = '';
$dot2active = '';
$dot3active = '';

if ($hasslide1) {
    $slide1active = 'logini-slide--active';
    $dot1active = 'logini-dot--active';
} else if ($hasslide2) {
    $slide2active = 'logini-slide--active';
    $dot2active = 'logini-dot--active';
} else if ($hasslide3) {
    $slide3active = 'logini-slide--active';
    $dot3active = 'logini-dot--active';
}

$templatecontext = [
    'sitename'       => format_string($SITE->shortname, true, [
        'context' => context_course::instance(SITEID),
        'escape'  => false,
    ]),
    'output'         => $OUTPUT,
    'bodyattributes' => $bodyattributes,
    'currentyear'    => date('Y'),
    'langmenu'       => !empty($langmenu) ? $langmenu : false,

    // Content.
    'loginheadingtext'    => $headingtext,
    'loginsubtitletext'   => $subtitletext,
    'loginfootertext'     => !empty($s->loginfootertext)
        ? format_string($s->loginfootertext) : '',

    // Logo display booleans.
    'loginlogo_icon'      => in_array($logotype, ['icon_name', 'icon_only']),
    'loginlogo_name'      => in_array($logotype, ['icon_name', 'text_only']),
    'loginlogoiconclass'  => !empty($s->loginlogoiconclass) ? format_string($s->loginlogoiconclass) : 'fa-graduation-cap',


    'isloginpage'         => $isloginpage,

    // Social / divider visibility (true = hidden).
    'loginhidesocial'     => !$isloginpage,
    'loginhideordivider'  => !$isloginpage,

    // OAuth 2 social login buttons (only configured + enabled providers).
    'socialbuttons'       => $socialbuttons,
    'hassocialbuttons'    => !empty($socialbuttons),

    // Right panel visibility.
    'loginhidecards'      => !empty($s->loginhidecards),
    'loginhideslider'     => !empty($s->loginhideslider) || !$hasanyslides,

    // Floating course card text.
    'logincardcourse'     => !empty($s->logincardcourse)
        ? format_string($s->logincardcourse)
        : get_string('logincardcourse_default', 'theme_logini'),
    'logincarddesc'       => !empty($s->logincarddesc)
        ? format_string($s->logincarddesc)
        : get_string('logincarddesc_default', 'theme_logini'),
    'logincardlevel'      => !empty($s->logincardlevel)
        ? format_string($s->logincardlevel)
        : get_string('logincardlevel_default', 'theme_logini'),
    'logincardduration'   => !empty($s->logincardduration)
        ? format_string($s->logincardduration)
        : get_string('logincardduration_default', 'theme_logini'),
    'logincardrating'     => !empty($s->logincardrating)
        ? format_string($s->logincardrating)
        : get_string('logincardrating_default', 'theme_logini'),
    'logincardratingcount' => !empty($s->logincardratingcount)
        ? format_string($s->logincardratingcount)
        : get_string('logincardratingcount_default', 'theme_logini'),

    // Brand slider text with defaults.
    'showdots'            => $showdots,
    'loginslide1heading'  => $loginslide1heading,
    'loginslide1body'     => $loginslide1body,
    'loginslide1imageurl' => $loginslide1image instanceof moodle_url
        ? $loginslide1image->out(false)
        : (is_string($loginslide1image) ? $loginslide1image : null),
    'hasslide1'           => $hasslide1,
    'slide1active'        => $slide1active,
    'dot1active'          => $dot1active,

    'loginslide2heading'  => $loginslide2heading,
    'loginslide2body'     => $loginslide2body,
    'loginslide2imageurl' => $loginslide2image instanceof moodle_url
        ? $loginslide2image->out(false)
        : (is_string($loginslide2image) ? $loginslide2image : null),
    'hasslide2'           => $hasslide2,
    'slide2active'        => $slide2active,
    'dot2active'          => $dot2active,

    'loginslide3heading'  => $loginslide3heading,
    'loginslide3body'     => $loginslide3body,
    'loginslide3imageurl' => $loginslide3image instanceof moodle_url
        ? $loginslide3image->out(false)
        : (is_string($loginslide3image) ? $loginslide3image : null),
    'hasslide3'           => $hasslide3,
    'slide3active'        => $slide3active,
    'dot3active'          => $dot3active,
];

echo $OUTPUT->render_from_template('theme_logini/login', $templatecontext);
