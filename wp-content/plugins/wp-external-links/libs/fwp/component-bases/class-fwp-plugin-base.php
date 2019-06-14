<?php
/**
 * Class FWP_Plugin_Base
 *
 * @package  FWP
 * @category WordPress Library
 * @version  1.0.0
 
 * @link     https://www.webfactoryltd.com/
 */
abstract class FWP_Plugin_Base_1x0x0 extends WPRun_Base_1x0x0
{

    /**
     * @var string
     */
    private $plugin_file = null;

    /**
     * @var string
     */
    private $plugin_dir = null;

    /**
     * Initialize plugin
     * @param string $plugin_file
     * @param string $plugin_dir
     */
    protected function init( $plugin_file, $plugin_dir )
    {
        $this->set_plugin_file( $plugin_file );
        $this->set_plugin_dir( $plugin_dir );
    }

    /**
     * Action for "plugins_loaded"
     */
    protected function action_plugins_loaded()
    {
        $plugin_data = get_file_data( $this->plugin_file, array(
            'TextDomain'  => 'Text Domain',
            'DomainPath'  => 'Domain Path',
        ) );

        if ( ! $plugin_data[ 'TextDomain' ] || ! $plugin_data[ 'DomainPath' ] ) {
            return;
        }

        load_plugin_textdomain(
            $plugin_data[ 'TextDomain' ]
            , false
            , $this->get_plugin_dir( $plugin_data[ 'DomainPath' ] )
        );
    }

    /**
     * Action for "admin_action_wpel_dismiss_notice"
     */
    protected function action_admin_action_wpel_dismiss_notice()
    {
      update_option( 'wpel-notice-dismissed-rate', true );

      if ( !empty( $_GET['redirect'] ) ) {
        wp_safe_redirect( $_GET['redirect'] );
      } else {
        wp_safe_redirect( admin_url() );
      }
  
      exit;
    }

    /**
     * @param string $plugin_file
     */
    final protected function set_plugin_file( $plugin_file )
    {
        $this->plugin_file = $plugin_file;
    }

    /**
     * @param string $plugin_dir
     */
    final protected function set_plugin_dir( $plugin_dir )
    {
        $this->plugin_dir = untrailingslashit( $plugin_dir );;
    }

    /**
     * @return string
     */
    final public static function get_plugin_file()
    {
        return self::get_instance()->plugin_file;
    }

    /**
     * @param string $path Optional
     * @return string
     */
    final public static function get_plugin_dir( $path = '' )
    {
        return self::get_instance()->plugin_dir . $path;
    }

}

/*?>*/
