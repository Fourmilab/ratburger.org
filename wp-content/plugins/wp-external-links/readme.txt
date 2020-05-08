=== External Links - nofollow, noopener & new window ===
Contributors: WebFactory, UnderConstructionPage, googlemapswidget, wpreset
Tags: new window, new tab, external links, nofollow, noopener, ugc, sponsored, follow, dofollow, seo, noreferrer, internal links, target, links, link, internal link, external link
Requires at least: 4.2
Tested up to: 5.4
Requires PHP: 5.3
Stable tag: 2.45
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Manage internal & external links: open in new window or tab, control nofollow, ugc, sponsored & noopener. SEO friendly.

== Description ==

**Manage all external & internal links on your site**. Control icons, nofollow, noopener, ugc (User Generated Content), sponsored and if links open in new window or new tab.

WP External Links plugin was completely rebuilt in v2 and has lots of new features, like noopener, ugc and sponsored values for rel; font icons, internal links options and full WPMU support.

= Features =
* Manage external and internal links 
* Open links in new window or tab
* Add follow or nofollow (for SEO)
* Add noopener and noreferrer (for security)
* Add ugc (User Generated Content) and sponsored values to rel (<a href="https://webmasters.googleblog.com/2019/09/evolving-nofollow-new-ways-to-identify.html">Google announcement</a>)
* Add link icons (FontAwesome and Dashicons)
* Set other attributes like title and CSS classes
* Scan complete page (or just posts, comments, widgets)
* SEO friendly

= And more... =
* Network Settings (WPMU support)
* Use template tag to apply plugin settings on specific contents
* Set data-attribute to change how individual links will be treated
* Use built-in actions and filters to implement your specific needs

= Easy to use =
After activating you can set all options for external and internal links on the plugins admin page.

= On the fly =
The plugin filters the output and changes the links on the fly. The real contents (posts, pages, widget etcetera) will not be changed in the database.
When deactivating the plugin, all contents will be the same as it was before.

