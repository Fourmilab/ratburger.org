<?php
if( !defined('ABSPATH') ){ exit();}
?>
<style>
.buy-button {
	display:block;
	color:white;
    padding:5px 21px;
    background: #545454;
    border: 0;
    border-bottom: 2px solid #545454;
    cursor: pointer;
    -webkit-box-shadow: inset 0 -2px #545454;
    box-shadow: inset 0 -2px #545454;
    text-decoration: none;
    font-size: 14px;
    font-weight: bold;
    border-radius:3px;
}
</style>

<h1 style="visibility: visible;">Twitter Auto Publish (V <?php echo xyz_twap_plugin_get_version(); ?>)</h1>

<div style="width: 99%">
<p style="text-align: justify">
Twitter Auto Publish automatically publishes posts from your blog to your  Twitter pages. It allows you to filter posts based on post-types and categories.
 Twitter Auto Publish is developed and maintained by <a href="http://xyzscripts.com">xyzscripts</a>.</p>

 

<p style="text-align: justify">
	If you would like to have more features , please try <a
		href="http://xyzscripts.com/wordpress-plugins/twitter-auto-publish/features"
		target="_blank">XYZ Twitter Auto Publish</a> which is a premium version of this
	plugin. We have included a quick comparison of the free and premium
	plugins for your reference.
