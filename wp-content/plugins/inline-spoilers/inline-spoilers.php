<?php
/**
Plugin Name: Inline Spoilers
Plugin URI: https://wordpress.org/plugins/inline-spoilers/
Description: The plugin allows to create content spoilers with simple shortcode.
Version: 1.3.3
Author: Sergey Kuzmich
Author URI: http://kuzmi.ch
Text Domain: inline-spoilers
Domain Path: /languages/
License: GPLv3
*/

/**
 * @package Inline Spoilers
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

add_action( 'plugins_loaded', 'is_load_textdomain' );
function is_load_textdomain() {
	load_plugin_textdomain( 'inline-spoilers', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

add_shortcode( 'spoiler', 'is_spoiler_shortcode' );
function is_spoiler_shortcode( $atts, $content ) {
	$output = '';
	$head   = '';
	$body   = '';
	$extra  = '';

	$attributes = shortcode_atts(
		array(
			'title'         => __( 'Spoiler', 'inline-spoilers' ),
			'initial_state' => 'collapsed',
		),
		$atts,
		'spoiler'
	);

	$title         = $attributes['title'];
	$initial_state = $attributes['initial_state'];

	$title      = esc_attr( $title );
	$head_class = ( esc_attr( $initial_state ) === 'collapsed' )
										? ' collapsed'
										: ' expanded';

	$body_atts = ( esc_attr( $initial_state ) === 'collapsed' ) ? 'style="display: none;"' : 'style="display: block;"';

	$head_hint = ( esc_attr( $initial_state ) === 'collapsed' )
									? __( 'Expand', 'inline-spoilers' )
									: __( 'Collapse', 'inline-spoilers' );

	$head .= '<div class="spoiler-head no-icon ' . $head_class . '" title="' . $head_hint . '">';
	$head .= $title;
	$head .= '</div>';

	$body .= '<div class="spoiler-body" ' . $body_atts . '>';
	$body .= balanceTags( do_shortcode( $content ), true );
	$body .= '</div>';

	$extra .= '<div class="spoiler-body">';
	$extra .= balanceTags( do_shortcode( $content ), true );
	$extra .= '</div>';

	$output .= '<div class="spoiler-wrap">';
	$output .= $head;
	$output .= $body;
	$output .= '<noscript>';
	$output .= ( esc_attr( $initial_state ) === 'collapsed' ) ? $extra : '';
	$output .= '</noscript>';
	$output .= '</div>';

	return $output;
}

add_action( 'wp_enqueue_scripts', 'is_styles_scripts' );
function is_styles_scripts() {
	global $post;
	wp_register_style( 'inline-spoilers_style', plugins_url( 'styles/inline-spoilers-default.css', __FILE__ ), null, '1.0' );
	wp_register_script( 'inline-spoilers_script', plugins_url( 'scripts/inline-spoilers-scripts.js', __FILE__ ), array( 'jquery' ), '1.0', true );

	/* RATBURGER LOCAL CODE
	   Always include spoiler CSS and JavaScript so spoilers work in comments
	   on posts which do not, themselves, include spoilers.
	if ( has_shortcode( $post->post_content, 'spoiler' ) ) {
	   END RATBURGER LOCAL CODE */
		wp_enqueue_style( 'inline-spoilers_style' );
		wp_enqueue_script( 'inline-spoilers_script' );

		$translation_array = array(
			'expand'   => __( 'Expand', 'inline-spoilers' ),
			'collapse' => __( 'Collapse', 'inline-spoilers' ),
		);

		wp_localize_script( 'inline-spoilers_script', 'title', $translation_array );
	/* RATBURGER LOCAL CODE
	}
	*/
}
