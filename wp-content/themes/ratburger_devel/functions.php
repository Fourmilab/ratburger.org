<?php
/**
 * Twenty Sixteen functions and definitions
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * When using a child theme you can override certain functions (those wrapped
 * in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before
 * the parent theme's file, so the child theme functions would be used.
 *
 * @link https://codex.wordpress.org/Theme_Development
 * @link https://codex.wordpress.org/Child_Themes
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are
 * instead attached to a filter or action hook.
 *
 * For more information on hooks, actions, and filters,
 * {@link https://codex.wordpress.org/Plugin_API}
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */

/**
 * Twenty Sixteen only works in WordPress 4.4 or later.
 */
if ( version_compare( $GLOBALS['wp_version'], '4.4-alpha', '<' ) ) {
	require get_template_directory() . '/inc/back-compat.php';
}

if ( ! function_exists( 'twentysixteen_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 *
 * Create your own twentysixteen_setup() function to override in a child theme.
 *
 * @since Twenty Sixteen 1.0
 */
function twentysixteen_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed at WordPress.org. See: https://translate.wordpress.org/projects/wp-themes/twentysixteen
	 * If you're building a theme based on Twenty Sixteen, use a find and replace
	 * to change 'twentysixteen' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'twentysixteen' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for custom logo.
	 *
	 *  @since Twenty Sixteen 1.2
	 */
	add_theme_support( 'custom-logo', array(
		'height'      => 240,
		'width'       => 240,
		'flex-height' => true,
	) );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 1200, 9999 );

	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'twentysixteen' ),
		'social'  => __( 'Social Links Menu', 'twentysixteen' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 *
	 * See: https://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside',
		'image',
		'video',
		'quote',
		'link',
		'gallery',
		'status',
		'audio',
		'chat',
	) );

	/*
	 * This theme styles the visual editor to resemble the theme style,
	 * specifically font, colors, icons, and column width.
	 */
	add_editor_style( array( 'css/editor-style.css', twentysixteen_fonts_url() ) );

	// Indicate widget sidebars can use selective refresh in the Customizer.
	add_theme_support( 'customize-selective-refresh-widgets' );
}
endif; // twentysixteen_setup
add_action( 'after_setup_theme', 'twentysixteen_setup' );

/**
 * Sets the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 *
 * @since Twenty Sixteen 1.0
 */
function twentysixteen_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'twentysixteen_content_width', 840 );
}
add_action( 'after_setup_theme', 'twentysixteen_content_width', 0 );

/**
 * Registers a widget area.
 *
 * @link https://developer.wordpress.org/reference/functions/register_sidebar/
 *
 * @since Twenty Sixteen 1.0
 */
