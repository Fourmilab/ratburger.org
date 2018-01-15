=== Quote Comments ===
Contributors: metodiew, Joen
Donate link: https://metodiew.com/
Tags: quote, comments, javascript, textile, wysiwyg
Requires at least: 2.5.0
Tested up to: 4.7
Stable tag: 2.2
Stable tag: trunk
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html


== Description ==

<strong>An important note: </strong>the plugin was adopted and it will be updated a few times in the next couple of weeks. I'll need your help with the testing, reporting potential issues, suggesting new improvements, etc :)

This plugin adds a tiny link that says "Quote" on each comment on your posts. Click it, and the contents of the comment is copied to the comment area, wrapped in blockquote tags.

Note: Doesn't work with Jetpack comments.

== Installation ==

1. Upload the whole quote-comments folder to your wp-content/plugins/ folder
2. Go to your Wordpress admin -> Plugins and activate the Quote Comments plugin.
3. That should be it.

Note: If it doesn't work for you, go to the Quote Comments options page, and try using `get_comment_text` as plugin hook.

== Screenshots ==

1. The default look: a little (Quote) link next to the comment time.
2. An alternate look, where the icon has been styled to look like a Quote icon. Click the tiny quote icon to quote. 
3. Quote Comments options page. 

== Frequently Asked Questions ==

= Do I have to do anything else than simply activate the plugin? =

Nope. That's all.

= It doesn't work for me? =

Quote-Comments requires that JavaScript is enabled.

If it still doesn't work, try going to the Quote Comments options page and switching plugin hook to `get_comment_text`.

If even then it doesn't work, or it gives you a JavaScript alertbox, then there's most likely an issue with your Wordpress theme. Maybe look for theme that is compatible with newer versions of Wordpress. A theme that uses threaded comments, for instance, is very likely to work. 

= How To Replace The "Quote" Text With An Icon =

Add this CSS to your stylesheet:

`a.comment_quote_link {
	margin: 0;
	height: 0;
	display: block;
	overflow: hidden;
	width: 12px;
	height: 12px;
	text-indent: -9999px;
	background: url(images/icon_quote_comment.gif) no-repeat right top;
}`

= How To Move The "Quote" Text To The Top Right Corner Of The Comment =

In addition to the above CSS, add the following to your stylesheet:

`.commentlist li {
	position: relative;
}
a.comment_quote_link {
	position: absolute;
	right: 20px;
	top: 20px;
	z-index: 1;
}`

== Changelog ==

= 2.2 =

* Release date - January 17, 2017
* The plugin was adopted by me, Stanko Metodiev. I've talked with Joen and he agreed to transfer the plugin. The fun starts here.
* Fix a few deprecated notices and PHP errors.
* Reverse the order of the changelog.

= 2.1.7 =

* Fixed problem with smileys and paragraphs disappearing on some themes. Credit to Gabi for fixing this! Thanks Gabi!


= 2.1.6 =

* Fixed problem with multiple linebreaks being collapsed

= 2.1.4 =

* Turns out quoting didn't actually work. It should work now.

= 2.1.3 =

* Made the plugin compatible with WordPress 3.0.5.
* The option "get_comment_time" on the options page will most likely be phased out in the future, please don't rely on it (it's buggy anyway).

= 2.1.2 =

* Escaped names to prevent JavaScript injection. Thanks to Chris Travers from Metatron Technology Consulting for reporting this issue.

= 2.1.1 =

* Fixed a bug where the reply link wouldn't work in Google Chrome.

= 2.1 =

* Added a simple "reply" button, for people that don't like threaded comments.
* I think I also fixed a semantic bug.

= 2.0 =

* Dropped 1.9.8 because it had new bugs.
* Fixed a bug where "<author>: " wouldn't be quoted.
* Added option to choose whether "<author>: " should be prepended quoted comments.
* Rewrote options page.
* Updated english and danish and swedish po files. German and dutch still work, they just haven't translated the options page.

= 1.9.7 =

* QC now inserts fewer linebreaks when quoting and using TinyMCE comments.

