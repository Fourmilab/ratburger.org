<?php
/**
 * General Hooks
 * 
 * @package    wp-ulike
 * @author     Alimir 2018
 * @link       https://wpulike.com
 */

/*******************************************************
  General Hooks
*******************************************************/

/**
 * Register WP ULike Widgets
 *
 * @author       	Alimir
 * @since           1.2 
 * @return			Void
 */
if( ! function_exists( 'wp_ulike_register_widget' ) ){
	function wp_ulike_register_widget() {
		register_widget( 'wp_ulike_widget' );
	}
	add_action( 'widgets_init', 'wp_ulike_register_widget' );
}

/**
 * Create ShortCode: 	[wp_ulike]
 *
 * @author       	Alimir
 * @since           1.4
 * @return			wp ulike button
 */
if( ! function_exists( 'wp_ulike_shortcode' ) ){
	function  wp_ulike_shortcode( $atts, $content = null ){
		// Final result
		$result = '';
		// Default Args
		$args   = shortcode_atts( array(
					"for"           => 'post',	//shortcode Type (post, comment, activity, topic)
					"id"            => '',		//Post ID
					"slug"          => 'post',	//Slug Name
					"style"         => '',		//Get Default Theme
					"attributes"    => '',		//Get Attributes Filter
					"wrapper_class" => ''		//Extra Wrapper class
			    ), $atts );

	    switch ( $args['for'] ) {
	    	case 'comment':
	    		$result = $content . wp_ulike_comments( 'put', array_filter( $args ) );
	    		break;
	    	
	    	case 'activity':
	    		$result = $content . wp_ulike_buddypress( 'put', array_filter( $args ) );
	    		break;
	    	
	    	case 'topic':
	    		$result = $content . wp_ulike_bbpress( 'put', array_filter( $args ) );
	    		break;
	    	
	    	default:
	    		$result = $content . wp_ulike( 'put', array_filter( $args ) );
	    }

		return $result;
	}
	add_shortcode( 'wp_ulike', 'wp_ulike_shortcode' );
}

/*******************************************************
  Posts
*******************************************************/

/**
 * Auto insert wp_ulike function in the posts/pages content
 *
 * @author       	Alimir	 	
 * @param           String $content	 
 * @since           1.0	 
 * @return			filter on "the_content"
 */
if( ! function_exists( 'wp_ulike_put_posts' ) ){	
	function wp_ulike_put_posts($content) {
		//auto display position
		$position = wp_ulike_get_setting( 'wp_ulike_posts', 'auto_display_position');
		$button = '';
		
		//add wp_ulike function
		if(	!is_feed() && is_wp_ulike( wp_ulike_get_setting( 'wp_ulike_posts', 'auto_display_filter') ) ){
			$button = wp_ulike('put');
		}
		
		//return by position
		if($position=='bottom')
		return $content . $button;
		else if($position=='top')
		return $button . $content;
		else if($position=='top_bottom')
		return $button . $content . $button;
		else
		return $content . $button;
	}

	if (wp_ulike_get_setting( 'wp_ulike_posts', 'auto_display' ) == '1') {
		add_filter('the_content', 'wp_ulike_put_posts');
	}
}

/**
 * Add itemtype to wp_ulike_posts_add_attr filter
 *
 * @author       	Alimir
 * @since           2.7 
 * @return          String
 */
if( ! function_exists( 'wp_ulike_get_posts_microdata_itemtype' ) ){	
	function wp_ulike_get_posts_microdata_itemtype(){
		$get_ulike_count = get_post_meta(get_the_ID(), '_liked', true);
		if(!is_singular() || !wp_ulike_get_setting( 'wp_ulike_posts', 'google_rich_snippets') || $get_ulike_count == 0) return;
		return 'itemscope itemtype="http://schema.org/CreativeWork"';
	}
	add_filter('wp_ulike_posts_add_attr', 'wp_ulike_get_posts_microdata_itemtype');
}
	
/**
 * Add rich snippet for ratings in form of schema.org
 *
 * @author       	Alimir
 * @since           2.7 
 * @return          String
 */
