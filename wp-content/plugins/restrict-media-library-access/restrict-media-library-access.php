<?php

/**
 * Plugin Name: Restrict Media Library Access
 * Description: Restricts access for Authors and Contributors so they can only see their own Media Library uploads.
 * Plugin URI: https://wordpress.org/plugins/restrict-media-library-access
 * Version: 1.0
 * Author: mrfoxtalbot
 * Author URI: http://mrfoxtalbot.com
 * Text Domain: rmla
 * Domain path: /languages
 * License: GPLv2 or later (license.txt)
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

add_filter( 'ajax_query_attachments_args', 'mrfx_show_current_user_attachments' );

function mrfx_show_current_user_attachments( $query ) {
    $user_id = get_current_user_id();
    if ( $user_id && !current_user_can('administrator') && !current_user_can('editor') ) {
        $query['author'] = $user_id;
    }
    return $query;
} 