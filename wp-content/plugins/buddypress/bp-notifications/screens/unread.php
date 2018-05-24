<?php
/**
 * Notifications: User's "Notifications" screen handler
 *
 * @package BuddyPress
 * @subpackage NotificationsScreens
 * @since 3.0.0
 */

/**
 * Catch and route the 'unread' notifications screen.
 *
 * @since 1.9.0
 */
function bp_notifications_screen_unread() {

	/**
	 * Fires right before the loading of the notifications unread screen template file.
	 *
	 * @since 1.9.0
	 */
	do_action( 'bp_notifications_screen_unread' );

	/**
	 * Filters the template to load for the notifications unread screen.
	 *
	 * @since 1.9.0
	 *
	 * @param string $template Path to the notifications unread template to load.
	 */
	bp_core_load_template( apply_filters( 'bp_notifications_template_unread', 'members/single/home' ) );
}

/**
 * Handle marking single notifications as read.
 *
 * @since 1.9.0
 *
 * @return bool
 */
function bp_notifications_action_mark_read() {

	// Bail if not the unread screen.
	if ( ! bp_is_notifications_component() || ! bp_is_current_action( 'unread' ) ) {
		return false;
	}

	// Get the action.
	$action = !empty( $_GET['action']          ) ? $_GET['action']          : '';
	$nonce  = !empty( $_GET['_wpnonce']        ) ? $_GET['_wpnonce']        : '';
	$id     = !empty( $_GET['notification_id'] ) ? $_GET['notification_id'] : '';
	/* RATBURGER LOCAL CODE
	   If specified, extract URL to which we redirect after marking
	   the notification read. */
	$goto = !empty( $_GET['goto'] ) ? $_GET['goto'] : '';
	/* END RATBURGER LOCAL CODE */

	// Bail if no action or no ID.
	if ( ( 'read' !== $action ) || empty( $id ) || empty( $nonce ) ) {
		return false;
	}

	/* RATBURGER LOCAL CODE
	   Handle "all" as the ID to mark all read. */
	if ($id === 'all') {
	    if (bp_verify_nonce_request('bp_notification_mark_read_' . $id)) {
	        rb_notif_mark_all_read();
	    }
	    bp_core_redirect( bp_displayed_user_domain() . bp_get_notifications_slug() . '/read/' );
	    // Does not return
	}
	/* END RATBURGER LOCAL CODE */

	// Check the nonce and mark the notification.
	if ( bp_verify_nonce_request( 'bp_notification_mark_read_' . $id ) && bp_notifications_mark_notification( $id, false ) ) {
		bp_core_add_message( __( 'Notification successfully marked read.',         'buddypress' )          );
	} else {
		bp_core_add_message( __( 'There was a problem marking that notification.', 'buddypress' ), 'error' );
	}

	/* RATBURGER LOCAL CODE
	   If $goto was specified, redirect to the URL it indicates
	   instead of back to the unread notifications page. */
	if (!empty($goto)) {
	    $redir_url = urldecode($goto);
	    bp_core_redirect($redir_url);
	}
	/* END RATBURGER LOCAL CODE */

	// Redirect.
	bp_core_redirect( bp_displayed_user_domain() . bp_get_notifications_slug() . '/unread/' );
}
add_action( 'bp_actions', 'bp_notifications_action_mark_read' );
