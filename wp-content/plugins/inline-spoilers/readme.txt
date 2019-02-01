=== Inline Spoilers ===
Contributors: sergeykuzmich
Tags: shortcode, spoiler
Requires at least: 3.9.1
Tested up to: 5.0.3
Requires PHP at least: 5.6
Stable tag: 1.3.7
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

The plugin allows to create content spoilers with simple shortcode.

== Description ==

`Example: [spoiler title="Expand Me"]Spoiler content[/spoiler]`

== Installation ==

1. Insall via WordPress Dashboard or upload `inline-spoiler.zip`;
2. Activate the plugin through the 'Plugins' menu in WordPress;
3. Use shortcode in your content;
(*Example:* `[spoiler title="Expand Me"]Spoiler content[/spoiler]`)

== Frequently Asked Questions ==

= How can I customize design of the spoiler? =
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

= 1.3.7 =
* Refactor deployment strategy to support multiply revisions for the same plugin version
