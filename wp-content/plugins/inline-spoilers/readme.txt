=== Inline Spoilers ===
Contributors: sergeykuzmich
Donate link: https://www.buymeacoffee.com/sergeykuzmich
Tags: shortcode, spoiler
Requires at least: 3.9.1
Tested up to: 5.1.0
Requires PHP: 5.6
Stable tag: 1.3.8
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
To change layout of a spoiler, please, edit `styles/inline-spoilers-styles.css` file.

== Screenshots ==

1. Spoiler shortcode `[spoiler][/spoiler]`
2. Collapsed spoiler
3. Expanded spoiler

== Changelog ==

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
