<?php
/*
Plugin Name: Auto Limit Posts Reloaded
Plugin URI: https://www.thefreewindows.com/16305/download-auto-limit-posts-free-wordpress-plugin-create-automatic-excerpts
Description: Limit your posts automatically, without caring to write the more tag. Options to limit posts by letter, word or paragraph, in home, categories, archive and search pages. Read More links will not include the NoFollow tag. You can also style your Read More links using CSS right in the admin settings or in your main style sheet, even activate a sharing option.
Version: 2.5
Author: TheFreeWindows
Author URI: https://www.thefreewindows.com
*/
add_option('alpr_post_wordcut', 'Wordcut');
add_option('alpr_style', 'border: none; text-decoration: none; letter-spacing: 1px;');
add_option('alpr_post_letters', '255');
add_option('alpr_post_ending', '... ');
add_option('alpr_post_linktext', 'Read More');
add_option('alpr_post_sharetext', 'Share it now!');
add_option('alpr_post_home', 'on');
add_option('alpr_post_category', 'on');
add_option('alpr_post_archive', 'on');
add_option('alpr_share', 'on');
add_option('alpr_post_search', 'on');
add_option('alpr_striptags', 'on');

function alpr_replace_content($content)
{
	// Get data from database
	$alpr_post_wordcut = get_option("alpr_post_wordcut");
	
	$alpr_post_letters = get_option("alpr_post_letters");
	$alpr_post_linktext = get_option("alpr_post_linktext");
	$alpr_post_sharetext = get_option("alpr_post_sharetext");
	$alpr_post_ending = get_option("alpr_post_ending");
	$alpr_style = get_option("alpr_style");
	
	$alpr_post_home = get_option("alpr_post_home");
	$alpr_post_category = get_option("alpr_post_category");
	$alpr_post_archive = get_option("alpr_post_archive");
	$alpr_share = get_option("alpr_share");
	$alpr_post_search = get_option("alpr_post_search");
	$alpr_striptags = get_option("alpr_striptags");

	// If post letters are not set, default is set to 255
	if ($alpr_post_letters == ""){
		$alpr_post_letters = 255;
	}
	if ($alpr_post_wordcut == "Wordcut")
	{
		// Check what options is set
		if ( (is_home() && $alpr_post_home == "on") || (is_category() && $alpr_post_category == "on") || (is_archive() && $alpr_post_archive == "on") || (is_search() && $alpr_post_search == "on") ) {
		
			// Get data to see if more tag is used
			global $post;
			global $content2;
			$ismoretag = explode('<!--',$post->post_content);
			$ismoretag2 = explode('-->', (isset($ismoretag[1]) ? $ismoretag[1] : null));
			
			if ($alpr_striptags == "on") {
				$content2 = "<p>" . preg_replace("~(?:\[/?)[^/\]]+/?\]~s", '', strip_shortcodes(strip_tags($content)));
			}
		
			// Limit the post by wordwarp to check for more tag
			$prev_content = wordwrap($content, $alpr_post_letters, "[lpa]");
			$cuttext = explode ('[lpa]', $prev_content);
			$end_string = substr($cuttext[0], -5);
			$endingp = "";
			
			// Limit the post by wordwarp
			$prev_content2 = wordwrap($content2, $alpr_post_letters, "[lpa]");
			$cuttext2 = explode ('[lpa]', $prev_content2);
			$end_string2 = substr($cuttext2[0], -5);
			$endingp2 = "";
			
			// If end of p-tag is missing create one
			if ($end_string == "</p>\n") {
				$cuttext[0]=substr($cuttext[0],0,(strlen($cuttext[0])-5));
			}
			// Check if more tag is used
			if ($ismoretag2[0] != "more") {
				if ($alpr_striptags == "on") {
					echo $cuttext2[0]; // Add limited post
				}
				else {
					echo $cuttext[0]; // Add limited post
				}
				echo $alpr_post_ending; // Add limited ending
				// Add link if link text exists
				if ($alpr_post_linktext != "" && $alpr_share == "on"){
					echo " <a id='alpr' style='visibility:visible;".$alpr_style.";' href='" .get_permalink(). "'>".$alpr_post_linktext."</a> | <a id='alpr' style='visibility:visible;".$alpr_style.";' target='_blank' href='https://www.socializer.info/share.asp?docurl=" .get_permalink(). "&doctitle=".get_the_title()."'>".$alpr_post_sharetext."</a>" ;
				}
				if ($alpr_post_linktext != "" && $alpr_share != "on"){
					echo " <a id='alpr' style='visibility:visible;".$alpr_style.";' href='" .get_permalink(). "'>".$alpr_post_linktext."</a>" ;
				}
				echo "</p>";
			}
			else {
				return $content;
			}
		}
		else {
			return $content;
		}
	}
	else if ($alpr_post_wordcut == "Lettercut") {
		// Check what options is set
		if ( (is_home() && $alpr_post_home == "on") || (is_category() && $alpr_post_category == "on") || (is_archive() && $alpr_post_archive == "on") || (is_search() && $alpr_post_search == "on") ) {
			
			// Get data to see if more tag is used
			global $post;
			global $content2;
			$ismoretag = explode('<!--',$post->post_content);
		$ismoretag2 = explode('-->', (isset($ismoretag[1]) ? $ismoretag[1] : null));
//			$ismoretag2 = explode('-->', $ismoretag[1]);

	

			
			if ($alpr_striptags == "on") {
				$content2 = "<p>" . preg_replace("~(?:\[/?)[^/\]]+/?\]~s", '', strip_shortcodes(strip_tags($content)));
			}


// Limit the post by letter to check for more tag
			$new_string2 = mb_substr($content2, 0, $alpr_post_letters+3);
			$end_string2 = mb_substr($new_string2, -5);
			$endingp = "";

			// Limit the post by letter
			$new_string = mb_substr($content, 0, $alpr_post_letters+3);
			$end_string = mb_substr($new_string, -5);
			$endingp = "";

			// If end of p-tag is missing create one
			if ($end_string == "</p>\n") {
				$new_string = mb_substr($new_string, 0, (strlen($new_string)-5));
			}


			// Check if more tag is used
			if ($ismoretag2[0] != "more") {
				
				if ($alpr_striptags == "on") {
					echo $new_string2; // Add limited post
				}
				else {
					echo $new_string; // Add limited post
				}
				
				echo $alpr_post_ending; // Add limited ending
				// Add link if link text exists
				if ($alpr_post_linktext != "" && $alpr_share == "on"){
					echo " <a id='alpr' style='visibility:visible;".$alpr_style.";' href='" .get_permalink(). "'>".$alpr_post_linktext."</a> | <a id='alpr' style='visibility:visible;".$alpr_style.";' target='_blank' href='https://www.socializer.info/share.asp?docurl=" .get_permalink(). "&doctitle=".get_the_title()."'>".$alpr_post_sharetext."</a>";
				}
				if ($alpr_post_linktext != "" && $alpr_share != "on"){
					echo " <a id='alpr' style='visibility:visible;".$alpr_style.";' href='" .get_permalink(). "'>".$alpr_post_linktext."</a>" ;
				}
				echo "</p>";
			}
			else {
				return $content;
			}
		}
		else {
			return $content;
		}
	}
	else if ($alpr_post_wordcut == "Paragraphcut") {
		if ( (is_home() && $alpr_post_home == "on") || (is_category() && $alpr_post_category == "on") || (is_archive() && $alpr_post_archive == "on") || (is_search() && $alpr_post_search == "on") ) {
			$paragraphcut = explode('</p>', $content);
			global $post;
			$ismoretag = explode('<!--',$post->post_content);
//			$ismoretag2 = explode('-->', $ismoretag[1]);
			$ismoretag2 = explode('-->', (isset($ismoretag[1]) ? $ismoretag[1] : null));
			
			
			if ($ismoretag2[0] != "more") {
				echo $paragraphcut[0];
				echo $alpr_post_ending;
				if ($alpr_post_linktext != "" && $alpr_share == "on"){
					echo " <a id='alpr' style='visibility:visible;".$alpr_style.";' href='" .get_permalink(). "'>".$alpr_post_linktext."</a> | <a id='alpr' style='visibility:visible;".$alpr_style.";' target='_blank' href='https://www.socializer.info/share.asp?docurl=" .get_permalink(). "&doctitle=".get_the_title()."'>".$alpr_post_sharetext."</a>";
				}
				if ($alpr_post_linktext != "" && $alpr_share != "on"){
					echo " <a id='alpr' style='visibility:visible;".$alpr_style.";' href='" .get_permalink(). "'>".$alpr_post_linktext."</a>" ;
				}
				echo "</p>";
			}
			else {
				return $content;
			}
		}
		else {
			return $content;
		}
	}
	else {
		return $content;
	}
}
add_filter('the_content','alpr_replace_content');

