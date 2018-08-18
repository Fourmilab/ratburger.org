<?php if (!defined('ABSPATH')) die('No direct access.'); ?>

<?php if (!defined('UPDRAFTPLUS_TEMPORARY_CLONE')) return; ?>

<h3><span class="dashicons dashicons-admin-page"></span><?php _e("Or, create a temporary clone", "updraftplus");?></h3>
<div class="updraft_migrate_widget_module_content">
	<div class="updraft_migrate_widget_temporary_clone_stage1">
	<?php if (is_multisite()) { ?>
		<p><?php echo '<a target="_blank" href="https://updraftplus.com/faqs/how-do-i-migrate-to-a-new-site-location/">'.__('Temporary clones of WordPress multisite installations are not yet supported. See our documentation on how to carry out a normal migration here', 'updraftplus').'.</a>'; ?></p>
	<?php } else { ?>
		<p><?php echo __("To create a temporary clone you must first connect to your UpdraftPlus.com account (and have clone tokens available in that account).", "updraftplus"); ?></p>
		<p><a href="https://updraftplus.com/shop/"><?php echo __("You can add temporary clone tokens to your account here.", "updraftplus"); ?></a></p>
		<?php
			$updraftplus_admin->build_credentials_form('temporary_clone', true, false);
		?>
	</div>
	<?php } ?>
	<div class="updraft_migrate_widget_temporary_clone_stage2" style="display: none;">
	</div>
	<div class="updraft_migrate_widget_temporary_clone_stage3" style="display: none;">
	</div>
</div>