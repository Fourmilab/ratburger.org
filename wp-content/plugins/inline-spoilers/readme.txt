=== Inline Spoilers ===
Contributors: sergeykuzmich, gadswan
Donate link: https://www.buymeacoffee.com/sergeykuzmich
Tags: shortcode, spoiler
Requires at least: 4.9
Tested up to: 5.3
Requires PHP: 5.6
Stable tag: 1.5.0
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

The plugin allows to create content spoilers with simple shortcode.

== Description ==

The plugin allows to create content spoilers with simple shortcode.

`
[spoiler title="Expand Me"]Spoiler content[/spoiler]
`

== Installation ==

1. Insall via WordPress Dashboard or upload `inline-spoiler.zip`;
2. Activate the plugin through the 'Plugins' menu in WordPress;
3. Use shortcode in your content;

== Frequently Asked Questions ==

= How can I customize design of the spoiler? =
Just override classes defined in `styles/inline-spoilers-styles.css` with your theme styles.

== Screenshots ==

1. Guttenberg block
2. Spoiler shortcode `[spoiler][/spoiler]`
3. Collapsed spoiler
4. Expanded spoiler

== Changelog ==

= 1.5.0 =
* Make flag for non-optimized script & style loading to prevent issues on some child themes (see https://wordpress.org/support/topic/spoiler-doesnt-show-up/ for more information)

`
wp-config.php:

...
/** Set FALSE to disable 'Inline Spoliers' plugin script & style optimization
define( 'IS_OPTIMIZE_LOADER', false ); 

/* That's all, stop editing! Happy publishing. */
...
`

= 1.4.1 =
* Fix https://wordpress.org/support/topic/fatal-error-when-activating-the-plugin-10/

= 1.4.0 =
* Introduce Guttenberg block to create spoilers (special thanks to [Sergey Zaytsev](https://www.linkedin.com/in/sergey-zaytsev-b50857b0/) for doing most of things)

= 1.3.8 =
* Allow empty spoiler title by default

= 1.3.7 =
* Refactor deployment strategy to support multiply revisions for the same plugin version

= 1.3.3 =
* Fix https://wordpress.org/support/topic/notice-undefined-variable-extra-in-wp-content-plugins-inline-spoilers-inlin/

= 1.3.2 =
* Compatibility up to Wordpress 4.9.8

= 1.3.1 =
* Always show spoiler contents while javascript is disabled

= 1.2.8 =
* Setup automated deployment with TravisCI

= 1.2.5 =
* Balance content html tags

= 1.2.4 =
* Add WP_DEBUG mode
* Fix incorrect paragraph tags inside the spoiler

= 1.2.3 =
* JavaScript bug fix

= 1.2.2 =
* Update spoiler default behaviour

= 1.1.2 =
* Update Russian translation
* Add attribute 'initial_state' to define default state of a spoiler `initial_state=(expanded|collapsed)`. Default state is 'collapsed'
* Security updates

= 1.0.2 =
* Update Russian translation

= 1.0.1 =
* Release the plugin 