function twentysixteen_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'twentysixteen' ),
		'id'            => 'sidebar-1',
		'description'   => __( 'Add widgets here to appear in your sidebar.', 'twentysixteen' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => __( 'Content Bottom 1', 'twentysixteen' ),
		'id'            => 'sidebar-2',
		'description'   => __( 'Appears at the bottom of the content on posts and pages.', 'twentysixteen' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => __( 'Content Bottom 2', 'twentysixteen' ),
		'id'            => 'sidebar-3',
		'description'   => __( 'Appears at the bottom of the content on posts and pages.', 'twentysixteen' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'twentysixteen_widgets_init' );

if ( ! function_exists( 'twentysixteen_fonts_url' ) ) :
/**
 * Register Google fonts for Twenty Sixteen.
 *
 * Create your own twentysixteen_fonts_url() function to override in a child theme.
 *
 * @since Twenty Sixteen 1.0
 *
 * @return string Google fonts URL for the theme.
 */
function twentysixteen_fonts_url() {
	$fonts_url = '';
	$fonts     = array();
	$subsets   = 'latin,latin-ext';

	/* translators: If there are characters in your language that are not supported by Merriweather, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Merriweather font: on or off', 'twentysixteen' ) ) {
		$fonts[] = 'Merriweather:400,700,900,400italic,700italic,900italic';
	}

	/* translators: If there are characters in your language that are not supported by Montserrat, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Montserrat font: on or off', 'twentysixteen' ) ) {
		$fonts[] = 'Montserrat:400,700';
	}

	/* translators: If there are characters in your language that are not supported by Inconsolata, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Inconsolata font: on or off', 'twentysixteen' ) ) {
		$fonts[] = 'Inconsolata:400';
	}

	if ( $fonts ) {
		$fonts_url = add_query_arg( array(
			'family' => urlencode( implode( '|', $fonts ) ),
			'subset' => urlencode( $subsets ),
		), 'https://fonts.googleapis.com/css' );
	}

	return $fonts_url;
}
endif;

/**
 * Handles JavaScript detection.
 *
 * Adds a `js` class to the root `<html>` element when JavaScript is detected.
 *
 * @since Twenty Sixteen 1.0
 */
function twentysixteen_javascript_detection() {
	echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
}
add_action( 'wp_head', 'twentysixteen_javascript_detection', 0 );

/**
 * Enqueues scripts and styles.
 *
 * @since Twenty Sixteen 1.0
 */
function twentysixteen_scripts() {
	// Add custom fonts, used in the main stylesheet.
	wp_enqueue_style( 'twentysixteen-fonts', twentysixteen_fonts_url(), array(), null );

	// Add Genericons, used in the main stylesheet.
	wp_enqueue_style( 'genericons', get_template_directory_uri() . '/genericons/genericons.css', array(), '3.4.1' );

	// Theme stylesheet.
	wp_enqueue_style( 'twentysixteen-style', get_stylesheet_uri() );

	// Load the Internet Explorer specific stylesheet.
	wp_enqueue_style( 'twentysixteen-ie', get_template_directory_uri() . '/css/ie.css', array( 'twentysixteen-style' ), '20160816' );
	wp_style_add_data( 'twentysixteen-ie', 'conditional', 'lt IE 10' );

	// Load the Internet Explorer 8 specific stylesheet.
	wp_enqueue_style( 'twentysixteen-ie8', get_template_directory_uri() . '/css/ie8.css', array( 'twentysixteen-style' ), '20160816' );
	wp_style_add_data( 'twentysixteen-ie8', 'conditional', 'lt IE 9' );

	// Load the Internet Explorer 7 specific stylesheet.
	wp_enqueue_style( 'twentysixteen-ie7', get_template_directory_uri() . '/css/ie7.css', array( 'twentysixteen-style' ), '20160816' );
	wp_style_add_data( 'twentysixteen-ie7', 'conditional', 'lt IE 8' );

	// Load the html5 shiv.
	wp_enqueue_script( 'twentysixteen-html5', get_template_directory_uri() . '/js/html5.js', array(), '3.7.3' );
	wp_script_add_data( 'twentysixteen-html5', 'conditional', 'lt IE 9' );

	wp_enqueue_script( 'twentysixteen-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20160816', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_singular() && wp_attachment_is_image() ) {
		wp_enqueue_script( 'twentysixteen-keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20160816' );
	}

	wp_enqueue_script( 'twentysixteen-script', get_template_directory_uri() . '/js/functions.js', array( 'jquery' ), '20160816', true );

	wp_localize_script( 'twentysixteen-script', 'screenReaderText', array(
		'expand'   => __( 'expand child menu', 'twentysixteen' ),
		'collapse' => __( 'collapse child menu', 'twentysixteen' ),
	) );
}
add_action( 'wp_enqueue_scripts', 'twentysixteen_scripts' );

/**
 * Adds custom classes to the array of body classes.
 *
 * @since Twenty Sixteen 1.0
 *
 * @param array $classes Classes for the body element.
 * @return array (Maybe) filtered body classes.
 */
function twentysixteen_body_classes( $classes ) {
	// Adds a class of custom-background-image to sites with a custom background image.
	if ( get_background_image() ) {
		$classes[] = 'custom-background-image';
	}

	// Adds a class of group-blog to sites with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Adds a class of no-sidebar to sites without active sidebar.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	return $classes;
}
add_filter( 'body_class', 'twentysixteen_body_classes' );

/**
 * Converts a HEX value to RGB.
 *
 * @since Twenty Sixteen 1.0
 *
 * @param string $color The original color, in 3- or 6-digit hexadecimal form.
 * @return array Array containing RGB (red, green, and blue) values for the given
 *               HEX code, empty array otherwise.
 */
function twentysixteen_hex2rgb( $color ) {
	$color = trim( $color, '#' );

	if ( strlen( $color ) === 3 ) {
		$r = hexdec( substr( $color, 0, 1 ).substr( $color, 0, 1 ) );
		$g = hexdec( substr( $color, 1, 1 ).substr( $color, 1, 1 ) );
		$b = hexdec( substr( $color, 2, 1 ).substr( $color, 2, 1 ) );
	} else if ( strlen( $color ) === 6 ) {
		$r = hexdec( substr( $color, 0, 2 ) );
		$g = hexdec( substr( $color, 2, 2 ) );
		$b = hexdec( substr( $color, 4, 2 ) );
	} else {
		return array();
	}

	return array( 'red' => $r, 'green' => $g, 'blue' => $b );
}

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Add custom image sizes attribute to enhance responsive image functionality
 * for content images
 *
 * @since Twenty Sixteen 1.0
 *
 * @param string $sizes A source size value for use in a 'sizes' attribute.
 * @param array  $size  Image size. Accepts an array of width and height
 *                      values in pixels (in that order).
 * @return string A source size value for use in a content image 'sizes' attribute.
 */
function twentysixteen_content_image_sizes_attr( $sizes, $size ) {
	$width = $size[0];

	if ( 840 <= $width ) {
		$sizes = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 1362px) 62vw, 840px';
	}

	if ( 'page' === get_post_type() ) {
		if ( 840 > $width ) {
			$sizes = '(max-width: ' . $width . 'px) 85vw, ' . $width . 'px';
		}
	} else {
		if ( 840 > $width && 600 <= $width ) {
			$sizes = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 984px) 61vw, (max-width: 1362px) 45vw, 600px';
		} elseif ( 600 > $width ) {
			$sizes = '(max-width: ' . $width . 'px) 85vw, ' . $width . 'px';
		}
	}

	return $sizes;
}
add_filter( 'wp_calculate_image_sizes', 'twentysixteen_content_image_sizes_attr', 10 , 2 );

/**
 * Add custom image sizes attribute to enhance responsive image functionality
 * for post thumbnails
 *
 * @since Twenty Sixteen 1.0
 *
 * @param array $attr Attributes for the image markup.
 * @param int   $attachment Image attachment ID.
 * @param array $size Registered image size or flat array of height and width dimensions.
 * @return array The filtered attributes for the image markup.
 */
function twentysixteen_post_thumbnail_sizes_attr( $attr, $attachment, $size ) {
	if ( 'post-thumbnail' === $size ) {
		if ( is_active_sidebar( 'sidebar-1' ) ) {
			$attr['sizes'] = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 984px) 60vw, (max-width: 1362px) 62vw, 840px';
		} else {
			$attr['sizes'] = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 1362px) 88vw, 1200px';
		}
	}
	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'twentysixteen_post_thumbnail_sizes_attr', 10 , 3 );

