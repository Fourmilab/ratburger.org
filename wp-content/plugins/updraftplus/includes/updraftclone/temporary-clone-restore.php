<?php

if (!defined('ABSPATH')) die('No direct access allowed');

class UpdraftPlus_Temporary_Clone_Restore {
	
	/**
	 * Constructor for the class.
	 */
	public function __construct() {
		add_action('updraftplus_temporary_clone_ready_for_restore', array($this, 'clone_ready_for_restore'));
	}

	/**
	 * This function will add a ready_for_restore file in the updraft backup directory to indicate that we are ready to restore the received backup set
	 *
	 * @return void
	 */
	public function clone_ready_for_restore() {
		global $updraftplus;
		
		$updraft_dir = trailingslashit($updraftplus->backups_dir_location());

		touch($updraft_dir . 'ready_for_restore');
	}
}

if (defined('UPDRAFTPLUS_THIS_IS_CLONE')) {
	$updraftplus_temporary_clone_restore = new UpdraftPlus_Temporary_Clone_Restore();
}
