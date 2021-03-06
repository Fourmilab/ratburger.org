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
        $forums_allowedtags['a']['target'] = array();
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

/*  Replace the standard "bp_activity_filter_kses" function with
    our custom, and more permissive, "ratburger_forums_filter_kses".  */

remove_filter("bp_get_activity_content_body", "bp_activity_filter_kses", 1);
add_filter("bp_get_activity_content_body", "ratburger_forums_filter_kses", 1);

remove_filter("bp_get_activity_content", "bp_activity_filter_kses", 1);
add_filter("bp_get_activity_content", "ratburger_forums_filter_kses", 1);

remove_filter("bp_get_activity_parent_content", "bp_activity_filter_kses", 1);
add_filter("bp_get_activity_parent_content", "ratburger_forums_filter_kses", 1);

remove_filter("bp_get_activity_latest_update", "bp_activity_filter_kses", 1);
add_filter("bp_get_activity_latest_update", "ratburger_forums_filter_kses", 1);

remove_filter("bp_get_activity_latest_update_excerpt", "bp_activity_filter_kses", 1);
add_filter("bp_get_activity_latest_update_excerpt", "ratburger_forums_filter_kses", 1);

remove_filter("bp_get_activity_feed_item_description", "bp_activity_filter_kses", 1);
add_filter("bp_get_activity_feed_item_description", "ratburger_forums_filter_kses", 1);

remove_filter("bp_activity_content_before_save", "bp_activity_filter_kses", 1);
add_filter("bp_activity_content_before_save", "ratburger_forums_filter_kses", 1);

remove_filter("bp_activity_latest_update_content", "bp_activity_filter_kses", 1);
add_filter("bp_activity_latest_update_content", "ratburger_forums_filter_kses", 1);

/*  Add target="_blank" to links in group posts and comments
    so they open in a new tab/window.  */

add_filter("bp_get_activity_content_body", "RB_fix_link_targets", 97);  // Posts
add_filter("bp_get_activity_content", "RB_fix_link_targets", 97);       // Comments

/*

    Allow additional tags in BuddyPress group descriptions

*/


add_filter("bp_groups_filter_kses", "rb_bp_groups_filter_kses", 1);
remove_filter("groups_group_description_before_save", "wp_filter_kses", 1);
add_filter("groups_group_description_before_save", "bp_groups_filter_kses", 1);

function rb_bp_groups_filter_kses($tags) {
    $tags["a"]["target"] = array();

    return $tags;
}

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
	$allowedtags['span']['class'] = array();
	$allowedtags['span']['style'] = array();
    $allowedtags['sub'] = array();
    $allowedtags['sup'] = array();
    $allowedtags['hr'] = array();

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
//RB_dumpvar("Post", $options['plugins']);
    return $options;
}

add_filter('tiny_mce_before_init', 'ratburger_filter_tiny_mce_before_init');

/*

    Add our custom items to the Meta widget

*/

