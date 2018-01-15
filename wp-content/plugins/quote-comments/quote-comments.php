<?php
/*
Plugin Name: Quote Comments
Plugin URI: https://github.com/metodiew/Quote-Comments
Description: Creates a little quote icon in comment boxes which, when clicked, copies that comment to the comment box wrapped in blockquotes.
Version: 2.2
Author: Stanko Metodiev
Author URI: https://metodiew.com
*/

/**
 * @TODO
 * 
 * This is the real TO DO list:
 * - fix all notifications, notes, issues and errors
 * - improve the code base
 */

 
 /**
  * That's the previous @TODO:
  * 
	- phase out "get_comment_time" option
	- clean up remaining functions for reply
	- improve reply layout
	- recode JS
  */

load_plugin_textdomain('quote-comments', NULL, dirname(plugin_basename(__FILE__)) . "/languages");

// Add a define variable, we'll need it later :)
if ( ! defined( 'QUOTE_COMMENTS_VERSION' ) ) {
	define( 'QUOTE_COMMENTS_VERSION', '2.2' );
}

function quote_scripts () {

	if ( function_exists('plugin_url') )
		$plugin_url = plugin_url();
	else
		$plugin_url = get_option('siteurl') . '/wp-content/plugins/' . plugin_basename(dirname(__FILE__));

	wp_register_script('quote_comments_js', ($plugin_url . '/quote-comments.js'), false, '1.0');
	wp_enqueue_script('quote_comments_js');

}
if (!is_admin()) {
	add_action('init', 'quote_scripts');
}






function add_quote_button($output) {


	global $user_ID;
	if (get_option('comment_registration') && !$user_ID) {
		
		return $output;
		
	} else if (!is_feed() && comments_open()) {

		$commentID = get_comment_id();

		if (function_exists('mcecomment_init')) {
			$mce = ", true";
		} else {
			$mce = ", false";
		}

		// quote link
		$button = "";
		
		// fixme close if using "get_comment_time"
		//if (get_option('quote_comments_pluginhook') == "get_comment_time") {
			$button .= '</a>';
		//}
		
		$button .= '&nbsp;&nbsp;';
		$button .= '<span id="name'.get_comment_ID().'" style="display: none;">'.get_comment_author().'</span>';
		$button .= '<a class="comment_quote_link" ';
		$button .= 'href="javascript:void(null)" ';
		$button .= 'title="' . __('Click here or select text to quote comment', 'quote-comments'). '" ';
		
		if( get_option('quote_comments_author') == true ) {
			$button .= 'onmousedown="quote(\'' . get_comment_ID() .'\', document.getElementById(\'name'.get_comment_ID().'\').innerHTML, \'comment\',\'div-comment-'. get_comment_ID() .'\''. $mce .');';
		} else {
			$button .= 'onmousedown="quote(\'' . get_comment_ID() .'\', null, \'comment\',\'div-comment-'. get_comment_ID() .'\''. $mce .');';
		}
		
		$button .= 'try { addComment.moveForm(\'div-comment-'.get_comment_ID().'\', \''.get_comment_ID().'\', \'respond\', \''.get_the_ID().'\'); } catch(e) {}; ';
		$button .= 'return false;">';
		$button .= "" . get_option('quote_comments_title') . "";
		
		
		// reply link
		if (get_option('quote_comments_replylink') == true) {
			$button .= '</a>&nbsp;&nbsp;';
			$button .= '<a class="comment_reply_link" href="javascript:void(null)" ';
			$button .= 'title="' . __('Click here to respond to author', 'quote-comments'). '" ';
			$button .= 'onmousedown="inlinereply(\'' . get_comment_ID() .'\', document.getElementById(\'name'.get_comment_ID().'\').innerHTML, \'comment\',\'div-comment-'. get_comment_ID() .'\''. $mce .');';
			$button .= 'try { addComment.moveForm(\'div-comment-'.get_comment_ID().'\', \''.get_comment_ID().'\', \'respond\', \''.get_the_ID().'\'); } catch(e) {}; ';
			$button .= 'return false;">';
			$button .= "" . get_option('quote_comments_replytitle') . "";
		}
		
		
		// close anchor link if body text (if using get comment time, this </a> is already here due to a bug)
		if (get_option('quote_comments_pluginhook') == "get_comment_text") {
			$button .= "</a>";
		}


		// output
		if (comments_open() && have_comments() && get_comment_type() != "pingback" && get_comment_type() != "trackback") {
			//echo ($output . $button);
			return ($output . $button);
		}

	
	} else {
	
		//echo ($output . $button);
		return ($output . $button);
	
	}

}