/**
 * Modifies tag cloud widget arguments to display all tags in the same font size
 * and use list format for better accessibility.
 *
 * @since Twenty Sixteen 1.1
 *
 * @param array $args Arguments for tag cloud widget.
 * @return array The filtered arguments for tag cloud widget.
 */
function twentysixteen_widget_tag_cloud_args( $args ) {
	$args['largest']  = 1;
	$args['smallest'] = 1;
	$args['unit']     = 'em';
	$args['format']   = 'list'; 

	return $args;
}
add_filter( 'widget_tag_cloud_args', 'twentysixteen_widget_tag_cloud_args' );

/* RATBURGER LOCAL CODE */

/**
 * Custom KSES filter for the Forums component.
 *
 * @since 1.2.0
 *
 * @param string $content Content to sanitize.
 * @return string Sanitized string.
 */
function ratburger_forums_filter_kses( $content ) {
        global $allowedtags;

        $forums_allowedtags = $allowedtags;
        $forums_allowedtags['span'] = array();
        $forums_allowedtags['span']['class'] = array();
        $forums_allowedtags['div'] = array();
        $forums_allowedtags['div']['class'] = array();
        $forums_allowedtags['div']['id'] = array();
        $forums_allowedtags['a']['class'] = array();
        $forums_allowedtags['img'] = array();
        $forums_allowedtags['br'] = array();
        $forums_allowedtags['p'] = array();
        $forums_allowedtags['img']['src'] = array();
        $forums_allowedtags['img']['alt'] = array();
        $forums_allowedtags['img']['class'] = array();
        $forums_allowedtags['img']['width'] = array();
        $forums_allowedtags['img']['height'] = array();
        $forums_allowedtags['img']['class'] = array();
        $forums_allowedtags['img']['id'] = array();
        $forums_allowedtags['code'] = array();
        $forums_allowedtags['blockquote'] = array();

	    /* Ratburger additional allowed tags */
	    $forums_allowedtags['pre'] = array();
	    $forums_allowedtags['pre']['style'] = array();
	    $forums_allowedtags['span'] = array();
	    $forums_allowedtags['span']['style'] = array();
	    $forums_allowedtags['div'] = array();
	    $forums_allowedtags['div']['style'] = array();
        $forums_allowedtags['sub'] = array();
        $forums_allowedtags['sup'] = array();

        /**
         * Filters the allowed HTML tags for forum posts.
         *
         * @since 1.2.0
         *
         * @param array $forums_allowedtags Array of allowed HTML tags.
         */
        $forums_allowedtags = apply_filters( 'bp_forums_allowed_tags', $forums_allowedtags );
        return wp_kses( $content, $forums_allowedtags );
}