function alpr_admin(){
    if (isset($_POST['submitted'])){
		// Get data from input fields
        if(empty($_POST['alpr_striptags'])){
            $striptags = false;
         } else {
         	$striptags = $_POST['alpr_striptags'];
        }
        if(empty($_POST['alpr_post_wordcut'])){
            $wordcut = false;
         } else {
         	$wordcut = $_POST['alpr_post_wordcut'];
        }
        if(empty($_POST['alpr_post_category'])){
            $category = false;
         } else {
         	$category = $_POST['alpr_post_category'];
        }
        if(empty($_POST['alpr_post_letters'])){
            $letters = false;
         } else {
         	$letters = $_POST['alpr_post_letters'];
        }
        if(empty($_POST['alpr_post_linktext'])){
            $linktext = "Read More";
         } else {
         	$linktext = $_POST['alpr_post_linktext'];
        }
        if(empty($_POST['alpr_post_linktext'])){
            $sharetext = "Share it!";
         } else {
         	$sharetext = $_POST['alpr_post_sharetext'];
        }
        if(empty($_POST['alpr_post_ending'])){
            $ending = " ";
         } else {
         	$ending = $_POST['alpr_post_ending'];
        }
        if(empty($_POST['alpr_style'])){
            $thestyle = " ";
         } else {
         	$thestyle = $_POST['alpr_style'];
        }
        if(empty($_POST['alpr_post_home'])){
            $home = false;
         } else {
         	$home = $_POST['alpr_post_home'];
        }
        if(empty($_POST['alpr_post_archive'])){
            $archive = false;
         } else {
         	$archive = $_POST['alpr_post_archive'];
        }
        if(empty($_POST['alpr_share'])){
            $share = false;
         } else {
         	$share = $_POST['alpr_share'];
        }
        if(empty($_POST['alpr_post_search'])){
            $search = false;
         } else {
         	$search = $_POST['alpr_post_search'];
        }
        
		// Upload / update data to database
		update_option("alpr_post_wordcut", $wordcut);		
		update_option("alpr_post_letters", $letters);
		update_option("alpr_post_linktext", $linktext);
		update_option("alpr_post_sharetext", $sharetext);
		update_option("alpr_post_ending", $ending);
		update_option("alpr_style", $thestyle);		
		update_option("alpr_post_home", $home);
		update_option("alpr_post_category", $category);
		update_option("alpr_post_archive", $archive);
		update_option("alpr_post_search", $search);		
		update_option("alpr_striptags", $striptags);
		update_option("alpr_share", $share);
		
        //Options updated message
        echo "<div id=\"message\" class=\"updated fade\"><p><strong>Settings updated.</strong></p></div>";
    }
	?>

<style>
	input { height:35px;padding:0 8px 5px 9px;-moz-border-radius: 17px;border-radius: 17px;font-size:17px;margin-right:22px;
  }
  
	</style>


    <div class="wrap" style="padding:15px;background:white;font-size:15px;">



    <h2 style="font-weight:normal;font-size:33px;color:#C45A57;padding-top:0;margin-top:25px;margin-left:35px;letter-spacing:1px;">
    	
    	
    	Auto Limit Posts
    	    		<br><br>
    	<span style="margin-left:24px;margin-bottom:22px;"><a style="color:#2b95ff;text-decoration:none;letter-spacing:2px;font-size:15px;" href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=8LEU4AZ8DWET2" target="_blank">You are very welcome to donate for Auto Limit Posts any amount you like</a></span>
    		<br>
    <span style="margin-left:24px;"><a style="color:#2b95ff;text-decoration:none;letter-spacing:2px;font-size:15px;" href="https://www.socializer.info/share.asp?docurl=https://www.thefreewindows.com/16305/download-auto-limit-posts-free-wordpress-plugin-create-automatic-excerpts&doctitle=Auto Limit Posts" target="_blank">Share</a> <i style="font-size:15px;">&</i> <a style="color:#2b95ff;text-decoration:none;letter-spacing:2px;font-size:15px;" target="_blank" href="https://www.socializer.info/follow.asp?docurlf=https://www.facebook.com/TheFreeWindows&docurlt=https://twitter.com/TheFreeWindows&myname=TheFreeWindows">Follow!</a></span>
    	</h2>
  
  
	<?php
	$limitpostby = get_option("alpr_post_wordcut");
	$input_letters = get_option("alpr_post_letters");
	$input_linktext = get_option("alpr_post_linktext");
	$input_sharetext = get_option("alpr_post_sharetext");
	$input_ending = get_option("alpr_post_ending");
	$input_thestyle = get_option("alpr_style");
	$alpr_home = get_option("alpr_post_home");
	$alpr_category = get_option("alpr_post_category");
	$alpr_archive = get_option("alpr_post_archive");
	$alpr_search = get_option("alpr_post_search");
	$alpr_striptags = get_option("alpr_striptags");
	$alpr_share = get_option("alpr_share");
	?>
	
    <form method="post" name="options" target="_self">


    	<br>

	<h3 style="margin-left:33px;font-weight: normal;letter-spacing:2px;">Limit posts by</h3>
	

	<label style="margin-left:43px;"><input type="radio" name="alpr_post_wordcut" value="Lettercut" <?php if ($limitpostby == "Lettercut"){ echo 'checked="checked"'; } ?> onclick="javascript:document.getElementById('letternumber').style.display='';" /> Letter</label>
	
	
			&nbsp; &nbsp; &nbsp; 
			
			<label><input type="radio" name="alpr_post_wordcut" value="Wordcut" <?php if ($limitpostby == "Wordcut"){ echo 'checked="checked"'; } ?> onclick="javascript:document.getElementById('letternumber').style.display='';" /> Word</label>

			&nbsp; &nbsp; &nbsp; 
			
			<label><input type="radio" name="alpr_post_wordcut" value="Paragraphcut" <?php if ($limitpostby == "Paragraphcut"){ echo 'checked="checked"'; } ?> onclick="javascript:document.getElementById('letternumber').style.display='none';" /> First paragraph</label>
			
			&nbsp; &nbsp; &nbsp; 
			
			<label><input type="radio" name="alpr_post_wordcut" value="Nocut" <?php if ($limitpostby == "Nocut"){ echo 'checked="checked"'; } ?> onclick="javascript:document.getElementById('letternumber').style.display='none';" /> None for the moment</label>


	<h3 style="margin-left:33px;margin-top:55px;font-weight: normal;letter-spacing:2px;">Excerpt size & Reference link</h3>
	
	<table style="width:88%;" cellspacing="11">
		<tr id="letternumber" <?php if ($limitpostby=="Paragraphcut" || $limitpostby=="Nocut"){ echo 'style="display: none;"'; }?>>
			<td><input style="color:#C45A57;width:222px;box-shadow: 2px 2px 7px #DBDBDB; -webkit-box-shadow: 2px 2px 7px #DBDBDB; -moz-box-shadow: 2px 2px 7px #DBDBDB;" name="alpr_post_letters" type="text" value="<?php echo $input_letters; ?>" /> <strong>Number of letters</strong> (default is 255)</td>
		</tr>
		<tr>
			<td><input style="width:222px;color:#C45A57;box-shadow: 2px 2px 7px #DBDBDB; -webkit-box-shadow: 2px 2px 7px #DBDBDB; -moz-box-shadow: 2px 2px 7px #DBDBDB;" name="alpr_post_ending" type="text" value="<?php echo $input_ending; ?>" /> <strong>Text continuation</strong></td>
		</tr>
		<tr>
			<td><input style="width:222px;color:#C45A57;box-shadow: 2px 2px 7px #DBDBDB; -webkit-box-shadow: 2px 2px 7px #DBDBDB; -moz-box-shadow: 2px 2px 7px #DBDBDB;" name="alpr_post_linktext" type="text" value="<?php echo $input_linktext; ?>" /> <strong>Link description</strong> (e.g. Read More)</td>
		</tr>
		
		
		<tr>
		
			<td><input style="width:570px;color:#C45A57;box-shadow: 2px 2px 7px #DBDBDB; -webkit-box-shadow: 2px 2px 7px #DBDBDB; -moz-box-shadow: 2px 2px 7px #DBDBDB;margin-bottom:15px;margin-top:12px;" name="alpr_style" type="text" value="<?php echo $input_thestyle; ?>" /><br>
			
			<div style="line-height:188%;"><strong style="margin-left:22px;">Optional Link CSS</strong> (You can also use an <b>#alpr</b> reference in your main style sheet)
		<br> &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp; Default is &mdash; <span style="color:grey;">border: none; text-decoration: none; letter-spacing: 1px;</span></div>
		
		
		<br><br>
		
		<label style="margin-left:22px;"><input type="checkbox" name="alpr_share" <?php if($alpr_share== "on"){ echo 'checked="checked"'; } ?>/> Include also a Share option</label> <br>
		<div style="margin-left:22px;color:grey;margin-top:8px;">(This will let your visitors or yourself share a post on top social networks even when browsing an index / archive page.)</div>
		<br>
		<input style="width:222px;color:#C45A57;box-shadow: 2px 2px 7px #DBDBDB; -webkit-box-shadow: 2px 2px 7px #DBDBDB; -moz-box-shadow: 2px 2px 7px #DBDBDB;" name="alpr_post_sharetext" type="text" value="<?php echo $input_sharetext; ?>" /> <strong>Share description</strong> (e.g. Share it now!)
		
		
			</td>
		</tr>
	</table>


	
	<div style="margin-left:30px;">


	<h3 style="margin-top:55px;font-weight: normal;letter-spacing:2px;">Automatically limit posts in</h3>
	<table style="margin-left:11px;">
		<tr>
			<td><label><input type="checkbox" name="alpr_post_home" <?php if($alpr_home == "on"){ echo 'checked="checked"'; } ?>/> Home &nbsp; &nbsp; &nbsp; </label></td>
			<td><label><input type="checkbox" name="alpr_post_category" <?php if($alpr_category == "on"){ echo 'checked="checked"'; } ?>/> Category &nbsp; &nbsp; &nbsp; </label></td>
			<td><label><input type="checkbox" name="alpr_post_archive" <?php if($alpr_archive == "on"){ echo 'checked="checked"'; } ?>/> Archive &nbsp; &nbsp; &nbsp; </label></td>
			<td><label><input type="checkbox" name="alpr_post_search" <?php if($alpr_search == "on"){ echo 'checked="checked"'; } ?>/> Search</label></td>
		</tr>
	</table>


	
	<h3 style="margin-top:55px;font-weight: normal;letter-spacing:2px;margin-bottom:31px;">Avoid code breaks</h3>

<label style="margin-left:14px;"><input type="checkbox" name="alpr_striptags" <?php if($alpr_striptags == "on"){ echo 'checked="checked"'; } ?>/> <b>Strip tags and shortcodes</b> : : disable images, videos, links in the excerpt</label>


	<ul style="margin-left:25px;">
		<li><span style="color:grey;">(This displays simple text, preventing code errors when posts are cut. Activate this option when you limit by letter / word.)</span></li>
	</ul>

</div>

<br>&nbsp;<br>


<input name="submitted" type="hidden" value="yes" />

<div class="submit" style="background:#F5F5F5;padding:7pt;margin:12px 42px 32px 0;-moz-border-radius: 29px;border-radius: 29px;">

<input style="cursor:pointer;letter-spacing:7px;color:#2b95ff;-moz-border-radius: 22px;border-radius: 22px;border:1px solid white;background:white;padding:1px 19px;font-size:17px;" type="submit" name="Submit" value="<?php _e(' Save your settings '); ?>" />  &raquo;&nbsp; <a style="color:#2b95ff;text-decoration:none;letter-spacing:2px;font-size:17px;" href="https://www.socializer.info/share.asp?docurl=https://www.thefreewindows.com/16305/download-auto-limit-posts-free-wordpress-plugin-create-automatic-excerpts&doctitle=Auto Limit Posts" target="_blank">Share this plugin with your friends</a> &nbsp; &nbsp;  &raquo;&nbsp; <a style="color:#2b95ff;text-decoration:none;letter-spacing:2px;font-size:17px;" target="_blank" href="https://www.socializer.info/follow.asp?docurlf=https://www.facebook.com/TheFreeWindows&docurlt=https://twitter.com/TheFreeWindows&docurlg=https://plus.google.com/107469524242742386932&myname=TheFreeWindows">Follow!</a>

</div>

</form>

<div align="center" style="padding:22px;font-size:17px;letter-spacing:1px;">
	
	&raquo; Don't miss also the <a style="color:#2b95ff;text-decoration:none;font-size:18px;font-variant:small-caps;" target="_blank" href="https://www.thefreewindows.com/5598/socializer-share-wordpress-posts-pages/">Socializer!</a> & the <a style="color:#2b95ff;text-decoration:none;font-size:18px;font-variant:small-caps;" target="_blank" href="https://www.thefreewindows.com/15816/reveal-posts-visitors-share-social-networks/">Social Share Motivator</a> free WordPress plugins</div>


<div align="center" style="text-align:center;margin-top:70px;"> &nbsp;  &nbsp;  &nbsp; <a style="color:silver;text-decoration:none;font-weight:bold;font-size:20pt;letter-spacing:3px;" href="https://www.thefreewindows.com/" target="_blank">TheFreeWindows</a></div>




</div>

<?php } 

function autolimitposts_settings_link($links) { 
  $settings_link = '<a href="options-general.php?page=auto-limit-posts-reloaded/auto-limit-posts.php">Settings</a>'; 
  array_unshift($links, $settings_link); 
  return $links; 
} 
$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'autolimitposts_settings_link' );

function alpr_addpage() {
    add_submenu_page('options-general.php', 'Auto Limit Posts', 'Auto Limit Posts', 'manage_options', __FILE__, 'alpr_admin');
}
add_action('admin_menu', 'alpr_addpage');
?>