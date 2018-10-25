=== Inline Spoilers ===
Contributors: sergeykuzmich
Tags: shortcode, spoiler
Requires at least: 3.9.1
Tested up to: 4.9.8
Requires PHP at least: 5.5
Stable tag: 1.3.3
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

The plugin allows to create content spoilers with simple shortcode.

== Description ==

`Example: [spoiler title="Expand Me"]Spoiler content[/spoiler]`

== Installation ==

1. Upload folder `inline-spoiler` to the `/wp-content/plugins/` directory;
1. Activate the plugin through the 'Plugins' menu in WordPress;
1. Place shortcode (*Example:* `[spoiler title="Expand Me"]Spoiler content[/spoiler]`) in your content;

== Frequently Asked Questions ==

= How do I can customize design of the spoiler? =
To change layout of a spoiler, please, edit `styles/inline-spoilers-styles.css` file.

= How to remove text from the title? =
To remove default title you can use
`
[spoiler title="&nbsp;"]
...
[/spoiler]
`

== Screenshots ==

1. Spoiler shortcode `[spoiler][/spoiler]`
2. Collapsed spoiler
3. Expanded spoiler

== Changelog ==

= 1.3.3 =
* Fix https://wordpress.org/support/topic/notice-undefined-variable-extra-in-wp-content-plugins-inline-spoilers-inlin/
