<?php
/**
 * BuddyPress Notifications Admin Bar functions.
 *
 * Admin Bar functions for the Notifications component.
 *
 * @package BuddyPress
 * @subpackage NotificationsToolbar
 * @since 1.9.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Build the "Notifications" dropdown.
 *
 * @since 1.9.0
 *
 * @return bool
 */
function bp_notifications_toolbar_menu() {
	global $wp_admin_bar;

	if ( ! is_user_logged_in() ) {
		return false;
	}

	$notifications = bp_notifications_get_notifications_for_user( bp_loggedin_user_id(), 'object' );
	$count         = ! empty( $notifications ) ? count( $notifications ) : 0;
	$alert_class   = (int) $count > 0 ? 'pending-count alert' : 'count no-alert';
	$menu_title    = '<span id="ab-pending-notifications" class="' . $alert_class . '">' . number_format_i18n( $count ) . '</span>';
	$menu_link     = trailingslashit( bp_loggedin_user_domain() . bp_get_notifications_slug() );

	// Add the top-level Notifications button.
	$wp_admin_bar->add_menu( array(
		'parent'    => 'top-secondary',
		'id'        => 'bp-notifications',
		'title'     => $menu_title,
		'href'      => $menu_link,
	) );

	if ( ! empty( $notifications ) ) {
		foreach ( (array) $notifications as $notification ) {
			$wp_admin_bar->add_menu( array(
				'parent' => 'bp-notifications',
				'id'     => 'notification-' . $notification->id,
				'title'  => $notification->content,
				'href'   => $notification->href,
			) );
		}
		/* RATBURGER LOCAL CODE
		   Add menu item to mark all notifications read */
		$custom_kink = bp_get_root_domain() . '/members/' .
	            wp_get_current_user()->user_login .
	            '/notifications/unread/' .
	             wp_nonce_url('', 'bp_notification_mark_read_all') .
	            '&action=read&notification_id=all';
		$wp_admin_bar->add_menu(array(
			'parent' => 'bp-notifications',
			'id'     => 'notification-' . 'mark-all-read',
			'title'  => '<span class="rb_notif_mark_all_read rb_notif_highlight">Mark all notifications read</span>',
			'href'   => $custom_kink,
		));
		/* END RATBURGER LOCAL CODE */
	} else {
		$wp_admin_bar->add_menu( array(
			'parent' => 'bp-notifications',
			'id'     => 'no-notifications',
			'title'  => __( 'No new notifications', 'buddypress' ),
			'href'   => $menu_link,
		) );
	}

	return;
}
add_action( 'admin_bar_menu', 'bp_members_admin_bar_notifications_menu', 90 );
