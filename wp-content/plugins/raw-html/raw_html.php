<?php
/*
Plugin Name: Raw HTML
Plugin URI: http://w-shadow.com/blog/2007/12/13/raw-html-in-wordpress/
Description: Lets you enter any HTML/JS/CSS in your posts without WP changing it, as well as disable automatic formatting on a per-post basis. <strong>Usage:</strong> Wrap your code in [raw]...[/raw] tags. To avoid problems, only edit posts that contain raw code in HTML mode. <strong><a href="http://rawhtmlpro.com/?utm_source=RawHTML%20free&utm_medium=plugin_description&utm_campaign=Plugins">Upgrade to Pro</a></strong> to be able to use Visual editor on the same posts without it messing up the code.
Version: 1.6.1
Author: Janis Elsts
Author URI: http://w-shadow.com/
*/

/*
Created by Janis Elsts (email : whiteshadow@w-shadow.com) 
Licensed under the LGPL.
*/

if ( function_exists('wsh_extract_exclusions') || defined('RAWHTML_PLUGIN_FILE') ) {
	function wsh_raw_html_activation_conflict() {
		if ( !current_user_can('activate_plugins') ) {
			return; //The current user can't do anything about the problem.
		}
		?>
		<div class="notice notice-error">
			<p>
				<strong>Warning: Another version of Raw HTML is already active.</strong><br>
				Please deactivate the older version. It is not possible to run two different versions
				of this plugin at the same time.
			</p>
		</div>
		<?php
	}

	add_action('admin_notices', 'wsh_raw_html_activation_conflict');
	return;
}


define('RAWHTML_PLUGIN_FILE', __FILE__);

require 'include/tag-handler.php';
require 'include/formatting-override.php';

if ( is_admin() && file_exists(dirname(__FILE__).'/editor-plugin/init.php') ){
	require dirname(__FILE__) . '/editor-plugin/init.php';
}