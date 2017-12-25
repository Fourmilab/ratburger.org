<?php
/*
Plugin Name: Plugins Garbage Collector
Plugin URI: http://www.shinephp.com/plugins-garbage-collector-wordpress-plugin/
Description: It scans your WordPress database and shows what various things old plugins which were deactivated, uninstalled) left in it. The list of additional database tables used by plugins with quant of records, size, and plugin name is shown.
Version: 0.10.3
Author: Vladimir Garagulya
Author URI: http://www.shinephp.com
Text Domain: plugins-garbage-collector
Domain Path: ./lang/
*/

/*
Copyright 2010-2016  Vladimir Garagulya  (email: vladimir@shinephp.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


if (!function_exists('get_option')) {
  header('HTTP/1.0 403 Forbidden');
  die;  // Silence is golden, direct call is prohibited
}

global $wp_version;

define('PGC_PLUGIN_NAME', esc_html__('Plugins Garbage Collector', 'plugins-garbage-collector'));

$exit_msg = PGC_PLUGIN_NAME .' '. esc_html__('requires WordPress 4.0 or newer.', 'plugins-garbage-collector') .
        ' <a href="http://codex.wordpress.org/Upgrading_WordPress">'. esc_html__('Please update!', 'plugins-garbage-collector').'</a>';
if (version_compare($wp_version,"4.0","<")) {
	return ($exit_msg);
}

define('PGC_VERSION', '0.10.3');
define('PGC_PLUGIN_URL', plugin_dir_url(__FILE__));
define('PGC_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('PGC_PLUGIN_FILE', __FILE__);


require_once(PGC_PLUGIN_DIR .'includes/lib.php');
require_once(PGC_PLUGIN_DIR .'includes/class-plugins-garbage-collector.php');

new Plugins_Garbage_Collector();
