<?php
/**
 * Tab Nav
 *
 * @package  WPEL
 * @category WordPress Plugin
 * @version  2.3
 * @link     https://www.webfactoryltd.com/
 * @license  Dual licensed under the MIT and GPLv2+ licenses
 *
 * @var array $vars
 *      @option array  "tabs"
 *      @option string "current_tab"
 *      @option string "page_url"
 */
$set_tab_active_class = function ( $tab ) use ( $vars ) {
    if ( $tab === $vars[ 'current_tab' ] ) {
        echo ' nav-tab-active';
    }
};

$first_install = get_option( 'wpel-first-install' );
$dismiss_url = add_query_arg(array('action' => 'wpel_dismiss_notice', 'notice' => 'rate', 'redirect' => urlencode($_SERVER['REQUEST_URI'])), admin_url('admin.php'));

if (false == get_option( 'wpel-notice-dismissed-rate', false ) && current_time( 'timestamp' ) - $first_install > ( HOUR_IN_SECONDS / 4 ) ) {
  echo '<div id="rating-notice" class="notice notice-info"> 
  <p><strong>Help us keep External Links <u>free &amp; maintained</u></strong><br>By taking a minute to rate the plugin you\'ll help us keep it free &amp; maintained. Thank you 👋</p>
  <p><a href="https://wordpress.org/support/plugin/wp-external-links/reviews/#new-post" target="_blank" class="button button-primary">Rate the plugin 👍</a> &nbsp;&nbsp; <a href="' . $dismiss_url . '">I\'ve already rated it</a></p>
  </div>';
}
?>
<h2 class="nav-tab-wrapper">
    <?php foreach ( $vars[ 'tabs' ] as $tab_key => $tab_values ): ?>
        <a class="nav-tab<?php $set_tab_active_class( $tab_key ); ?> nav-tab-<?php echo $tab_key; ?>" href="<?php echo $vars[ 'page_url' ]; ?>&tab=<?php echo $tab_key; ?>">
            <?php echo $tab_values[ 'icon' ]; ?> <?php echo $tab_values[ 'title' ]; ?>
        </a>
    <?php endforeach; ?>
</h2>