remove_filter('bp_get_the_topic_post_content', 'bp_forms_filter_kses', 1);
add_filter('bp_get_the_topic_post_content', 'ratburger_forums_filter_kses', 1);
remove_filter('bp_get_the_topic_post_excerpt', 'bp_forms_filter_kses', 1);
add_filter('bp_get_the_topic_post_excerpt', 'ratburger_forums_filter_kses', 1);

remove_filter('bp_get_activity_action', 'bp_forms_filter_kses', 1);
add_filter('bp_get_activity_action', 'ratburger_forums_filter_kses', 1);
remove_filter('bp_get_activity_content_body', 'bp_forms_filter_kses', 1);
add_filter('bp_get_activity_content_body', 'ratburger_forums_filter_kses', 1);
remove_filter('bp_get_activity_content', 'bp_forms_filter_kses', 1);
add_filter('bp_get_activity_content', 'ratburger_forums_filter_kses', 1);
remove_filter('bp_get_activity_parent_content', 'bp_forms_filter_kses', 1);
add_filter('bp_get_activity_parent_content', 'ratburger_forums_filter_kses', 1);
remove_filter('bp_get_activity_latest_update', 'bp_forms_filter_kses', 1);
add_filter('bp_get_activity_latest_update', 'ratburger_forums_filter_kses', 1);
remove_filter('bp_get_activity_latest_update_excerpt', 'bp_forms_filter_kses', 1);
add_filter('bp_get_activity_latest_update_excerpt', 'ratburger_forums_filter_kses', 1);
remove_filter('bp_get_activity_feed_item_description', 'bp_forms_filter_kses', 1);
add_filter('bp_get_activity_feed_item_description', 'ratburger_forums_filter_kses', 1);
remove_filter('bp_activity_content_before_save', 'bp_forms_filter_kses', 1);
add_filter('bp_activity_content_before_save', 'ratburger_forums_filter_kses', 1);
remove_filter('bp_activity_latest_update_content', 'bp_forms_filter_kses', 1);
add_filter('bp_activity_latest_update_content', 'ratburger_forums_filter_kses', 1);

/*

    Allow additional tags in WordPress KSES filtering

*/

function ratburger_add_allowed_tags() {
	global $allowedtags;
    global $allowedposttags;

	$allowedtags['pre'] = array('style'=>array());
	$allowedtags['ol'] = array();
	$allowedtags['ul'] = array();
	$allowedtags['li'] = array();
	$allowedtags['p'] = array();
	$allowedtags['p']['style'] = array();
	$allowedtags['span'] = array();
	$allowedtags['span']['style'] = array();
    $allowedtags['sub'] = array();
    $allowedtags['sup'] = array();

    // Add attributes to already allowed tags
    $allowedposttags['blockquote']['class'] = true;
    $allowedtags['blockquote']['class'] = true;
}

add_action('init', 'ratburger_add_allowed_tags', 10);

