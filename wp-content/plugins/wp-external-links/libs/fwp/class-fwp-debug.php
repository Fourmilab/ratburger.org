<?php
/**
 * Class FWP_Debug_1x0x0
 *
 * @package  FWP
 * @category WordPress Library
 * @version  1.0.0
 
 * @link     https://www.webfactoryltd.com/
 */
class FWP_Debug_1x0x0 extends WPRun_Base_1x0x0
{

    /**
     * @var array
     */
    private $settings = array(
        'debug_func_name' => 'debug',
        'log_hooks'       => false,
    );

    /**
     * @var array
     */
    private static $benchmarks = array();

    /**
     * Initialize
     * @param array $settings Optional
     */
    protected function init( array $settings = array() )
    {
        $this->settings = wp_parse_args( $settings, $this->settings );

        $this->create_func();

        if ( $this->settings[ 'log_hooks' ] ) {
            register_shutdown_function( $this->get_callback( 'log_hooks' ) );
        }
    }

    /**
     * Create logbal debug function
     * @return void
     */
    private function create_func()
    {
        $func = $this->settings[ 'debug_func_name' ];

        if ( function_exists( $func ) || !is_callable( $func, true ) ) {
            return;
        }
        
    }

    /**
     * @param mixed $entry
     */
    public static function log( $entry, $title = '' )
    {
        $content = '';

        if ( !empty($title) ) {
            $content = $title . ': ';
        }

        $content .= var_export( $entry, true );

        error_log( $content );
    }

    /**
     * Log all hooks being applied
     * @global array $wp_filter
     */
    protected function log_hooks()
    {
        global $wp_filter;

        $hooks = array_keys( $wp_filter );
        self::log( $hooks, 'WP Hooks' );
    }

    /**
     *
     */
    public static function start_benchmark( $label = 'benchmark' )
    {
        self::$benchmarks[ $label ][ 'start' ] = microtime( true );
    }

    /**
     *
     */
    public static function end_benchmark( $label = 'benchmark' )
    {
        $end_time = microtime( true );
        self::$benchmarks[ $label ][ 'end' ] = $end_time;
        $start_time = self::$benchmarks[ $label ][ 'start' ];

        $total_time = $end_time - $start_time;

        self::log( $total_time, $label );

        return $total_time;
    }

}

/*?>*/
