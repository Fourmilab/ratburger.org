<?php
if( !defined('ABSPATH') ){ exit();}
if(get_option('xyz_twap_premium_version_ads')==1){?>
<div id="xyz-wp-twap-premium">

	<div style="float: left; padding: 0 5px">
		<h2 style="vertical-align: middle;">
			<a target="_blank"
				href="http://xyzscripts.com/wordpress-plugins/social-media-auto-publish/features">Fully
				Featured XYZ WP SMAP Premium Plugin</a> - Just 39 USD
		</h2>
	</div>
	<div style="float: left; margin-top: 3px">
		<a target="_blank"
			href="http://xyzscripts.com/members/product/purchase/XYZWPSMPPRE"><img class="hoverImages"
			src="<?php  echo plugins_url("twitter-auto-publish/admin/images/orange_buynow.png"); ?>">
		</a>
	</div>
	<div style="float: left; padding: 0 5px">
	<h2 style="vertical-align: middle;text-shadow: 1px 1px 1px #686868">
			( <a 	href="<?php echo admin_url('admin.php?page=twitter-auto-publish-about');?>">Compare Features</a> ) 
	</h2>		
	</div>
</div>
<?php }?>

<?php 
if($_POST && isset($_POST['xyz_credit_link']))
{
	
	$xyz_credit_link=$_POST['xyz_credit_link'];
	
	update_option('xyz_credit_link', $xyz_credit_link);
	?>
<div class="system_notice_area_style1" id="system_notice_area">
	Settings updated successfully. &nbsp;&nbsp;&nbsp;<span id="system_notice_area_dismiss">Dismiss</span>
</div>
	<?php 
}?>


<?php 

if(get_option('xyz_credit_link')=="0"){
	?>
<div style="float:left;background-color: #FFECB3;border-radius:5px;padding: 0px 5px;margin-top: 10px;border: 1px solid #E0AB1B" id="xyz_backlink_div">

	Please do a favour by enabling backlink to our site. <a class="xyz_twap_backlink" style="cursor: pointer;" >Okay, Enable</a>.
<script type="text/javascript">
jQuery(document).ready(function() {

	jQuery('.xyz_twap_backlink').click(function() {
		var backlink_nonce= '<?php echo wp_create_nonce('backlink');?>';
		var dataString = { 
				action: 'xyz_twap_ajax_backlink', 
				enable: 1 ,
				_wpnonce: backlink_nonce
			};

		jQuery.post(ajaxurl, dataString, function(response) {

			if(response==1)
		       	alert("You do not have sufficient permissions");
		else{
			jQuery('.xyz_twap_backlink').hide();
			jQuery("#xyz_backlink_div").html('Thank you for enabling backlink !');
			jQuery("#xyz_backlink_div").css('background-color', '#D8E8DA');
			jQuery("#xyz_backlink_div").css('border', '1px solid #0F801C');
		}
		});

});
});
</script>
</div>
	<?php 
}



?>


 
<div style="margin-top: 10px">
<table style="float:right; ">
<tr>
<td  style="float:right;">
	<a title="Please help us to keep this plugin free forever by donating a dollar"   class="xyz_twap_link" style="margin-right:12px;"  target="_blank" href="http://xyzscripts.com/donate/1">Donate</a>
</td>
<td style="float:right;">
	<a class="xyz_twap_link"  target="_blank" href="http://help.xyzscripts.com/docs/twitter-auto-publish/faq/">FAQ</a> | 
</td>
<td style="float:right;">
	<a class="xyz_twap_link"  target="_blank" href="http://help.xyzscripts.com/docs/twitter-auto-publish/">Readme</a> | 
</td>
<td style="float:right;">
	<a class="xyz_twap_link"  target="_blank" href="http://xyzscripts.com/wordpress-plugins/twitter-auto-publish/details">About</a> | 
</td>
<td style="float:right;">
	<a class="xyz_twap_link"  target="_blank" href="http://xyzscripts.com">XYZScripts</a> |
</td>

</tr>
</table>
</div>


<div style="clear: both"></div>