function ratburger_filter_tiny_mce_before_init( $options ) {
 
    if ( ! isset( $options['extended_valid_elements'] ) ) {
        $options['extended_valid_elements'] = '';
    } else {
        $options['extended_valid_elements'] .= ',';
    }
 
    if ( ! isset( $options['custom_elements'] ) ) {
        $options['custom_elements'] = '';
    } else {
        $options['custom_elements'] .= ',';
    }
 
    $options['extended_valid_elements'] .= 'pre[class|id|style]';
    $options['custom_elements']         .= 'pre[class|id|style]';
    return $options;
}

add_filter('tiny_mce_before_init', 'ratburger_filter_tiny_mce_before_init');

/*

    Add our custom items to the Meta widget

*/

function ratburger_meta_widget_items() {
    echo '<li><a href="/index.php/frequently-asked-questions/">Frequently Asked Questions</a></li>';
    echo '<li><a href="/index.php/privacy/">Privacy</a></li>';
    echo '<li><a href="https://twitter.com/Ratburger_org" target="_blank">Twitter</a></li>';
    echo '<li><a href="/index.php/podcasts/">Podcasts</a></li>';
    echo '<li><a href="/index.php/statistics/">Access Statistics</a></li>';
}

add_action('wp_meta', 'ratburger_meta_widget_items');

/*

    Add our custom CSS to administration and main site pages

    We can't just add the CSS to style.css in the theme
    because that doesn't get loaded for administration
    pages.

*/

function ratburger_register_admin_styles() {
    wp_register_style('ratburger_css',
        get_template_directory_uri() . '/ratburger/ratburger.css');
    wp_enqueue_style('ratburger_css');
}
add_action('admin_enqueue_scripts', 'ratburger_register_admin_styles');
add_action('wp_enqueue_scripts', 'ratburger_register_admin_styles');

/*
	Utility function to dump a variable with a label

*/

function RB_dumpvar($label, $var) {
	error_log($label . ": " . print_r($var, TRUE));
}

/*

    Test whether we're running under my development
    account (party card 2).  This is handy for diagnostic
    code like:
        if (RB_me()) { RB_dumpvar('furbish', $furbish); }
    or: RB_mdumpvar('lousewort', lousewort);

*/

function RB_me() {
    return get_current_user_id() == 2;
}


function RB_mdumpvar($label, $var) {
    if (RB_me()) {
        RB_dumpvar($label, $var);
    }
}

/*
	Add notifications when a comment is added

*/

add_filter('wp_insert_comment', 'ratburger_wp_insert_comment', 10, 2);

function ratburger_wp_insert_comment($id, $comment) {

    /* If the body of the comment consists exclusively of the
       text "c4c" or "follow" (case-insensitive), possibly
       preceded by various kinds of white space and followed by
       white space and sentence-ending punctuation, this is
       taken to be a comment made solely to follow the post.  In
       this case, we don't perform notification of the comment
       to either the author of the post or others who have
       commented on the post. */
    global $Ratburger_follow_comment_pattern;
    if (preg_match($Ratburger_follow_comment_pattern,
        $comment->comment_content)) {
        return;
    }

	$post = get_post($comment->comment_post_ID, 'OBJECT', 'raw');

	/* If the comment was made by a person other than the
	   author of the post, queue a notification of the
	   comment to the post's author. */
	if ($comment->user_id != $post->post_author) {
//	if ($comment->user_id == 2) { // Hack for testing: only try for me.
	    bp_notifications_add_notification(
		array(
		    'user_id' => $post->post_author,
		    'item_id' => $comment->comment_ID,
		    'secondary_item_id' => $post->ID,
		    'component_name' => 'wp_ulike',
		    'component_action' => 'wp_ulike_' . 'commentadded' . '_action_' . $comment->user_id,
		    'date_notified' => bp_core_current_time(),
		    'is_new' => 1
	        )
	    );
	}

	/* Now, walk through the comments on the post to which this
	   is a comment and prepare an associative array of users,
	   excluding the post author (who we've notified above) and
	   the author of this comment, who have commented on the
	   post.  Notify them of the new comment in the discussion
	   of this post. */

//	if ($comment->user_id == 2) { // Hack for testing: only try for me.
	    $cq = new WP_Comment_Query;
	    $comments = $cq->query(array(
		'post_id' => $post->ID,
		'fields' => 'ids',
		'status' => 'approve',
		'order' => 'ASC'
		)
	    );
	    $commenters = array();
	    foreach ($comments as $cmt) {
		$c = get_comment($cmt, 'OBJECT');
		if (!empty($c)) {
		    if (!(($c->user_id == $comment->user_id) || ($c->user_id == $post->ID))) {
			if ($c->user_id > 0) {	// Can't notify guests
			    $commenters[$c->user_id] = 1;
			}
		    }
		}
	    }
	    foreach (array_keys($commenters) as $commenter) {
		if (($u = get_userdata($commenter)) && $u->user_registered) {
                   bp_notifications_add_notification(
                       array(
                           'user_id' => $commenter,
                           'item_id' => $comment->comment_ID,
                           'secondary_item_id' => $post->ID,
                           'component_name' => 'wp_ulike',
                           'component_action' => 'wp_ulike_' . 'commentadded' . '_action_' . $comment->user_id,
                           'date_notified' => bp_core_current_time(),
                           'is_new' => 1
                        )
                    );
		}
	    }
//	}
}

