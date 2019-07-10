<?php
/**
 * Tab Support
 *
 * @package  WPEL
 * @category WordPress Plugin
 * @version  2.3
 * @link     https://www.webfactoryltd.com/
 * @license  Dual licensed under the MIT and GPLv2+ licenses
 *
 * @var array $vars
 *      @option array  "tabs"
 */
?>
<h2><?php _e( 'Support', 'wp-external-links' ); ?></h2>

<h3><?php _e( 'Exclude or include by data-attribute', 'wp-external-links' ) ?></h3>
<p>
    <?php _e( 'The <code>data-wpel-link</code> attribute can be set on links and forces the plugin to treat those links that way.', 'wp-external-links' ); ?>
</p>
<ul>
    <li><?php _e( 'Links with <code>data-wpel-link="internal"</code> will be treated as internal links.', 'wp-external-links' ); ?></li>
    <li><?php _e( 'Links with <code>data-wpel-link="external"</code> will be treated as external links.', 'wp-external-links' ); ?></li>
    <li><?php _e( 'Links with <code>data-wpel-link="exclude"</code> will be treated as excluded links (which have their own settings or will be treated as internal links).', 'wp-external-links' ); ?></li>
    <li><?php _e( 'Links with <code>data-wpel-link="ignore"</code> will be completely ignored by this plugin.', 'wp-external-links' ); ?></li>
</ul>

<h3><?php _e( 'FAQ', 'wp-external-links' ); ?></h3>
<p><?php _e( 'On the <a href="https://wordpress.org/plugins/wp-external-links/faq/" target="_blank">FAQ page</a> you can find some additional tips & trics.', 'wp-external-links' ); ?></p>

<h3><?php _e( 'Reported issues', 'wp-external-links' ); ?></h3>
<p><?php _e( 'When you experience problems using this plugin please look if your problem was <a href="https://wordpress.org/support/plugin/wp-external-links" target="_blank">already reported</a>.', 'wp-external-links' ); ?></p>

<h3><?php _e( 'Send your issue', 'wp-external-links' ); ?></h3>
<p><?php _e( 'If the issue wasn\'t reported yet then you should <a href="https://wordpress.org/support/plugin/wp-external-links#postform" target="_blank">post your problem</a>. <b>Our average response time is 2 hours, and we reply to every message!</b>', 'wp-external-links' ); ?>
    <?php _e( '<br>Make sure you copy/past the technical information displayed below. Without it we can\'t help you.', 'wp-external-links' ); ?>
</p>
<p>
    <button class="button js-wpel-copy"><?php _e( 'Copy Technical Info', 'wp-external-links' ); ?></button>
</p>
<p>
<textarea id="plugin-settings" class="large-text js-wpel-copy-target" rows="8" readonly="readonly">
<?php _e( 'WP url:', 'wp-external-links' ); ?>  <?php bloginfo( 'wpurl' ); ?>

<?php _e( 'WP version:', 'wp-external-links' ); ?>  <?php bloginfo( 'version' ); ?>

<?php _e( 'PHP version:', 'wp-external-links' ); ?>  <?php echo phpversion(); ?>

<?php _e( 'Active Plugins:', 'wp-external-links' ); ?>

<?php
$plugins = get_plugins() ;

foreach ( $plugins as $plugin => $plugin_values ) {
    if ( ! is_plugin_active( $plugin ) ) {
        continue;
    }

    echo ' - '. $plugin_values[ 'Name' ] .', version: '. $plugin_values[ 'Version' ] . "\n";
}
?>

<?php _e( 'WPEL Settings:', 'wp-external-links' ); ?>

array(
<?php
foreach ( $vars[ 'tabs' ] as $tab_key => $values ) {
    if ( ! isset( $values[ 'fields' ] ) ) {
        continue;
    }

    $option_values = $values[ 'fields' ]->get_option_values();
    $option_name = $values[ 'fields' ]->get_setting( 'option_name' );

    echo "'$option_name' => ";
    var_export( $option_values );
    echo ",\n";
}
?>
);
</textarea>
</p>
