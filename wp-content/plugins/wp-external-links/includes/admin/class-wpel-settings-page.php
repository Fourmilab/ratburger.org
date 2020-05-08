<?php
/**
 * Class WPEL_Settings_Page
 *
 * @package  WPEL
 * @category WordPress Plugin
 * @version  2.3
 * @link     https://www.webfactoryltd.com/
 * @license  Dual licensed under the MIT and GPLv2+ licenses
 */
final class WPEL_Settings_Page extends WPRun_Base_1x0x0
{

    /**
     * @var string
     */
    private $menu_slug = 'wpel-settings-page';

    /**
     * @var string
     */
    private $current_tab = null;

    /**
     * @var array
     */
    private $tabs = array();

    /**
     * @var WPEL_Network_Page
     */
    private $network_page = null;

    /**
     * Initialize
     */
    protected function init( $network_page, array $fields_objects )
    {
        $this->network_page = $network_page;

        $this->tabs = array(
            'external-links' => array(
                'title'     => __( 'External Links', 'wp-external-links' ),
                'icon'      => '<i class="fa fa-external-link-square" aria-hidden="true"></i>',
                'fields'    => $fields_objects[ 'external-links' ],
            ),
            'internal-links' => array(
                'title'     => __( 'Internal Links', 'wp-external-links' ),
                'icon'      => '<i class="fa fa-link" aria-hidden="true"></i>',
                'fields'    => $fields_objects[ 'internal-links' ],
            ),
            'excluded-links' => array(
                'title'     => __( 'Excluded Links', 'wp-external-links' ),
                'icon'      => '<i class="fa fa-share-square" aria-hidden="true"></i>',
                'fields'    => $fields_objects[ 'excluded-links' ],
            ),
            'exceptions' => array(
                'title'     => __( 'Exceptions', 'wp-external-links' ),
                'icon'      => '<i class="fa fa-th-large" aria-hidden="true"></i>',
                'fields'    => $fields_objects[ 'exceptions' ],
            ),
            'admin' => array(
                'title'     => __( 'Admin Settings', 'wp-external-links' ),
                'icon'      => '<i class="fa fa-cogs" aria-hidden="true"></i>',
                'fields'    => $fields_objects[ 'admin' ],
            ),
            'support' => array(
                'title'     => __( 'Support', 'wp-external-links' ),
                'icon'      => '<i class="fa fa-question" aria-hidden="true"></i>',
            ),
        );

        // check excluded links tab available
        if ( $this->get_option_value( 'excludes_as_internal_links', 'exceptions' ) ) {
            unset( $this->tabs[ 'excluded-links' ] );
        }

        // get current tab
        $this->current_tab = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING );

        // set default tab
        if ( ! key_exists( $this->current_tab, $this->tabs ) ) {
            reset( $this->tabs );
            $this->current_tab = key( $this->tabs );
        }