// this function to be phased out with "get_comment_time"
function add_quote_button_filter($output) {

	global $user_ID;
	if (get_option('comment_registration') && !$user_ID) {
		
		return $output;
		
	} else if (!is_feed() && comments_open()) {

		$commentID = get_comment_id();

		if (function_exists('mcecomment_init')) {
			$mce = ", true";
		} else {
			$mce = ", false";
		}

		// quote link
		$button = "";
		$button .= '</a>&nbsp;&nbsp;';
		$button .= '<span id="name'.get_comment_ID().'" style="display: none;">'.get_comment_author().'</span>';
		$button .= '<a class="comment_quote_link" ';
		$button .= 'href="javascript:void(null)" ';
		$button .= 'title="' . __('Click here or select text to quote comment', 'quote-comments'). '" ';
		
		if( get_option('quote_comments_author') == true ) {
			$button .= 'onmousedown="quote(\'' . get_comment_ID() .'\', document.getElementById(\'name'.get_comment_ID().'\').innerHTML, \'comment\',\'div-comment-'. get_comment_ID() .'\''. $mce .');';
		} else {
			$button .= 'onmousedown="quote(\'' . get_comment_ID() .'\', null, \'comment\',\'div-comment-'. get_comment_ID() .'\''. $mce .');';
		}
		
		$button .= 'try { addComment.moveForm(\'div-comment-'.get_comment_ID().'\', \''.get_comment_ID().'\', \'respond\', \''.get_the_ID().'\'); } catch(e) {}; ';
		$button .= 'return false;">';
		$button .= "" . get_option('quote_comments_title') . "";
		
		
		// reply link
		if (get_option('quote_comments_replylink') == true) {
			$button .= '</a>&nbsp;&nbsp;';
			$button .= '<a class="comment_reply_link" href="javascript:void(null)" ';
			$button .= 'title="' . __('Click here to respond to author', 'quote-comments'). '" ';
			$button .= 'onmousedown="inlinereply(\'' . get_comment_ID() .'\', document.getElementById(\'name'.get_comment_ID().'\').innerHTML, \'comment\',\'div-comment-'. get_comment_ID() .'\''. $mce .');';
			$button .= 'try { addComment.moveForm(\'div-comment-'.get_comment_ID().'\', \''.get_comment_ID().'\', \'respond\', \''.get_the_ID().'\'); } catch(e) {}; ';
			$button .= 'return false;">';
			$button .= "" . get_option('quote_comments_replytitle') . "";
		}
		
		// close anchor link if body text
		if (get_option('quote_comments_pluginhook') == "get_comment_text") {
			$button .= "</a>";
		}



		if (comments_open() && have_comments() && get_comment_type() != "pingback" && get_comment_type() != "trackback") {
			return($output . $button);
		}

	
	} else {
	
		return($output . $button);
	
	}



}




if (get_option('quote_comments_pluginhook') == 'get_comment_time') {
	if (!is_admin()) {
		add_filter('get_comment_time', 'add_quote_button_filter');
	}
} else {
	if (!is_admin()) {
		//add_action('get_comment_text', 'add_quote_button');
		add_filter('get_comment_text', 'add_quote_button');
	}
}




