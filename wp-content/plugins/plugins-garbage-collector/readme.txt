=== Plugins Garbage Collector ===
Contributors: shinephp
Donate link: http://www.shinephp.com/donate/
Tags: garbage, collector, database, clear, unused tables, cleaner
Requires at least: 4.0
Tested up to: 4.6
Stable tag: 0.10.3

It scans your WordPress database and shows what various things old plugins (which were deactivated or uninstalled) left in it. Cleanup is available.

== Description ==

Plugins Garbage Collector scans your WordPress database and shows the tables beyond of core WordPress installation. Some WordPress plugins create and use its own database tables. Those tables are left in your database after plugin deactivation and deletion often. If your blog is more than 1 day old you have some plugins garbage in your WordPress database probably. With the help of this plugin you can check your database and discover if it is clean or not.
Extra columns added to the core WordPress tables could be shown also.
To read more about 'Plugins Garbage Collector' visit this link at <a href="http://www.shinephp.com/plugins-garbage-collector-wordpress-plugin/" rel="nofollow">shinephp.com</a>


== Installation ==

Installation procedure:

Attention! Starting from version 0.9.2 plugin works with WordPress 3.0 and higher only. For earlier WordPress versions use plugin version 0.9.1 from http://downloads.wordpress.org/plugin/plugins-garbage-collector.0.9.1.zip
1. Deactivate plugin if you have the previous version installed.
2. Extract "plugins-garbage-collector.x.x.x.zip" archive content to the "/wp-content/plugins/plugins-garbage-collector" directory.
3. Activate "Plugins Garbage Collector" plugin via 'Plugins' menu in WordPress admin menu. 
4. Go to the "Tools"-"Plugins Garbage Collector" menu item and scan your WordPress database if it has some forgotten tables from old plugins.

== Frequently Asked Questions ==
Comming soon. Just ask it. I will search the answer.


== Screenshots ==
1. screenshot-1.png Plugins Garbage Collector scan action results.


== Changelog ==

= 0.10.3 [01.09.2016] =
* Fix: 1st plugin in the alphabetically ordered plugins list  was always skipped at the scan process.

= 0.10.2 [08.01.2016] =
* Fix: missed text was added to a translation
* Japanese translation was added.


= 0.10.1 [16.11.2015] =
* Fix: Wrong "Scan" link under plugin row at the "Plugins" page was updated.

= 0.10 [29.10.2015] =
* use WordPress AJAX API and JSON for data exchange
* move some logic to the client and split the whole task for the smaller parts to exclude server time limit PHP error for the sites with large quant of plugins installed.
* use jQuery UI progress bar
* restructure code
* escape translated text

= 0.9.15 =
* 01.12.2014
* Fix for fatal error during activation with fresh install of WordPress 4.0 (missed WPLANG constant caused plugin to stop execution).
  Unfortunately it worked fine under WP 4.0 with wp-config.php from previous version with WPLANG defined.

= 0.9.14 =
* 30.06.2014
* Polish translation was updated. Thanks to Grzegorz Janoszka.

= 0.9.12 =
* 2.12.2012
* Dutch - Nederlands translation was updated. Thanks to Harald Labout.
* load_plugin_textdomain() call moved to the 'plugins_loaded' hook for higher compatibility with translation plugins.

= 0.9.11 =
* 31.10.2012
* In case other plugin had some uppercase letters at the DB table name PGC failed to define that plugin as table owner. It is fixed now.

= 0.9.10 =
* 22.08.2012
* Minor code cleanup and unused piece of code removing

= 0.9.9 =
* 16.07.2012
* Polish translation is added, thanks to Esej Konrad Łącki.

= 0.9.8 =
* 15.04.2012
* Lithuanian translation is added, thanks to Vincent G.

= 0.9.7 =
* 28.07.2011
* AJAX empty response for WordPress multi-site with subdomains is fixed - thanks to alx359. 
* AJAX error processing is enhanced slightly.
* Minor enhancements to plugin page CSS are made.

= 0.9.5 =
* 16.01.2011
* Chinese Simple translation is added.
* Minor change to pgc-ajax.js errors processing is made.

= 0.9.4 =
* 28.12.2010
* Italian translation is updated.
* Latin translation is added.
* Thanks to [Alessandro Mariani](http://technodin.org) for these translations update.

= 0.9.3 =
* 14.10.2010
* Bug fix: database table names had been processed in the lowercase format and tables could not be deleted if have uppercase letters in it.  It is fixed now. If database table has 'dbTableWithSomeData' name, you will see it as 'dbTableWithSomeData', not as 'dbtablewithsomedata'. Thanks to [Deirdre](http://unlimitedwhispers.com) who found this bug.

= 0.9.2 =
* 27.09.2010
* Technical update for WordPress 3.0 full compatibility. Staff deprecated since WordPress v.3.0 is excluded.
* Italian translation update. Thanks to [Alessandro Mariani](http://technodin.org).

= 0.9.1 =
* 07.07.2010
* options form layout problem is fixed. That was wrong pgc-admin.css file version issue.

= 0.9 =
* 01.07.2010
* You can mark tables which you do not wish to see as scan results as hidden.
* You can search extra columns in the core WordPress tables, which could be added by plugins.
* Czech translation is added

= 0.6 = 
* 25.05.2010
* Dutch translation is updated.

= 0.5.2 =
* 18.05.2010
* Italian translation is updated

= 0.5.1 =
* 14.05.2010
* German translation is updated

= 0.5 =
* 12.05.2010
* Italian translation is added
* Lost translation domain inserted into a few places in the source code. Translators are welcome to update their work :).

= 0.4 =
* 03.05.2010
* "Delete Tables" button is added
* General code cleanup

= 0.3 =
* 02.05.2010
* German, Japanese translations are added
* Another text domain 'pgc' missing bug is fixed

= 0.2 =
* 01.05.2010
* French, Indonesian, Spanish translations are added
* Text domain 'pgc' missing bug is fixed


= 0.1 =
* 29.04.2010
* 1st pre-release.

== Additional Documentation ==

You can find more information about "Plugins Garbage Collector" plugin at this page
http://www.shinephp.com/plugins-garbage-collector-wordpress-plugin/

I am ready to answer on your questions about this plugin usage. Use ShinePHP forum at
http://shinephp.com/community/forum/plugins-garbage-collector/
or plugin page comments and site contact form for it please.