if( ! function_exists( 'wp_ulike_get_posts_microdata' ) ){		
	function wp_ulike_get_posts_microdata(){
		$get_ulike_count = get_post_meta(get_the_ID(), '_liked', true);
		if(!is_singular() || !wp_ulike_get_setting( 'wp_ulike_posts', 'google_rich_snippets') || $get_ulike_count == 0) return;
        $post_meta 		= '<meta itemprop="name" content="' . get_the_title() . '" />';
        $post_meta 		.= apply_filters( 'wp_ulike_extra_structured_data', NULL );
		$post_meta 		.= '<span itemprop="author" itemscope itemtype="http://schema.org/Person"><meta itemprop="name" content="' . get_the_author() . '" /></span>';
        $post_meta 		.= '<meta itemprop="datePublished" content="' . get_post_time('c') . '" />';
		$ratings_meta 	= '<span itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">';
		$ratings_meta	.= '<meta itemprop="bestRating" content="5" />';
		$ratings_meta 	.= '<meta itemprop="worstRating" content="1" />';
		$ratings_meta 	.= '<meta itemprop="ratingValue" content="'. wp_ulike_get_rating_value(get_the_ID()) .'" />';
		$ratings_meta 	.= '<meta itemprop="ratingCount" content="' . $get_ulike_count . '" />';
		$ratings_meta 	.= '</span>';
		$itemtype 		= apply_filters( 'wp_ulike_remove_microdata_post_meta', false );
        return apply_filters( 'wp_ulike_generate_google_structured_data', ( $itemtype ? $ratings_meta : ( $post_meta . $ratings_meta )));
	}
	add_filter( 'wp_ulike_posts_microdata', 'wp_ulike_get_posts_microdata');
}

/*******************************************************
  Comments
*******************************************************/

/**
 * Auto insert wp_ulike_comments in the comments content
 *
 * @author       	Alimir
 * @param           String $content		 
 * @since           1.6		 
 * @return          filter on "comment_text"
 */		
if( ! function_exists( 'wp_ulike_put_comments' ) ){			
	function wp_ulike_put_comments($content) {
		//auto display position
		$position = wp_ulike_get_setting( 'wp_ulike_comments', 'auto_display_position');
		
		//add wp_ulike_comments function
		$button = wp_ulike_comments('put');
		
		//return by position
		if($position=='bottom')
		return $content . $button;
		else if($position=='top')
		return $button . $content;
		else if($position=='top_bottom')
		return $button . $content . $button;
		else
		return $content . $button;
	}
	
	if ( wp_ulike_get_setting( 'wp_ulike_comments', 'auto_display' ) == '1'  && ! is_admin() ) {
		add_filter('comment_text', 'wp_ulike_put_comments');
	}
}


/*******************************************************
  BuddyPress
*******************************************************/

