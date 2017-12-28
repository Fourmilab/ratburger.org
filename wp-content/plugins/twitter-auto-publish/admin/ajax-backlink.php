<?php
if( !defined('ABSPATH') ){ exit();}
add_action('wp_ajax_xyz_twap_ajax_backlink', 'xyz_twap_ajax_backlink_call');

function xyz_twap_ajax_backlink_call() {


	global $wpdb;

	if($_POST){
		if (! isset( $_POST['_wpnonce'] )|| ! wp_verify_nonce( $_POST['_wpnonce'],'backlink' ))
		 {
					echo 1;die;
					// wp_nonce_ays( 'backlink' );
					//exit();
		
				}
				update_option('xyz_credit_link','twap');
	}
	die();
}


?>