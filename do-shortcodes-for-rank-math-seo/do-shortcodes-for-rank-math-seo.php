<?php

/*
Plugin Name: Do Shortcodes for Rank Math SEO
Plugin URI: https://denra.com/products/wordpress/plugins/do-shortcodes-for-rank-math-seo/
Description:  Display shortcodes in the title, description, Facebook and Twitter fields, and other locations for Rank Math SEO.
Version: 1.2.3
Author: Denra.com aka SoftShop Ltd
Author URI: https://www.denra.com/
Text Domain: denra-do-sc-rank-math-seo
Domain Path: /plugin/i18n
 */

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

Copyright 2020 Denra.com aka SoftShop Ltd
*/

namespace Denra\Plugins;

defined('ABSPATH') || exit;

$plugin_id = 'denra-plugin-do-shortcodes-for-rank-math-seo';
$plugin_class = 'DoShortcodesRankMathSEO';

global $denra_plugins;

$denra_plugins['data'][$plugin_id] = [
    'class' => $plugin_class,
    'file' => __FILE__,
    'dir' => \plugin_dir_path(__FILE__),
    'url' => \plugin_dir_url(__FILE__),
    'framework_version' => '1.3.8' // MUST match $version in Framework.php
];

# STOP EDITING HERE! DO NOT TOUCH BELOW THIS LINE! #

// Get the Framework Loader from the first plugin
if (!class_exists ('\Denra\Plugins\FrameworkLoader')) {
   require_once 'denra-plugins/classes/FrameworkLoader.php';
   require_once 'denra-plugins/classes/PluginHooks.php';
   \add_action('plugins_loaded', ['\Denra\Plugins\FrameworkLoader', 'loadFramework']);
}

// Set plugin activation, deactivation and uninstall hooks
FrameworkLoader::initHooks($plugin_id, $denra_plugins['data'][$plugin_id]);

unset($plugin_id, $plugin_class);