/*

	Add notifications when a post is made to a group

*/

add_filter('bp_groups_posted_update', 'ratburger_bp_groups_posted_update', 10, 4);

function ratburger_bp_groups_posted_update($content, $user_id, $group_id, $activity_id) {
//RB_dumpvar('Group update', array($activity_id, $group_id, $user_id, $content));

	//  Get a list of members in the group
	$gmembc = groups_get_total_member_count($group_id);
	$members = groups_get_group_members(
	    array(
		'group_id' => $group_id,
		'page' => 1,
		'per_page' => $gmembc,
		'exclude_admins_mods' => false,
		'exclude_banned' => true,
		'exclude' => array($user_id)
	    )
	);

	/*  Walk through the list of members sending notifications
	    to each.  Note that we excluded the user who made the post
	    in the query above, so we don't need to test for that here.  */

	foreach ($members['members'] as $memb) {
	    $uid = $memb->user_id;
	    bp_notifications_add_notification(
               array(
                   'user_id' => $uid,
                   'item_id' => $activity_id,
                   'secondary_item_id' => $group_id,
                   'component_name' => 'wp_ulike',
                   'component_action' => 'wp_ulike_' . 'grouppost' . '_action_' . $user_id,
                   'date_notified' => bp_core_current_time(),
                   'is_new' => 1
                )
            );
	}
}

// Load build information.  We don't require this to exist.

include get_template_directory() . '/ratburger/build.php';

//  Mark all of a user's notifications read

function rb_notif_mark_all_read() {
    if (bp_has_notifications(
            array('is_new' => 1,
                  'max' => false,
                  'per_page' => 10000000))) {
        while (bp_the_notifications()) {
            $notif = bp_the_notification();
            $notif_id = bp_get_the_notification_id();
            if (!bp_notifications_mark_notification($notif_id, false)) {
            }
        }
    }
}

//  Delete all of a user's read notifications

function rb_notif_delete_all_read() {
    if (bp_has_notifications(
            array('is_new' => 0,
                  'max' => false,
                  'per_page' => 10000000))) {
        while (bp_the_notifications()) {
            $notif = bp_the_notification();
            $notif_id = bp_get_the_notification_id();
            if (!bp_notifications_delete_notification($notif_id)) {
            }
        }
    }
}

/*  Change expiration date for "Remember Me" cookie from 14
    to 180 days.  */

add_filter('auth_cookie_expiration', 'rb_expiration_filter', 99, 3);
function rb_expiration_filter($seconds, $user_id, $remember){

    //  If "Remember Me" is checked;
    if ($remember) {
        //  WP defaults to 2 weeks
        $expiration = 180 * DAY_IN_SECONDS;
    } else {
        //  WP defaults to 48 hrs/2 days
        $expiration = 2 * DAY_IN_SECONDS;
    }

    return $expiration;
}

/*  Display user's description/biography, "Party card number"
    and date joined. This is invoked when displaying a user's
    profile, just after the last activity line.  */