**Like the plugin?** [Rate it](http://wordpress.org/support/view/plugin-reviews/wp-external-links) to support the development.


== Installation ==

1. Go to **Plugins** in the Admin menu
1. Click on the button **Add new**
1. Search for **WP External Links** and click **Install Now**
1. Click on the **Activate plugin** link

= Install Older Version =

When you have an older version of WordPress (version 4.1 or less) or PHP (version 5.2.x) you can only use an older
version of this plugin.

1. [Download version 1.81](https://plugins.svn.wordpress.org/wp-external-links/tags/1.81)
1. Go to **Plugins** in admin
1. Click **New Plugin**
1. Click **Upload Plugin**
1. Choose the downloaded file and click **Install Now**
1. Click on the **Activate plugin** link


== Frequently Asked Questions ==

= I want certain posts or pages to be ignored by the plugin. How? =

Just use the option "Skip pages or posts" under the tab "Exceptions".

For a more custom approach use the action `wpel_apply_settings`:
`add_action( 'wpel_apply_settings', function () {
    global $post;
    $ignored_post_ids = array( 1, 2, 4 );

    if ( in_array( $post->ID, $ignored_post_ids ) ) {
        return false;
    }

    return true;
}, 10 );`

Using this filter you can ignore any request, like certain category, archive etcetera.

= I want specific links to be ignored by the plugin. How? =

There's an option for ignoring links containing a certain class (under tab "Exceptions").

For a more flexible check on ignoring links you could use the filter `wpel_before_apply_link`:
`add_action( 'wpel_before_apply_link', function ( $link ) {
    // ignore links with class "some-cls"
    if ( $link->has_attr_value( 'class', 'some-cls' ) ) {
        $link->set_ignore();
    }
}, 10 );`


= How to create a redirect for external links? (f.e. affiliate links) =

Create redirect by using the `wpel_link` action. Add some code to functions.php of your theme, like:

`add_action( 'wpel_link', function ( $link ) {
    // check if link is an external links
    if ( $link->is_external() ) {
        // get current url
        $url = $link->get_attr( 'href' );

        // set redirect url
        $redirect_url = '//somedom.com?url='. urlencode( $url );
        $link->set_attr( 'href', $redirect_url );
    }
}, 10, 1 );`

= How to open external links in a new popup window? =

By adding this JavaScript code to your site:

`jQuery(function ($) {
    $('a[data-wpel-link="external"]').click(function (e) {
        // open link in popup window
        window.open($(this).attr('href'), '_blank', 'width=800, height=600');

        // stop default and other behaviour
        e.preventDefault();
        e.stopImmediatePropagation();
    });
});`

See more information on the [window.open() method](http://www.w3schools.com/jsref/met_win_open.asp).

= How to add an confirm (or alert) when opening external links? =

Add this JavaScript code to your site:

`jQuery(function ($) {
    $('a[data-wpel-link="external"]').click(function (e) {
        if (!confirm('Are you sure you want to open this link?')) {
            // cancelled
            e.preventDefault();
            e.stopImmediatePropagation();
        }
    });
});`

= How to open PDF files in a new window? =

Use some JavaScript code for opening PDF files in a new window:

`jQuery(function ($) {
    $('a[href$=".pdf"]').prop('target', '_blank');
});`

= How to set another icon for secure sites (using https)? =

Use some CSS style to change the icon for secure sites using https:

`a[href^="https"] .wpel-icon:before {
  content: "\f023" !important;
}`

The code `\f023` refers to a dashicon or font awesome icon.

= I am a plugin developer and my plugin conflicts with WPEL. How can I solve the problem? =

If your plugin contains links it might be filtered by the WPEL plugin as well, causing a conflict.
Here are some suggestions on solving the problem:

1. Add `data-wpel-link="ignore"` to links that need to be ignored by WPEL plugin
1. Use `wpel_before_apply_link`-action to ignore your links (f.e. containing certain class or data-attribute)
1. Use `wpel_apply_settings`-filter to ignore complete post, pages, categories etc


== Screenshots ==

1. Link Icons
2. Admin Settings Page
3. WPMU Network Settings Page


== Documentation ==

After activating you can set all options for external and internal links.

= Data attribute "data-wpel-link" =

Links being processed by this plugin will also contain the data-attribute `data-wpel-link`.
The plugin could set the value to `external`, `internal` or `exclude`, meaning how the
link was processed.

You can also set the data-attribute yourself. This way you can force how the plugin will process
certain links.

When you add the value `ignore`, the link will be completely ignored by the plugin:

`<a href="http://somedomain.com" data-wpel-link="ignore">Go to somedomain</a>`


= Action "wpel_link" =

Use this action to change the link object after all plugin settings have been applied.

`add_action( 'wpel_link', ( $link_object ) {
    if ( $link_object->is_external() ) {
        // get current url
        $url = $link_object->getAttribute( 'href' );

        // set redirect url
        $redirect_url = '//somedom.com?url='. urlencode( $url );
        $link_object->setAttribute( 'href', $redirect_url );
    }
}, 10, 1 );`

The link object is an instance of `WPEL_Link` class.

= Action hook "wpel_before_apply_link" =

Use this action to change the link object before the plugin settings will be applied on the link.
You can use this filter f.e. to ignore individual links from being processed. Or change dynamically how
they will be treated by this plugin.

`add_action( 'wpel_before_apply_link', function ( $link ) {
    // ignore links with class "some-cls"
    if ( $link->has_attr_value( 'class', 'some-cls' ) ) {
        $link->set_ignore();
    }

    // mark and treat links with class "ext-cls" as external link
    if ( $link->has_attr_value( 'class', 'ext-cls' ) ) {
        $link->set_external();
    }
}, 10 );`

= Filter hook "wpel_apply_settings" =

When filter returns false the plugin settings will not be applied. Can be used when f.e. certain posts or pages should be ignored by this plugin.

`add_filter( 'wpel_apply_settings', '__return_false' );`


See [FAQ](https://wordpress.org/plugins/wp-external-links/faq/) for more info.


== Changelog ==

= 2.45 =
 * 2020-05-08
 * fixed compatibility issue with Site Kit by Google

= 2.43 =
 * 2019-12-27
 * improved link detection regex
 
= 2.42 =
 * 2019-12-18
 * fixed various undefined variable notices
 * fixed an issue when there’s no href attribute set in an A element
 
= 2.40 =
 * 2019-11-20
 * no longer loads CSS and FontAwesome globally on admin pages
 * added support for "ugc" (User Generated Content) and "sponsored" values in rel

= 2.35 =
 * 2019-09-12
 * FontAwesome updated to 4.7 with 41 new icons
 * bug fix: ship anchor URLs (#anchor)
 * bug fix: don't match domain if the name is contained in the URL
 
= 2.32 =
 * 2019-07-09
 * security fixes

= 2.3 =
 * 2019-06-14
 * bug fixes
 * 40,000 installations hit on 2018-03-13

= 2.2.0 =
 * Added option ignore links by classes
 * Added option skip pages and posts by id
 * Fixed bug checking internal links without protocol (starting //)

= 2.1.3 =
 * Commit error

= 2.1.2 =
 * Fixed bug checking internal links with https
 * Fixed bug with REST API
 * Fixed conflict Widget CSS Classes plugin (partially fixed)

= 2.1.1 =
* Fixed updating old plugin values
* Fixed links containing rel="external" will also be treated as external
* Fixed prevent caching old styles and scripts

= 2.1.0 =
* Added tab with options for excluded links
* Added `wpel-no-icon` class to set on links
* Added action `wpel_before_apply_link`
* Added option ignore mailto links
* Fixed ignore links of admin bar
* Fixed regexp for ignoring tags
* Fixed text domain to text slug
* Made filters `wpel_before_filter` and `wpel_after_filter` "private"
* Removed DOMElement dependency
* Removed rel="external" option for internal links
* Removed filter `wpel_regexp_link` (this should not be changed)

= 2.0.4 =
* Fixed DOMElement breaks of text containing `&`

= 2.0.3 =
* Fixed bug ignoring links in <header> section

= 2.0.2 =
* Fixed bug parsing empty attributess
* Changed mailto links wil be completely ignored

= 2.0.1 =
* Fixed mark mailto links as excluded
* Fixed include / exclude url's

= 2.0.0 =
* REQUIREMENTS: PHP 5.3+
* Complete rebuilt
* Added `noopener` and `noreferrer`
* Added font icons (font awesome and dashicons)
* Added options for internal links
* Added Network settings (WPMU support)
* Contribution: David Page solving bug `home_url()`

= 1.81 =
* Security update (reported by Vulnerability Lab)
* Some small changes

= 1.80 =
* Added filter hook wpel_external_link_attrs to change attributes before creating the link
* Added filter hook wpel_ignored_external_links
* Removed phpQuery option
* Moved ignore selectors option

= 1.70 =
* Added option to ignore all subdomains

= 1.62 =
* Fixed php error when using phpQuery option

= 1.61 =
* Fixed deprecated split() function
* Fixed deprecated $wp_version

= 1.60 =
* Added option to replace "follow" values of external links with "nofollow"
* Updated FAQ with custom solutions

= 1.56 =
* Fixed bug jQuery as dependency for js scripts
* Fixed bug "no-icon class in same window" working with javascript
* Fixed bug setting defaults on installation

= 1.55 =
* Fixed bug JS error: Uncaught TypeError: undefined is not a function
* Fixed bug PHP error for links without href attribute ("undefined index href")
* Replaced deprecated jQuery .live() to .on()  (contribution by Alonbilu)

= 1.54 =
* Fixed bug opening links containing html tags (like <b>)

= 1.53 =
* Fixed bug also opening ignored URL's on other tab/window when using javascript
* Changed javascript open method (data-attribute)

= 1.52  =
* Added filter hook wpel_internal_link
* Fixed use_js option bug
* Fixed bug loading non-existing stylesheet
* Minified javascripts

= 1.51 =
* Fixed also check url's starting with //
* Fixed wpel_external_link also applied on ignored links

= 1.50 =
* Removed stylesheet file to save extra request
* Added option for loading js file in wp_footer
* Fixed bug with data-* attributes
* Fixed bug url's with hash at the end
* Fixed PHP errors

= 1.41 =
* Fixed Bug: wpmel_external_link filter hook was not working correctly

= 1.40 =
* Added action hook wpel_ready
* Added filter hook wpel_external_link
* Added output flush on wp_footer
* Fixed Bug: spaces before url in href-attribute not recognized as external link
* Fixed Bug: external links not processed (regexpr tag conflict starting with an a, like <aside> or <article>)
* Cosmetic changes: added "Admin Settings", replaced help icon, restyled tooltip texts, removed "About this plugin" box

= 1.31 =
* Fixed passing arguments by reference using & (deprecated for PHP 5.4+)
* Fixed options save failure by adding a non-ajax submit fallback

= 1.30 =
* Re-arranged options in metaboxes
* Added option for no icons on images

= 1.21 =
* Fixed phpQuery bugs (class already exists and loading stylesheet)
* Solved php notices

= 1.20 =
* Added option to ignore certain links or domains
* Solved tweet button problem by adding link to new ignore option
* Made JavaScript method consistent to not using JS
* Solved PHP warnings
* Solved bug adding own class
* Changed bloginfo "url" to "wpurl"

= 1.10 =
* Resolved old parsing method (same as version 0.35)
* Option to use phpQuery for parsing (for those who didn't experience problems with version 1.03)

= 1.03 =
* Workaround for echo DOCTYPE bug (caused by attributes in the head-tag)

= 1.02 =
* Solved the not working activation hook

= 1.01 =
* Solved bug after live testing

= 1.00 =
* Added option for setting title-attribute
* Added option for excluding filtering certain external links
* Added Admin help tooltips using jQuery Tipsy Plugin
* Reorganized files and refactored code to PHP5 (no support for PHP4)
* Added WP built-in meta box functionality (using the `WP_Meta_Box_Page` Class)
* Reorganized saving options and added Ajax save method (using the `WP_Option_Forms` Class)
* Removed Regexp and using phpQuery
* Choose menu position for this plugin (see "Screen Options")
* Removed possibility to convert all `<a>` tags to xhtml clean code (so only external links will be converted)
* Removed "Solve problem" options

= 0.35 =
* Widget Logic options bug

= 0.34 =
* Added option only converting external `<a>` tags to XHTML valid code
* Changed script attribute `language` to `type`
* Added support for widget_content filter of the Logic Widget plugin

= 0.33 =
* Added option to fix js problem
* Fixed PHP / WP notices

= 0.32 =
* For jQuery uses live() function so also opens dynamiclly created links in given target
* Fixed bug of changing `<abbr>` tag
* Small cosmetical adjustments

= 0.31 =
* Small cosmetical adjustments

= 0.30 =
* Improved Admin Options, f.e. target option looks more like the Blogroll target option
* Added option for choosing which content should be filtered

= 0.21 =
* Solved bug removing icon stylesheet

= 0.20 =
* Put icon styles in external stylesheet
* Can use "ext-icon-..." to show a specific icon on a link
* Added option to set your own No-Icon class
* Made "Class" optional, so it's not used for showing icons anymore
* Added 3 more icons

= 0.12 =
* Options are organized more logical
* Added some more icons

= 0.11 =
* JavaScript uses window.open() (tested in FireFox Opera, Safari, Chrome and IE6+)
* Also possible to open all external links in the same new window
* Some layout changes on the Admin Options Page

= 0.10 =
* Features: opening in a new window, set link icon, set "external", set "nofollow", set css-class
* Replaces external links by clean XHTML <a> tags
* Internalization implemented (no language files yet)
