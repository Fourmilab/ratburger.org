<?php

if (!defined('ABSPATH')) die('No direct access allowed');

class UpdraftPlus_Temporary_Clone_Dash_Notice {
	
	/**
	 * Constructor for the class.
	 */
	public function __construct() {
		add_action('updraftplus_temporary_clone_refresh_connection', array($this, 'refresh_connection'));
		add_action('wp_ajax_updraftplus_dash_notice_ajax', array($this, 'updraftplus_dash_notice_ajax'));
		add_action('all_admin_notices', array($this, 'all_admin_notices_dashboard_notice'));
		
		if (!wp_next_scheduled('updraftplus_temporary_clone_refresh_connection')) {
			wp_schedule_event(time(), 'twicedaily', 'updraftplus_temporary_clone_refresh_connection');
		}

		if ('' == get_option('updraftplus_clone_scheduled_removal', '')) {
			$this->refresh_connection();
		}
	}

	/**
	 * This function will add a dashboard notice to every page, that shows the user when their clone will expire and directs them to UpdraftPlus.com to extend their clones life.
	 *
	 * @return void
	 */
	public function all_admin_notices_dashboard_notice() {
		$date = strtotime(get_site_option('updraftplus_clone_scheduled_removal', ''));
		if ('' == $date) {
			$pretty_date = __('Unable to get expiry date', 'updraftplus');
		} else {
			$pretty_date = get_date_from_gmt(gmdate('Y-m-d H:i:s', (int) $date), 'M d, Y G:i');
		}
		?>
		<div id="updraftplus_temporary_clone-dashnotice" class="updated">
			<div style="float:right;"><a href="#" onclick="jQuery.post('<?php echo admin_url('admin-ajax.php'); ?>', {action: 'updraftplus_dash_notice_ajax', subaction: 'refresh_connection', nonce: '<?php echo wp_create_nonce('updraftplus_refresh_connection');?>' }, function() { location.reload(); });"><?php _e('Refresh connection', 'updraftplus'); ?></a></div>
			<h1><?php _e('Welcome to your UpdraftPlus temporary clone', 'updraftplus'); ?></h1>
			<p><?php echo __('Your clone will expire:', 'updraftplus') . ' ' . $pretty_date; ?></p>
			<p><?php _e('You can extend the life of this clone at updraftplus.com, note that each full week you extend by will cost 1 token.', 'updraftplus'); ?></p>
			<a href="https://updraftplus.com/my-account/clones/"><?php _e('Extend life', 'updraftplus'); ?></a>
		</div>
		<?php
	}

	/**
	 * This function will perform security checks before allowing the ajax calls for the UpdraftPlus clone VPS mu-plugin be processed.
	 *
	 * @return void
	 */
	public function updraftplus_dash_notice_ajax() {

		if (is_user_logged_in() && current_user_can('manage_options')) {
			$this->process_dash_notice_ajax();
		} else {
			return;
		}
	}

	/**
	 * This function will handle the ajax calls for the UpdraftPlus clone notice mu-plugin.
	 *
	 * @return void
	 */
	public function process_dash_notice_ajax() {
		$return = array('code' => 'fail', 'data' => '');

		if (!isset($_POST['subaction'])) {
			$return['code'] = 'error';
			$return['data'] = 'Missing subaction';
			echo json_encode($return);
			die();
		}

		if ('refresh_connection' === $_POST['subaction']) {
			check_ajax_referer('updraftplus_refresh_connection', 'nonce');

			$result = $this->refresh_connection();

			if ($result) {
				$return['code'] = 'success';
				$return['data'] = $result;
			} else {
				$return['code'] = 'error';
				$return['data'] = $result;
			}

			echo json_encode($return);
			die();
		} else {
			$return['code'] = 'error';
			$return['data'] = 'Unknown action';
			echo json_encode($return);
			die();
		}
	}

	/**
	 * This function will refresh the stored clones expire date by calling UpdraftPlus.com and getting the latest value.
	 * Note this function needs two defines to work UPDRAFTPLUS_USER_ID and UPDRAFTPLUS_VPS_ID.
	 *
	 * @return void
	 */
	public function refresh_connection() {

		if (!defined('UPDRAFTPLUS_USER_ID') || !is_integer(UPDRAFTPLUS_USER_ID) || !defined('UPDRAFTPLUS_VPS_ID') || !is_integer(UPDRAFTPLUS_VPS_ID)) {
			return array('code' => 'error', 'data' => 'No user or VPS ID found');
		}

		if (!defined('UPDRAFTPLUS_UNIQUE_TOKEN')) return array('code' => 'error', 'data' => 'No unique token found');

		$user_id = UPDRAFTPLUS_USER_ID;
		$vps_id = UPDRAFTPLUS_VPS_ID;
		$token = UPDRAFTPLUS_UNIQUE_TOKEN;

		$args = array(
			'user_id' => $user_id,
			'vps_id' => $vps_id,
			'token' => $token
		);

		$result = wp_remote_post('https://updraftplus.com/plugin-info/?udm_action=updraftplus_temporary_clone_vps_list', array(
			'timeout' => 60,
			'headers' => apply_filters('updraftplus_auth_headers', ''),
			'body' => $args
		));

		$data = json_decode(wp_remote_retrieve_body($result), true);
		$data = $data['data'];

		if (empty($data['scheduled_removal'])) return array('code' => 'error', 'data' => 'No scheduled removal date found');
		
		update_site_option('updraftplus_clone_scheduled_removal', $data['scheduled_removal']);

		return array('code' => 'success', 'data' => $data['scheduled_removal']);
	}
}

if (defined('UPDRAFTPLUS_THIS_IS_CLONE') && UPDRAFTPLUS_THIS_IS_CLONE) {
	$updraftplus_temporary_clone_dash_notice = new UpdraftPlus_Temporary_Clone_Dash_Notice();
}