add_action('bp_before_member_header_meta', 'rb_party_card');
function rb_party_card() {
    echo("<div id=\"rb-party-card\">\n");

    /*  Display user's description/biography, if any.  */

    $desc = get_user_meta(bp_displayed_user_id(), 'description', true);

    if ($desc && ($desc !== '')) {
        echo("<p style=\"text-align: justify; margin-bottom: 0px; margin-top: 4px;\">\n");
        echo("<span class=\"activity\">\n");
        echo($desc . "<br />\n");
        echo("</span>\n");
        echo("</p>\n");
    }

    /*  Display "Party card number" and date joined.  */

    $dispid = bp_displayed_user_id();
    $joined = preg_replace('/^(\d+\-\d+\-\d+).*$/', '$1',
        get_user_by('id', bp_displayed_user_id())->user_registered, 1);
    echo("<p style=\"margin-top: 4px;\">\n");
    echo("<span class=\"activity\">\n");
    echo("Party card no. $dispid<br />\n");
    echo("Joined $joined\n");
    echo("</span>\n");
    echo("</p>\n");

    echo("</div>\n");
}

/*  The following function is invoked to filter the list of
    navigation menu items before they are output to the HTML
    file.  It implements a simple macro facility which allows
    expanding "%author%" to the nicename of the currently
    logged-in user.  This is used by custom link menu items
    which need to include the user's name in URLs.  */

function rb_modify_nav_menu($items, $args) {
    if ( is_user_logged_in() ) {
        $items = preg_replace('/%author%/',
            wp_get_current_user()->user_nicename, $items);
    }
    return $items;
}
add_filter('wp_nav_menu_items', 'rb_modify_nav_menu', 10, 2);

/*  Remove trailing white space from post or comment text before
    posting to the database.  */

function rb_trim_trailing_space($text) {
    $text = preg_replace('/(?:\s|&nbsp;)+$/', '', $text);
    return $text;
}

/*  We need a special function when processing new
    comments because the 'preprocess_comment' filter
    gets the entire comment object as its argument, not
    just the comment body text.  */

function rb_trim_comment_trailing_space($comment) {
    $comment['comment_content'] =
        rb_trim_trailing_space($comment['comment_content']);
    return $comment;
}

//  When adding a new comment
add_filter('preprocess_comment', 'rb_trim_comment_trailing_space');
//  When saving an edited comment
add_filter('comment_save_pre', 'rb_trim_trailing_space');
//  When saving a new or edited post
add_filter('content_save_pre', 'rb_trim_trailing_space');

/*  When displaying the notification for a message or
    multiple messages, wrap it with a <span> that
    applies our local styles.  */

function rb_filter_notification_message($return, $total_items,
    $text, $item_id, $secondary_item_id) {
    /* At the point this function gets invoked, the $return
       argument can either be an array containing the text and
       the URL link with which it should be wrapped or a string
       containing the HTML ready to insert.  Of course, it does
       not pass you the $format argument to tell you which to
       expect.  So we cope with this by testing the type of the
       argument here, and only wrap it with the classes if it's
       of the array form, which means it came from the code that
       build the notifications drop-down list, as opposed to the
       notifications editing page, where we don't want to modify
       the style. */
    if (gettype($return) == 'array') {
        $return['text'] = '<span class="rb_notif_new_message rb_notif_highlight">' .
            $return['text'] . '</span>';
    }
    return $return;
}

add_filter('bp_messages_multiple_new_message_notification',
    'rb_filter_notification_message', 10, 5);
add_filter('bp_messages_single_new_message_notification',
    'rb_filter_notification_message', 10, 5);

/*  Increase the length of excerpts shown for group posts
    and comments.  */

function rb_filter_bp_activity_excerpt_length($howlong) {
    return 600;
}

add_filter('bp_activity_excerpt_length',
    'rb_filter_bp_activity_excerpt_length');

/*  Regular expression used to detect from the content of a
    comment whether it is a comment made only to follow the post
    on which it was made.

    We test whether the body of the comment consists exclusively
    of the text "c4c" or "follow" (case-insensitive), possibly
    preceded by various kinds of white space and followed by
    white space and sentence-ending punctuation.  */

global $Ratburger_follow_comment_pattern;
$Ratburger_follow_comment_pattern =
    "/^(?:[\s\R]|&nbsp;)*(?:c4c|follow)([\s\R\.\!\?]|&nbsp;)*$/i";


/* END RATBURGER LOCAL CODE */
