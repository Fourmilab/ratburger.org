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
	public function updraftplus_restore_completed($restore_data) {
		global $updraftplus_admin;

		if (empty($restore_data['clone_id']) || empty($restore_data['secret_token'])) {
			error_log("updraftplus_restore_completed called, but no clone information (presumably a user-initiated restore)");
			return;
		}

		$data = array('clone_id' => $restore_data['clone_id'], 'secret_token' => $restore_data['secret_token']);
		$updraftplus_admin->get_updraftplus_clone()->clone_restore_complete($data);
	}
}

if (defined('UPDRAFTPLUS_THIS_IS_CLONE') && UPDRAFTPLUS_THIS_IS_CLONE) {
	$updraftplus_temporary_clone_commands = new UpdraftPlus_Temporary_Clone_Commands();
}
