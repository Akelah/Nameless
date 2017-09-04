<?php 
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr2
 *
 *  License: MIT
 *
 *  Core initialisation file
 */

// Ensure module has been installed
$module_installed = $cache->retrieve('module_core');
if(!$module_installed){
	// Hasn't been installed
	// Need to run the installer
	
	die('Run the installer first!');
	
} else {
	// Installed
}

// Define URLs which belong to this module
$pages->add('Core', '/', 'pages/index.php');
$pages->add('Core', '/contact', 'pages/contact.php');
$pages->add('Core', '/home', 'pages/home.php', 'index', true);
$pages->add('Core', '/admin', 'pages/admin/index.php');
$pages->add('Core', '/admin/auth', 'pages/admin/auth.php');
$pages->add('Core', '/admin/core', 'pages/admin/core.php');
$pages->add('Core', '/admin/groups', 'pages/admin/groups.php');
$pages->add('Core', '/admin/images', 'pages/admin/images.php');
$pages->add('Core', '/admin/minecraft', 'pages/admin/minecraft.php');
$pages->add('Core', '/admin/modules', 'pages/admin/modules.php');
$pages->add('Core', '/admin/registration', 'pages/admin/registration.php');
$pages->add('Core', '/admin/security', 'pages/admin/security.php');
$pages->add('Core', '/admin/styles', 'pages/admin/styles.php');
$pages->add('Core', '/admin/users', 'pages/admin/users.php');
$pages->add('Core', '/admin/update', 'pages/admin/update.php');
$pages->add('Core', '/admin/update_execute', 'pages/admin/update_execute.php');
$pages->add('Core', '/admin/update_uuids', 'pages/admin/update_uuids.php');
$pages->add('Core', '/admin/update_mcnames', 'pages/admin/update_mcnames.php');
$pages->add('Core', '/admin/reset_password', 'pages/admin/reset_password.php');
$pages->add('Core', '/admin/night_mode', 'pages/admin/night_mode.php');
$pages->add('Core', '/admin/widgets', 'pages/admin/widgets.php');
$pages->add('Core', '/user', 'pages/user/index.php');
$pages->add('Core', '/user/settings', 'pages/user/settings.php');
$pages->add('Core', '/user/messaging', 'pages/user/messaging.php');
$pages->add('Core', '/user/alerts', 'pages/user/alerts.php');
$pages->add('Core', '/mod', 'pages/mod/index.php');
$pages->add('Core', '/mod/punishments', 'pages/mod/punishments.php');
$pages->add('Core', '/mod/reports', 'pages/mod/reports.php');
$pages->add('Core', '/mod/ip_lookup', 'pages/mod/ip_lookup.php');
$pages->add('Core', '/login', 'pages/login.php');
$pages->add('Core', '/logout', 'pages/logout.php');
$pages->add('Core', '/profile', 'pages/profile.php', 'profile', true);
$pages->add('Core', '/register', 'pages/register.php');
$pages->add('Core', '/validate', 'pages/validate.php');
$pages->add('Core', '/queries/alerts', 'queries/alerts.php');
$pages->add('Core', '/queries/pms', 'queries/pms.php');
$pages->add('Core', '/queries/servers', 'queries/servers.php');
$pages->add('Core', '/banner', 'pages/minecraft/banner.php');
$pages->add('Core', '/terms', 'pages/terms.php');
$pages->add('Core', '/forgot_password', 'pages/forgot_password.php');

// Widgets
// Facebook
require_once(ROOT_PATH . '/modules/Core/widgets/FacebookWidget.php');
$cache->setCache('social_media');
$fb_url = $cache->retrieve('facebook');
if($fb_url){
    // Active pages
    $module_pages = $widgets->getPages('Facebook');

    $widgets->add(new FacebookWidget($module_pages, $fb_url));
}

// Twitter
require_once(ROOT_PATH . '/modules/Core/widgets/TwitterWidget.php');
$twitter = $cache->retrieve('twitter');

if($twitter){
    $theme = $cache->retrieve('twitter_theme');
    $module_pages = $widgets->getPages('Twitter');

    $widgets->add(new TwitterWidget($module_pages, $twitter, $theme));
}

// Discord
require_once(ROOT_PATH . '/modules/Core/widgets/DiscordWidget.php');
$discord = $cache->retrieve('discord');

if($discord){
    $module_pages = $widgets->getPages('Discord');

    $widgets->add(new DiscordWidget($module_pages, $discord));
}