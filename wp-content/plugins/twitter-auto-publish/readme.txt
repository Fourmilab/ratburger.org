=== Twitter Auto Publish ===
Contributors: f1logic
Donate link: http://xyzscripts.com/donate/
Tags:  twitter, twitter auto publish, publish post to twitter, add link to twitter, twitter publishing, post to twitter, social media auto publish, social media publishing, social network auto publish, social media, social network
Requires at least: 3.0
Tested up to: 4.9.1
Stable tag: 1.3.4
License: GPLv2 or later

Publish posts automatically to Twitter.

== Description ==

A quick look into Twitter Auto Publish :

	★ Publish simple text message to Twitter
	★ Publish message to Twitter with image
	★ Filter items  to be published based on categories
	★ Filter items to be published based on custom post types
	★ Enable or disable wordpress page publishing
	★ Customizable  message formats for Twitter


= Twitter Auto Publish Features in Detail =

The Twitter Auto Publish lets you publish posts automatically from your blog to Twitter. You can publish your posts to Twitter as simple text message or as text message with image. The plugin supports filtering posts based on  custom post-types as well as categories.

The prominent features of  the Twitter Auto Publish plugin are highlighted below.

= Supported Mechanisms =

The various mechanisms of posting to Twitter are listed below. 

    Simple text message
    Text message with image

= Filter Settings =

The plugin offers multiple kinds of filters for contents to be published automatically.

    Enable or disable publishing of wordpress pages
    Filter posts to be published based on categories
    Filtering based on custom post types

= Message Format Settings =

The supported post elements which can be published are given below.

    Post title 
    Post description
    Post excerpt
    Permalink
    Blog title
    User nicename
    Post ID
    Post publish date


= About =

Twitter Auto Publish is developed and maintained by [XYZScripts](http://xyzscripts.com/ "xyzscripts.com"). For any support, you may [contact us](http://xyzscripts.com/support/ "XYZScripts Support").

★ [Twitter Auto Publish User Guide](http://help.xyzscripts.com/docs/twitter-auto-publish/ "Twitter Auto Publish User Guide")
★ [Twitter Auto Publish FAQ](http://help.xyzscripts.com/docs/twitter-auto-publish/faq/ "Twitter Auto Publish FAQ")

== Installation ==

★ [Twitter Auto Publish User Guide](http://help.xyzscripts.com/docs/twitter-auto-publish/installation/ "Twitter Auto Publish User Guide")
★ [Twitter Auto Publish FAQ](http://help.xyzscripts.com/docs/twitter-auto-publish/faq/ "Twitter Auto Publish FAQ")

1. Extract `twitter-auto-publish.zip` to your `/wp-content/plugins/` directory.
2. In the admin panel under plugins activate Twitter Auto Publish.
3. You can configure the settings from Twitter Auto Publish menu. (Make sure to Authorize Twitter application after saving the settings.)
4. Once these are done, posts should get automatically published based on your filter settings.

If you need any further help, you may contact our [support desk](http://xyzscripts.com/support/ "XYZScripts Support").

== Frequently Asked Questions ==

★ [Twitter Auto Publish User Guide](http://help.xyzscripts.com/docs/twitter-auto-publish/user-guide/ "Twitter Auto Publish User Guide")
★ [Twitter Auto Publish FAQ](http://help.xyzscripts.com/docs/twitter-auto-publish/faq/ "Twitter Auto Publish FAQ")

= 1. The Twitter Auto Publish is not working properly. =

Please check the wordpress version you are using. Make sure it meets the minimum version recommended by us. Make sure all files of the `twitter auto publish` plugin are uploaded to the folder `wp-content/plugins/`


= 2. How do I restrict auto publish to certain categories ? =

Yes, you can specify the categories which need to be auto published from settings page.


= 3. Why do I have to create applications in Twitter ? =

When you create your own applications, it ensures that the posts to Twitter are not shared with any message like "shared via xxx"


= 4. Which  all data fields can I send to social networks ? =

You may use post title, content, excerpt, permalink, site title and user nicename for auto publishing.


= 5. Why do I see SSL related errors in logs ? =

SSL peer verification may not be functioning in your server. Please turn off SSL peer verification in settings of plugin and try again.


= More questions ? =

[Drop a mail](http://xyzscripts.com/support/ "XYZScripts Support") and we shall get back to you with the answers.


== Screenshots ==

1. This is the Twitter configuration section.
2. Publishing options while creating a post.

== Changelog ==

= Twitter Auto Publish 1.3.4 =
* Added USER_DISPLAY_NAME in message formats
* Twitter character length limit updated
* Minor security issues fixed
* Twitter api updated

= Twitter Auto Publish 1.3.3 =
* Added POST_ID and POST_PUBLISH_DATE in message formats

= Twitter Auto Publish 1.3.2 =
* utf-8 decoding issue fixed
* Visual composer compatiblity issue fixed
* Minor bugs fixed
* Nonce added
* Prevented direct access to plugin files
* Data validation updated

= Twitter Auto Publish 1.3.1 =
* Fixed custom post types autopublish issue	
* Fixed duplicate autopublish issue

= Twitter Auto Publish 1.3 =
* Added option to enable/disable utf-8 decoding before publishing	
* Removed unwanted configuration related to 'future_to_publish' hook
* Postid added in autopublish logs
* Updated auto publish mechanism using transition_post_status hook

= Twitter Auto Publish 1.2.2 =
* Added option to enable/disable "future_to_publish" hook for handling auto publish of scheduled posts	
* Added options to enable/disable "the_content", "the_excerpt", "the_title" filters on content to be auto-published
* Inline edit of posts will work according to the value set for "Default selection of auto publish while editing posts/pages" 
* Latest five auto publish logs are maintained

= Twitter Auto Publish 1.2.1 =
* Fixed auto publish related bug in post edit
* Fixed message format bug in auto publish
* Bug fix for duplicate publishing of scheduled posts
* Fixed category display issue

= Twitter Auto Publish 1.2 =
* Option to configure auto publish settings while editing posts/pages
* General setting to enable/disable post publishing
* Added auto publish for scheduled post
* Fixed issue related to \" in auto publish

= Twitter Auto Publish 1.1.1 =
* Added compatibility with wordpress 3.9.1
* Compatibility with bitly plugin

= Twitter Auto Publish 1.1 =
* View logs for last published post
* Option to enable/disable SSL peer verification

= Twitter Auto Publish 1.0.2 =
* Bug fixed for &amp;nbsp; in post
* Twitter api updated to https

= Twitter Auto Publish 1.0.1 =
* Default image fetch logic for auto publish updated.

= Twitter Auto Publish 1.0 =
* First official launch.

== Upgrade Notice ==

= Twitter Auto Publish 1.0.1 =
If you had issues  with default image used for auto publishing, you may apply this upgrade.

= Twitter Auto Publish 1.0 =
First official launch.

== More Information ==

★ [Twitter Auto Publish User Guide](http://help.xyzscripts.com/docs/twitter-auto-publish/ "Twitter Auto Publish User Guide")
★ [Twitter Auto Publish FAQ](http://help.xyzscripts.com/docs/twitter-auto-publish/faq/ "Twitter Auto Publish FAQ")

= Troubleshooting =

Please read the FAQ first if you are having problems.

= Requirements =

    WordPress 3.0+
    PHP 5+ 

= Feedback =

We would like to receive your feedback and suggestions about Twitter Auto Publish plugin. You may submit them at our [support desk](http://xyzscripts.com/support/ "XYZScripts Support").