function add_quote_tags($output) {
	

	global $user_ID;
	if (get_option('comment_registration') && !$user_ID) {
		
		return $output;
		
	} else if (!is_feed() && comments_open()) {
	
		return "\n<div id='q-".get_comment_ID()."'>\n\n\n" . $output . "\n\n\n</div>\n";
	
	} else {
	
		return $output;
		
	}


/*
	global $user_ID;
	if (get_option('comment_registration') && !$user_ID) {
		
		echo $output;
		
	} else if (!is_feed() && comments_open()) {
	
	    echo "\n<div id='q-".get_comment_ID()."'>\n\n\n" . $output . "\n\n\n</div>\n";
	
	} else {
	
		echo $output;
		
	}
*/
	
}
if (!is_admin()) {
	//add_filter('get_comment_text', 'add_quote_tags');
	add_filter('get_comment_text', 'add_quote_tags', 1);
}












/**
 * Options Page
 */


// Options
$qc_themename = "Quote Comments";
$qc_shortname = "quote_comments";


$qc_options = array (

	array(	"name" => __('Quote-link title?','quote-comments'),
		//"desc" => __('Title of comment link.','quote-comments'),
		"id" => $qc_shortname."_title",
		"std" => "(Quote)",
		"type" => "text"),

	array(	"name" => __('Show author in quote?','quote-comments'),
		"desc" => __('Show authors','quote-comments'),
		"id" => $qc_shortname."_author",
		"std" => true,
		"type" => "checkbox"),

	array(	"name" => __('Show reply link?','quote-comments'),
		"desc" => __('Show reply link','quote-comments'),
		"id" => $qc_shortname."_replylink",
		"std" => false,
		"type" => "checkbox"),

	array(	"name" => __('Reply-link title?','quote-comments'),
		//"desc" => __('Title of comment link.','quote-comments'),
		"id" => $qc_shortname."_replytitle",
		"std" => "(Reply)",
		"type" => "text"),

	array(	"name" => __('Insert Quote link using which hook?','quote-comments'),
		"desc" => __('Which plugin hook should be used to insert the quote link?','quote-comments'),
		"id" => $qc_shortname."_pluginhook",
		"std" => 'get_comment_text',
		"type" => "radio",
		"options" => array( 'get_comment_time' => "<code>get_comment_time</code> (places the link close to the authors name)",
							'get_comment_text' => "<code>get_comment_text</code> (places the link after the comment body text -- most compatible)") ),


);





function quotecomments_add_admin() {

	global $qc_themename, $qc_shortname, $qc_options, $blog_id;

	if ( ! empty( $_GET['page'] ) && $_GET['page'] == basename(__FILE__) ) {
    
		if ( ! empty( $_REQUEST['action'] ) && 'save' == $_REQUEST['action'] ) {

			// update options
			foreach ($qc_options as $value) {
				update_option( $value['id'], $_REQUEST[ $value['id'] ] ); }

			foreach ($qc_options as $value) {
				if( isset( $_REQUEST[ $value['id'] ] ) ) { update_option( $value['id'], $_REQUEST[ $value['id'] ]  ); } else { delete_option( $value['id'] ); } }

			header("Location: options-general.php?page=quote-comments.php&saved=true");
			die;

		}
	}

	// add options page
	add_options_page($qc_themename, $qc_themename, 'manage_options', basename(__FILE__), 'quotecomments_admin');
	//add_options_page( $page_title, $menu_title, $capability, $menu_slug, $function);

}

