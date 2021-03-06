<?php
/**
 * Include admin files
 * 
 * @package    wp-ulike
 * @author     TechnoWich 2020
 * @link       https://wpulike.com
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die('No Naughty Business Please !');
}

//include settings panel
require_once( WP_ULIKE_ADMIN_DIR . '/settings/classes/setup.class.php');
// Register admin menus
new wp_ulike_admin_panel();

//include about menu functions
require_once( WP_ULIKE_ADMIN_DIR . '/admin-functions.php');
//include logs menu functions
require_once( WP_ULIKE_ADMIN_DIR . '/admin-hooks.php');