function ratburger_meta_widget_items() {
    echo '<li><a href="/index.php/frequently-asked-questions/">Frequently Asked Questions</a></li>';
    echo '<li><a href="/index.php/category/knowledge-base/">Knowledge Base</a></li>';
    echo '<li><a href="/index.php/privacy/">Privacy</a></li>';
    echo '<li><a href="https://twitter.com/Ratburger_org" target="_blank">Twitter</a></li>';
    echo '<li><a href="/index.php/podcasts/">Podcasts</a></li>';
    echo '<li><a href="/index.php/video-theatre/">Video Theatre</a></li>';
    if (is_user_logged_in()) {
        echo '<li><a href="/index.php/second-life-clubhouse/">Second Life Clubhouse</a></li>';
    }
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

    Print a stack trace on the error log

*/

function RB_stacktrace() {
    $e = new Exception;
    error_log(var_export($e->getTraceAsString(), true));
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

function RB_chef() {
    return get_current_user_id() == 1;
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
                if (!(($c->user_id == $comment->user_id) ||
                      ($c->user_id == $post->ID))) {
                    if ($c->user_id > 0) {  // Can't notify guests
                        $commenters[$c->user_id] = 1;
                    }
                }
            }
        }

        /*  Query users who have liked this post and add them
            to the associative array of those to be notified
            of the comment.  Once again, we exclude the author
            of the post (who has already been notified) and the
            author of the comment (who presumably is aware of
            the comment they've just posted).  */

                // Global WordPress database object
                global $wpdb;
                // Get likers list
                $likers =  $wpdb->get_results(
                    "SELECT user_id FROM " .
                    $wpdb->prefix .
                    "ulike WHERE post_id = '$post->ID' " .
                    "AND status = 'like' " .
                    "AND user_id BETWEEN 1 AND 999999 " .
                    "GROUP BY user_id LIMIT 999999");
        if (!empty($likers)) {
            foreach ($likers as $liker) {
                if (!(($liker->user_id == $comment->user_id) ||
                      ($liker->user_id == $post->ID))) {
                    $commenters[$liker->user_id] = 1;
                }
            }
        }

        /*  Finally, notify everybody we've identified as a
            commenter or liker of this post of the new comment
            made on it.  */

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

/*  Prune expired notifications

    Some users do not pay attention to notifications,
    neither clicking through them (which marks them read)
    or manually clearing them.  This can result in a huge
    number of notifications being displayed on every
    page in the notification drop-down menu.  Code in:
        ~/plug/buddypress/bp-notifications/bp-notifications-adminbar.php
    calls this function when it observes a suspiciously
    large number of notifications queued for a user.  It
    scans the notifications and deletes any which was
    originally posted more than $exptime in seconds
    ago.

    We delete the notifications rather than marking them
    read because a user who ignores unread notifications
    is unlikely to visit or be inclined to clear up ones
    marked as read.

*/

function rb_notif_prune($exptime) {
    global $current_user;
    $num_notif = 0;
    $num_pruned = 0;
    $num_failed = 0;

    if (bp_has_notifications(
            array('is_new' => 1,                // Retrieve only unread notifications
                  'max' => false,
                  'per_page' => 10000000))) {
        while (bp_the_notifications()) {
            $num_notif++;
            $notif = bp_the_notification();
            $notif_id = bp_get_the_notification_id();
                $RB_time = explode(':', str_replace(' ', ':', bp_get_the_notification_date_notified()));
                $RB_date = explode('-', str_replace(' ', '-', bp_get_the_notification_date_notified()));
                $RB_not_time  = gmmktime((int) $RB_time[1], (int) $RB_time[2], (int) $RB_time[3],
                                     (int) $RB_date[1], (int) $RB_date[2], (int) $RB_date[0]);
            $RB_not_age = time() - $RB_not_time;
            if (($RB_not_age) > $exptime) {
                /*  Note that we can't use bp_notifications_delete_notification() here
                    because it requires to be called in the context of a profile
                    page.  We must call the lower level delete method here in order
                    to bypass that check.  */
                if (BP_Notifications_Notification::delete(array('id' => $notif_id))) {
                    $num_pruned++;
 //error_log("rb_notif_prune " . print_r($notif_id, TRUE) .
 //    "  age " . print_r($RB_not_age, TRUE) .
 //    "  date " . print_r($RB_not_time, TRUE) .
 //    "  " . print_r(bp_get_the_notification_description(), TRUE));
                } else {
                    $num_failed = 0;
                    RB_dumpvar("rb_notif_prune cannot delete", $notif_id);
                }
            }
        }
//error_log("rb_notif_prune: " . print_r(bp_loggedin_user_id(), TRUE) .
//    " (" . $current_user->user_login . ")" .
//    "  Notifications: " . print_r($num_notif, TRUE) . ", " .
//        print_r($num_pruned, TRUE) . " pruned, " .
//        print_r($num_failed, TRUE) . " failed.");
        return $num_pruned;
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

    /*  Trim a text string to the specified length.  If the
        string is already shorter or equal to the length, it is
        returned unchanged.  Otherwise, the string is trimmed to
        the specified length.  If the trim caused a word to be
        broken, delete the fragment of the broken word from the
        end of the trimmed string. Any trailing white space is
        removed and a Unicode horizontal ellipsis is appended to
        the string to indicate it has been trimmed.  */

    function rb_trimtext($text, $length) {

        //  If it's already shorter than $length, return unchanged
        if (strlen($text) <= $length) {
            return $text;
        }

        //  Extract desired length
        $t = substr($text, 0, $length);

        /*  If trimming the string broke a word, remove the
            fragment of the broken word from the end of the
            string.  */
        if (preg_match("/\w/", substr($text, $length, 1)) &&
            preg_match("/\w/", substr($t, -1, 1))) {
            $t = preg_replace("/\s+\w+$/", "", $t, 1);
        }

        //  Trim any trailing white space from string
        $t = preg_replace("/\s+$/", "", $t, 1);

        //  Append horizontal ellipsis character
        $t .= "\u{2026}";

        return $t;
    }

/*  Register oEmbed providers we add to the white list.
    These providers are not subjected to the filtering
    that those not on the list suffer. */

function rb_custom_oembed_providers() {
        wp_oembed_add_provider(
        'https://*.nytimes.com/*',
        'https://www.nytimes.com/svc/oembed/json',
        false);
}
add_action('init', 'rb_custom_oembed_providers');

/*  Test whether current user is on probation.  We use
    the "edit_posts" capability as a proxy for probation.
    In our role architecture, the only users who do not
    have this capability are those on probation.  */

function rb_on_probation() {
    return !bp_current_user_can('edit_posts');
}

/*  When sending a User Approval E-mail to administrators
    from the New User Approve plug-in, modify the composed
    message text to include the IP address from which the
    user registered the account.  This allows easier vetting
    of new sign-ups against spam databases.  */

function rb_add_IP_address_to_user_approve_Email($message,
    $user_login, $user_email) {

    return str_replace(") has",
                       ") IP " . $_SERVER['REMOTE_ADDR'] .
                                 " has", $message);
}

add_filter("new_user_approve_request_approval_message",
           "rb_add_IP_address_to_user_approve_Email", 10, 3);

/*  Include a column with the IP address from which the user
    registered the account on the Pending accounts page in
    the Administrator dashboard.  */

function rb_bp_members_signup_columns() {
    return array(
        'cb'         => '<input type="checkbox" />',
        'username'   => __( 'Username',    'buddypress' ),
        'name'       => __( 'Name',        'buddypress' ),
        'email'      => __( 'Email',       'buddypress' ),
        'ip_addr'    => __( 'IP Address',  'ratburger'  ),
        'registered' => __( 'Registered',  'buddypress' ),
        'date_sent'  => __( 'Last Sent',   'buddypress' ),
        'count_sent' => __( 'Emails Sent', 'buddypress' )
    );
}

add_filter("bp_members_signup_columns", "rb_bp_members_signup_columns");

function rb_bp_members_signup_custom_column($value, $column_name, $signup_object) {
    if ($column_name == "ip_addr") {
        //  Why do we need to do this?  Because $signup_object->id is a cruel joke
        $usr = get_user_by("login", $signup_object->user_login);
        $ip = get_user_meta($usr->ID, 'signup_ip', true);
        echo $ip;
    }
}

add_filter("bp_members_signup_custom_column", "rb_bp_members_signup_custom_column", 10, 3);

/*  Add override to query string used to obtain list of
    posts for the "Recent Posts" widget to use our date
    criterion rather than a fixed number of posts.  We
    also allow logged-in users to see private posts,
    while guests can see only published posts.  */

function rb_select_recent_posts($a) {
    return array(
        'date_query'          => array(
                                'column' => 'post_date_gmt',
                                'after' => '36 hours ago'
                             ),
        'posts_per_page'      => -1,
        'no_found_rows'       => true,
        'post_status'         => is_user_logged_in() ?
            array('publish', 'private') : 'publish',
        'ignore_sticky_posts' => true
                );
}
add_filter('widget_posts_args', 'rb_select_recent_posts', 10, 1);

/*  In the "Recent Comments" widget, we allow users who are
    logged in to see comments on private posts as well as
    published posts.  Guests will see only comments on
    published posts.  */

function rb_select_recent_comments($a) {
    if (is_user_logged_in()) {
        $a['post_status'] = array('publish', 'private');
    }
    return $a;
}
add_filter('widget_comments_args', 'rb_select_recent_comments', 10, 1);

/*  Add a clock to the administration toolbar.  The code below
    includes the placeholder for the clock and invokes the
    JavaScript which updates it.  If the JavaScript is not
    loaded, the clock remains blank.  */

function rb_add_clock_toolbar_menu() {
    global $wp_admin_bar;

    $wp_admin_bar->add_menu(array(
        'parent' => 'top-secondary',
        'id' => 'rb-toolbar-clock',
        'title' => '<span id="rb_toolbar_clock"></span>'));
}
add_action('admin_bar_menu', 'rb_add_clock_toolbar_menu', 95);

/*  Enqueue the JavaScript support code for the toolbar clock
    to be included in both user and administration pages.  We
    have to do this via an external script enqueued here rather
    than including the code in the theme's functions.js because
    that is not loaded for administration pages.  */

function rb_enqueue_toolbar_clock() {
    wp_enqueue_script("RB_toolbar_clock",
        get_template_directory_uri() . "/js/rb_toolbar_clock.js");
}
add_action("wp_enqueue_scripts", "rb_enqueue_toolbar_clock");
add_action("admin_enqueue_scripts", "rb_enqueue_toolbar_clock");

/*  Add our local system status panel to the administration
    dashboard "At a Glance" panel.  We only show this if the
    user is an administrator.  */

function rb_dashboard_system_status() {
    if (current_user_can("manage_options")) {
        $atop = exec('top -b -n 1 | fgrep "avail Mem"');
        if (preg_match("/(\d+)\s+avail\s+Mem/", $atop, $aton)) {
            $atoc = rb_commas($aton[1]);
            echo "<p class='ratburger-dashboard-system-status'>" .
                "Available memory: $atoc kB.</p>\n";
        }
    }
}
add_action("rightnow_end", "rb_dashboard_system_status", 95);

/*  Return decimal integer argument with delimeters
    separating commas.  It's up to the caller to remove
    any sign or decimal part of the number argument
    before calling and re-attach them to the result.  */

function rb_commas($n, $Thousands = ",") {
    $text = strrev($n);
    $text = preg_replace("/(\d\d\d)(?=\d)(?!\d*\.)/",
                "$1$Thousands", $text);
    return strrev($text);
}

/*  Enqueue the JavaScript code, which will be embedded into the
    head of both user and administration pages, which checks if
    we're running on the "Raw" test server and, if so, modifies
    the site name in the administration bar at the top to alert
    the user they're on the test server.  */

function rb_enqueue_check_test_server() {
    wp_enqueue_script("RB_check_test_server",
        get_template_directory_uri() . "/js/rb_check_test_server.js");
}
add_action("wp_enqueue_scripts", "rb_enqueue_check_test_server");
add_action("admin_enqueue_scripts", "rb_enqueue_check_test_server");

/*  When a post is about to be inserted in the database make
    sure its title is not blank.  If the title is blank, revert
    to draft and issue an error message to the author/editor.  */

function rb_check_blank_post_title($data, $postarr) {
    if (is_array($data) &&
        ($data["post_status"] == "publish" ||
         $data["post_status"] == "private") &&
        preg_match("/^\s*$/", $data["post_title"])) {
        $data["post_status"] = "draft";
        update_option("rb_post_blank_title", "empty_title");
    }

    return $data;
}
add_filter("wp_insert_post_data", "rb_check_blank_post_title", 10, 2);

/*  If post title was empty, don't show post published message(s)

function rb_remove_post_error_messages($messages) {
    if (get_option("rb_post_blank_title")) {
        return array();         // Return void message array
    } else {
        return $messages;
    }
}
add_filter("post_updated_messages", "rb_remove_post_error_messages");

/*  Display error message for blank title in post.  */

function rb_show_empty_title_error() {
    $screen = get_current_screen();
    if ($screen->id != "post") {
        return;
    }
    if (!get_option("rb_post_blank_title")) {
        return;
    }
    echo '<div class="error"><p>' .
         esc_html__("Post title is blank.  Please enter a title for the post.", "RB" ) .
         "</p></div>";
    delete_option("rb_post_blank_title");
}
add_action("admin_notices", "rb_show_empty_title_error");

/*  Display both published and private posts in dashboard.  */

function rb_show_published_private_dashboard($query_args) {
    if ($query_args["post_status"] == "publish") {
        $query_args["post_status"] = array("publish", "private");
    }
    return $query_args;
}

add_filter("dashboard_recent_posts_query_args", "rb_show_published_private_dashboard");

/*  Automatically turn contents of posts which look like
    valid URLs into clickable links.  Note that the built-in
    function make_clickable() is compatible with the function
    expected by the the_content filter.  */
add_filter('the_content', 'make_clickable', 20);

/*  Suppress automatic linking of URLs which will be embedded
    (such as YouTube) when pasted into the TinyMCE comment
    editor provided by the tinymce-comment-field plug-in.  The
    default automatic linking breaks embedding and the user must
    manually remove the link to enable embedding.  We suppress
    the automatic linking by a two step process: first enable
    the TinyMCE "paste" plug-in, then supply a call-back for the
    "paste_preprocess" hook in the plug-in which removes the
    unwanted link around URLs we wish to embed.  */

function RB_filter_teeny_mce_before_init($options) {
    $options['paste_preprocess'] =
            "function(pl, o) {
                if (o.content.match(/https?:\/\/((m|www)\.)?youtube\.com\/watch\?/i) ||
                    o.content.match(/https?:\/\/((m|www)\.)?youtube\.com\/playlist\?/i) ||
                    o.content.match(/https?:\/\/youtu\.be\//i)) {
                    o.content = o.content.replace(/<a\s+href=[^>]*>/, '');
                    o.content = o.content.replace(/<\/a>/, '');
                }
            }";
    $options['plugins'] = str_replace("lists,fullscreen",
                                      "lists,paste,fullscreen",
                                      $options['plugins']);
    return $options;
}

add_filter('teeny_mce_before_init', 'RB_filter_teeny_mce_before_init');

/*  Fix links within posts and comments to open in a new
    tab/window.  This code accomplishes what the WP External
    Links plug-in attempts to do, but falls short.  Given the
    body of a post or comment, each link (<a> tag) is located
    and analysed.  If it already contains a target= attribute,
    and the target is "_blank", nothing need be done.  If the
    target is different, the link was probably pasted in as
    HTML from another source, and contains targets relevant to
    the source site, not us, so we replace the target with
    "_blank".  If there is no target at all, we add one at
    the end of the link tag, again specifying "_blank".  This
    is done regardless of whether the link is internal or
    external to the site.  */

function RB_fix_link_targets($ctext) {
    /*  Process all links embedded in the comment text, even
        if they span multiple lines.  Extract link body.  */

    return preg_replace_callback("|(<a\s+)([^>]*)(>)|im",
        function($m) {

            /*  If the link contains class="more-link", this
                is a link to "Continue reading" the a post of
                which this is the excerpt.  We wish to open
                the full post in the same tab/window, so we
                skip modification of the target here.  */

            if (strpos($m[2], "class=\"more-link\"") !== FALSE) {
                return $m[0];
            }

            /*  If the link contains a target attribute,
                extract the target name.  */

            if (preg_match("|(^.*target=['\"])(\w*)(['\"].*$)|im",
                    $m[2], $tgt)) {

                /*  If the target is "_blank", this link is
                    already set to open in a new tab/window:
                    return it unmodified.  */

                if ($tgt[2] == "_blank") {
                    return $m[0];
                }

                /*  This link contains a target= attribute
                    but the target isn't "_blank".  This is
                    probably a link pasted in as part of HTML
                    from another site.  Replace the target with
                    "_blank" so it opens in a new tab/window here.  */


                return $m[1] . $tgt[1] . "_blank" . $tgt[3] . $m[3];
            }

            /*  This link has no target= attribute.  Add a
                target="_blank" at the end of the link.  */

            return $m[1] . $m[2] . ' target="_blank"' . $m[3];
        },
        $ctext, -1);
}

//  Process links in comment body text

function RB_open_comment_links_in_new_tab($ctext, $cobj = null) {
    if ($cobj !== null) {       // Only process before display

        /*  Process all links embedded in the comment body.  */

        $ctext = RB_fix_link_targets($ctext);
    }
    return $ctext;
}

   add_filter("comment_text", "RB_open_comment_links_in_new_tab", 97, 2);

//  Process links in post body text

function RB_open_post_links_in_new_tab($ptext) {
    global $post;

    if (in_the_loop() && is_main_query() &&
        ($post->post_type == "post")) {

        /*  Process all links embedded in the post body.  */

        $ptext = RB_fix_link_targets($ptext);
    }
    return $ptext;
}

   add_filter("the_content", "RB_open_post_links_in_new_tab", 97);

/*  Disable periodic nagging of administrators to confirm
    their E-mail addresses.  This idiotic "feature" was added
    in WordPress 5.3.  This one-liner gets rid of it.  */

add_filter("admin_email_check_interval", "__return_false");

/*  Unconditionally prohibit trackbacks/pingbacks regardless
    of whether the post says they're accepted.  */

function RB_disable_pingbacks($open, $post_id) {
    return false;
}

add_filter("pings_open", "RB_disable_pingbacks", 10, 2);

/*  Remove the Subscribe to Comments Reloaded plug-in's
    enabling non-logged-in visitors to the site to subscribe
    E-mail addresses to comments.  */

function RB_subscribe_reloaded_disable_visitors() {
    global $wp_subscribe_reloaded;
    remove_action("comment_form_must_log_in_after",
        array($wp_subscribe_reloaded->stcr, "subscribe_reloaded_show"), 5);
}

add_action("wp_loaded", "RB_subscribe_reloaded_disable_visitors");

//  For developers, load the diagnostic functions

if (RB_me() || RB_chef()) {
    include get_template_directory() . '/ratburger/fnord.php';
}

/* END RATBURGER LOCAL CODE */
