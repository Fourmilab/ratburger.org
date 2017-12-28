<?php
if( !defined('ABSPATH') ){ exit();}
function wp_twap_admin_notice()
{
	add_thickbox();
	$sharelink_text_array_tw = array
						(
						"I Use Twitter Auto Publish  wordpress plugin from @xyzscripts and you should too",
						"Twitter Auto Publish  wordpress Plugin from @xyzscripts is awesome",
						"Thanks @xyzscripts for developing such a wonderful Twitter auto publishing wordpress plugin",
						"I was looking for a Twitter publishing plugin like this. Thanks @xyzscripts",
						"Its very easy to use Twitter Auto Publish  wordpress Plugin from @xyzscripts",
						"I installed Twitter Auto Publish from @xyzscripts, it works flawlessly",
						"The Twitter Auto Publish wordpress plugin that i use works terrific", 
						"I am using Twittter Auto Publish wordpress plugin from @xyzscripts and I like it",
						"The Twitter Auto Publish plugin from @xyzscripts is simple and works fine",
						"I've been using this Twitter plugin for a while now and it is really good",
						"Twitter Auto Publish wordpress plugin is a fantastic plugin",
						"Twitter Auto Publish wordpress plugin is easy to use and works great. Thank you!",
						"Good and flexible  Twitter Auto publish plugin especially for beginners",
						"The best Twittter auto publish wordpress plugin I have used ! THANKS @xyzscripts",
						);
$sharelink_text_tw = array_rand($sharelink_text_array_tw, 1);
$sharelink_text_tw = $sharelink_text_array_tw[$sharelink_text_tw];

	
	echo '<div id="tw_notice_td" style="clear:both;width:98%;background: none repeat scroll 0pt 0pt #FBFCC5; border: 1px solid #EAEA09;padding:5px;">
	<p>It looks like you have been enjoying using <a href="https://wordpress.org/plugins/twitter-auto-publish/" target="_blank"> Twitter Auto Publish  </a> plugin from Xyzscripts for atleast 30 days.Would you consider supporting us with the continued development of the plugin using any of the below methods?</p>
	<p>
	<a href="https://wordpress.org/support/view/plugin-reviews/twitter-auto-publish" class="button" style="color:black;text-decoration:none;margin-right:4px;" target="_blank">Rate it 5★\'s on wordpress</a>
	<a href="http://xyzscripts.com/wordpress-plugins/social-media-auto-publish/purchase" class="button" style="color:black;text-decoration:none;margin-right:4px;" target="_blank">Purchase premium version</a>';
	if(get_option('xyz_credit_link')=="0")
		echo '<a class="button xyz_twap_backlink" style="color:black;text-decoration:none;margin-right:4px;" target="_blank">Enable backlink</a>';
	
	echo '<a href="#TB_inline?width=250&height=75&inlineId=show_share_icons_tw" class="button thickbox" style="color:black;text-decoration:none;margin-right:4px;" target="_blank">Share on</a>
	
	<a href="admin.php?page=twitter-auto-publish-settings&twap_notice=hide" class="button" style="color:black;text-decoration:none;margin-right:4px;">Don\'t Show This Again</a>
	</p>
	
	<div id="show_share_icons_tw" style="display: none;">
	<a class="button" style="background-color:#3b5998;color:white;margin-right:4px;margin-left:100px;margin-top: 25px;" href="http://www.facebook.com/sharer/sharer.php?u=http://xyzscripts.com/wordpress-plugins/twitter-auto-publish/" target="_blank">Facebook</a>
	<a class="button" style="background-color:#00aced;color:white;margin-right:4px;margin-left:20px;margin-top: 25px;" href="http://twitter.com/share?url=http://xyzscripts.com/wordpress-plugins/twitter-auto-publish/&text='.$sharelink_text_tw.'" target="_blank">Twitter</a>
	<a class="button" style="background-color:#007bb6;color:white;margin-right:4px;margin-left:20px;margin-top: 25px;" href="http://www.linkedin.com/shareArticle?mini=true&url=http://xyzscripts.com/wordpress-plugins/twitter-auto-publish/" target="_blank">LinkedIn</a>
	<a class="button" style="background-color:#dd4b39;color:white;margin-right:4px;margin-left:20px;margin-top: 25px;" href="https://plus.google.com/share?&hl=en&url=http://xyzscripts.com/wordpress-plugins/twitter-auto-publish/" target="_blank">google+</a>
	</div>
	
	
	
	</div>';
	
	
}
$twap_installed_date = get_option('twap_installed_date');
if ($twap_installed_date=="") {
	$twap_installed_date = time();
}
if($twap_installed_date < ( time() - (30*24*60*60) ))
{
	if (get_option('xyz_twap_dnt_shw_notice') != "hide")
	{
		add_action('admin_notices', 'wp_twap_admin_notice');
	}
}
?>