if( defined( 'BP_VERSION' ) ) {

	/**
	 * Auto insert wp_ulike_buddypress in the comments content
	 *
	 * @author       	Alimir	 
	 * @param           String $content	 
	 * @since           1.7		 
	 * @return          filter on "bp_get_activity_action"
	 */
	if( ! function_exists( 'wp_ulike_put_buddypress' ) ){		
		function wp_ulike_put_buddypress() {
			wp_ulike_buddypress('get');
		}

		if (wp_ulike_get_setting( 'wp_ulike_buddypress', 'auto_display' ) == '1') {

			if (wp_ulike_get_setting( 'wp_ulike_buddypress', 'auto_display_position' ) == 'meta'){
				add_action( 'bp_activity_entry_meta', 'wp_ulike_put_buddypress' );
	        } else	{
	        	add_action( 'bp_activity_entry_content', 'wp_ulike_put_buddypress' );
	        }
	        // Add wp ulike in buddpress comments
	        if ( wp_ulike_get_setting( 'wp_ulike_buddypress', 'activity_comment' ) == '1' ) {
	        	add_action( 'bp_activity_comment_options', 'wp_ulike_put_buddypress' );        
	        }
		}
	}
		
	/**
	 * Register "WP ULike Activity" action
	 *
	 * @author       	Alimir
	 * @since           1.7	 
	 * @return          Add action on "bp_register_activity_actions"
	 */
	if( ! function_exists( 'wp_ulike_register_activity_actions' ) ){			
		function wp_ulike_register_activity_actions() {
			global $bp;
			bp_activity_set_action(
				$bp->activity->id,
				'wp_like_group',
				__( 'WP ULike Activity', WP_ULIKE_SLUG )
			);
		}
		add_action( 'bp_register_activity_actions', 'wp_ulike_register_activity_actions' );	
	}

	/**
	 * Display likes option in BuddyPress activity filter
	 *
	 * @author       	Alimir	 
	 * @since           2.5.1
	 * @return          Void
	 */
	if( ! function_exists( 'wp_ulike_bp_activity_filter_options' ) ){		
		function wp_ulike_bp_activity_filter_options() {
			echo "<option value='wp_like_group'>". __('Likes') ."</option>";
		}
		add_action( 'bp_activity_filter_options', 'wp_ulike_bp_activity_filter_options' ); // Activity Directory
		add_action( 'bp_member_activity_filter_options', 'wp_ulike_bp_activity_filter_options' ); // Member's profile activity
		add_action( 'bp_group_activity_filter_options', 'wp_ulike_bp_activity_filter_options' ); // Group's activity	
	}

	/**
	 * Register 'wp_ulike' to BuddyPress component. 
	 *
	 * @author       	Alimir	 
	 * @param           Array $component_names	 
	 * @since           2.5
	 * @return          String
	 */
	if( ! function_exists( 'wp_ulike_filter_notifications_get_registered_components' ) ){	
		function wp_ulike_filter_notifications_get_registered_components( $component_names = array() ) {
			// Force $component_names to be an array
			if ( ! is_array( $component_names ) ) {
				$component_names = array();
			}
			// Add 'wp_ulike' component to registered components array
			array_push( $component_names, 'wp_ulike' );
			// Return component's with 'wp_ulike' appended
			return $component_names;
		}
		add_filter( 'bp_notifications_get_registered_components', 'wp_ulike_filter_notifications_get_registered_components', 10 );
	}


	/**
	 * Add custom format for 'wp_ulike' notifications.
	 *
	 * @author       	Alimir	 
	 * @since           2.5
	 * @return          String
	 */
	if( ! function_exists( 'wp_ulike_format_buddypress_notifications' ) ){	
	        /* RATBURGER LOCAL CODE
	           Add $not_id (notification ID) argument
		        function wp_ulike_format_buddypress_notifications( $action, $item_id, $secondary_item_id, $total_items, $format = 'string' ) {
	        */
	        function wp_ulike_format_buddypress_notifications( $action, $item_id, $secondary_item_id, $total_items, $format = 'string',
	            $canon_act, $comp_name, $not_id = -1 ) {
	        /* END RATBURGER LOCAL CODE */
			global $wp_filter,$wp_version;	
				if (strpos($action, 'wp_ulike_') !== false) {
					$custom_link	= '';
					//Extracting ulike type from the action value.
					preg_match('/wp_ulike_(.*?)_action/', $action, $type);
					//Extracting user id from the action value.
					preg_match('/action_([0-9]+)/', $action, $user_ID);
					$user_info 		= get_userdata($user_ID[1]);
				    /* RATBURGER LOCAL CODE
					$custom_text 	= __('You have a new like from', WP_ULIKE_SLUG ) . ' "' . $user_info->display_name . '"';
				    */
				    $custom_text = $user_info->display_name . " liked your ";
				    $custom_class = '';
				    /* END RATBURGER LOCAL CODE */
					//checking the ulike types
					if($type[1] == 'liked'){
						$custom_link  	= get_permalink($item_id);
					    /* RATBURGER LOCAL CODE
					       Add title of post to post like notification */
					    $custom_text .= 'post ' . '"' .
					    get_post($item_id)->post_title . '"';
					    $custom_class = 'rb_notif_post_like rb_notif_highlight';
					    /* END RATBURGER LOCAL CODE */
					}
					else if($type[1] == 'topicliked'){
						$custom_link  	= get_permalink($item_id);
					    /* RATBURGER LOCAL CODE
					       Include group name in group post like */
					    $zzact = new BP_Activity_Activity($item_id);  // Activity for post
					    $zzgrp = new BP_Groups_Group($zzact->item_id); // Parent group object
					    $custom_text .= 'post in group ' . '"' .
					    $custom_class = 'rb_notif_group_topic_like rb_notif_highlight';
					    $zzgrp->name . '"';
					    /* END RATBURGER LOCAL CODE */
					}
					else if($type[1] == 'commentliked'){
						$custom_link  	= get_comment_link( $item_id );
					    /* RATBURGER LOCAL CODE
					       Add title of post commented on to comment like notification.
					    */
					    $custom_text .= 'comment on ' . '"' .
					        get_post(get_comment($item_id)->comment_post_ID)->post_title . '"';
					    $custom_class = 'rb_notif_comment_like rb_notif_highlight';
					    /* END RATBURGER LOCAL CODE */
					}
					else if($type[1] == 'activityliked'){
						$custom_link  	= bp_activity_get_permalink( $item_id );
					    /* RATBURGER LOCAL CODE
					       Include group name in group comment like notification.
					    */
					    $zzact = new BP_Activity_Activity($item_id);  // Activity for comment
					    $zztype = 'post';
					    if ($zzact->type == 'activity_comment') {
					        $zztype = 'comment';
					        $zzact = new BP_Activity_Activity($zzact->item_id);  // Activity for parent group
					    }
					    $zzgrp = new BP_Groups_Group($zzact->item_id); // Parent group object
					    $custom_text .= $zztype . ' in group ' . '"' .
					        $zzgrp->name . '"';
					    $custom_class = 'rb_notif_group_' . $zztype . '_like rb_notif_highlight';
				    /* Handle notifications for new comments. */
				    } else if ($type[1] == 'commentadded') {
					    $custom_link = get_comment_link( $item_id );
					    $custom_text = $user_info->display_name . ' commented on ' . '"' .
                                                get_post(get_comment($item_id)->comment_post_ID)->post_title . '"';
					    $custom_class = 'rb_notif_new_comment rb_notif_highlight';
				    /* Handle notification for new posts in a group. */
				    } else if ($type[1] == 'grouppost') {
					    $grp = groups_get_group($secondary_item_id);
					    $custom_link = bp_get_group_permalink($grp) . '#activity-' . $item_id;
                                            $custom_text = $user_info->display_name . ' posted an update in the ' .
					        $grp->name . ' group';
					    $custom_class = 'rb_notif_new_group_post rb_notif_highlight';
				    /* Handle notification for new comments in a group. */
				    } else if ($type[1] == 'groupcomment') {
				        $custom_link = add_query_arg( 'nid', (int) $not_id, bp_activity_get_permalink( $item_id ) );
				        $RB_grp = new BP_Groups_Group((new BP_Activity_Activity((new BP_Activity_Activity($item_id))->item_id))->item_id);
				        $custom_text = sprintf( __( '%1$s commented on one of your updates in group %2$s', 'buddypress' ),
					    get_userdata($secondary_item_id)->display_name,
					    $RB_grp->name );
					$custom_class = 'rb_notif_new_group_comment rb_notif_highlight';
					    /* END RATBURGER LOCAL CODE */
					}
				    /* RATBURGER LOCAL CODE
				       Build urlencode()d $goto_link for redirect destination after marking the
				       notification read.
				    */
				    $goto_link = urlencode($custom_link);
				    /* Replace the $custom_link with a link to mark the notification read and
				       specify the redirect to view the topic of the notification. */
				    $custom_kink = bp_get_root_domain() . '/members/' .
				        wp_get_current_user()->user_login .
				        '/notifications/unread/' .
				         wp_nonce_url('', 'bp_notification_mark_read_' . $not_id) .
				        '&action=read&notification_id=' . $not_id .
				        '&goto=' . $goto_link;
				    $custom_link = $custom_kink;
				    /* END RATBURGER LOCAL CODE */
					// WordPress Toolbar
					if ( 'string' === $format ) {
						$return = apply_filters( 'wp_ulike_bp_notifications_template', '<a href="' . esc_url( $custom_link ) . '" title="' . esc_attr( $custom_text ) . '">' . esc_html( $custom_text ) . '</a>', $custom_text, $custom_link );
					// Deprecated BuddyBar
					} else {
						$ctx = $custom_text;
						if ($custom_class !== '') {
							$ctx = '<span class="' . $custom_class . '">' . $custom_text . '</span>';
						}
						$return = apply_filters( 'wp_ulike_bp_notifications_template', array(
							'text' => $ctx,
							'link' => $custom_link
						), $custom_link, (int) $total_items, $ctx, $ctx );
					}
					// global wp_filter to call bbPress wrapper function
					if (isset($wp_filter['bp_notifications_get_notifications_for_user'][10]['bbp_format_buddypress_notifications'])) {
						if (version_compare($wp_version, '4.7', '>=' )) {
							// https://make.wordpress.org/core/2016/09/08/wp_hook-next-generation-actions-and-filters/
							$wp_filter['bp_notifications_get_notifications_for_user']->callbacks[10]['bbp_format_buddypress_notifications']['function'] = 'wp_ulike_bbp_format_buddypress_notifications';
						} else {
							$wp_filter['bp_notifications_get_notifications_for_user'][10]['bbp_format_buddypress_notifications']['function'] = 'wp_ulike_bbp_format_buddypress_notifications';
						}
					}
					return $return;
			}
			return $action;
		}
        /* RATBURGER LOCAL CODE
	       Add $not_id (notification ID) argument
		add_filter( 'bp_notifications_get_notifications_for_user', 'wp_ulike_format_buddypress_notifications', 5, 5 );
        */
		add_filter( 'bp_notifications_get_notifications_for_user', 'wp_ulike_format_buddypress_notifications', 5, 8 );
        /* END RATBURGER LOCAL CODE */
	}
	
}

