<?php

if (!defined('ABSPATH')) die('No direct access allowed');

class UpdraftPlus_Temporary_Clone_Commands {
	
	/**
	 * Constructor for the class.
	 */
	public function __construct() {
		add_action('updraftplus_restore_completed', array($this, 'updraftplus_restore_completed'));
	}

	/**
	 * This function is called via an action when the restore is completed it will call the mothership to update the complete status of the vps.
	 *
	 * @param array $restore_data - an array of relevant information
	 * @return void
	 */
	public function updraftplus_restore_completed() {
		global $updraftplus;

		if (!defined('UPDRAFTPLUS_USER_ID') || !is_numeric(UPDRAFTPLUS_USER_ID) || !defined('UPDRAFTPLUS_VPS_ID') || !is_numeric(UPDRAFTPLUS_VPS_ID) || !defined('UPDRAFTPLUS_UNIQUE_TOKEN')) {
			error_log("updraftplus_restore_completed called, but no clone information (presumably a user-initiated restore)");
			return;
		}

		$user_id = UPDRAFTPLUS_USER_ID;
		$vps_id = UPDRAFTPLUS_VPS_ID;
		$token = UPDRAFTPLUS_UNIQUE_TOKEN;
		
		$data = array('user_id' => $user_id, 'vps_id' => $vps_id, 'token' => $token);
		$updraftplus->get_updraftplus_clone()->clone_restore_complete($data);
	}
}

if (defined('UPDRAFTPLUS_THIS_IS_CLONE') && UPDRAFTPLUS_THIS_IS_CLONE) {
	$updraftplus_temporary_clone_commands = new UpdraftPlus_Temporary_Clone_Commands();
}
