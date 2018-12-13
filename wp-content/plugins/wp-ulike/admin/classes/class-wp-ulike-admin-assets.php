<?php
/**
 * Wp ULike Admin Scripts Class.
 * 
 * @package    wp-ulike
 * @author     Alimir 2018
 * @link       https://wpulike.com
*/

// no direct access allowed
if ( ! defined('ABSPATH') ) {
    die();
}

if ( ! class_exists( 'wp_ulike_admin_assets' ) ) {
	/**
	 *  Class to load and print master slider panel scripts
	 */
	class wp_ulike_admin_assets {

		private $hook;

		/**
		 * __construct
		 */
		function __construct( $hook ) {
			$this->hook = $hook;
			// general assets
			$this->load_styles();
			$this->load_scripts();
		 }

		
		/**
		 * Styles for admin
		 *
		 * @return void
		 */
		public function load_styles() {
			// Enqueue admin styles
			wp_enqueue_style(
				'wp-ulike-admin',
				WP_ULIKE_ADMIN_URL . '/assets/css/admin.css'
			);
		
		}

	    /**
	     * Scripts for admin
	     *
	     * @return void
	     */
		public function load_scripts() {

			// Scripts is only can be load on ulike pages.
			if ( strpos( $this->hook, 'wp-ulike' ) === false ) {
				return;
			}

			// Remove all notices in wp ulike pages.
			// remove_all_actions( 'admin_notices' );

			// Enqueue vueJS	
			wp_enqueue_script(
				'wp_ulike_vuejs',
				WP_ULIKE_ADMIN_URL . '/assets/js/solo/vue/vue.min.js',
				array(),
				null,
				false
			);

			// Enqueue admin plugins
			wp_enqueue_script(
				'wp_ulike_admin_plugins',
				WP_ULIKE_ADMIN_URL . '/assets/js/plugins.js',
				array( 'jquery' ),
				false,
				true
			);

			// Enqueue admin scripts
			wp_enqueue_script(
				'wp_ulike_admin_scripts',
				WP_ULIKE_ADMIN_URL . '/assets/js/scripts.js',
				array( 'wp_ulike_admin_plugins', 'wp_ulike_vuejs'),
				false,
				true
			);

			// Localize scripts
			wp_localize_script( 'wp_ulike_admin_scripts', 'wp_ulike_admin', array(
				'hook_address'    => esc_html( $this->hook ),
				'nonce_field'     => wp_create_nonce( 'wp-ulike-ajax-nonce' ),
				'logs_notif'      => __('Are you sure to remove this item?!',WP_ULIKE_SLUG),
				'not_found_notif' => __('No information was found in this database!',WP_ULIKE_SLUG),
				'spinner'         => admin_url( 'images/spinner.gif' )
			));

		}

	}
	
}