/*******************************************************
  bbPress
*******************************************************/

/**
 * Auto insert wp_ulike_bbpress in the topcis content
 *
 * @author       	Alimir	 
 * @param           String $content	 
 * @since           2.2	 
 * @return          filter on bbpPress hooks
 */
if( ! function_exists( 'wp_ulike_put_bbpress' ) && function_exists( 'is_bbpress' ) ){
	function wp_ulike_put_bbpress() {
		 wp_ulike_bbpress('get');
	}
	if (wp_ulike_get_setting( 'wp_ulike_bbpress', 'auto_display' ) == '1') {	
		if (wp_ulike_get_setting( 'wp_ulike_bbpress', 'auto_display_position' ) == 'top') {
			add_action( 'bbp_theme_before_reply_content', 'wp_ulike_put_bbpress' );	
		} else {
			add_action( 'bbp_theme_after_reply_content', 'wp_ulike_put_bbpress' );
		}
	}
}

/*******************************************************
  Other Plugins
*******************************************************/

/**
 * MyCred Hooks
 *
 * @author       	Gabriel Lemarie & Alimir
 * @since          	2.3
 */
if( defined( 'myCRED_VERSION' ) ){	
	if( ! function_exists( 'wp_ulike_register_myCRED_hook' ) ){
		function wp_ulike_register_myCRED_hook( $installed ) {
			$installed['wp_ulike'] = array(
				'title'       => __( 'WP ULike', WP_ULIKE_SLUG ),
				'description' => __( 'This hook award / deducts points from users who Like/Unlike any content of WordPress, bbPress, BuddyPress & ...', WP_ULIKE_SLUG ),
				'callback'    => array( 'wp_ulike_myCRED' )
			);
			return $installed;
		}
		add_filter( 'mycred_setup_hooks', 'wp_ulike_register_myCRED_hook' );
	}
	if( ! function_exists( 'wp_ulike_myCRED_references' ) ){
		function wp_ulike_myCRED_references( $hooks ) {
			$hooks['wp_add_like'] 	= __( 'Liking Content', WP_ULIKE_SLUG );
			$hooks['wp_get_like'] 	= __( 'Liked Content', WP_ULIKE_SLUG );
			$hooks['wp_add_unlike'] = __( 'Unliking Content', WP_ULIKE_SLUG );
			$hooks['wp_get_unlike'] = __( 'Unliked Content', WP_ULIKE_SLUG );
			return $hooks;
		}
		add_filter( 'mycred_all_references', 'wp_ulike_myCRED_references' );
	}
}