= 1.9.5 =

* It may now be simpler to quote using MCEComments, as an extra linebreak is inserted so it's easy to break out of the "blockquote" tag.

= 1.9.3 =

* Now the commentform jumps to the comment thread whose quote button you clicked. Tip: David Abrahams.

= 1.9.2 =

* Added checks to see whether the plugin is loaded in the admin or the blog, and only adds quote stuff when on the blog.

= 1.9.1 =

* Added an options page with the ability to pick which hook to use to insert the quote link. Unfortunately I had to do this due to a recent change where I switched hooks to a superior place (a hook that's apparently not there in all themes). The net result is that you should be able to pick one of the two hooks, to get the quote link to show up.
* Added an option to customize the text that shows up in the commentlink. Kinda makes the languages folder a bit obsolete. I'll deal with that later.
* Bugfixes.

= 1.9 =

* Moved the "quote" button HTML to be right next to the date. I think this used to break validation, perhaps this has been fixed in a recent Wordpress version. Let me know if it's now broken in older versions.

= 1.8 =

* Translation release! Includes Danish and German (thanks to Daniel H�ly)
* Moved script and CSS includes to use "enqueue_scripts".

= 1.7.6 =

* Added localization features. Easier to localize.
* Fixed so that the quote icon doesn't show up if comments are closed, or user registration is required and the user isn't logged in.

= 1.7.5 = 

* Removed the Textile support. This plugin still works with Textile, it just won't use bq. to create quotes any more. This borked when selecting several paragraphs to quote.

= 1.7 =

* Made the plugin compatible with MCEComments. As it turns out, MCEComments is (at the time of this writing) not compatible with Wordpress 2.7 threaded comments. So if you're having trouble with that, it's not "Quote Comments" fault. MCEComments is working on the issue, though.


= 1.6.3 =

* Fixed a problem where nested blockquotes weren't removed when quoting.

= 1.6.2 =

* Fixed problems with the comments feed breaking. Again.

= 1.6.1 = 

* Did further tweaks to prevent validation from breaking. Previously, if you had written something in the comment field and not made a linebreak, and then proceeded to quote something, the quote tag would be inserted right after the text you had already written, causing bad formatting. Now when you quote something, two linebreaks are always inserted before any quote code. 
* Uncommented the "float left" CSS because it borked most layouts. I encourage you to write your own CSS.

= 1.6 =

* Should finally nuke the problem that plagued validation. Thanks Ute.

= 1.5 =

* Had to use "get_comment_text" as a hook, because the reply link is not available on the deepest level threading. On the plus side, this should mean the plugin is now 2.6 compatible again.
* Added a minimalistic stylesheet which floats the quote link left, placing it next to the reply link.

= 1.4.1 =

* Added link back to commenters name in a link anchor.

= 1.4 =

* Fixed issue with the plugin only being compatible with Wordpress 2.7. Should now be more backwards compatible, and more compatible in general. 
* Added back "<Author>: " in the quote. Now inside the blockquotes.
* Added a pipe to separate the quote text from the comment.
* Good news and bad news. The quote icon cannot be outputted near the comment time. If it is, then the HTML won't validate. Right now I have moved the quote link next to the reply button in Wordpress 2.7, and above the comment in older than 2.7. The comment is still CSS stylable using span.quote {}.

= 1.3 =

* Fixed issue with the quote link being unstylable
* Should fix problems with the comments feed breaking

= 1.2 =

* Moved all JS to a separate file. Should improve loadtimes.
* Added "cite" attribute to blockquote.
* Removed "<Author> said:" text because it annoyed me. To enable it again, edit "quote-comments.js" and remove the line that says "author = null;"
* Used "get_comment_time" as hook, which moves the quote text to a better place on each comment.

= 1.0 =

* First release.

== Upgrade Notice ==

= 2.2 =

The plugin wasn't maintained for some time. In this version I, the new author, I'm fixing a few PHP notices and errors, but I'm planning to do a few updates in the next weeks and months. Let me know if you have some ideas, suggestions or a feedback you'd like to share!