        add_filter('install_plugins_table_api_args_featured', array($this, 'featured_plugins_tab'));
    }

    /**
    * Helper function for adding plugins to featured list
    *
    * @return array
    */
    public function featured_plugins_tab($args)
    {
        add_filter('plugins_api_result', array($this, 'plugins_api_result'), 10, 3);
        return $args;
    }

    /**
    * Add plugins to featured plugins list
    *
    * @return object
    */
    function plugins_api_result($res, $action, $args)
    {
        remove_filter('plugins_api_result', array($this, 'plugins_api_result'), 10, 3);

        $res = self::add_plugin_featured('wp-force-ssl', $res);
        $res = self::add_plugin_featured('sticky-menu-or-anything-on-scroll', $res);
        $res = self::add_plugin_featured('eps-301-redirects', $res);
        $res = self::add_plugin_featured('simple-author-box', $res);

        return $res;
    } // plugins_api_result

    /**
    * Add single plugin to featured list
    *
    * @return object
    */
    public function add_plugin_featured($plugin_slug, $res)
    {
      // check if plugin is already on the list
      if (!empty($res->plugins) && is_array($res->plugins)) {
        foreach ($res->plugins as $plugin) {
          if (is_object($plugin) && !empty($plugin->slug) && $plugin->slug == $plugin_slug) {
            return $res;
          }
        } // foreach
      }

      if ($plugin_info = get_transient('wf-plugin-info-' . $plugin_slug)) {
        array_unshift($res->plugins, $plugin_info);
      } else {
        $plugin_info = plugins_api('plugin_information', array(
            'slug'   => $plugin_slug,
            'is_ssl' => is_ssl(),
            'fields' => array(
            'banners'           => true,
            'reviews'           => true,
            'downloaded'        => true,
            'active_installs'   => true,
            'icons'             => true,
            'short_description' => true,
            )
        ));
        if (!is_wp_error($plugin_info)) {
          $res->plugins = array_merge(array($plugin_info), $res->plugins);
          set_transient('wf-plugin-info-' . $plugin_slug, $plugin_info, DAY_IN_SECONDS * 7);
        }
      }

      return $res;
    } // add_plugin_featured

    /**
     * Get option value
     * @param string $key
     * @param string $type
     * @return string
     * @triggers E_USER_NOTICE Option value cannot be found
     */
    public function get_option_value( $key, $type = null )
    {
        if ( null === $type ) {
            foreach ( $this->tabs as $tab_key => $values ) {
                if ( ! isset( $values[ 'fields' ] ) ) {
                    continue;
                }

                $option_values = $values[ 'fields' ]->get_option_values();

                if ( ! isset( $option_values[ $key ] ) ) {
                    continue;
                }

                return $option_values[ $key ];
            }
        } else if ( isset( $this->tabs[ $type ][ 'fields' ] ) ) {
            $option_values = $this->tabs[ $type ][ 'fields' ]->get_option_values();
            return @$option_values[ $key ];
        }

        trigger_error( 'Option value "'. $key .'" cannot be found.' );
    }

    /**
     * Action for "admin_menu"
     */
    protected function action_admin_menu()
    {
        $capability = $this->network_page->get_option_value( 'capability' );

        $own_admin_menu = $this->get_option_value( 'own_admin_menu', 'admin' );

        if ( '1' === $own_admin_menu ) {
            $this->page_hook = add_menu_page(
                __( 'WP External Links' , 'wp-external-links' )          // page title
                , __( 'External Links' , 'wp-external-links' )           // menu title
                , $capability                               // capability
                , $this->menu_slug                          // id
                , $this->get_callback( 'show_admin_page' )  // callback
                , 'none'                                    // icon
                , null                                      // position
            );
        } else {
            $this->page_hook = add_options_page(
                __( 'WP External Links' , 'wp-external-links' )          // page title
                , __( 'External Links' , 'wp-external-links' )           // menu title
                , $capability                               // capability
                , $this->menu_slug                          // id
                , $this->get_callback( 'show_admin_page' )  // callback
            );
        }

        add_action( 'load-'. $this->page_hook, $this->get_callback( 'add_help_tabs' ) );
    }

    /**
     * Set default option values for new created sites
     * @param integer $blog_id
     */
    protected function action_wpmu_new_blog( $blog_id )
    {
        $default_site_id = $this->network_page->get_option_value( 'default_settings_site' );

        foreach ( $this->tabs as $tab_key => $values ) {
            if ( ! isset( $values[ 'fields' ] ) ) {
                continue;
            }

            $option_name = $values[ 'fields' ]->get_setting( 'option_name' );

            $default_option_values = get_blog_option( $default_site_id, $option_name, array() );
            update_blog_option( $blog_id, $option_name, $default_option_values );
        }
    }

    /**
     * Action for "admin_enqueue_scripts"
     */
    protected function action_admin_enqueue_scripts()
    {
        $current_screen = get_current_screen();
        if($current_screen->id == 'toplevel_page_wpel-settings-page' || $current_screen->id == 'settings_page_wpel-settings-page'){
            wp_enqueue_style( 'font-awesome' );
            wp_enqueue_style( 'wpel-admin-style' );
            wp_enqueue_script( 'wpel-admin-script' );
        } 
        
        wp_enqueue_style( 'wpel-admin-global-style' );
    }

    /**
     * Show Admin Page
     */
    protected function show_admin_page()
    {
        $template_file = WPEL_Plugin::get_plugin_dir( '/templates/settings-page/main.php' );
        $page = $this->get_option_value( 'own_admin_menu' ) ? 'admin.php' : 'options-general.php';
        $page_url = admin_url() . $page .'?page='. $this->menu_slug;

        $template_vars = array(
            'tabs'              => $this->tabs,
            'current_tab'       => $this->current_tab,
            'page_url'          => $page_url,
            'menu_slug'         => $this->menu_slug,
            'own_admin_menu'    => $this->get_option_value( 'own_admin_menu', 'admin' ),
        );

        $this->show_template( $template_file, $template_vars );
    }

    /**
     * Add help tabs
     */
    protected function add_help_tabs()
    {
        $screen = get_current_screen();
        return;

        $screen->add_help_tab( array(
            'id'        => 'under-construction',
            'title'     => __( 'Under Construction', 'wp-external-links' ),
            'callback'  => $this->get_callback( 'show_help_tab' ),
        ) );
        $screen->add_help_tab( array(
            'id'        => 'data-attributes',
            'title'     => __( 'Data Attributes', 'wp-external-links' ),
            'callback'  => $this->get_callback( 'show_help_tab' ),
        ) );
    }

    /**
     * @param WP_Screen $screen
     * @param array     $args
     */
    protected function show_help_tab( $screen, array $args )
    {
        $template_file = WPEL_Plugin::get_plugin_dir( '/templates/settings-page/help-tabs/'. $args[ 'id' ] .'.php' );
        $this->show_template( $template_file );
    }

}

/*?>*/