/**
 * UltimateMember Hooks
 *
 * @author       	Alimir
 * @since          	2.3
 */
if ( defined( 'ultimatemember_version' ) ) {
	/**
	 * Add custom tabs in the UltimateMember profiles.
	 *
	 * @author       	Alimir
	 * @since           2.3
	 * @return          Array
	 */
	if( ! function_exists( 'wp_ulike_add_custom_profile_tab' ) ){
		function wp_ulike_add_custom_profile_tab( $tabs ) {
			
			$tabs['wp-ulike-posts'] = array(
				'name' => __('Recent Posts Liked',WP_ULIKE_SLUG),
				'icon' => 'um-faicon-thumbs-up',
			);
				
			$tabs['wp-ulike-comments'] = array(
				'name' => __('Recent Comments Liked',WP_ULIKE_SLUG),
				'icon' => 'um-faicon-thumbs-o-up',
			);
				
			return $tabs;
		}
		add_filter('um_profile_tabs', 'wp_ulike_add_custom_profile_tab', 1000 );
	}

	/**
	 * Add content to the wp-ulike-posts tab
	 *
	 * @author       	Alimir
	 * @since           2.3
	 * @return          Void
	 */
	if( ! function_exists( 'wp_ulike_posts_um_profile_content' ) ){
		function wp_ulike_posts_um_profile_content( $args ) {
			global $wp_ulike_class,$ultimatemember;
			
			$args = array(
				"user_id" 	=> um_profile_id(),			//User ID
				"col" 		=> 'post_id',				//Table Column (post_id,comment_id,activity_id,topic_id)
				"table" 	=> 'ulike',					//Table Name
				"limit" 	=> 10,						//limit Number
			);	
			
			$user_logs = $wp_ulike_class->get_current_user_likes($args);
			
			if($user_logs != null){
				echo '<div class="um-profile-note"><span>'. __('Recent Posts Liked',WP_ULIKE_SLUG).'</span></div>';
				foreach ($user_logs as $user_log) {
					$get_post 	= get_post(stripslashes($user_log->post_id));
					$get_date 	= $user_log->date_time;
					
					echo '<div class="um-item">';
					echo '<div class="um-item-link">
						  <i class="um-icon-ios-paper"></i>
						  <a href="'.get_permalink($get_post->ID).'">'.$get_post->post_title.'</a>
						  </div>';
					echo '<div class="um-item-meta">
						  <span>'.wp_ulike_date_i18n($get_date).'</span>
						  <span class="badge"><i class="um-faicon-thumbs-o-up"></i> '.get_post_meta( $get_post->ID, '_liked', true ).'</span>
						  </div>';
					echo '</div>';
				}
			} else echo '<div style="display: block;" class="um-profile-note"><i class="um-faicon-frown-o"></i><span>'. __('This user has not made any likes.',WP_ULIKE_SLUG).'</span></div>';
		}	
		add_action('um_profile_content_wp-ulike-posts_default', 'wp_ulike_posts_um_profile_content');
	}

	/**
	 * Add content to the wp-ulike-comments tab
	 *
	 * @author       	Alimir
	 * @since           2.3
	 * @return          Void
	 */	
	if( ! function_exists( 'wp_ulike_comments_um_profile_content' ) ){
		function wp_ulike_comments_um_profile_content( $args ) {
			global $wp_ulike_class,$ultimatemember;
			
			$args = array(
				"user_id" 	=> um_profile_id(),			//User ID
				"col" 		=> 'comment_id',			//Table Column (post_id,comment_id,activity_id,topic_id)
				"table" 	=> 'ulike_comments',		//Table Name
				"limit" 	=> 10,						//limit Number
			);	
			
			$user_logs = $wp_ulike_class->get_current_user_likes($args);
			
			if($user_logs != null){
				echo '<div class="um-profile-note"><span>'. __('Recent Comments Liked',WP_ULIKE_SLUG).'</span></div>';
				foreach ($user_logs as $user_log) {
					$comment 	= get_comment(stripslashes($user_log->comment_id));
					$get_date 	= $user_log->date_time;
					
					echo '<div class="um-item">';
					echo '<div class="um-item-link">
						  <i class="um-icon-ios-chatboxes"></i>
						  <a href="'.get_comment_link($comment->comment_ID).'">'.$comment->comment_content .'</a>
						  <em style="font-size:.7em;padding:0 10px;"><span class="um-faicon-quote-left"></span> '.$comment->comment_author.' <span class="um-faicon-quote-right"></span></em>
						  </div>';
					echo '<div class="um-item-meta">
						  <span>'.wp_ulike_date_i18n($get_date).'</span>
						  <span class="badge"><i class="um-faicon-thumbs-o-up"></i> '.get_comment_meta( $comment->comment_ID, '_commentliked', true ).'</span>
						  </div>';
					echo '</div>';
				}
			} else echo '<div style="display: block;" class="um-profile-note"><i class="um-faicon-frown-o"></i><span>'. __('This user has not made any likes.',WP_ULIKE_SLUG).'</span></div>';
		}
		add_action('um_profile_content_wp-ulike-comments_default', 'wp_ulike_comments_um_profile_content');
	}
}					