function quotecomments_admin() {

	global $qc_themename, $qc_shortname, $qc_options;

	if (! empty( $_REQUEST['saved'] ) ) {
		echo '<div id="message" class="updated fade"><p><strong>'.$qc_themename.' '.__('settings saved.','quote-comments').'</strong></p></div>';
	}


	// Show options
?>
<div class="wrap">
<?php if ( function_exists('screen_icon') ) screen_icon(); ?>
<h2><?php echo $qc_themename; _e(': General Options', 'quote-comments'); ?></h2>

<form method="post" action="">

	<p class="submit">
		<input class="button-primary" name="save" type="submit" value="<?php _e('Save changes','quote-comments'); ?>" />    
		<input type="hidden" name="action" value="save" />
	</p>


	<?php // Smart options ?>
	<table class="form-table">

<?php foreach ($qc_options as $value) { 
	
	switch ( $value['type'] ) {
		case 'text':
		?>
		<tr valign="top"> 
			<th scope="row"><label for="<?php echo $value['id']; ?>"><?php echo __($value['name'],'quote-comments'); ?></label></th>
			<td>
				<input name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" value="<?php if ( get_option( $value['id'] ) != "") { echo get_option( $value['id'] ); } else { echo $value['std']; } ?>" />
				<?php 
				if ( ! empty( $value['desc'] ) ) {
					_e($value['desc'],'quote-comments');
				}
				?>

			</td>
		</tr>
		<?php
		break;
		
		case 'select':
		?>
		<tr valign="top">
			<th scope="row"><label for="<?php echo $value['id']; ?>"><?php echo __($value['name'],'quote-comments'); ?></label></th>
				<td>
					<select name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>">
					<?php foreach ($value['options'] as $option) { ?>
					<option<?php if ( get_option( $value['id'] ) == $option) { echo ' selected="selected"'; } elseif ($option == $value['std']) { echo ' selected="selected"'; } ?>><?php echo $option; ?></option>
					<?php } ?>
				</select>
			</td>
		</tr>
		<?php
		break;
		
		case 'textarea':
		$ta_options = $value['options'];
		?>
		<tr valign="top"> 
			<th scope="row"><label for="<?php echo $value['id']; ?>"><?php echo __($value['name'],'quote-comments'); ?></label></th>
			<td><textarea name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" cols="<?php echo $ta_options['cols']; ?>" rows="<?php echo $ta_options['rows']; ?>"><?php 
				if( get_option($value['id']) != "") {
						echo __(stripslashes(get_option($value['id'])),'quote-comments');
					}else{
						echo __($value['std'],'quote-comments');
				}?></textarea><br /><?php echo __($value['desc'],'quote-comments'); ?></td>
		</tr>
		<?php
		break;

		case 'radio':
		?>
		<tr valign="top"> 
			<th scope="row"><?php echo __($value['name'],'quote-comments'); ?></th>
			<td>
				<?php foreach ($value['options'] as $key=>$option) { 
				$radio_setting = get_option($value['id']);
				if($radio_setting != ''){
					if ($key == get_option($value['id']) ) {
						$checked = "checked=\"checked\"";
						} else {
							$checked = "";
						}
				}else{
					if($key == $value['std']){
						$checked = "checked=\"checked\"";
					}else{
						$checked = "";
					}
				}?>
				<input type="radio" name="<?php echo $value['id']; ?>" id="<?php echo $value['id'] . $key; ?>" value="<?php echo $key; ?>" <?php echo $checked; ?> /><label for="<?php echo $value['id'] . $key; ?>"><?php echo $option; ?></label><br />
				<?php } ?>
			</td>
		</tr>
		<?php
		break;
		
		case 'checkbox':
		?>
		<tr valign="top"> 
			<th scope="row"><?php echo __($value['name'],'quote-comments'); ?></th>
			<td>
				<?php
					if(get_option($value['id'])){
						$checked = "checked=\"checked\"";
					}else{
						$checked = "";
					}
				?>
				<input type="checkbox" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" value="true" <?php echo $checked; ?> />
				<label for="<?php echo $value['id']; ?>"><?php echo __($value['desc'],'quote-comments'); ?></label>
			</td>
		</tr>
		<?php
		break;

		default:

		break;
	}
}
?>

	</table>
	
	

	<p class="submit">
		<input class="button-primary" name="save" type="submit" value="<?php _e('Save changes','quote-comments'); ?>" />    
		<input type="hidden" name="action" value="save" />
	</p>
	
</form>

</div><?php //.wrap ?>
<?php
}

add_action('admin_menu' , 'quotecomments_add_admin'); 



?>