</p>
 </div>
 <table class="xyz-premium-comparison" cellspacing=0 style="width: 99%;">
	<tr style="background-color: #EDEDED">
		<td><h2>Feature group</h2></td>
		<td><h2>Feature</h2></td>
		<td><h2>Free</h2>
		</td>
		<td><h2>Premium</h2></td>
	</tr>
	<!-- Supported Media  -->
	<tr>
		<td rowspan="5"><h4>Supported Media</h4></td>
		<td>Facebook</td>
		<td><img src="<?php echo plugins_url("images/cross.png",__FILE__);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",__FILE__);?>">
		</td>
	</tr>
	
	<tr>
		<td>Twitter</td>
		<td><img src="<?php echo plugins_url("images/tick.png",__FILE__);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",__FILE__);?>">
		</td>
	</tr>
	<tr>
		<td>LinkedIn</td>
		<td><img src="<?php echo plugins_url("images/cross.png",__FILE__);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",__FILE__);?>">
		</td>
	</tr>
	<tr>
		<td>Pinterest<span style="color: #FF8000;font-size: 14px;font-weight: bold;">*</span></td>
		<td><img src="<?php echo plugins_url("images/cross.png",__FILE__);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",__FILE__);?>">
		</td>
	</tr>
	<tr>
		<td>Google Plus<span style="color: #FF8000;font-size: 14px;font-weight: bold;">*</span></td>
		<td><img src="<?php echo plugins_url("images/cross.png",__FILE__);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",__FILE__);?>">
		</td>
	</tr>
	<!-- Posting Options  -->
	<tr>
		<td rowspan="12"><h4>Posting Options</h4></td>
		<td>Publish to facebook profile/pages</td>
		<td><img src="<?php echo plugins_url("images/cross.png",__FILE__);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",__FILE__);?>">
		</td>
	</tr>
	
	<tr>
		<td>Publish to twitter profile</td>
		<td><img src="<?php echo plugins_url("images/tick.png",__FILE__);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",__FILE__);?>">
		</td>
	</tr>
	
	<tr>
		<td>Publish to linkedin profile/company pages</td>
		<td><img src="<?php echo plugins_url("images/cross.png",__FILE__);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",__FILE__);?>">
		</td>
	</tr>
	
	<tr>
		<td>Publish to pinterest boards</td>
		<td><img src="<?php echo plugins_url("images/cross.png",__FILE__);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",__FILE__);?>">
		</td>
	</tr>
	
	<tr>
		<td>Publish to google plus profile/pages</td>
		<td><img src="<?php echo plugins_url("images/cross.png",__FILE__);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",__FILE__);?>">
		</td>
	</tr>
	
	<tr>
		<td>Option to republish existing posts</td>
		<td><img src="<?php echo plugins_url("images/cross.png",__FILE__);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",__FILE__);?>">
		</td>
	</tr>
	
	<tr>
		<td>Publish to multiple social media accounts</td>
		<td><img src="<?php echo plugins_url("images/cross.png",__FILE__);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",__FILE__);?>">
		</td>
	</tr>
	
	<tr>
		<td>Seperate message formats for publishing to multiple social media accounts</td>
		<td><img src="<?php echo plugins_url("images/cross.png",__FILE__);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",__FILE__);?>">
		</td>
	</tr>
	
	<tr>
		<td>Save auto publish settings of individual posts</td>
		<td><img src="<?php echo plugins_url("images/cross.png",__FILE__);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",__FILE__);?>">
		</td>
	</tr>
	
	<tr>
		<td>Hash Tags support for Facebook, Twitter, Linkedin, Pinterest and Google plus</td>
		<td><img src="<?php echo plugins_url("images/cross.png",__FILE__);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",__FILE__);?>">
		</td>
	</tr>
	
	<tr>
		<td>Option to use post tags as hash tags</td>
		<td><img src="<?php echo plugins_url("images/cross.png",__FILE__);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",__FILE__);?>">
		</td>
	</tr>
	
	<tr>
		<td>Enable/Disable SSL peer verification</td>
		<td><img src="<?php echo plugins_url("images/tick.png",__FILE__);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",__FILE__);?>">
		</td>
	</tr>
	
	<!-- Image Options  -->
	
	<tr>
	<td rowspan="5"><h4>Image Options</h4></td>
		<td>Publish images along with post content</td>
		<td><img src="<?php echo plugins_url("images/tick.png",__FILE__);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",__FILE__);?>">
		</td>
	</tr>
	
	
	<tr>
		<td>Separate default image url for publishing to multiple social media accounts</td>
		<td><img src="<?php echo plugins_url("images/cross.png",__FILE__);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",__FILE__);?>">
		</td>
	</tr>
	
		<tr>
		<td>Option to specify preference from featured image, post content, post meta and open graph tags</td>
		<td><img src="<?php echo plugins_url("images/cross.png",__FILE__);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",__FILE__);?>">
		</td>
	</tr>
	
	<tr>
		<td>Publish multiple images to facebook and twitter along with post content</td>
		<td><img src="<?php echo plugins_url("images/cross.png",__FILE__);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",__FILE__);?>">
		</td>
	</tr>
	
	<tr>
		<td>Option to specify multiphoto preference from post content and post meta</td>
		<td><img src="<?php echo plugins_url("images/cross.png",__FILE__);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",__FILE__);?>">
		</td>
	</tr>
	
	<!-- Video Options  -->
	
	<tr>
	<td rowspan="2"><h4>Video Options</h4></td>
		<td>Publish video to facebook and twitter along with post content</td>
		<td><img src="<?php echo plugins_url("images/cross.png",__FILE__);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",__FILE__);?>">
		</td>
	</tr>
	
	<tr>
		<td>Option to specify preference from post content, post meta and open graph tags</td>
		<td><img src="<?php echo plugins_url("images/cross.png",__FILE__);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",__FILE__);?>">
		</td>
	</tr>
	
	<!-- Filter Options  -->
		
	<tr>
	<td rowspan="7"><h4>Filter Options</h4></td>
		<td>Filter posts to publish based on categories</td>
		<td><img src="<?php echo plugins_url("images/tick.png",__FILE__);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",__FILE__);?>">
		</td>
	</tr>
	
	<tr>
		<td>Filter posts to publish based on custom post types</td>
		<td><img src="<?php echo plugins_url("images/tick.png",__FILE__);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",__FILE__);?>">
		</td>
	</tr>
	
	<tr>
		<td>Configuration to enable/disable page publishing</td>
		<td><img src="<?php echo plugins_url("images/tick.png",__FILE__);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",__FILE__);?>">
		</td>
	</tr>
	
	<tr>
		<td>Category filter for individual accounts</td>
		<td><img src="<?php echo plugins_url("images/cross.png",__FILE__);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",__FILE__);?>">
		</td>
	</tr>
	
	<tr>
		<td>Custom post type filter for individual accounts</td>
		<td><img src="<?php echo plugins_url("images/cross.png",__FILE__);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",__FILE__);?>">
		</td>
	</tr>
	
	<tr>
		<td>Enable/Disable page publishing for individual accounts</td>
		<td><img src="<?php echo plugins_url("images/cross.png",__FILE__);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",__FILE__);?>">
		</td>
	</tr>
	
	<tr>
		<td>Override auto publish scheduling for individual accounts</td>
		<td><img src="<?php echo plugins_url("images/cross.png",__FILE__);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",__FILE__);?>">
		</td>
	</tr>
	
	<!-- Scheduling  -->
		
	<tr>
	<td rowspan="3"><h4>Scheduling</h4></td>
		<td>Instantaneous post publishing</td>
		<td><img src="<?php echo plugins_url("images/tick.png",__FILE__);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",__FILE__);?>">
		</td>
	</tr>
	
	<tr>
		<td>Scheduled post publishing using cron</td>
		<td><img src="<?php echo plugins_url("images/cross.png",__FILE__);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",__FILE__);?>">
		</td>
	</tr>

	
	<tr>
		<td>Configurable auto publishing time interval</td>
		<td><img src="<?php echo plugins_url("images/cross.png",__FILE__);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",__FILE__);?>">
		</td>
	</tr>
	
	
	
	
	<!-- Publishing History  -->
	<tr>
		<td rowspan="4"><h4>Publishing History</h4></td>
		<td>View auto publish history</td>
		<td><img src="<?php echo plugins_url("images/cross.png",__FILE__);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",__FILE__);?>">
		</td>
	</tr>
	<tr>
		<td>View auto publish error logs</td>
		<td><img src="<?php echo plugins_url("images/tick.png",__FILE__);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",__FILE__);?>">
		</td>
	</tr>
	
	<tr>
		<td>Option to republish post</td>
		<td><img src="<?php echo plugins_url("images/cross.png",__FILE__);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",__FILE__);?>">
		</td>
	</tr>
	
	<tr>
		<td>Option to reschedule publishing</td>
		<td><img src="<?php echo plugins_url("images/cross.png",__FILE__);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",__FILE__);?>">
		</td>
	</tr>
	
	<!-- Installation and Support -->
	<tr>
		<td rowspan="2"><h4>Installation and Support</h4></td>
		<td>Free Installation</td>
		<td><img src="<?php echo plugins_url("images/tick.png",__FILE__);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",__FILE__);?>">
		</td>
	</tr>
	<tr>
		<td>Privilege customer support</td>
		<td><img src="<?php echo plugins_url("images/cross.png",__FILE__);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",__FILE__);?>">
		</td>
	</tr>
	
	<!-- Addons and Support -->
	<tr>
		<td rowspan="3"><h4>Addon Features</h4></td>
		<td>Advanced Autopublish Scheduler</td>
		<td><img src="<?php echo plugins_url("images/cross.png",__FILE__);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",__FILE__);?>">
		</td>
		</tr>
		<tr>
		<td>URL-Shortener</td>
		<td><img src="<?php echo plugins_url("images/cross.png",__FILE__);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",__FILE__);?>">
		</td>
	</tr>
	<tr>
		<td>Privilege Management</td>
		<td><img src="<?php echo plugins_url("images/cross.png",__FILE__);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",__FILE__);?>">
		</td>
	</tr>
	<tr>
		<td rowspan="2"><h4>Other</h4></td>
		<td>Price</td>
		<td>FREE</td>
		<td>Starts from 39 USD</td>
	</tr>
	<tr>
		<td>Purchase</td>
		<td></td>
		<td style="padding: 2px" ><a target="_blank"href="https://xyzscripts.com/members/product/purchase/XYZWPSMPPRE"  class="buy-button">Buy Now</a>
		</td>
	</tr>
		
</table>
<br/>
<div style="clear: both;"></div>
<span style="color: #FF8000;font-size: 14px;font-weight: bold;"> * </span> Pinterest and Google Plus are added on experimental basis.
<?php 

?>
