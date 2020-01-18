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
    /* RATBURGER LOCAL CODE
       Some users do not respond to notifications, either
       clicking them (which causes their deletion) or manually
       clearing them.  In the standard BuddyPress system, this
       eventually results in the construction of an enormous
       notification drop-down menu, far too long to fit on any
       screen, and costly to prepare and transmit to the user.
       I've seen one case of a menu with more than 19,000
       notifications which took more than 8 megabytes of HTML to
       transmit.

       This is our first layer of defence.  If the user has
       substantially more notifications than can possibly fit on
       the screen, call rb_notif_prune() with an argument which
       will cause all notifications older than 30 days to be
       deleted.  As these are physically deleted from the
       database, they will no longer take up space there, nor
       consume time to load by
           bp_notifications_get_notifications_for_user()
       or memory in the $notifications variable here.

       We delete the notifications rather than marking them read
       because a user who doesn't respond to unread
       notifications is unlikely to ever check for and clean up
       read ones.
    */
    if ($count > 500) {
        $num_pruned = rb_notif_prune(30 * DAY_IN_SECONDS);
        //  If any were pruned, refresh notification list
        if ($num_pruned > 0) {
                $notifications = bp_notifications_get_notifications_for_user(bp_loggedin_user_id(), 'object');
                $count = !empty($notifications) ? count($notifications) : 0;
        }
    }
    /* END RATBURGER LOCAL CODE */
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
        $rb_maxnot_toolbar = 35;
        /* END RATBURGER LOCAL CODE */
		foreach ( (array) $notifications as $notification ) {
            /* RATBURGER LOCAL CODE
                This is our second level of defence against
                absurdly large numbers of unread notifications.
                After we've pruned old notifications above,
                there may still be too many to fit on the screen
                in a menu.  There's no reason to waste the time
                and data transfer to prepare and send something
                to a user they can't see.  Here we limit the
                number of items in the menu to
                $rb_maxnot_toolbar, which should be set larger
                than menu items which might fit
                on a generously sized screen.
            */
            if ($rb_maxnot_toolbar-- <= 0) {
                break;
            }
            /* END RATBURGER LOCAL CODE */
			$wp_admin_bar->add_menu( array(
				'parent' => 'bp-notifications',
				'id'     => 'notification-' . $notification->id,
				'title'  => $notification->content,
				'href'   => $notification->href,
			) );
		}
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
