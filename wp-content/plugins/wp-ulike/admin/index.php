<?php
/**
 * Include admin files
 * 
 * @package    wp-ulike
 * @author     Alimir 2019
 * @link       https://wpulike.com
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die('No Naughty Business Please !');
}

//include about menu functions
require_once( WP_ULIKE_ADMIN_DIR . '/admin-functions.php');
//include logs menu functions
require_once( WP_ULIKE_ADMIN_DIR . '/admin-hooks.php');

// Register admin menus
new wp_ulike_admin_pages();